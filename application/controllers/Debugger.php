<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once dirname(dirname(__FILE__))."/third_party/simple_html_dom.php";
abstract class Model_Task
{
    public $FetchList = array();
    public $ResultList = array();

    public $IsError = FALSE;
    public $Layer = 0;
    public $ProjectId = 0;
    public $CurrentTaskId = 0;
    public $CurrentUrl = '';
    public $CurrentDomain = '';

    abstract public function OnStart();

    function Fetch($url, $callback, $options = array())
    {
        if(empty($url) || empty($callback))
            return;
        $url = Debugger::AddFullUrl($url, $this->CurrentUrl);

        $this->FetchList[] = array(
            'url' => $url,
            'referer' => $this->CurrentUrl,
            'callback' => $callback,
            'project_id' => $this->ProjectId,
            'parent_task_id' => $this->CurrentTaskId,
            'layer' => $this->Layer + 1,
            'options' => $options,
        );
    }

    function AddResult($data)
    {
        $this->ResultList[] = array(
            "task_id" => $this->CurrentTaskId,
            "project_id" => $this->ProjectId,
            "url" => $this->CurrentUrl,
            "content" => json_encode($data),
        );
    }

    public function GetDomain($url)
    {
        if(empty($url))
            return $this->CurrentDomain;

        preg_match("/http:\/\/[^\/]+\//", $url, $domain);
        return $domain[0];
    }

    function SetError($error = TRUE)
    {
        $this->IsError = $error;
    }

    public function GetUrl()
    {
        return $this->CurrentUrl;
    }

}
//使用simple_html_dom的类
class Model_Response
{
    protected $Html;
    protected $Dom;

    public function __construct($html)
    {
        $this->Html = $html;
        $this->Dom = new simple_html_dom();
        $this->Dom->load($html);
    }

     public function __destruct()
     {
        $this->Dom->clear();
     }

     public function GetDom()
     {
        return $this->Dom;
     }

     public function GetOriHtml()
     {
     	return $this->Html;
     }
     
     public function GetHtml()
     {
        return $this->Html;
     }

     public function Doc($selector)
     {
        return $this->Dom->find($selector);
     }
}

class Debugger extends CI_Controller
{
    const Html    = 0;
    const Image   = 1;
    const Json    = 2;
	const Originalhtml = 3;
	

