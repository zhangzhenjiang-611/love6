<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/19
 * Time: 13:18
 */
function sum($n) {
    if($n > 1) {
       // echo $n."<br/>";
        //return sum($n - 1) + $n;
        $tmp = sum($n - 1) + $n;
        echo $n."<br>";
        return $tmp;
    } else{
        echo $n."<br/>";
        return 1;
    }
}
echo sum(10);