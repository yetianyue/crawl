<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once dirname(dirname(__FILE__))."/third_party/simple_html_dom.php";
require_once dirname(dirname(__FILE__))."/libraries/FetchUtil.php";


class Onlineclean extends CI_Controller
{
	function __construct()
	{
        parent::__construct();
        $this->load->model("redis_model");
	$this->load->model("Multicurl_model");
		
    }


    public function Handle()
    {
         ini_set('memory_limit','1024M');

         //  ini_set('display_errors', 'on');
         //   error_reporting(E_ALL);
         //test
/*

         $params= array();

        $html= $this->dataclean_model->FetchUrl('http://mp.weixin.qq.com/s?__biz=MjM5NzM2MTE3Nw==&mid=208385644&idx=3&sn=7648a13bdb47e0213ed6e1df7720c585&3rd=MjM5NzM2NjUzNg==&scene=8#rd',array('type'=>
    'html'));
         $response = new Model_Response($html);
         $contentsRes = $response->Doc('div.rich_media_content');
         $content = $contentsRes[0]->innertext;
         $params[] = array('openid'=>'oCOnkjmVNYEa9EKF1WuKU79TThcg','cmsid'=>'20150721B03NV300','url'=>'http://mp.weixin.qq.com/s?__biz=MjM5NzM2MTE3Nw==&mid=208385644&idx=3&sn=7648a13bdb47e0213ed6e1df7720c585&3rd=MjM5NzM2NjUzNg==&scene=8#rd','content'=>$content);

		$html= $this->dataclean_model->FetchUrl('http://mp.weixin.qq.com/s?__biz=MzA4Mzc5OTgzMQ==&mid=211150413&idx=4&sn=3c353d2313f02d7381c59eaa2b3eafb5&3rd=MjM5NzM2NjUzNg==&scene=8#rd',array('type'=>
    'html'));
         $response = new Model_Response($html);
         $contentsRes = $response->Doc('div.rich_media_content');
         $content = $contentsRes[0]->innertext;
         $params[] = array('openid'=>'oCOnkjomvhdfVAnFLkpYNNhkzHYQ','cmsid'=>'20150721B03NV300','url'=>'http://mp.weixin.qq.com/s?__biz=MzA4Mzc5OTgzMQ==&mid=211150413&idx=4&sn=3c353d2313f02d7381c59eaa2b3eafb5&3rd=MjM5NzM2NjUzNg==&scene=8#rd','content'=>$content);
         $data = json_encode($params);

		$params= array();
		$params[] = array('openid'=>'','cmsid'=>'','url'=>'http://www.yidianzixun.com/n/0AEPsfhF?s=3','content'=>'');
		$params[] = array('openid'=>'','cmsid'=>'','url'=>'http://toutiao.com/a5214717452/?tt_from=weixin%2525252525252525252525252525252525252','content'=>'');
        $data = json_encode($params);

*/
         $start_time = time();
         try
         {
            $data = trim($this->input->post("data"));
			$data = $this->getParams($data);
			
			$this->load->model("dataclean_model");
			
            $dbResult = array();
            $info = array();
            foreach($data as $key=>$value)
            {
                $tmp = array();
                $tmp['errno'] = 0;
                $tmp['openid'] = $value['openid'];
                $tmp['cmsid'] = $value['cmsid'];
                $tmp['url'] = $value['url'];
				$tmp['content'] = '';
                if($value['openid'] && ($value['content'] || $value['draw_content']))
                {

                    $redisKey = 'dataclean_openid_'.$value['openid'];
                    $projectInfo = $this->redis_model->get($redisKey);
                    $projectInfo = json_decode($projectInfo,true);
					//没有模板走通用提取策略
                    if(empty($projectInfo) || empty($projectInfo['script']) ||empty($projectInfo['project_id']) || empty($projectInfo["update_time"]))
                    {
						if($value['draw_content'])
						{
							$after_content = $this->dataclean_model->commonDrawContent($value,$tmp['errno']);
							if($tmp['errno']==0 )
							{
								$tmp['content'] = $after_content;
							}
						}
						else
						{
							$tmp['errno'] = 1;
						}
                    }
                    else//有模板 走模板处理
                    {
                        $after_content = $this->drawContent($projectInfo,$value,$tmp['errno']);
                        if($tmp['errno']==0 )
                        {
                            $tmp['content'] = $after_content;
                        }
                    }

                }
                elseif(empty($value['content']) && empty($value['draw_content']))
                {
                    $tmp['errno'] = 4;
                }
				else
				{
					$tmp['errno'] = 3;
				}
				$tmp['total_time'] = $value['total_time'] ? $value['total_time'] : 0;
				$tmp['content'] = urlencode($tmp['content']);
                $info[] = $tmp;
				
				$value['content'] = $value['content'] ? $value['content'] : '';
				$value['cmsid'] = $value['cmsid'] ? $value['cmsid'] : '';
				$value['url'] = $value['url'] ? $value['url'] : '';
				$projectInfo['project_id'] = $projectInfo['project_id'] ? $projectInfo['project_id'] : 0;
				$before_content = $value['draw_content'] ? $value['draw_content'] : $value['content'];
				$after_content = $after_content ? $after_content : '';
                $dbResult[] = array(
                'project_id' =>$projectInfo['project_id'],
                'openid' =>$value['openid'],
                'cmsid' =>$value['cmsid'],
                'url' =>$value['url'] ,
                'before_content' =>$before_content,
                'after_content' =>$after_content,
                'project_id' =>$projectInfo['project_id'],
                'status'=>$tmp['errno'],
                );
            }

            try{
                $this->dataclean_model->AddHandleResult($dbResult);
            }
            catch(Exception $e)
            {
                $msg = date("Y-m-d H:i:s",time()).",errno:".$e->getCode().",errmsg:".$e->getMessage();
                $msg .= "save result error\n";
                $this->writeLog("onlineclean_error",$msg);
            }

        }
        catch(Exception $e)
        {
            $msg = date("Y-m-d H:i:s",time()).",errno:".$e->getCode().",errmsg:".$e->getMessage();
            $msg .= "\n";
            $this->writeLog("onlineclean_error",$msg);
        }

        $end_time = time();

        $cost_time = $end_time-$start_time;
        $msg =  date("Y-m-d H:i:s",time()).",onlineclean cost:".$cost_time."\n";
        $this->writeLog("onlineclean_cost",$msg);

        echo json_encode(array('result'=>0,'msg'=>'','info'=>$info));
        return ;
    }
	
