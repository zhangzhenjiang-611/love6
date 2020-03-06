<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/2/24
 * Time: 20:33
 */
//把数组里面的值赋值给对应的变量 只能用于下标连续的索引数组
list($a,$b,,$d) = [11,22,33,44];
echo $a;
echo $b;
//echo $c;
echo $d;

echo "<hr/>";
$arr = ['妹子','亚军','江江'];
  /* $arr1 = each($arr);
   print_r($arr1);
   echo "<br/>";
$arr1 = each($arr);
print_r($arr1);
echo "<br/>";
$arr1 = each($arr);
print_r($arr1);
echo "<br/>";
$arr1 = each($arr);
print_r($arr1);
echo "<br/>";*/
  while(list($key,$val) = each($arr)){
      //print_r($tmp);
      //echo "{$tmp['key']}=>"."{$tmp['value']}";
      //echo "{$tmp['0']}=>"."{$tmp['1']}";
      echo "{$key}=>"."{$val}";
      echo "<br/>";
  }