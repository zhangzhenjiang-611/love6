<?php
namespace Home\Controller;
use Think\Controller;
class SmallScreenController extends Controller {
public function index(){
  $room = I("get.room");
  $room_name = M("tbl_room_list")->where("room_id=".$room)->getField("room_name");
  $this->real_id = M("tbl_room_list")->where("room_id=".$room)->getField("real_id");
  $this->room_name = $room_name;
  $doctor_code = M("client_uid")->where("room_id=".$room)->getField("uid");
  $this->assign("room",$room);
  $this->assign("intro",M("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("description"));
  $this->display();
}
public function getSmallScreenInfo(){
	$room = I("post.room");
	$room_small_txt = "3,8,9,10,80";
	$room_small_ary = explode(",",$room_small_txt);
	if(in_array($room,$room_small_ary)){
		$this->small_txt = 1;
	}else{
		$this->small_txt = 0;
	}
	$pat = array();
	$hpt = M("client_uid")->where("room_id=".$room)->field("id,uid,room_id,dept,expert")->select();
	$doctor_code = "";
	if(count($hpt)>0){
		/*
		**获取诊室医生
		*/
		$dept_ary=array();
		for($i=0;$i<count($hpt);$i++){
			if($i==0){
				$doctor_code .= $hpt[$i]['uid'];
			}else{
				$doctor_code .= ",".$hpt[$i]['uid'];
			}
			$dept_ary[] = $hpt[$i]['dept'];
		}
		//处理同一诊室登录两个科室的情
		if(count($hpt)==2&&count($dept_ary)>1&&$dept_ary[0]!=$dept_ary[1]){
			$rel['two_dept'] = true;
			$rel['have_logout']=0;
			$rel['room_id'] = M("tbl_room_list")->where("room_id=".$room)->getField("real_id");
			for($m=0;$m<count($hpt);$m++){
				$dept_name = M("dept_info")->where("dept_code='".$hpt[$m]['dept']."'")->getField("dept_name");
				$docinfo = M("doctor_info")->where("doctor_code='".$hpt[$m]['uid']."'")->field("doctor_name,doctor_postitle")->select();
		
				$pat_now = M("register_assign")->where("allot_doctor_code='".$hpt[$m]['uid']."' and status=1 and dept_code='".$hpt[$m]['dept']."' and withdraw_flag='0'")->field("pat_name,reg_seq_no")->order("call_time desc")->limit(1)->select(); 
				if(count($pat_now)==0){
					$pat_now='n';
				}else{
					$pat_now[0]['reg_seq_no'] = str_replace("-","加",$pat_now[0]['reg_seq_no']);
				}
				
				if($hpt[$m]['expert']==1){
					$pat_wait = M("register_assign")->where("doctor_code='".$hpt[$m]['uid']."' and ((status=5 and is_jz=1) or (status=0 and is_jz=0))  and withdraw_flag='0' and dept_code='".$hpt[$m]['dept']."' and baodao=1 and reg_type='专家' and (fp_doctor_code='".$hpt[$m]['uid']."' or doctor_code='".$hpt[$m]['uid']."')")->field("pat_name,reg_seq_no")->order("noon_flag,wait_no desc,weight asc")->limit(getOpVal("wait_num"))->select();
				}else{
					$pat_wait = M("register_assign")->where("((status=5 and is_jz=1) or (status=0 and is_jz=0))  and withdraw_flag='0' and dept_code='".$hpt[$m]['dept']."' and baodao=1 and reg_type='普通' and (fp_doctor_code='".$hpt[$m]['uid']."' or doctor_code='".$hpt[$m]['uid']."')")->field("pat_name,reg_seq_no")->order("noon_flag,wait_no desc,weight asc")->limit(getOpVal("wait_num"))->select();
				}
				
				if(count($pat_wait)>0){
					for($i=0;$i<count($pat_wait);$i++){
						$pat_wait[$i]['reg_seq_no'] = str_replace("-","加",$pat_wait[$i]['reg_seq_no']);	
					}
				}else{
					$pat_wait='n';
				}
				$pat['expert'] = $hpt[$m]['expert'];
				$pat['pat_now'] = $pat_now;
				$pat['pat_wait'] = $pat_wait;
				$pat['doctor_postitle'] =$docinfo[0]['doctor_postitle'];
				$pat['doctor_name'] = $docinfo[0]['doctor_name'];
				$pat['dept_name'] = M("dept_info")->where("dept_code='".$hpt[$m]['dept']."'")->getField("dept_name");
				$pat_list[$m] = $pat;
			}
			$rel['pat'] = $pat_list;
			$this->ajaxReturn($rel,"JSON");
		}
		/*
		**一个专家
		*/
		if(count($hpt)==1&&$hpt[0]['expert']==1){
			$dept_code = $hpt[0]['dept'];
			$rel['two_dept'] = false;
			$rel['expert'] = 1;
			$rel['num'] = 1;
			$rel['have_logout']=0;
			$room_info = M("tbl_room_list")->where("room_id=".$hpt[0]['room_id'])->field("real_id,room_name")->select();
			//$rel['room_name'] = $room_info[0]['room_name'];
			$rel['room_id'] = $room_info[0]['real_id'];
			//$rel['dept_name'] = M("dept_info")->where("dept_code='".$dept_code."'")->getField("dept_name");
			
			$doctor_code = $hpt[0]['uid'];
			$rel['room'] = M("doctor_info")->join(" dept_info on dept_info.dept_code=doctor_info.dept_code")->where("doctor_code='".$doctor_code."'")->getField("dept_info.dept_name");
			$docinfo = M("doctor_info")->where("doctor_code='".$doctor_code."'")->field("doctor_name,thumbnail,description,doctor_postitle")->select();
			$pat_now = M("register_assign")->where("doctor_code='".$doctor_code."' and dept_code='".$dept_code."' and status=1 and baodao=1 and withdraw_flag='0'")->field("pat_name,reg_seq_no")->order("call_time desc")->limit(1)->select(); 
			if($pat_now){
				$pat_now[0]['reg_seq_no'] = str_replace("-","加",$pat_now[0]['reg_seq_no']);
			}
			
			$pat_wait = M("register_assign")->where("doctor_code='".$doctor_code."' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and baodao=1 and dept_code='".$dept_code."' and withdraw_flag='0'")->field("pat_name,reg_seq_no")->order("noon_flag,wait_no desc,weight asc")->limit(getOpVal("wait_num"))->select();
			for($i=0;$i<count($pat_wait);$i++){
				$pat_wait[$i]['reg_seq_no'] = str_replace("-","加",$pat_wait[$i]['reg_seq_no']);
			}
			if($pat_now){
				$rel['pat_now'] = $pat_now;
			}else{
				$rel['pat_now'] = '';
			}
			
			$rel['pat_wait'] = $pat_wait;
			if($docinfo[0]['thumbnail']!=null){
				$rel['thumbnail'] = $docinfo[0]['thumbnail'];
			}else{
				$rel['thumbnail'] = "/mz/Uploads/doc_default.jpg"; 
			}
			
			$rel['doctor_name'] = $docinfo[0]['doctor_name'];
			if($docinfo[0]['description']!=null&&$docinfo[0]['description']!=""){
				$rel['description'] = html_entity_decode(stripslashes(htmlspecialchars_decode($docinfo[0]['description'])), ENT_QUOTES, 'UTF-8');
			}else{
				$rel['description'] = "暂无";
			}
			
			$rel['zhicheng'] = $docinfo[0]['doctor_postitle'];
			$rel['room'] = M("dept_info")->where("dept_code='".$hpt[0]['dept']."'")->getField("dept_name");
			$this->ajaxReturn($rel,"JSON");
		}
		/*
		**两个专家
		*/
		if(count($hpt)==2&&$hpt[0]['expert']==1){
			$rel['expert'] = 1;
			$rel['two_dept'] = false;
			$rel['num'] = 2;
			$rel['have_logout'] = 0;
			$rel['room'] = M("dept_info")->where("dept_code='".$hpt[0]['dept']."'")->getField("dept_name");
			$rel['room_id'] = M("tbl_room_list")->where("room_id=".$room)->getField("real_id");
			$pat_list = array();
			for($m=0;$m<count($hpt);$m++){
				$pat="";
				$doctor_code = $hpt[$m]['uid'];
				$dept_code = $hpt[$m]['dept'];
				//$pat['dept_name'] = M("dept_info")->where("dept_code='".$dept_code."'")->getField("dept_name");
				$docinfo = M("doctor_info")->where("doctor_code='".$doctor_code."'")->field("doctor_name,doctor_postitle")->select();
				if($hpt[$m]['expert']==1){
					$pat_now = M("register_assign")->where("doctor_code='".$doctor_code."' and reg_type='专家' and status=1 and dept_code='".$dept_code."' and withdraw_flag='0'")->field("pat_name,reg_seq_no")->order("call_time desc")->limit(1)->select(); 
				}else{
					$pat_now = M("register_assign")->where("allot_doctor_code='".$doctor_code."' and reg_type='普通' and status=1 and dept_code='".$dept_code."'  and withdraw_flag='0'")->field("pat_name,reg_seq_no")->order("call_time desc")->limit(1)->select(); 
				}
				if(count($pat_now)==0){
					$pat_now='n';
				}else{
					$pat_now[0]['reg_seq_no'] = str_replace("-","加",$pat_now[0]['reg_seq_no']);
				}
				if($hpt[$m]['expert']==1){
					$pat_wait = M("register_assign")->where("doctor_code='".$doctor_code."' and ((status=5 and is_jz=1) or (status=0 and is_jz=0))  and withdraw_flag='0' and dept_code='".$dept_code."' and baodao=1 and reg_type='专家' and (fp_doctor_code='".$doctor_code."' or doctor_code='".$doctor_code."')")->field("pat_name,reg_seq_no")->order("noon_flag,wait_no desc,weight asc")->limit(getOpVal("wait_num"))->select();
					
				}else{
					$pat_wait = M("register_assign")->where("((status=5 and is_jz=1) or (status=0 and is_jz=0))  and withdraw_flag='0' and dept_code='".$dept_code."' and baodao=1 and reg_type='普通' and (fp_doctor_code='".$doctor_code."' or doctor_code='".$doctor_code."')")->field("pat_name,reg_seq_no")->order("noon_flag,wait_no desc,weight asc")->limit(getOpVal("wait_num"))->select();
					
				}
				
				
				if(count($pat_wait)>0){
					for($i=0;$i<count($pat_wait);$i++){
						$pat_wait[$i]['reg_seq_no'] = str_replace("-","加",$pat_wait[$i]['reg_seq_no']);	
					}
				}else{
					$pat_wait='n';
				}
				$pat['expert'] = $hpt[$m]['expert'];
				$pat['pat_now'] = $pat_now;
				$pat['pat_wait'] = $pat_wait;
				$pat['doctor_postitle'] = M("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_postitle");
				$pat['shortinfo'] = $docinfo[0]['shortinfo'];
				$pat['thumbnail'] = "/mz//Uploads/".$docinfo[0]['thumbnail'];
				$pat['doctor_name'] = $docinfo[0]['doctor_name'];
				$pat['dept_name'] = M("dept_info")->where("dept_code='".$hpt[$m]['dept']."'")->getField("dept_name");
				$pat_list[$m] = $pat;
			}
			//print_r($pat_list);
			$rel['pat'] = $pat_list;
			$this->ajaxReturn($rel,"JSON");
		}
		
		/*
		**一个普诊医生
		*/
		if(count($hpt)==1&&$hpt[0]['expert']==0){
			$rel['expert'] = 0;
			$rel['two_dept'] = false;
			$rel['num'] = 1;
			$rel['have_logout'] = 0;
			$room_info = M("tbl_room_list")->where("room_id=".$hpt[0]['room_id'])->field("real_id,room_name")->select();
			//$rel['room'] = $room_info[0]['room_name'];
			$rel['room_id'] = $room_info[0]['real_id'];
			//$dept_code = substr($hpt[0]['dept'],0,7);
			$dept_code = $hpt[0]['dept'];
			$doctor_code = $hpt[0]['uid'];
			
			$rel['room'] = M("dept_info")->where("dept_code='".$dept_code."'")->getField("dept_name"); 
		
			$docinfo = M("doctor_info")->where("doctor_code='".$doctor_code."'")->field("doctor_name,doctor_postitle")->select();
			
			$pat_now = M("register_assign")->where("dept_code='".$dept_code."' and status=1 and allot_doctor_code='".$doctor_code."' and withdraw_flag='0'")->field("pat_name,reg_seq_no")->order("call_time desc")->limit(1)->select();
			if(count($pat_now)==0){
				$pat_now="n";
			}else{
				$pat_now[0]['reg_seq_no'] = str_replace("-","加",$pat_now[0]['reg_seq_no']);
			}
			
			$pat_wait = M("register_assign")->where("reg_type='普通' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and withdraw_flag='0' and dept_code='".$dept_code."' and fp_doctor_code='".$doctor_code."' and baodao=1")->field("pat_name,reg_seq_no")->order("noon_flag,wait_no desc,weight asc")->limit(getOpVal("wait_num"))->select();
			for($i=0;$i<count($pat_wait);$i++){
				$pat_wait[$i]['reg_seq_no'] = str_replace("-","加",$pat_wait[$i]['reg_seq_no']);	
			}
			if(count($pat_wait)>0){
			}else{
				$pat_wait="n";
			}
			$rel['pat_wait'] = $pat_wait;
			
			$rel['pat_now'] = $pat_now;
			$rel['shortinfo'] = $docinfo[0]['shortinfo'];
			$rel['have_logout'] = 0;
			$rel['doctor_name'] = $docinfo[0]['doctor_name'];
			$rel['dept_name'] = M("dept_info")->where("dept_code='".$hpt[0]['dept']."'")->getField("dept_name");
			$this->ajaxReturn($rel,"JSON");
		}
		/*
		**两个普诊医生
		*/
		if(count($hpt)==2&&$hpt[0]['expert']==0){
			$rel['expert'] = 0;
			$rel['two_dept'] = false;
			$rel['num'] = 2;
			$rel['have_logout'] = 0;
			$room_info = M("tbl_room_list")->where("room_id=".$room)->field("real_id,room_name")->select();
			//$rel['room_name'] = $room_info[0]['room_name'];
			$rel['room'] = M("client_uid")->join(" dept_info on dept_info.dept_code=client_uid.dept")->where("uid='".$hpt[0]['uid']."'")->getField("dept_info.dept_name");
			$rel['room_id'] = $room_info[0]['real_id'];
			$pat_list = array();
			for($m=0;$m<count($hpt);$m++){
				$pat="";
				$doctor_code = $hpt[$m]['uid'];
				$dept_code = $hpt[$m]['dept'];
				$docinfo = M("doctor_info")->where("doctor_code='".$doctor_code."'")->field("doctor_name,doctor_postitle")->select();
				if($hpt[$m]['expert']==1){
					$pat_now = M("register_assign")->where("allot_doctor_code='".$doctor_code."' and reg_type='专家' and status=1 and dept_code='".$dept_code."' and withdraw_flag='0' and baodao=1")->field("pat_name,reg_seq_no")->order("call_time desc")->limit(1)->select(); 
				}else{
					$pat_now = M("register_assign")->where("allot_doctor_code='".$doctor_code."' and reg_type='普通' and status=1 and dept_code='".$dept_code."' and withdraw_flag='0' and baodao=1")->field("pat_name,reg_seq_no")->order("call_time desc")->limit(1)->select(); 
				}
				if(count($pat_now)==0){
					$pat_now='n';
				}else{
					$pat_now[0]['reg_seq_no'] = str_replace("-","加",$pat_now[0]['reg_seq_no']);
				}
				if($hpt[$m]['expert']==1){
					$pat_wait = M("register_assign")->where("doctor_code='".$doctor_code."' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and reg_type='专家' and dept_code='".$dept_code."' and fp_doctor_code='".$doctor_code."' and baodao=1 and withdraw_flag='0'")->field("pat_name,reg_seq_no")->order("noon_flag,wait_no desc,weight asc")->limit(3)->select();
				}else{
					$pat_wait = M("register_assign")->where("((status=5 and is_jz=1) or (status=0 and is_jz=0)) and reg_type='普通' and dept_code='".$dept_code."' and fp_doctor_code='".$doctor_code."' and baodao=1 and withdraw_flag='0'")->field("pat_name,reg_seq_no")->order("noon_flag,wait_no desc,weight asc")->limit(getOpVal("wait_num"))->select();
				}
				if(count($pat_wait)==0){
					$pat_wait="n";
				}else{
					/*for($i=0;$i<count($pat_wait);$i++){
						$pat_wait[$i]['reg_seq_no'] = str_replace("-","加",$pat_wait[$i]['reg_seq_no']);	
					}*/
				}
				$pat['pat_wait'] = $pat_wait;
				$pat['expert'] = $hpt[$m]['expert'];
				$pat['pat_now'] = $pat_now;
				$pat['shortinfo'] = $docinfo[0]['shortinfo'];
				$pat['thumbnail'] = "/Uploads/".$docinfo[0]['thumbnail'];
				$pat['doctor_name'] = $docinfo[0]['doctor_name'];
				$pat['dept_name'] = M("dept_info")->where("dept_code='".$hpt[$m]['dept']."'")->getField("dept_name");
				$pat_list[$m] = $pat;
			}
			//print_r($pat_list);
			$rel['pat'] = $pat_list;
			 $this->ajaxReturn($rel,"JSON");
		}
		
		
	}else{
		$rel['have_logout'] = 1;
		 $this->ajaxReturn($rel,"JSON");
	}
	
   
}

function tanchuang(){
	/*
	**获取最新的诊室患者呼叫信息
	*/
	$room = I("post.room");
	$lis = M("client_uid")->where("room_id='".$room."'")->field("uid")->select();
	for($i=0;$i<count($lis);$i++){
		if($i==0){
			$doctor_code = $lis[$i]['uid'];
		}else{
			$doctor_code .=",". $lis[$i]['uid'];
		}
	}
	$pat_now_call = M("register_assign")->join(" pat_now on pat_now.pat_code=register_assign.reg_id")->where(" register_assign.allot_doctor_code in(".$doctor_code.") and status=1 and withdraw_flag='0'")->order("call_time desc")->field("reg_seq_no,pat_name")->select();
	
	if($pat_now_call!=""){
		$pat_now_call[0]['reg_seq_no'] = str_replace("-","加",$pat_now_call[0]['reg_seq_no']);
		$rel['pat_now_call'] = $pat_now_call;
		
	}else{
		$rel['pat_now_call'] = "n";
	}
	$this->ajaxReturn($rel,"JSON");
}

public function getDec(){
	$room = I("post.room");
	$pat = array();
	$hpt = M("client_uid")->where("room_id='".$room."'")->select();
	
	if(count($hpt)>0){
		$doctor_code = $hpt[0]['uid'];
		$docinfo = M("doctor_info")->where("doctor_code='".$doctor_code."'")->select();
		$pat['description'] = html_entity_decode(stripslashes(htmlspecialchars_decode($docinfo[0]['description'])), ENT_QUOTES, 'UTF-8');
		
		
	}else{
		$pat['description'] = " ";
	}
	
    $this->ajaxReturn($pat,"JSON");
}

}