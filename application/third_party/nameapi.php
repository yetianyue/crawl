<?php
/*
created: by ronniechen
date: 2011-03-02 version: 1.0
date: 2011-04-10 version: 2.0
date: 2011-05-05 version: 3.0
date: 2011-06-06 version: 4.0
date: 2011-10-24 version: 5.0
*/
//require_once("/usr/local/zk_agent/names/nameapi_imp.php");

/*class ZkHost{
         var $ip="";
         var $port=0;
 };
 */
/* ���ַ���API
 * getHostByKey($key, ZkHost& $host)
 * param key: key name
 * param host:  host info
 * return Value: 
 *     0: success
 *     
 **/

if ( !function_exists('getHostByKey'))
{
	function getHostByKey($key, ZkHost& $host, $timeout=500)
	{
		return getHostByKey_imp($key, $host, $timeout);
	}
}


if ( ! function_exists('getHostByKey2'))
{

	function getHostByKey2($key, ZkHost& $host, $ip, $timeout=500)
	{
		//echo $ip;
		return getHostByKey_imp($key, $host, $timeout, $ip);
	}

}
/* �ֵ����API
 * getValueByKey($key, $value)
 * param key: key name
 * param value:  value, max length=1K
 * return Value: 
 *      0: success
 *    

 **/

if( ! function_exists('getValueByKey'))
{
	function getValueByKey($key, &$value, $timeout=500)
	{
		return getValueByKey_imp($key, $value, $timeout);
	}

}


if( ! function_exists('getValueByKey2'))
{	
	function getValueByKey2($id, $key, &$value, $timeout=500)
	{
		$newkey=$id."_".$key;
		return getValueByKey_imp($newkey, $value, $timeout);
	}

}

?>
