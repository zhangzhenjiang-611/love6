<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/5
 * Time: 10:59
 */
//array_filter
$arr = [1,2,false,3,"",4,5,-1,-2,null,-3,-4,-5,0];
print_r($arr);
var_dump($arr);
var_dump(array_filter($arr)); //默认过滤值为false的
  function myFun($val){
      if($val>=0){
          return 1;
      }else{
          return 0;
      }
  }
  var_dump(array_filter($arr,'myFun'));
  $arr = [1,2,3,4,5,6,7,8];
  var_dump(array_filter($arr,function($value){
      if($value%2==0){
          return 0;
      }else{
          return 1;
      }
  }));