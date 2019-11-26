<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\26 0026
 * Time: 18:14
 */
include 'a1.php';
echo 221;   //报waring错误后继续执行下面的代码 a1.php 文件不存在
/*
( ! ) Warning: include(a1.php): failed to open stream: No such file or directory in D:\demo\love6\php_singnel_demo\include_require.php on line 8
Call Stack
#	Time	Memory	Function	Location
1	0.0000	360664	{main}( )	...\include_require.php:0

( ! ) Warning: include(): Failed opening 'a1.php' for inclusion (include_path='.;C:\php\pear') in D:\demo\love6\php_singnel_demo\include_require.php on line 8
Call Stack
#	Time	Memory	Function	Location
1	0.0000	360664	{main}( )	...\include_require.php:0
221*/


require 'a1.php';    //报Fatal错误后停止执行下面的代码 a1.php 文件不存在
echo 222;

/*( ! ) Warning: require(a1.php): failed to open stream: No such file or directory in D:\demo\love6\php_singnel_demo\include_require.php on line 23
Call Stack
#	Time	Memory	Function	Location
1	0.0000	360840	{main}( )	...\include_require.php:0

( ! ) Fatal error: require(): Failed opening required 'a1.php' (include_path='.;C:\php\pear') in D:\demo\love6\php_singnel_demo\include_require.php on line 23
Call Stack
#	Time	Memory	Function	Location
1	0.0000	360840	{main}( )	...\include_require.php:0*/