    public function GenerateScript()
    {
        $project_priority = $this->input->post("project_priority");
        $project_encoding = trim($this->input->post("project_encoding"));
        $project_template = $this->input->post("project_template");
        $project_cookie = trim($this->input->post("project_cookie"));
        $list_url = $this->input->post("list_url");
        $list_dom = $this->input->post("list_dom");
        $list_regex = $this->input->post("list_regex");
        $detail_title = $this->input->post("detail_title");
        $detail_content = $this->input->post("detail_content");
        $detail_contentStart = $this->input->post("detail_contentStart");
        $detail_contentEnd = $this->input->post("detail_contentEnd");
        $detail_pubtime = $this->input->post("detail_pubtime");
        $detail_src = $this->input->post("detail_src");
        $detail_author = $this->input->post("detail_author");
        $detail_config = $this->input->post("detail_config");
        $detail_filter = $this->input->post("detail_filter");
		
		$video_find = $this->input->post("video_find");
        $video_src = $this->input->post("video_src");
        $option = array();
        if(!empty($list_url))
        {
            $option["OnStart"] = json_decode($list_url);
        }
        else
        {
            echo json_encode(array('error'=>'抓取url不能为空！'));
            exit;
        }       
        
        if(empty($list_dom) && $project_template!=2)
        {
            echo json_encode(array('error'=>'链接筛选器不能为空！'));
            exit;
        } 

        if(empty($list_regex) && $project_template!=2)
        {
            echo json_encode(array('error'=>'列表地址过滤正则不能为空！'));
            exit;
        }

        if("自动识别" == $project_encoding)
        {
            $option["encoding"] = "";
        }
        else
        {
            $option["encoding"] = $project_encoding;
        }

        if($detail_filter)
        {
            $detail_filter = json_decode($detail_filter, true);
        }

       // $option["age"] = $project_age;
        $option["priority"] = $project_priority;
        $option["cookie"] = $project_cookie;
        $option["IndexPage"]["doc"] = addslashes(trim($list_dom));
        $option["IndexPage"]["regex"] = trim($list_regex);
        
        $option["DetailPage"]["title"] = addslashes(trim($detail_title));
        $option["DetailPage"]["content"] = addslashes(trim($detail_content));
        $option["DetailPage"]["contentStart"] = trim($detail_contentStart);
        $option["DetailPage"]["contentEnd"] = trim($detail_contentEnd);
        $option["DetailPage"]["pubtime"] = addslashes(trim($detail_pubtime));
        $option["DetailPage"]["images"] = addslashes(trim($detail_content))." img";
        $option["DetailPage"]["source"] = addslashes(trim($detail_src));
        $option["DetailPage"]["author"] = addslashes(trim($detail_author));
		$option["DetailPage"]["video_find"] = addslashes(trim($video_find));
        $option["DetailPage"]["video_src"] = addslashes(trim($video_src));
        $option["DetailPage"]["config"] = $detail_config;
        $option["DetailPage"]["filter"] = $detail_filter;

        if($project_template == 0)
        {
            require_once "newsScript.php";
        }
        else if($project_template == 1)
        {
            require_once "dataMiningScript.php";
        }
        else if($project_template == 2)
        {
            require_once "sinaWeiboScript.php";
        }
        else
        {
            require_once "newsScript.php";
        }
        $script = ProduceScript::produce($option);
        echo json_encode(array('error'=>'', 'script'=>$script));
    }

    public function Run()
    {
        if(empty($_POST["code"]))
        {
            echo json_encode(array('error'=>'没有输入有效代码,或代码脚本正在生成,请再试一次'));
            exit;
        }

        $code = "<?php\n".trim(stripcslashes($_POST["code"]));

        //$user = $_COOKIE['PAS_COOKIE_USER'];//这里吧抓取人的用户信息存入COOKIE
        $user='123456';
        $tmpfile = "tmp/debugger_".$user.".php";
        
        $res=file_put_contents($tmpfile, $code);

        include $tmpfile;
        $data = $this->Debug(new ProjectHandle(), trim($_POST["fetch"]));

        echo json_encode(array('error'=>'', 'result'=>$data));

        unlink($tmpfile);
    }

