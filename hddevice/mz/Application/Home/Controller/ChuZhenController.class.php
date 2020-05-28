<?php
namespace Home\Controller;
use Think\Controller;
class ChuZhenController extends Controller {
public function index(){
  $this->display();
}
public function getDoc(){
	$list = M("client_uid")->where("expert=1")->select();
	$list2 = array();
	if(count($list)>0){
		$rel['success']  =1;
		if(count($list)>12){
			 $last_page_num = count($list)%12;
			 $spetor =floor((count($list))/12);
			 if(((count($list))%12)>0){
				$spetor = $spetor+1;
			 }
		
			 if((session("page")+1)>=$spetor){
					session("page",0);	
			 }else{
				session("page",session("page")+1);
			 }
			 $begin = 12*session("page");
			 if((session("page")+1)==$spetor){
				 if($last_page_num>0){
					$end = $begin+$last_page_num;	
				 }else{
				 	$end = $begin+12;
				 }
					
			 }else{
		     	$end = $begin+12;
			 }
			 for($i=$begin;$i<$end;$i++){
				$list2[] = $list[$i];
			 }
			 
		}else{
			$list2 = $list;
		}
		for($i=0;$i<count($list2);$i++){
			$row = M("doctor_info")->join(" dept_info on dept_info.dept_code=doctor_info.dept_code")->where("doctor_code='".$list2[$i]['uid']."'")->field("doctor_code,doctor_name,doctor_postitle,dept_name,shortinfo,thumbnail")->select();
			
			$list2[$i]['doctor_name'] = $row[0]['doctor_name'];
			$list2[$i]['dept_name'] = $row[0]['dept_name'];
			$list2[$i]['doctor_postitle'] = $row[0]['doctor_postitle'];
			$list2[$i]['doctor_name'] = $row[0]['doctor_name'];
			
			if(trim($row[0]['thumbnail'])==""||trim($row[0]['thumbnail'])=='null'){
				$list2[$i]['thumbnail']="default.jpg";
			}else{
				$list2[$i]['thumbnail'] = $row[0]['thumbnail'];
			}
			
			if(trim($row[0]['shortinfo'])==""||trim($row[0]['shortinfo'])=='null'){
				$list2[$i]['shortinfo']="暂无信息";
			}else{
				$list2[$i]['shortinfo'] = $row[0]['shortinfo'];
			}
			$hour = date("H");
			if($hour<12){
				$list2[$i]['shiduan'] = "上午";
			}else{
				$list2[$i]['shiduan'] = "下午";
			}
			$h_num = M("register_assign")->where("doctor_code='".$list[$i]['uid']."'")->count();
			$list2[$i]['h_num'] = $h_num;
		}
		$rel['row'] = $list2;
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}

}