<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/11
 * Time: 16:18
 */
//定义形状的抽象类 定义此类必须实现的一个方法
abstract class Shape{
    public $name; //形状名称
    //求面积
    abstract function area();
    //求周长
    abstract function zhou();
    //图形表单界面
    abstract function view();
    //形状验证
    abstract function confirm($arr);

}