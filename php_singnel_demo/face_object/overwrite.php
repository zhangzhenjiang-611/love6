<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/8
 * Time: 8:37
 */
//重载（覆盖）
class People{
    protected $name;
    protected $age = 16;
    protected $sex;
    public function __construct($name,$age,$sex)
    {
        $this->name = $name;
        $this->age  = $age;
        $this->sex  = $sex;

    }
    public function eat(){

        echo "我的名字是aaaaaaaaba：{$this->name},我的年龄是：{$this->age},我的性别是：{$this->sex}";
    }
    public function play(){

    }
}

class Tearher extends People{
    public $salary;
    public function __construct($name,$age,$sex,$money)
    {
        parent::__construct($name,$age,$sex);
        $this->salary = $money;
    }

    public function teach(){
        echo $this->age;
        // echo $this->name;

    }
    public function eat(){
        echo "<br/>";
        parent::eat();

        echo "我的工资是：{$this->salary}";
    }
}

$t1 = new People('张璐',24,'女');
$t1->eat(); //子类可以继承父类中的方法，非私有，此番方法在父类内部可以访问自己的私有属性
//受保护的可以在子类内部使用，不可以在外部使用
$t2 = new Tearher('双儿',17,'女','2000');
$t2->eat();
echo "<hr/>";
define('APP',12);
echo APP;
