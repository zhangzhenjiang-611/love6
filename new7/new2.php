<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/25
 * Time: 11:08
 */
//返回值得声明
    function returnString(string $str) :string {
        //php5不支持
        return 123;
    }
     var_dump(returnString('abc'));