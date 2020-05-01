<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/21
 * Time: 15:29
 */
$str = "nddemdereeeffetcssssfggddregg";
$arr=str_split($str);
$arr=array_count_values($arr);
arsort($arr);
print_r($arr);