	public function getParams($data)
	{
		if(empty($data))
        {
            echo json_encode(array('result'=>1,'msg'=>'参数不能为空'));
            exit;
        }
        $data = json_decode($data,true);
        if(empty($data))
        {
            echo json_encode(array('result'=>1,'msg'=>'参数不能decode'));
            exit;
        }
		
		$drawUrls = array();
		foreach($data as $key=>$value)
		{
			if(empty($value['content']) && empty($value['url']) ) 
			{
				echo json_encode(array('result'=>1,'msg'=>'content和url必须有一个不能为空'));
				exit;
			}
			if(empty($value['openid']))
			{
				preg_match("/http[s]?:\/\/([^\/|^:|^?]+)/", $value['url'], $domain);
				$urlHost = $domain[1];
				$data[$key]['openid'] = $urlHost;
			}
			$value['content'] = @urldecode($value['content']);
			$data[$key]['content'] = $value['content'];
			if(empty($value['content']))
			{
				$drawUrls[$key] = $value['url'];
				$this->Multicurl_model->multiAdd($key,$value['url']);
			}
		}
		
		if($drawUrls)
		{
			$drawUrlResult = $this->Multicurl_model->multi();
			foreach($data as $key=>$value)
			{
				if($drawUrlResult[$key])
				{
					
					$data[$key]['total_time'] =  $drawUrlResult[$key]['total_time'];
					if($drawUrlResult[$key]['code'] == 200)
					{
						$data[$key]['draw_content'] =  $drawUrlResult[$key]['data'];
					}
					else
					{
						$msg = date("Y-m-d H:i:s",time()).",curl http error,errno:".$drawUrlResult[$key]['errno'].",errmsg:".$drawUrlResult[$key]['errmsg'];
						$msg .= ",code:".$drawUrlResult[$key]['code'].",content:".$drawUrlResult[$key]['data']."\n";
						$this->writeLog("onlineclean_error",$msg);
					}
				}
			}
		}
		return $data;
	}

    public static $projectKeys = array();

    public function drawContent($projectInfo,$value,&$errno)
    {
        try
        {
            $className = "ProjectHandle".$projectInfo["project_id"];

            $scriptFile = dirname(dirname(__FILE__))."/project/project_".$projectInfo["project_id"].".php";
            if(empty(self::$projectKeys[$projectInfo['project_id']]))
            {
                if(!file_exists($scriptFile) || filemtime($scriptFile)<$projectInfo["update_time"])
                {
                    $projectInfo['script'] = str_replace('ProjectHandle',$className,$projectInfo['script']);
                    $fd = fopen($scriptFile, "w+");
                    fwrite($fd, "<?php\n".stripcslashes($projectInfo['script']));
                    fclose($fd);

                }
                $projectKeys[$projectInfo['project_id']]=1;
            }
            include_once $scriptFile;

			if($value['content'])
			{
				$newcontent = '<div class="weixin_dataclean_rich_media_content" id="js_content">'.$value['content'].'</div>';
				$response = new Model_Response($newcontent);
				$instance = new $className;
				$instance->DetailPage($response->Doc('div.weixin_dataclean_rich_media_content'),array());
			}
			elseif($value['draw_content'])
			{
				$response = new Model_Response($value['draw_content']);
				$instance = new $className;
				$instance->DetailPage($response,array());
			}
			
			//详情页多页的处理
			$maxExeNum = 5;
			$iNum = 0;
			while($instance->FetchList && $iNum <= $maxExeNum)
			{
				$fetchInfo = $instance->FetchList ;
				$instance->FetchList = array();
				$iNum++;
				foreach($fetchInfo as $fetchItem)
				{
					if($fetchItem['url'] && $fetchItem['callback'])
					{
						$fetchRes = $this->dataclean_model->FetchUrl($fetchItem['url'],$fetchItem['options']);
						$instance->$fetchItem['callback']($fetchRes,$fetchItem['options']);
					}
				}
			}
			
            if($instance->IsError)
            {
                $errno = 2;
                return '';
            }

            foreach($instance->ResultList as $resultItem)
            {
                if($resultItem['content'])
                {
                    return $resultItem['content'];
                }
            }

        }
        catch(Exception $e)
        {
            $errno = 2;
            return '';
        }
        $errno = 2;
        return '';
    }

	public function writeLog($filename,$msg)
    {
        $filepath = dirname(dirname(__FILE__))."/logs/".$filename."_".date("YmdH",time()).".log";
        file_put_contents($filepath,$msg,FILE_APPEND);
    }

}




