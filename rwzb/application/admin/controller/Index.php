<?php
namespace app\admin\controller;

class Index extends Base
{
    public function index()
    {
      return $this->fetch();
    }

    //退出登录
    public function logout() {
        session(null);
        $this->redirect('login/index');
    }
}
