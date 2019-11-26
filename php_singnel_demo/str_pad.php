<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\25 0025
 * Time: 17:09
 */
//str_pad() 函数把字符串填充为新的长度。
$str1 = 'abc';
$str1 = str_pad($str1,'6','&',STR_PAD_RIGHT );     //6新的长度 向右填充 默认
echo $str1;
echo "<hr/>";

$str2 = 'def';
$str2 = str_pad($str2,'6','*',STR_PAD_LEFT );     //6新的长度 向左填充
echo $str2;
echo "<hr/>";


$str3 = 'ghk';
$str3 = str_pad($str3,'6','#',STR_PAD_BOTH );     //6新的长度 两侧填充 如果不是偶数，则右侧获得额外的填充。
echo $str3;
echo "<hr/>";