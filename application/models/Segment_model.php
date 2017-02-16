<?php

require 'DB.php';

class Segment_model extends CI_Model
{
	private  static $pageSize = 15;
	
	private $db1;

	function __construct()
	{
		parent::__construct();
		$host = new ZkHost();
		getHostByKey('m3320.qqnews_recsys.cdb.com', $host);
		$host = "{$host->ip}:{$host->port}";
		$user = "stevenyshi_r";
		$passwd = "for_php5_6";
		$database = "qqnews_recsys";
		$this->db1 = new DB($host, $user, $passwd, $database);
		$this->load->model("tool_model");
	}
	
	public function GetWord($options)
	{	
		if(!empty($options["page"]))
		{
			$page = $options["page"];
		}
		else 
		{
			$page = 0;
		}
		
		$where = array();
		
		if(!empty($options["word"]))
		{
			$word = trim($options["word"]);
			$where[] = "word like '%{$word}%'";
		}
		$where = implode(" and ", $where);
		if(!empty($where))
		{
			$where = "where {$where}";
		}
		$pageSize = self::$pageSize;
		$start = $page * $pageSize;
		$sql = "select * from {$options['table']} {$where} order by id desc limit {$start},{$pageSize}";
		$query = $this->db->query($sql);
		$returnData = array();
		$returnData["result"] = $query->result_array();
		$sql = "select count(*) as num from {$options['table']} {$where}";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$returnData["itemNum"] = $result[0]["num"];
		$returnData["pageNum"] = intval($returnData["itemNum"] / $pageSize) + 1;
		$returnData["curPage"] = $page;
		return $returnData;
	}
	
	public function DelWord($options)
	{
		$sql = "delete from {$options['table']} where id={$options['id']}";
		$this->db->query($sql);
	}
	
	public function AddWord($options)
	{
		$sql = "select count(*) as num from {$options['table']} where word ='".mysql_escape_string($options['word'])."'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$num = $result[0]['num'];
		if($num >0)
		{
			return -1;
		}
		$sql = "insert into {$options['table']}(word) values('".mysql_escape_string($options['word'])."')";
		$this->db->query($sql);
		return 0;
	}
	
