<?php

class Stat_model extends CI_Model
{
	
    public function getSystemInfo()
    {
        $SysInfo = array('Project'=>array(), 'Task'=>array());

        $sql = "SELECT COUNT(*) AS `Total` FROM `spider_project` WHERE `status` <2";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $SysInfo['Project']['Total'] = $result[0]["Total"];

        $sql = "SELECT COUNT(*) AS `Today` FROM `spider_project` WHERE `create_time` >= ".strtotime(date("Y-m-d",time()));
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $SysInfo['Project']['Today'] = $result[0]["Today"];

        $sql = "SELECT COUNT(*) AS `Total` FROM `spider_scheduler`";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $SysInfo['Task']['Total'] = $result[0]["Total"];

        $sql = "SELECT COUNT(*) AS `Complete` FROM `spider_scheduler` WHERE `state` = 3";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $SysInfo['Task']['Complete'] = $result[0]["Complete"];
        
        $sql = "SELECT COUNT(*) AS `Today` FROM `spider_response` WHERE `create_time` >= ".strtotime(date("Y-m-d",time()));
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $SysInfo['Response']['Today'] = $result[0]["Today"];

        return $SysInfo;
    }

    public function getUserInfo()
    {
        $UserInfo = array();

        $sql = "SELECT `user` AS `Name`, COUNT(*) AS `Count` ".
               "FROM `spider_project` ".
               "GROUP BY `user`";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        foreach($result as $item)
        {
            $UserInfo[$item["Name"]] = array(
                "Name" => $item["Name"],
                "Total" => $item["Count"],
                "Today" => 0,
                "Error" => 0,
            );
        }

        $sql = "SELECT `user` AS `Name`, COUNT(*) AS `Count` ".
               "FROM `spider_project` ".
               "WHERE `create_time` >= ".strtotime(date("Y-m-d",time()))." GROUP BY `user`";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        foreach($result as $item)
        {
            $UserInfo[$item["Name"]]["Today"] = $item["Count"];
        }

        return $UserInfo;
    }
    
    

    public function getSchedulerInfo()
    {
        $Info = array();

        $sql = "SELECT COUNT(*) AS `Wait` FROM `spider_scheduler` WHERE `state` = 0 AND (`scheduledtime` = 0 or `scheduledtime` < CAST(unix_timestamp() - `age` AS SIGNED)) AND `layer` < 20";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $Info['Wait'] = $result[0]["Wait"];

        $sql = "SELECT COUNT(*) AS `Queue` FROM `spider_scheduler` WHERE `state` = 1";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $Info['Queue'] = $result[0]["Queue"];

        $sql = "SELECT COUNT(*) AS `Process` FROM `spider_scheduler` WHERE `state` = 2";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $Info['Process'] = $result[0]["Process"];

        $sql = "SELECT COUNT(*) AS `Fail` FROM `spider_scheduler` WHERE `state` = 5";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $Info['Fail'] = $result[0]["Fail"];
        
        $schdQueueCount = 0;
        $redis = new Redis();
        if(TRUE == $redis->pconnect("10.134.7.99", 6379))
        {
            $keys = $redis->keys("SCHEDULER_QUEUE_*");
            foreach($keys as $key)
                $schdQueueCount += $redis->llen($key);
        }
        $Info['Scheduler'] = $schdQueueCount;

        return $Info;
    }
    
   
    public function getProjectList($state="", $today=FALSE)
    {
    	$sql = "select project_id,name,domain from spider_project";
    	$query = $this->db->query($sql);
        $projectList = $query->result_array(); 
        $count = array();               
        foreach($projectList as $key=>$val)
        {
        	$where = array();
        	if(!empty($state) || $state===0)
        	{
        		$where[] = "state={$state}";
        	}
        	
        	if($today)
        	{
        		$today_timestamp = strtotime(date("Y-m-d",time()));
        		$where[] = "processtime >= {$today_timestamp}";
        	}
        	$where[] = "project_id={$val["project_id"]}";
        	$where = implode(" and ", $where);
        	$sql = "select count(*) as num from spider_scheduler where {$where}";
        	$query = $this->db->query($sql);
        	$result = $query->result_array();
        	$projectList[$key]["Count"] = $result[0]["num"];
        	$count[] = $result[0]["num"];        	
        }
        array_multisort($count, SORT_DESC, $projectList);
        return $projectList;
        
    }
    
    
    public function getResponseStatistics()
    {
    	$statics = array();
    	for($i=6;$i>=0;$i--)
    	{
    		$timeStamp = ((floor(time() / 86400)-$i) * 86400);
    		$timeStampEnd = ((floor(time() / 86400)-$i+1) * 86400);
    		$timeStamp = strtotime(date("Y-m-d",$timeStamp));
    		$timeStampEnd = strtotime(date("Y-m-d",$timeStampEnd));
    		$date = date("m-d",$timeStamp);
    		$sql = "SELECT COUNT(*) AS `num` FROM `spider_response` WHERE `create_time` >={$timeStamp} and `create_time`<{$timeStampEnd} ";
    		$query = $this->db->query($sql);
    		$result = $query->result_array();
    		$statics[$date]["create_time"] = $result[0]["num"];
    		
    		$sql = "SELECT COUNT(*) AS `num` FROM `spider_response` WHERE `pub_time` >={$timeStamp} and `pub_time`<{$timeStampEnd} ";
    		$query = $this->db->query($sql);
    		$result = $query->result_array();
    		$statics[$date]["pub_time"] = $result[0]["num"];
    		
    	}
    	$labels = array();
    	$data = array();   	
    	foreach ($statics as $key=>$val)
    	{
    		$labels[] = $key;    		
    		$data["create_time"][] = $val["create_time"];
    		$data["pub_time"][] = $val["pub_time"];
    		
    	}
    	$returnData["labels"] = $labels;
    	$returnData["data"] = $data;
    	return $returnData;   	
    }

