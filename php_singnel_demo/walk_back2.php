<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/1/7
 * Time: 18:51
 */
//回调函数
function fun($one=1,$two=2,$three=3){
    echo "{$one}------$two---------{$three}";
}
//fun();

call_user_func_array("fun",[111,222,333,444]); //数组里传几个元素，函数接收几个