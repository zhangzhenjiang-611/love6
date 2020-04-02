<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/28
 * Time: 18:03
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
class ArticleController extends BaseController {
    /*
     * 帖子列表
     * */
    public function index () {
        $count = M('wish')->count();// 查询满足要求的总记录数 $map表示查询条件
        $Page = new Page($count,10);// 实例化分页类 传入总记录数
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询
        $list = M('wish')->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select(); // $Page->firstRow 起始条数 $Page->listRows 获取多少条
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }

    //文章删除
    public function delete()  {
        //dump(I('id','','intval'));
        $id = I('id','','intval');
        $res = M('wish')->where(array('id'=>$id))->delete();
        if($res) {
            $this->success('删除成功',U('Admin/Article/index'));
        } else{
            $this->error('删除失败');
        }

    }
}