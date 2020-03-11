<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/8
 * Time: 16:43
 */
// __toString 直接使用echo print printf 输出一个对象引用的时候自动调用的方法 j将对象的基本信息放在__toString内部形成字符串返回
// __toString 方法内部不能有参数，必须返回字符串
//__clone() 克隆对象
class Teacher {
    public $name;
    public $age;
    public $sex;
    public function __construct($name,$age,$sex)
    {
        $this->name = $name;
        $this->age =$age;
        $this->sex = $sex;
    }
    public function say(){
        echo "我的名字是：{$this->name}，我的年龄是：{$this->age}，我的性别是:{$this->sex}";
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return "aaaaaaaaaaaaaaa";
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        echo "3333333333333";
    }

}
$b = new Teacher('张璐',24,'女');
$b->say();

 $b2 = $b;
 $b2->say();