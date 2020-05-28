<?php

namespace app\admin\controller;

use think\Db;

class Home extends Base
{
    //后台首页
    public function index() {
       //$db = Db::connect('db_config2');
       //$res = $db->query("select * from hd_role");
      // dump($res);
        //exit;
        return $this->fetch();
    }


    public function soap() {
        $url = 'http://192.168.1.218:11289/KWSService.asmx?wsdl';
        return WebService($url,'Home','app\admin\controller');
    }

    //退出登录
    public function logout() {
        session(null);
        if (session('?admin.id')) {
            $this->error('退出失败');
        } else {
            $this->success('退出成功','admin/index/login');
        }
    }
}
