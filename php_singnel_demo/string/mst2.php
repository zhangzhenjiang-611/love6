<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/15
 * Time: 15:12
 */
//写一个获取文件扩展名的函数

  $str = "https://www.baidu.com/saaa/bbb/init.inc.php?a=100";
  function exrname($str) {
      //1 首先判断有没有参数
      //echo strstr($str,'?',true);
      if(strstr($str,'?')) {
          //如果有问号,将问号前的取出，给$q
          list($q) = explode("?",$str);
      } else{
          $q = $str;
      }
   //2 把文件名取出 最后一个出现的位置
         $loc = strrpos($q,'/') + 1;
         $filename = substr($q,$loc);
         //获取扩展名称
         $arr = explode('.',$filename);
         //print_r($arr);
       return array_pop($arr);

  }
echo exrname($str);