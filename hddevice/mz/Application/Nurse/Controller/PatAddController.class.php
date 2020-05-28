<?php
namespace Nurse\Controller;
use Think\Controller;
class PatAddController extends CommonController {
public function index(){
  $zhenqu = I("get.zhenqu");
  if($zhenqu=="all"){
	 $where = "id=".$zhenqu; 
  }else{
  	 $where = "1=1";
  }
  $this->zhenqu = $zhenqu;
  $this->display();
}
//获取患者信息
public function getPat(){
	$pat_code = I("post.pat_code");
	$zhenqu = I("post.zhenqu");
	if($zhenqu=='all'){
		$where = "1=1";
	}else{
		$dept_txt = M("zhenqu")->where("id=".$zhenqu)->getField("dept");
		$dept_ary = array();
		$dept_ary2 = explode(",",$dept_txt);
		for($i=0;$i<count($dept_ary2);$i++){
			$mys = M("client_uid")->where("dept='".$dept_ary2[$i]."'")->count();
			//if($mys>0){
			if(0==0){
				$dept_ary[] = $dept_ary2[$i];
			}
		}
		$dept_txt = implode(",",$dept_ary);
		//$zq_room = M("zhenqu")->where("id=".$zhenqu)->getField("room");
		$where="find_in_set(dept_code,'".$dept_txt."')";
		$where2 = "dept_code in(".$dept_txt.")";
	}
	//echo $dept_txt;
	//判断患者所属科室 本诊区是否有有登记记录
	$zq_room_list = M("zhenqu")->where("id=".$zhenqu)->getField("room");
	$hdjs = M("client_uid")->where("find_in_set(room_id,'".$zq_room_list."') and find_in_set(dept,'".$dept_txt."')")->count();
	$f_sql = "select * from register_assign where pat_code='".$pat_code."' and ".$where." and withdraw_flag=0";
	//echo $f_sql;
	$s_sql = "select * from view_patient_registration where pat_code='".$pat_code."' and ".$where2." and withdraw_flag=0";
	
	$row = M()->db(1,"DB_CONFIG1")->query($s_sql);
	//$row = M()->query($f_sql);
	//首先判断本地库是否已经有相同记录,如果没有则添加.
	for($i=0;$i<count($row);$i++){
		$local_flag = M("register_assign")->where("reg_id='".$row[$i]['reg_id']."'")->count();
		
		$data['pat_code']=$row[$i]['pat_code'];
		$data['treat_card_no']=$row[$i]['treat_card_no'];
		if($row[$i]['healthcare_card_no']==null){
			$data['healthcare_card_no']="";
		}else{
			$data['healthcare_card_no']=$row[$i]['healthcare_card_no'];
		}
		
		$data['pat_name']= iconv("gbk","utf8",$row[$i]['pat_name']);
		$data['pat_sex']= iconv("gbk","utf8",$row[$i]['pat_sex']);
		$data['reg_type']= iconv("gbk","utf8",$row[$i]['reg_type']);
		$data['reg_grade']= iconv("gbk","utf8",$row[$i]['reg_grade']);
		$data['reg_seq_no']=$row[$i]['reg_seq_no'];
		$data['times']=$row[$i]['times'];
		$data['weight'] = $row[$i]['reg_seq_no'];
		$data['doctor_code']=trim($row[$i]['doctor_code']);
		$data['doctor_name']= iconv("gbk","utf8",$row[$i]['doctor_name']);
		$data['dept_code']=trim($row[$i]['dept_code']);

		$data['dept_name']= iconv("gbk","utf8",$row[$i]['dept_name']);
		$data['reg_time'] = date("Y-m-d H:i:s",strtotime($row[$i]['reg_time']));
		$data['status'] = 0;
		$data['withdraw_flag']=$row[$i]['withdraw_flag'];
		$data['is_jz'] = $row[$i]['is_jz'];
		if( iconv("gbk","utf8",$row[$i]['reg_type'])=='普通'){
			$data['fp_doctor_code'] = trim($row[$i]['doctor_code']);
		}
		$data['baodao']=1;
		if($local_flag==0){
			$data['reg_id'] = $row[$i]['reg_id']; 
			$a = M("register_assign")->add($data);
		}else{
			M("register_assign")->where("reg_id='".$row[$i]['reg_id']."'")->save($data);
		}
		
	}
	
	/**患者信息有误或者没有在本诊区的情况**/
	if(count($row)==0){
		$rel['num']=0;
		$zq_all = "";
		//$patlist = M("register_assign")->where("pat_code='".$pat_code."'")->select();
		$patlist = M()->db(1,"DB_CONFIG1")->query("select * from view_patient_registration where pat_code='".$pat_code."'");
		if(count($patlist)>0){
			foreach($patlist as $val){
				$zq = $this->getZhenQuByDept($val['dept_code']);
				$zq_txt = "";
				for($i=0;$i<count($zq);$i++){
					$mya = explode("|",$zq[$i]);
					if($mya[0]!=$zhenqu){
						if($i==0){
							$zq_txt = $mya[1];
						}else{
							$zq_txt .=",".$mya[1];
						}
					}
					
				}
				
			}
			$zq_all.=$zq_txt;
			$rel['status_txt'] = "患者 ".$patlist[0]['pat_name']." 为非本诊区患者 请到 <b>".$zq_all."</b> 报到";
		}else{
			$rel['status_txt'] = "无此患者 ，请核对";
		}

		$this->ajaxReturn($rel,"JSON");
	}
	/*
	if($hdjs==0){
		$rel['num']=0;
		$zq_all = "";
		$patlist = M("register_assign")->where("pat_code='".$pat_code."'")->select();
		foreach($patlist as $val){
			$zq = $this->getZhenQuByDept($val['dept_code']);
			$zq_txt = "";
			for($i=0;$i<count($zq);$i++){
				$mya = explode("|",$zq[$i]);
				if($mya[0]!=$zhenqu){
					if($i==0){
						$zq_txt = $mya[1];
					}else{
						$zq_txt .=",".$mya[1];
					}
				}
				
			}
			
		}
		$zq_all.=$zq_txt;
		$rel['status_txt'] = "患者 ".$patlist[0]['pat_name']." 为非本诊区患者 请到 <b>".$zq_all."</b> 报到";
		$this->ajaxReturn($rel,"JSON");
	}
	*/
	/**患者在本诊区只有一条挂号信息**/
	if(count($row)==1){
		//$pat = M("register_assign")->where("reg_id='".$row[0]['reg_id']."'")->select();
		$pat = M()->db(1,"DB_CONFIG1")->query("select * from view_patient_registration  where reg_id='".$row[0]['reg_id']."' and withdraw_flag=0");
		for($x=0;$x<count($pat);$x++){
			
			$pat[$x]['pat_name'] = iconv("gbk","utf8",$pat[$x]['pat_name']);
			$pat[$x]['pat_sex'] = iconv("gbk","utf8",$pat[$x]['pat_sex']);
			$pat[$x]['doctor_name'] = iconv("gbk","utf8",$pat[$x]['doctor_name']);
			$pat[$x]['dept_name'] = iconv("gbk","utf8",$pat[$x]['dept_name']);
			$pat[$x]['reg_type'] = iconv("gbk","utf8",$pat[$x]['reg_type']);
			$pat[$x]['reg_grade'] = iconv("gbk","utf8",$pat[$x]['reg_grade']);
			$pat[$x]['noon_flag'] = iconv("gbk","utf8",$pat[$x]['noon_flag']);
			$pat[$x]['reg_time'] = date("Y-m-d H:i:s",strtotime($pat[$x]['reg_time']));
			
		}
		$rel['num'] = 1;
		$rows = $this->baodao($pat[0]['pat_code']."|".$pat[0]['times'],1,$zhenqu);
		$rel['row'] = $rows;
		if($pat[0]['withdraw_flag']==1){
			$pat[0]['status_txt'] = "<font color='red'>已退号</font>";
		}else{
			switch($pat[0]['status']){
				case 0:
				$pat[0]['status_txt'] = "未呼叫";
				break;
				case 1:
				$pat[0]['status_txt'] = "已呼叫";
				break;
				case 3:
				$pat[0]['status_txt'] = "已过号";
				break;
				case 5:
				$pat[0]['status_txt'] = "已复诊";
				break;
			}
		}
		
		$rel['pat'] = $pat[0];
		if($rows[0]['success']==1){
			$rel['success']=1;
		}else{
			$rel['success'] = 0;
			$rel['error'] = $rows[0]['error'];
		}
	}else{//同一名患者多个挂号记录
		//$p_ary = M("register_assign")->where("pat_code='".$pat_code."' and ".$where)->select();
		$p_ary =  M()->db(1,"DB_CONFIG1")->query("select * from view_patient_registration  where pat_code='".$pat_code."' and ".$where2." and withdraw_flag=0");
		for($i=0;$i<count($p_ary);$i++){
			if($p_ary[$i]['withdraw_flag']==1){
				$p_ary[$i]['status_txt'] = "已退号";
			}else if($p_ary[$i]['is_jz']==1){
				$p_ary[$i]['status_txt'] = "已接诊";
			}else if($p_ary[$i]['status']==1){
				$p_ary[$i]['status_txt'] = "已呼叫";
			}else if($p_ary[$i]['status']==3){
				$p_ary[$i]['status_txt'] = "已过号";
			}else if($p_ary[$i]['baodao']==1){
				$p_ary[$i]['status_txt'] = "已报到";
			}
			else if($p_ary[$i]['baodao']==0){
				$p_ary[$i]['status_txt'] = "未报到";
			}
		}
		$rel['pat'] = $p_ary;
		$rel['num'] = count($p_ary);
		$rel['success'] = 1; 
	}
	$this->ajaxReturn($rel,"JSON");
}
public function getZhenQuByDept($dept_code){
	$zqlist = M("zhenqu")->field("id,dept,name")->select();
	$zhenqu_ary = array();
	for($i=0;$i<count($zqlist);$i++){
		$dept = $zqlist[$i]['dept'];
		if(strpos($dept,$dept_code)!==false){
			$zhenqu_ary[] = $zqlist[$i]['id']."|".$zqlist[$i]['name'];
		}
	}
	return $zhenqu_ary;
}
public function baodao($str='',$num=0,$zhenqu=''){
	if($str==''){
		$str = I("post.str");
	}
	if($zhenqu==''){
		$zhenqu = I("post.zhenqu");
	}
	$a_ary = explode(",",$str);
	for($x=0;$x<count($a_ary);$x++){
		$b_ary = explode("|",$a_ary[$x]);
		$pat_code = $b_ary[0];
		$reg_id = $b_ary[0].$b_ary[1];
		$hd = M("register_assign")->where("reg_id='".$reg_id."'")->select();
		if($hd[0]['withdraw_flag']==0){
			$rel['success'] = 1;
			if($hd[0]['baodao']==1){
				switch ($hd[0]['status']){
					case 1:
					case 5:
					if($hd[0]['is_jz']==1){  
						$is_over = M("client_uid")->where("uid='".$hd[0]['allot_doctor_code']."'")->getField("is_over");
						if($is_over==1){
							$rel['is_over']=1;
							$rel['doctor_name'] = M("doctor_info")->where("doctor_code='".$hd[0]['allot_doctor_code']."'")->getField("doctor_name");						
							$rel['doctor_code'] = trim($hd[0]['fp_doctor_code']);
							$rel['reg_id'] = $hd[0]['reg_id'];
							$rel['dept_code'] = $hd[0]['dept_code'];
						}else{
							$data['status'] = 5;//复诊
							$rel['is_over']=0;
							
							if($hd[0]['reg_seq_no']>1000){ 
								//加号患者开始分配医生 
								$fp_doc_ary = $this->getFpDoctor($hd,$hd[0]['doctor_code'],$zhenqu);
								$data['fp_doctor_code'] = $fp_doc_ary['fp_doctor_code'];
								$data['fp_doctor_name'] = M("doctor_info")->where("doctor_code='".$fp_doc_ary['fp_doctor_code']."'")->getField("doctor_name");
								
								//$data['weight'] = 1000+str_replace("-","",$hd[0]['reg_seq_no']);
								$data['room'] = $fp_doc_ary['room'];
								$rel['status_txt'] = "加号患者 ".$hd[0]['pat_name']."复诊成功";
							}else{
								$rel['status_txt'] = "患者 ".$hd[0]['pat_name']."复诊成功";
							}
								
						}
					}else{
						$data['status'] = 1;
						$rel['status_txt'] = "患者 ".$hd[0]['pat_name']."已经叫号，请去<font color='red'>".$hd[0]['room']."</font>诊室就诊";
					
					}
					break;
					case 3:
					if($hd[0]['is_jz']==1){
						
						
						$data['status'] = 5;//复诊
						$rel['status_txt'] = "过号患者 ".$hd[0]['pat_name']."复诊成功";
					}else{
						$data['status'] = 0;//过号归队
						//开始分配医生
						$fp_doc_ary = $this->getFpDoctor($hd,$hd[0]['doctor_code'],$zhenqu);
						$data['fp_doctor_code'] = $fp_doc_ary['fp_doctor_code'];
						$data['room'] = $fp_doc_ary['room'];
						$rel['status_txt'] = "过号患者 ".$hd[0]['pat_name']." 重新分配在第<font color='red'>".$fp_doc_ary['room']."</font>诊室";				
					}
					break;
					case 0:
					
					if(fp_doctor_code==""){
						$rel['status_txt'] = "患者 ".$hd[0]['pat_name']."为未报到复诊患者，请手动分配";
						echo "hahha"; 
					}else{
						if($hd[0]['is_jz']==1){
							$data['status'] = 5;//误操作先接诊
							$rel['status_txt'] = "患者 ".$hd[0]['pat_name']."复诊成功";
						}else{
							//开始分配医生
							$fp_doc_ary = $this->getFpDoctor($hd,$hd[0]['doctor_code'],$zhenqu);
							if($fp_doc_ary['room']!=""&&$fp_doc_ary['room']!=0){
								$data['fp_doctor_code'] = $fp_doc_ary['fp_doctor_code'];
								$data['fp_doctor_name'] = M("doctor_info")->where("doctor_code='".$fp_doc_ary['fp_doctor_code']."'")->getField("doctor_name");
								$data['room'] = $fp_doc_ary['room'];
								//$rel['status_txt'] = "患者 ".$hd[0]['pat_name']." 已经报到过了,重新分配在第 <b>".$fp_doc_ary['room']."</b> 诊室";
								$rel['status_txt'] = "患者 ".$hd[0]['pat_name']." 报到成功,被分配在第 <b>".$fp_doc_ary['room']."</b> 诊室";
							}else{
								$rel['status_txt'] = "患者 ".$hd[0]['pat_name']."报到成功，正在等待分配诊室";
							}
							
						}
					}
					
					break;
				}
				
			}else if($hd[0]['baodao']==0){
				if($hd[0]['is_jz']==1){
					$data['baodao']=1;
					if($hd[0]['reg_seq_no']<0){ 
						//加号患者开始分配医生 
				
						$fp_doc_ary = $this->getFpDoctor($hd,$hd[0]['doctor_code'],$zhenqu);
						
						
							$data['fp_doctor_code'] = $fp_doc_ary['fp_doctor_code'];
							$data['fp_doctor_name'] = M("doctor_info")->where("doctor_code='".$fp_doc_ary['fp_doctor_code']."'")->getField("doctor_name");
							
							$data['weight'] = 1000+str_replace("-","",$hd[0]['reg_seq_no']);
							$data['room'] = $fp_doc_ary['room'];
							$rel['status_txt'] = "加号患者 ".$hd[0]['pat_name']."复诊成功";
							$data['status']=5;
						
						
					}else{
						if($hd[0]['fp_doctor_code']==""){
							$fp_doc_ary = $this->getFpDoctor($hd,$hd[0]['doctor_code'],$zhenqu);
							$data['fp_doctor_code'] = $fp_doc_ary['fp_doctor_code'];
							$data['fp_doctor_name'] = M("doctor_info")->where("doctor_code='".$fp_doc_ary['fp_doctor_code']."'")->getField("doctor_name");
							$data['room'] = $fp_doc_ary['room'];
							$rel['status_txt'] = "患者 ".$hd[0]['pat_name']."复诊成功";
							$data['status']=5;
						}else{
							$data['status']=5;
							$rel['status_txt'] = "患者 ".$hd[0]['pat_name']."复诊成功";
						}
						
					}
					
				}else{
					$data['baodao']=1;
					//开始分配医生

					$fp_doc_ary = $this->getFpDoctor($hd,$hd[0]['doctor_code'],$zhenqu);
					if($fp_doc_ary['room']!=""&&$fp_doc_ary['room']!=0){
						$rel['status_txt'] = "患者 ".$hd[0]['pat_name']."报到成功，被分配在第 <b>".$fp_doc_ary['room']."</b> 诊室";
					}else{
						$rel['status_txt'] = "患者 ".$hd[0]['pat_name']."报到成功，等待分配诊室";
					}
					
				}
				
				
				
				
				$data['fp_doctor_code'] = $fp_doc_ary['fp_doctor_code'];
				$data['room'] = $fp_doc_ary['room'];
				$data['bd_time'] = date("Y-m-d H:i:s");
			}
			M("register_assign")->where("reg_id='".$reg_id."'")->save($data);
			
			
		}else{//如果已经退号
			$rel['error'] = "已退号";
			$rel['success'] = 1;
		}
		
		
		
		$rel['pat_name'] = $hd[0]['pat_name'];
		$rel['times'] = $hd[0]['times'];
		$rel['dept_name'] = $hd[0]['dept_name'];
		$rel2[] = $rel;
	}
	if($num==0){
		$this->ajaxReturn($rel2,"JSON");
	}else{
		return $rel2;
	}
	
}
public function getFpDoctor($hd,$doctor_code,$zhenqu){
	$fp_doctor_code="";
	$fp_ary = array();
	$zq_room = M("zhenqu")->where("id=".$zhenqu)->getField("room");
	if(trim($doctor_code)!=""){
		$fp_doctor_code=$doctor_code;
	}else{
		$login_doctor_ary = M("client_uid")->where("dept='".$hd[0]['dept_code']."' and find_in_set(room_id,'".$zq_room."') and expert=0 and is_over=0")->order("login_time asc")->select();
		for($i=0;$i<count($login_doctor_ary);$i++){	
			//等候患者人数
			M()->query("update register_assign set fp_doctor_code='',fp_doctor_name='' where reg_id='".$hd[0]['reg_id']."'");
			$fp_num = M("register_assign")->where("fp_doctor_code='".$login_doctor_ary[$i]['uid']."' and withdraw_flag=0 and baodao=1 and ((is_jz=1 and status=5) or (is_jz=0 and status=0))")->count();
			$get_fp_num = M("settxt")->where("id=3")->getField("con");
				if($fp_num<$get_fp_num){
					$fp_ary[] = $login_doctor_ary[$i]['uid']."|".$fp_num."|".$login_doctor_ary[$i]['room_id'];
				}	
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
			
			$p_min_num=99999999999999999999999;
			if(count($c_ary)>0){
				for($n=0;$n<count($c_ary);$n++){
					$p_num = strtotime(M("register_assign")->where("fp_doctor_code='".$c_ary[$n]."' and withdraw_flag='0'")->order("call_time desc")->limit(1)->getField("call_time"));
					
					if($p_num<$p_min_num){
						$p_min_num = $p_num;
						$fp_doctor_code = $c_ary[$n];
					}
				}
				
		
			}
			
			
		}
	}

	$my_room = M("client_uid")->where("uid='".$fp_doctor_code."'")->getField("room_id");
	if($my_room==0){
		$rel['room'] = M("client_uid")->where("uid='".$fp_doctor_code."'")->getField("room_id");
	}else{
		$rel['room'] = $my_room;
	}
	$rel['fp_doctor_code'] = $fp_doctor_code;
	return  $rel; 	
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
	$condition['pat_code'] = I("post.pat_code");
	$condition['dept_code'] = I("post.dept_code");
	$condition['doctor_code'] = I("post.doctor_code");
	
	$data['status'] = 5;
	$have_call = M("register_assign")->where($condition)->getField("call_time");
	if($have_call==null||$have_call==""){
		$rel['success'] = 0;
		$rel['txt'] = "未就诊患者不可执行复诊操作";
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
	$condition['pat_code'] = I("post.pat_code");
	$condition['dept_code'] = I("post.dept_code");
	$condition['doctor_code'] = I("post.doctor_code");
	$data['status'] = 0;
	$have_call = M("register_assign")->where($condition)->getField("status");
	if($have_call!=3&&$have_call!=1){
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
	$zhenqu = I("get.zhenqu");
	$this->source = I("get.source");
	if($zhenqu=="all"){
		$where = "1=1"; 
	}else{
		$where = "id=".$zhenqu;
	}
	$this->pat_code = I("get.pat_code");
	$this->dept_code = $dept_code;
	$this->dept_name = M("dept_info")->where("dept_code='".$dept_code."'")->getField("dept_name");
	$this->pat_name = M("register_assign")->where("pat_code='".$pat_code."'")->getField("pat_name");
	
	$zq = M("zhenqu")->where($where)->select();
	for($i=0;$i<count($zq);$i++){
		if($i==0){
			$dept_txt = $zq[$i]['dept'];
		}else{
			$dept_txt .= ",".$zq[$i]['dept'];
		}
	}
	$dept_list = M("custom_dept")->where("find_in_set(dept_code,'".$dept_txt."')")->select();
	//echo M("custom_dept")->where("find_in_set('".$dept_txt."',dept_code)")->select(false);
	$this->dept_list = $dept_list;
	$this->display();
}
public function change_doctor(){
	$dept_code = I("get.dept_code");
	$doctor_code = I("get.doctor_code");
	$this->doctor_code = $doctor_code;
	$zhenqu = I("get.zhenqu");
	$this->zhenqu = $zhenqu;
	$this->reg_id = I("get.reg_id");
	$this->pat_code = M("register_assign")->where("reg_id='".I("get.reg_id")."'")->getField("pat_code");
	$this->source = I("get.source");
	if($doctor_code!=""){
		$this->doctor_name = M("doctor_info")->where("doctor_code='".$doctor_code."'")->getField("doctor_name");
	}
	$this->dept_name = M("dept_info")->where("dept_code='".I("get.dept_code")."'")->getField("dept_name");
	$this->pat_name = M("register_assign")->where("reg_id='".I("get.reg_id")."'")->getField("pat_name");
	$this->dept_code=$dept_code;
	if($zhenqu=='all'){
		$where="1=1";
	}else{
		$where="id=".$zhenqu;
	}
	$zql = M("zhenqu")->where($where)->select();
	$room_list_txt = "";
	for($i=0;$i<count($zql);$i++){
		$zqid = $zql[$i]['id'];
		$room_txt = M("zhenqu")->where("id=".$zqid)->getField("room");
		if($i==0){
			$room_list_txt = $room_txt;
		}else{
			$room_list_txt = ",".$room_txt;
		}
	}
	$doclist = M("client_uid")->where("room_id in(".$room_list_txt.") and is_over=0 and dept='".$dept_code."'")->select();
	$doclist_txt = "";
	for($i=0;$i<count($doclist);$i++){
		if($i==0){
			$doclist_txt=$doclist[$i]['uid'];
		}else{
			$doclist_txt.=",".$doclist[$i]['uid'];
		}
	}
	$doclist = M("doctor_info")->where("doctor_code in(".$doclist_txt.")")->select();
	$this->doclist = $doclist;
	$this->display();
}
public function ajax_get_doctorname(){
	$doctor_code = I("post.doctor_code");
	$condition['doctor_code'] = $doctor_code;
	$rel['doctor_name'] = M("doctor_info")->where($condition)->getField("doctor_name");
	$this->ajaxReturn($rel,"JSON");
}
//插队操作
public function pat_chadui(){
	$doctor_code = I("get.doctor_code");
	$dept_code = I("get.dept_code");
	$this->dept_code = $dept_code;
	$this->doctor_code = $doctor_code;
	$reg_id = I("get.reg_id");
	$pinf = M("register_assign")->where("reg_id='".$reg_id."'")->field("reg_id,reg_type,pat_code,pat_name,reg_seq_no,weight")->select();
	$this->pinfo = $pinf[0]; 
	if($pinf[0]['reg_type']=='专家'){
		$where = "dept_code='".$dept_code."' and doctor_code='".$doctor_code."' and reg_type='专家' and reg_id<>'".$reg_id."'";
	}else{
		$where = "dept_code='".$dept_code."' and reg_type='普通' and fp_doctor_code='".$doctor_code."' and reg_id<>'".$reg_id."'";
	}
	$patlist = M("register_assign")->where($where." and baodao=1")->field("reg_id,reg_seq_no,pat_code,pat_name,weight")->order("weight asc")->select();
	$this->patlist = $patlist;
	$this->display();
}
public function ajax_set_patsort(){
	$str = I("post.str");
	$dept_code = I("post.dept_code");
	$doctor_code = I("post.doctor_code");
	$ary = explode("|",$str);
	$flag = 1;
	$expert = M("client_uid")->where("uid='".$doctor_code."'")->getField("expert");
	if($expert==1){
		for($i=0;$i<count($ary);$i++){
			$data['weight'] = $i+1;
			$condition['reg_id'] = $ary[$i];
			M("register_assign")->where($condition)->save($data);
			
		}
	}else{
		$weight_ary = array();
		for($i=0;$i<count($ary);$i++){
			$weight_ary[$i] = M("register_assign")->where("reg_id='".$ary[$i]."'")->getField("weight");
		}
		sort($weight_ary);
		
		for($i=0;$i<count($ary);$i++){
			$data['weight'] = $weight_ary[$i];
			M("register_assign")->where("reg_id='".$ary[$i]."'")->save($data);
		}
	}
	
	$rel['success'] = $flag;
	$this->ajaxReturn($rel,"JSON");
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
	
	
	$csel = M("register_assign")->where("dept_code='".$dept_code_new."' and status in(0,5) and (doctor_code='' or doctor_code is null) and withdraw_flag=0 and is_jz=0")->limit($position.",1")->order("weight asc")->field("weight")->select();
	
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
	$reg_id = I("post.reg_id");
	$doc_info = M("client_uid")->where("uid='".$doctor_code_old."'")->select();
	$is_over = $doc_info[0]['is_over'];
	if($doc_info[0]['expert']==1){
		$data['doctor_code'] = $doctor_code_new;
		$data['doctor_name'] = M("doctor_info")->where("doctor_code='".$doctor_code_new."'")->getField("doctor_name");
	}
	
	$where = "reg_id='".$reg_id."'";
	if($is_over==1){
		$data['status']  = 0;
		$data['baodao'] = 1;
	}else{
		$data['status']  = 0;
	}
	$data['fp_doctor_code'] = $doctor_code_new;
	
	$data['room'] = M("client_uid")->where("uid='".$doctor_code_new."'")->getField("room_id");
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