<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/19 0019
 * Time: 19:27
 */
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
    function index(){
        $this->display();
    }
    function check(){
        $m = M('adminuser');
        $username = $_POST['username'];
        $password = $_POST['password'];
        $re = $m->where("username='%s' and password='%s'",array($username,$password))->find();
        if($re){
            setcookie("userid",$re['id'],0,'/');
            setcookie('username',$re['username'],0,'/');
            header("location:".U("Admin/Index/index"));
        }else{
            $this->error("登录失败",U("Admin/Login/index"),3);
        }
    }
}