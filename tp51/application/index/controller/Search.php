<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/4
 * Time: 10:05
 */

namespace app\index\controller;


use think\Controller;
use think\Db;

class Search extends Controller
{
    public function index() {
        $count = Db::name('user')->count(); //SELECT COUNT(*) AS tp_count FROM `hd_user`
        $count = Db::name('user')->count('num'); //2 null 不计算
        $count = Db::name('user')->count('lock'); //9
        $count = Db::name('user')->max('num'); //6
        $count = Db::name('user')->max('password'); //0
        $count = Db::name('user')->avg('num'); //4
        $count = Db::name('user')->sum('num'); //8
        $sql = Db::name('user')->fetchSql()->sum('num'); //8
        $sql = Db::name('user')->fetchSql()->buildSql(); //8
        //闭包
       /* Db::name('user')->where('id','in',function ($query) {
            $query->name('role')->field('pid')->where('sex','男');
        })->select();*/
        dump(Db::getLastSql());
    }

    public function field() {
        $res = Db::name('user')->field('SUM(num)')->select();
        $res = Db::name('user')->field(['id','LEFT(password,9)'=>'pwd'])->select();
        dump($res);
    }
    public function limit() {
        $rs = Db::name('user')->limit(2,5)->select();
        $rs = Db::name('user')->page(1,5)->select();
        $rs = Db::name('user')->page(2,5)->select();
        dump($rs);
    }

}