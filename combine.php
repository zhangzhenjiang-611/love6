<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\7\31 0031
 * Time: 11:11
 */
$a=['aa','bb','cc','dd'];
//$a=12;
$b=[1,2,3,4];
print_r(array_combine($a,$b));
echo "<br/>";
print_r($a+$b);
echo "<br/>";
print_r(array_merge($a,$b));
echo "<br/>";
$arr1 = array("a"=>"PHP","b"=>"java","python");
foreach ($arr1 as $k=>$v){
    echo "$k"."<br/>";
}
echo "<hr/>";
$arr1 = array("a"=>"PHP","b"=>"java","python");

$arr2 = array("c" =>"ruby","d" => "c++","go","a"=> "swift","js");

$arr3 = array_merge($arr1,$arr2);

$arr4 = $arr1 + $arr2; //相同键名先出现的被保留,数字键会被重新排序
print_r($arr3);  //array_merge对数值键不会覆盖，但会对字符键进行覆盖，如果两个数组字符键相同，则先出现的覆盖后出现的。对数字键的值会从0开始重新排列
echo "<hr/>";
print_r($arr4);  //加号进行数组合并则无论是数字键还是字符键，只要相同首先出现的将被保留，后出现的将被丢弃，且数字键会被重新排序