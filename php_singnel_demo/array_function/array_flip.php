<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/4
 * Time: 13:08
 */
//array_flip交换数组中的键和值
$arr= ['a'=>'hello','b'=>'japan','c'=>null,'d'=>'japan'];
$arr1 = array_flip($arr);
print_r($arr1);