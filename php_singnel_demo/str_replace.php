<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\26 0026
 * Time: 15:08
 */
// str_replace() 函数用一些字符串替换字符串中的另一些字符
$str = 'hello world Jelly kitty';
$str1= 'Lily';
$str2 = str_replace('kitty',$str1,$str);
echo $str2;

echo "<hr/>";
$str3 = 'I love Lily';
$str4 = str_replace('Lily','aa',$str3);
echo $str4;