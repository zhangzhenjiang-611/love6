<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/13
 * Time: 16:24
 */
  $str = "hello world!";
  $str1 = rtrim($str,"!"); //取出字符串右边的！ 默认删除右边空格
  echo $str1."<br>";

    $str2="hello wor89iold!";
    $str3 = trim($str2,"old!");
    echo $str3;
echo "<br>";
    //字符串填充
    $str = "lamp";
    echo strlen($str)."<br>";
    $str = str_pad($str,10);
    echo strlen($str)."<br>";

    $str = "lamp";
    //$str = str_pad($str,10,'@');  //默认右边补齐
    $str = str_pad($str,10,'@',STR_PAD_LEFT);  //左边补齐
    echo $str."<br>";

    $strr = "hello world0";
    echo ucwords($strr); //将字符串的每个首字母大写
