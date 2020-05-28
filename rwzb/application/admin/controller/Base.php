<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\facade\Request;

class Base extends Controller
{
    //初始化
    public function initialize()
    {
        //parent::initialize(); // TODO: Change the autogenerated stub
        if (!session('?user.id')) {
            $this->redirect('admin/login/index');

        }

    }


  //三级联动
    public function getArea() {
        $res = Db::name('area')->where('parent_code=" "')->select();
        return $res;
    }
    //获取城市
    public function getCity() {
        if (Request::isAjax()) {
            //dump(input('post.pro_id'));
            $parent_code = input('post.pro_id');
            $region = Db::name('area')->where(['parent_code' => $parent_code])->select();
            $opt = '<option>--请选择--</option>';
            foreach($region as $key=>$val){
                $opt .= "<option value='{$val['code']}'>{$val['name']}</option>";
            }
            echo json_encode($opt);
            die;

        }
    }

    //获取市县
    public function getCounty() {
        if (Request::isAjax()) {
            $parent_code = input('post.pro_id');

            $region = Db::name('area')->where(['parent_code' => $parent_code])->select();

            $opt = '<option>--请选择--</option>';
            foreach($region as $key=>$val){
                $opt .= "<option value='{$val['code']}'>{$val['name']}</option>";
            }
            echo json_encode($opt);

        }
    }

}