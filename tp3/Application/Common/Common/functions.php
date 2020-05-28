<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/27
 * Time: 10:59
 */
function pp($arr) {
    dump($arr,1,'<pre>',0);
}
function say(){
    echo 456;
}


function object_array($obj) {
    $arr = array();
    if (is_object($obj)) {
       foreach ($obj as $key=>$value) {
           if($key == 'OutXml') {
               $arr[$key] = simplexml_load_string($value);
           }
       }
    }
    $xml_arr = array();
    foreach ($arr['OutXml'] as $k=>$v) {
        $xml_arr[$k] = (string)$v;

    }
    return $xml_arr;

    }

