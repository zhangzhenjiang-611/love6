<?php
namespace Manage\Controller;
use Think\Controller;
use Org\Util\Rbac;
class LoginController extends Controller {
	
	public function index(){
		$this->display();
	}

	/**
     * @desc 登陆验证
     * @param
     * @author 鲁翠霞
     * @final 2020-04-21
     */

	public function check(){

	    //session_destroy();
	   // exit;
	    //print_r($_SESSION);
	   //Rbac::AccessDecision();
	   //var_dump(Rbac::AccessDecision());
	   //exit;
	   /* session_destroy();
	    dump($_SESSION);
	    exit;*/
	    //print_r($_SESSION);
		//1是必传参数
		/*$rules = array(
			array('username',1),
			array('password',1),
		);
		$in = validParam($rules);//入参处理*/
		$in['username'] = 'test123';
        $in['password'] = 'test123';

     /*   $map['username'] = $in['username'];
        $map['status'] = array('eq', 1);
        $res = Rbac::authenticate($map,'user');*/

      /*  $user_id = $res['id'];
        $role_user = M();
        $role = $role_user->Table(C("RBAC_USER_TABLE"))->alias("user")->where("user_id=" . $user_id)->join(C("RBAC_ROLE_TABLE") . " as role ON role.id=user.role_id")->field("id,name")->find();*/
     /* $login = Rbac::AccessDecision();
      echo "<pre>";
      print_r($login);
        exit;*/
		$username = $in['username'];
		$password = md5($in['password']);
		$ob = M("user");
		$re = $ob->where("username='{$username}' and password='{$password}' and status='1'")->find();

		if($re){
			
				//记录登陆时间、ip
				if($_SERVER["REMOTE_ADDR"]=='::1'){
					$ip = '127.0.0.1';
				}else{
					$ip = $_SERVER["REMOTE_ADDR"];
				}
				$up = array(
				'login_time' => date('Y-m-d H:i:s'),
				'login_ip' => $ip
				);

				M('user')->where("id ='".$re['id']."'")->save($up);
                session(C('USER_AUTH_KEY'),$re['id']);
				session('uname',$username);
				session('uid',$re['id']);


				
				if($re['username']==C('RBAC_SUPERADMIN')){
					session(C('ADMIN_AUTH_KEY'),true);
				}
				
				//RBAC
				//$t1=new \Org\Util\Rbac();

				Rbac::saveAccessList();
                $rel['code']='0';
				$rel['msg']='登陆成功';
				//print_r($_SESSION);
		}else{
			$rel['code']='1';
			$rel['msg']='用户名密码错误';
		}
		//dump($rel);exit;
		$this->ajaxReturn($rel,'JSON');
	}


	/**
     * @desc 退出登陆
     * @param
     * @author 鲁翠霞
     * @final 2020-04-21
     */
	public function loginOut(){
        session_start(); //开启Session功能
		session_destroy();
		$rel['code']='0';
		$rel['msg']='退出登陆成功';
		
		$this->ajaxReturn($rel,'JSON');
        //$this->redirect($url);//重定向到新的模块
		
    }
    

	
	/**
     * @desc 修改密码
     * @param
     * @author 鲁翠霞
     * @final 2020-04-21
     */
	public function reg(){
		$rules = array(
			//array('username',1),
			array('password',1),
		);
		$in = validParam($rules);//入参处理
		session_start(); //开启Session功能
		if($_SESSION['uname']){
			$arr=array('password'=>md5($in['password']),);
			$return = M('user')->where("username='{$_SESSION['uname']}'")->save($arr);
		}	
		$rel=array(
			'code'=>'0',
			'msg'=>'修改成功!',
		);
		$this->ajaxReturn($rel,'JSON');	
	}
	



	
    

}