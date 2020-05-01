<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/1
 * Time: 20:41
 */

namespace app\index\controller;


use think\Controller;
use think\Db;

class Page extends Controller
{
    public function index() {
        $list = Db::name('user')->paginate(10);
        $this->assign('list',$list);
        return $this->fetch();
    }

}