<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
        $this->display();
    }
    /*
     * 退出登录
     * */
    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('Admin/Login/index');
    }
}