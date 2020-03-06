<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/5
 * Time: 17:27
 */
$arr = [1,3,5,100=>2,6,8,9,7,4];
print_r($arr);
echo "<br/>";
sort($arr); //键名不保留
print_r($arr);
echo "<br/>";

echo "<br/>";
rsort($arr);
print_r($arr);
echo "<br/>";