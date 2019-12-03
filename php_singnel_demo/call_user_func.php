<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\12\3 0003
 * Time: 14:48
 */
function barber($type){
    echo "you wanted a $type haircut, no problem\n";
}
call_user_func('barber','mushroom');

$str = 'asdfghjkl';  //{}取字符串的某一个字符（下标的形式）
echo "<br/>";
echo $str{0};
echo "<br/>";
echo $str{1};
echo "<br/>";
echo $str{2};
echo "<br/>";
echo $str{8};
echo "<br/>";
echo isset($str{9}) ? '存在' : '不存在';
