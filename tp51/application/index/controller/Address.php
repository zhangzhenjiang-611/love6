<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/27
 * Time: 11:59
 */

namespace app\index\controller;


use think\Collection;

class Address extends Collection
{
    public function index() {
        return 'address/index';
    }

    //带参数
    public function detail($id) {
        return 'detail目前调用的id'.$id;
    }

}