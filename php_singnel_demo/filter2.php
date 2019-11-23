<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\10\23 0023
 * Time: 18:32
 */
$arr2 = array('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5,'f'=>6);
$arr3 = [4,5,3];
$func=array_filter($arr2,function ($val) use ($arr3) {
    //echo in_array($val,$arr3) ? 1 :2;
    //echo "<br/>";
    return in_array($val, $arr3) ? true : false;
});
//echo "<pre>";
print_r($func);
