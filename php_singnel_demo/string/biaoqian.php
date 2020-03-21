<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/13
 * Time: 16:48
 */
//htmlspecialchars html标签实体化
//htmlspecialchars();
 $str = "hello world";
 echo strrev($str);
 echo "<br>";
 echo md5(md5($str));
echo "<br>";
 $str1 = "1234567890";
echo number_format($str1); //1,234,567,890 千分制
echo "<br>";
$str1 = "1234567890.23456";
echo number_format($str1,2); //1,234,567,890.23 千分制保留两位小数