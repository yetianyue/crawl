<?php

class Media_model extends CI_Model 
{

    public function __construct()
    {
        $DbConfig = dirname(dirname(__FILE__))."/config/mediadb.php";
        include_once $DbConfig;
        $DbBase = dirname(dirname(__FILE__))."/dao/media_dao.php";
        include_once $DbBase;
        $this->Media_dao = new Media_dao($db['default']);
    }

    public function getMediaByUrl($list_url)
    {
        $list_url = json_decode($list_url,true);
        foreach($list_url as $key=>$value)
        {
        
            $url = $key;
            break;
        }
        if(empty($url))
        {
            return '';
        }
        $res = $this->Media_dao->getMediaByUrl($url);
		
		if(empty($res))
		{
			$url = substr($url,7);
			$res = $this->Media_dao->getMediaByUrl($url);
		}
        return $res['id'];
    }

}