    public function getStatDataMap()
    {
        return array(
            'System' => $this->getSystemInfo(),
            'Scheduler' => $this->getSchedulerInfo(),
            'WaitProject' => $this->getStatProjectList(0),
            'ErrorProject' => $this->getStatProjectList(5),
            'SuccessProject' => $this->getStatProjectList(3, TRUE),
            'UserInfo' => $this->getUserInfo(),
        	'statics'=>$this->getResponseStatistics(),
        );
    }
    
    public function getWaitProjectList()
    {
        $sql = "SELECT `t`.`project_id`, `spider_project`.`name`, `t`.`domain`, `t`.`count` ".
               "FROM ".
                   "(SELECT COUNT(*) AS `count`, `domain`, `project_id` ".
                    "FROM `spider_scheduler` ".
                    "WHERE `state` = 0 AND (`scheduledtime` = 0 or `scheduledtime` < CAST(unix_timestamp() - `age` AS SIGNED)) AND ".
                          "`layer` < 20 GROUP BY `domain` ORDER BY `count` DESC) AS `t`".
               "INNER JOIN `spider_project` ON `t`.`project_id` = `spider_project`.`project_id`";

        $query = $this->db->query($sql);
        $result = $query->result_array();

        $redis = new Redis();
        if(TRUE == $redis->pconnect("10.134.7.99", 6379))
        {
            foreach($result as &$item)
            {
                $rateinfo = $redis->get("DTB_".$item['domain']);
                if(empty($rateinfo))
                    continue;
                $rateinfo = json_decode($rateinfo, TRUE);
                $item["rate"] = $rateinfo['rate'];
            }
        
        }
        return $result;
    }
    
    public function getFailProjectList()
    {
    	$Info = $this->getProjectList(5);
    	return $Info;
    }
    
    public function getSuccessProjectList()
    {
    	$Info = $this->getProjectList(3,TRUE);
    	return $Info;
    }
    
    public function getNetFlow($time1,$time2)
    {   	
    	$sql = "select sum(html_size) as html_size from spider_netflow where fetch_time >={$time1} and fetch_time<{$time2}";
    	$query = $this->db->query($sql);
    	$result = $query->result_array();
    	return $result[0]["html_size"];
    }
    
    public function getCrawlArticalNum($str, $start, $end, $pub=FALSE)
    {
    	$where = array();
    	if(!empty($str))
    	{
    		$where[] = "tag like '%{$str}%'";
    	}
    	
    	if(!empty($start))
    	{
    		$where[] = "spider_response.create_time>{$start}";
    		$start = $start-172800;
    		$where[] = "pub_time>{$start}";
    	}
    	
    	if(!empty($end))
    	{
    		$where[] = "spider_response.create_time<{$end}";
    		$where[] = "pub_time<{$end}";
    	}
    	
    	if($pub)
    	{
    		$where[] = "iurl != ''";
    	}
    	$where = implode(" and ", $where);
    	
    	$sql = "select count(*) as num from spider_response inner join spider_project on spider_response.project_id = spider_project.project_id where {$where}";
    	$query = $this->db->query($sql);
    	$result = $query->result_array();
    	return $result[0]["num"];
    	
    }
    
