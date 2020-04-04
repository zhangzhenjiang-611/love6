<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/3
 * Time: 17:01
 */

namespace app\index\controller;


use app\index\model\User;
use think\Controller;
use think\Db;
use think\db\exception\DataNotFoundException;

class Data extends Controller
{
    public function index() {
        return 222;
    }

    public function noModel() {
        $data = Db::table('hd_user')->select();  //表名带前缀
        $data = Db::name('user')->select();      //表名不带前缀
        dump($data);   //返回数组
    }

    //使用模型
    public function getModel() {
        $data = User::select();
        dump($data);   //返回数组
    }

    //数据查询
    public function  one() {
        //查询一条
        //$data = Db::table('hd_user')->find();
        //$sql = Db::getLastSql();
        //$data = Db::table('hd_user')->where('id',15)->find();


        try {
            $data = Db::table('hd_user')->where('id',5)->findOrFail();//没有抛出异常
        } catch (DataNotFoundException $e) {
            return '数据不存在';
        }
        $data = Db::table('hd_user')->where('id',15)->findOrEmpty(); //没有返回空数组
        dump($data);
    }

    public function all() {
        $data = Db::table('hd_user')->select();
        //指定字段
        $data = Db::table('hd_user')->field('password')->select();
        $data = Db::table('hd_user')->value('password'); //指定字段 单个
        $data = Db::table('hd_user')->column('password','id'); //重新索引数组
        dump($data);
    }
    //助手函数
    public function hand() {
        $data = \db('user')->select();
        dump($data);
    }
    //链式查询
    public function lian() {
        $data = Db::name('user'); //对象
        $data = Db::name('user')->where('id','5'); //对象
        $data = Db::name('user')->where('id','5')->order('id','desc'); //对象
        $data = Db::name('user')->where('id','5')->order('id','desc')->find(); //数组
        dump($data);
    }

    //节省内存
    public function jie() {
        $user = Db::name('user'); //对象
        $data1 = $user->where('id','5')->order('id','desc')->find();
        $data2 = $user->select(); //只有一条
        $data3 = $user->removeOption('where')->select(); //把where条件移除
        dump($data3);
        dump(Db::getLastSql()); //SELECT * FROM `hd_user` ORDER BY `id` DESC  order条件还在
    }
}