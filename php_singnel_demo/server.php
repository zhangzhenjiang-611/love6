<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/3
 * Time: 14:11
 */
//$_SERVER
//var_dump($_SERVER);
//获取客户端IP
var_dump($_SERVER['HTTP_CLIENT_IP']);
var_dump($_SERVER['HTTP_X_FORWARDED_FOR']);
var_dump($_SERVER['REMOTE_ADDR']);
echo "<hr/>";
var_dump($_COOKIE);
session_start();
var_dump($_SESSION);