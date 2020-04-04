<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/3
 * Time: 20:59
 */

namespace app\index\controller;


use think\Controller;
use think\Db;

class Curd extends Controller
{
    public function index() {
        return 1111;
    }

    //增加数据
    public function insert() {
        $request = \Request::instance();
        $data = [
            'username' => '玉玲5',
            'password' => md5('123456'),
            'logintime' => time(),
            //'xxx'=>'未知字段抛出异常',  //数据表字段不存在:[xxx]
            'loginip' => $request->ip()

        ];

        //$res = Db::name('user')->insert($data); //返回受影响的行数1
        //$res = Db::name('user')->data($data)->insert(); //返回受影响的行数1
        $res = Db::name('user')->insertGetId($data); //返回当前id
        dump($res);
    }

    //增加数据
    public function insertAll() {
        $request = \Request::instance();
        $data = [
            ['username' => '玉玲6',
            'password' => md5('123456'),
            'logintime' => time(),
            'loginip' => $request->ip()
            ],
            [
                'username' => '玉玲7',
                'password' => md5('123456'),
                'logintime' => time(),
                'loginip' => $request->ip()
            ]

        ];

        //$res = Db::name('user')->insert($data); //返回受影响的行数1
        //$res = Db::name('user')->data($data)->insert(); //返回受影响的行数1
        $res = Db::name('user')->insertAll($data); //返回当前id
        dump($res);
    }
    //修改数据
    public function update() {
        $request = \Request::instance();
        $data = [
            'username' => '亚萍'
            ];
         $res = Db::name('user')->inc('num',4)->where('id','7')->update($data);
         dump($res);
    }
    public function update2() {
        $data = [
            'username' => '亚萍14',
            'password' => Db::raw('UPPER(password)'),
            'num' => Db::raw('num + 1'),
            'id' =>14
        ];
        //$res = Db::name('user')->update($data);
       // dump($res);
        //改某一个字段
        Db::name('user')->where('id',14)->setField('username','李玉玲');
    }


    public function delete() {
        //Db::name('user')->delete('12');
        Db::name('user')->delete([11,13]);
    }

}