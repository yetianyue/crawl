<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helper extends CI_Controller
{
    public function Index()
    {
        $this->load->view('incs/header');
        $this->load->view('incs/menu');

        $this->load->view('home');

        $this->load->view('incs/footer');
    }
}



