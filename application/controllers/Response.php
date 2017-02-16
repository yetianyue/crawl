<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Response extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("spider_model");
        $this->load->model("stat_model");
        $this->load->model("tool_model");
    }

    public function Items()
    {
        $user = $_GET["user"];
        $mmsid = $_GET["mmsid"];
        if(!empty($user))
        {
            $state = intval($_GET["state"]);
            $data = $this->spider_model->GetResponseByUser($user, $state);
        }
        else if(!empty($mmsid))
        {
            $data = $this->spider_model->GetResponseByMmsid($mmsid);
            return;
        }
        else
        {
            $data = $this->spider_model->getResponseList();
        }
        $this->load->view('incs/header');
        $this->load->view('incs/menu');     
        $this->load->view('response_list',$data);

        $this->load->view('incs/footer');
    }

    public function ReviewItem()
    {
        $id = intval($_POST["id"]);
        $ret = $this->spider_model->UpdateResponseReviewTime($id);
        if($ret)
        {
            echo json_encode($ret);
        }
    }

    public function Detail()
    {
        $response_id = $_GET["response_id"];
        $table = "spider_response";
        $select = "content";
        $where = "id={$response_id}";
        $data = $this->tool_model->Query($table,$select,$where);        
        if(!empty($data))
        {
            $item = json_decode($data[0]["content"],true);

            require_once "Proxy.php";
            require_once dirname(dirname(__FILE__))."/third_party/simple_html_dom.php";
            $content = $item['content'];
            $Dom = new simple_html_dom();
            $Dom->load($content);
            $images = $Dom->find("img");
            foreach($images as $image)
            {   
                $url = $image->src;
                $image->src = "http://crawl.webdev.com/proxy/url?url=$url"; 
            }   
            $item['content'] = $Dom;

            foreach($item['imgs'] as $key=>$val)
            {
                $item['imgs'][$key] = "http://crawl.webdev.com/proxy/url?url=$val";
            }

        }

        $this->load->view('detail',$item);

    }

    public function Statics()
    {
        ini_set('memory_limit','512M');
        $this->stat_model->StaticsResponse();
    }

    public function DeleteExpireTimeArticle()
    {
        $timeStamp = time() - 7 * 24 * 60 * 60;
        $sql = "insert into spider_response_old(task_id,project_id,url,content,create_time,iurl,update_time,pub_time,title,source) select task_id,project_id,url,content,create_time,iurl,update_time,pub_time,title,source from spider_response where create_time<{$timeStamp}";
        $this->db->query($sql);
        $sql = "delete from spider_response where create_time<{$timeStamp}";
        $this->db->query($sql);
    }

}
