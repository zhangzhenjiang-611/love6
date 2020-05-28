<?php
//分诊台分配患者规则
function getFpDoctor($hd,$doctor_code){ 
	$fp_doctor_code="";
	$fp_ary = array();
	if(trim($doctor_code)!=""){
		$fp_doctor_code=$doctor_code;
	}else{
		
		$login_doctor_ary = M("client_uid")->where("dept='".$hd[0]['dept_code']."' and expert=0 and is_over=0")->order("login_time asc")->select();

		for($i=0;$i<count($login_doctor_ary);$i++){
			//等候患者人数
			M()->query("update register_assign set fp_doctor_code='',fp_doctor_name='' where reg_id='".$hd[0]['reg_id']."'");
			$fp_num = M("register_assign")->where("fp_doctor_code='".$login_doctor_ary[$i]['uid']."' and baodao=1 and ((is_jz=1 and status=5) or (is_jz=0 and status=0))")->count();
			
			/*if($fp_num==0){
				$fp_doctor_code=$login_doctor_ary[$i]['uid'];
				$rel['room'] = $login_doctor_ary[$i]['room_id'];
				unset($fp_ary);
				break;
			}else{*/
				if($fp_num<3){
					//判定医生最近呼叫的患者数量
					/*$have_call_min = M("register_assign")->where("allot_doctor_code='".$login_doctor_ary[$i]['uid']."'")->count();
					if($have_call_min){
						$fp_num = $fp_num + $have_call_min;
					}*/
					$fp_ary[] = $login_doctor_ary[$i]['uid']."|".$fp_num."|".$login_doctor_ary[$i]['room_id'];
				}	
			/* } */
		}
		
		if(count($fp_ary)>0){
			$min_tmp = 1000;
			for($n=0;$n<count($fp_ary);$n++){
				$fp2_ary = explode("|",$fp_ary[$n]);
				$num2 = $fp2_ary[1];
				$uid = $fp2_ary[0];
				if($num2<$min_tmp){
					$min_tmp = $num2;
				}
			}
			$c_ary = array();
			for($n=0;$n<count($fp_ary);$n++){
				$fp2_ary = explode("|",$fp_ary[$n]);
				$num2 = $fp2_ary[1];
				$uid = $fp2_ary[0];
				if($min_tmp==$num2){
					/*$fp_doctor_code = $uid;
					$rel['room'] = $fp2_ary[2];
					*/
					$c_ary[] = $uid;
				}
			}
			//取医生已呼叫患者数量
			$p_min_num=1000;
			for($n=0;$n<count($c_ary);$n++){
				$p_num = M("register_assign")->where("fp_doctor_code='".$c_ary[$n]."' and withdraw_flag='0'")->order("call_time desc")->limit(1)->getField("reg_seq_no");
				
				if($p_num<$p_min_num){
					$p_min_num = $p_num;
				}
			}
			
			for($n=0;$n<count($c_ary);$n++){
				$p_num = M("register_assign")->where("fp_doctor_code='".$c_ary[$n]."'  and withdraw_flag='0'")->order("call_time desc")->limit(1)->getField("reg_seq_no");
			
				if($p_num==$p_min_num){
					$fp_doctor_code = $c_ary[$n];
					break;
				}
			}
			
			
		}
	}

	$rel['room'] = M("client_uid")->where("uid='".$fp_doctor_code."'")->getField("room_id");
	$rel['fp_doctor_code'] = $fp_doctor_code;
	return  $rel; 	
}


?>