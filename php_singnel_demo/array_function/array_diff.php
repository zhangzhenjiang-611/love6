<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\26 0026
 * Time: 16:13
 */
//返回一个差集数组，该数组包括了所有在被比较的数组（array1）中，但是不在任何其他参数数组（array2 或 array3 等等）中的值。
$a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
$a2=array("e"=>"red","f"=>"green","g"=>"blue");

$result=array_diff($a1,$a2);
print_r($result);
echo "<hr/>";

$a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow",'k'=>'bbb');
$a2=array("e"=>"red","f"=>"black","g"=>"purple","h"=>"aaa");
$a3=array("a"=>"red","b"=>"black","h"=>"yellow");

$result=array_diff($a1,$a2,$a3);
print_r($result);
echo "<hr/>";


$a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
$a2=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");

$result=array_diff($a1,$a2);
print_r($result);
echo "<hr/>";

$arr1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
$arr2=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
if( !array_diff($arr1, $arr2) && !array_diff($arr2, $arr1)){
    // 即相互都不存在差集，那么这两个数组就是相同的了，多数组也一样的道理
   echo 1;
}else{
    echo 0;
}
echo "<hr/>";

$arr3=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellows");
$arr4=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
if( !array_diff($arr1, $arr2) || !array_diff($arr2, $arr1)){
    // 即相互都不存在差集，那么这两个数组就是相同的了，多数组也一样的道理
    echo 1;
}else{
    echo 0;
}
echo "<hr/>";
$arr3=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
$arr4=array("a"=>"red","b"=>"green","c"=>"blues","d"=>"yellow");
if( !array_diff($arr1, $arr2) || !array_diff($arr2, $arr1)){
    // 即相互都不存在差集，那么这两个数组就是相同的了，多数组也一样的道理
    echo 1;
}else{
    echo 0;
}
