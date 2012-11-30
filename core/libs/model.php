<?php


class Model {

	//连接句柄
	private $connection;

	//连接数据库
	public function __construct()
	{

		/*
		global $config;
		
		$this->connection = mysql_pconnect($config['db_host'], $config['db_username'], $config['db_password']) or die('MySQL Error: '. mysql_error());
		mysql_select_db($config['db_name'], $this->connection);
		*/
	}

	//escape字符串
	public function escapeString($string)
	{
		return mysql_real_escape_string($string);
	}

	//escape数组
	public function escapeArray($array)
	{
	    array_walk_recursive($array, create_function('&$v', '$v = mysql_real_escape_string($v);'));
		return $array;
	}
	
	//转成布尔型
	public function to_bool($val)
	{
	    return !!$val;
	}
	
	//转成日期型date("Y-m-d")
	public function to_date($val)
	{
	    return date('Y-m-d', $val);
	}

	//转成时间型date("H:i:s")	
	public function to_time($val)
	{
	    return date('H:i:s', $val);
	}
	
	//转成日期和时间型date("Y-m-d H:i:s")
	public function to_datetime($val)
	{
	    return date('Y-m-d H:i:s', $val);
	}

	/*
	//查询数据库，返回对象
	public function query($qry)
	{
		
		$result = mysql_query($qry) or die('MySQL Error: '. mysql_error());
		$resultObjects = array();

		while($row = mysql_fetch_object($result)) $resultObjects[] = $row;

		return $resultObjects;
		
	}
	*/

	//执行数据库查询
	public function execute($qry)
	{
		$exec = mysql_query($qry) or die('MySQL Error: '. mysql_error());
		return $exec;
	}
    
}