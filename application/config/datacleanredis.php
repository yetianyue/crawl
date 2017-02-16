<?php

/*$host = new ZkHost();
getHostByKey('sz.dataclean.redis.com', $host);

$redis_config = array(
	'default' => array(
		'hostname' => $host->ip,
		'port'     => $host->port,
		'weight'   => '1',
	),
);
*/


$redis_config = array(
	'default' => array(
		'hostname' => '127.0.0.1',
		'port'     => '6379',
		'weight'   => '1',
	),
);
