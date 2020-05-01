<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/29
 * Time: 11:07
 */

namespace app\index\controller;


use think\facade\Log;

class Record
{
    public function index() {
        Log::record('测试日志');
    }

}