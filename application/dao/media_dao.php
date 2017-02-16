<?php

include_once "base_dao.php";
Class Media_dao extends Base_dao
{
    private $TableName = 'media_status';


	public function getMediaByUrl($url)
	{
		$sql = "SELECT `id` ".
               "FROM `{$this->TableName}` ".
               "WHERE `url` = '{$url}' ";
		return $this->get_one($sql);
	}
   
}


