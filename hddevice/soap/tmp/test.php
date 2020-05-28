<?php 
include_once("ez_sql_core.php");
include_once("ez_sql_mysql.php");
$db = new ezSQL_mysql('root','trans','menzhen','192.168.1.173','utf-8');
$db->query("set names utf8");
$row = $db->get_var("select count(*) from register_assign");
echo $row;

?> 
