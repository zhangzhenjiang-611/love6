<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/16
 * Time: 15:03
 */
//初始化文件 框架初始化

//初始化当前的绝对路径
//echo __FILE__;
//define('ROOT',substr(str_replace('\\','/',__FILE__),0,-8));
define('ROOT',str_replace('\\','/',dirname(dirname(__FILE__))).'/');

 /*echo dirname($str);
 exit;*/
define('DEBUG',true);
require (ROOT.'/include/db.class.php');
require (ROOT.'/include/conf.class.php');
require (ROOT.'/include/log.class.php');
require (ROOT.'/include/lib_base.class.php');
require (ROOT.'/include/mysql.class.php');
//递归 过滤参数
  $_GET = zhuanyi($_GET);
  $_POST = zhuanyi($_POST);
  $_COOKIE = zhuanyi($_COOKIE);
//设置报错级别

   if(defined('DEBUG')) {
       error_reporting(E_ALL);
   } else{
       error_reporting(0);
   }

