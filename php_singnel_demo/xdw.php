<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/2/22
 * Time: 11:40
 */
//猴子选大王
     function xdw($m,$n) {
         $arr = array();
         $a = "a";
         for ($i = 0; $i<$m; $i++) {
             $arr[] = $a++;
          }
         $i = 0;
         while(count($arr) >1 ) {
             if($i%$n == 0){
                 unset($arr[$i]);
             } else{
                 $arr[] = $arr[$i];
                 unset($arr[$i]);
             }
             $i++;
         }
         return $arr;

}

$array = xdw(40,7);
     print_r($array);