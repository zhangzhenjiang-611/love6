<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index()
    {
        //echo 123;
        //echo C('USERNAME');
        $user = M('User');
        dump($user->select());
    }

    public function houtai(){

    }
}