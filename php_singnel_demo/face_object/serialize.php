<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/9
 * Time: 10:17
 */
//将对象转成字符串
include 'construct.php';
$b = new Person('张三',15,'女');
//var_dump($b);
file_put_contents('aa.txt',serialize($b));
var_dump(serialize($b));