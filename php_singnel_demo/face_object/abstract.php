<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/10
 * Time: 14:41
 */
//抽象类 抽象方法
/*
 * 1 一个方法如果没有方法体(一个方法,不使用{})，就是抽象方法
 * 2 如果是抽象方法，必须加关键字abstract
 * 3如果一个类中，有一个抽象方法，即为抽象类
 * 4 抽象类中不一定非要有抽象方法
 * 5 抽象类是一种特殊的类-可以有抽象方法
 * 6 抽象类不能实例化 不能创建对象
 * 7 看到抽象类必须写子类 ,将抽象类中的方法覆盖，加上方法体
 * 8 子类必须全部实现（覆盖）父类中的抽象类
 * 9 抽象方法的作用 ：规定子类必须有这个方法
 * */
abstract class Person {
    /*function say() {
        //有方法体
    }*/
     public $name;
     const PI = 3.14;
 abstract function eat(); //抽象方法 不含{}
 abstract function speak(); //抽象方法 不含{}
    public function say(){

    }
}

//$b = new Person();
 class student extends Person{
    public function eat(){
        echo "eat";
    }
    public  function speak(){
        echo "speak";
    }
 }

 $b= new Student();
$b->speak();