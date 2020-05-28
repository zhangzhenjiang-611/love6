<?php
namespace Nurse\Controller;
use Think\Controller;
require_once __DIR__ . '/Log.php';
class LoginController extends CommonController {
public function index(){
  $zqlist = M("zhenqu")->select();
  $zhenqu = I("get.zhenqu");
  $this->zhenqu = $zhenqu;
  $this->zqlist = $zqlist;
  $this->display();
}
public function login_do(){
	$code = I("post.code");
	$zhenqu = I("post.zhenqu");
	if($code=='admin'){
		session(array('name'=>'uname','expire'=>3600*12)); 
		session('uname','admin');
		$rel['success']=1;
		
	}else{

		session(array('name'=>'uname','expire'=>3600*12));
		session('uname',M("doctor_info")->where("doctor_code='".$code."'")->getField("doctor_name"));
		$ons = M("doctor_info")->where("doctor_code='".$code."'")->count();
		if($ons==0){
			$rel['success']=0;
			$this->ajaxReturn($rel,"JSON");
			exit(0);
		}
		$ha = M("zq_user")->where("code='".$code."'")->count();
		if($ha==0){
			$data['code'] = $code;
			$data['zhenqu'] = $zhenqu;
			$data['name'] = M("doctor_info")->where("doctor_code='".$code."'")->getField("doctor_name");
			if(M("zq_user")->add($data)){
				session(array('name'=>'nurse','expire'=>3600*12));
				session("nurse",$code);
				session(array('name'=>'zhenqu','expire'=>3600*12));
				session("zhenqu",$zhenqu);
				$rel['success'] = 1;
				$this->writeLog($code,"护士".I("post.code")."登录成功");
				\Log::write("护士".I("post.code")."登录成功");
			}else{
				$rel['success'] = 0;
				$this->writeLog($code,"护士".I("post.code")."登录失败2");
				$rel['error'] = "护士".I("post.code")."登录失败2";
				\Log::write("护士".I("post.code")."登录失败");
			}
		}else{
			$data['zhenqu'] = $zhenqu;
			M("zq_user")->where("code='".$code."'")->save($data);
			$this->writeLog($code,"护士".I("post.code")."登录成功");
			$rel['success']  =1;
		}
	}
	
	$this->ajaxReturn($rel,"JSON");
}





}