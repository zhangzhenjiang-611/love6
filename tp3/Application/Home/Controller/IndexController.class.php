<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index()
    {
        //echo C('USERNAME');
      //  $user = M('User');
       // dump($user->select());
       // pp($_SERVER);
        //var_dump(defined('__PUBLIC'));  //检测常量是否存在  不是常量
        //echo THINK_VERSION;
        //echo U('show',array('uid'=>1,'username'=>'aa'),'',true);die();
        //$wish = M('Wish') ->select();
        //var_dump($wish);die();
        $this->assign('wish',M('Wish') ->select())->display();
    }

    public function handle() {
       // var_dump(IS_POST);
       // pp(I('post.'));
        //echo I('username');
        if(!IS_POST) {
            $this->error('页面不存在');
        } else{
            $data =array(
                'username' =>I('post.username',''),
                'content' =>I('post.content',''),
                'time' => time()
            );
          // $id = M('Wish')->data($data)->add();
           $id = M('Wish')->add($data);
           if($id) {
               $this->success('发布成功','index');
           } else{
               $this->error('发布失败');
           }
        }
    }

    public function qiantai() {
    }
}