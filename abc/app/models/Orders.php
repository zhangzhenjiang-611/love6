<?php
use Phalcon\Mvc\Model;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\22 0022
 * Time: 11:30
 */
class Orders extends Model
{
    //我们可以建立一些类的公共变量,变量对应表的字段
    public $id;
    public $name;
    public $phone;
    public $passwd;

}