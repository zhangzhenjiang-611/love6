<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/6
 * Time: 0:35
 */
//变量函数
function add($a=0,$b=0){
    return $a + $b;
}

echo add(1,3);
echo "<br>";
$var = "add";

echo $var(2,6);