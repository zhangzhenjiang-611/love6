<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/1/7
 * Time: 17:56
 */
//回调函数 在函数调用的时候，传的不是一个值，而是一个函数，这就是回调函数的参数
  function demo($num,$n){
      //$n = "test"
      for ($i = 0; $i<$num; $i++){
          if(call_user_func_array($n,[$i])){
              continue;
          }
          echo "{$i}"."<br/>";
      }
  }

  function test($i){
      //echo 123; $i%6 == 0
      if(preg_match('/3/',$i)){
          return 1;
      }
      else{
          return 0;
      }
  }

  demo(100,'test');