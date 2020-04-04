<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/3
 * Time: 14:03
 */

namespace app\index\controller;


class HelloWorld
{
    public function index() {
        //http://www.tp5.cc/index/hello_world 使用下划线_方式访问驼峰式命名的控制器
        return 'hello world';
    }

}