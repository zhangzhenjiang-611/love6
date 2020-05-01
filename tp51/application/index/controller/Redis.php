<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/1
 * Time: 22:13
 */

namespace app\index\controller;


use think\facade\Cache;

class Redis
{
    public function index() {
       //Cache::set('user','xiaoli',10);
        //Cache::set('age','18',10);
        echo Cache::get('user');
        echo Cache::get('age');
    }

}