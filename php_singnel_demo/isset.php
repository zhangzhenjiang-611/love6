<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\8\2 0002
 * Time: 11:00
 */
$a='shj';
$b=456;
$c=12;
unset($c);
if(isset($a,$b,$c)){
    echo "存在";
}else{
    echo "不存在";
}

if(isset($a) && !empty($a)){
    echo $a;
}