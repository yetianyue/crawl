<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once dirname(dirname(__FILE__))."/third_party/nameapi.php";


$active_group = 'default';
$query_builder = TRUE;
//10.240.64.138 3324
$host = new ZkHost();
getHostByKey('s3324.qqnews_a_num.cdb.com', $host);

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $host->ip,
	'port' => $host->port,
	'username' => 'qqnews_a_num_r',
	'password' => '8b7d50905',
	'database' => 'qqnews_a_num',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE,
);


