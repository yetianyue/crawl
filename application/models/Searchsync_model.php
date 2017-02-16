<?php

require 'DB.php';

class Searchsync_model extends CI_Model
{
	private $db_cms;

	function __construct()
	{
		parent::__construct();
		$host = new ZkHost();
		getHostByKey('m3320.qqnews_recsys.cdb.com', $host);
		$host = "{$host->ip}:{$host->port}";
		$user = "stevenyshi_r";
		$passwd = "for_php5_6";
		$database = "qqnews_recsys";
		$this->db_cms = new DB($host, $user, $passwd, $database);
		$this->load->model("tool_model");
	}

    public function SearchSync()
    {  
        $now = time();
        $start = $now - 65;
        $start = date('Y-m-d H:i:s',$start);
        $now = date('Y-m-d H:i:s',$now);
        
        echo "START: $now\n";
        $data = $this->GetCMSData($start, $now);
        $this->InsertSearchData($data);
        $sum = count($data);
        echo "SUCCESS: Get [$sum] Items\n";
        $end = date('Y-m-d H:i:s',time());
        echo "END: $end\n\n";

    }   
	
    public function GetCMSData($startTime, $endTime)
    {   
        $sql = "SELECT t_all_news.cmsid,t_all_news.title,t_all_news.pubtime,t_all_news.src,t_all_news.c_time,t_all_category.category ". 
               " FROM t_all_news ".
               " LEFT JOIN t_all_category ".
               " ON t_all_news.cmsid = t_all_category.cmsid ".
               " WHERE t_all_news.c_time > '$startTime ' AND t_all_news.c_time <= '$endTime' ";
                                                         
        $ret = $this->db_cms->getAll($sql);
        return $ret;
    }   

    public function InsertSearchData($data)
    {   
        foreach($data as $item)
        {   
            $arr = explode("\x02", trim($item["category"]));
            if(count($arr) > 2)
            {   
                $item["category"] = trim($arr[1]);
            }

            if(empty($item["category"]))
            {   
                $item["category"] = -1; 
            }   
            $item["c_timestamp"] = strtotime($item["c_time"]);

            $sql = "REPLACE INTO `search_source_2` ".
                   "(`cmsid`, `title`, `pubtime`, `src`, `c_timestamp`, `category`) ".
                   "VALUE ".
                   "('".$item["cmsid"]."','".mysql_escape_string($item["title"])."',{$item["pubtime"]},'".
                   mysql_escape_string($item["src"])."',".$item["c_timestamp"].",".$item["category"].")";
            $this->db->query($sql);
         }   
     }   
	
}
