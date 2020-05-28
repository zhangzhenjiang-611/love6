<?php
header("Content-Type: text/html; charset=gb2312"); 
include_once("ez_sql_core.php");
include_once("ez_sql_mysql.php");
include_once("ez_sql_mssql.php");
include_once("Socket.php");	
include_once("Log.php");	
class call{

public function login($room,$doctor_code,$dept_code,$expert){
	$db = new ezSQL_mysql('root','trans','menzhen1','172.168.0.241','gb2312');
	$db->query("set names utf8");
	$dc_ary = $db->get_row("select doctor_name from doctor_info where doctor_code='".$doctor_code."'");
	$doctor_name = $dc_ary->doctor_name;
	if($doctor_name){
		$op_message = "登录成功";
	}else{
		$op_message = "登录失败";
	}
	//入库
	$have_login = $db->get_row("select id from client_uid where uid='".$doctor_code."' and dept='".$dept_code."' and expert=".$expert);
	if($have_login->id!=""){
		$sql = "update client_uid set room_id='".$room."',ip='".$_SERVER["REMOTE_ADDR"]."',dept='".$dept_code."',login_time='".date("Y-m-d H:i:s")."' where uid='".$doctor_code."'";	
	}else{
		$sql = "insert into client_uid (uid,room_id,ip,login_type,dept,expert,login_time) values ('".$doctor_code."','".$room."','".$_SERVER["REMOTE_ADDR"]."','医生','".$dept_code."','".$expert."','".date("Y-m-d H:i:s")."')";
	}
	$login_status = $db->query($sql);
	$log = new Log();
	if($login_status){
		Log::write("登录传入参数 -- 房间号:".$room." 医生编码:".$doctor_code." 科室编码:".$dept_code." 是否专家:".$expert."\r\n登录成功SQL：".$sql."\r\n---------------------------------------------------------------------------------------------------------------------");
		
		$success=1;
	}else{
		$success=0;
		
		Log::write("登录传入参数 -- 房间号:".$room." 医生编码:".$doctor_code." 科室编码:".$dept_code." 是否专家:".$expert."\r\n登录失败SQL：".$sql."\r\n---------------------------------------------------------------------------------------------------------------------");
	}
	
	
	if($expert==0){
		//首先确定自己已经分配了几名患者
		$my_fnum = $db->get_row("select count(*) as num from register_assign where fp_doctor_code='".$doctor_code."' and dept_code='".$dept_code."' and reg_type='普通' ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and withdraw_flag=0");
		$get_fp_ary = $db->get_row("select op_val from tbl_op where op_name='wait_num'");
		$my_fnum_cha = ($get_fp_ary->op_val)-($my_fnum->num);
		
		//$my_fnum_cha = $get_fp_num-$my_fnum->num;
		$sql2="select * from register_assign where fp_doctor_code='' and baodao=1 and reg_type='普通' and withdraw_flag='0'  and dept_code='".$dept_code."' order by noon_flag,wait_no desc,weight asc limit ".$my_fnum_cha;
		$lg1 = $db->get_results($sql2);
		foreach($lg1 as $val){
			$db->query("update register_assign set room='".$room."',fp_doctor_code='".$doctor_code."',fp_doctor_name='".$doctor_name."' where reg_id='".$val->reg_id."'");
		}
	}
	$patList = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root>";
	$patList .= "<Message>";
	$patList .= "<Success>".$success."</Success>"; 
	$patList .= "</Message>";
	$patList.="</root>";
	/**
	**插入记录数据
	***/
	$sql_insert = "insert into doctor_op_records (`room`,`doctor_code`,`doctor_name`,`expert`,`pat_code`,`pat_name`,`dept_code`,`op_name`,`op_message`,`op_data`,`op_time`) values ('".$room."','".$doctor_code."','".$doctor_name."',".$expert.",'','','".$dept_code."','医生上线','".$op_message."','".$patList."','".date("Y-m-d H:i:s")."')";
	$db->query($sql_insert);
	return $patList;
	
	//return $this->getPatList($doctor_code,$dept_code,$expert);
}
public function logout($room,$doctor_code,$dept_code,$expert){
	$db = new ezSQL_mysql('root','trans','menzhen1','172.168.0.241','gb2312');
	$db->query("set names utf8");
	$dc_ary = $db->get_row("select doctor_name from doctor_info where doctor_code='".$doctor_code."'");
	$doctor_name = $dc_ary->doctor_name;
	$sql = "delete from client_uid where uid='".$doctor_code."' and room_id='".$room."' and dept='".$dept_code."' and expert=".$expert; 
	$db->query($sql);
	$patList = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root>";
	$patList .= "<Message>";
	$patList .= "<Success>0</Success>";
	$patList .= "</Message>";
	$patList.="</root>";
	Log::write("下线传递参数 - 医生编号：".$doctor_code." 执行SQL：".$sql."\r\n---------------------------------------------------------------------------------------------------------------------");
	/**
	**插入记录数据
	***/
	$sql_insert = "insert into doctor_op_records (`room`,`doctor_code`,`doctor_name`,`expert`,`pat_code`,`pat_name`,`dept_code`,`op_name`,`op_message`,`op_data`,`op_time`) values ('".$room."','".$doctor_code."','".$doctor_name."',".$expert.",'','','".$dept_code."','医生下线','".$op_message."','".$patList."','".date("Y-m-d H:i:s")."')";
	$db->query($sql_insert);
	return $patList;
}
public function tingzhen($doctor_code){
	$db = new ezSQL_mysql('root','trans','menzhen1','172.168.0.241','gb2312');
	$db->query("set names utf8");
	$sql = "update client_uid set is_over=1 where uid='".$doctor_code."'";
	$db->query($sql);
	if($db){
		$success = 1;
	}else{
		$success = 0;
	}
	
	$patList = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root>";
	$patList .= "<Message>";
	$patList .= "<Success>".$success."</Success>";
	$patList .= "</Message>";
	$patList.="</root>";
	return patList;
}
public function shunhu($room,$doctor_code,$dept_code,$expert){
	$db = new ezSQL_mysql('root','trans','menzhen1','172.168.0.241','gb2312');
	$db->query("set names utf8");
	$show_room_ary = $db->get_row("select real_id from tbl_room_list where room_id=".$room);
	$show_room_txt = $show_room_ary->real_id;
	
	if($expert==1){//专家
		$where = "doctor_code='".$doctor_code."' and reg_type='专家' and dept_code='".$dept_code."'";
	}else{
		$where = "dept_code='".$dept_code."' and reg_type='普通' and fp_doctor_code='".$doctor_code."'";
	}
	
	$zq_list = $db->get_results("select * from zhenqu");
	foreach($zq_list as $val){
		$room_ars = explode(",",$val->room);
		if(in_array($room,$room_ars)){
			$sid = $val->BigScreenID;
			break;
		}
	}
	
	$dc_ary = $db->get_row("select doctor_name from doctor_info where doctor_code='".$doctor_code."'");
	$doctor_name = $dc_ary->doctor_name;
	
	$sql = "select * from register_assign where ".$where." and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and withdraw_flag='0' and baodao=1 order by noon_flag,wait_no desc,weight asc";
	$pat_call = $db->get_row($sql);
	if($pat_call){
		$op_message = "顺呼成功";
		$db->query("update register_assign set wait_no=0 where reg_id='".$pat_call->reg_id."'");
		//自动过号部分开始
		$db1 = new ezSQL_mssql('sa', 'Founder123', 'chisdb_dev', '172.168.0.142');
		$zd_sql = "select reg_id from register_assign where status=1 and is_jz=0 and withdraw_flag='0'  and ".$where." order by call_time desc limit 1";
		//Log::write("自动过号SQL：".$zd_sql);
		$pat_two = $db->get_row($zd_sql);
		/*$cz_reg_id = $pat_two->reg_id;
		$cz_sql = "select is_jz from view_patient_registration where reg_id='".$cz_reg_id."'";
		$cz_ary = $db1->get_row($cz_sql);
		if($cz_ary->is_jz==1){
			$up_sql = "update register_assign set is_jz=1 where reg_id='".$cz_reg_id."'";
			$db->query($up_sql);
		}else{
			$db->query("update register_assign set status=3 where reg_id='".$pat_two->reg_id."'");
		}*/
		
		if($pat_two->is_jz==0){
			$statu=3;
			$db->query("update register_assign set status=".$statu." where reg_id='".$pat_two->reg_id."'");
		}
		/***自动过号结束***/
	}else{
		$op_message = "无符合条件的患者,顺呼失败";
	}
	
	Log::write("顺呼传入参数 -- 房间号:".$room." 医生编码:".$doctor_code." 科室编码:".$dept_code." 是否专家:".$expert."\r\n顺呼成功SQL：".$sql."\r\n---------------------------------------------------------------------------------------------------------------------");
	
	if($pat_call){
		 $sql_next = "update register_assign set allot_doctor_code='".$doctor_code."',allot_doctor_name='".$doctor_name."',status=1,call_time='".date("Y-m-d H:i:s")."',room='".$room."' where reg_id='".$pat_call->reg_id."'";
		 $db->query($sql_next);
		 //Log::write("顺呼更新SQL：".$sql_next);
		 
		 //大屏弹窗内容
		 $sql2 = "insert into pat_now (`pat_code`,`dept_code`,`room`) values('".$pat_call->reg_id."','".$dept_code."','".$room."')";
		 $db->query($sql2);
		 
		 if($expert==0){
			//如果已经标识为下线状态
			$off_ary = $db->get_row("select is_over from client_uid where uid='".$doctor_code."' and dept_code='".$dept_code."' and expert=".$expert);
			if($off_ary->is_over==0){
				//首先确定自己已经分配了几名患者
				if($expert==1){
					$reg_type="专家";
				}else{
					$reg_type="普通";
				}
				$my_fnum = $db->get_row("select count(*) as num from register_assign where fp_doctor_code='".$doctor_code."' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and dept_code='".$dept_code."' and reg_type='".$reg_type."' and withdraw_flag=0");
				$get_fp_ary = $db->get_row("select op_val from tbl_op where op_name='wait_num'");
				$my_fnum_cha = ($get_fp_ary->op_val)-($my_fnum->num);
				if($my_fnum_cha<0){
					$my_fnum_cha=0;
				}
				$sql2="select * from register_assign where fp_doctor_code='' and withdraw_flag='0' and  dept_code='".$dept_code."' and baodao=1 and reg_type='".$reg_type."' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) order by noon_flag,wait_no desc,weight asc limit ".$my_fnum_cha;
				Log::write("抓取患者SQL：".$sql2."第一".$get_fp_ary->op_val."第二".$my_fnum->num); 
				
				
				$lg1 = $db->get_results($sql2);
				if($lg1){
					foreach($lg1 as $val){
						$db->query("update register_assign set room='".$room."',fp_doctor_code='".$doctor_code."',fp_doctor_name='".$doctor_name."',wait_no=1 where reg_id='".$val->reg_id."'");
					Log::write("抓取患者SQL2："."update register_assign set room='".$room."',fp_doctor_code='".$doctor_code."',fp_doctor_name='".$doctor_name."' where reg_id='".$val->reg_id."'");  
					}
				}
				
			}
			
		}
		 if($pat_call->reg_seq_no<0){
			$reg_seq_no = str_replace("-","加",$pat_call->reg_seq_no);
		 }else{
			$reg_seq_no = $pat_call->reg_seq_no;	
		 }
		 $pat_name = str_replace("·","",$pat_call->pat_name);
		 $dept_name_ary = $db->get_row("select dept_name from dept_info where dept_code='".$dept_code."'");
		 if($expert==1){
		 	
			//获取专家下一位等候患者
			/*$sql_wait = "select * from register_assign where ".$where." and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and withdraw_flag='0' and baodao=1 order by weight asc";
			$pat_wait = $db->get_row($sql_wait);
		    
			if($pat_wait){
				if($pat_wait->reg_seq_no<0){
				$reg_seq_no_wait = str_replace("-","加",$pat_wait->reg_seq_no);
				 }else{
					$reg_seq_no_wait = $pat_wait->reg_seq_no;	
				 }
				self::SendVoice("请".$dept_name_ary->dept_name.$doctor_name."专家".$reg_seq_no."号".$pat_name."到第".$room."诊室就诊,"."请".$reg_seq_no_wait."号".$pat_name."到门外等候",$sid);
			
			}else{
				self::SendVoice("请".$dept_name_ary->dept_name.$doctor_name."专家".$reg_seq_no."号".$pat_name."到第".$room."诊室就诊",$sid);
			}*/
			self::SendVoice("请".$dept_name_ary->dept_name.",".$doctor_name."专家,".$reg_seq_no."号".$pat_name.",到第".$show_room_txt."诊室就诊",$sid);
			
		 }else{
		 	self::SendVoice("请".$dept_name_ary->dept_name.",".$reg_seq_no."号".$pat_name.",到第".$show_room_txt."诊室就诊",$sid); 
		 }
		 
		 $success=1;
	}else{
		/*如何当前没有分配有患者则去主动去查询是否有符合规则的患者，如果有就直接分过来 
		  *修改日期：2016-09-28
		*/
		if($expert==1){
			$reg_type = "专家";
			
		}else{
			$reg_type = "普通";	
		}
		$sql = "select * from register_assign where dept_code='".$dept_code."' and reg_type='".$reg_type."' and fp_doctor_code='' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and withdraw_flag='0' and baodao=1 order by noon_flag,weight asc";
		$h_ve = $db->get_row($sql);
		if($h_ve){
			 $sql_next = "update register_assign set allot_doctor_code='".$doctor_code."',allot_doctor_name='".$doctor_name."',status=1,call_time='".date("Y-m-d H:i:s")."',room='".$room."' where reg_id='".$h_ve->reg_id."'";
			  $db->query($sql_next);
		      //大屏弹窗内容
			 $sql2 = "insert into pat_now (`pat_code`,`dept_code`,`room`) values('".$h_ve->reg_id."','".$dept_code."','".$room."')";
		 	 $db->query($sql2);
		 
			 if($expert==0){
				//如果已经标识为下线状态
				$off_ary = $db->get_row("select is_over from client_uid where uid='".$doctor_code."' and dept_code='".$dept_code."' and expert=".$expert);
				if($off_ary->is_over==0){
					//首先确定自己已经分配了几名患者
					if($expert==1){
						$reg_type="专家";
					}else{
						$reg_type="普通";
					}
					$my_fnum = $db->get_row("select count(*) as num from register_assign where fp_doctor_code='".$doctor_code."' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) and dept_code='".$dept_code."' and reg_type='".$reg_type."' and withdraw_flag=0");
					$get_fp_ary = $db->get_row("select op_val from tbl_op where op_name='wait_num'");
					$my_fnum_cha = ($get_fp_ary->op_val)-($my_fnum->num);
					if($my_fnum_cha<0){
						$my_fnum_cha=0;
					}
					$sql2="select * from register_assign where fp_doctor_code='' and withdraw_flag='0' and  dept_code='".$dept_code."' and baodao=1 and reg_type='".$reg_type."' and ((status=5 and is_jz=1) or (status=0 and is_jz=0)) order by noon_flag,wait_no desc,weight asc limit ".$my_fnum_cha;
					Log::write("抓取患者SQL：".$sql2."第一".$get_fp_ary->op_val."第二".$my_fnum->num); 
					$lg1 = $db->get_results($sql2);
					if($lg1){
						foreach($lg1 as $val){
							$db->query("update register_assign set room='".$room."',fp_doctor_code='".$doctor_code."',fp_doctor_name='".$doctor_name."',wait_no=1 where reg_id='".$val->reg_id."'");
						Log::write("抓取患者SQL2："."update register_assign set room='".$room."',fp_doctor_code='".$doctor_code."',fp_doctor_name='".$doctor_name."' where reg_id='".$val->reg_id."'");  
						}
					}
					
				}
				
			}
				 if($h_ve->reg_seq_no<0){
				$reg_seq_no = str_replace("-","加",$h_ve->reg_seq_no);
			 }else{
				$reg_seq_no = $h_ve->reg_seq_no;	
			 }
			 $pat_name = str_replace("·","",$h_ve->pat_name);
			 $dept_name_ary = $db->get_row("select dept_name from dept_info where dept_code='".$dept_code."'");
			 if($expert==1){
				self::SendVoice("请".$dept_name_ary->dept_name.",".$doctor_name."专家,".$reg_seq_no."号".$pat_name.",到第".$show_room_txt."诊室就诊",$sid);
				
			 }else{
				self::SendVoice("请".$dept_name_ary->dept_name.",".$reg_seq_no."号".$pat_name.",到第".$show_room_txt."诊室就诊",$sid); 
			 }
			 
			 $success=1;
		}else{
			$success=0;
		}
		
		$success=0;
	}
	
	
	
	$patList = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root>";
	$patList .= "<Message>";
	$patList .= "<Success>".$success."</Success>"; 
	$patList .= "</Message>";
	$patList.="</root>";
	
	/**
	**插入记录数据
	***/
	$sql_insert = "insert into doctor_op_records (`room`,`doctor_code`,`doctor_name`,`expert`,`reg_seq_no`,`reg_id`,`pat_code`,`pat_name`,`dept_code`,`op_name`,`op_message`,`op_data`,`op_time`) values ('".$room."','".$doctor_code."','".$doctor_name."',".$expert.",".$pat_call->reg_seq_no.",'".$pat_call->reg_id."','".$pat_call->pat_code."','".$pat_call->pat_name."','".$dept_code."','顺呼','".$op_message."','".$patList."','".date("Y-m-d H:i:s")."')";
	$db->query($sql_insert);
	return $patList;
}

public function chonghu($room,$doctor_code,$dept_code,$expert){
	$db = new ezSQL_mysql('root','trans','menzhen1','172.168.0.241','gb2312');
	$db->query("set names utf8");
	$show_room_ary = $db->get_row("select real_id from tbl_room_list where room_id=".$room);
	$show_room_txt = $show_room_ary->real_id;
	if($expert==1){//专家
		$where = "doctor_code='".$doctor_code."' and reg_type='专家' and dept_code='".$dept_code."'";
	}else{
		$where = "dept_code='".$dept_code."' and reg_type='普通' and fp_doctor_code='".$doctor_code."'";
	}
	
	$zq_list = $db->get_results("select * from zhenqu");
	foreach($zq_list as $val){
		$room_ars = explode(",",$val->room);
		if(in_array($room,$room_ars)){
			$sid = $val->BigScreenID;
			break;
		}
		
		/*if(strpos($val->room,$room)!==false){
			$sid = $val->BigScreenID;
			break;
		}*/   
	}
	
	$sql_pat_call = "select * from register_assign where ".$where." and status=1 order by call_time desc";
	$pat_call = $db->get_row($sql_pat_call);
	Log::write("\r\n重呼参数--房间编号:".$room." 医生编号:".$doctor_code." 科室编号:".$dept_code."是否专家:".$expert."\r\n执行SQL：".$sql_pat_call."\r\n -------------------------------------------------------------------------------------------------------------------");
	if($pat_call){
		$op_message = "重呼成功";
		 //大屏弹窗内容
		 $sql_tanchuang = "insert into pat_now (`pat_code`,`dept_code`,`room`) values('".$pat_call->reg_id."','".$dept_code."','".$room."')";
		// Log::write("弹窗SQL：".$sql_tanchuang);
		 $db->query($sql_tanchuang);
		 if($pat_call->reg_seq_no<0){
			$reg_seq_no = str_replace("-","加",$pat_call->reg_seq_no);
		 }else{
			$reg_seq_no = $pat_call->reg_seq_no;
		}
		$pat_name = str_replace("·","",$pat_call->pat_name);
		$pat_name = str_replace("·","",$pat_name);
		$pat_name = str_replace("-","",$pat_name);
		$pat_name = str_replace(".","",$pat_name);
		$pat_name = str_replace("。","",$pat_name);
		$dept_name_ary = $db->get_row("select dept_name from dept_info where dept_code='".$dept_code."'");
		
		$dc_ary = $db->get_row("select doctor_name from doctor_info where doctor_code='".$doctor_code."'");
		$doctor_name = $dc_ary->doctor_name;
		
		 if($expert==1){
		 	self::SendVoice("请".$dept_name_ary->dept_name.$doctor_name."专家,".$reg_seq_no."号".$pat_name.",到第".$show_room_txt."诊室就诊",$sid);
		 }else{
		 	self::SendVoice("请".$dept_name_ary->dept_name.$reg_seq_no."号,".$pat_name.",到第".$show_room_txt."诊室就诊",$sid);
		 }
		
		
		 
		$success=1;
	}else{
		$op_message = "重呼失败";
		$success=0;
	}
	 $patList = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root>";
	 $patList .= "<Message>";
	 $patList .= "<Success>".$success."</Success>";
	 $patList .= "</Message>";
	 $patList.="</root>";
	 /**
	**插入记录数据
	***/
	$sql_insert = "insert into doctor_op_records (`room`,`doctor_code`,`doctor_name`,`expert`,`reg_seq_no`,`reg_id`,`pat_code`,`pat_name`,`dept_code`,`op_name`,`op_message`,`op_data`,`op_time`) values ('".$room."','".$doctor_code."','".$doctor_name."',".$expert.",'".$pat_call->reg_seq_no."',".$pat_call->reg_id.",'".$pat_call->pat_code."','".$pat_call->pat_name."','".$dept_code."','重呼','".$op_message."','".$patList."','".date("Y-m-d H:i:s")."')";
	$db->query($sql_insert);
	return $patList;
}
public function getPatList($doctor_code,$dept_code,$expert){
	$db = new ezSQL_mysql('root','trans','menzhen','172.168.0.241','gb2312');
	$db->query("set names utf8");
	/****
	$client_ary = $db->get_row("select room_id from client_uid where uid='".$doctor_code."' and dept='".$dept_code."' and expert=".$expert);
	$room = $client_ary->room_id;
	$dc_ary = $db->get_row("select doctor_name from doctor_info where doctor_code='".$doctor_code."'");
	$doctor_name = $dc_ary->doctor_name;
	*****/
	if($expert==1){//专家
		$where = "doctor_code='".$doctor_code."' and reg_type='专家' and dept_code='".$dept_code."'";
	}else{
		$where = "dept_code='".$dept_code."' and reg_type='普通' and fp_doctor_code='".$doctor_code."'";
	}
	$sql1="select * from register_assign where ".$where." and status=1 and withdraw_flag=0 and baodao=1  order by call_time desc";
	// $sql2="select * from register_assign where ".$where." and ((is_jz=1 and status=5) or (status=0 and is_jz=0)) and baodao=1 and withdraw_flag='0' order by weight asc";
	$sql2="select * from register_assign where ".$where." and ((is_jz=1 and status=5) or (status=0 and is_jz=0)) and baodao=1 and withdraw_flag='0' order by noon_flag,wait_no desc,weight asc";
	if($expert==1){
		$sql3 = "select reg_id from view_patient_registration  where dept_code='".$dept_code."' and reg_type='".iconv("utf8","gbk","专家")."' and doctor_code='".$doctor_code."' and is_jz=0 and withdraw_flag='0'";
	}else{
		$sql3 = "select reg_id from view_patient_registration  where dept_code='".$dept_code."' and reg_type='".iconv("utf8","gbk","普通")."' and is_jz=0 and withdraw_flag='0'"; 
	}
	
	$list1 = $db->get_row($sql1);
	$list2 = $db->get_results($sql2);
	//$list3 = $db->get_results($sql3);
	$db1 = new ezSQL_mssql('sa', 'Founder123', 'chisdb_dev', '172.168.0.142');  
	$list3 = $db1->get_results($sql3);
	//Log::write("\r\n请求患者列表参数-- 医生编号:".$doctor_code." 科室编号:".$dept_code." 是否专家:".$expert."\r\n 执行SQL：".count($list3)."\r\n -------------------------------------------------------------------------------------------------------------------");
	
	$patList = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root>";
	if($list1||$list2){
		if($list1){
			//已呼患者
			 $patList .= "<RegInfo>";
			 $patList .= "<PatCode>".$list1->pat_code."</PatCode>";
			 $patList .= "<RegSeqNo>".$list1->reg_seq_no."</RegSeqNo>";
			 $patList .= "<PatName>".$list1->pat_name."</PatName>";
			 $patList .= "<DeptCode>".$list1->dept_code."</DeptCode>";
			 $patList .= "<DeptName>".$list1->dept_name."</DeptName>";
			 $patList .= "<DoctorCode>".$list1->doctor_code."</DoctorCode>";
			 $patList .= "<DoctorName>".$list1->doctor_name."</DoctorName>";
			 $patList .= "<Status>".$list1->status."</Status>";
			 $patList .= "<Times>".$val->times."</Times>";
			 $patList .= "<Weight>".$list1->weight."</Weight>";
			 $patList .= "</RegInfo>";
		}
		// foreach($list2 as $val){
		// 	 $patList .= "<RegInfo>";
		// 	 $patList .= "<PatCode>".$val->pat_code."</PatCode>";
		// 	 $patList .= "<RegSeqNo>".$val->reg_seq_no."</RegSeqNo>";
		// 	 $patList .= "<PatName>".$val->pat_name."</PatName>";
		// 	 $patList .= "<DeptCode>".$val->dept_code."</DeptCode>";
		// 	 $patList .= "<DeptName>".$val->dept_name."</DeptName>";
		// 	 $patList .= "<DoctorCode>".$val->doctor_code."</DoctorCode>";
		// 	 $patList .= "<DoctorName>".$val->doctor_name."</DoctorName>";
		// 	 $patList .= "<Status>".$val->status."</Status>";
		// 	 $patList .= "<Weight>".$list1->weight."</Weight>";
		// 	 $patList .= "<Times>".$val->times."</Times>";
		// 	 $patList .= "</RegInfo>";
		// }
		 $patList .= "<PatSum>".count($list3)."</PatSum>";
		$patList .= "<WaitNum>".count($list2)."</WaitNum>";
		$patList .= "<Message>";
		
		$patList .= "<Success>1</Success>"; 
		$patList .= "</Message>";
		$patList.="</root>";
	}else{
		 $patList = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root>";
		 $patList .= "<PatSum>".count($list3)."</PatSum>";
		  $patList .= "<WaitNum>".count($list2)."</WaitNum>";
		$patList .= "<Message>";
		
		 $patList .= "<Success>0</Success>";
		 $patList .= "</Message>";
		 $patList.="</root>";
	}

	return $patList;   
}
public function offline($room,$doctor_code,$dept_code,$expert){
	$db = new ezSQL_mysql('root','trans','menzhen1','172.168.0.241','gb2312');
	$sql = "update client_uid set is_over=1 where room_id='".$room."' and uid='".$doctor_code."' and dept='".$dept_code."' and expert=".$expert;
	$a = $db->query($sql);
	$error = mysql_error();
	$patList = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root>";
	$patList .= "<Message>".$error."</Message>";
	 $patList.="</root>";
	 return $patList;
	
}
public static function SendVoice($string,$sid) {
	$db = new ezSQL_mysql('root','trans','menzhen','172.168.0.241','gb2312');
	$db->query("set names utf8");
	$zq_info = $db->get_row("select VoiceIP from zhenqu where BigScreenID='".$sid."'");
	Log::write("呼叫内容：".$string."||".$sid."\r\n   ---------------------------------------------------------------------------------------------------------------------");
    $socketaddr = $zq_info->VoiceIP;
    $socketport = "7777"; 
    $str=iconv("utf8","gb2312",$string);
	//获取语音呼叫次数
	$t_ary = $db->get_row("select op_val from tbl_op where op_name='".call_times."'");
	$t_times = $t_ary->op_val;
	for($i=0;$i<$t_times;$i++){
		if($i>0){
			$str .= ",,, ".$str;
		}
		
	}
	
	//$str = $str.",,".$str;
	$socket = Socket::singleton();
	$socket->connect($socketaddr,$socketport);
	$send_buffer = pack('N', strlen($str)).$str;
	$sockresult = $socket->sendrequest ($send_buffer);

	$socket->disconnect (); //关闭链接		
}


}



?>