<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/4
 * Time: 13:27
 */
$str = 'abcdefg';
print_r(count($str));
$str = '';
print_r(count($str));
echo "<br/>";
$arr2 = [
    'aa'=>['a'=>10,'b'=>11],
    'bb'=>['c'=>12,'d'=>13],
    'cc'=>['c'=>12,'d'=>13],
];
print_r(count($arr2));  //统计最外层数组元素的个数
print_r(count($arr2,1)); //递归统计数组中所有元素的个数