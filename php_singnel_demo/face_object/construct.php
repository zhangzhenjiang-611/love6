<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/6
 * Time: 20:54
 */
//构造方法，创建对象时候第一个自动调用的方法，用于给类的属性赋初值
class Person {
    public $name;
    public $age;
    public $sex;

     public function __construct($name,$age,$sex = "男")
     {
         $this->name = $name;
         $this->age = $age;
         $this->sex = $sex;
     }

     public function eat() {
         echo $this->name.'年龄是'.$this->age."性别是".$this->sex;
         echo "<br/>";
     }
     //对象串行化自动调用 可以设置需要串行化的对象的属性 __wakeup() 反串行化自动调用
     public function __sleep()
     {
         // TODO: Implement __sleep() method.
         echo "串行化";
         return ['name','age'];

     }
}

$b1 = new Person('张三','15','女');
//$b1->eat();
$b2 = new Person('李四','22','妖怪');
//$b2->eat();