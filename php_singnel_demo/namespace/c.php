<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/12
 * Time: 17:11
 */
//变量函数
namespace cpp\dpp\epp;
  function say() {
      echo "吃货";
  }
     //$fun = "eat";
//$fun();  //声明命名空间后 变量函数不能直接使用
  //\app\eat();

  // $func = 'cpp\dpp\say';
   $func = __NAMESPACE__.'\say';

   $func();
   echo __NAMESPACE__;
   echo "<hr/>";
   //namespace\say();  //当前命名空间
