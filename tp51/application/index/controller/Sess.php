<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/1
 * Time: 15:23
 */

namespace app\index\controller;


use think\facade\Cookie;
use think\facade\Session;

class Sess
{
    /**
     *
     */
    public function index() {

    }

    public function sess() {
        Session::prefix('tp');
        Session::set('username','admin');
        //Session::delete('username');
        //dump(Session::get());
        dump($_SESSION);

    }

    public function cook() {
        Cookie::prefix('tp_');

        Cookie::set('user','xiaoli',3600*2);

        Cookie::clear('tp_');

        echo Cookie::has('user');
        echo Cookie::get('user');
    }

}