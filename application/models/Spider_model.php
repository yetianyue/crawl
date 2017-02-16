<?php
ini_set('memory_limit', '48M');

class Spider_model extends CI_Model
{
	public static $pageSize = 20;
	public static $pageIndexNum = 4;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("tool_model");
	}	
	
   public function getProjectList()
   {
   		$select = "project_id,name,user,priority,tag,create_time,modify_time,review_time";
   		$order = "project_id desc";
   		
   		$where = array();
   		$url = "";
   		
   		if(!empty($_GET["project_id"]))
   		{
   			$project_id = trim($_GET["project_id"]);
   			$where[] = "project_id = {$project_id}";
   			$url .="project_id={$project_id}&";
   			$returnData["project_id"] = $project_id;
   		}
   		
   		if(!empty($_GET["name"]))
   		{
   			$name = trim($_GET["name"]);
   			$where[] = "name like '%{$name}%'";
   			$url .="name={$name}&";
   			$returnData["name"] = $name;
   		}
   		
   		if(!empty($_GET["domain"]))
   		{
   			$domain = trim($_GET["domain"]);
   			if(substr($domain, 0, 7) == "http://")
   			{
   				preg_match("/http:\/\/([^\/]+)\/?/", $domain, $match);
   				$domain = $match[1];
   			}
   			$where[] = "domain = '{$domain}'";
   			$url .="domain={$domain}&";
   			$returnData["domain"] = $domain;
   		}
   		
   		if(!empty($_GET["user"]))
   		{
   			$user = trim($_GET["user"]);
   			$where[] = "user='{$user}'";
   			$url .="user={$user}&";
   			$returnData["user"] = $user;
   		}
   		
   		if(!empty($_GET["priority"]) || $_GET["priority"]==='0')
   		{
   			$priority = trim($_GET["priority"]);	
   			$where[] = "priority={$priority}";
   			$url .="priority={$priority}&";
   			$returnData["priority"] = $priority;
   		}
   		
   		$where[] = "status<2";
   		
   		$where = implode(" and ", $where);
   		
       	$data =  $this->tool_model->PageAll("spider_project",$select,$where,$order,$url);
        $now = time();
        foreach($data as $key=>$val)
        {
            $tag = explode(",",$val["tag"]);
            $tagName = array();
            foreach($tag as $k=>$v)
            {
                $v = trim($v);
                $tagName[] = $v;
            }
            //三天内检查项目过期
            if($now - $val["review_time"] < 86400*3)
            {
                $data[$key]["review_state"] = 0;
            }
            else if($this->IsProjectPublished($val["project_id"]))
            {
                $data[$key]["review_state"] = 1;
            }
            else
            {
                $data[$key]["review_state"] = 2;
            }

                      
            $data[$key]["tag"] = $tagName;
            $data[$key]["create_time"] = date("Y-m-d H:i:s",$val["create_time"]);
            $data[$key]["modify_time"] = date("Y-m-d H:i:s",$val["modify_time"]);
            
            $where = "project_id = {$val["project_id"]} and state = 0";
            $num = $this->CountItem("spider_scheduler",$where);
            $data[$key]["state"][0] = $num;
            
            $where = "project_id = {$val["project_id"]} and pub_time != 0";
            $num = $this->CountItem("spider_response",$where);
            $data[$key]["state"][1] = $num;
            
            $where = "project_id = {$val["project_id"]} and pub_time = 0";
            $num = $this->CountItem("spider_response",$where);
            $data[$key]["state"][2] = $num;
            
            $where = "project_id = {$val["project_id"]} and state = 6";
            $num = $this->CountItem("spider_scheduler",$where);
            $data[$key]["state"][3] = $num;
            
        } 
        $returnData["projectList"] = $data; 
        return $returnData;
   }
   
   
   public function getTaskList()
   {
   		   		
   		if(!empty($_GET["project_id"]))
   		{
   			$project_id = trim($_GET["project_id"]);
   			$select = "name";
   			$where = "project_id = {$project_id}";
   			$result = $this->tool_model->Query("spider_project",$select,$where);
   			if(!empty($result))
   			{
   				$project_name = $result[0]["name"];
   			}
   			
   		}
   		  		
   		if(isset($_GET["state"]))
   		{
   			$state = trim($_GET["state"]);
   		}
   		
   		$where = array();
   		$url = "";
   		if(isset($project_id))
   		{
   			$where[] = "project_id = {$project_id}";
   			$url .= "project_id={$project_id}&";
   			$returnData["project_id"] = $project_id;
   		}
   		
   			
   		if(!empty($state) || $state==='0')
   		{
   			
   			$where[] = "state = {$state}";
   			$url .= "state={$state}&";
   			$returnData["state"] = $state;
   		}
   		  		
   		if(!empty($_GET["url"]))
   		{
   			$u = trim($_GET["url"]);
   			$where[] = "url like'{$u}%'";
   			$returnData["url"] = $u;
   			$u = urlencode($u);
   			$url .= "url={$u}&";
   		}
   		
   		$where = implode(" and ", $where);		
   		$order = "processtime desc";
   		$table = "spider_scheduler inner join spider_scheduler_ext on spider_scheduler.task_id=spider_scheduler_ext.task_id";
   		$select = "spider_scheduler.task_id, project_id, url, state, errno, processtime, layer, age, callback";
   		$data = $this->tool_model->PageAll($table, $select, $where, $order, $url);
   		if(!empty($data))
   		{
   			foreach ($data as $key=>$val)
   			{   				   				
   				if($val["age"]>429496729)
   				{
   					$age = "--";
   				}
   				else 
   				{
   					$age = intval($val["age"]/60)."分钟";
   				}
   				
   				$data[$key]["age"] = $age;
   				$data[$key]["scheduledtime"] = date("Y-m-d H:i:s",$val["scheduledtime"]);
   				$data[$key]["processtime"] = date("Y-m-d H:i:s",$val["processtime"]); 				
   			}
   		}
   		
   		if(!empty($project_name))
   		{
   			$returnData["name"] = $project_name;
   		}
   		$returnData["taskList"] = $data;
   		return $returnData;
   		
   }

   public function GetResponseByMmsid($mmsid)
   {
        $returnData = array();
	   	$returnData["mmsid"] = $mmsid;

   		$table = "spider_response inner join spider_project on spider_response.project_id=spider_project.project_id";
   		$select = " spider_project.name,spider_project.user,spider_response.iurl,spider_response.create_time,spider_response.pub_time,spider_response.title,spider_response.url ";
        $where = " spider_project.media_serial = {$mmsid} and spider_response.iurl is not null ";
	   	$order = "pub_time desc";

   		$data = $this->tool_model->PageAll($table, $select, $where, $order);
	   	if(!empty($data))
	   	{
	   		foreach ($data as $key=>$val)
	   		{	   			   			
	   			$data[$key]["create_time"] = date("Y-m-d H:i:s",$val["create_time"]);
	   			$data[$key]["pub_time"] = date("Y-m-d H:i:s",$val["pub_time"]);
	   			$data[$key]["iurl"] = "http://kuaibao.qq.com/s/".$val["iurl"]."00";
	   		
	   		}
	   	    $returnData["responseList"] = $data;
	   	    echo json_encode(array('code'=>0,'msg'=>'success','data'=>$returnData));
            return;
	   	}
        else
        {
	   	    echo json_encode(array('code'=>-1,'msg'=>'empty dataset'));
            return;
        }
	   	 
   }
   
   public function GetResponseByUser($user,$state)
   {
        $returnData = array();
	   	$returnData["user"] = $user;

   		$table = " spider_response ";
   		$select = " * ";
        $where = " project_id in (select project_id from spider_project where user = '{$user}') ";
        if(!empty($state))
        {
            $returnData['state'] = $state;
            if($state == 1)
            {
	   		    $where .= " and status = 2000 ";//2000为发文成功
            }
            else if($state == 2)
            {
	   		    $where .= " and status = 1013 ";//1013为拦截文章
            }
        }
	    $url .= "&user={$user}&state={$state}&";
	   	$order = "pub_time desc";

   		$data = $this->tool_model->PageAll($table, $select, $where, $order, $url);
	   	if(!empty($data))
	   	{
	   		foreach ($data as $key=>$val)
	   		{	   			   			
	   			$data[$key]["create_time"] = date("Y-m-d H:i:s",$val["create_time"]);
	   			$data[$key]["pubtime"] = date("Y-m-d H:i:s",$val["pub_time"]);
	   			$content = $val["content"];
	   		
		   		$content = json_decode($content,true);
		   		if(empty($content))
		   		{
		   			continue;
		   		}
		   		$data[$key]["title"] = $content["title"];
		   		$data[$key]["source"] = $content["source"];
		   		$data[$key]["count"] = $content["count"];
		   		$data[$key]["user"] = $user;
		   		
	   			
	   		}
	   	}
	   	 
	   	$returnData["responseList"] = $data;
	   	return $returnData;
   }

   public function UpdateResponseReviewTime($id)
   {
        $review_time = time();
        $table = " spider_response ";
        $set = " review_time = {$review_time} ";
        $where = " id = {$id} ";
	   	$ret = $this->tool_model->Update($table,$set,$where);
        return $ret; 
   }

   public function UpdateProjectReviewTime($project_id)
   {
        $review_time = time();
        $table = " spider_project ";
        $set = " review_time = {$review_time} ";
        $where = " project_id = {$project_id} ";
	   	$ret = $this->tool_model->Update($table,$set,$where);
        return $ret; 
   }

   public function GetUserById($project_id)
   {
	   	$select = "user";
	   	$where = "project_id = {$project_id}";
	   	$result = $this->tool_model->Query("spider_project",$select,$where);
	   	if(!empty($result))
	   	{
	   		$user = $result[0]["user"];
	   		return $user;
	   	}
   }


   public function getResponseList()
   {
        $returnData = array();
	   	if(!empty($_GET["project_id"]))
	   	{
	   		$project_id = trim($_GET["project_id"]);
	   		$select = "name";
	   		$where = "project_id = {$project_id}";
	   		$result = $this->tool_model->Query("spider_project",$select,$where);
	   		if(!empty($result))
	   		{
	   			$project_name = $result[0]["name"];
	   			$returnData["name"] = $project_name;
	   		}
	   	}
	   	
	   	$where = array();
	   	$url = "";

        $returnData['state'] = 0;
        if(!empty($_GET["state"]))
        {
        	$state = trim($_GET['state']);
            $returnData['state'] = intval($state);
            if($state == 1)
            {
	   		    $where[] = " status = 2000 ";//2000为发文成功
            }
            else if($state == 2)
            {
	   		    $where[] = " status = 1013 ";//1013为拦截文章
            }
	   		$url .= "state={$state}&";
        }

	   	if(!empty($_GET["url"]))
	   	{
	   		$u = trim($_GET["url"]);
	   		$where[] = "url like'{$u}%'";
	   		$returnData["url"] = $u;
	   		$u = urlencode($u);
	   		$url .= "url={$u}&";
	   	}

	   	if(!empty($_GET["iurl"]))
	   	{
	   		$iurl = trim($_GET["iurl"]);
	   		preg_match("/(http:\/\/kuaibao\.qq\.com\/[a-z]\/)?([0-9a-zA-Z]{14})*/", $iurl, $matchs);
	   		$where[] = "iurl = '{$matchs[2]}'";
	   		$returnData["iurl"] = $iurl;
	   		$iurl = urlencode($iurl);
	   		$url .= "iurl={$iurl}&";
	   	}
	   	
	   	
	   	if(isset($project_id))
	   	{
	   		$where[] = "project_id = {$project_id}";
	   		$url .= "project_id={$project_id}&";
	   		$returnData["project_id"] = $project_id;
	   	}
	   	
	   	if(!empty($_GET["pub_time"]))
	   	{
	   		$pub_time = trim($_GET["pub_time"]);
	   		$url .= "pub_time={$pub_time}&";
	   		$returnData["pub_time"] = $pub_time;
	   		if($pub_time < 0)
	   		{
	   			$where[] = "pub_time = 0";
	   		}
	   		else
	   		{
	   			$where[] = "pub_time != 0";
	   		}
	   		
	   	}
	   	
         
   		$table = " spider_response ";
   		$select = " * ";
	   	$where = implode(" and ", $where);
	   	$order = "pub_time desc";
	   	$data = $this->tool_model->PageAll($table,$select,$where,$order,$url);
	   	
	   	if(!empty($data))
	   	{
	   		foreach ($data as $key=>$val)
	   		{	   			   			
	   			$data[$key]["create_time"] = date("Y-m-d H:i:s",$val["create_time"]);
	   			$data[$key]["pubtime"] = date("Y-m-d H:i:s",$val["pub_time"]);
	   			$content = $val["content"];
	   		
		   		$content = json_decode($content,true);
		   		if(empty($content))
		   		{
		   			continue;
		   		}
		   		$data[$key]["title"] = $content["title"];
		   		$data[$key]["source"] = $content["source"];
		   		$data[$key]["count"] = $content["count"];
		        $data[$key]["user"] = $this->GetUserById($val["project_id"]);   		
	   			
	   		}
	   	}
	   	$returnData["responseList"] = $data;
	   	return $returnData;
	   		   		   		   	
   }
   
   public function getFailTaskList()
   {
   		
   		if(isset($_GET["state"]))
   		{
   			$state = trim($_GET["state"]);
   		}
   		$where = array();
   		$url = "";
   		
   		if(!empty($_GET["name"]))
   		{
   			$name = trim($_GET["name"]);
   			$where[] = "b.name like '%{$name}%'";
   			$url .="name={$name}&";
   			$returnData["name"] = $name;
   		}
   		
   		if(!empty($_GET["user"]))
   		{
   			$user = trim($_GET["user"]);
   			$where[] = "b.user='{$user}'";
   			$url .="user={$user}&";
   			$returnData["user"] = $user;
   		}
   		
   		
   		if(!empty($state) || $state==='0')
   		{	
   			$where[] = "a.state = {$state}";
   			$url .= "state={$state}&";
   			$returnData["state"] = $state;
   		}
   		
   		$where[] = "a.project_id = b.project_id";
   		
   		$where = implode(" and ", $where);
   		$order = "a.total_fail desc";
   		$select = "a.task_id as task_id,a.total_fail as total_fail,a.state as state,b.name as name,b.user as user";
   		$table = "spider_fail_task a,spider_project b";
   		$data = $this->tool_model->PageAll($table,$select,$where,$order,$url);
   		if(!empty($data))
   		{
   			foreach ($data as $key=>$val)
   			{
   				$select = "url,processtime,callback,errno";
   				$table = "spider_scheduler inner join spider_scheduler_ext on spider_scheduler.task_id=spider_scheduler_ext.task_id";
   				$where = "spider_scheduler.task_id={$val["task_id"]}";
   				$result = $this->tool_model->Query($table,$select,$where);
   				if(!empty($result))
   				{
   					$data[$key]["url"] = $result[0]["url"];
   					$data[$key]["processtime"] = date("Y-m-d H:i:s",$result[0]["processtime"]);
   					$data[$key]["callback"] = $result[0]["callback"];
   					$data[$key]["errno"] = $result[0]["errno"];
   					
   				}
   			}
   		}
   		$returnData["failTaskList"] = $data;
   		return $returnData;
   		  		
   }

   


   
   public function CountItem($table,$where="")
   {
   		if(!empty($where))
   		{
   			$where = "where {$where}";
   		}
   		$sql = "select count(*) as num from {$table} {$where}";
   		$query = $this->db->query($sql);
   		$result = $query->result_array();
   		$num = $result[0]["num"];
   		return $num;
   		
   }

    //重置项目所有的拦截文章(1013)状态为3000（3000为已废弃）
   public function ResetAllBannedStatus($project_id)
   {
   		$sql = "update spider_response set status = 3000 where project_id = {$project_id} and status = 1013";
   		$ret = $this->db->query($sql);
        return $ret;
   }

    //项目中是否有文章发布
   public function IsProjectPublished($project_id)
   {
   		$sql = "select id from spider_response where project_id = {$project_id} and status = 2000 limit 1";
   		$query = $this->db->query($sql);
   		$ret = $query->result_array();
    	if(count($ret) != 0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
   		
   }

    public function AddProject($data)
    {
    	$this->db->insert('spider_project', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function AddScriptInfo($data)
    {
    	$this->db->insert('spider_script_info', $data);
    }

    public function UpdateProject($id, $data)
    {
        $this->db->where('project_id', $id);
        $this->db->update('spider_project', $data); 
    }
    
    public function UpdateScriptInfo($id, $data)
    {
        $this->db->where('project_id', $id);
        $this->db->update('spider_script_info', $data); 
    }
    
    public function IsScriptInfoExisted($id)
    {
    	$this->db->select('project_id');
    	$this->db->where('project_id',$id);
    	$query = $this->db->get('spider_script_info');
    	$ret = $query->result_array();
    	if(count($ret) != 0)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    public function AddScriptHistory($data)
    {
        $this->db->insert('spider_script_history', $data);
    }

    public function GetProjectByName($name)
    {
    	$this->db->select('project_id');
        $this->db->where('name',$name);
    	$query = $this->db->get('spider_project');
        $ret = $query->result_array();
        if(count($ret) != 0)
        {
            return $ret[0];
        }
        else
        {
            return 0;
        }
    }
    
    public function IsUrlExisted($project_id,$url)
    {
    	$this->db->select('project_id');
    	$this->db->from('spider_scheduler');
    	$this->db->join('spider_scheduler_ext',"spider_scheduler.task_id = spider_scheduler_ext.task_id ",'inner');
    	$this->db->where("spider_scheduler_ext.url = '$url'");
    	$query = $this->db->get();
    	$ret = $query->result_array();
    	if(count($ret) != 0 && $ret[0]['project_id'] != $project_id)
    	{
    		return 1;
    	}
    	else
    	{
    		return 0;
    	}
    }

	public function GetProjectDetailInfo($id)
    {
        $this->db->select('*');
        $this->db->from('spider_project');
        $this->db->join('spider_script_info',"spider_project.project_id = spider_script_info.project_id ",'inner');
        $this->db->where("spider_project.project_id = $id");
        $query = $this->db->get();
        $ret = $query->result_array();
        if(count($ret) != 0)
        {
            return $ret[0];
        }
        
        $this->db->select('*');
        $this->db->from('spider_project');
        $this->db->where("spider_project.project_id = $id");
        $query = $this->db->get();
        $ret = $query->result_array();
        if(count($ret) != 0)
        {
            return $ret[0];
        }
        else
        {
            return 0;
        }
    }
    
    public function GetScriptHistory($project_id)
    {
    	$this->db->select('id,project_id,user,modify_time');
    	$this->db->from('spider_script_history');
    	$this->db->where("project_id = $project_id");
    	$this->db->order_by('id','desc');
    	$query = $this->db->get();
    	$ret = $query->result_array();
    	if(count($ret) != 0)
    	{
    		return $ret;
    	}
    	else
    	{
    		return 0;
    	}
    }

    

	
	 public function getByMediaId($meidia_id)
    {
        $this->db->select('project_id');
        $this->db->from('spider_project');
        $this->db->where("media_serial = $meidia_id");
        $query = $this->db->get();
        $ret = $query->result_array();
        if(count($ret) != 0)
        {
            return $ret[0]['project_id'];
        }
        else
        {
            return 0;
        }
    }
}






