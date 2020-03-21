<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/17
 * Time: 15:48
 */
 $file = './custom.txt';
  $cont = file_get_contents($file);
 // print_r(explode("\r\n",$cont));

 //打开 一点一点的读
 //fgets
$fh = fopen($file,'rb'); //二进制格式
/*echo fgets($fh);   //每次读一行
echo fgets($fh);   //每次读一行
echo fgets($fh);   //每次读一行*/

 //文件指针一直移动 feof  end of file
  /*while (!feof($fh)) {
      echo fgets($fh)."<br/>";
  }*/


     $arr = file($file);  //file函数直接读取文件内容，并且按行拆成数组
     print_r($arr);
     echo date('Y-m-d H:i:s',fileatime($file));

