<?php

class Dataclean_model extends CI_Model
{
	public static $pageSize = 20;
	public static $pageIndexNum = 4;
	
   public function getProjectList()
   {
   		$select = "project_id,name,openid,user,tag,create_time,update_time";
   		$order = "update_time desc";
   		
   		$where = array();
   		$url = "";
   		if(!empty($_GET["name"]))
   		{
   			$name = trim($_GET["name"]);
   			$where[] = "name like '%{$name}%'";
   			$url .="name={$name}&";
   			$returnData["name"] = $name;
   		}
   		
   		if(!empty($_GET["user"]))
   		{
   			$user = trim($_GET["user"]);
   			$where[] = "user='{$user}'";
   			$url .="user={$user}&";
   			$returnData["user"] = $user;
   		}
   		
   		if(!empty($_GET["openid"]) )
   		{
   			$openid = trim($_GET["openid"]);	
   			$where[] = "openid='{$openid}'";
   			$url .="openid={$openid}&";
   			$returnData["openid"] = $openid;
   		}
   		
   		$where = implode(" and ", $where);
   		
       	$data =  $this->PageAll("dataclean_project",$select,$where,$order,$url);
        foreach($data as $key=>$val)
        {
            $tag = explode(",",$val["tag"]);
            $tagName = array();
            foreach($tag as $k=>$v)
            {
                $v = trim($v);
                if(!empty($v))
                {
                    $tagName[] = $v;
                }
            }
            
            $data[$key]["tag"] = $tagName;
        } 
        $returnData["projectList"] = $data; 
        return $returnData;
   }
   