	public function UpdateWord($options)
	{
		$sql = "select word from {$options['table']}";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	public function AddBlackword($options)
	{
		
		$sql = "update {$options['table']} set flag=1 where word_id={$options['word_id']}";
		$this->db->query($sql);		
	}
	
	public function GetBlackword($options)
	{
		if(!empty($options["page"]))
		{
			$page = $options["page"];
		}
		else
		{
			$page = 0;
		}
						
		$pageSize = self::$pageSize;
		$start = $page * $pageSize;
		$sql = "select * from {$options['table']} where flag=1 order by word_id limit {$start},{$pageSize}";
		$query = $this->db->query($sql);
		$returnData = array();
		$returnData["result_add"] = $query->result_array();
		
		$sql = "select * from {$options['table']} where flag=0 order by word_id";
		$query = $this->db->query($sql);
		$returnData["result_unadd"] = $query->result_array();
		
		$sql = "select count(*) as num from {$options['table']} where flag=1";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$returnData["itemNum"] = $result[0]["num"];
		$returnData["pageNum"] = intval($returnData["itemNum"] / $pageSize) + 1;
		$returnData["curPage"] = $page;
		return $returnData;
	}
	
	public function DelBlackword($options)
	{
		$sql = "update {$options['table']} set flag=0 where word_id={$options['word_id']}";
		$this->db->query($sql);
	}
	
	public function UpdateBlackWord()
	{
		$sql = "select word_id ,word from segment_blackword where flag=1";
		$query = $this->db->query($sql);
		return $query->result_array();
		
	}
	
	public function SimilarMatch($options)
	{
		$where = array();
		$url = "";
		if(empty($options["cmsid"]))
		{
			return;
		}
		
		$where[] = "cmsid='{$options["cmsid"]}'";
		$url .= "cmsid={$options["cmsid"]}&";		
		$where = implode(" and ", $where);
		$select = "*";
		$table = "near_duplicate";
		$order = "similarity desc";
		$data = $this->tool_model->PageAll($table, $select, $where, $order, $url);
		foreach ($data as $key=>$val)
		{
			$cmsid = $val["relid"];
			$sql = "select count(*) as num from near_duplicate where cmsid='{$cmsid}'";
			$query = $this->db->query($sql);
			$result = $query->result_array();
			$data[$key]["simNum"] = $result[0]["num"];
			$data[$key]["cmsid"] = $cmsid;
		}
		
		$db = $this->load->database($this->dbConf);
		foreach ($data as $key=>$val)
		{
			$cmsid = $val["cmsid"];
			$sql = "select title,content,pubtime,src from t_all_news where cmsid='{$cmsid}'";
			$result = $this->db1->getOne($sql);
			$data[$key]["title"] = $result["title"];
			$data[$key]["src"] = $result["src"];
			$data[$key]["pubtime"] = date("Y-m-d H:i:s",$result["pubtime"]);
			$data[$key]["content"] = mb_substr($result["content"],0,500, "utf8");
		}
		
		$return = array();
		$return["cmsid"] = $options["cmsid"];
		$sql = "select title,content,pubtime,src from t_all_news where cmsid='{$options["cmsid"]}'";
		$result = $this->db1->getOne($sql);
		$return["title"] = $result["title"];
		$return["src"] = $result["src"];
		$return["pubtime"] = date("Y-m-d H:i:s",$result["pubtime"]);
		$return["content"] = mb_substr($result["content"],0,500, "utf8");
		$return["data"] = $data;
		return $return;
		
	}
	
	public function MatchList()
	{
		$select = "distinct(cmsid) ";
		$table = "near_duplicate";
		$order = "createtime desc";
		$data = $this->tool_model->PageAll($table, $select,"",$order);
		foreach ($data as $key=>$val)
		{
			$cmsid = $val["cmsid"];
			$sql = "select count(*) as num from near_duplicate where cmsid='{$cmsid}'";
			$query = $this->db->query($sql);
			$result = $query->result_array();
			$data[$key]["simNum"] = $result[0]["num"];
			$data[$key]["cmsid"] = $cmsid;
		}
				
		foreach ($data as $key=>$val)
		{
			$cmsid = $val["cmsid"];
			$sql = "select title,content,pubtime,src from t_all_news where cmsid='{$cmsid}'";
			$result = $this->db1->getOne($sql);			
			$data[$key]["title"] = $result["title"];
			$data[$key]["src"] = $result["src"];
			$data[$key]["pubtime"] = date("Y-m-d H:i:s",$result["pubtime"]);
			$data[$key]["content"] = mb_substr($result["content"],0,400, "utf8");
		}
			
		$return = array();
		$return["data"] = $data;
		return $return;
		
	}
	
	public function GetArticlsByCmsid($options)
	{
		$cmsid = $options["cmsid"];
		$sql = "select title,content from t_all_news where cmsid='{$cmsid}'";
		$result = $this->db1->getOne($sql);
		return $result;
	}
	
	
	public function ClearExpireData()
	{
		$time = time() - 5*24*60*60;
		$sql = "delete from near_duplicate where createtime<{$time}";
		$this->db->query($sql);
		$sql = "select cmsid from near_duplicate union select relid from near_duplicate";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		
		getHostByKey('m4201.spider.cdb.com', $host);
		$host = "{$host->ip}:{$host->port}";
		$user = "spider";
		$passwd = "79fabf334";
		$database = "spider";
		$db = new DB($host, $user, $passwd, $database);	
		foreach ($result as $key=>$val)
		{
			$cmsid = $val["cmsid"];
			$sql = "select count(*) as num from t_all_news where cmsid='{$cmsid}'";
			$return = $this->db1->getOne($sql);
			if(!empty($return["num"]) && $return["num"]>0)
			{
				continue;
			}
			
			$sql = "delete from near_duplicate where cmsid='{$cmsid}' or relid='{$cmsid}'";		
			$db->query($sql);
			
		}
	}
	
	public function getSimilarArticalList()
	{
		$sql = "select cmsid, count(*) as num from near_duplicate group by cmsid having num>200 order by num desc";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}