<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\9\6 0006
 * Time: 18:59
 */
$data='2019-05-25';
if(preg_match("/^(19|20)\d\d-([1-9]|0[1-9]|1[012])-([1-9]|0[1-9]|[12][0-9]|3[01])$/", $data)){
    $datas = explode('-', $data);
    var_dump($datas);
    $year  = $datas[0];
    $month = $datas[1];
    $day   = $datas[2];
    $max_day = date('t', strtotime($year . '-' . $month));
    var_dump($max_day);//判断一个月有几天
    var_dump($day <= $max_day);
}