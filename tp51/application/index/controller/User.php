<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/4
 * Time: 11:05
 */

namespace app\index\controller;
use app\index\model\User as UserModel;

use think\Controller;
use think\Db;

class User extends Controller
{
    public function index() {
       $res = UserModel::select();
       echo "<pre>";
       print_r($res);
    }

    public function delete() {
        $res = UserModel::destroy(14); //模型里设置loginip为主键，此处无法删除
        dump($res);
    }

    public function table() {
        $res = UserModel::select(); //模型里设置role为表，此处查询role表里的数据
        dump($res);
    }

    //模型添加
    public function insert() {
        $user = new UserModel();
       // print_r($user);
       // dump($user);
        $user->username  = '张芳芳'.mt_rand(1000,5000);
        $user->password  = md5('111111');
        $user->logintime = time();
        $user->loginip   = '127.0.0.1';
        $res = $user->save();
        dump($res);
    }

    //模型添加
    public function insert1() {
        $user = new UserModel();
        /*$res = $user->save([
            'username'  => '张芳芳15',
            'password'  => md5('111111'),
            'logintime' => time(),
            'loginip'   => '127.0.0.1'
        ]);*/
        $res = $user->saveAll([
            [
                'username'  => '张芳芳16',
                'password'  => md5('111111'),
                'logintime' => time(),
                'loginip'   => '127.0.0.1'
            ],
            [
                'username'  => '李芳15',
                'password'  => md5('111111'),
                'logintime' => time(),
                'loginip'   => '127.0.0.1'
            ]
        ]);
        dump($res); //返回新增的数据
    }

    //模型删除
    public function del() {
        $user = UserModel::get(5);
        //print_r($user->delete());
        //print_r(UserModel::destroy(9));
       // UserModel::where('id','>',18)->delete();
        UserModel::destroy(function ($query) {
            $query->where('id',18);
        });

    }
    //模型修改数据
    public function update() {
        //$user = UserModel::get(14);
        //$user->username = '随身听';

        //$user = UserModel::where('id',17)->find();
        //$user->username = 'dvd';


        $user = UserModel::get(14);
        //$user->num   =  Db::raw('num + 1');
        $user->num   = ['inc',2];
        $user->username   = 'aaa';
        print_r($user->save());
    }

    public function pass() {
        $user = new UserModel();
        echo $user->getPwd();
    }

    public function where() {
        $user = UserModel::whereIn('id',[14,16,17])->select();
        print_r($user);
    }

    //调用获取器
    public function getAttr() {
        $user = UserModel::select();
        //dump($user);
        $user = UserModel::get(14);
        dump ($user->nothing);


        //获取原始值
        dump ($user->getData());
        return $user->getData('status');
    }

    //动态获取器
    public function attr() {
        $result = UserModel::withAttr('username',function ($value){
            return strtoupper($value);
        })->select();
        dump($result);
    }

    //调用模型搜索器
    public function search() {
        $result = UserModel::withSearch(['email','create_at'],[
            'email'  => 3477,
            'create_at' => ['2018-06-14','2019-06-14']
        ])->limit(3)->select();
        dump($result);
    }
    public function emp(){
        //判断结果集是否为空
        $res = UserModel::where('id',99)->select();
        if($res->isEmpty()) {
            echo  333;
        }
        //隐藏字段
        $rs = UserModel::select();
        //dump($rs->hidden(['password']));
        //显示某个字段
        //dump($rs->visible(['password']));

        dump($rs->append(['nothing'])->withAttr('email',function ($value) {
            return strtoupper($value);
        })
        );

    }

    public function fill() {
        $rs = UserModel::select();
       $new_res = $rs->filter(function ($data) {
            return $data['id'] > 15;
        });
        dump($new_res);
    }

    //类型转换
    public  function convert() {
        $user = UserModel::get(17);
        var_dump($user->num);
        //会调用获取器
        var_dump($user->status);
        var_dump($user->create_at);
        var_dump($user->email);
    }

    //封装模型查询范围

    public function queryScope() {
        //$res = UserModel::scope('sex')->select();
        //$res = UserModel::sex()->select();

        $res = UserModel::emailLike('477')->num(12)->select();
        dump($res);
    }

    //模板输出
    public function view() {
        $user = UserModel::get(27);
        $this->assign('user',$user);
        return $this->fetch();
    }

    //json数据
    public function json() {
        $data = [
            'username'  => '张芳芳16',
            'password'  => md5('111111'),
            'logintime' => time(),
            'loginip'   => '127.0.0.1',
            'list'  =>     [
                'username'  => '张芳芳17',
                'password'  => md5('111111'),
                'logintime' => time(),
                'loginip'   => '127.0.0.1'
            ]
        ];

        $user = new UserModel();
        $user->save($data);

    }


    //软删除
    public function softDelete() {
        $user = Db::name('user')->where('id',27)->useSoftDelete('delete_time',date('Y-m-d H:i:s'))->delete();
    }

    //模型软删除查询
    public function msoftDelete() {
        //模型自动屏蔽被软删除的数据 数据库出巡不会屏蔽
        $user = UserModel::where('id','gt',26)->select();
        //SELECT * FROM `hd_user` WHERE ( `id` > 26 ) AND `hd_user`.`delete_time` IS NULL
         dump($user);
         $user1 = Db::name('user')->where('id','gt',26)->select();
         //SELECT * FROM `hd_user` WHERE `id` > 26
         dump($user1);
    }
    //模型软删除
    public function softDelete1() {
        $user = UserModel::get(28);
        $user->delete();  //模型软删除
    }
    //还原软删除数据
    public function back() {
        //查询出所有数据 包括软删除的
        //$user0 = UserModel::withTrashed()->select();
       // dump($user0);
        //查询出所有软删除的数据
        //$user = UserModel::onlyTrashed()->select();
       // dump($user);
        //查询出一条软删除的数据
        $user = UserModel::onlyTrashed()->find();
        //dump($user);
        //还原
        $user->restore();

    }

}