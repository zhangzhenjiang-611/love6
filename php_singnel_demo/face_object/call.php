<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/8
 * Time: 18:24
 */
//__call()
class Teacher {
    public $name;
    public $age;
    public $sex;
    public $m = ['aa','bb','cc','dd'];
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
    //对象调用类中不存在的方法时候自动调用此方法 参数1 调用的不存在的方法名 参数2调用这个不存在的方法的参数array()
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
       /* echo "{$name}()方法不存在";
        echo "<br/>";
        print_r($arguments);*/
       if(in_array($name,$this->m)){
           print_r($arguments);
       }else{
           echo "{$name}方法不存在";
       }
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        //echo "3333333333333";
    }

}
$b = new Teacher('张璐',24,'女');
//$b->say();

$b2 = $b;
//$b2->say();
$b->aa('张璐','25','girl');
$b->bb('张璐','15','girl');