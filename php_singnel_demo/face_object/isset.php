<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/7
 * Time: 10:13
 */
//魔术方法 __isset()
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
    //判断私有属性是否存在的时候自动调用
    public function __isset($pro)
    {
        // TODO: Implement __isset() method.
        //echo "$pro";
        if($pro == "age"){
            return false;
        }
        return isset($this->$pro);
    }
}

$b1 = new Person('张三','15','女');
if(isset($b1->age)){
    echo "存在";
}else{
    echo "不存在";
}

//$b2 = new Person('李四','22','妖怪');
//$b2->eat();