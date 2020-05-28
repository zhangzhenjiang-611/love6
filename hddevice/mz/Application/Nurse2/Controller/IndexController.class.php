<?php
namespace Nurse2\Controller;
use Think\Controller;
class IndexController extends Controller {
public function index(){
  $this->login_time = M("zq_user")->where("zhenqu=".session("zhenqu")." and code=".session("ucode"))->limit("1,1")->order("login_time desc")->getField("login_time");
  $data['tdate'] = date("Y-m-d H:i:s");
  M("tbl_op")->where("op_name='login_time'")->save($data);
  
  
  $weekarray=array("日","一","二","三","四","五","六");
  $this->week = "星期".$weekarray[date("w")];
  $this->pat_num = M("register_assign")->count();
  $this->expert_num = M("register_assign")->where("trim(doctor_code)<>'' and trim(doctor_code) is not null")->count();
  $this->normal_num = M("register_assign")->where("trim(doctor_code)='' or trim(doctor_code) is null")->count();
  $this->yes = M("register_assign")->where("is_jz=1")->count();
  $this->no = M("register_assign")->where("is_jz=0")->count();
  $this->fuzhen = M("register_assign")->where("status=5")->count();
  $zq_room = M("zhenqu")->where("id=".session("zhenqu"))->getField("room");
  //诊室列表
  $client_list = M("client_uid")->where("find_in_set(room_id,'".$zq_room."')")->order("room_id asc")->select();
  for($i=0;$i<count($client_list);$i++){
	   $client_list[$i]['dept_name'] = getDeptNameByCode($client_list[$i]['dept']);
	   $client_list[$i]['doctor_name'] = getDoctorNameByCode($client_list[$i]['uid']);
	   switch($client_list[$i]['expert']){
			case 1:
			$client_list[$i]['wait_num'] = M("register_assign")->where("reg_type='专家' and dept_code='".$client_list[$i]['dept']."' and ((is_jz=1 and status=5) or (is_jz=0 and status=0)) and withdraw_flag=0 and fp_doctor_code='".$client_list[$i]['uid']."'")->count();
			 $client_list[$i]['guohao_num'] = M("register_assign")->where("reg_type='专家' and dept_code='".$client_list[$i]['dept']."' and status=3 and withdraw_flag=0 and fp_doctor_code='".$client_list[$i]['uid']."'")->count();
			break;
			case 0:
			$client_list[$i]['wait_num'] = M("register_assign")->where("reg_type='普通' and dept_code='".$client_list[$i]['dept']."' and ((is_jz=1 and status=5) or (is_jz=0 and status=0)) and withdraw_flag=0 and fp_doctor_code='".$client_list[$i]['uid']."'")->count();
			 $client_list[$i]['guohao_num'] = M("register_assign")->where("reg_type='普通' and dept_code='".$client_list[$i]['dept']."' and status=3 and withdraw_flag=0 and fp_doctor_code='".$client_list[$i]['uid']."'")->count();
			break;
			
	   }
	  
	   $client_list[$i]['room_id'] = getShowRoomName($client_list[$i]['room_id']);
	   
  }
   
  
 $this->clist = $client_list; 
  $this->display();
}
public function login(){
	$this->display();
}
public function main(){
	$admin = I("get.admin");
	$zhenqu = I("get.zhenqu");
	session("zhenqu",$zhenqu);
	$this->admin = $admin;
	$this->display();
}
public function left(){
	$admin = I("get.admin");
	$this->admin = $admin;
	$id = I("get.id");
	$this->id = $id;
	$this->display();
}
public function gnsz(){
	$jpz = M("tbl_op")->where("op_name='jpz'")->getField("op_val");
	$this->jpz = $jpz;
	$this->login_txt =  M("tbl_op")->where("op_name='login_txt'")->getField("op_val2");
	$this->t_doc =  M("tbl_op")->where("op_name='t_doc'")->getField("op_val");
	$this->t_dept =  M("tbl_op")->where("op_name='t_dept'")->getField("op_val");
	$this->t_fuzhen =  M("tbl_op")->where("op_name='t_fuzhen'")->getField("op_val");
	$this->chadui =  M("tbl_op")->where("op_name='chadui'")->getField("op_val");
	$this->bottom_info = M("tbl_op")->where("op_name='bottom_info'")->getField("op_val2");
	$this->call_times = M("tbl_op")->where("op_name='call_times'")->getField("op_val");
	$this->ftime = M("tbl_op")->where("op_name='ftime'")->getField("op_val");
	$this->t_fenzhen = M("tbl_op")->where("op_name='t_fenzhen'")->getField("op_val");
	$this->wait_num = M("tbl_op")->where("op_name='wait_num'")->getField("op_val");
	$this->his_code = M("tbl_op")->where("op_name='his_code'")->getField("op_val");
	$this->fptype = getOpVal("fptype");
	$this->is_tc = getOpVal("is_tc");
	$this->houtai_page_num = getOpVal("houtai_page_num");
	$this->display();
}
public function jpz_do(){
	$data['op_val'] = I("post.jpz");
	M("tbl_op")->where("op_name='jpz'")->save($data);
	$data2['op_val2'] = htmlspecialchars(stripslashes(I("post.login_txt")),ENT_QUOTES ,UTF-8 - ASCII );;
	M("tbl_op")->where("op_name='login_txt'")->save($data2);
	
	$data3['op_val'] = I("post.t_doc");
	M("tbl_op")->where("op_name='t_doc'")->save($data3);
	
	$data4['op_val'] = I("post.t_dept");
	M("tbl_op")->where("op_name='t_dept'")->save($data4);
	
	$data5['op_val'] = I("post.t_fuzhen");
	M("tbl_op")->where("op_name='t_fuzhen'")->save($data5);
	//插队
	$data6['op_val'] = I("post.chadui");
	M("tbl_op")->where("op_name='chadui'")->save($data6);
	//分诊大屏下方文字
	$data7['op_val2'] = I("post.bottom_info");
	M("tbl_op")->where("op_name='bottom_info'")->save($data7);
	//分诊语音呼叫次数
	$data8['op_val'] = I("post.call_times");
	M("tbl_op")->where("op_name='call_times'")->save($data8);
	//分诊大屏刷新数据时间间隔
	$data9['op_val'] = I("post.ftime");
	M("tbl_op")->where("op_name='ftime'")->save($data9);
	//是否开启分诊
	$data10['op_val'] = I("post.t_fenzhen");
	M("tbl_op")->where("op_name='t_fenzhen'")->save($data10);
	//分诊候诊人数
	$data11['op_val'] = I("post.wait_num");
	M("tbl_op")->where("op_name='wait_num'")->save($data11);
	//HIS获取数据编码
	$data12['op_val'] = I("post.his_code");
	M("tbl_op")->where("op_name='his_code'")->save($data12);
	
	//队列插入模式
	$data13['op_val'] = I("post.fptype");
	M("tbl_op")->where("op_name='fptype'")->save($data13);
	
	//报到机多记录弹窗
	$data14['op_val'] = I("post.is_tc");
	M("tbl_op")->where("op_name='is_tc'")->save($data14);
	
	//分页数据量
	$data15['op_val'] = I("post.houtai_page_num");
	M("tbl_op")->where("op_name='houtai_page_num'")->save($data15);
	
	$this->success("操作成功");
	
}
/**
 *医生列表部分
**/
public function doc_list(){
	if(I("get.flag")=="search"){
  	$count = M()->Table("doctor_info")->where("doctor_name like '%".I("post.keywords")."%'")->count();
	 $Page = new \Think\Page2($count,20);
  $show = $Page->show();// 分页显示输出
	$list = M()->Table("doctor_info")->where("doctor_name like '%".I("post.keywords")."%'")->limit($Page->firstRow.','.$Page->listRows)->select();
	
  }else{
	  $count = M()->Table("doctor_info")->count();
	   $Page = new \Think\Page2($count,20);
  $show = $Page->show();// 分页显示输出
  	$list = M()->Table("doctor_info")->limit($Page->firstRow.','.$Page->listRows)->select();
  }
  
  $this->list = $list;
  $this->assign('page',$show);// 赋值分页输出
  $this->display();
}
}