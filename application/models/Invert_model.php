<?php

class Invert_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("tool_model");
		
	}
	public function DeleteExpireData()
	{
		ini_set("memory_limit","500M");
		$timeStamp = time() - 259200;
		$sql = "select  cmsid from invert_article where pubtime<{$timeStamp}";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		foreach($result as $key=>$val)
		{
			$table = "invert_dict";
			$where = "cmsid= '{$val['cmsid']}'";
			$this->tool_model->Delete($table,$where);
			$table = "invert_article";
			$this->tool_model->Delete($table,$where);
		}
		
		
	}
}