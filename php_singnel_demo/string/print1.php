<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/13
 * Time: 11:59
 */
//字符串输出函数
  //substr 截取字符串
  $str1 = "hello world";

   $str2 = "你好妹子";

   echo substr($str1,0,7)."<br>";  //  要截取的字符串 起始位置 截取长度
   echo substr($str1,0,7)."<br>";  //  要截取的字符串 起始位置 截取长度

echo substr($str2,0,7)."<br>"; //你好�
echo mb_substr($str2,0,7,"utf-8")."<br>";  //你好妹子


//echo print有返回值
echo "gggggg";
echo ("jjjjjjjj");
echo "<br>";
echo 'aaa','bbb0','ccc'; //可以同时输出多个 指令形式
die(1);
//echo ('aaa','bbb0','ccc'); //错误
print 'aaa';
//print 'aaa','bbb0','ccc'; //不可以同时输出多个