   public function getResponseList()
   {
	   	if(!empty($_GET["project_id"]))
	   	{
	   		$project_id = trim($_GET["project_id"]);
	   		$select = "name";
	   		$where = "project_id = {$project_id}";
	   		$result = $this->Query("spider_project",$select,$where);
	   		if(!empty($result))
	   		{
	   			$project_name = $result[0]["name"];
	   			$returnData["name"] = $project_name;
	   		}
	   	}
	   	
	   	$where = array();
	   	$url = "";
	   	if(!empty($project_id))
	   	{
	   		$where[] = "project_id = {$project_id}";
	   		$url .= "project_id={$project_id}&";
	   		$returnData["project_id"] = $project_id;
	   	}
	   	
	   	if(!empty($_GET["url"]))
	   	{
	   		$u = trim($_GET["url"]);
	   		$where[] = "url like'%{$u}%'";
	   		$returnData["url"] = $u;
	   		$u = urlencode($u);
	   		$url .= "url={$u}&";
	   	}
        
		$_GET["openid"] = trim($_GET["openid"]);
		if(!empty($_GET["openid"]))
	   	{
            $openid = trim($_GET["openid"]);
	   		$where[] = "openid = '{$openid}'";
	   		$url .= "openid={$openid}&";
	   		$returnData["openid"] = $openid;
	   	}
	   	 
	   	$where = implode(" and ", $where);
	   	$order = "create_time desc";
	   	$select = "id,project_id,openid,url,create_time,status";
	   	$data = $this->PageAll("dataclean_result",$select,$where,$order,$url);
	   	
	   	if(!empty($data))
	   	{
	   		foreach ($data as $key=>$val)
	   		{	   			   			
	   			$data[$key]["create_time"] = date("Y-m-d H:i:s",$val["create_time"]);
	   			
	   		}
	   	}
	   	 
	   	$returnData["responseList"] = $data;
	   	return $returnData;
	   		   		   		   	
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
    

    public function AddProject($data)
    {
    	$this->db->insert('dataclean_project', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function AddScriptHistory($data)
    {
    	$this->db->insert('spider_script_history', $data);
    }

    public function UpdateProject($id, $data)
    {
        $this->db->where('project_id', $id);
        $this->db->update('dataclean_project', $data); 
    }

    public function GetProjectByName($name)
    {
    	$this->db->select('project_id');
        $this->db->where('name',$name);
    	$query = $this->db->get('dataclean_project');
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

    public function getResponseListById($id)
    {
    	$this->db->select('*');
        $this->db->where('id',$id);
    	$query = $this->db->get('dataclean_result');
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

    public function GetProjectByOpenid($openid)
    {
    	$this->db->select('openid,project_id');
        $this->db->where('openid',$openid);
    	$query = $this->db->get('dataclean_project');
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
   
    public function GetProjectDetailInfo($id)
    {
        $this->db->select('*');
        $this->db->from('dataclean_project');
        $this->db->where("project_id = $id");
        $query = $this->db->get();
        $ret = $query->result_array();
        if(count($ret) != 0)
        {
            return $ret[0];
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
    
    public function AddHandleResult($dbResult)
    {
        $nowTime = time();
        $table = "dataclean_result";
        foreach($dbResult as $key=>$value)
        {
        
            $sql = " insert into {$table} (`project_id`,`openid`,`cmsid`,`url`,`before_content`,`after_content`,`status`,`create_time`,`update_time`) values ";
            $sql .= " (";
            $sql .= "{$value['project_id']},'{$value['openid']}','{$value['cmsid']}','".mysql_escape_string($value['url'])."'";
            $sql .= ", '".mysql_escape_string($value['before_content'])."','".mysql_escape_string($value['after_content'])."',";
            $sql .= " {$value['status']},{$nowTime},{$nowTime}";
            $sql .= " )";
        
		    $this->db->query($sql);		
        
        }
    }
	
	public function FetchUrl($url, $options = array())
    {
        $curl = curl_init();

        if(isset($options["referer"]))
            curl_setopt($curl, CURLOPT_REFERER, $options["referer"]);

        if(!empty($options["agent"]))
            curl_setopt($curl, CURLOPT_USERAGENT, $options["agent"]);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
		if(!preg_match("/http:\/\/(.*?)\.webdev\.com\//", $url))
        {
			require_once dirname(dirname(__FILE__))."/config/proxy.php";
			curl_setopt($ch, CURLOPT_PROXY, Config_Proxy::$config[mt_rand(0, count(Config_Proxy::$config) - 1)]);
		}
       // curl_setopt($curl, CURLOPT_PROXY, "http://10.130.145.102:80");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        $html = curl_exec($curl);
        $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
		curl_close($curl);
        if(!empty($options["encoding"]))
        {
            $to = iconv($options["encoding"], "UTF-8", $html);
            if(!empty($to)) 
                $html = $to;
        }
        else if(preg_match("/charset=([^ ;]+)/", $contentType, $matches) &&
                strtolower($matches[1]) != "utf-8" &&
                strtolower($matches[1]) != "utf8")
        {
            $to = iconv($matches[1], "UTF-8", $html);
            if(!empty($to)) 
                $html = $to;
        }
		else
		{
			$encode = mb_detect_encoding($html, array("UTF-8","GB2312","GBK"));
			if($encode != 'UTF-8')
			{
				$html = mb_convert_encoding($html,'utf-8',$encode);
			}
		}

        if(isset($options["type"]) && $options["type"] == 'json')
            return json_decode($html, TRUE);
		if(isset($options["type"]) && $options["type"] == 'html')
            return $html;
		
		$response = new Model_Response($html); 
		return $response;
    }
	
	public function filterChar($word)
	{
		$word = trim($word);
		$word = trim($word,'　');
		while(ord($word[0]) == 194 && ord($word[1]) == 160)
		{
			$word = substr($word,2);
		}
		while(ord(substr($word,-2,1)) == 194 && ord(substr($word,-1,1)) == 160)
		{
			$word = substr($word,0,-2);
		}
		$word = trim($word);
		return $word;
	}
	
	public function getContentSartEnd($ReadabilityData)
	{
		$content = $ReadabilityData['content'];
		if(empty($content))
		{
			return '';
		}

		$className = $ReadabilityData['className'];
		$idName = $ReadabilityData['idName'];
		if($className || $idName)
		{
			$findName = $className ? '.'.$className : '#'.$idName;
			$Dom = new simple_html_dom();
			$content = str_replace("alt=\"\\\"","",$content);
			$Dom->load($content);
			$body = $Dom->find($findName);
			while(count($body)==1 && method_exists($body[0],children))
			{
				$body = $body[0]->children;
			}
			foreach($body as $key=>$value)
			{
				$outertext = trim($value->outertext);
				$word = trim($value->plaintext);
				$word = strip_tags($outertext);
				$word = $this->filterChar($word);
				$outertext =  $this->filterChar($outertext);
				if($outertext && $word)
				{
					$contentEndHtml = $outertext;
					$contentEndWord = $word;
					$endHtmlDom = $value;
					if(empty($contentStartHtml) && empty($contentStartWord))
					{
						$contentStartHtml = $outertext;
						$contentStartWord = $word;
						$startHtmlDom = $value;
					}
				}
			}
			while( (method_exists($startHtmlDom,children) && count($startHtmlDom->children)>1)
			|| (method_exists($startHtmlDom->children[0],children) && count($startHtmlDom->children[0]->children)>1))
			{
				if(method_exists($startHtmlDom,children) && count($startHtmlDom->children)>1)
					$cycleBody = $startHtmlDom->children;
				else
					$cycleBody = $startHtmlDom->children[0]->children;
				
				$flag = 0;
				foreach($cycleBody as $key=>$startValue)
				{
					$outertext = trim($startValue->outertext);
					$word = trim($startValue->plaintext);
					$word = strip_tags($outertext);
					$word = $this->filterChar($word);
					$outertext =  $this->filterChar($outertext);
					if($outertext && $word)
					{
						$contentStartHtml = $outertext;
						$contentStartWord = $word;
						$startHtmlDom = $startValue;
						$flag = 1;
						break;
					}
				}
				if(empty($flag))
				{
					break;
				}
			}
			
			while( (method_exists($endHtmlDom,children) && count($endHtmlDom->children)>1)
			|| (method_exists($endHtmlDom->children[0],children) && count($endHtmlDom->children[0]->children)>1) )
			{
				if(method_exists($endHtmlDom,children) && count($endHtmlDom->children)>1)
					$cycleBody = $endHtmlDom->children;
				else
					$cycleBody = $endHtmlDom->children[0]->children;
				
				$flag = 0;
				foreach($cycleBody as $key=>$endValue)
				{
					$outertext = trim($endValue->outertext);
					$word = trim($endValue->plaintext);
					$word = strip_tags($outertext);
					$word = $this->filterChar($word);
					$outertext =  $this->filterChar($outertext);
					if($outertext && $word)
					{
						$contentEndHtml = $outertext;
						$contentEndWord = $word;
						$endHtmlDom = $endValue;
						$flag = 1;
					}
				}
				if(empty($flag))
				{
					break;
				}
			}
			
		}
		if($contentStartHtml && $contentStartWord && $contentEndHtml && $contentEndWord)
		{
			return array($contentStartHtml , $contentStartWord , $contentEndHtml , $contentEndWord);
		}
		
		preg_match_all("/<p>(.*?)<\/p>/si", $content, $match);
		if($match[1])
		{
			foreach($match[1] as $key=>$value)
			{
				$value = trim($value);
				$word = strip_tags($value);
				$word = trim($word);
				$word = trim($word,'　');
				$value = trim($value,'　');
				if($value && $word)
				{
					$contentEndHtml = $value;
					$contentEndWord = $word;
					if(empty($contentStartHtml) && empty($contentStartWord))
					{
						$contentStartHtml = $value;
						$contentStartWord = $word;
					}
				}
			}
		}
		
		return array($contentStartHtml , $contentStartWord , $contentEndHtml , $contentEndWord);
	}
	
	public  function commonDrawContent($params,&$errno)
	{
		try
		{
			require_once dirname(dirname(__FILE__)).'/libraries/Readability.php';
			require_once dirname(dirname(__FILE__))."/third_party/simple_html_dom.php";
			
			$html = $params['draw_content'];
			
			$Readability     = new Readability($html);
			$ReadabilityData = $Readability->getCommon();
			if(empty($ReadabilityData['content']))
			{
				$msg = date("Y-m-d H:i:s",time());
				$msg .= ",auto draw Readability return null,url:{$params['url']}\n";
				$this->writeLog("onlineclean_error",$msg);	
			}
			
			$title = $ReadabilityData['title'];
			$title = $this->filterTitle($title,'_');
			$title = $this->filterTitle($title,'|');
			
			list($contentStartHtml , $contentStartWord , $contentEndHtml , $contentEndWord) = $this->getContentSartEnd($ReadabilityData);
		
			$Dom = new simple_html_dom();
			$html = str_replace("alt=\"\\\"","",$html);
			$Dom->load($html);
			$body = $Dom->find('body');
			
			if($contentStartHtml && $contentStartWord && $contentEndHtml && $contentEndWord)
			{
				//广度搜索  body,直到最小包含 正文内容的html为止
				$this->theContent = '';
				$this->beferContent = '';
				$this->BreadthSearch($body,$contentStartHtml , $contentStartWord , $contentEndHtml ,$contentEndWord);
				if($contentStartWord == $contentEndWord || $contentStartHtml==$contentEndHtml)
				{
					$content = $this->beferContent;
				}
				else
				{
					$content = $this->theContent;
				}
			}
			if(empty($content) || $this->isTooShortContent($params['url'],$html,$content))
			{
				$this->BodyHtmlContent = '';
				$this->getHtmlContent($body);
				$content = $this->BodyHtmlContent ? $this->BodyHtmlContent : $body[0]->innertext;
				
				$msg = date("Y-m-d H:i:s",time());
				$msg .= ",auto draw find content start and end null,or Readability content is too short,url:{$params['url']}\n";
				$this->writeLog("onlineclean_error",$msg);	
			}
			
			if(empty($content))
			{
				$errno = 5;
				$msg = date("Y-m-d H:i:s",time());
				$msg .= ",auto draw the result content null,url:{$params['url']}\n";
				$this->writeLog("onlineclean_error",$msg);	
			}
			
			$res = array();
			$res['title'] = $title;
			$res['content'] = $content;

		}
		catch(Exception $e)
		{
			$errno = 6;
			$msg = date("Y-m-d H:i:s",time()).",errno:".$e->getCode().",errmsg:".$e->getMessage();
            $msg .= ",auto draw exception ,url:{$params['url']}\n";
            $this->writeLog("onlineclean_error",$msg);	
		}
		return json_encode($res);
	}
	
	public function filterTitle($title,$filter="_")
	{
		$arr = explode($filter,$title);
		if(empty($arr) || count($arr) == 1)
		{
			return $title;
		}
		$titleArr = array();
		$i=0;
		foreach($arr as $key=>$value)
		{
			$value = trim($value);
			if($value)
			{
				if(preg_match("/^[a-z]*/i",$value) && $i>0)
				{
					$titleArr[$i-1] .= $filter.$value;
				}
				else
				{
					$titleArr[$i] .= $value;
					$i++;
				}
			}
		}
		
		if(empty($titleArr) || count($titleArr) == 1)
		{
			return $title;
		}
		$prefixTitle = $titleArr[0];
		$prefixTitleLen = mb_strlen($prefixTitle,'utf-8');
		if(empty($prefixTitleLen))
		{
			return $title;
		}
		if($prefixTitleLen>10)
		{
			return $prefixTitle;
		}
		return $prefixTitle."_".$titleArr[1];
	}
	
	public $BlacklistHost = array('www.qwgtw.com');
	
	public function isTooShortContent($url,$html,$content)
	{
		$hostInfo = parse_url($url);
		$host = $hostInfo['host'];
		if($host && in_array($host,$BlacklistHost))
		{
			return true;
		}
		$html = strip_tags($html);
		$content = strip_tags($content);
		$html_strlen = mb_strlen($html,'utf-8');
		$content_strlen = mb_strlen($content,'utf-8');
		if($html_strlen && $content_strlen && $html_strlen > $content_strlen * 100)
		{
			return true;
		}
		return false;
	}
	
	public function getHtmlContent($body)
	{
		if($this->BodyHtmlContent)
		{
			return;
		}
		if($body && is_array($body))
		{
			foreach($body as $key=>$value)
			{
				$id = strtolower($value->id);
				$class = strtolower($value->class);
				if($id && ( !(strpos($id,'content')===false) || !(strpos($id,'article')===false)) && (strpos($id,'title')===false))
				{
					$this->BodyHtmlContent = $value->outertext; 
					break;
				}
				if($class && ( !(strpos($class,'content')===false) || !(strpos($class,'article')===false)) && (strpos($class,'title')===false))
				{
					$this->BodyHtmlContent = $value->outertext;
					break;
				}
				if(method_exists($value,children))
				{
					$this->getHtmlContent($value->children);
				}
				if($this->BodyHtmlContent)
				{
					break;
				}
			}
		}
		return ;
	}
	
	public function BreadthSearch($body,$contentStartHtml , $contentStartWord , $contentEndHtml ,$contentEndWord)
	{
		foreach($body as $key=>$value)
		{
			if( $this->hasWords($value,$contentStartHtml,$contentStartWord)  && 
				$this->hasWords($value,$contentEndHtml,$contentEndWord) )
			{
				$this->beferContent = $this->theContent;
				$this->theContent = $value->outertext;
				if(method_exists($value,children))
				{
					$this->BreadthSearch($value->children,$contentStartHtml , $contentStartWord , $contentEndHtml ,$contentEndWord);
				}
				break;
			}
		}
		return ;
	}
	
	public function hasWords($content,$html,$word)
	{
		if(!(strpos($content->outertext,$html) === false) || !(strpos($content->plaintext,$word) === false))
		{
			return true;
		}
		
		$content->outertext = htmlspecialchars_decode($content->outertext);
		$content->plaintext = htmlspecialchars_decode($content->plaintext);
		$content->outertext = str_replace('&ldquo;','“',$content->outertext);
		$content->plaintext = str_replace('&ldquo;','“',$content->plaintext);
		$content->outertext = str_replace('&rdquo;','”',$content->outertext);
		$content->plaintext = str_replace('&rdquo;','”',$content->plaintext);
		$content->plaintext = str_replace('&nbsp;','',$content->plaintext);
		$content->outertext = str_replace('&nbsp;','',$content->outertext);
		
		if(!(strpos($content->outertext,$html) === false) || !(strpos($content->plaintext,$word) === false))
		{
			return true;
		}
		if(mb_strlen($word,'utf-8') > 15)
		{
			$arr = explode(chr(194).chr(160),$word);
			if(count($arr)==1)
			{
				$arr = explode(' ',$word);
			}
			if(count($arr)==1)
			{
				$word = mb_substr($word,0,10,'utf-8');
			}
			else
			{
				foreach($arr  as $key=>$value)
				{
					$value= trim($value);
					if($value)
					{
						$word = $value;
						break;
					}
				}
			}
		}
		else
		{
			$word = str_replace(chr(194).chr(160),' ',$word);
		}
		if(!(strpos($content->outertext,$html) === false) || !(strpos($content->plaintext,$word) === false))
		{
			return true;
		}
		
		return false;
	}

	public function writeLog($filename,$msg)
    {
        $filepath = dirname(dirname(__FILE__))."/logs/".$filename."_".date("YmdH",time()).".log";
        file_put_contents($filepath,$msg,FILE_APPEND);
    }
	
}







