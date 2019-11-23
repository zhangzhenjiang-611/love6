<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/21
 * Time: 23:30
 */
//echo "yanli";
//php全局变量 在函数外部声明的变量 都叫做全局变量 可以在每个函数中使用（顺序）
//所有在函数内部声明的变量，都是新声明的变量
$name = "高老师";
  function demo(){
      global $name;  //声明为全局
     //$name = 'mezoi'; //高老师高老师
      $name = 'mezoi';  //mezoimezoi
      echo $name;
  }
  demo();

  echo $name;
echo "<hr/>";
$name = "高老师";
function demo1(){
    $name = 'mezoi';
    global $name;  //声明为全局
    echo $name;
}
demo1();

echo $name;  //高老师高老师


echo "<hr/>";
$name = "高老师";
$age = 30;
function demo2(){
    global $name,$age;  //声明为全局
    //$name = 'mezoi'; //高老师高老师
    $name = 'mezoi';  //mezoimezoi
    $age = 20;
    echo $name.'----'.$age;
}
demo2();
echo $name.'----'.$age;

//位置 调用之后

echo "<hr/>";
echo "<hr/>";

function demo3(){

    $name1 = 'mezoi'; //高老师高老师
    $name = 'mezoi';  //mezoimezoi
    $age1 = 20;
    global $name1,$age1;  //声明为全局
    echo $name1.'----'.$age1; //找不到
}
demo3();
$name1 = "高老师";
$age1 = 30;
//echo $name.'----'.$age;

//位置 调用之后

echo "<hr/>";
echo "<hr/>";

function demo4(){

    $name2 = 'mezoi'; //高老师高老师
    $name = 'mezoi';  //mezoimezoi
    $age2 = 20;
    global $name2,$age2;  //声明为全局
    echo $name2.'----'.$age2; //找不到
}

$name2 = "高老师";
$age2 = 30;
demo4();
//echo $name.'----'.$age;



