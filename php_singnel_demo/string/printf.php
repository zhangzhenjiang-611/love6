<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/13
 * Time: 14:34
 */
//printf 格式化输出
  $int = 100;
  //转ASCII码
  echo chr($int);

  printf("%c",$int);  //转ASCII码
  printf("%d,%c",$int,$int);  //转ASCII码
  echo "<br>";
   printf("%cbbbbbbb{$int}bbbbb",$int);
   echo "<br>";
   //二进制
   printf("%b",$int);

