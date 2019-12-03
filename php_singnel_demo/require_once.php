<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\26 0026
 * Time: 18:00
 */
/*
 *
 * 有一个文件a.php,里面有一个变量$var=1;我在b.php 中用require_once()函数引用了a.php文件，然后我在c.php 中用require_once()引用了b.php文件，在c.php中有一个函数 需要引用a.php中的变量$var. 但却访问不了 变量的值为空*/

/*1 include与require的区别
include与require除了在处理引入文件的方式不同外，最大的区别就是：include在引入不存文件时产生一个警告且脚本还会继续执行，而require则会导致一个致命性错误且脚本停止执行。*/
require 'a.php';
require_once 'a.php';  //包含过了一次，不会再包含
echo "<br/>";


require_once 'a.php';  //包含过了一次，不会再包含
require_once 'a.php';  //包含过了一次，不会再包含
echo "<br/>";