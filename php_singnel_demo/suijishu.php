<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\11 0011
 * Time: 9:55
 */
function nonceStr() {
    static $seed = array(0,1,2,3,4,5,6,7,8,9);
    $str = '';
    for($i=0;$i<8;$i++) {
        $rand = rand(0,count($seed)-1);
        $temp = $seed[$rand];
        $str .= $temp;
        unset($seed[$rand]);
        $seed = array_values($seed);
    }
    return $str;
}
echo nonceStr();