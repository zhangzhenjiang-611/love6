<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/12
 * Time: 14:42
 */
//echo __FILE__;
class  Demo {
    public $name;
    public $name1;
    public $name2;
    public function eat(){
        echo __CLASS__;
        echo __METHOD__;
        echo "<br/>";
    }
    function hello(){
        echo __METHOD__;
    }
}
 $b = new Demo();
$b->eat();
$b->hello();
var_dump(get_class_methods('Demo'));
var_dump(get_declared_interfaces());