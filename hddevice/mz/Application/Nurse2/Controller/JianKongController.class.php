<?php
namespace Nurse2\Controller;
use Think\Controller;
class JianKongController extends Controller {
public function index(){
  $zhenqu = session("zhenqu");
  $room_txt = M("zhenqu")->where("id=".$zhenqu)->getField("room");
  if($zhenqu=='all'||$zhenqu==''){
	  $list = M("client_uid")->select();
  }else{
  		 $list = M("client_uid")->where("find_in_set(room_id,'".$room_txt."')")->select();
  }
 
  
  $relist = array();
  for($i=0;$i<count($list);$i++){
  	$doctor_code = $list[$i]['uid'];
	if($list[$i]['expert']==1){
		$where="fp_doctor_code='".$doctor_code."' and reg_type='专家' and dept_code='".$list[$i]['dept']."'";
	}else{
		$where="fp_doctor_code='".$doctor_code."' and reg_type='普通' and dept_code='".$list[$i]['dept']."'";
	}
	$doctor_name = M("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_name");
	$pat_now = M("register_assign")->where("status=1 and ".$where."and withdraw_flag='0'")->limit(1)->order("call_time desc")->select();
	$pat_wait = M("register_assign")->where($where." and ((status=5 and is_jz=1) or (status in(0,3,1) and is_jz=0)) and withdraw_flag='0'")->order("weight asc")->select();
	for($m=0;$m<count($pat_wait);$m++){
		if($pat_wait[$m]['is_jz=1']){
			$pat_wait[$m]['fuzhen']=1;
		}else{
			$pat_wait[$m]['fuzhen']=0;
		}
		
		switch($pat_wait[$m]['status']){
			case 0:
			$pat_wait[$m]['status_txt'] = "未呼";
			break;
			case 1:
			$pat_wait[$m]['status_txt'] = "已呼";
			break;
			case 3:
			$pat_wait[$m]['status_txt'] = "过号";
			break;
			case 5:
			$pat_wait[$m]['status_txt'] = "复诊";
			break;
			
		}
	}
	
	$relist[$i]['room_id'] = $list[$i]['room_id'];
	$relist[$i]['real_id'] = getShowRoomName($list[$i]['room_id']);
	$relist[$i]['dept_code'] = $list[$i]['dept'];
	$relist[$i]['expert'] = $list[$i]['expert'];
	$relist[$i]['dept_name'] = getDeptNameByCode($list[$i]['dept']);
	$relist[$i]['is_over'] =  	$list[$i]['is_over'];
	$relist[$i]['doctor_name'] = $doctor_name;
	$relist[$i]['doctor_code'] = $doctor_code;
	$relist[$i]['pat_now'] = $pat_now[0];
	$relist[$i]['pat_wait'] = $pat_wait;
	$relist[$i]['pat_wait_num'] = M("register_assign")->where("status=0 and is_jz=0 and doctor_code='".$doctor_code."'")->count();
	if($relist[$i]['is_jz']==1){
		$relist[$i]['status_txt'] = '<font color="green"><b>已诊</b></font>';
	}
	if($relist[$i]['status']==0){
		$relist[$i]['status_txt'] = '<font color="#333333"><b>未呼</b></font>';
	}
	if($relist[$i]['status']==3){
		$relist[$i]['status_txt'] = '<font color="blue"><b>过号</b></font>';
	}
  	
  }
  $this->show = I("get.show");
  $this->list = $relist;
  $this->zhenqu = I("get.zhenqu");
  $this->display();
}


public function setStatus(){
	$pat_code = I("post.pat_code");
	$doctor_code = I("post.doctor_code");
	$data['status'] = 0;
	if(M("register_assign")->where("pat_code='".$pat_code."'")->save($data)){
		$rel['success'] = 1;
		$rel['pat_wait_num'] = M("register_assign")->where("status = 0 and doctor_code='".$doctor_code."'")->count();
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}
function delRoom(){
	$room = I("post.room");
	$doctor_code = I("post.doctor_code");
	if(M("client_uid")->where("room_id=".$room." and uid='".$doctor_code."'")->delete()){
		$rel['success']  =1;
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}
public function op_tingzhen(){
	$room = I("post.room");
	$is_over = I("post.is_over");
	$uid = I("post.doctor_code");
	$expert = I("post.expert");
	
	$doctor_name = M("doctor_info")->where("doctor_code='".$uid."'")->getField("doctor_name");
	$condition['room_id']=$room;
	$condition['uid']=$uid;
	$condition['expert']=$expert; 
	$dept_code = M("client_uid")->where($condition)->getField("dept");
	if($is_over==1){
		$is_over=0;
	}else{
		$is_over=1;
	}
	//如果停诊,则释放病人绑定
	if($is_over==1){
		//释放普通未接患者
		if($expert==0){
			$data2['fp_doctor_code']="";
			$data2['fp_doctor_name']="";
			$data2['baodao']=1;
			$data2['room']='';
			$no_chuli = M("register_assign")->where("fp_doctor_code='".$uid."' and reg_type='普通' and status=5")->count();
				
			if($no_chuli>0){
				$rel['no_chuli'] = $no_chuli;
				$this->ajaxReturn($rel,"JSON"); 
			}else{
				$rel['no_chuli'] = 0;
			}
			M("register_assign")->where("fp_doctor_code='".$uid."' and reg_type='普通' and baodao=1 and is_jz=0")->save($data2);
		}
		
		if($expert==1){
			$no_chuli = M("register_assign")->where("fp_doctor_code='".$uid."' and reg_type='专家' and status in(0,5)")->count();  
			if($no_chuli>0){
				$rel['no_chuli'] = $no_chuli;
				$this->ajaxReturn($rel,"JSON"); 
			}else{
				$rel['no_chuli'] = 0;
			}
		}
		
	}else{
		if($expert==0){
			//首先确定自己已经分配了几名患者
			$my_fnum = M()->query("select count(*) as num from register_assign where fp_doctor_code='".$uid."' and dept_code='".$dept_code."' and reg_type='普通' and ((status=5 and is_jz=1) or (status=0 and is_jz=0))");
			$my_fnum_cha = getOpVal("wait_num")-$my_fnum->num;
			$sql2="select * from register_assign where fp_doctor_code='' and baodao=1 and reg_type='普通' and doctor_code='' and dept_code='".$dept_code."' order by weight asc limit ".$my_fnum_cha;
			$lg1 = M()->query($sql2);

			foreach($lg1 as $val){
				M()->query("update register_assign set room='".$room."',fp_doctor_code='".$uid."',fp_doctor_name='".$doctor_name."' where reg_id='".$val['reg_id']."' and baodao=1 and reg_type='普通'");
			}
		}
	}
	$data['is_over'] = $is_over;
	if(M("client_uid")->where($condition)->save($data)){
		
		$rel['success'] = 1;
		$rel['is_over'] = $is_over;
	}else{
	
		$rel['success'] = 0;
	}
	//echo M("client_uid")->getlastsql();
	$this->ajaxReturn($rel,"JSON"); 
}




}