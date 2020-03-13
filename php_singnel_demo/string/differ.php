<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/13
 * Time: 11:02
 */
//双引号可以解析变量 可以使用所有的转义字符  分割查找 匹配 替换
echo count('hello');  //1
echo "<br>";
echo count("");  //1
echo "<br>";

echo strlen(10000000);


echo "<br>";
$str = "hello";
echo $str{1};  //e 字符串的访问{}

echo "<br>";
  echo strlen("中国"); //utf-8每个汉字占3个字节 gb2312占2个

