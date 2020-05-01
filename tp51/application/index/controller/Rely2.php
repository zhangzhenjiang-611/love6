<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/27
 * Time: 17:05
 */

namespace app\index\controller;


use think\Request;

class Rely2
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index() {
        return $this->request->param('name');

    }

}