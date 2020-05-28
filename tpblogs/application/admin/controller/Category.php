<?php

namespace app\admin\controller;

use think\facade\Request;

use app\common\model\Category as CategoryModel;

class Category extends Base
{
    protected $model = null;
    public function __construct()
    {
        parent::__construct();
        $this->model = new CategoryModel();
    }

    //栏目列表
    public function list() {
        $cates = $this->model->order('sort','desc')->paginate(10);
        $listData = [
            'cates'  =>  $cates
        ];
        $this->assign($listData);
        return $this->fetch();
    }

    //栏目添加
    public function add() {
        if (Request::isAjax()) {
            $data = [
                'catename' => input('post.catename'),
                'sort'     => input('post.sort')
            ];
            $result = $this->model->add($data);

            if ($result == 1) {
                $this->success('栏目添加成功','admin/category/list');
            } else {
                $this->error($result);
            }

        }
        return $this->fetch();
    }

    //栏目排序
    public function sort() {
        if (Request::isAjax()) {
            $data = [
                'id'  => input('post.id'),
                'sort'  => input('post.sort'),
            ];
        }

        $result = $this->model->sort($data);

        if ($result == 1) {
            $this->success('栏目排序成功','admin/category/list');
        } else {
            $this->error($result);
        }
    }

    //栏目编辑
    public function edit() {
       /* dump(input('id'));
        exit;*/
       //Request::input('id');
        if (Request::isAjax()) {
            $data = [
                'id'        =>  input('post.id'),
                'catename'  =>  input('post.catename')
            ];

            $result = $this->model->edit($data);
            if ($result == 1) {
                $this->success('栏目编辑成功','admin/category/list');
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

    //栏目删除
    public function delete() {
        if (Request::isAjax()) {
            $cateInfo = $this->model->findOrFail(input('post.id'));
            $result = $cateInfo->delete();
            if ($result) {
                $this->success('栏目删除成功','admin/category/list');
            } else {
                $this->error('栏目删除失败');
            }
        }

    }
}
