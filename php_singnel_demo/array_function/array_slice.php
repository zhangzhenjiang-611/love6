<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/6
 * Time: 12:38
 */
//array_slice 从数组中取出一段
$arr = ['a','b','c','d','e','f'];

$narr = array_slice($arr,2);
print_r($narr); //Array ( [0] => c [1] => d [2] => e [3] => f )

$barr = array_slice($arr,2,2);
print_r($barr);  //Array ( [0] => c [1] => d )

$carr = array_slice($arr,-2,2);
print_r($carr);  //Array ( [0] => e [1] => f )


$darr = array_slice($arr,-2,2,true);
print_r($darr);  //Array ( [4] => e [5] => f ) 保留原数组键名

echo "<br/>";
//array_splice() //把数组中的一部分去掉并用其他值替代
//$err = array_splice($arr,2,2,'aa'); //改变原数组 Array ( [0] => a [1] => b [2] => aa [3] => e [4] => f )
array_splice($arr,2,2,['aa','bb']);
print_r($arr);  //Array ( [0] => a [1] => b [2] => aa [3] => bb [4] => e [5] => f )
echo "<br/>";
$a = ['a','b','c','d'];
$b = ['A','B','C','D','e'];
$c = array_combine($a,$b);
print_r($c);