<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Image;
use Think\Verify;
use Org\Util\Rbac;
class LoginController extends Controller {

    /*
     *
     * 后台登录页面
     * */
    public function index(){
       // echo  session_id();die();
        $this->display();
    }

    /*
     * 登录
     * */
    public function login() {
        if(!IS_POST) {
            $this->error('你访问的页面不存在');
        }
        if(!$this->check_verify(I('code'))) {
            $this->error('验证码错误');
        }
        $username = I('username','');
        $password = I('password','','md5');
        $user = M('User')->where(array('username'=>$username))->find();
        if(!$user || $user['password'] != $password) {
            $this->error('账号或密码错误');
        }
        if($user['lock']) {
            $this->error('用户被锁定');
        }
       $data = array(
           'id' => $user['id'],
           'logintime' => time(),
           'loginip'  => get_client_ip()
       );
       $res = M('User')->save($data);
       if($res) {
           session(C('USER_AUTH_KEY'),$user['id']);
           session('username',$user['username']);
           session('logintime',date('Y-m-d H:i:s',$user['logintime']));
           session('loginip',$user['loginip']);
           //超级管理员识别
           if($user['username'] == C('RBAC_SUPERADMIN') ) {
               session(C('ADMIN_AUTH_KEY'),true);
           }
           //读取用户权限
           Rbac::saveAccessList();
           $this->redirect('Index/index');
       }


    }
    /*
     * 验证码
     * */
    public function verify() {
        $config = array(
            'fontSize' => 19, // 验证码字体大小
            'length' => 4, // 验证码位数
           'imageH' => 34
        );
         $Verify = new Verify($config);
         $Verify->entry();
    }

    /*
     * 校验验证码
     * */
    public function check_verify($code,$id = '') {
        $verify = new Verify();
        return $verify->check($code,$id);
    }
}