<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dataclean extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("dataclean_model");
        $this->load->model("redis_model");
    }
	
	public function drawContent()
    {
		require_once dirname(dirname(__FILE__))."/libraries/FetchUtil.php";
		$url = $this->input->get('url');
        $result = array();
        $result['url'] = $url;
        $options["type"]='html';
		$html = $this->dataclean_model->FetchUrl($url,$options);
		$content = $this->dataclean_model->commonDrawContent(array('draw_content'=>$html,'url'=>$url),$errno);
		$content = json_decode($content,true);
		$result['title'] = $content['title'];
		$result['content'] = $content['content'];
		
        $this->load->view('dataclean_debug',$result);
    }


    public function detail()
    {
        $response_id = $this->input->get('response_id');
        $type = $this->input->get('type');
        if(empty($type) || empty($response_id))
        {
            echo json_encode(array('error'=>'参数有误'));
            return;
        }

		$data = $this->dataclean_model->getResponseListById($response_id);
        $result = array();
        $result['url'] = $data['url'];
        if($type=='before')
        {
            $result['content']['content'] = $data['before_content'];

        }
        elseif($type='after')
        {

            $result['content'] = json_decode($data['after_content'],true);

        }
		if($result['videos'])
		{
			$result['videos'] = implode(',',$result['videos']);
		}
        $this->load->view('dataclean_detail',$result);

    }

	public function result()
	{
		$data = $this->dataclean_model->getResponseList();

		$this->load->view('incs/header');
		$this->load->view('incs/menu');		
		$this->load->view('dataclean_result',$data);
		
		$this->load->view('incs/footer');
	}
    
    public function debugres()
    {
        $user = $_COOKIE['PAS_COOKIE_USER'];
        $redisKey = "dataclean_debuger_".$user;
        $res = $this->redis_model->get($redisKey);
        $res = json_decode($res,true);
        $content = json_decode($res['content'],true);
        $content['url'] = $res['url'];
		if($content['videos'])
		{
			$content['videos'] = implode(',',$content['videos']);
		}
        $this->load->view('dataclean_debug',$content);
    }


    public function Items()
    {
        $data = $this->dataclean_model->getProjectList();       

        $this->load->view('incs/header');
        $this->load->view('incs/menu');
        $this->load->view('dataclean_list',$data);
        $this->load->view('incs/footer');
    }

    public function Add()
    {
        $data = array(
             'user' => $_COOKIE['PAS_COOKIE_USER'],
        );

        $this->load->view('incs/header');
        $this->load->view('incs/menu');
        $this->load->view('dataclean_add', $data);
        $this->load->view('incs/footer');
    }
    
    public function Alter()
    {
        $project_id = $this->input->get('id');
        $data = $this->dataclean_model->GetProjectDetailInfo($project_id);
        $page_info =  json_decode($data['page_info'],true);
        unset($data['page_info']);
		if($page_info)
		{
			$data = array_merge($data,$page_info);
		}

        if($data['url'] == NULL)
        {
            $data['url'] = -1;
        }

        $data['history'] = $this->dataclean_model->GetScriptHistory($project_id);

        $this->load->view('incs/header');
        $this->load->view('incs/menu');
        $this->load->view('dataclean_alter', $data);
        $this->load->view('incs/footer');
    }
    
    public function SaveProject()
    {
		$project_template = trim($this->input->post("project_template"));
        $name = trim($this->input->post("name"));
        $openid = trim($this->input->post("project_openid"));
        $user = trim($this->input->post("user"));
        $tag = trim($this->input->post("tag"));
        $script = trim($this->input->post("script"));
    
		$video_find = trim($this->input->post("video_find"));
		$video_src = trim($this->input->post("video_src"));
        $list_url = trim($this->input->post("list_url"));
        $list_dom = trim($this->input->post("list_dom"));
        $detail_contentStart = trim($this->input->post("detail_contentStart"));
        $detail_contentEnd = trim($this->input->post("detail_contentEnd"));
        $detail_config = trim($this->input->post("detail_config"));
		
		$detail_title = trim($this->input->post("detail_title"));
        $detail_content = trim($this->input->post("detail_content"));
		$detail_pubtime = trim($this->input->post("detail_pubtime"));
        $detail_src = trim($this->input->post("detail_src"));
        if(true == $this->IsProjectNameExisted($name))
        {
            echo json_encode(array('error'=>'项目名称已经存在'));
            return;
        }
        if(empty($openid))
        {
            echo json_encode(array('error'=>'openid 不能为空'));
            return;
        }
        if(true == $this->IsOpenidExisted($openid))
        {
            echo json_encode(array('error'=>'openid 已经存在'));
            return;
        }
        if($detail_config)
        {
            $detail_config = json_decode($detail_config,true);
            $new_detail_config = array();
            foreach($detail_config as $key=>$value)
            {
                $new_detail_config[] = $value;
            }
            $detail_config = json_encode($new_detail_config);
        }

        $script_info = array(
                "url"=>$list_url,
                "list_dom"=>$list_dom,
                "detail_contentStart"=>$detail_contentStart,
                "detail_contentEnd"=>$detail_contentEnd,
                "detail_config"=>$detail_config,
				"detail_title"=>$detail_title,
				"detail_content"=>$detail_content,
				"detail_pubtime"=>$detail_pubtime,
				"detail_src"=>$detail_src,
				"video_find"=>$video_find,
				"video_src"=>$video_src,
        );
        $script_info = json_encode($script_info);
        
        $create_time = time();
        $modify_time = $create_time;
    
        $project_info = array("name"=>$name,
                "openid"=>$openid,
                "user"=>$user,
                "status"=>0,
                "tag"=>$tag,
                "type"=>0,
                "script"=>$script,
                "create_time"=>$create_time,
                "update_time"=>$create_time,
                "page_info"=>$script_info,
				'template'=>$project_template,
        );
        $project_id = $this->dataclean_model->AddProject($project_info);
        if(empty($project_id))
        {
            echo json_encode(array('error'=>'脚本信息插入数据库失败'));
            return;
        }
        //保存到redis中 供线上调用 

        $redisKey = "dataclean_openid_".$openid;

        $project_info['project_id'] = $project_id;
        $redisValue = json_encode($project_info);
        $res = $this->redis_model->set($redisKey,$redisValue);
        if($res === false)
        {
            echo json_encode(array('error'=>'脚本信息插入redis失败'));
            return;
        }

        echo json_encode(array('error'=>''));
    }


    public function UpdateProject()
    {
		$project_template = $this->input->post("project_template");
        $project_id = $this->input->post("project_id");
        $name = trim($this->input->post("name"));
        $tag = trim($this->input->post("tag"));
        $script = trim($this->input->post("script"));
        $user = trim($this->input->post("user"));
        $openid = trim($this->input->post("project_openid"));
        $modify_time = time();
        $status = 0;
    
		$video_find = trim($this->input->post("video_find"));
		$video_src = trim($this->input->post("video_src"));
		
        $list_url = trim($this->input->post("list_url"));
        $list_dom = trim($this->input->post("list_dom"));
        $detail_contentStart = trim($this->input->post("detail_contentStart"));
        $detail_contentEnd = trim($this->input->post("detail_contentEnd"));
        $detail_config = trim($this->input->post("detail_config"));
		
		$detail_title = trim($this->input->post("detail_title"));
        $detail_content = trim($this->input->post("detail_content"));
		$detail_pubtime = trim($this->input->post("detail_pubtime"));
        $detail_src = trim($this->input->post("detail_src"));
        if(true == $this->IsProjectNameExisted($name,$project_id))
        {
            echo json_encode(array('error'=>'项目名称已经存在'));
            return;
        }
        if(empty($openid))
        {
            echo json_encode(array('error'=>'openid 不能为空'));
            return;
        }
        if(true == $this->IsOpenidExisted($openid,$project_id))
        {
            echo json_encode(array('error'=>'openid 已经存在'));
            return;
        }
        if($detail_config)
        {
            $detail_config = json_decode($detail_config,true);
            $new_detail_config = array();
            foreach($detail_config as $key=>$value)
            {
                $new_detail_config[] = $value;
            }
            $detail_config = json_encode($new_detail_config);
        }
    
        $script_info = array(
                "url"=>$list_url,
                "list_dom"=>$list_dom,
                "detail_contentStart"=>$detail_contentStart,
                "detail_contentEnd"=>$detail_contentEnd,
                "detail_config"=>$detail_config,
				"detail_title"=>$detail_title,
				"detail_content"=>$detail_content,
				"detail_pubtime"=>$detail_pubtime,
				"detail_src"=>$detail_src,
				"video_find"=>$video_find,
				"video_src"=>$video_src,
        );
        $script_info = json_encode($script_info);

        $project_info = array("name"=>$name,
                "openid"=>$openid,
                "user"=>$user,
                "status"=>0,
                "tag"=>$tag,
                "type"=>0,
                "script"=>$script,
                "update_time"=>$modify_time,
                "page_info"=>$script_info,
				'template'=>$project_template,
        );
        $this->dataclean_model->UpdateProject($project_id,$project_info);

        //保存到redis中 供线上调用 

        $redisKey = "dataclean_openid_".$openid;

        $project_info['project_id'] = $project_id;
        $redisValue = json_encode($project_info);
        $res = $this->redis_model->set($redisKey,$redisValue);
        if($res === false)
        {
            echo json_encode(array('error'=>'脚本信息插入redis失败'));
            return;
        }
        

        $script_history = array("project_id"=>$project_id,
            "modify_time"=>$modify_time,
            "user"=>$user,
            "script"=>$script
        );
        $this->dataclean_model->AddScriptHistory($script_history);

        echo json_encode(array('error'=>''));
    }
    
    public function IsProjectNameExisted($project_name,$project_id=0)
    {
        $data = $this->dataclean_model->GetProjectByName($project_name);
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

    public function IsOpenidExisted($openid,$project_id='0')
    {
        $data = $this->dataclean_model->GetProjectByOpenid($openid);

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
    
    public function del()
    {
        $project_id = trim($_GET["project_id"]);
        
		$select = "project_id,openid";
    	$table = "dataclean_project";
    	$where = "project_id={$project_id}";
    	$projectInfo = $this->dataclean_model->Query($table, $select, $where);
		if(empty($projectInfo))
        {
            echo -1;
            return ;
        }
		
        $table = "dataclean_project";
        $where = "project_id = {$project_id}";
        $result = $this->dataclean_model->Delete($table,$where);
        if(!$result)
        {
            echo -1;
            return ;
        }
		
		$redisKey = "dataclean_openid_".$projectInfo[0]['openid'];
        $res = $this->redis_model->del($redisKey);
		if(!$res)
        {
            echo -1;
            return ;
        }
        echo 0;
    }

    public function GenerateScript()
    {
        $project_template = $this->input->post("project_template");
        $list_url = $this->input->post("list_url");
        $list_dom = $this->input->post("list_dom");
        $detail_contentStart = $this->input->post("detail_contentStart");
        $detail_contentEnd = $this->input->post("detail_contentEnd");
        $detail_config = $this->input->post("detail_config");
		
		$video_find = trim($this->input->post("video_find"));
		$video_src = trim($this->input->post("video_src"));
		
		$detail_title = $this->input->post("detail_title");
		$detail_content = $this->input->post("detail_content");
		$detail_pubtime = $this->input->post("detail_pubtime");
		$detail_src = $this->input->post("detail_src");

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
        
        if($project_template==0 && empty($list_dom))
        {
            echo json_encode(array('error'=>'文章正文筛选器不能为空！'));
            exit;
        } 
		if($project_template==1 && (empty($detail_title) || empty($detail_content) || empty($detail_pubtime) || empty($detail_src)))
        {
            echo json_encode(array('error'=>'文章正文筛选器不能为空！'));
            exit;
        } 
        if($detail_config)
        {
            $detail_config = json_decode($detail_config,true);
        }


        $option["DrawContent"]["doc"] = addslashes(trim($list_dom));
        
        $option["DetailPage"]["contentStart"] = trim($detail_contentStart);
        $option["DetailPage"]["contentEnd"] = trim($detail_contentEnd);
        $option["DetailPage"]["config"] = $detail_config;
		
		$option["DetailPage"]["title"] = addslashes(trim($detail_title));
		$option["DetailPage"]["content"] = addslashes(trim($detail_content));
		$option["DetailPage"]["pubtime"] = addslashes(trim($detail_pubtime));
		$option["DetailPage"]["source"] = addslashes(trim($detail_src));
		
		$option["DetailPage"]["video_find"] = addslashes(trim($video_find));
		$option["DetailPage"]["video_src"] = addslashes(trim($video_src));
		
		if($project_template == 0)
		{
			require_once "script/datacleanScript.php";
			$script = DatacleanScript::produce($option);
		}
		elseif($project_template == 1)
		{
			require_once "script/datacleanCmsScript.php";
			$script = DatacleanCmsScript::produce($option);
		}
        
        echo json_encode(array('error'=>'', 'script'=>$script));
    }


    //清除两星期前的数据
    public function delresult()
    {
        $res = array();
        $del_time = date("Y-m-d",time()-14*3600*24);
        $del_time = strtotime($del_time);
        if(empty($del_time))
        {
            $res['errno'] =-1;
			$res['msg'] = 'del_time error';
			echo json_encode($res);
			return ;
        }
    	$table = "dataclean_result";
    	$where = "create_time <= {$del_time}";
    	$result = $this->dataclean_model->Delete($table,$where);
    	if(!$result)
    	{
            $res['errno'] = -2;
            $res['msg'] = 'del dataclean_result error,del_time:'.$del_time;
            echo json_encode($res);
            return ;
    	}
        $res['errno'] =0;
        $res['msg'] = '成功';
        echo json_encode($res);
        return ;
    }

}




