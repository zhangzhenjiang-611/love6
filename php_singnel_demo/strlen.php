<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\26 0026
 * Time: 9:21
 */
//字符串函数 取字符串长度  strlen()
$str = 'Hello Jelly ';
echo strlen($str);

echo "<hr/>";
//对字符串中的单词计数 str_word_count()
$str2 = 'hello world ab c&d ';
echo str_word_count($str2);

//反转字符串 strrev()
echo "<hr/>";
$str3 = 'hello world ab c&d ';
echo strrev($str3);

//检索字符串内指定的字符或文本 如果找到匹配，则会返回首个匹配的字符位置。如果未找到匹配，则将返回 FALSE。
echo "<hr/>";
$str4 = 'hello aworld ab c&d ';
echo strpos($str4,'o');
echo strpos($str4,'ab');
echo strpos($str4,'a');