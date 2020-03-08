<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/8
 * Time: 8:08
 */
class People{
    private $name;
    protected $age = 16;
    private $sex;
    public function __construct($name,$age,$sex)
    {
        $this->name = $name;
        $this->age  = $age;
        $this->sex  = $sex;

    }
    public function eat(){

        echo "我的名字是：{$this->name},我的年龄是：{$this->age},我的性别是：{$this->sex}";
    }
    public function play(){

    }
}

  class Tearher extends People{
    public $salary;
    public function teach(){
        echo $this->age;
       // echo $this->name;

    }
  }

  $t1 = new Tearher('张璐',24,'女');
   $t1->eat(); //子类可以继承父类中的方法，非私有，此番方法在父类内部可以访问自己的私有属性
//受保护的可以在子类内部使用，不可以在外部使用
 $t2 = new Tearher('双儿',17,'女');
  echo $t2->teach();
