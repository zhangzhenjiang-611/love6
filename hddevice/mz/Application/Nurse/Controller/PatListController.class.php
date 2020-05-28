<?php
namespace Nurse\Controller;
use Think\Controller;
class PatListController extends Controller {
public function index(){
  $flag = I("get.flag"); 
  $dept_ary = array();
  $dept_txt="";
  $this->zq = I("get.zhenqu");
  if(I("get.zhenqu")=="all")	{
  	  $zhenqu = M("zhenqu")->select();
	  
  }else{
  	$zhenqu = M("zhenqu")->where("id=".I("get.zhenqu"))->select();
  }
  $this->zhenqu = $zhenqu[0]['id'];
  for($i=0;$i<count($zhenqu);$i++){
		  if($i==0){
				$dept_txt=$zhenqu[$i]['dept']; 
		  }else{
			$dept_txt.=",".$zhenqu[$i]['dept'];
		  }
	  }
  $where = "1=1 and dept_code in(".$dept_txt.")";
 
 if($flag=='search'){
	  $doctor_code = I("param.doctor_code");
	  $pat_name = I("param.pat_name");
	  $dept_code  = I("param.dept_code");
	  $room_id = I("param.room");
	  if($pat_name!=""){
	  	  $where .= " and pat_name like '%".$pat_name."%' or `pat_code`='".$pat_name."'";
	  }else{
	  	  if($doctor_code!=""){
			  $where.=" and doctor_code='".$doctor_code."'";
		  }else{
			 if($dept_code!=""){
	      	    $where.=" and dept_code='".$dept_code."' and reg_type='普通'";
			    cookie("search_dept_code",$dept_code);
		 	 }
		  }
		  
		
	  }
	 
  }
  $count = M("register_assign")->where($where." and baodao=1")->count();
  
  $Page = new \Think\Page($count,1300);
  $show = $Page->show();// 分页显示输出
  
  $list = M("register_assign")->where($where)->order("reg_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
  
  for($i=0;$i<count($list);$i++){
	 if($list[$i]['status']==1){
	 	$list[$i]['status_txt'] = "已呼";
		$list[$i]['bgcolor'] = "#EFFF79";
	 } 
	 if($list[$i]['status']==3){
	 	$list[$i]['status_txt'] = "已过号";
		$list[$i]['bgcolor'] = "#6EB687";
	 }
	 
	 if($list[$i]['status']==5){
	 	$list[$i]['status_txt'] = "已复诊";
		$list[$i]['bgcolor'] = "#F8FCB2";
	 }
	 
	 if($list[$i]['status']==0){
	 	$list[$i]['status_txt'] = "未呼";
		$list[$i]['bgcolor'] = "#ffffff";
	 }
	 if($list[$i]['withdraw_flag']==1){
		$list[$i]['jz_status'] = "<font color='red'>已退号</font>";	
	 }
	 else if($list[$i]['is_jz']==0){
		$list[$i]['jz_status'] = "<font color='red'>未接</font>";	
     }else{
     	$list[$i]['jz_status'] = "<font color='green'>已接</font>";	
	 }
	 switch($list[$i]['baodao']){
	 case 0:
	 	$list[$i]['bd_status'] = 	"<font color='red'>未报到</font>";
		break;
	 case 1:
		$list[$i]['bd_status'] = 	"<font color='green'>已报到</font>";
		break;
	 }
	 
	 $list[$i]['room'] = M("tbl_room_list")->where("room_id=".$list[$i]['room'])->getField("real_id");
	 if($list[$i]['fp_doctor_code']!=""){
		$list[$i]['doctor_name'] = M("doctor_info")->where("doctor_code='".$list[$i]['fp_doctor_code']."'")->getField("doctor_name");	
	 }
	 if($list[$i]['doctor_code']==""){
		$list[$i]['doctor_name'] = "";
	 }
  }
  $this->assign('page',$show);// 赋值分页输出
  $this->list = $list;
  //科室列表
  
  if(I("get.admin")==0){
  	  $dpt_txt = $zhenqu[0]['dept'];
	  $dept_lists = explode(",",$dpt_txt);
	  $dept_list = array();
	  for($i=0;$i<count($dept_lists);$i++){
	  	  $dept_list[$i]['dept_code'] = $dept_lists[$i];
		  $dept_list[$i]['dept_name'] = M("dept_info")->where("dept_code='".$dept_lists[$i]."'")->getField("dept_name");
	  }
  }else{
	 $dept_list = M("custom_dept")->field("id,dept_code,dept_name")->select();
  }
  $this->dept_list = $dept_list;
  $this->t_doc = M("tbl_op")->where("op_name='t_doc'")->getField("op_val");
  $this->t_dept = M("tbl_op")->where("op_name='t_dept'")->getField("op_val");
  $this->t_fuzhen = M("tbl_op")->where("op_name='t_fuzhen'")->getField("op_val");
  $this->chadui =  M("tbl_op")->where("op_name='chadui'")->getField("op_val");
  
  
  for($i=0;$i<count($dept_list);$i++){
	 $dept_list[$i]['total'] = M("register_assign")->where("dept_code='".$dept_list[$i]['dept_code']."'")->count();
	 $dept_list[$i]['bd_yes'] = M("register_assign")->where("dept_code='".$dept_list[$i]['dept_code']."' and baodao=1")->count();
	  $dept_list[$i]['bd_no'] = M("register_assign")->where("dept_code='".$dept_list[$i]['dept_code']."' and baodao=0")->count();
	  
	  $doclist = M("register_assign")->where("dept_code='".$dept_list[$i]['dept_code']."' and doctor_code!='' and doctor_code is not null and baodao=1")->group("doctor_code")->select();
	  $dept_list[$i]['doclist'] = $doclist;
	 
  }
  $this->dept_list = $dept_list;
  $this->display();
}

public function del(){
	$condition['pat_code'] = I("post.pat_code");
	$condition['ledger_sn'] = I("post.ledger_sn");
	$condition['order_no'] = I("post.order_no");
	if(M("medicine_gets")->where($condition)->delete()){
		$rel['success'] = 1;
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}
public function edit(){
	$pat_code = I("get.pat_code");
	$dept_code = I("get.dept_code");
	
	$condition['pat_code'] = $pat_code;
	$condition['dept_code'] = $dept_code;
	$row = M("register_assign")->where($condition)->select();
	if($row[0]['status']==0){
		$row[0]['status_txt'] = "待呼叫";
	}
	if($row[0]['status']==1){
		$row[0]['status_txt'] = "已呼叫";
	}
	if($row[0]['status']==3){
		$row[0]['status_txt'] = "已过号";
	}
	if($row[0]['status']==5){
		$row[0]['status_txt'] = "已复诊";
	}
	$this->row = $row[0];
	$this->display();
}
public function fuzhen(){
	$condition['reg_id'] = I("post.reg_id");
	$condition['dept_code'] = I("post.dept_code");
	$condition['doctor_code'] = I("post.doctor_code");
	
	$data['status'] = 5;
	$have_call = M("register_assign")->where($condition)->field("status,is_jz")->select(); 
	if($have_call[0]['is_jz']==0){
		$rel['success'] = 0;
		$rel['txt'] = "未接诊患者不可执行复诊操作";
	}else{
		if(M("register_assign")->where($condition)->save($data)){
			$rel['success'] = 1;
		}else{
			$rel['success'] = 0;
			$rel['txt'] = "复诊失败";
		}
	}
	$this->ajaxReturn($rel,"JSON");
}

public function guidui(){
	$condition['reg_id'] = I("post.reg_id");
	//$condition['dept_code'] = I("post.dept_code");
	//$condition['doctor_code'] = I("post.doctor_code");
	$data['status'] = 0;
	$have_call = M("register_assign")->where($condition)->getField("status");
	if($have_call!=3){
		$rel['success'] = 0;
		$rel['txt'] = "未过号患者不可执行复诊操作"; 
	}else{
		if(M("register_assign")->where($condition)->save($data)){
			$rel['success'] = 1;
		}else{
			$rel['success'] = 0;
			$rel['txt'] = "归队失败";
		}
	}
	$this->ajaxReturn($rel,"JSON");
}
public function change_dept(){
	$dept_code = I("get.dept_code");
	$pat_code = I("get.pat_code");
	$this->dept_code=$dept_code;
	$this->dept_name = M("dept_info")->where("dept_code='".$dept_code."'")->getField("dept_name");
	$this->pat_name = M("register_assign")->where("pat_code='".$pat_code."'")->getField("pat_name");
	$dept_list = M("custom_dept")->select();
	$this->dept_list = $dept_list;
	$this->display();
}
public function change_doctor(){
	$dept_code = I("get.dept_code");
	$doctor_code = I("get.doctor_code");
	$this->doctor_code = $doctor_code;
	if($doctor_code!=""){
		$this->doctor_name = M("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_name");
	}
	$this->dept_name = M("dept_info")->where("dept_code='".I("get.dept_code")."'")->getField("dept_name");
	$this->pat_name = M("register_assign")->where("pat_code='".I("get.pat_code")."'")->getField("pat_name");
	$this->dept_code=$dept_code;;
	$this->display();
}
public function chadui(){
	$dept_code = I("get.dept_code");
	$pat_code = I("get.pat_code");
	$this->dept_code=$dept_code;
	$this->dept_name = M("dept_info")->where("dept_code='".$dept_code."'")->getField("dept_name");
	$this->pat_name = M("register_assign")->where("pat_code='".$pat_code."'")->getField("pat_name");
	$dept_list = M("custom_dept")->select();
	$this->dept_list = $dept_list;
	$this->display();
}
public function ajax_chadui(){
	$position = I("post.position");
	if($position==1){
		$position=0;
	}else{
		$position = $position;
	}
	if($position<0){
		echo "参数错误";
	}
	$dept_code_old = I("post.dept_code_old");
	$dept_code_new = I("post.dept_code_new");
	$pat_code = I("post.pat_code");
	
	$dept_new_name = M("dept_info")->where("dept_code='".$dept_code_new."'")->getField("dept_name");
	$data['dept_code'] = $dept_code_new;
	$data['dept_name'] = $dept_new_name;
	$data['chadui']  = 1;
	
	
	$csel = M("register_assign")->where("dept_code='".$dept_code_new."' and status in(0,5) and (doctor_code='' or doctor_code is null) and withdraw_flag=0 and (is_jz=0)")->limit($position.",1")->order("weight asc")->field("weight")->select(); 
	//echo M("register_assign")->where("dept_code='".$dept_code_new."' and status in(0,5) and (doctor_code='' or doctor_code is null) and withdraw_flag=0 and jz_time='0000-00-00 00:00:00'")->limit($position.",1")->order("weight asc")->select(false);
	$data['weight'] = $csel[0]['weight']-1;
	
	if(M("register_assign")->where("pat_code='".$pat_code."' and dept_code='".$dept_code_old."'")->save($data)){
		$rel['success'] = 1;
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
	
	
}
public function ajax_change_dept(){
	$dept_code_old = I("post.dept_code_old");
	$dept_code_new = I("post.dept_code_new");
	$pat_code = I("post.pat_code");
	$dept_new_name = M("dept_info")->where("dept_code='".$dept_code_new."'")->getField("dept_name");
	$data['dept_code'] = $dept_code_new;
	$data['dept_name'] = $dept_new_name;
	if($dept_code_old!=""){
		$where = "pat_code='".$pat_code."' and dept_code='".$dept_code_old."'";
	}else{
		$where = "pat_code='".$pat_code."'";
	}
	if(M("register_assign")->where($where)->save($data)){
		$rel['success'] = 1;
		$rel['dept_name'] = $dept_new_name;
	}else{
		$rel['success'] =0;
	}
	$this->ajaxReturn($rel,"JSON");
}

public function ajax_change_doctor(){	
	$doctor_code_old = I("post.doctor_code_old");
	$doctor_code_new = I("post.doctor_code_new");
	$dept_code = I("post.dept_code_old");
	$pat_code = I("post.pat_code");
	$data['doctor_code'] = $doctor_code_new;
	$data['doctor_name'] = M("doctor_info")->where("doctor_code='".$doctor_code_new."'")->getField("doctor_name");
	if($doctor_code_old!=""){
		$where = "pat_code='".$pat_code."' and doctor_code='".$doctor_code_old."'";
	}else{
		$where = "pat_code='".$pat_code."'";
	}
	if(M("register_assign")->where($where)->save($data)){
		$rel['success'] = 1;
		$rel['doctor_name'] = $data['doctor_name'];
	}else{
		$rel['success'] =0;
	}
	$this->ajaxReturn($rel,"JSON");
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
public function getDcotorKey(){
	$key = $_GET["term"];
	$flag = $_GET["flag"];
	//$result = $Model->query("select tid,tname from team where tname like '%".$key."%'");
	$result = M("doctor_info")->where("doctor_name like '%".$key."%' or doctor_code like '%".$key."%'")->field("doctor_code,doctor_name")->select();
	for($i=0;$i<count($result);$i++){
		$list[$i]['id'] = $result[$i]['doctor_code'];
		$list[$i]['label'] = $result[$i]['doctor_name'];
	}
	echo json_encode($list);
}

}