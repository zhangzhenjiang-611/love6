<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/2
 * Time: 8:46
 */

namespace app\index\controller;


use think\captcha\Captcha;
use think\Controller;
use think\facade\Request;

class Code extends Controller
{
    public function index() {
        $data = [
            'code' => Request::post('code')
        ];

        $res = $this->validate($data,[
            'code|验证码' => 'require|captcha'
        ]);
        dump($res);
    }

    //对象显示验证码
    public function show() {
        $config = [
            'fontSize' => 30,
            'length'   => 4,
            'useNoise' => false
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    public function check() {
        $captcha = new Captcha();
        dump($captcha->check(Request::post('code')));
    }

}