<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends CI_Controller
{
	public static $maxFailNum = 2;
	public static $expireTime = 604800;
	public static $updateTime = 3600;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("spider_model");
		$this->load->model("tool_model");
	}
    public function Items()
    {
    	
    	$data = $this->spider_model->getTaskList();
        $this->load->view('incs/header');
        $this->load->view('incs/menu');

        $this->load->view('task_list',$data);

        $this->load->view('incs/footer');
    }
    
    public function redo()
    {
    	$task_id = trim($_POST["task_id"]);
    	$table = "spider_fail_task";
    	$set = "now_fail=1,state=1";
    	$where = "task_id = {$task_id}";
    	$this->tool_model->Update($table,$set,$where);
    	
    	$table = "spider_scheduler";
    	$set = "state=0,scheduledtime=0,errno=0";
    	$where = "task_id = {$task_id}";
    	$result = $this->tool_model->Update($table,$set,$where);
    	if($result)
    	{
    		echo 0;
    	}
    	else 
    	{
    		echo -1;
    	}    	
    }
    
    public function FailItems()
    {
    	$data = $this->spider_model->getFailTaskList();
    	$this->load->view('incs/header');
    	$this->load->view('incs/menu');
    	
    	$this->load->view('fail_task_list',$data);
    	
    	$this->load->view('incs/footer');
    }
    
    public function autoRedo()
    {
    	ini_set('memory_limit','1024M');
    	$table = "spider_fail_task";
    	$select = "task_id";
    	$where = "state=1";
    	$result = $this->tool_model->Query($table,$select,$where);
    	if(!empty($result))
    	{
    		$success_result = array();
    		$table = "spider_scheduler";
    		$select = "state,processtime";
    		foreach($result as $key=>$val)
    		{    			
    			$where = "task_id={$val["task_id"]}";
    			$state_result = $this->tool_model->Query($table,$select,$where);
    			if(!empty($state_result))
    			{
    				if($state_result[0]["state"]==3 || ($state_result[0]["state"]==0 && $state_result[0]["processtime"]>time()-self::$updateTime))
    				{
    					$success_result[] = $val["task_id"];
    				}
    			}	
    		}
    		
    		if(!empty($success_result))
    		{
    			$table = "spider_fail_task";
    			$set = "now_fail=0, state=0";
    			foreach ($success_result as $key=>$val)
    			{
    				$where = "task_id={$val}";
    				$this->tool_model->Update($table,$set,$where);
    			}
    		}
    	}
    	
    	$table = "spider_scheduler";
    	$select = "task_id,project_id";
    	$where = "state=5 and errno !=5";
    	$result = $this->tool_model->Query($table,$select,$where);
    	if(!empty($result))
    	{
    		foreach ($result as $key=>$val)
    		{
    			$table = "spider_fail_task";
    			$select = "*";
    			$where = "task_id={$val["task_id"]}";
    			$fail_result = $this->tool_model->Query($table,$select,$where);
    			if(!empty($fail_result))
    			{
    				if($fail_result[0]["state"] ==2)
    				{
    					continue;
    				}
    				elseif($fail_result[0]["now_fail"]+1>self::$maxFailNum)
    				{
    					$set = "total_fail=total_fail+1,now_fail=now_fail+1,state=2";
    					$where = "id={$fail_result[0]["id"]}";
    					$this->tool_model->Update($table,$set,$where);
    					continue;
    				}
    				else 
    				{
    					$set = "total_fail=total_fail+1,now_fail=now_fail+1,state=1";
    					$where = "id={$fail_result[0]["id"]}";
    					$this->tool_model->Update($table,$set,$where);
    				}
    				
    			}
    			else 
    			{
    				$valueArray = array("task_id"=>$val["task_id"],
    						"project_id"=>$val["project_id"],
    						"total_fail"=>1,
    						"now_fail"=>1,
    						"state"=>1			
    				);
    				$this->tool_model->Insert($table,$valueArray);
    			}
    			
    			$table = "spider_scheduler";
    			$set = "state=0,scheduledtime=0,errno=0";
    			$where = "task_id = {$val["task_id"]}";
    			$this->tool_model->Update($table,$set,$where);    			
    		}
    	}
    	
    	$date = date("Y-m-d H:i:s",time());
    	var_dump($date);
    	
    }
    
    public function DeleteExpireTask()
    {
    	ini_set('memory_limit','1024M');
    	$expire = self::$expireTime;
    	$table = "spider_scheduler";
    	$where = "state=3 or layer>=20 or (state=5 and layer !=0 and createtime<current_timestamp()-{$expire})";
    	$select = "task_id";
    	
    	$result = $this->tool_model->Query($table, $select, $where);
    	foreach ($result as $key=>$val)
    	{
    		$table = "spider_scheduler";
    		$where = "task_id={$val["task_id"]}";
    		$this->tool_model->Delete($table,$where);
    		$table = "spider_scheduler_ext";
    		$this->tool_model->Delete($table,$where);
    		$table = "spider_fail_task";
    		$this->tool_model->Delete($table,$where);
    	}

    	$table = "spider_scheduler";
    	$where = "state=5 and layer=0";
    	$set = "state=0";
    	$this->tool_model->Update($table,$set,$where);
    	
    	$date = date("Y-m-d H:i:s",time());
    	var_dump($date);
    	
    }
    
    
    public function Debug()
    {
    	$task_id = $_GET["task_id"];
    	require "/data/spider/script/DebugTask.php";
    	$task["task_id"] = $task_id;
    	$data = $debug->DoTask($task);
    	$this->load->view("task_debug",$data);
    }
    
    public function del()
    {
    	$task_id = $_GET["task_id"];
    	if(empty($task_id))
    	{
    		echo -1;
    		return;
    	}
    	
    	$select = "url";
    	$table = "spider_scheduler_ext";
    	$where = "task_id={$task_id}";
    	$result = $this->tool_model->Query($table,$select,$where);
    	if(!empty($result))
    	{
    		$table = "spider_url";
    		$where = "url='{$result[0]["url"]}'";
    		$this->tool_model->Delete($table,$where);
    	}  	    	
    	$table = "spider_scheduler";
    	$where = "task_id={$task_id}";
    	$this->tool_model->Delete($table,$where);
    	$table = "spider_scheduler_ext";
    	$this->tool_model->Delete($table,$where);
    	$table = "spider_fail_task";
    	$this->tool_model->Delete($table,$where);
    	echo 0;
    }
    
   
}




