<?php
namespace Home\Controller;
use Think\Controller;
class AddZhenLiaoPatController extends Controller {
public function index(){
  //$list = M("doctor_info")->select();
  $this->display();
}
public function getTeamKey(){
	$key = $_GET["term"];
	$flag = $_GET["flag"];
	//$result = $Model->query("select tid,tname from team where tname like '%".$key."%'");
	$result = M("doctor_info")->where("doctor_name like '%".$key."%'")->field("doctor_code,doctor_name")->select();
	for($i=0;$i<count($result);$i++){
		$list[$i]['id'] = $result[$i]['doctor_code'];
		$list[$i]['label'] = $result[$i]['doctor_name'];
	}
	echo json_encode($list);
}
public function addPatToList(){
	$doctor_code = I("post.doctor_code");
	$doctor_name = I("post.doctor_name");
	$noon_flag = I("post.noon_flag");
	
	//获取最新加号患者编号
	$jlist = M("register_assign")->where("reg_id like '%Y%' and doctor_code='".$doctor_code."' and noon_flag='".$noon_flag."'")->order("weight desc")->limit(1)->select();
	$mlist = M("register_assign")->where("doctor_code='".$doctor_code."' and noon_flag='".$noon_flag."'")->order("weight desc")->limit(1)->select();
	
	if(count($mlist)>0){
		$max_weight = $mlist[0]['weight'];
		$weight = $max_weight+1;
	}else{
		$weight = 1;
	}
	if(count($jlist)>0){
		$no_y_reg_id = str_replace("Y","",$jlist[0]['reg_id']);
		$no_y_reg_id = $no_y_reg_id+1;
		if(no_y_reg_id<10){
			 $reg_id = "Y0".$no_y_reg_id;
		}else{
			 $reg_id = "Y".$no_y_reg_id;
		}
	}else{
		$reg_id="Y01";
	}
	
	$data['reg_id'] = $reg_id;
	$data['doctor_code'] = $doctor_code;
	$data['doctor_name'] = $doctor_name;
	$data['pat_name'] = I("post.pat_name");
	$data['pat_code'] = date("YmdHis");
	$data['noon_flag'] = $noon_flag;
	$data['weight'] = $weight;
	$data['reg_time'] = date("Y-m-d H:i:s");
	
	if(M("register_assign")->add($data)){
		$rel['success'] = 1;
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}
}