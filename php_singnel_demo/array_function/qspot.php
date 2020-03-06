<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/5
 * Time: 16:57
 */
$arr = [11,2,31,9,1,568,26,32,16,798];
  function qsort($arr){
      if(!is_array($arr) || empty($arr)){
          return [];
      }
      $len = count($arr);
      if($len <= 1){
          return $arr;
      }
      $key[0] = $arr[0];
      $left = [];
      $right = [];
      for($i = 1; $i < $len; $i++){
          if($arr[$i] <= $key[0]){
              $left[] = $arr[$i];
          }else{
              $right[] = $arr[$i];
          }
      }
/*
      print_r($left);
      echo "<br/>";
      echo $key;
      echo "<br/>";
      print_r($right);
      echo "<br/>";*/
      $left = qsort($left);
      $right = qsort($right);

      return array_merge($left,$key,$right);
  }
//qsort($arr);
  print_r($arr);
  echo "<br/>";
  print_r(qsort($arr));