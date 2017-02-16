<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("spider_model");
		$this->load->model("tool_model");
	}
	
    public function Items()
    {
        $data = $this->spider_model->getProjectList();       

        $this->load->view('incs/header');
        $this->load->view('incs/menu');
        $this->load->view('project_list',$data);
        $this->load->view('incs/footer');
    }

    public function Add()
    {
        /*$data = array(
             'user' => $_COOKIE['PAS_COOKIE_USER'],
        );
*/
        $this->load->view('incs/header');
        $this->load->view('incs/menu');
        $this->load->view('project_add');
        $this->load->view('incs/footer');
    }
    
    public function Alter()
    {
        $project_id = $this->input->get('id');
        $data = $this->spider_model->GetProjectDetailInfo($project_id);
        if($data['url'] == NULL)
        {
            $data['url'] = -1;
        }
        $data['history'] = $this->spider_model->GetScriptHistory($project_id);
        $this->load->view('incs/header');
        $this->load->view('incs/menu');
        $this->load->view('project_alter', $data);
        $this->load->view('incs/footer');
    }

    public function ResetProject()
    {
        $project_id = intval($_POST["project_id"]);
        $ret = $this->spider_model->ResetAllBannedStatus($project_id);
        if($ret)
        {
            echo json_encode($ret);
        }
    }

    public function ReviewProject()
    {
        $project_id = intval($_POST["project_id"]);
        $ret = $this->spider_model->UpdateProjectReviewTime($project_id);
        if($ret)
        {
            echo json_encode($ret);
        }
    }
    
    public function SaveProject()
    {
    	$name = trim($this->input->post("name"));
    	$rate = trim($this->input->post("rate"));
    	$priority = trim($this->input->post("priority"));
    	$tag = trim($this->input->post("tag"));
    	$class = trim($this->input->post("class"));
    	$script = trim($this->input->post("script"));
    	$user = trim($this->input->post("user"));
    	$cookie = trim($this->input->post("cookie"));
    	$create_time = time();
    	$modify_time = $create_time;
    	$status = 0;
    
        $list_url = trim($this->input->post("list_url"));
        $list_dom = trim($this->input->post("list_dom"));
        $list_regex = trim($this->input->post("list_regex"));
        $detail_title = trim($this->input->post("detail_title"));
        $detail_content = trim($this->input->post("detail_content"));
        $detail_contentStart = trim($this->input->post("detail_contentStart"));
        $detail_contentEnd = trim($this->input->post("detail_contentEnd"));
        $detail_pubtime = trim($this->input->post("detail_pubtime"));
        $detail_src = trim($this->input->post("detail_src"));
        $detail_author = trim($this->input->post("detail_author"));
		
		$video_find = trim($this->input->post("video_find"));
        $video_src = trim($this->input->post("video_src"));
    	if(true == $this->IsProjectNameExisted($name))
    	{
    		echo json_encode(array('error'=>'项目名称已经存在'));
    		return;
    	}
		
		$media_serial = 0;
        if(!(strpos($tag,"新闻后台") === false))
        {
		    $this->load->model("media_model");
            
            $media_serial = $this->media_model->getMediaByUrl($list_url);
            if(empty($media_serial))
            {
				$msg = date("Y-m-d H:i:s",time()).' media_serial_error,list_url:'.$list_url.',name:'.$name."\n";
				$logFileName = dirname(dirname(__FILE__))."/logs/media_error_".date("Ymd",time());
				error_log($msg,3,$logFileName);
                echo json_encode(array('error'=>'没有媒体序列号'));
                return;
            }
        }
    
    	$burst = 10*$rate;
    	$project_info = array("name"=>$name,
    			"rate"=>$rate,
    			"priority"=>$priority,
    			"tag"=>$tag,
    			"create_time"=>$create_time,
    			"modify_time"=>$modify_time,
    			"status"=>$status,
    			"class"=>$class,
    			"burst"=>$burst,
                "cookie"=>$cookie,
    			"user"=>$user,
				"media_serial"=>$media_serial ? $media_serial : 0,
    			"script"=>$script,
    	);
    	$project_id = $this->spider_model->AddProject($project_info);
        if(empty($project_id))
        {
            echo json_encode(array('error'=>'脚本信息插入数据库失败'));
            return;
        }

        $script_info = array("project_id"=>$project_id,
                "url"=>$list_url,
                "list_dom"=>$list_dom,
                "list_regex"=>$list_regex,
                "detail_title"=>$detail_title,
                "detail_content"=>$detail_content,
                "detail_contentStart"=>$detail_contentStart,
                "detail_contentEnd"=>$detail_contentEnd,
                "detail_pubtime"=>$detail_pubtime,
                "detail_src"=>$detail_src,
                "detail_author"=>$detail_author,
				"video_find"=>$video_find,
				"video_src"=>$video_src,
				
        );
        $this->spider_model->AddScriptInfo($script_info);
        

    	echo json_encode(array('error'=>''));
    }

    public function UpdateProject()
    {
        $project_id = $this->input->post("project_id");
    	$name = trim($this->input->post("name"));
    	$rate = trim($this->input->post("rate"));
    	$priority = trim($this->input->post("priority"));
    	$tag = trim($this->input->post("tag"));
    	$class = trim($this->input->post("class"));
    	$script = trim($this->input->post("script"));
    	$user = trim($this->input->post("user"));
    	$cookie = trim($this->input->post("cookie"));
    	$modify_time = time();
    	$status = 0;
    
        $list_url = trim($this->input->post("list_url"));
        $list_dom = trim($this->input->post("list_dom"));
        $list_regex = trim($this->input->post("list_regex"));
        $detail_title = trim($this->input->post("detail_title"));
        $detail_content = trim($this->input->post("detail_content"));
        $detail_contentStart = trim($this->input->post("detail_contentStart"));
        $detail_contentEnd = trim($this->input->post("detail_contentEnd"));
        $detail_pubtime = trim($this->input->post("detail_pubtime"));
        $detail_src = trim($this->input->post("detail_src"));
        $detail_author = trim($this->input->post("detail_author"));
		
		$video_find = trim($this->input->post("video_find"));
        $video_src = trim($this->input->post("video_src"));
    	if(true == $this->IsProjectNameExisted($name,$project_id))
    	{
    		echo json_encode(array('error'=>'项目名称已经存在'));
    		return;
    	}
    
    	$burst = 10*$rate;
    	$project_info = array("name"=>$name,
    			"rate"=>$rate,
    			"priority"=>$priority,
    			"tag"=>$tag,
    			"status"=>$status,
    			"modify_time"=>$modify_time,
    			"class"=>$class,
    			"burst"=>$burst,
    			"user"=>$user,
                "cookie"=>$cookie,
    			"script"=>$script,
    	);
    	$this->spider_model->UpdateProject($project_id,$project_info);

        $script_info = array("project_id"=>$project_id,
                "url"=>$list_url,
                "list_dom"=>$list_dom,
                "list_regex"=>$list_regex,
                "detail_title"=>$detail_title,
                "detail_content"=>$detail_content,
                "detail_contentStart"=>$detail_contentStart,
                "detail_contentEnd"=>$detail_contentEnd,
                "detail_pubtime"=>$detail_pubtime,
                "detail_src"=>$detail_src,
                "detail_author"=>$detail_author,
				"video_find"=>$video_find,
				"video_src"=>$video_src,
        );
        if(!$this->spider_model->IsScriptInfoExisted($project_id))
        {
        	$this->spider_model->AddScriptInfo($script_info);
        }
        $this->spider_model->UpdateScriptInfo($project_id,$script_info);
        
        $table = "spider_scheduler inner join spider_scheduler_ext on spider_scheduler.task_id=spider_scheduler_ext.task_id";
        $select = "spider_scheduler.task_id, url";
        $where = "project_id ={$project_id} and layer = 0";
        $urlArray = $this->tool_model->Query($table, $select, $where);
        foreach ($urlArray as $key=>$val)
        {
        	$table = "spider_url";
    		$where = "url='{$val["url"]}'";
    		$this->tool_model->Delete($table,$where);
    		
    		$table = "spider_scheduler_ext";
    		$where = "task_id={$val["task_id"]}";
    		$this->tool_model->Delete($table,$where);
    		
    		$table = "spider_fail_task";
    		$where = "task_id={$val["task_id"]}";
    		$this->tool_model->Delete($table,$where);
    		
    		$table = "spider_scheduler";
    		$where = "task_id={$val["task_id"]}";
    		$this->tool_model->Delete($table,$where);
        }
        		

        $script_history = array("project_id"=>$project_id,
    	    "modify_time"=>$modify_time,
    		"user"=>$user,
    		"script"=>$script
        );
       	$this->spider_model->AddScriptHistory($script_history);

    	echo json_encode(array('error'=>''));
    }
    
    public function IsProjectNameExisted($project_name,$project_id=0)
    {
    	$data = $this->spider_model->GetProjectByName($project_name);
        if(empty($data) || -1 == $data)
        {
    	    return false;
        }
        else if($data['project_id'] == $project_id)
        {
            //项目名称、id都相同，说明是在修改原项目
            return false;
        }
        else
        {
            return true;
        }
    }

    public function IsUrlExisted()
    {
        echo json_encode(array('ret'=>0));exit;
    	$url = $this->input->post('url');
    	$project_id = $this->input->post('project_id');
    	$ret = $this->spider_model->IsUrlExisted($project_id, $url);
    	echo json_encode(array('ret'=>$ret));
    }
    
    public function del()
    {
    	$project_id = trim($_GET["project_id"]);
        require_once "authorized.php";
        if(Authorized::IsAuthorized())
        {
            $user = $_COOKIE['PAS_COOKIE_USER'];
            $msg = "[".date("Y-m-d H:i:s",time())."][$user] Delete Project Id: $project_id\n";
            $this->tool_model->writeLog("delete_project",$msg);
        }
        else
        {
            echo -2;
            return;
        }
    	
    	$select = "spider_scheduler.task_id, url";
    	$table = "spider_scheduler inner join spider_scheduler_ext on spider_scheduler.task_id=spider_scheduler_ext.task_id";
    	$where = "project_id={$project_id}";
    	$result = $this->tool_model->Query($table, $select, $where);
    	foreach ($result as $key=>$val)
    	{
    		$table = "spider_url";
    		$where = "url='{$val["url"]}'";
    		$this->tool_model->Delete($table,$where);
    		
    		$table = "spider_scheduler_ext";
    		$where = "task_id={$val["task_id"]}";
    		$this->tool_model->Delete($table,$where);
    		
    		$table = "spider_fail_task";
    		$where = "task_id={$val["task_id"]}";
    		$this->tool_model->Delete($table,$where);

    		$table = "spider_scheduler";
    		$where = "task_id={$val["task_id"]}";
    		$this->tool_model->Delete($table,$where);
    	}
    	
    	   	   	
    	$table = "spider_response";
    	$where = "project_id = {$project_id}";
    	$result = $this->tool_model->Delete($table,$where);
    	if(!$result)
    	{
    		echo -1;
    		return ;
    	}
    	
    	$table = "spider_project";
    	$set = "status = 2";
    	$where = "project_id = {$project_id}";
    	$result = $this->tool_model->Update($table, $set, $where);
    	if(!$result)
    	{
    		echo -1;
    		return ;
    	}
    	
    	$table = "spider_script_info";
    	$where = "project_id = {$project_id}";
    	$result = $this->tool_model->Delete($table,$where);
    	if(!$result)
    	{
    		echo -1;
    		return ;
    	}

    	$table = "spider_script_history";
    	$where = "project_id = {$project_id}";
    	$result = $this->tool_model->Delete($table,$where);
    	if(!$result)
    	{
    		echo -1;
    		return ;
    	}
    	echo 0;
    }
    
    public function startStop()
    {
    	$project_id = trim($_GET["project_id"]);
    	$state = trim($_GET["state"]);
    	
    	if($state==1)
    	{
    		$sql = "update spider_scheduler set state=6 where project_id={$project_id} and state<3";
    	}
    	else 
    	{
    		$sql = "update spider_scheduler set state=0 where project_id={$project_id} and state=6";
    	}
    	
    	$result = $this->db->query($sql);
    	if(!$result)
    	{
    		echo -1;
    		return ;
    	}
    	
    	echo 0;
    }
	
	public function delTask()
    {
        $res = array();
        $res['errno'] = 0;
        $res['msg'] = '成功';
    	$media_serial = trim($_GET["media_serial"]);
		
		$whiteIps = array('10.134.143.225','172.27.30.142','10.208.131.162');

		$clientIp = $this->tool_model->getClientIp();
		if(!in_array($clientIp,$whiteIps))
		{
			$res['errno'] = -8;
            $res['msg'] = 'ip Illegal';
			$msg = date('Y-m-d H:i:s',time()).",delTask error,media_serial:{$media_serial},msg:{$res['msg']}\n";
			$this->tool_model->writeLog("mmsDelTask",$msg);
            echo json_encode($res);
            return ;
		}
		
        if(empty($media_serial) || !is_numeric($media_serial))
        {
        
            $res['errno'] = -1;
            $res['msg'] = 'media_serial error';
			$msg = date('Y-m-d H:i:s',time()).",delTask error,media_serial:{$media_serial},msg:{$res['msg']}\n";
			$this->tool_model->writeLog("mmsDelTask",$msg);
            echo json_encode($res);
            return ;
        }
		$msg = date('Y-m-d H:i:s',time()).",delTask start,media_serial:{$media_serial}\n";
		$this->tool_model->writeLog("mmsDelTask",$msg);
		
        $project_id = $this->spider_model->getByMediaId($media_serial);
        if(empty($project_id))
        {
            $res['errno'] = -7;
            $res['msg'] = '没有对应的项目';
			$msg = date('Y-m-d H:i:s',time()).",delTask error,media_serial:{$media_serial},msg:{$res['msg']}\n";
			$this->tool_model->writeLog("mmsDelTask",$msg);
            echo json_encode($res);
            return ;
        }
		
		$select = "spider_scheduler.task_id, url";
    	$table = "spider_scheduler inner join spider_scheduler_ext on spider_scheduler.task_id=spider_scheduler_ext.task_id";
    	$where = "project_id={$project_id}";
    	$result = $this->tool_model->Query($table, $select, $where);
    	foreach ($result as $key=>$val)
    	{
    		$table = "spider_url";
    		$where = "url='{$val["url"]}'";
    		$this->tool_model->Delete($table,$where);
    		
    		$table = "spider_scheduler_ext";
    		$where = "task_id={$val["task_id"]}";
    		$this->tool_model->Delete($table,$where);
    		
    		$table = "spider_fail_task";
    		$where = "task_id={$val["task_id"]}";
    		$this->tool_model->Delete($table,$where);

    		$table = "spider_scheduler";
    		$where = "task_id={$val["task_id"]}";
    		$this->tool_model->Delete($table,$where);
    	}
    	
    	$table = "spider_response";   	
    	$where = "project_id = {$project_id}";
    	$result = $this->tool_model->Delete($table,$where);
    	if(!$result)
    	{
            $res['errno'] = -3;
            $res['msg'] = 'del result error';
			$msg = date('Y-m-d H:i:s',time()).",delTask error,media_serial:{$media_serial},msg:{$res['msg']}\n";
			$this->tool_model->writeLog("mmsDelTask",$msg);
            echo json_encode($res);
            return ;
    	}
    	
    	$table = "spider_project";
    	$set = "status = 2";
    	$where = "project_id = {$project_id}";
    	$result = $this->tool_model->Update($table, $set, $where);
    	if(!$result)
    	{
            $res['errno'] = -4;
            $res['msg'] = 'del project error';
			$msg = date('Y-m-d H:i:s',time()).",delTask error,media_serial:{$media_serial},msg:{$res['msg']}\n";
			$this->tool_model->writeLog("mmsDelTask",$msg);
            echo json_encode($res);
            return ;
    	}
    	
    	$table = "spider_script_info";
    	$where = "project_id = {$project_id}";
    	$result = $this->tool_model->Delete($table,$where);
    	if(!$result)
    	{
            $res['errno'] = -5;
            $res['msg'] = 'del project_script_info error';
			$msg = date('Y-m-d H:i:s',time()).",delTask error,media_serial:{$media_serial},msg:{$res['msg']}\n";
			$this->tool_model->writeLog("mmsDelTask",$msg);
            echo json_encode($res);
            return ;
    	}
		$msg = date('Y-m-d H:i:s',time()).",delTask end,sucessful,media_serial:{$media_serial}\n";
		$this->tool_model->writeLog("mmsDelTask",$msg);
        echo json_encode($res);
        return ;

    }
}




