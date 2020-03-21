<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/18
 * Time: 19:56
 */
//递归
  //求1-n的和？
  function sum($n)  {
      if($n <= 1) {
          return 1;
      } else{
          return $n + sum($n - 1);
      }
  }
     echo sum(100);
