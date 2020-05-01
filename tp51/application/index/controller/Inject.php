<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/28
 * Time: 15:54
 */

namespace app\index\controller;


//use app\common\Test;
use app\facade\Test;
use app\index\model\One;
use think\facade\Hook;

class Inject
{
    //依赖注入
    protected $one;

    public function  __construct(One $one)
    {
        $this->one = $one;
    }

    public function index(){
        return $this->one->name;
    }

    public function  test(){
        //$test = new Test();
       // return $test->hello();
        //return \app\facade\Test::hello();
        return Test::hello();
    }

    //自定义钩子
    public function bhv(){
        Hook::listen('eat','吃饭');
    }

    //路由中间件
    public function read($id){
        return 'read'.$id;
    }

}