<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/27
 * Time: 16:58
 */

namespace app\index\controller;


use think\Controller;
use think\Request;

class Rely extends Controller
{
    public  function index() {
        return $this->request->param('name');
    }

    public function index2(Request $request) {
        return $request->param('name');
    }

}