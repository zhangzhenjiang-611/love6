<?php
header("Content-Type: text/html; charset=utf-8"); 
include_once("ez_sql_core.php");
include_once("ez_sql_mysql.php");
	
$db = new ezSQL_mysql('root','trans','changzheng','192.168.1.175','utf-8');
	$db->query("set names utf8");
	$list1 = $db->get_results("select * from ct");

print_r($list1); 

?>