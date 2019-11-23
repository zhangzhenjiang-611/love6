<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\10\24 0024
 * Time: 9:26
 */
  $hello = function () {
      echo 235;
  };
  $hello();
  echo "<br/>";
    function sayHello(){
        $num = 12;
        $hi = function () use ($num) {
            return $num;
        };
        echo $hi();
    }
    sayHello();
echo "<br/>";
    function getCount(){
       static $count = 25;
        $fun = function () use (&$count){
           return  $count++;
        };
        echo $fun();
        echo "<br/>";
    }
 getCount();
 getCount();
 getCount();
