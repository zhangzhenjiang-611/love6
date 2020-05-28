<?php

namespace app\admin\controller;
use app\common\model\AuthGroup as AuthGroupModel;

use think\facade\Request;


class AuthGroup extends Base
{
    protected $model = null;
    public function __construct()
    {
        parent::__construct();
        $this->model = new AuthGroupModel();
    }

    //用户组列表
    public function group_list() {
        $cates = AuthGroupModel::order('id','desc')->paginate(10);
        $listData = [
            'cates'  =>  $cates
        ];
        $this->assign($listData);
        return $this->fetch();
    }

    //用户组添加
    public function add() {
        if (Request::isAjax()){
            $data = [
                'title' => input('post.title'),
                'status' => input('post.status')
            ];
            $result = $this->model->add($data);
            if ($result == 1) {
                $this->success('用户组添加成功','group_list');
            } else {
                $this->error($result);
            }


        }

        return $this->fetch();

    }

    //用户组编辑
    public function edit() {
        if (Request::isAjax()) {
            $data = [
               'id' => input('post.id'),
               'title' => input('post.title'),
               'status' => input('post.status')
            ];
            $result = $this->model->edit($data);
            if ($result == 1) {
                $this->success('用户组修改成功','group_list');
            } else {
                $this->error($result);
            }


        }
        $cateInfo = $this->model->find(input('id'));
        $listData = [
            'cate' => $cateInfo
        ];
        $this->assign($listData);
        return $this->fetch();
    }

    //用户组删除
    public function delete() {
        if (Request::isAjax()) {
            $cateInfo = $this->model->findOrFail(input('post.id'));
            $result = $cateInfo->delete();
            if ($result) {
                $this->success('用户组删除成功','group_list');
            } else {
                $this->error('用户组删除失败');
            }
        }

    }
}
