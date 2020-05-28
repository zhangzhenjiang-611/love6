<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
public function index(){
  $room = I("get.room");
  $this->room = $room;
  $room_name = M("tbl_room_list")->where("room_id=".$room)->getField("room_name");
  if(strpos($room_name,"专家")!==false){
		$this->expert = 1; 
  }else{
		$this->expert = 0;  
  }
  //获取诊区信息
  $zhenqu = M("zhenqu")->where("find_in_set(".$room.",room)")->getField('dept');
  $this->zq_id = $this->getZhenQuByRoom($room);
  $zhenqu_ary = explode(",",$zhenqu);
  $dept_ary = array();
  for($i=0;$i<count($zhenqu_ary);$i++){
      $dept_code = $zhenqu_ary[$i];
	  $dept_name = M("dept_info")->where("dept_code='".$dept_code."'")->getField("dept_name");
	  $dept_ary[$i]['dept_code'] = $dept_code;
	  $dept_ary[$i]['dept_name'] = $dept_name;
  }
 $dept = $dept_ary;

  $dept_cookie = cookie("dept");
  $dept_cookie_ary = explode(",",$dept_cookie);
  for($i=0;$i<count($dept);$i++){
  	  if(in_array($dept[$i]['dept_code'],$dept_cookie_ary)){
	  	  $dept[$i]['cookies'] = 1;
	  }else{
	  	  $dept[$i]['cookies'] = 0;
	  }
  }
  $this->dept = $dept;
  $this->login_txt =  M("tbl_op")->where("op_name='login_txt'")->getField("op_val2");
  $this->display();
}
public function login_do(){
	$doctor_code = I("post.doctor_code");
	$login_type = I("post.login_type");
	$dept = I("post.dept");
	$dept = substr($dept,0,strlen($dept)-1);  
	$room = I("post.room");
	$condition['doctor_code'] = $doctor_code;
	$row = M("doctor_info")->where($condition)->select();
	$expert = 0;
	//$exp = M("register_assign")->where("doctor_code='".$doctor_code."'")->count();
	if($login_type=="expert"){
		$data['expert'] = 1;
		$expert = 1;
	}if($login_type=="normal"){
		$data['expert'] = 0;
	}
	if($row!=""){
		$data['room_id'] = $room;
		$data['uid'] = $doctor_code;
		$data['dept'] = $dept;
		$data['ip'] = get_client_ip();
		$data['login_type'] = "医生";
		$hlin = M("client_uid")->where("uid='".$doctor_code."'")->count();
		if($hlin>0){	
			M("client_uid")->where("uid='".$doctor_code."'")->save($data);
		}else{
			M("client_uid")->add($data);
		}
		//$rel['doctor_name'] = $row[0]['doctor_name'];
		$rel['doctor_code'] = $doctor_code;
		$rel['success'] = 1;
		$rel['expert'] = $expert;
		$rel['room'] = $room;
		
		//$dept = substr($dept,0,strlen($dept)-1); 
		$rel['dept'] = $dept;
		cookie('dept',$dept,36000000);
	}else{
		$rel['success'] = 0;
		
	}
	$this->ajaxReturn($rel,"JSON");
}
public function getZhenQuByRoom($room){
	$zhenqu=M("zhenqu")->where("find_in_set('".$room."',room)")->getField("id");
	return $zhenqu;
}
}