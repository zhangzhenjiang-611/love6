<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    /*
     * 首页视图
     * */
    public function index(){
        $wish = M('Wish')->select();
        $this->assign('wish',$wish);
        $this->display();
    }
    /*
     * 异步发布处理
     * */
    public  function handle() {
        if(!IS_AJAX) {
            $this->error('你访问的页面不存在!');
        }
        //echo C('DEFAULT_FILTER');
       // dump(I('post.'));
       // $data['username'] = I('post.username','');
        //$data['content'] = I('post.content','');
        $data = array(
            'username' => I('username',''),
            'content' => I('content',''),
            'time' => time()
        );
        $phiz = F('phiz','','./Data/');

        if($id = M('wish')->add($data)) {
            $data['id'] = $id;
            $data['content'] = replace_phiz($data['content']);
            $data['time'] = date('Y-m-d H:i');
            $data['status'] =1;
            $this->ajaxReturn($data,'json');
        } else{
            $this->ajaxReturn(array('status'=>0),'json');
        }

    }
}