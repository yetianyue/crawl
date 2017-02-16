<?php

class DB
{
    private $host;
    private $user;
    private $passwd;
    private $dbName;
    private $link_id;

    public function __construct($host="10.198.30.118:4201", $user="spider", $passwd="79fabf334", $dbName="spider")
    {
    	$this->host = $host;
    	$this->user = $user;
    	$this->passwd = $passwd;
    	$this->dbName = $dbName;    	
        $this->connect();
    }

    public function connect()
    {
        $this->link_id = mysql_connect($this->host,$this->user,$this->passwd) or die("connect fail");
        mysql_select_db($this->dbName,$this->link_id) or die("select db fail");
        mysql_query("set names utf8");
    }

    public function reconnect()
    {
        if(!empty($this->link_id))
        {
            mysql_close($this->link_id);
        }
        $this->connect();
    }

    public function query($sql)
    {
        if(empty($this->link_id))
        {
            $this->reconnect();
        }
        $times = 3;
        while($times > 0)
        {
            $query = mysql_query($sql, $this->link_id);
            if($query == FALSE)
            {
            	if(mysql_errno($this->link_id)==2006)
            	{
            		$this->reconnect();
            	}
            	else 
            	{
            		return FALSE;
            	}
                
            }
            else
            {
                return $query;
            }
            $times--;
        }
        echo "db connect fail";
        exit;
    }

    public function getAll($sql, $result_type = MYSQL_ASSOC)
    {
        $query = $this->query($sql);
        $i = 0;
        $result_array = array();
        while($row = &mysql_fetch_array($query, $result_type))
        {
            $result_array[$i] = $row;
            $i++;
        }
        return $result_array;
    }
    
    public function getOne($sql, $result_type = MYSQL_ASSOC)
    {
    	$query = $this->query($sql);
    	$rt = &mysql_fetch_array($query, $result_type);
    	return $rt;
    }

}


















