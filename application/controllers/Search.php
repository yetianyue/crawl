<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once dirname(dirname(__FILE__))."/third_party/sphinxapi.php";

class Search extends CI_Controller
{
    

    public function Index()
    {
        $this->load->view('incs/header');
        $this->load->view('incs/menu');

        $this->load->view('search_index');

        $this->load->view('incs/footer');
    }

    public function OnSearch()
    {
        $pageSize = 15; 
        $query = $this->input->get('query');
        $page = intval($this->input->get('page'));
        if(trim($query) == '' || $page<=0)
        {
            $this->Index();
            return;
        }
        $SphinxClient = new SphinxClient();
        $SphinxClient->setServer('10.185.10.17', 9312);
        $SphinxClient->setSortMode(SPH_SORT_EXTENDED , 'pubtime DESC');
        $SphinxClient->setLimits(($page-1)*$pageSize,$pageSize);
        $index = "main";
        $res = $SphinxClient->query($query, $index);
        $totalPage = intval($res['total'] / $pageSize);
        $data = array();
        if(!empty($res) && count($res['matches'])!=0)
        {
            foreach($res['matches'] as $key=>$value)
            {
                $value['attrs']['url'] = "http://kuaibao.qq.com/s/".$value['attrs']['cmsid'];
                $value['attrs']['sim_url'] = "http://crawl.webdev.com/segment/match?cmsid=".$value['attrs']['cmsid'];
                $value['attrs']['pubtime'] = date('Y-m-d H:i:s',$value['attrs']['pubtime']); 
                $data[] = $value['attrs'];
            }
        }
        $returnData['total'] = $res['total'];
        $returnData['time'] = $res['time'];
        $returnData['resultList'] = $data;
        $returnData['query'] = $query;
        $returnData['pageInfo'] = $this->getPageInfo($page, $totalPage, $query);
        $this->load->view('incs/header');
        $this->load->view('incs/menu');

        $this->load->view('search_main',$returnData);

        $this->load->view('incs/footer');
        
    }

    public function SearchApi()
    {
        $title = $this->input->get('query');
        $source = trim($this->input->get('source'));
        $order = trim($this->input->get('order'));
        $category = intval($this->input->get('category'));
        $page = intval($this->input->get('page'));
        $pageSize = intval($this->input->get('pageSize'));
        if(empty($page))
        {
            $page = 1;
        }
        if(empty($pageSize))
        {
            $pageSize = 10;
        }
        if($page<-1 || $page == 0  || $pageSize<=0 || $pageSize>100)
        {
            echo json_encode(array('code'=>-1,'msg'=>'Parameter Error!','data'=>''));
            return;
        }

        //全量数据
        if($page == -1)
        {
            $page = 1;
            $pageSize = 1000;
        }
        $SphinxClient = new SphinxClient();
        $SphinxClient->setServer('10.185.10.17', 9312);
        if(empty($order) || $order == "pubtime")
        {
            $SphinxClient->setSortMode(SPH_SORT_EXTENDED , 'pubtime DESC');
        }
        else if($order == "c_time") 
        {
            $SphinxClient->setSortMode(SPH_SORT_EXTENDED , 'c_timestamp DESC');
        }
        else
        {
            echo json_encode(array('code'=>-1,'msg'=>'Parameter Error!','data'=>''));
            return;
        }
        $SphinxClient->setLimits(($page-1)*$pageSize,$pageSize);
        $SphinxClient->setMatchMode(SPH_MATCH_EXTENDED2);
        //$SphinxClient->setMatchMode(SPH_MATCH_ANY);
        $index = "main";
        $query = "";
        if(!empty($title))
        {
            $query .= " @title ".$title;
        }
        if(!empty($source))
        {
            $query .= " @src ".$source;
        }
        if(!empty($category))
        {
            $SphinxClient->setFilter("category",array($category));
        }
        $res = $SphinxClient->query($query, $index);
 
        $totalPage = intval($res['total'] / $pageSize);
        $data = array();
        if(!empty($res) && count($res['matches'])!=0)
        {
            foreach($res['matches'] as $key=>$value)
            {
                $value['attrs']['c_time'] = date('Y-m-d H:i:s',$value['attrs']['c_timestamp']); 
                $data[] = $value['attrs'];
            }
        }
       
        $returnData['query'] = $query;
        $returnData['total'] = $res['total'];
        $returnData['page'] = $page;
        $returnData['pageSize'] = $pageSize;
        $returnData['resultList'] = $data;
        echo json_encode(array('code'=>0, 'msg'=>'Success', 'data'=>$returnData));

    }

    function getPageInfo($curPage,$totalPage,$query)
    {
        $pageInfo = array();
        $pageInfo['url'] = "http://crawl.webdev.com/search/OnSearch?query=".$query."&page=";
        $pageInfo['curPage'] = $curPage;
        $pageInfo['totalPage'] = $totalPage;
        if($curPage >= 5)
        {
            $pageInfo['start'] = $curPage-4;
        }
        else
        {
            $pageInfo['start'] = 1;
        }
        $pageInfo['end'] = min($pageInfo['start']+9, $totalPage);
        return $pageInfo;
    }

    public function SearchSync()
    {
        $this->load->model("searchsync_model");
        $this->searchsync_model->SearchSync();
    }
    
}


