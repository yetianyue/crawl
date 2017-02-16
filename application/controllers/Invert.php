<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invert extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("invert_model");
	}
	
	public function  DeleteExpireData()
	{
		$this->invert_model->DeleteExpireData();
	}
}