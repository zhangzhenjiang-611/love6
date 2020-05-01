<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/7
 * Time: 9:19
 */

namespace app\index\controller;


use think\Controller;
use think\facade\Request;
use think\facade\Session;

class See extends Controller
{
    public function index() {
        return $this->fetch();
    }

    public function dis () {
        $name = 'zzj';
        return $this->display($name);
    }

    public function vali() {
        echo Request::token();
        echo "<br>";
        echo Session::get('__token__');
       return $this->fetch();
    }

}