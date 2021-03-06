<?php
namespace Home\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
require_once __DIR__ . '/Log.php';
class IndexController extends Controller {
public function index(){
	  
	$doctor_code = I("get.doctor_code");
	$login_type = I("get.login_type");
	$room = I("get.room");
	$dept = I("get.dept");
    $this->assign("doctor_code",$doctor_code);
    $this->assign("room",$room);
    $this->assign("dept",$dept);
	$this->expert = I("get.login_type");
    $doctor_name = M()->Table("doctor_info")->where("doctor_code=".$doctor_code)->getField("doctor_name");
    $this->assign("doctor_name",$doctor_name);
    $jz_normal = M("client_uid")->where("uid='".$doctor_code."' and room_id='".$room."'")->getField("jz_normal");
	$this->jz_normal = $jz_normal;
   $expert = I("get.login_type");
   $this->login_type = $login_type;
   $this->dept_all = $dept;
  /// echo $doctor_code;
  
   $dnList = $this->FreshData($dept,$expert,$doctor_code,$jz_normal);
   //print_r($dnList);
   $this->dnList = $dnList;
   $jpz = M("tbl_op")->where("op_name='jpz'")->getField("op_val");
	$this->jpz = $jpz;
   $this->display();
}
public function del_room(){
	$condition['uid']  = I("post.doctor_code");
	$condition['room_id'] = I("post.room");
	if(M("client_uid")->where($condition)->delete()){
		\Log::write("退出成功");
		$rel['success'] = 1;
	}else{
		\Log::write("退出失败","error");
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}


public function setGuoHao(){
	$reg_id = I("post.reg_id");
	$dept_code = I("post.dept_code");
	$data['status'] = 3;
	$jz_normal = M("client_uid")->where("uid='".I("post.doctor_code")."' and dept='".I("post.dept_code_all")."'")->getField("jz_normal");
	if(M("register_assign")->where("reg_id='".$reg_id."' and dept_code='".$dept_code."'")->save($data)){
		$rel['success'] = 1;
	
			$rel['data'] = $this->FreshData(I("post.dept_code_all"),I("post.login_type"),I("post.doctor_code"),$jz_normal);
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}
public function setQiHao(){
	$pat_code = I("post.pat_code");
	$dept_code = I("post.dept_code");
	$data['status'] = 4;
	$jz_normal = M("client_uid")->where("uid='".I("post.doctor_code")."' and dept='".I("post.dept_code_all")."'")->getField("jz_normal");
	if(M("register_assign")->where("pat_code='".$pat_code."' and dept_code='".$dept_code."'")->save($data)){
		$rel['success'] = 1;
		$rel['data'] =  $this->FreshData(I("post.dept_code_all"),I("post.login_type"),I("post.doctor_code"),$jz_normal);
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}
public function setGuiDui(){
	$reg_id = I("post.reg_id");
	$dept_code = I("post.dept_code");
	$data['status'] = 0;
	$jz_normal = M("client_uid")->where("uid='".I("post.doctor_code")."' and dept='".I("post.dept_code_all")."'")->getField("jz_normal");
	if(M("register_assign")->where("reg_id='".$reg_id."' and dept_code='".$dept_code."'")->save($data)){
		$rel['success'] = 1;
	
			$rel['data'] = $this->FreshData(I("post.dept_code_all"),I("post.login_type"),I("post.doctor_code"),$jz_normal);
		
		//$rel['data'] = $this->FreshData(I("post.dept_code_all"));
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}
public function shunhu(){
	$dept_code = I("post.dept_code");
	$doctor_code = I("doctor_code");
	$data['status'] = 1;
	$data['call_time'] = date("Y-m-d H:i:s");
	$cinfo = M("client_uid")->where("uid='".$doctor_code."'")->field("room_id,expert,jz_normal")->select(); 
	\Log::write("顺呼SQL_01  - ".M("client_uid")->where("uid='".$doctor_code."'")->field("room_id,expert,jz_normal")->select(false));
	$room_id = $cinfo[0]['room_id'];
	$room_info = M("tbl_room_list")->where("room_id='".$room_id."'")->field("room_name,real_id")->select();
	$room_name = $room_info[0]['room_name'];
	$real_id = $room_info[0]['real_id'];
	$room = M("tbl_room_list")->where("room_id=".$room_id)->getField("room_name");
	$expert = $cinfo[0]['expert'];
	$jz_normal = $cinfo[0]['jz_normal'];
	$data['room'] =  $cinfo[0]['room_id'];
	$data['allot_doctor_code'] = $doctor_code;
	$data['allot_doctor_name'] = M("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_name");
	$sid="";
	
	$zq_list = M("zhenqu")->select();
	for($i=0;$i<count($zq_list);$i++){
		if(strpos($zq_list[$i]['room'],$room_id)!==false){
			$sid = $zq_list[$i]['BigScreenID'];
			break;
		}
	}
	//echo $sid;
	/*
	$ary_s01 = array("1","2","3","4","5","6","7","8","9","10","11","12");
	$ary_s02 = array("13","14","15","16","17","18","19","20","21","22","23","24");
	$ary_s03 = array("30","31","32","33","34","35","36","37","38","39","40","41");
	$ary_s04 = array("72","73","74","75","76","77","78","79","80","81","82","83","84");
	$ary_s10 = array("85","86","87","88","89","110","111");//外科
	$ary_s11 = array("102","103","104","105","106");
	$ary_s12 = array("109","108","107");//儿科
	$ary_s13 = array("55","56","57","58","59","60");//康复科
	$ary_s14 = array("96","97","98","99","100","101");//骨科
	$ary_s15 = array("42","43","44","45","46","47","48");//眼科
	$ary_s16 = array("49","50","51","52","53","54");//耳鼻喉科
	$ary_s17 = array("67","68","69","70","71");//除痛门诊
	$ary_s18 = array("112","113","114","115","116","117","118","119","120","121","122","123");//口腔门诊
	$ary_s20 = array("199");
	if(in_array($room_id,$ary_s01)){
		$sid = "s01";
	}
	else if(in_array($room_id,$ary_s02)){
		$sid = "s02";
	}
	else if(in_array($room_id,$ary_s03)){
		$sid = "s03";
	}
	else if(in_array($room_id,$ary_s04)){
		$sid = "s04";
	}
	else if(in_array($room_id,$ary_s10)){
		$sid = "s10";
	}
	else if(in_array($room_id,$ary_s11)){
		$sid = "s11";
	}
	else if(in_array($room_id,$ary_s12)){
		$sid = "s12";
	}
	else if(in_array($room_id,$ary_s13)){
		$sid = "s13";
	}
	else if(in_array($room_id,$ary_s14)){
		$sid = "s14";//骨科
	}
	else if(in_array($room_id,$ary_s15)){
		$sid = "s15";//眼科
	}
	else if(in_array($room_id,$ary_s16)){
		$sid = "s16";//耳鼻喉科
	}
	else if(in_array($room_id,$ary_s17)){
		$sid = "s17";//除痛门诊
	}
	else if(in_array($room_id,$ary_s18)){
		$sid = "s18";//口腔门诊
	}
	else if(in_array($room_id,$ary_s20)){
		$sid = "s20";
	}
	*/
	/*
	if($room_id<4){
		$sid = "s01";
	}else if($room_id>3&&$room_id<14){
		$sid = "s02";
	}else if($room_id>13&&$room_id<23){
		$sid = "s03";
	}else if($room_id>23&&$room_id<34){
		$sid = "s04";
	}*/
	if($expert==1){
		if($cinfo[0]['jz_normal']==1){
			$where = "and trim(doctor_code)=''";
		}else{
			$where=" and doctor_code='".$doctor_code."'";
		}
		
	}else{
		$where = "and trim(doctor_code)=''";
	}
	$pat_one = M("register_assign")->where("status=0 and (jz_time='0000-00-00 00:00:00' or jz_time is null) and withdraw_flag='0' and dept_code='".$dept_code."' ".$where)->order("status desc,weight asc")->limit(1)->select();
	\Log::write("顺呼SQL_02  - ".M("register_assign")->where("status=0 and dept_code='".$dept_code."' ".$where)->order("weight asc")->limit(1)->select(false));
	if(count($pat_one)>0){
		if(M("register_assign")->where("pat_code='".$pat_one[0]['pat_code']."' and dept_code='".$dept_code."' ".$where)->save($data)){
			$da['pat_code'] = $pat_one[0]['pat_code'];
			$da['dept_code'] = $pat_one[0]['dept_code'];
			$da['room'] = $real_id;
			M("pat_now")->add($da);
			$rel['success'] = 1;
			//自动过号部分开始
			$pat_two = M("register_assign")->where("status =1  and (jz_time='0000-00-00 00:00:00' or jz_time is null) and withdraw_flag='0' and dept_code='".$dept_code."' ".$where)->order("status desc,call_time desc")->limit(1,3)->select();
			for($n=0;$n<count($pat_two);$n++){
				if($pat_two[$n]['is_jz']==0){
					$data_upsql['status']=3;
					M("register_assign")->where("pat_code='".$pat_two[$n]['pat_code']."' and dept_code='".$dept_code."' ")->save($data_upsql);
				}
			}
			/***自动过号结束***/
			$rel['data'] = $this->FreshData(I("post.dept_code_all"),$expert,$doctor_code,$jz_normal);
			
			if(strpos($sid,"|")!==false){
				$sid_ary = explode("|",$sid);
			
			for($x=0;$x<count($sid_ary);$x++){
				$this->sendVoice("请".$pat_one[0]['reg_seq_no']."号患者".$pat_one[0]['pat_name'].",到第".$real_id."诊室就诊",$sid_ary[$x]);
			}
			}else{
			$this->sendVoice("请".$pat_one[0]['reg_seq_no']."号患者".$pat_one[0]['pat_name'].",到第".$real_id."诊室就诊",$sid);
			}
				
			
			
		}else{
			$rel['success'] = 0;
		}
	}else{
		$rel['success'] = 0;
	}
	
	
	$this->ajaxReturn($rel,"JSON");
}

public function xuanhu(){
	$doctor_code = I("post.doctor_code");
	$dept_code = I("post.dept_code");
	$pat_code = I("post.pat_code");
	$cinfo = M("client_uid")->where("uid='".$doctor_code."'")->field("room_id,expert,jz_normal")->select(); 
	$room_id = $cinfo[0]['room_id']; 
	$room_info = M("tbl_room_list")->where("room_id='".$cinfo[0]['room_id']."'")->field("room_name,real_id")->select();
	$room_name = $room_info[0]['room_name'];
	$real_id = $room_info[0]['real_id'];
	$data['status'] = 1;
	$data['call_time'] = date("Y-m-d H:i:s");
	$data['room'] = M("client_uid")->where("uid='".$doctor_code."' and dept='".$dept_code."'")->getField("room_id");
	$data['allot_doctor_code'] = $doctor_code;
	$data['allot_doctor_name'] = M("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_name");
	$sid="";
	$zq_list = M("zhenqu")->select();
	for($i=0;$i<count($zq_list);$i++){
		if(strpos($zq_list[$i]['room'],$room_id)!==false){
			$sid = $zq_list[$i]['BigScreenID'];
			break;
		}
	}
	/*
	$ary_s01 = array("1","2","3","4","5","6","7","8","9","10","11","12");
	$ary_s02 = array("13","14","15","16","17","18","19","20","21","22","23","24");
	$ary_s03 = array("30","31","32","33","34","35","36","37","38","39","40","41");
	$ary_s04 = array("72","73","74","75","76","77","78","79","80","81","82","83","84");
	$ary_s10 = array("85","86","87","88","89","110","111");
	$ary_s11 = array("102","103","104","105","106");
	$ary_s12 = array("109","108","107");
	$ary_s13 = array("55","56","57","58","59","60");//康复科
	$ary_s14 = array("96","97","98","99","100","101");//骨科
	$ary_s15 = array("42","43","44","45","46","47","48");//眼科
	$ary_s16 = array("49","50","51","52","53","54");//耳鼻喉科
	$ary_s17 = array("67","68","69","70","71");//除痛门诊
	$ary_s18 = array("112","113","114","115","116","117","118","119","120","121","122","123");//口腔门诊
	
	if(in_array($room_id,$ary_s01)){
		$sid = "s01";
	}
	else if(in_array($room_id,$ary_s02)){
		$sid = "s02";
	}
	else if(in_array($room_id,$ary_s03)){
		$sid = "s03";
	}
	else if(in_array($room_id,$ary_s04)){
		$sid = "s04";
	}
	else if(in_array($room_id,$ary_s10)){
		$sid = "s10";
	}
	else if(in_array($room_id,$ary_s11)){
		$sid = "s11";
	}
	else if(in_array($room_id,$ary_s12)){
		$sid = "s12";
	}
	else if(in_array($room_id,$ary_s13)){
		$sid = "s13";
	}
	else if(in_array($room_id,$ary_s14)){
		$sid = "s14";
	}
		else if(in_array($room_id,$ary_s15)){
		$sid = "s15";
	}
	else if(in_array($room_id,$ary_s16)){
		$sid = "s16";
	}
	else if(in_array($room_id,$ary_s17)){
		$sid = "s17";
	}
	else if(in_array($room_id,$ary_s18)){
		$sid = "s18";
	}
	*/
	
	$pat_one = M("register_assign")->where("pat_code='".$pat_code."' and dept_code='".$dept_code."'")->order("weight asc")->limit(1)->select();
	if(M("register_assign")->where("pat_code='".$pat_one[0]['pat_code']."' and dept_code='".$dept_code."'")->save($data)){
		$da['pat_code'] = $pat_one[0]['pat_code'];
		$da['dept_code'] = $pat_one[0]['dept_code'];
		$da['room'] = $real_id;
		M("pat_now")->add($da);
		$rel['success'] = 1;
		$rel['data'] = $this->FreshData(I("post.dept_code_all"),$cinfo[0]['expert'],$doctor_code,$cinfo[0]['jz_normal']);
	}else{
		$rel['success'] = 0;
	}
	\Log::write("选呼SQL  - ".M("register_assign")->where("pat_code='".$pat_code."' and dept_code='".$dept_code."'")->order("weight asc")->limit(1)->select(false));
	if(strpos($sid,"|")!==false){
		$sid_ary = explode("|",$sid);
	
		for($x=0;$x<count($sid_ary);$x++){
			$this->sendVoice("请".$pat_one[0]['reg_seq_no']."号患者".$pat_one[0]['pat_name'].",到第".$real_id."诊室就诊",$sid_ary[$x]);
		}
	}else{
		$this->sendVoice("请".$pat_one[0]['reg_seq_no']."号患者".$pat_one[0]['pat_name'].",到第".$real_id."诊室就诊",$sid);
	}
	$this->ajaxReturn($rel,"JSON");
}
public function chonghu(){
	$doctor_code = I("post.doctor_code");
	$cinfo = M("client_uid")->where("uid='".$doctor_code."'")->field("room_id,expert,jz_normal")->select(); 
	$room_id = $cinfo[0]['room_id'];
	$room_info = M("tbl_room_list")->where("room_id=".$room_id)->field("room_name,real_id")->select();

	$room = $room_info[0]['room_name'];
	$real_id = $room_info[0]['real_id'];
	$expert = $cinfo[0]['expert'];
	$dept_code = I("post.dept_code");
	$dept_name = M("dept_info")->where("dept_code='".$dept_code."'")->getField("dept_name");
	
	
	
	if($expert==1){
		if($cinfo[0]['jz_normal']==1){
			$where = "and trim(doctor_code)=''";
		}else{
			$where=" and doctor_code='".$doctor_code."'";
		}
		
	}else{
		$where = "and trim(doctor_code)=''";
	}
	
	$pat_now = M("register_assign")->where("dept_code='".$dept_code."' and status=1 and room='".$cinfo[0]['room_id']."' ".$where)->order("call_time desc")->limit(1)->select();
	//echo  M("register_assign")->where("dept_code='".$dept_code."' and status=1 and room='".$cinfo[0]['room_id']."' ".$where)->order("call_time desc")->limit(1)->select(false);
	$da['pat_code'] = $pat_now[0]['pat_code'];
	$da['dept_code'] = $pat_now[0]['dept_code'];
	$da['room'] = $real_id;
	M("pat_now")->add($da);
	
	$zq_list = M("zhenqu")->select();
	for($i=0;$i<count($zq_list);$i++){
		if(strpos($zq_list[$i]['room'],$pat_now[0]['room'])!==false){
			$sid = $zq_list[$i]['BigScreenID'];
			break;
		}
	}
	
	/*
	$ary_s01 = array("1","2","3","4","5","6","7","8","9","10","11","12");
	$ary_s02 = array("13","14","15","16","17","18","19","20","21","22","23","24");
	$ary_s03 = array("30","31","32","33","34","35","36","37","38","39","40","41");
	$ary_s04 = array("72","73","74","75","76","77","78","79","80","81","82","83","84");
	$ary_s10 = array("85","86","87","88","89","110","111");
	$ary_s11 = array("102","103","104","105","106");
	$ary_s12 = array("109","108","107");
	$ary_s13 = array("55","56","57","58","59","60");//康复科
	$ary_s14 = array("96","97","98","99","100","101");//骨科
	$ary_s15 = array("42","43","44","45","46","47","48");//眼科
	$ary_s16 = array("49","50","51","52","53","54");//耳鼻喉科
	$ary_s17 = array("67","68","69","70","71");//除痛门诊
	$ary_s18 = array("112","113","114","115","116","117","118","119","120","121","122","123");//口腔门诊
	
	if(in_array($pat_now[0]['room'],$ary_s01)){
	$sid = "s01";
	}
	else if(in_array($pat_now[0]['room'],$ary_s02)){
		$sid = "s02"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s03)){
		$sid = "s03"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s04)){
		$sid = "s04"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s10)){
		$sid = "s10"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s11)){
		$sid = "s11"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s12)){
		$sid = "s12"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s13)){
		$sid = "s13"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s14)){
		$sid = "s14"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s15)){
		$sid = "s15"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s16)){
		$sid = "s16"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s17)){
		$sid = "s17"; 
	}
	else if(in_array($pat_now[0]['room'],$ary_s18)){
		$sid = "s18"; 
	}
	*/
	/*if($pat_now[0]['room']<4){
		$sid = "s01";
	}else if($pat_now[0]['room']>3&&$room_id<14){
		$sid = "s02";
	}else if($pat_now[0]['room']>13&&$room_id<23){
		$sid = "s03";
	}else if($pat_now[0]['room']>23&&$room_id<34){
		$sid = "s04";
	}*/
	if(count($pat_now)>0){
		if($pat_now[0]['room']==1010){
				$this->sendVoice("请".$pat_now[0]['reg_id']."号患者".$pat_now[0]['pat_name'].",到会诊中心,一,就诊",$sid);
		}else if($pat_now[0]['room']==1020){
			$this->sendVoice("请".$pat_now[0]['reg_id']."号患者".$pat_now[0]['pat_name'].",到会诊中心2就诊",$sid);
		}else{
			
			if(strpos($sid,"|")!==false){
					$sid_ary = explode("|",$sid);
				
				for($x=0;$x<count($sid_ary);$x++){
					$this->sendVoice("请".$pat_now[0]['reg_seq_no']."号患者".$pat_now[0]['pat_name'].",到第".$real_id."诊室就诊",$sid_ary[$x]);
				}
				}else{
				$this->sendVoice("请".$pat_now[0]['reg_seq_no']."号患者".$pat_now[0]['pat_name'].",到第".$real_id."诊室就诊",$sid);
				}
			
			//$this->sendVoice("请".$pat_now[0]['reg_seq_no']."号患者".$pat_now[0]['pat_name'].",到".$room."诊室就诊",$sid);
		}
		//$this->sendVoice("请".$pat_now[0]['reg_id']."号患者".$pat_now[0]['pat_name'].",到".$pat_now[0]['room']."诊室就诊",$sid);
	}
	$this->ajaxReturn($pat_now[0],"JSON");
}

//接诊普诊患者
public function jz_normal(){
	$doctor_code = I("post.doctor_code");
	$room = I("post.room");
	$jz_type = I("post.jz_type");
	//$has = M("register_assign")->where("doctor_code='".$doctor_code."' and status in(0,3)")->count();
	$cc = M("client_uid")->where("uid='".$doctor_code."' and room_id='".$room."'")->field("dept,expert,jz_normal")->select();
		if($cc[0]['jz_normal']==1){
			$data['jz_normal'] = 0;
		}else{
			$data['jz_normal'] = 1;
		}
		
		M("client_uid")->where("uid='".$doctor_code."' and room_id='".$room."'")->save($data);
		
	
	$cinfo = M("client_uid")->where("uid='".$doctor_code."' and room_id='".$room."'")->field("dept,expert,jz_normal")->select();
	$rel['pat_num'] = $has;
	$rel['data'] = $this->FreshData($cinfo[0]['dept'],$cinfo[0]['expert'],$doctor_code,$cinfo[0]['jz_normal']);
	$rel['success'] = 1;
	$rel['jz_normal'] = $cinfo[0]['jz_normal'];
	$this->ajaxReturn($rel,"JSON");


}
public function getData(){
	$doctor_code = I("post.doctor_code");
	$room = I("post.room");
	$uinfo = M("client_uid")->where("uid='".$doctor_code."' and room_id='".$room."'")->field("expert,dept,jz_normal")->select();
	$expert = $uinfo[0]['expert'];
	$jz_normal = $uinfo[0]['jz_normal'];
	$dept = $uinfo[0]['dept'];
	
	$rel['data'] = $this->FreshData($dept,$expert,$doctor_code,$jz_normal);
	$rel['success'] = 1;
	$this->ajaxReturn($rel,"JSON");
}
/**
*刷新数据
**/
public function FreshData($dept,$expert,$doctor_code,$jz_normal){
	//$dept = substr($dept,0,strlen($dept)-1);  
	$dept_ary = explode(",",$dept);
	$dnList = array();
	$ary_can = array();
	$z = 0;
	$where= "";
	for($i=0;$i<count($dept_ary);$i++){
		$dept_code = $dept_ary[$i];						
		
		$CanCaller = self::getCanCaller($dept_code,$expert,$doctor_code,$jz_normal);
		$ttow2= $CanCaller['wait'];
		$ttow = $CanCaller['now'];
		
		$zn = M()->query("select * from dept_info where dept_code='".$dept_code."'");
		$dnList[$z]['dept_name'] = $zn[0]['dept_name'];
		$dnList[$z]['dept_code'] = $zn[0]['dept_code'];
		$dnList[$z]['pat_now'] = $ttow;
		$dnList[$z]['pat_wait'] = $ttow2;
		if($expert==1&&$jz_normal==0){
			$dnList[$z]['jz_type'] = "专家";
		}else{
			$dnList[$z]['jz_type'] = "普通";
		}
		//普诊数目
		$dnList[$z]['normal_num'] = M("register_assign")->where("dept_code='".$zn[0]['dept_code']."' and trim(doctor_code)='' and status=0 and withdraw_flag='0' and ((jz_time='0000-00-00 00:00:00' or jz_time is null))")->count();
		if($ttow2=='n'){
			$dnList[$z]['pat_wait_num']=0;
		}else{
			$dnList[$z]['pat_wait_num'] = count($ttow2);
		}
		
		
		//过号列表
		if($expert==1&&$jz_normal==0){
			$where=" and doctor_code='".$doctor_code."'";
		}else{
			$where = " and trim(doctor_code)=''";
		}
	
		
		$guohao_list = M()->query("select * from register_assign where dept_code='".$dept_code."' ".$where."  and status=3  and withdraw_flag='0' and ((jz_time='0000-00-00 00:00:00' or jz_time is null)) order by weight asc");
		if(count($guohao_list)==0){
			$guohao_list = "n";
		}
		$dnList[$z]['pat_guohao'] = $guohao_list;
		
		$z++;

	}
	return $dnList;
}
 public function getCanCaller($dept_code,$expert,$doctor_code,$jz_normal){
		//$jz_normal = M("client_uid")->where("uid='".$doctor_code."' and expert=".$expert)->getField("jz_normal");
		if($expert==1&&$jz_normal==0){
				$where=" and doctor_code='".$doctor_code."'"; 
		}else{
			$where = " and trim(doctor_code)=''";
		}
		//echo "select * from register_assign where status=0 and dept_code='".$dept_code."' ".$where." order by weight asc";
		$sql = "select * from register_assign where status=1 and withdraw_flag='0' and dept_code='".$dept_code."' ".$where." and allot_doctor_code='".$doctor_code."' order by call_time desc limit 1";
		$sql2 = "select * from register_assign where status in(0,5) and ((jz_time='0000-00-00 00:00:00' or jz_time is null)) and withdraw_flag='0' and dept_code='".$dept_code."' ".$where." order by status desc,weight asc";
		$list = M()->query($sql);
		$list2 = M()->query($sql2);
		
		$list3 = array();
		if(count($list)==0){
			$list="n";
		}
		if(count($list2)==0){
			$list2="n";
		}
			
		$list3['now'] = $list;
		$list3['wait'] = $list2;
		return $list3;
		
   }
public function sendVoice($speech,$sid){	 
		 $ip = M("zhenqu")->where("BigScreenID='".$sid."'")->getField("VoiceIP");
		 /*
		 if(strpos($sid,"s01")!==false){
			$socketaddr = "183.3.10.238";
	      }
		   //神经科
		   else if(strpos($sid,"s02")!==false){ 
				  $socketaddr = "183.3.8.144"; 
		 	 }
		   else if(strpos($sid,"s03")!==false){ 
				  $socketaddr = "183.3.7.239"; 
			  }
		  //产科
		  else if(strpos($sid,"s04")!==false){  
				  $socketaddr = "183.3.7.233"; //183.3.7.149
			  }
		  else if(strpos($sid,"s10")!==false){ 
				  $socketaddr = "183.1.10.239"; 
			  }
		  //妇科
		  else if(strpos($sid,"s11")!==false){ 
				  $socketaddr = "183.3.10.245"; 
			  }
		  //儿科
	  	 else if(strpos($sid,"s12")!==false){ 
				  $socketaddr = "183.1.9.240"; 
			  }
		   //康复科
	  	 //else if(strpos($sid,"s13")!==false){ 
		  //$socketaddr = "183.3.10.149"; 
		  //$socketport = "7777"; 
		  //}
		  //骨科
		   else if(strpos($sid,"s14")!==false){ 
				  $socketaddr = "183.1.9.147"; 
			  }
		    //眼科
		   else if(strpos($sid,"s15")!==false){ 
				  $socketaddr = "183.3.9.237"; 
			  }
		    //耳鼻喉科
		   else if(strpos($sid,"s16")!==false){ 
				  $socketaddr = "183.3.9.238"; 
			  }
		  else if(strpos($sid,"s17")!==false){ 
				  $socketaddr = "183.3.9.236"; //除痛门诊
		  	  }
		  else if(strpos($sid,"s18")!==false){ 
				  $socketaddr = "183.3.7.253"; //口腔门诊//183.3.7.36
				    
		  	  }
		*/	  
		$socketaddr = $ip;
		$socketport = "7777";
		$str=iconv("utf-8","gbk",$speech);
		$str = $str.",".$str;
		$socket = \Socket::singleton();
		$socket->connect($socketaddr,$socketport);
		$send_buffer = pack('N', strlen($str)).$str;
		$sockresult = $socket->sendrequest ($send_buffer);

        $socket->disconnect (); //关闭链接		
}

public function soap_client(){
	$this->display();
}
public function login(){
	$soap = new \SoapClient('http://172.168.0.241/demo/soap/Service.php?wsdl');
	$rel['soap'] = $soap;
	$room_id = I("post.room");
	$doctor_code = I("post.doctor_code");
	$dept_code = I("post.dept_code");
	$expert = I("post.expert");
	
	$row = $soap->login($room_id,$doctor_code,$dept_code,$expert);
	
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$patlist = $xml['Message']; 

	$rel['info'] = M("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_name")." 第".$room_id."诊室";
	$rel['Message'] = $xml['Message'];
	$this->ajaxReturn($rel,"JSON");
	
}
public function logout(){
	$soap = new \SoapClient('http://172.168.0.241/demo/soap/Service.php?wsdl');
	$doctor_code = I("param.doctor_code");
	$soap->logout(I("param.room"),$doctor_code,I("param.dept_code"),I("param.expert"));	
	$rel['success'] = 1;
	$this->ajaxReturn($rel,"JSON");
	
}
public function shun(){
	$soap = new \SoapClient('http://172.168.0.241/demo/soap/Service.php?wsdl');
	$room_id = I("post.room");
	$doctor_code = I("post.doctor_code");
	$dept_code = I("post.dept_code");
	$expert = I("post.expert");
	
	$row = $soap->shunhu($room_id,$doctor_code,$dept_code,$expert);
	$xml = simplexml_load_string($row);

	$xml = (array)$xml;
	$patlist = $xml['RegInfo'];
	
	$rel['patlist'] = $xml['RegInfo'];
	$rel['success'] = $xml['Message'];
	$this->ajaxReturn($rel,"JSON");
}

public function xuan(){
	$soap = new \SoapClient('http://172.168.0.241/demo/soap/Service.php?wsdl');
	$room_id = I("post.room");
	$doctor_code = I("post.doctor_code");
	$dept_code = I("post.dept_code");
	$expert = I("post.expert");
	$pat_code = I("post.pat_code");
	
	$row = $soap->xuanhu($room_id,$doctor_code,$dept_code,$expert,$pat_code);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$patlist = $xml['Message'];
	
	$rel['patlist'] = $xml['Message'];
	//$rel['xml'] = $row;
	$rel['success'] = 1;
	$this->ajaxReturn($rel,"JSON");
}

public function chong(){
	$soap = new \SoapClient('http://172.168.0.241/demo/soap/Service.php?wsdl');
	$room_id = I("post.room");
	$doctor_code = I("post.doctor_code");
	$dept_code = I("post.dept_code");
	$expert = I("post.expert");
	
	$row = $soap->chonghu($room_id,$doctor_code,$dept_code,$expert);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$patlist = $xml['RegInfo'];
	
	$rel['patlist'] = $xml['RegInfo'];
	$rel['success'] = 1;
	$this->ajaxReturn($rel,"JSON");
}
public function getPatList(){
	$doctor_code = I("post.doctor_code");
	$dept_code = I("post.dept_code");
	$expert = I("post.expert");
	
	$soap = new \SoapClient('http://172.168.0.241/demo/soap/Service.php?wsdl');
	$row = $soap->getPatList($doctor_code,$dept_code,$expert);

	$xml  = str_replace('gb2312', 'utf-8', $row);
	
	$xml = simplexml_load_string($xml);
	$xml = (array)$xml;

	$patlist = $xml['RegInfo'];
	$rel['patlist'] = $xml['RegInfo'];
	$rel['success'] = 1;
	$this->ajaxReturn($rel,"JSON");
}

}