    public function getCrawlInfo()
    {
    	$start = strtotime(date("Y-m-d",time()));
    	$end =  $start + 86400;   	
    	$str = "新闻后台";
    	$info["news_today_pub"] = $this->getCrawlArticalNum($str, $start, $end, TRUE);
    	$info["news_today"] = $this->getCrawlArticalNum($str, $start, $end);
    	
    	$end = $start;
    	$start = $start - 86400;
    	$info["news_yestoday_pub"] = $this->getCrawlArticalNum($str, $start, $end, TRUE);
    	$info["news_yestoday"] = $this->getCrawlArticalNum($str, $start, $end);
    	return $info;
    }
    
    public function GetCrawlArticalInfo($tag, $day = 3, $hour = 3)
    {
    	   	
    	$timeStamp = strtotime(date("Y-m-d",time())) - ($day - 1) * 86400;
    	$interval = $hour * 3600;
    	$statics = array();
    	while($timeStamp < time())
    	{
    		
    		$start = $timeStamp;
    		$end = $timeStamp + $interval;
    		$crawl_num = 0;
    		$effective_num = 0;
    		$result = $this->GetArticalNumByTag($tag, $start, $end);
    		
    		foreach ($result as $key=>$val)
    		{
    			$crawl_num += $val["crawl_num"];
    			$effective_num += $val["effective_num"];
    		}
    		
    		if($end>time())
    		{
    			$end = time();
    		}
    		$date = date("m-d H:i", $end);
    		$statics[$date]["crawl_num"] = 	$crawl_num;
    		$statics[$date]["effective_num"] = 	$effective_num;
    		$timeStamp += $interval;
    	}
    	
    	$labels = array();
    	$data = array();
    	foreach ($statics as $key=>$val)
    	{
    		$labels[] = $key;
    		$data["crawl_num"][] = $val["crawl_num"];
    		$data["effective_num"][] = $val["effective_num"];
    	}
    	$returnData["labels"] = $labels;
    	$returnData["data"] = $data;
    	return $returnData;  	
    }
    
    public function GetArticalNumByTag($tag, $start ,$end)
    {
    	if(!empty($tag))
    	{
    		$sql = "select crawl_num,effective_num from spider_response_statics where timeStamp>{$start} and timeStamp<{$end} and tag='{$tag}'";
    	}
    	else
    	{
    		$sql = "select crawl_num,effective_num from spider_response_statics where timeStamp>{$start} and timeStamp<{$end}";
    	}
    	
    	$query = $this->db->query($sql);
    	return $query->result_array();
    }
    
    public function GetArticalNumByProjectId($project_id, $start, $end, $column = "pub_time")
    {
    	$sql = "select count(*) as num from spider_response where project_id = {$project_id} and {$column}>{$start} and {$column}<{$end}";
    	$query = $this->db->query($sql);
    	$num = $query->result_array();
    	return $num[0]["num"]; 	
    }
    
    public function StaticsResponse($tag = "新闻后台")
    {
    	$sql = "select timeStamp from spider_response_statics where id=(select max(id) from spider_response_statics)";
    	$query = $this->db->query($sql);
    	$result= $query->result_array();
    	$timeStamp = $result[0]["timeStamp"];
    	$nowStamp = time();
    	$interval = 1800;
    	$start = $timeStamp;    	    	
    	
    	$where = array();
    	if(!empty($tag))
    	{
    		$where[] = "tag like '%{$str}%'";
    	}
    	$where = implode(" and ", $where);
    	$sql = "select project_id from spider_project where {$where}";
    	$query = $this->db->query($sql);
    	$result = $query->result_array();
    	
    	while($start < $nowStamp)
    	{
    		$end = $start + $interval;
    		if($end > $nowStamp)
    		{
    			$end = $nowStamp;
    		}
    		$crawl_num = 0;
    		$effective_num = 0;
    		
    		foreach ($result as $key=>$val)
    		{
    			$crawl_num += $this->GetArticalNumByProjectId($val["project_id"], $start, $end,"create_time");
    			$effective_num += $this->GetArticalNumByProjectId($val["project_id"], $start, $end,"pub_time");	
    		}
    		
    		$sql = "insert into spider_response_statics(tag,crawl_num,effective_num,timeStamp) values('{$tag}', {$crawl_num},{$effective_num},{$end})";    		
    		$this->db->query($sql);
    		
    		$start = $end;
    	}
    	    	    	
    }
    
}


