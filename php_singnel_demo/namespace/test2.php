<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/12
 * Time: 16:16
 */
namespace app;
include 'test.php';
 function one(){
     echo 3333333333333;
 }
 function two(){
     echo 444444444444444;
 }
 one(); // 调用自己命名空间下的方法
 two(); //调用自己命名空间下的方法
  \one(); //调用全局的方法
