<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/6
 * Time: 17:13
 */
class BoyFriend {
    //类的成员属性前面一定要有修饰词
   var $name = "xiaojiang";
   public $age = 18;
   private $num = 1+3;
  // public $num1 = $num2;
   function eat($a) {
       return $a;

   }
}

 $obj = new BoyFriend();
var_dump($obj);
echo $obj->age;
echo $obj->eat('吃饭');
//phpinfo();