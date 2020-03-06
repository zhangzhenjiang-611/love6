<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/3
 * Time: 15:41
 */
//array_merge 索引数组不会覆盖
$a = ['aa','bb','cc'];
$b = ['aa','bb','cc'];
$c = array_merge($a,$b);
print_r($c);
//Array ( [0] => aa [1] => bb [2] => cc [3] => aa [4] => bb [5] => cc )