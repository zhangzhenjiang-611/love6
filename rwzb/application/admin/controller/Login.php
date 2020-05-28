<?php

namespace app\admin\controller;
use app\common\model\User;
use think\Controller;
use think\facade\Request;

class Login extends Controller
{

    //登录
    public function index() {
        if (Request::isAjax()) {
            $data = [
                'username' => input('post.username'),
                'password' => md5(input('post.password')),
            ];

            $user = new User();
            $result = $user->login($data);
            if ($result == 1) {
                $this->success('登录成功','Index/index');
            } else {
                $this->error($result);
            }

        }

        return $this->fetch();

    }

    //注册
    public function register() {
        if (Request::isAjax()) {
            $data = [
                'username'  =>  input('post.username'),
                'password'  =>  md5(input('post.password')),
                'repassword'   =>  md5(input('post.repassword')),
                'email'     =>  input('post.email')
            ];
            $admin = new User();
            $result = $admin->register($data);
            if ($result == 1) {
                $this->success('注册成功','admin/login/index');
            } else {
                $this->error($result);
            }
        }
        return $this->fetch();
    }

    //忘记密码
    public function forget() {
        dump(input('post.email'));
    }
}
