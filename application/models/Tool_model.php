<?php

class Tool_model extends CI_Model
{
	public static $pageSize = 20;
	public static $pageIndexNum = 4;
	
	public function PageAll($table,$select="",$where="",$order="",$url = "")
	{
		if(isset($_GET["page"]))
		{
			$page = $_GET["page"];
		}
		if(empty($page))
		{
			$page = 1;
		}
		 
		$pageSize = self::$pageSize;
		$start = ($page-1)*$pageSize;
		if(!empty($select))
		{
			$select = $select;
		}
		else
		{
			$select = "*";
		}
		 
		if(!empty($where))
		{
			$where = "where {$where}";
		}
	
		if(!empty($order))
		{
			$order = "order by {$order}";
		}
	
		$sql = "select {$select} from {$table} {$where} {$order} limit {$start},{$pageSize} ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		 
		$sql = "select count(*) as num from {$table} {$where}";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$num = $result[0]["num"];
		$totalPage = intval($num/$pageSize)+1;
		$pageInfo["num"] = $num;
		$pageInfo["curPage"] = $page;
		$pageInfo["totalPage"] = $totalPage;
		$this->load->helper("url");
		$pageInfo["url"] = base_url().uri_string()."?{$url}page=";
		 
		if($page-self::$pageIndexNum>=1 && $page+self::$pageIndexNum<=$totalPage)
		{
			$pageInfo["start"] = $page-self::$pageIndexNum;
			$pageInfo["end"] = $page+self::$pageIndexNum;
		}
		elseif($page-self::$pageIndexNum>=1)
		{
			$pageInfo["end"] = $totalPage;
			if($totalPage - 2*self::$pageIndexNum>=1)
			{
				$pageInfo["start"] = $totalPage - 2*self::$pageIndexNum;
			}
			else
			{
				$pageInfo["start"] = 1;
			}
		}
		elseif($page+self::$pageIndexNum<=$totalPage)
		{
			$pageInfo["start"] = 1;
			if($totalPage - 2*self::$pageIndexNum>=1)
			{
				$pageInfo["end"] = 1 + 2*self::$pageIndexNum;
			}
			else
			{
				$pageInfo["end"] = $totalPage;
			}
	
		}
		else
		{
			$pageInfo["start"] = 1;
			$pageInfo["end"] = $totalPage;
		}
		 
		if($page+2*self::$pageIndexNum<=$totalPage)
		{
			$pageInfo["next"] = $page+2*self::$pageIndexNum;
		}
		else
		{
			$pageInfo["next"] = $totalPage;
		}
			
		 
		$this->config->set_item("pageInfo",$pageInfo);
		return $data;		 
	}
	
	public function Update($table,$set,$where="")
	{
		if(!empty($where))
		{
			$where = "where {$where}";
		}
		$sql = "update {$table} set {$set} $where";
		return $this->db->query($sql);
	}
	
	public function Delete($table,$where)
	{
		$sql = "delete from {$table} where {$where}";
		return $this->db->query($sql);
	}
	
	public function Insert($table,$valueArray)
	{
		if(!empty($valueArray))
		{
			$str1="(";
			$str2 ="(";
			foreach ($valueArray as $key=>$val)
			{
				if($str1=="(" && $str2=="(")
				{
					$str1 .="{$key}";
					$str2 .="{$val}";
				}
				else
				{
					$str1 .=",{$key}";
					$str2 .=",{$val}";
				}
	
			}
			$str1 .=")";
			$str2 .=")";
		}
	
		$sql = "insert into {$table} {$str1} values {$str2}";
		return $this->db->query($sql);
	}
	
	public function Query($table,$select="",$where="",$order="")
	{
		if(!empty($select))
		{
			$select = $select;
		}
		else
		{
			$select = "*";
		}
		 
		if(!empty($where))
		{
			$where = "where {$where}";
		}
	
		if(!empty($order))
		{
			$order = "order by {$order}";
		}
		 
		$sql = "select {$select} from {$table} {$where} {$order} ";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	
	}
	
	public function  getClientIp()
	{
		if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) 
		{ 
			$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]; 
		} 
		elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) 
		{ 
			$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"]; 
		}
		elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]) 
		{ 
			$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
		} 
		elseif (getenv("HTTP_X_FORWARDED_FOR")) 
		{ 
			$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		} 
		elseif (getenv("HTTP_CLIENT_IP")) 
		{ 
			$ip = getenv("HTTP_CLIENT_IP"); 
		} 
		elseif (getenv("REMOTE_ADDR"))
		{ 
			$ip = getenv("REMOTE_ADDR"); 
		} 
		else 
		{ 
			$ip = false; 
		} 
		return $ip;
	}
	
	public function writeLog($filename,$msg)
    {
        $filepath = dirname(dirname(__FILE__))."/logs/".$filename."_".date("YmdH",time()).".log";
        file_put_contents($filepath,$msg,FILE_APPEND);
    }
	
}