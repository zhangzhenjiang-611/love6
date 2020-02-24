<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\10\23 0023
 * Time: 17:22
 */
//闭包函数
function getMoney() {
    $rmb = 1;
    $dollar = 6;
    $func = function() use ( &$rmb,$dollar ) {
        echo $rmb;
        echo "<br/>";
        echo $dollar;
        $rmb++; //匿名函数里不能改变局部变量
    };
    $func();
    echo "<br/>";
    echo $rmb;
}
getMoney();

echo "<hr/>";
//声明闭包函数
$a=100;
$fun = function () use ($a) {
    echo 666;
    echo $a;
};
$fun();