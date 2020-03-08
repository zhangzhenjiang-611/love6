<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/7
 * Time: 9:44
 */
class Person {
    private $name;
    private $age;
    private $sex;

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
    //类的外部访问私有属性成员时候自动调用  自动把私有属性名称传给$name
    public function __get($pro)
    {
        // TODO: Implement __get() method.
       // return $pro."22222222222222"."<br/>";
        return $this->$pro."<br/>";
    }
    //直接设置私有属性值得时候自动调用
    public function __set($pro, $val)
    {
        // TODO: Implement __set() method.
        //echo "{$pro} - {$val}";
        $this->$pro = $val;
    }

}

$b1 = new Person('张三','15','女');
echo $b1->name;
echo $b1->age;
echo $b1->sex;
$b1->eat();
$b1->name = "王二";
$b1->age = "22";
echo "<br/>";
$b1->eat();
//$b1->eat();
//$b2 = new Person('李四','22','妖怪');
//$b2->eat();