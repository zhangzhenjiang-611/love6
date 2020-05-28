<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/2
 * Time: 16:38
 */

namespace app\index\controller;
use app\index\model\Profile;
use app\index\model\User as UserModel;
use app\index\model\Profile as ProfileModel;


class Grade
{
    public function index() {
        //$user = UserModel::get(14);
       //echo  $user->profile->hobby;
       //关联模型修改
       /* $user->profile->save([
            'hobby' => '打人'
        ]);*/

       //关联新增
       /* $user->profile()->save([
            'hobby' => '打人'
        ]);*/
        /*$profile = ProfileModel::get(1);
        return $profile->user;*/
        //反向查询
        //$user = UserModel::hasWhere('profile',['id' => 1])->find();

        //闭包
        /*$user = UserModel::hasWhere('profile',function ($query){
            $query->where('profile.id',1);

        })->find();
        return $user;*/




       // $user = UserModel::get(14);
        //return  $user->profile;

        //反向

        //$user = UserModel::has('profile','>=','1')->select();

        //$user = UserModel::hasWhere('profile',['status'=>1])->select();
        //return $user;

        //关联新增 批量
       /* $user = UserModel::get(14);
        $user->profile()->saveAll([
            ['hobby' => '测试新增'],
            ['hobby' => '测试新增']
        ]);*/

       //删除主表数据 附表记录也随着删除
        $user = UserModel::get(14,'profile');
        $user->together('profile')->delete();








    }

    //关联预加载
    public function before() {
        $list = UserModel::withJoin('profile')->all([3,4,7]);
        foreach ($list as $user) {
            dump($user->profile);
        }
    }

    //关联统计
    public function count() {
        $list = UserModel::withCount('profile')->all([3,4,7]);
        foreach ($list as $user) {
          echo $user->profile_count."<br>";
        }

        $list = UserModel::withSum('profile','status')->all([3,4,7]);
        foreach ($list as $user) {
            echo $user->profile_sum."<br>";
        }
    }

    public function dis() {
        $list = UserModel::with('profile')->select();
        dump($list->hidden(['password','profile.status']));
    }

    //多对多
    public function  many() {
        //$user = UserModel::get(4);
        //return $user->roles;

    }

}