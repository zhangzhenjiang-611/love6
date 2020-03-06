<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/5
 * Time: 12:41
 */
//array_map
$arr = [1,2,3,4,5];
$brr = [1,2,3,4,5,6];
print_r($arr);
echo "<br/>";
 function myFun($v,$bv){
     echo  $v*$v*$v;
     echo "<br/>";
     echo $bv+1;
     echo "<br/>";
     return 1;
 }
$rarr = array_map('myFun',$arr,$brr);
print_r($rarr);
echo "<br/>";