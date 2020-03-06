<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/5
 * Time: 12:56
 */
//冒泡排序法
$arr = [0,1,2,3,4,5,6,7,8,9];
 function mySort(&$arr){
     $len = count($arr);
//从大到小
     for($i = 0; $i< $len - 1; $i++){
         for($j = 0; $j < $len - $i - 1; $j++) {
             if($arr[$j] < $arr[$j+1]) {
                 $tmp = $arr[$j];
                 $arr[$j] = $arr[$j+1];
                 $arr[$j+1] = $tmp;
             }
         }
     }

 }
 mySort($arr);
 print_r($arr);
