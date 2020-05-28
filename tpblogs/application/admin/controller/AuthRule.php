<?php

namespace app\admin\controller;

use app\common\model\AuthRule as AuthRuleModel;
use think\Db;
use think\facade\Request;

class AuthRule extends Base
{
    protected $model = null;
    public function __construct()
    {
        parent::__construct();
        $this->model = new AuthRuleModel();
    }
    //权限列表
    public function rule_list() {
        $cates = AuthRuleModel::order('id','desc')->paginate(10);
        $listData = [
            'cates'  =>  $cates
        ];
        $this->assign($listData);
        return $this->fetch();
    }

    //添加权限
    public function add() {
        if (Request::isAjax()){
            $plevel = Db::name('auth_rule')->where('id',input('post.pid'))->field('level')->find();
            if ($plevel) {
                $data['level'] = $plevel['level'] + 1 ;
            } else {
                $data['level'] = 0 ;
            }
            $data = [
                'title' => input('post.title'),
                'name' => input('post.name'),
                'pid' => input('post.pid')
            ];
            $result = $this->model->add($data);
            if ($result == 1) {
                $this->success('权限添加成功','rule_list');
            } else {
                $this->error($result);
            }


        }

        $auth = Db::name('auth_rule')->field('id,title')->select();
        $this->assign('auth',$auth);
        return $this->fetch();
    }
}
