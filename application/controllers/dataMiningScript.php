<?php
class ProduceScript
{
	public static function produce($option)
	{
        $tab = "    ";
        $break = "\n";
		$script = "class ProjectHandle extends Model_Task{$break}{{$break}";
    	$script .= "{$tab}public function OnStart(){$break}{$tab}{{$break}";
		
		if(!empty($option["OnStart"]))
		{
			foreach ($option["OnStart"] as $url=>$age)
			{
				$script .= "{$tab}{$tab}\$this->Fetch('{$url}','IndexPage',array('age'=>{$age},'cookie'=>'".$option['cookie']."','priority'=>".$option['priority'].",'encoding'=>'".$option['encoding']."'));{$break}";		
			}
		}
		
		$script .= "{$tab}}{$break}{$break}";
		$script .= "{$tab}public function IndexPage(\$response, \$options){$break}{$tab}{{$break}";
        $script .= "{$tab}{$tab}\$options['layer'] = 10;{$break}";
        $script .= "{$tab}{$tab}if(empty (\$options['data']['page'])){$break}";
        $script .= "{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['page'] = 0;{$break}";
        $script .= "{$tab}{$tab}}{$break}";
		$script .= "{$tab}{$tab}++\$options['data']['page'];{$break}";
		
		$script .= "{$tab}{$tab}\$links = \$response->Doc(\"".$option['IndexPage']['doc']."\");{$break}";
		
		$script .= "{$tab}{$tab}foreach(\$links as \$link){$break}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}if(preg_match(\"".$option['IndexPage']['regex']."\", trim(\$link->href))){$break}{$tab}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}\$url = trim(\$link->href);{$break}";
		
		$script .= "{$tab}{$tab}{$tab}{$tab}if(substr(\$url, 0, 7) != \"http://\"){$break}{$tab}{$tab}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}if(substr(\$url, 0, 1) == \"/\"){$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}{$tab}\$options[\"data\"]['url'] = \$this->CurrentDomain.substr(\$url, 1);{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}else{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}{$tab}\$options[\"data\"]['url'] = \$this->CurrentDomain.\$url;{$break}{$tab}{$tab}{$tab}{$tab}}{$break}";

		$script .= "{$tab}{$tab}{$tab}{$tab}else{$break}{$tab}{$tab}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}\$options[\"data\"]['url'] = \$url;{$break}{$tab}{$tab}{$tab}{$tab}}{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}\$this->Fetch(\$link->href, \"DetailPage\", \$options);{$break}{$tab}{$tab}{$tab}}{$break}{$tab}{$tab}}{$break}";

		$script .= "{$tab}{$tab}if(\$options['data']['page']>1000){$break}{$tab}{$tab}{{$break}{$tab}{$tab}{$tab}return;{$break}{$tab}{$tab}}{$break}";
		$script .= "{$tab}{$tab}\$pages  = \$response->Doc(\"a\");{$break}";
		$script .= "{$tab}{$tab}foreach(\$pages  as \$next){$break}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}if((\$next->plaintext =='后一页' || \$next->plaintext =='下页' || \$next->plaintext =='下一页'||\$next->plaintext =='下一頁') && \$next->href){$break}{$tab}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}\$this->Fetch(\$next->href, \"IndexPage\",\$options);{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}break;{$break}{$tab}{$tab}{$tab}}{$break}{$tab}{$tab}}{$break}{$tab}}{$break}{$break}";
        
        //DetailPage
		$script .= "{$tab}public function DetailPage(\$response, \$options){$break}{$tab}{{$break}";
        if("" != $option["DetailPage"]["title"]){
            $script .= "{$tab}{$tab}if(empty(\$options['data']['title'])){$break}{$tab}{$tab}{{$break}";
		    $script .= "{$tab}{$tab}{$tab}\$title = \$response->Doc(\"".$option["DetailPage"]["title"]."\");{$break}";
		    $script .= "{$tab}{$tab}{$tab}\$options['data']['title'] =  trim(\$title[0]->plaintext);{$break}{$tab}{$tab}}{$break}{$break}";
        }
        if("" != $option["DetailPage"]["content"]){
		    $script .= "{$tab}{$tab}\$content = \$response->Doc(\"".$option["DetailPage"]["content"]."\");{$break}";
            $script .= "{$tab}{$tab}if(empty (\$options['data']['content'])){$break}";
            $script .= "{$tab}{$tab}{{$break}";
            $script .= "{$tab}{$tab}{$tab}\$options['data']['content'] = '';{$break}";
            $script .= "{$tab}{$tab}}{$break}";
            $script .= "{$tab}{$tab}\$start = ".$option["DetailPage"]["contentStart"].";{$break}";
            $script .= "{$tab}{$tab}\$end = ".$option["DetailPage"]["contentEnd"].";{$break}";
            $script .= "{$tab}{$tab}\$contentChildren = \$content[0]->children();{$break}";
            $script .= "{$tab}{$tab}\$contentCount = count(\$contentChildren);{$break}";
            $script .= "{$tab}{$tab}for(\$i = \$start; \$i <= (\$contentCount + \$end); ++\$i){$break}";
            $script .= "{$tab}{$tab}{{$break}";
            $script .= "{$tab}{$tab}{$tab}\$options['data']['content'] .= trim(\$contentChildren[\$i]->outertext);{$break}";
            $script .= "{$tab}{$tab}}{$break}";
		    //$script .= "{$tab}{$tab}\$options['data']['content'] .= trim(\$content[0]->plaintext);{$break}{$break}";//加上上一页的信息 
        }
        if("" != $option["DetailPage"]["pubtime"]){
		    $script .= "{$tab}{$tab}\$pubtime = \$response->Doc(\"".$option["DetailPage"]["pubtime"]."\");{$break}";
		    $script .= "{$tab}{$tab}\$options['data']['pubtime'] =  trim(\$pubtime[0]->plaintext);{$break}{$break}";
        }
        if("" != $option["DetailPage"]["images"]){
		    $script .= "{$tab}{$tab}\$images = \$response->Doc(\"".$option["DetailPage"]["images"]."\");{$break}";
            $script .= "{$tab}{$tab}if(empty (\$options['data']['count'])){$break}";
            $script .= "{$tab}{$tab}{{$break}";
            $script .= "{$tab}{$tab}{$tab}\$options['data']['count'] = 0;{$break}";
            $script .= "{$tab}{$tab}}{$break}";
		    $script .= "{$tab}{$tab}\$options['data']['count'] +=  count(\$images);{$break}{$break}";
        }
        if("" != $option["DetailPage"]["author"]){
		    $script .= "{$tab}{$tab}\$author= \$response->Doc(\"".$option["DetailPage"]["author"]."\");{$break}";
		    $script .= "{$tab}{$tab}\$options['data']['author'] =  trim(\$author[0]->plaintext);{$break}{$break}";
        }
        if("" != $option["DetailPage"]["source"]){
		    $script .= "{$tab}{$tab}\$source= \$response->Doc(\"".$option["DetailPage"]["source"]."\");{$break}";
		    $script .= "{$tab}{$tab}\$options['data']['source'] =  trim(\$source[0]->plaintext);{$break}{$break}";
        }
        if("" != $option["DetailPage"]["config"]){
            $configArr = json_decode($option["DetailPage"]["config"]);
            foreach ($configArr as $key=>$value)
            {
		        $script .= "{$tab}{$tab}\${$key}= \$response->Doc(\"{$value}\");{$break}";
		        $script .= "{$tab}{$tab}\$options['data']['{$value}'] =  trim(\${$key}[0]->plaintext);{$break}{$break}";
            }
        }
		
		$script .= "{$tab}{$tab}for(\$i = 0; \$i < count(\$images); ++\$i){$break}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}\$url = trim(\$images[\$i]->src);{$break}";
		
		$script .= "{$tab}{$tab}{$tab}if(substr(\$url, 0, 7) != \"http://\"){$break}{$tab}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}if(substr(\$url, 0, 1) == \"/\"){$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}\$options['data']['imgs'][] = \$this->CurrentDomain.substr(\$url, 1);{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}else{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}\$options['data']['imgs'][] = \$this->CurrentDomain.\$url;{$break}{$tab}{$tab}{$tab}}{$break}";
		$script .= "{$tab}{$tab}{$tab}else{$break}{$tab}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}\$options['data']['imgs'][] = \$url;{$break}{$tab}{$tab}{$tab}}{$break}{$tab}{$tab}}{$break}";
		
		//视频地址处理 start
		if("" != $option["DetailPage"]["video_find"]){
		    $script .= "{$tab}{$tab}\$videos = \$response->Doc(\"".$option["DetailPage"]["video_find"]."\");{$break}";
            $script .= "{$tab}{$tab}if(empty (\$options['data']['video_count'])){$break}";
            $script .= "{$tab}{$tab}{{$break}";
            $script .= "{$tab}{$tab}{$tab}\$options['data']['video_count'] = 0;{$break}";
            $script .= "{$tab}{$tab}}{$break}";
		    $script .= "{$tab}{$tab}\$options['data']['video_count'] += count(\$videos);{$break}{$break}";
        }
		$script .= "{$tab}{$tab}if(\$videos){$break}{$tab}{$tab}{{$break}";
		
		$script .= "{$tab}{$tab}{$tab}for(\$i = 0; \$i < count(\$videos); ++\$i){$break}{$tab}{$tab}{$tab}{{$break}";
		if($option["DetailPage"]["video_src"])
		{
			$script .= "{$tab}{$tab}{$tab}{$tab}\$url = trim(\$videos[\$i]->".$option["DetailPage"]["video_src"].");{$break}";	
		}
		else
		{
			$script .= "{$tab}{$tab}{$tab}{$tab}\$url = trim(\$videos[\$i]->value);{$break}";
		}
		
		$script .= "{$tab}{$tab}{$tab}{$tab}if(substr(\$url, 0, 7) != \"http://\"){$break}{$tab}{$tab}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}if(substr(\$url, 0, 1) == \"/\"){$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}{$tab}\$options['data']['videos'][] = \$this->CurrentDomain.substr(\$url, 1);{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}else{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}{$tab}\$options['data']['videos'][] = \$this->CurrentDomain.\$url;{$break}{$tab}{$tab}{$tab}{$tab}}{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}else{$break}{$tab}{$tab}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}{$tab}\$options['data']['videos'][] = \$url;{$break}{$tab}{$tab}{$tab}{$tab}}{$break}{$tab}{$tab}{$tab}}{$break}";
		$script .= "{$tab}{$tab}}{$break}";
		if($option["DetailPage"]["video_find"])
		{
			$script .= "{$tab}{$tab}\$options['data']['is_video'] = 1;{$break}";
		}
		//视频地址处理 end
		
		$script .= "{$tab}{$tab}\$nextPageFlag = 0;{$break}";
		$script .= "{$tab}{$tab}\$pages  = \$response->Doc(\"a\");{$break}";
		$script .= "{$tab}{$tab}foreach(\$pages  as \$next){$break}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}if((\$next->plaintext =='后一页' || \$next->plaintext =='下页' || \$next->plaintext =='下一页'||\$next->plaintext =='下一頁') && \$next->href){$break}{$tab}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}\$nextPageFlag = 1;{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}\$options['layer'] = \$this->Layer;{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}\$this->Fetch(\$next->href, \"DetailPage\",\$options);{$break}";
		$script .= "{$tab}{$tab}{$tab}{$tab}break;{$break}{$tab}{$tab}{$tab}}{$break}{$tab}{$tab}}{$break}";
		$script .= "{$tab}{$tab}if( \$nextPageFlag == 0){$break}{$tab}{$tab}{{$break}";
		$script .= "{$tab}{$tab}{$tab}if(empty(\$options['data']['title']) || empty(\$options['data']['content']) || empty(\$options['data']['url'])){$break}";
        $script .= "{$tab}{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}\$this->SetError();{$break}";
        $script .= "{$tab}{$tab}{$tab}}{$break}";
		$script .= "{$tab}{$tab}{$tab}\$this->AddResult(\$options['data']);{$break}{$tab}{$tab}}{$break}{$tab}}{$break}}{$break}{$break}";
			
		return $script;
	}
	
	
}


