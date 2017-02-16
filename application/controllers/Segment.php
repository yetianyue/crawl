<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Segment extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("segment_model");		
	}
	
	public function OnlineSegment()
	{
		if(!empty($_GET["url"]))
		{
			$url = trim($_GET["url"]);
		}
		$data = array();
		$data["url"] = $url;
		$this->load->view('incs/header');
		$this->load->view('incs/menu');
		$this->load->view('segment_menu');
		$this->load->view('segment_online',$data);
		$this->load->view('incs/footer');
	}
	
	public function ExtractArticalByUrl($url)
	{
		$cmsid = substr($url,24);
		$options["cmsid"] = $cmsid;
		$result = $this->segment_model->GetArticlsByCmsid($options);
		$title = $result["title"];
		$content = $result["content"];
					
		if(empty($title) || empty($content))
		{
			require_once 'Debugger.php';
			$debug = new Debugger();
			$response = $debug->FetchUrl($url);
			$titleDom = $response->Doc("p.title");
			$title = trim($titleDom[0]->plaintext);
			$contentDom = $response->Doc("p.text");
			$count = count($contentDom);
			$content = "";
			for($i=0; $i<$count; $i++)
			{
				$content .= $contentDom[$i]->plaintext;
				$content .= "\n";
			}
		}
		
		$return = array();
		$return["title"] = $title;
		$return["content"] = $content;
		return $return;
	}
	
	public function ExtractText()
	{
		$fetchurl = trim($_GET['fetchurl']);		
		$data = $this->ExtractArticalByUrl($fetchurl);
		echo json_encode($data);		
	}
	
	public function SegmentContent($title, $content,$seg=FALSE)
	{
		$options = array();
		$options["ID"] = "0";
		$options["PT"] = time();
		$options["TI"] = trim($title);
		$options["CT"] = trim($content);
		$filePath = "/tmp/segmentword.txt";
		$result = json_encode($options);
		file_put_contents($filePath, $result);
		if($seg)
		{
			$cmd = "/data/crawl.webdev.com/htdocs/script/keyword --host 10.129.137.233:15520 -s --file {$filePath}";
		}
		else
		{
			$cmd = "/data/crawl.webdev.com/htdocs/script/keyword --host 10.129.137.233:15520  --file {$filePath}";
		}
	
		return shell_exec($cmd);
	}
	
	public function SegmentWord()
	{
		$title = trim($_POST["title"]);
		$content = trim($_POST["content"]);
		$seg = FALSE;
		if(!empty($_POST["s"]) && trim($_POST["s"])==1)
		{
			$seg = TRUE;
		}		
		echo $this->SegmentContent($title, $content, $seg);
	}
	
	public function Analyse()
	{
		$this->load->view('incs/header');
		$this->load->view('incs/menu');
		$this->load->view('segment_menu');
		$this->load->view('segment_analyse');
		$this->load->view('incs/footer');
	}
	
	public function AnalyseTwo()
	{
		$fetchurl1 = trim($_GET['fetchurl1']);
		$fetchurl2 = trim($_GET['fetchurl2']);
		$returnData = array();
		$data1 = $this->ExtractArticalByUrl($fetchurl1);
		$data2 = $this->ExtractArticalByUrl($fetchurl2);
		$seg1 = $this->SegmentContent($data1["title"], $data1["content"],TRUE);
		$seg2 = $this->SegmentContent($data2["title"], $data2["content"],TRUE);
		$returnData["seg1"] = json_decode($seg1, true);
		$returnData["seg2"] = json_decode($seg2, true);
		$wordExtract1 = $this->SegmentContent($data1["titile"], $data1["content"]);
		$wordExtract2 = $this->SegmentContent($data2["titile"], $data2["content"]);
		$wordExtract1 = json_decode($wordExtract1, true);
		$wordExtract2 = json_decode($wordExtract2, true);
		$data = $this->ExtractWordMearge($wordExtract1, $wordExtract2);		
		$returnData["count"] = $data["simNum"];
		$returnData["extract"] = $data["word"];
		echo json_encode($returnData);	
	}
	
	public function ExtractWordMearge($wordExtract1, $wordExtract2)
	{
		$count = 0;
		$wordArray1 = array();
		$wordArray2 = array();
		for($i=0; $i<20; $i++)
		{
			$wordArray1[] = $wordExtract1["TN"][$i];
			$wordArray2[] = $wordExtract2["TN"][$i];
		}
		
		$count = 0;
		$wordAnalyse = array();
		foreach ($wordArray1 as $key=>$val)
		{
			$flag = 0;
			foreach ($wordArray2 as $k=>$v)
			{
				if($val["WD"]==$v["WD"])
				{
					$flag = 1;
					break;
				}
			}
			$val["set"] = 0;
			$v["set"] = 0;
			if($flag)
			{
				$val["set"] = 1;				
				$v["set"] = 1;			
				++$count;				
			}
			$wordArray1[$key] = $val;
			$wordArray2[$k] = $v;
		}
		
		for($i=0; $i<20; $i++)
		{
			$wordAnalyse[] = array("1"=>$wordArray1[$i], "2"=>$wordArray2[$i]);
		}
			
		
		$returnData = array();
		$returnData["simNum"] = $count;
		$returnData["word"] = $wordAnalyse;
		return $returnData;
	}
	
	
	public function Index()
	{	
		$this->load->view('incs/header');
		$this->load->view('incs/menu');
		$this->load->view('segment_menu');
		$this->load->view('segment_custom');
		$this->load->view('segment_stop');
		$this->load->view('segment_black');
		$this->load->view('incs/footer');
	}
	
	public function GetWord()
	{
		$options = array();
		
		$options["table"] = trim($_GET["table"]);
		
		if(!empty($_GET["page"]))
		{
			$options["page"] = trim($_GET["page"]);
		}
		
		if(!empty($_GET["word"]))
		{
			$options["word"] = trim($_GET["word"]);
		}
		
		$returnData = $this->segment_model->GetWord($options);
		echo json_encode($returnData);
	}
	
	public function DelWord()
	{
		$options["table"] = trim($_GET["table"]);
		$options["id"] = trim($_GET['id']);
		$this->segment_model->DelWord($options);
	}
	
	public function AddWord()
	{
		$options["table"] = trim($_GET["table"]);
		$options["word"] = trim($_GET["word"]);
		echo $this->segment_model->AddWord($options);
	}
	
	public function UpdateRemoteHost($localFile, $para)
	{
		$hosts = array(
				"10.129.137.233:15520",
				"10.129.137.233:15521",
				"10.129.137.233:15522",
				"10.129.137.233:15523"			
		);
		$remote = "wfhuang@10.129.137.233:/data/libTCWordSeg3.8.7.2/data/stopword.txt";
		$cmd = "/data/crawl.webdev.com/htdocs/script/scp.sh ".$localFile." ".$remote." 691ed05996ba";
		system($cmd);
		
		foreach ($hosts as $val)
		{
			$cmd = "/data/crawl.webdev.com/htdocs/script/keyword --host {$val} --update {$para}";
			system($cmd);
		}		
	}
	
	public function UpdateStopWord()
	{	
		$options["table"] = trim($_GET["table"]);
		$wordArray = $this->segment_model->UpdateWord($options);
		$filePath = "/tmp/stopword.txt";
		$fd = fopen($filePath,'w+');
		foreach ($wordArray as $key=>$val)
		{
			$val = trim($val['word']);
			fwrite($fd, "{$val}\n");
		}
		
		if(!file_exists($filePath))
		{
			echo -1;
		}

		$this->UpdateRemoteHost($filePath, "stopword");
		echo 0;				
	}
	
	public function UpdateCustomWord()
	{
		$options["table"] = trim($_GET["table"]);
		$wordArray = $this->segment_model->UpdateWord($options);
		$filePath = "/tmp/customword.txt";
		$fd = fopen($filePath,'w+');
		$str = "";
		foreach ($wordArray as $key=>$val)
		{
			
			$str .= "{$val['word']}\n";
		}
		
		$result = iconv("utf8","gbk",$str);
		fwrite($fd, $result);
			
		if(!file_exists($filePath))
		{
			echo -1;
		}
		
		$this->UpdateRemoteHost($filePath, "segment");
		echo 0;
	}
	
	
	public function UpdateBlackWord()
	{
		$options["table"] = trim($_GET["table"]);
		$wordArray = $this->segment_model->UpdateBlackWord($options);
		$filePath = "/tmp/wordpos.txt";
		$fd = fopen($filePath,'w+');		
		foreach ($wordArray as $key=>$val)
		{
			fwrite($fd, "{$val["word_id"]}     #{$val["word"]}\n");
			
		}			
		if(!file_exists($filePath))
		{
			echo -1;
		}
		
		$this->UpdateRemoteHost($filePath, "blackpos");
		echo 0;
	}
	
	public function AddBlackword()
	{
		$word_id = trim($_GET["word_id"]);					
		$options = array();
		$options["word_id"] = $word_id;
		$options["table"] = trim($_GET["table"]);		
		echo $this->segment_model->AddBlackword($options);
		
	}
	
	
	public function GetBlackword()
	{
		$options = array();
		
		$options["table"] = trim($_GET["table"]);
		
		if(!empty($_GET["page"]))
		{
			$options["page"] = trim($_GET["page"]);
		}				
		
		$returnData = $this->segment_model->GetBlackword($options);
		echo json_encode($returnData);
		
	}
	
	public function DelBlackword()
	{
		$options["table"] = trim($_GET["table"]);
		$options["word_id"] = trim($_GET['id']);
		$this->segment_model->DelBlackword($options);
	}
	
	
	public function AddBlackPos()
	{
		$this->AddBlackword();
		$this->UpdateBlackWord();
	}
	
	
	public function AddStopWord()
	{
		$this->AddWord();
		$this->UpdateStopWord();
	}
	
	
	public function Match()
	{
		if(!empty($_REQUEST["cmsid"]))
		{
			$options = array();
			$options["cmsid"] = trim($_REQUEST["cmsid"]);
			$return = $this->segment_model->SimilarMatch($options);
		}
		else 
		{
			$return = $this->segment_model->MatchList();
		}
		$this->load->view('incs/header');
		$this->load->view('incs/menu');
		$this->load->view('segment_menu');
		$this->load->view('segment_match',$return);
		$this->load->view('incs/footer');
	}
		
	
	public function ClearExpireData()
	{
		ini_set('memory_limit','1024M');
		$this->segment_model->ClearExpireData();
	}
	
	public function Monitor()
	{
		$this->load->view('incs/header');
		$this->load->view('incs/menu');
		$this->load->view('segment_menu');
		$this->load->view('segment_monitor');
		$this->load->view('incs/footer');
	}
	
	public function getSimilarArticalList()
	{
		$return = $this->segment_model->getSimilarArticalList();
		echo json_encode($return);
	}
	
	
	
	
}