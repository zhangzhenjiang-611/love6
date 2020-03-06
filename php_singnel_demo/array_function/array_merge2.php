<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/6
 * Time: 13:05
 */
//数组相加
$a = ['a','b','c'];
$b = [5=>1,2,3];
print_r($a + $b); //Array ( [0] => a [1] => b [2] => c ) 下标相同的会覆盖,前面会覆盖后面的
echo "<br/>";
print_r($b + $a); //Array ( [0] => 1 [1] => 2 [2] => 3 )
//Array ( [0] => a [1] => b [2] => c [5] => 1 [6] => 2 [7] => 3 )

print_r(array_merge($a,$b));
echo "<br/>";
//交集
$c = ['a','b',12,34];
$d = ['b','c',34,56];
print_r(array_intersect($c,$d));

print_r(array_diff($c,$d));