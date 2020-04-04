<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/3
 * Time: 15:25
 */
trait Dog{
    public $name="dog";
    public function drive(){
        echo "This is dog drive";
    }
    public function eat(){
        echo "This is dog eat";
    }
}

class Animal{
    public function drive(){
        echo "This is animal drive";
    }
    public function eat(){
        echo "This is animal eat";
    }
}

class Cat extends Animal{
    use Dog;
    public function drive(){
        echo "This is cat drive";
    }
}
$cat = new Cat();
$cat->drive(); //调用自己内部方法
echo "<br>";
$cat->eat();  //优先调用trait里的方法 继承和use trait同时存在的情况下
/*a
Trait中的方法会覆盖 基类中的同名方法，而本类会覆盖Trait中同名方法
注意点：当trait定义了属性后，类就不能定义同样名称的属性，否则会产生 fatal error，除非是设置成相同可见度、相同默认值。不过在php7之前，即使这样设置，还是会产生E_STRICT 的提醒

a*/