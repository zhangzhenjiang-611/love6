<?php
namespace Nurse\Controller;
use Think\Controller;
class MainController extends Controller {
public function index(){
  $weekarray=array("日","一","二","三","四","五","六");
  $this->week = "星期".$weekarray[date("w")];
  $this->pat_num = M("register_assign")->count();
  $this->expert_num = M("register_assign")->where("trim(doctor_code)<>'' and trim(doctor_code) is not null")->count();
  $this->normal_num = M("register_assign")->where("trim(doctor_code)='' or trim(doctor_code) is null")->count();
  $this->yes = M("register_assign")->where("status=1")->count();
  $this->no = M("register_assign")->where("status=0")->count();
  $this->fuzhen = M("register_assign")->where("status=5")->count();
  //诊室列表
  $room_list = M("tbl_room_list")->where("real_id <>''")->order("real_id asc")->select();
  $client_list = M("client_uid")->select();
  $client = "";
  for($i=0;$i<count($client_list);$i++){
	if($i==0){
		$client.=$client_list[$i]['room_id'];
	}else{
		$client.=",".$client_list[$i]['room_id'];
	}
  }
  $client_ary = explode(",",$client);
  $room_a = array();
  $room_b = array();
  for($i=0;$i<count($room_list);$i++){
	  if(in_array($room_list[$i]['room_id'],$client_ary)){
	  	  $room_list[$i]['kaizhen'] = 1;
	  }else{
	  	  $room_list[$i]['kaizhen'] = 0;
	  }
	  if(strpos($room_list[$i]['room_name'],"A")!==false){
	  	$room_a[] = $room_list[$i];
	  }
	  if(strpos($room_list[$i]['room_name'],"B")!==false){
	  	$room_b[] = $room_list[$i];
	  }
  }
  
  $this->room_a = $room_a;
  $this->room_b = $room_b;
  $this->display();
}




}