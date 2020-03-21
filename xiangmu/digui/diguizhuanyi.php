<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/20
 * Time: 16:18
 */
$arr = array('a"b',array("c'd",array('e"f')));
   function zhuanyi($arr) {
       if(is_array($arr)){
           foreach($arr as $k=>$v) {
               if(is_array($v)) {
                   $arr[$k] = zhuanyi($v);
               } else{
                   $arr[$k] = addslashes($v);
               }
           }
       }
       return $arr;
   }
   echo "<pre>";
   print_r(zhuanyi($arr));
   echo "<hr/>";
  function add($arr) {
      foreach ($arr as $k=>$v) {
          if(is_string($v)) {
             $arr[$k] = addslashes($v);
          } elseif (is_array($v)) {
              $arr[$k] = add($v);
          }
      }
      return $arr;
  }

echo "<pre>";
print_r(add($arr));

echo "<pre>";
print_r($arr);