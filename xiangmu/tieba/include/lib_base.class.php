<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/20
 * Time: 16:47
 */
//递归转义数组
function zhuanyi($arr) {
    if(is_array($arr)){
        foreach($arr as $k=>$v) {
            if(is_array($v)) {
                $arr[$k] = zhuanyi($v);
            } else{
                $arr[$k] = addslashes($v);
            }
        }
    }
    return $arr;
}