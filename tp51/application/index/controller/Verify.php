<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/29
 * Time: 14:00
 */

namespace app\index\controller;


use think\Controller;
use think\facade\Validate;

class Verify extends Controller
{
    //批量验证
    protected $batchValidate = true;

    public function index() {
        $data = [
            'name'  =>  'a999ahh',
            'price' =>  150,
            'email' =>  '123qq.com'
        ];

        $validate = new  \app\common\validate\User();

        if (!$validate->scene('edit')->batch()->check($data)) {
            dump($validate->getError());
        }
      /* $result = $this->validate([
            'name'  =>  '',
            'price' =>  530,
            'email' =>  '123@qq.com'
        ],\app\common\validate\User::class);

       if ($result !== true) {
           dump($result);
       }*/
    }

    public function fade() {
        dump(Validate::isEmail('111qq.com'));
    }

    public function check() {
       $data = [
           'user' =>  input('post.user'),
           '__token__' =>  input('post.__token__'),

       ];

       $validate = new \think\Validate();
       $validate->rule([
           'user'  =>  'require|token'
       ]);

       if (!$validate->batch()->check($data)) {
           dump($validate->getError());
       }
    }

    public function make() {
        dump(Validate::number(10));
    }

}