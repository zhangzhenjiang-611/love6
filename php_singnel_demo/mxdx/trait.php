<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/3
 * Time: 15:19
 */
/*aa
php从以前到现在一直都是单继承的语言，无法同时从两个基类中继承属性和方法，为了解决这个问题，php出了Trait这个特性
通过在类中使用use 关键字，声明要组合的Trait名称，具体的Trait的声明使用Trait关键词，Trait不能实例化
*/
trait Dog{
    public $name="dog";
    public function bark(){
        echo "This is dog";
    }
}
class Animal{
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
$cat->drive();
echo "<br/>";
$cat->eat();
echo "<br/>";
$cat->bark();
echo "<br/>";