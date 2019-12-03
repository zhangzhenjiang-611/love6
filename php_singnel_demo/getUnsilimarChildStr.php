<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\12\2 0002
 * Time: 17:13
 */
//获取字符串的最长不重复子串
$string = "abcdaefaedkqtlmtx";
$len = strlen($string);
$resArr =[];
$tmp = [];
$i = 0;
while ($i < $len){

    $char = $string{$i};


    if(!array_key_exists($char, $tmp)){
        $tmp[$char]= $i;
        $i++;
        if($i !== $len) continue;
    }
    //从重复值下个开始
    $i = $tmp[$char]+1;
    if( count($tmp) > count($resArr) ){
        $resArr = $tmp;
    }
    $tmp = [];
}

//print_r($resArr);
$resArr = array_flip($resArr);
