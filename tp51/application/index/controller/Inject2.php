<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/28
 * Time: 16:47
 */

namespace app\index\controller;


class Inject2
{
    public function index(){
        bind('one','app\index\model\One');
        return app('one')->name;
    }

}