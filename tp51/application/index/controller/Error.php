<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/3
 * Time: 16:51
 */

namespace app\index\controller;


use think\Request;

class Error
{
    public function index(Request $request) {
        return '控制器不存在'.$request->controller();
    }

}