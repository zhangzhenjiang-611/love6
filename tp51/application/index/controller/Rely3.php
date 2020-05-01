<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/27
 * Time: 17:20
 */

namespace app\index\controller;


//use think\Request;
use think\facade\Request;

class Rely3
{
    public function index(Request $request){
        dump($request->has('id','get'));
    }

    public function edit() {
       //dump(Request::has('id','get'));
        //return 123;
        dump(Request::param('id'));
        dump(Request::param());
        dump(Request::param(false)); //原始变量 不过滤
        dump(Request::param(true));
    }

    public function read(\think\Request $request) {
        return $request->name;
    }




}