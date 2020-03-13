<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/13
 * Time: 11:28
 */

$str = "hello";
$str[1] = "orld";
//设置值
 echo $str; //hollo
 \var_dump($str);

   $int = 100;

    echo "aaaaaaa $int aaaaaaaaaaaa"."<br>";


    $int1 = ['one'=>100,'two'=>200];
    echo "aaaaaa{$int1["one"]}aaaaaaa".$int1["two"]."aaaaaa"."<br>";

echo "aaaaaaa$int1[one]aaaaaaaaaaaa"."<br>";


class Demo{
    public $one = 300;
}

  $b = new Demo();
echo "aaaaaaa $b->one aaaaaaaaaaaa"."<br>";
echo "aaaaaaa{$b->one}aaaaaaaaaaaa"."<br>";


