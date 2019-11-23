<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\10\23 0023
 * Time: 17:59
 */
$arr = [
    'a' => 'aa',
    'b' => 0,
    'c' => '',
];

array_filter($arr, function ($var) {
    if ($var !== '' && $var != null) {
        echo  $var;
        echo "<br/>";
    }else{
    echo  0;
    echo "<br/>";
    }
}
);