    public function Debug($task, $fetch)
    {
        if(empty($task))
            return;

        $task->ProjectId = 0;
        $task->Layer = 0;
            
        if(empty($fetch))
        {
            $task->OnStart();
            return array('FetchList'=>$task->FetchList, 'ResultList'=>$task->ResultList);
        }
        else
        {
            $fetch = json_decode($fetch, TRUE);
            $fetchOpts = array(
                "referer" => $fetch["referer"],
            );

            if(isset($fetch["options"]["encoding"]))
            {
                $fetchOpts["encoding"] = $fetch["options"]["encoding"];
            }

            if(!empty($fetch["options"]["agent"]))
            {
                $fetchOpts["agent"] = $fetch["options"]["agent"];
            }

            if(isset($fetch["options"]["type"]) && $fetch["options"]["type"] == "json")
            {
                $fetchOpts["type"] = self::Json;
            }
			
			if(isset($fetch["options"]["type"]) && $fetch["options"]["type"] == "Originalhtml")
			{
				$fetchOpts["type"] = self::Originalhtml;
			}
			
			if(isset($fetch["options"]["post"]))
			{				
				$fetchOpts["post"] = $fetch["options"]["post"]; 
			}
			
			if(isset($fetch["options"]["head"]))
			{
				$fetchOpts["head"] = $fetch["options"]["head"];
			}
			
			if(isset($fetch["options"]["cookie"]))
			{
				$fetchOpts["cookie"] = $fetch["options"]["cookie"];
			}

            $resp = $this->FetchUrl($fetch["url"], $fetchOpts);
            //var_dump($resp);exit;
            //var_dump($fetch);exit;
            $task->CurrentTaskId = 0;
            $task->CurrentUrl = $fetch["url"];
            $task->CurrentDomain = $task->GetDomain($fetch["url"]);
            $task->Layer = intval($fetch['layer']) + 1;

            $options = array();
            $options["data"] = isset($fetch["options"]["data"]) ? $fetch["options"]["data"] : "";
            $options["url"] = $fetch["url"];
            //不再将设置的编码隐式传入脚本中，脚本必须显示指定编码
            //$options["encoding"] = $fetch["options"]["encoding"];
            $options["domain"] = $task->GetDomain($fetch["url"]);

            $task->$fetch["callback"]($resp, $options);
            		 $this->load->model("redis_model");
                         $redisKey = "debug_preview";
                @$this->redis_model->set($redisKey,$task->ResultList[0]['content'],600);
            //var_dump($_POST);exit; 
            /*
            if($_POST['from'] == 'dataclean' &&$task->ResultList[0]['content'])
            {

                $user = $_COOKIE['PAS_COOKIE_USER'];
                $redisKey = "dataclean_debuger_".$user;
		 $this->load->model("redis_model");
                $this->redis_model->set($redisKey,json_encode($task->ResultList[0]),3600);

            }
            else if($task->ResultList[0]['content'])
            {
                $user = $_COOKIE['PAS_COOKIE_USER'];
                $redisKey = "debug_preview_".$user;
		 $this->load->model("redis_model");
                $this->redis_model->set($redisKey,$task->ResultList[0]['content'],600);
            }*/

            return array('FetchList'=>$task->FetchList, 'ResultList'=>$task->ResultList);
        }
    }

    public function Preview()
    {
        //$user = $_COOKIE['PAS_COOKIE_USER'];
        //$redisKey = "debug_preview_".$user;
		$this->load->model("redis_model");
        $res = $this->redis_model->get($redisKey);
        $res = json_decode($res, true);

        require_once "Proxy.php";
        $content = $res['content'];
        $Dom = new simple_html_dom();
        $Dom->load($content);
        $images = $Dom->find("img");
        foreach($images as $image)
        {   
            $url = $image->src;
            $image->src = "http://crawl.webdev.com/proxy/url?url=$url"; 
        }   
        $res['content'] = $Dom;

        $this->load->view('debug_preview',$res);
    }

