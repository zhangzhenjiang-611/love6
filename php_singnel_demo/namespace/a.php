<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/12
 * Time: 16:04
 */
//命名空间 解决常量 类 函数重名问题
namespace application;
 define('NAME','dd');
 function var_dump(){
     echo "sssssssssss";

 }

 var_dump(); //调用自己命名空间下的方法
 \var_dump([1,2,3]); //调用系统的函数
 echo NAME;