<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/3
 * Time: 16:23
 */

namespace app\index\controller;


use think\Controller;

class Before extends Controller
{
    protected $beforeActionList = [
        'first',                      //访问任何一个public方法，都会首先调用first方法
        'second' => ['except' => 'one'],  //访问任何一个public方法，都会首先调用second方法，除了one方法
        'three'  => ['only' => 'one,two']  //只有访问one,two方法，才会首先调用three方法
    ];
    protected $flag = false;
    protected function first() {
        echo 'first';
    }
    protected function second() {
        echo 'second';
    }
    protected function three() {
        echo 'three';
    }
    public function index() {
        if (!$this->flag) {
            $this->success('注册成功','./');
        } else {
            $this->error('注册失败');
        }
        return 1234;
    }

    public function one() {
        return 'one';
    }
    public function two() {
        return 'two';
    }
   //空方法拦截
    public function _empty($name) {
        return $name.'空方法';
    }

}