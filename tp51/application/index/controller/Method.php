<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/27
 * Time: 22:36
 */

namespace app\index\controller;


use think\Controller;
use think\facade\Request;

class Method extends Controller
{
    public function index() {
      return  $this->fetch();
    }

    public function handle() {
        dump(Request::method());
        dump(Request::header());
    }

    //伪静态
    public function url(){
        dump(Request::ext());
    }

    //响应
    public function res(){
        $data = 'hello';
        return response($data,'201');
    }

    public function rec(){
        //return redirect('http://www.baidu.com');

    }

}