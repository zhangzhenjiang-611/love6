<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\26 0026
 * Time: 13:41
 */
/*
 *1.json_decode()

json_decode
(PHP 5 >= 5.2.0, PECL json >= 1.2.0)

json_decode — 对 JSON 格式的字符串进行编码

说明
mixed json_decode ( string $json [, bool $assoc ] )
接受一个 JSON 格式的字符串并且把它转换为 PHP 变量

参数

json
待解码的 json string 格式的字符串。

assoc
当该参数为 TRUE 时，将返回 array 而非 object 。


返回值
Returns an object or if the optional assoc parameter is TRUE, an associative array is instead returned.
 * */

$json ='{"a":1,"b":2,"c":3,"d":4,"e":5}';
echo "<pre>";
print_r(json_decode($json));
//对象
/*stdClass Object
(
    [a] => 1
    [b] => 2
    [c] => 3
    [d] => 4
    [e] => 5
)*/
echo "<hr/>";
print_r(json_decode($json,true));
/*  数组
 * Array
(
    [a] => 1
    [b] => 2
    [c] => 3
    [d] => 4
    [e] => 5
)*/


echo "<hr/>";
$data= '[
{"Name":"a1","Number":"123","Contno":"000","QQNo":""},
{"Name":"a1","Number":"123","Contno":"000","QQNo":""},
{"Name":"a1","Number":"123","Contno":"000","QQNo":""}
]';
print_r(json_decode($data));
/*  对象
 * Array
(
    [0] => stdClass Object
        (
            [Name] => a1
            [Number] => 123
            [Contno] => 000
            [QQNo] =>
        )

    [1] => stdClass Object
        (
            [Name] => a1
            [Number] => 123
            [Contno] => 000
            [QQNo] =>
        )

    [2] => stdClass Object
        (
            [Name] => a1
            [Number] => 123
            [Contno] => 000
            [QQNo] =>
        )

)*/

echo "<hr/>";
print_r(json_decode($data,true));
/*Array
(
    [0] => Array
    (
        [Name] => a1
        [Number] => 123
            [Contno] => 000
            [QQNo] =>
        )

    [1] => Array
(
    [Name] => a1
    [Number] => 123
            [Contno] => 000
            [QQNo] =>
        )

    [2] => Array
(
    [Name] => a1
    [Number] => 123
            [Contno] => 000
            [QQNo] =>
        )

)*/
echo "<hr/>";
$json1 ='{
    "from":"zh",
 "to":"en",
 "trans_result":[
  {
      "src":"u4f60u597d",
   "dst":"Hello"
  }
 ]
}';

var_dump(json_decode($json1));
var_dump(json_decode($json1,true));

