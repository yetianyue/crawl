<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proxy extends CI_Controller
{
    public function Url()
    {
        $opt = array(
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $_GET['url'],
            CURLOPT_REFERER => $_GET['ref'],
        );

        if(empty($opt[CURLOPT_REFERER]))
            $opt[CURLOPT_REFERER] = $_GET['url'];

        $curl = curl_init();
        curl_setopt_array($curl, $opt);
        $data = curl_exec($curl);

        header("Content-Type: ".curl_getinfo(CURLINFO_CONTENT_TYPE));
        echo $data;
    }
    
}


