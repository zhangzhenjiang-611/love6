<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/3
 * Time: 15:40
 */
//strpos
//查找 "php" 在字符串中第一次出现的位置：
echo strpos("You love php, I love php too!","php");
echo "<br>";
//strpos() 函数查找字符串在另一字符串中第一次出现的位置。
//strpos() 函数对大小写敏感。
/*stripos() - 查找字符串在另一字符串中第一次出现的位置（不区分大小写）
strripos() - 查找字符串在另一字符串中最后一次出现的位置（不区分大小写）
strrpos() - 查找字符串在另一字符串中最后一次出现的位置（区分大小写）*/
//字符串位置从 0 开始，不是从 1 开始。
$str = 'hello world';
echo strrpos($str,'o');
echo "<br>";
$url = 'a/bc';
echo strpos($url, '/');
var_dump(0 === strpos($url, '/')) ;