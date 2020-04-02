<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/25
 * Time: 10:46
 */
//标量类型声明
// 1强制2严格
  function checkArr(array $arr) {
      //php5支持此种语法
      var_dump($arr);
  }
  checkArr(array(1,2,3));
  //exit;
function check(int $number) {
    //php7以下不支持此种语法
    var_dump($number);
}
check(11);
echo "<br/>";
check(false);
echo "<br/>";
//check('abc');
echo "<br/>";
function checkString(string $str) {
    //php7以下不支持此种语法
    var_dump($str);
}
  checkString('hello');
  checkString(true);
  checkString(12);