<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\7\27 0027
 * Time: 18:32
 */
$str="  '你好'@中&国";
function replace_specialChar($strParam){
    $regex = "/\/|\～|\，|\。|\！|\？|\“|\”|\【|\】|\『|\』|\：|\；|\《|\》|\’|\‘|\ |\·|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
    return preg_replace($regex,"",$strParam);
}
echo replace_specialChar($str);