    public function FetchUrl($url, $options = array())
    {
        $curl = curl_init();
        if(!empty($options["referer"]))
            curl_setopt($curl, CURLOPT_REFERER, $options["referer"]);

        if(!empty($options["agent"]))
        {
            curl_setopt($curl, CURLOPT_USERAGENT, $options["agent"]);
        }
        
        else 
        {   
            $userAgent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.134 Safari/537.36";
            curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        }
        if(!empty($options["cookie"]))
        {
            curl_setopt($curl, CURLOPT_COOKIE, $options["cookie"]);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
		/*if(!preg_match("/http:\/\/(.*?)\.webdev\.com\//", $url))
		{
			curl_setopt($curl, CURLOPT_PROXY, "http://115.159.5.247:80");
			//curl_setopt($curl, CURLOPT_PROXY, "http://10.130.145.102:80");

		}*/
		
    	if(!empty($options["post"]))
        {
        	curl_setopt($curl, CURLOPT_POST, true);
        	curl_setopt($curl, CURLOPT_POSTFIELDS, $options["post"]);
        }
		
        
        if(!empty($options["head"]))
        {
        	curl_setopt($curl, CURLOPT_HTTPHEADER, $options["head"]);
        }
		
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        $html = curl_exec($curl);
		$flagId1 = ord(substr($html, 0, 1)); 
		$flagId2 = ord(substr($html, 1, 1)); 
		//压缩文件解压
		if($flagId1 == 31 && $flagId2 == 139)
		{
			$html = $this->gzdecode($html);
		}
        
        $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

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

        curl_close($curl);

        if(isset($options["type"]) && $options["type"] == self::Json)
            return json_decode($html, TRUE);

		if(isset($options["type"]) && $options["type"] === self::Originalhtml)
			return $html;

        $response = new Model_Response($html);       
        $html_dom = $response->GetDom();
        $this->HandleHtmlUrl($html_dom, $url);
        return $response;
    }
    
    public function HandleHtmlUrl(&$html_dom, $current_url)
    {
    	foreach ($html_dom->find("[src]") as $element)
    	{
    		$element->src = $this->AddFullUrl($element->src, $current_url);
    	}
    
    	foreach ($html_dom->find("[href]") as $element)
    	{
    		$element->href = $this->AddFullUrl($element->href, $current_url);
    	}
    }
	
	public function gzdecode($data) 
	{ 
		$flags = ord(substr($data, 3, 1)); 
		$headerlen = 10; 
		$extralen = 0; 
		$filenamelen = 0; 
		if ($flags & 4) { 
			$extralen = unpack('v' ,substr($data, 10, 2)); 
			$extralen = $extralen[1]; 
			$headerlen += 2 + $extralen; 
		} 
		if ($flags & 8) // Filename 
			$headerlen = strpos($data, chr(0), $headerlen) + 1; 
		if ($flags & 16) // Comment 
			$headerlen = strpos($data, chr(0), $headerlen) + 1; 
		if ($flags & 2) // CRC at end of file 
			$headerlen += 2; 
		$unpacked = @gzinflate(substr($data, $headerlen)); 
		if ($unpacked === FALSE) 
			$unpacked = $data; 
		return $unpacked; 
	}
    
    static public function AddFullUrl($handle_url, $current_url)
    {
    	$handle_url = trim($handle_url);
    	$current_url = trim($current_url);
    	if(empty($handle_url) || empty($current_url))
    	{
    		return $handle_url;
    	}
    	preg_match("/http:\/\/[^\/]+[\/]?/", $current_url, $match);
    	$domain = $match[0];
    	if(substr($domain,-1) != '/')
    	{
    		$domain .= '/';
    	}
    
    	if(substr($handle_url, 0, 7) != "http://" && substr($handle_url, 0, 11) !="javascript:")
    	{
    		if(substr($handle_url, 0, 1) == "/")
    		{
    			$handle_url = $domain.substr($handle_url, 1);
    		}
    		elseif(substr($handle_url, 0, 2) == "./" || substr($handle_url, 0, 1) != ".")
    		{
    			$urlArray = explode('/',$current_url);
    			if(count($urlArray)<3)
    			{
    				return $handle_url;
    			}
    			$pre_url = $domain;
    			for($i=3; $i<count($urlArray)-1; $i++)
    			{
    				if(empty($urlArray[$i]))
    				{
    					continue;
    				}
    				$pre_url .= $urlArray[$i]."/";
    			}
    			if(substr($handle_url, 0, 2) == "./")
    			{
    				$handle_url = $pre_url.substr($handle_url, 2);
    			}
    			else
    			{
    				$handle_url = $pre_url.$handle_url;
    			}
    			 
    		}
    		elseif(substr($handle_url, 0, 3) == "../")
    		{
    			$count = 0;
    			while(substr($handle_url, 0, 3) == "../")
    			{
    				$count ++;
    				$handle_url= substr($handle_url, 3);
    			}
    				 
    			$urlArray = explode('/',$current_url);
    			if(count($urlArray)<3)
    			{
    				return $handle_url;
    			}
    
    			$pre_url = $domain;
    			for($i=3; $i<count($urlArray)-1-$count; $i++)
    			{
    				if(empty($urlArray[$i]))
    				{
    					continue;
    				}
    				$pre_url .= $urlArray[$i]."/";
    			}
    			$handle_url = $pre_url.$handle_url;
    		}
    	}
    	return $handle_url;
    }
        
}


