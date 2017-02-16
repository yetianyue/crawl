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
				$script .= "{$tab}{$tab}\$this->Fetch('{$url}','IndexPage',array('type'=>'json','age'=>{$age},'cookie'=>'".$option['cookie']."','priority'=>".$option['priority'].",'encoding'=>'".$option['encoding']."'));{$break}";		
			}
		}
		
		$script .= "{$tab}}{$break}{$break}";
		$script .= "{$tab}public function IndexPage(\$response, \$options){$break}{$tab}{{$break}";
        $script .= "{$tab}{$tab}if(\$response['response']['code'] != 0){$break}";
        $script .= "{$tab}{$tab}{$tab}return;{$break}";
        $script .= "{$tab}{$tab}if(empty(\$response['data']['news'] )){$break}";
        $script .= "{$tab}{$tab}{$tab}return;{$break}";
        $script .= "{$tab}{$tab}{$break}";
        $script .= "{$tab}{$tab}foreach(\$response['data']['news'] as \$item){$break}";
        $script .= "{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['title'] = \$item['Fabstract'];{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['content'] = \$item['Fabstract'];{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['pubtime'] = date('Y-m-d H:i:s',\$item['Fpub_time']/1000);{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['url'] = \$item['Furl'];{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['imgs'] = array();{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['count'] = 0;{$break}";
        $script .= "{$tab}{$tab}{$tab}if(\$item['Fpic_urls']){$break}";
        $script .= "{$tab}{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}\$options['data']['imgs'] = \$item['Fpic_urls'];{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}\$options['data']['count'] = count(\$item['Fpic_urls']);{$break}";
        $script .= "{$tab}{$tab}{$tab}}{$break}";
        $script .= "{$tab}{$tab}{$tab}elseif(\$item['Foriginal_pic']){$break}";
        $script .= "{$tab}{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}\$options['data']['imgs'][] = \$item['Foriginal_pic'];{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}\$options['data']['count'] = 1;{$break}";
        $script .= "{$tab}{$tab}{$tab}}{$break}";
        $script .= "{$tab}{$tab}{$tab}if(empty(\$item['Fweibo_id'])){$break}";
        $script .= "{$tab}{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}continue;{$break}";
        $script .= "{$tab}{$tab}{$tab}}{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['Fweibo_id'] = \$item['Fweibo_id'];{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['Furl'] = \$item['Furl'];{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['Fsection_id'] = \$item['Fsection_id'];{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['Fscreen_name'] = \$item['Fscreen_name'];{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['source'] = \$item['Fscreen_name'];{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['data']['Is_long_wb'] = 0;{$break}";
        $script .= "{$tab}{$tab}{$tab}\$options['cookie'] = 'SINAGLOBAL=761691641528.1594.1429265886623; TC-Ugrow-G0=2e646e41b2047a74add408e9b737ad7d; TC-V5-G0=8518b479055542524f4cf5907e498469; _s_tentry=login.sina.com.cn; Apache=7634572365786.88.1437021193664; ULV=1437021193673:9:3:2:7634572365786.88.1437021193664:1436850344884; TC-Page-G0=a1e213552523eaff2a80326cc1068982; WBStore=4e40f953589b7b00|undefined; SUHB=05irUPhPg1760V; myuid=1824040613; login_sid_t=c1d80180f8d2cc4c5eebe71d1784516a;UOR=tech.ifeng.com,widget.weibo.com,login.sina.com.cn; SUB=_2AkMi68aDf8NhqwJRmPoVy2rkaopxygHEiebDAHzsJxIyHk4y7BU-B__Iiw1Z9ZCICmDCC1lvKrCb; SUBP=0033WrSXqPxfM72-Ws9jqgMF55529P9D9WWbEOcoSZdBAEfB-Gnmauzs';
                    {$break}";
        $script .= "{$tab}{$tab}{$tab}\$item['Furl_struct'] = trim(\$item['Furl_struct']);{$break}";
        $script .= "{$tab}{$tab}{$tab}if(\$item['Furl_struct']){$break}";
        $script .= "{$tab}{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}\$item['Furl_struct'] = \$this->drawLongUrl(\$item['Furl_struct']);{$break}";
        $script .= "{$tab}{$tab}{$tab}}{$break}";
        $script .= "{$tab}{$tab}{$tab}//\$item['Furl_struct'] = 'http://weibo.com/p/1001603871693059011214';{$break}";
        $script .= "{$tab}{$tab}{$tab}if(empty(\$item['Furl_struct']) || strpos(\$item['Furl_struct'],'http://weibo.com') === false){$break}";
        $script .= "{$tab}{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}}else{$break}";
        $script .= "{$tab}{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}\$options['data']['url'] = \$item['Furl_struct'];{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}\$options['type'] = 'Originalhtml';{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}\$this->Fetch(\$item['Furl_struct'], 'DetailPage', \$options);{$break}";
        $script .= "{$tab}{$tab}{$tab}}{$break}";
        $script .= "{$tab}{$tab}}{$break}";
        $script .= "{$tab}}{$break}";
        $script .= "{$break}";
        
        $script .= "{$tab}public function DetailPage(\$response, \$options){$break}";
        $script .= "{$tab}{{$break}";
        $script .= "{$tab}{$tab}if(empty(\$options['data']['Fweibo_id'])){$break}";
        $script .= "{$tab}{$tab}{$tab}return;{$break}";
        $script .= "{$tab}{$tab}\$html = \$response;{$break}";
        $script .= "{$tab}{$tab}\$html = str_replace('\\\\".'"'."','".'"'."',\$html);{$break}";
        $script .= "{$tab}{$tab}\$html = str_replace('\\\\n','',\$html);{$break}";
        $script .= "{$tab}{$tab}\$html = str_replace('\\\\r','',\$html);{$break}";
        $script .= "{$tab}{$tab}\$html = str_replace('\\\\t','',\$html);{$break}";
        $script .= "{$tab}{$tab}\$html = str_replace('\\\\/','/',\$html);{$break}";
        $script .= "{$tab}{$tab}\$html = str_replace('/>','>',\$html);{$break}";
        $script .= "{$tab}{$tab}\$html = str_replace('<!doctype html>','',\$html);{$break}";
        $script .= "{$tab}{$tab}\$html = str_replace('<body >','',\$html);{$break}";
        $script .= "{$tab}{$tab}\$html = str_replace('</body>','',\$html);{$break}";
        $script .= "{$tab}{$tab}preg_match(".'"'."/<div class=\\\\".'"'."header\\\\".'"'."><h1 class=\\\\".'"'."title\\\\".'"'.">(.*?)<\\\\/h1>/".'"'.", \$html, \$match);{$break}";
        $script .= "{$tab}{$tab}\$title = trim(\$match[1]);{$break}";
        $script .= "{$tab}{$tab}preg_match(".'"'."/<span class=\\\\".'"'."time\\\\".'"'.">(.*?)<\\\\/span>/".'"'.", \$html, \$match);{$break}";
        $script .= "{$tab}{$tab}\$pubtime = \$match[1];{$break}";
        $script .= "{$tab}{$tab}\$pubtime = str_replace('年','-',\$pubtime);{$break}";
        $script .= "{$tab}{$tab}\$pubtime = str_replace('月','-',\$pubtime);{$break}";
        $script .= "{$tab}{$tab}\$pubtime = str_replace('日','',\$pubtime);{$break}";
        $script .= "{$tab}{$tab}\$pubtime = trim(\$pubtime);{$break}";
        $script .= "{$tab}{$tab}preg_match(".'"'."/<div class=\\\\".'"'."WBA_content\\\\".'"'.">(.*)[<\\\\/div>|<\\\\/p>]?<\\\\/div><\\\\/div><\\\\/div><\\\\/div>/".'"'.", \$html, \$match);{$break}";
        $script .= "{$tab}{$tab}\$content = trim(\$match[1]);{$break}";
        $script .= "{$tab}{$tab} preg_match_all(".'"'."/<img.*? src=\\\\".'"'."(.*?)\\\\".'"'."/".'"'.", \$content, \$match);{$break}";
        $script .= "{$tab}{$tab}\$imgs = array();{$break}";
        $script .= "{$tab}{$tab} if(\$match && \$match[0] && \$match[1]){$break}";
        $script .= "{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}unset(\$match[0]);{$break}";
        $script .= "{$tab}{$tab}{$tab}foreach(\$match[1] as \$value ){$break}";
        $script .= "{$tab}{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}if(strpos(\$value,'e.weibo.com/v1/public/stats/article') ===false ){$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}{$tab}\$imgs[] = \$value;{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}}{$break}";
        $script .= "{$tab}{$tab}{$tab}}{$break}";
        $script .= "{$tab}{$tab}}{$break}";
        $script .= "{$tab}{$tab}{$tab}\$article = array({$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'title' => \$title ,{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'pubtime' => \$pubtime ,{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'content' => \$content  ,{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'count' => count(\$imgs),{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'imgs' => \$imgs ,{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'url' =>\$options['data']['url'],{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'source'=>\$options['data']['source'],{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'Fsection_id'=>\$options['data']['Fsection_id'],{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'Fscreen_name'=>\$options['data']['Fscreen_name'],{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'Fweibo_id'=>\$options['data']['Fweibo_id'],{$break}";
        $script .= "{$tab}{$tab}{$tab}{$tab}'Is_long_wb'=>1{$break}";
        $script .= "{$tab}{$tab}{$tab});{$break}";
        $script .= "{$tab}{$tab}if(empty(\$article['title']) || empty(\$article['content']) || empty(\$article['pubtime']) || empty(\$article['url'])) {$break}";
        $script .= "{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}\$this->SetError();{$break}";
        $script .= "{$tab}{$tab}}{$break}";
        $script .= "{$tab}{$tab}\$this->AddResult(\$article);{$break}";
        $script .= "{$tab}}{$break}";
        $script .= "{$tab}{$break}";

        
        $script .= "{$tab}public function drawLongUrl(\$url, \$options = array()){$break}";
        $script .= "{$tab}{{$break}";
        $script .= "{$tab}{$tab}\$url = trim(\$url);{$break}";
        $script .= "{$tab}{$tab}\$curl = curl_init();{$break}";
        $script .= "{$tab}{$tab}if(!empty(\$options['cookie'])){$break}";
        $script .= "{$tab}{$tab}{$tab}curl_setopt(\$curl, CURLOPT_COOKIE, \$options['cookie']);{$break}";
        $script .= "{$tab}{$tab}\$proxyConfig = array({$break}";
        $script .= "{$tab}{$tab}'http://115.159.5.247:80', 'http://182.254.153.54:80','http://203.195.172.147:80','http://203.195.162.96:80', 'http://203.195.160.14:80',{$break}";
        $script .= "{$tab}{$tab});{$break}";
        $script .= "{$tab}{$tab}curl_setopt(\$curl, CURLOPT_PROXY, \$proxyConfig[mt_rand(0, count(\$proxyConfig) - 1)]);{$break}";
        $script .= "{$tab}{$tab}curl_setopt(\$curl, CURLOPT_URL, \$url);{$break}";
        $script .= "{$tab}{$tab}curl_setopt(\$curl, CURLOPT_HEADER, 0);{$break}";
        $script .= "{$tab}{$tab}curl_setopt(\$curl, CURLOPT_RETURNTRANSFER, 1);{$break}";
        $script .= "{$tab}{$tab}curl_setopt(\$curl, CURLOPT_NOSIGNAL, 1);{$break}";
        $script .= "{$tab}{$tab}\$html = curl_exec(\$curl);{$break}";
        $script .= "{$tab}{$tab}curl_close(\$curl);{$break}";
        $script .= "{$tab}{$tab}preg_match(".'"'."/<A HREF=\\\\".'"'."(.*?)\\\\".'"'.">here/".'"'.", \$html, \$match);{$break}";
        $script .= "{$tab}{$tab}\$longUrl = \$match[1] ? \$match[1] : '';{$break}";
        $script .= "{$tab}{$tab}return \$longUrl;{$break}";
        $script .= "{$tab}}{$break}";

        $script .= "}{$break}";

        $script .= "{$break}";

		return $script;
	}
	
	
}


