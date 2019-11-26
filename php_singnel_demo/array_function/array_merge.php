<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\26 0026
 * Time: 15:22
 */
$a1=array("red","green");
$a2=array("blue","yellow");
echo "<pre>";
print_r(array_merge($a1,$a2));
echo "<hr/>";

//如果两个或更多个数组元素有相同的键名，则最后的元素会覆盖其他元素。
$arr1 = [
     'a' => 'red',
     'b' => 'green'
];
$arr2 = [
     'a' => 'blue',
     'b' => 'yellow'
];

$arr3 = array_merge($arr1,$arr2);
print_r($arr3);
$arr4 = array_merge($arr2,$arr1);
print_r($arr4);

//如果您仅向 array_merge() 函数输入一个数组，且键名是整数，则该函数将返回带有整数键名的新数组，其键名以 0 开始进行重新索引
echo "<hr/>";
$a=array(3=>"red",4=>"green");
print_r(array_merge($a));

//如果两个或更多个数组元素有相同的键名，则最后的元素不会覆盖其他元素。
echo "<hr/>";
$a1=array("a"=>"red","b"=>"green");
$a2=array("c"=>"blue","b"=>"yellow");
print_r(array_merge_recursive($a1,$a2));