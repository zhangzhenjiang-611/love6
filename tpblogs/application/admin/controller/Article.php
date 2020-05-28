<?php

namespace app\admin\controller;
use app\common\model\Article as ArticleModel;
use think\facade\Request;

class Article extends Base
{
    protected $model = null;
    public function __construct()
    {
        parent::__construct();
        $this->model = new ArticleModel();
    }
    //文章列表
    public function list() {
        $articles = $this->model->list();
        return $this->fetch();
    }

    //文章添加
    public function add() {
        if (Request::isAjax()) {
            $data = [
                'title'   =>  input('post.title'),
                'tags'    =>  input('post.tags'),
                'is_top'  =>  input('post.is_top',0),
                'cate_id'  =>  input('post.cate_id'),
                'desc'    =>  input('post.desc'),
                'content' =>  input('post.content')

            ];
            $result = $this->model->add($data);
            if ($result == 1) {
                $this->success('文章添加成功','admin/article/list');
            } else {
                $this->error($result);
            }
        }
        $cate = model('Category')->select();
        $cateData = [
            'cates'  => $cate
        ];
        $this->assign($cateData);
        return $this->fetch();
    }
}
