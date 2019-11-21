<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\10\23 0023
 * Time: 17:36
 */
//闭包函数

   function getMoneycount(){
       $a = 2;
      $func = function() use (&$a) {
          echo $a;
          $a++;
      };
     return $func();

   }
 $getMoneys= getMoneycount();
 /*$getMoneys();
 $getMoneys();
 $getMoneys();*/



