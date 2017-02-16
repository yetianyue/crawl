<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("stat_model");
	}
	
    public function Index()
    {
              
        $this->load->view('incs/header');
        $this->load->view('incs/menu');
        $this->load->view('monitor');
        $this->load->view('incs/footer');
    }
    
    public function getSystemInfo()
    {
    	$systemInfo = $this->stat_model->getSystemInfo();
    	$systemInfo["Project"]["Total"] = number_format($systemInfo["Project"]["Total"]);
    	$systemInfo["Project"]["Today"] = number_format($systemInfo["Project"]["Today"]);
    	$systemInfo["Task"]["Total"] = number_format($systemInfo["Task"]["Total"]);
    	$systemInfo["Task"]["Complete"] = number_format($systemInfo["Task"]["Complete"]);
    	$systemInfo["Response"]["Today"] = number_format($systemInfo["Response"]["Today"]);    	
    	$data = json_encode($systemInfo);
    	echo $data;
    }
    
    public function getSchedulerInfo()
    {
    	$schedulerInfo = $this->stat_model->getSchedulerInfo();
    	$schedulerInfo["Wait"] = number_format($schedulerInfo["Wait"]);
    	$schedulerInfo["Queue"] = number_format($schedulerInfo["Queue"]);
    	$schedulerInfo["Process"] = number_format($schedulerInfo["Process"]);
    	$schedulerInfo["Today"] = number_format($schedulerInfo["Today"]);
    	$schedulerInfo["Fail"] = number_format($schedulerInfo["Fail"]);
    	$data = json_encode($schedulerInfo );
    	echo $data;
    }
    
    public function getWaitProjectList()
    {
    	$data = $this->stat_model->getWaitProjectList();
    	$data = json_encode($data);
    	echo $data;
    }
    
    public function getFailProjectList()
    {
    	$data = $this->stat_model->getFailProjectList();
    	$data = json_encode($data);
    	echo $data;
    }
    
    public function getSuccessProjectList()
    {
    	$data = $this->stat_model->getSuccessProjectList();
    	$data = json_encode($data);
    	echo $data;
    }
    
    
    public function getUserInfo()
    {
    	$data = $this->stat_model->getUserInfo();
    	$data = json_encode($data);
    	echo $data;
    }
    
    public function getResponseStatistics()
    {
    	$data = $this->stat_model->getResponseStatistics();
    	$data = json_encode($data);
    	echo $data;
    }
    
    public function getCrawlInfo()
    {
    	$data = $this->stat_model->getCrawlInfo();
        $rate = floatval(($data["news_yestoday_pub"] + $data["news_today_pub"]))*100 / ($data["news_yestoday"] + $data["news_today"]);
        $rate = round($rate * 100) / 100;
    	$data["rate"] = $rate." %";
    	$data["news_yestoday"] = number_format($data["news_yestoday"]);
    	$data["news_yestoday_pub"] = number_format($data["news_yestoday_pub"]);
    	$data["news_today"] = number_format($data["news_today"]);
    	$data["news_today_pub"] = number_format($data["news_today_pub"]);    	
    	$data = json_encode($data);
    	echo $data;
    }
    
    public function getCrawlArticalInfo()
    {
    	 $str = "新闻后台";
    	 $data = $this->stat_model->GetCrawlArticalInfo($str);
    	 $data = json_encode($data);
    	 echo $data;
    }
    
    public function getNetFlowStatics()
    {
    	$interval = $_GET["interval"];
    	$time = $_GET["time"];
    	$pointNum = $_GET["point"];
    	    	    	
    	if(empty($interval))
    	{
    		$interval = 3600;
    	}
    	
    	if(empty($time))
    	{
    		$time = time();
    	}
    	
    	if(empty($pointNum))
    	{
    		$pointNum = 15;
    	}
    	
    	$totalInterval = $interval * $pointNum;
    	$startTime = $time - $interval * $pointNum;
    	
    	$netflowArray = array();
    	$date = array();
    	$dayInterval = 24*60*60;    	
    	$size = 1024*1024*$interval/8;
    	for($i=0; $i<$pointNum; $i++)
    	{
    		$endTime = $startTime + $interval;
    		$netFlow = $this->stat_model->getNetFlow($startTime,$endTime);
    		$netFlow = $netFlow/$size;
    		$netflowArray[] = $netFlow;
    		if($totalInterval<$dayInterval)
    		{
    			$date[] = date("H:i",$endTime);
    		}
    		else 
    		{
    			$date[] = date("m-d H",$endTime);
    		}
    		$startTime = $endTime;
    	}
    	
    	$returnData["time"] = $date;
    	$returnData["netFlow"] = $netflowArray;
    	$data = json_encode($returnData);
    	echo $data;
    }
    
   
    
    
}


