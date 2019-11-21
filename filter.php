<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\10\23 0023
 * Time: 18:29
 */
$arr2 = array('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5,'f'=>6);
$resArr2=array_filter($arr2,'fun_odd');//其中的fun_odd必须加引号，不能加()
print_r($resArr2);
function fun_odd($arr){
    if($arr % 2 == 1){
        return $arr;
    }
}