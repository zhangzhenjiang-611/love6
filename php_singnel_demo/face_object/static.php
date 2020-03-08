<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/8
 * Time: 15:34
 */
//静态
class People{
    private $name;
    private $age;
    public static $sex = "女孩子";
    const Pi = 3.14;
    public function __construct($name,$age)
    {
        $this->name = $name;
        $this->age = $age;
    }
    public function eat(){
        echo "{$this->name} -- {$this->age} -- ".self::$sex;
        echo "<br/>";
        echo self::Pi;
    }
}
 People::$sex = "玲玲"; //赋值
 $b = new People('倩倩',13);
 $b->eat();