<?php
namespace Home\Controller;
use Think\Controller;
class BigScreenController extends Controller {
public function index(){
  $this->id =I("get.id");
  $this->info = M("settxt")->where("id=1")->getField("con");
  //数据轮询间隔
  $this->ftimes = M("tbl_op")->where("op_name='".ftime."'")->getField("op_val");
  $this->display();
}
public function getBigScreenInfo(){
	
	$sid = I("post.id");
	$room_ary = M("zhenqu")->where("BigScreenID='".$sid."'")->getField("room");
	$where = "room_id in(".$room_ary.")";
	
		$room_list2 = M()->Table("client_uid")->where($where)->order("dept,room_id")->select();
		$room_list = array();
		$z=0;
		for($i=0;$i<count($room_list2);$i++){
			//$dept_txt = substr($room_list2[$i]['dept'],0,strlen($room_list2[$i]['dept'])-1);
			$dept_txt = $room_list2[$i]['dept'];
			$dept_ary = explode(",",$dept_txt);
			for($m=0;$m<count($dept_ary);$m++){
				$room_list[$z] = $room_list2[$i];
				$room_list[$z]['dept'] = $dept_ary[$m];
				$z++;
			}
		}
		
		$list = array();
		$plist = array();
	
		if(count($room_list)>6){
			 $last_page_num = count($room_list)%6;
			 $spetor =floor((count($room_list))/6);
			 if(((count($room_list))%6)>0){
				$spetor = $spetor+1;
			 }
		
			 if((session("page")+1)>=$spetor){
					session("page",0);	
			 }else{
				session("page",session("page")+1);
			 }
			 $begin = 6*session("page");
			 if((session("page")+1)==$spetor){
				 if($last_page_num>0){
					$end = $begin+$last_page_num;	
				 }else{
				 	$end = $begin+6;
				 }
					
			 }else{
		     	$end = $begin+6;
			 }
			 
			 for($i=$begin;$i<$end;$i++){
			 	$list[] = $room_list[$i];
			 }
		}else{
			$list = $room_list;
		}
	
				
		
		if(count($list)>0){
			for($i=0;$i<count($list);$i++){
				$pat_wait = "";
				$cinfo = M()->Table("client_uid")->where("room_id=".$list[$i]['room_id']." and uid='".$list[$i]['uid']."'")->field("uid,expert")->select();
				$doctor_code = $cinfo[0]['uid'];
				$pat_list_num = M()->Table("register_assign")->where("doctor_code='".$doctor_code."'")->count();
				$pat = array();
				//当前患者
				if($list[$i]['expert']==1){
					$pat_now = M("register_assign")->where("room='".$list[$i]['room_id']."' and dept_code='".$list[$i]['dept']."' and doctor_code='".$doctor_code."' and reg_type='专家' and status=1 and withdraw_flag='0' and baodao=1")->order("call_time desc")->limit(1)->field("pat_name,reg_seq_no")->select(); 	
				}else{
					$pat_now = M("register_assign")->where("room='".$list[$i]['room_id']."' and dept_code='".$list[$i]['dept']."' and allot_doctor_code='".$doctor_code."' and status=1 and withdraw_flag='0' and baodao=1 and reg_type='普通'")->order("call_time desc")->limit(1)->field("pat_name,reg_seq_no")->select();
				}
				
				if($pat_now!=""){
					$pat_now[0]['reg_seq_no'] = str_replace("-","加",$pat_now[0]['reg_seq_no']);
				}
			
				//等候患者
				$pat_wait = M("register_assign")->where("withdraw_flag=0 and doctor_code='".$doctor_code."' and reg_type='专家' and dept_code='".$list[$i]['dept']."' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and baodao=1")->order("noon_flag,weight asc")->limit(getOpVal("wait_num"))->field("reg_seq_no,pat_name")->select();
				//echo M("register_assign")->where("withdraw_flag=0 and doctor_code='".$doctor_code."' and reg_type='专家' and dept_code='".$list[$i]['dept']."' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and baodao=1")->order("weight asc")->limit("2")->select(false);
				
				
				for($n=0;$n<count($pat_wait);$n++){
						$pat_wait[$n]['reg_seq_no'] = str_replace("-","加",$pat_wait[$n]['reg_seq_no']);
				}
				
					
				$pat['pat_now'] = $pat_now;
					
					
				if($list[$i]['expert']==1){
					$pat['level'] = "专家";
					if($list[$i]['is_over']==1){//如果停诊
						$doc_name = M()->Table("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_name");
						$pat['doctor_name']= $doc_name."<font color='yellow'>停诊</font>"; 
					}else{
						$pat['doctor_name'] = M()->Table("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_name");
					}
					
					$pat['pat_wait'] = $pat_wait;
				}else{
					$pat['level'] = "普通";
					$pat_wait = M("register_assign")->where("dept_code='".$list[$i]['dept']."' and reg_type='普通' and withdraw_flag='0' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and baodao=1 and fp_doctor_code='".$doctor_code."' and (doctor_code='' or doctor_code='".$doctor_code."')")->order("noon_flag,wait_no desc,weight asc")->limit(getOpVal("wait_num"))->field("reg_seq_no,pat_name")->select();
					for($n=0;$n<count($pat_wait);$n++){
						$pat_wait[$n]['reg_seq_no'] = str_replace("-","加",$pat_wait[$n]['reg_seq_no']);
					}
					$pat['pat_wait'] = $pat_wait;
					if($list[$i]['is_over']==1){//如果停诊
						$doc_name = M()->Table("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_name");
						$pat['doctor_name']= $doc_name."<font color='yellow'>停诊</font>"; 
					}else{
						$pat['doctor_name']=M()->Table("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_name"); 
					}
					
				}
				
				$pat['room'] =M("tbl_room_list")->where("room_id=". $list[$i]['room_id'])->getField("real_id");
				$pat['dept_name'] = M("dept_info")->where("dept_code='".$list[$i]['dept']."'")->getField("dept_name");
				$plist['pat_list'][]=$pat;
				
			}	
		}else{
			$plist['pat_list']="n";
	
			$plist['sid'] = $sid;
		}
		
		$this->ajaxReturn($plist,"JSON");
			
}
public function getPrice(){
	//物价
	$sid = I("post.id");

	$dept_txt = M("zhenqu")->where("BigScreenID='".$sid."'")->getField("dept");

	$dept_ary = explode(",",$dept_txt);
	$price = array();
	for($i=0;$i<count($dept_ary);$i++){
		$price[] = M("wujia")->where("find_in_set('".$dept_ary[$i]."',dept_code)")->select();
		//echo M("wujia")->where("dept_code in (".$dept_ary[$i].")")->select(false);
	}
	
	if(count($price)>0){
		$rel['success']  =1;
		$rel['price'] = $price;
	}else{
		$rel['success']  =0;
	}
	$this->ajaxReturn($rel,"JSON");
}
public function tanchuang(){
	$sid = I("post.id");
	$plist['sid'] = $sid;
	
	$now_calling2 = M("pat_now")->order("id asc")->field("id,pat_code,dept_code")->select();
		$now_calling = M("register_assign")->where("reg_id='".$now_calling2[0]['pat_code']."'")->field("reg_id,reg_seq_no,room,pat_name")->select(); 
		if($now_calling){
			$now_calling[0]['reg_seq_no'] = str_replace("-","加",$now_calling[0]['reg_seq_no']);
		}
		$room_txt_arys = M("tbl_room_list")->where("room_id=".$now_calling[0]['room'])->field("dept_name,room_name,real_id")->select();
		$now_calling[0]['room_txt'] = M("dept_info")->where("dept_code='".$now_calling2[0]['dept_code']."'")->getField("dept_name");
		$now_calling[0]['room_id'] = $room_txt_arys[0]['real_id'];
		$plist['now_calling'] = $now_calling;
		if($now_calling[0]['room_txt']!=null&&$now_calling[0]['room_txt']!=""){
			$zq_list = M("zhenqu")->select();
			for($i=0;$i<count($zq_list);$i++){
				$room_list = explode(",",$zq_list[$i]['room']);
				if(in_array($now_calling[0]['room'],$room_list)){
					$plist['sid_now'] = $zq_list[$i]['BigScreenID'];
					break;
				}
			}
			
		}else{
			M("pat_now")->where("id=".$now_calling2[0]['id'])->delete();
			$plist['sid_now'] = "n";
		}
		$this->ajaxReturn($plist,"JSON");
		//最新病人
			
}
public function setCalls(){
	$pat_code = I("post.pat_code");
	//$data['have_call'] = 1;
	if(M("pat_now")->where("pat_code='".$pat_code."'")->delete()){
		
		$rel['success'] = 1;
	}else{
		$rel['success'] = 0;
	}
	//echo M("pat_now")->where("pat_code='".$pat_code."' and noon_flag='".$noon_flag."'")->select(false);
	$this->ajaxReturn($rel,"JSON");
}

public function AjaxGetGuoHao(){
	$sid = I("post.sid");
	$room_txt = M("zhenqu")->where("BigScreenID='".$sid."'")->getField("room");
	$room_ary = explode(",",$room_txt);
	$dept_ary = array();
	for($i=0;$i<count($room_ary);$i++){
		$myc = M("client_uid")->where("room_id='".$room_ary[$i]."'")->field("dept")->select();	
		if($myc){
			for($m=0;$m<count($myc);$m++){
				$dept_ary[] = $myc[$m]['dept'];
			}
		}
	}
	$dept_txt = implode(",",$dept_ary);
	$gh_list = M("register_assign")->where("find_in_set(dept_code,'".$dept_txt."') and status=3 and is_jz=0  and withdraw_flag='0'")->field("reg_seq_no,pat_name")->select();
	
	if(count($gh_list)==0){
		$rel['gh_list'] = "n";
	}else{
		$rel['gh_list'] = $gh_list;
	}
	$this->ajaxReturn($rel,"JSON");
}

}