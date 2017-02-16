<?php

class Multicurl_model {
    
	protected $mh;
	protected $timeout = 5000;
	protected $conn_timeout = 1000;
	protected $handles = array();
	
	function __construct()
	{
		$this->mh = curl_multi_init();
	}
	
	function __destruct()
	{
		curl_multi_close($this->mh);
	}
 
    //加入一个curl并发请求
    public function multiAdd($key,$url,$method='get',$post_data = array(),$header = array(),$timeout='',$conn_timeout='') 
    {
		if(empty($url))
		{
			return false;
		}
		if(empty($key))
		{
			$key = 0;
		}
		$timeout = isset($timeout) ? $timeout : $this->timeout;
		$conn_timeout = isset($conn_timeout) ? $conn_timeout : $this->conn_timeout;

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER        , 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
		
		if(!preg_match("/http:\/\/(.*?)\.webdev\.com\//", $url))
        {
			require_once dirname(dirname(__FILE__))."/config/proxy.php";
			curl_setopt($ch, CURLOPT_PROXY, Config_Proxy::$config[mt_rand(0, count(Config_Proxy::$config) - 1)]);
		}
		
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $conn_timeout);
		if(isset($header))
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		if(strncasecmp($method, 'post', 4) === 0)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			if(isset($post_data))
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			}
		}
		curl_multi_add_handle($this->mh, $ch);	
		$this->handles[$key] = $ch;
		return true;
	}
    //获取并发curl结果
    //为防止假死，设置循环最大执行时间，为0为不设最大执行时间
	public function multi($max_timeout=5)
	{
		$active = null;
        $t1 = time();
        do
		{
			$mrc = curl_multi_exec($this->mh, $active);
		}
		while($mrc == CURLM_CALL_MULTI_PERFORM);
		while($active && $mrc == CURLM_OK)
        {
            if($max_timeout >0 && (time() - $t1) > $max_timeout){
               //exec time out,exit loop;
                break;
            }
			//PHP_VERSION_ID < 50214 ? usleep(5000) : curl_multi_select($this->mh, 0.2);
            if(curl_multi_select($this->mh, 0.2)!=-1){
                do
			    {
				    $mrc = curl_multi_exec($this->mh, $active);
			    }
                while($mrc == CURLM_CALL_MULTI_PERFORM);
            }
		}
		$stat = array();
		$data = array();
		foreach($this->handles as $key => $ch)
		{
			$d = array();
			$d['errno'] = curl_errno($ch);
			if($d['errno'] != CURLE_OK)
			{
				$d['errmsg'] = curl_error($ch);
				continue;
			}
			else
			{
				$d['total_time'] = curl_getinfo($ch, CURLINFO_TOTAL_TIME );
				$d['code']       = curl_getinfo($ch, CURLINFO_HTTP_CODE  ); 
				$d['data']       = curl_multi_getcontent($ch);
				
				$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
				if(preg_match("/charset=([^ ;]+)/", $contentType, $matches) &&
                strtolower($matches[1]) != "utf-8" &&
                strtolower($matches[1]) != "utf8")
				{
					$to = iconv($matches[1], "UTF-8", $d['data']);
					if(!empty($to)) 
						$d['data'] = $to;
				}
				else
				{
					$encode = mb_detect_encoding($d['data'], array("UTF-8","GB2312","GBK"));
					if($encode != 'UTF-8')
					{
						$to = mb_convert_encoding($d['data'],'utf-8',$encode);
						if(!empty($to)) 
						$d['data'] = $to;
					}
				}
			}
			$data[$key] = $d;
			curl_multi_remove_handle($this->mh, $ch);
			curl_close($ch);
		}
		$this->handles = array();
		return $data;
	}
	
}



