<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/4
 * Time: 13:17
 */
//array_reverse交换数组中的键和值
$arr= ['a'=>'hello','b'=>'japan','c'=>12,'d'=>'japan'];
$arr1 = array_reverse($arr);
print_r($arr1);