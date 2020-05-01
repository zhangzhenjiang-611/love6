<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/7
 * Time: 14:32
 */
$str = 'aaaaaaaaaaaaa12356aaaaaa5aaaaa12aaaaa8a77aaa';

$reg = "/\d/";       // 1 定界符/  / ,也可以{} | |  2 原子  放在定界符中 至少一个原子
//元字符： 不能单独使用 用来扩展原子功能 / \d{5} /

// \d 表示数字  替换
echo preg_replace($reg,"#",$str);

echo "<br/>";
//分割
print_r(preg_split($reg,$str));
echo "<br/>";
//匹配
  if(preg_match($reg,$str,$arr))
  {
      echo 'aaaaa0';
      print_r($arr);
  } else{
      echo 333;
  }