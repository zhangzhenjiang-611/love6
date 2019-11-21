<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\8\23 0023
 * Time: 16:16
 */

//整型的0，换成整型的其他值试试
/*
 * 1，整型的0为假，其他整型值全为真
2, 浮点的0.0，布尔值的假。小数点后只要有一个非零的数值即为真。
3，空字符串为假，只要里面有一个空格都算真。
4，字符串的0，也将其看作是假。其他的都为真
5，空数组也将其视为假，只要里面有一个值，就为真。
6，空也为假
7, 未声明成功的资源也为假*/
$bool = 0;
if($bool){
    echo '美女美女我爱你';
}else{
    echo '凤姐凤姐爱死我，执行假区间咯';
}

$bool = 0.0;
if($bool){
    echo '美女美女我爱你';
}else{
    echo '凤姐凤姐爱死我，执行假区间咯1';
}

//空字符串，中间没有空格哟。实验完加个空格试试
$str = ' ';
if($str){
    echo '美女美女我爱你';
}else{
    echo '凤姐凤姐爱死我，执行假区间咯2';
}

$str = '0';
if($str){
    echo '美女美女我爱你';
}else{
    echo '凤姐凤姐爱死我，执行假区间咯';
}

$arr = array();
if($arr){
    echo '美女美女我爱你';
}else{
    echo '凤姐凤姐爱死我，执行假区间咯';
}

$bool = null;
if($bool){
    echo '美女美女我爱你';
}else{
    echo '凤姐凤姐爱死我，执行假区间咯';
}

$res = @fopen('adasfasfasfdsa.txt','r');
if($res){
    echo '美女美女我爱你';
}else{
    echo '凤姐凤姐爱死我，执行假区间咯3';
}

$arr=123;
print_r($arr);
echo "<br/>";
print_r((array)$arr);
echo "<br/>";
$num=1;
$num='1';
$num='1s';
if(is_numeric($num)){
    echo "是数字";
}else{
    echo "不是数字";
}