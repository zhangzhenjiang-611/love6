<?php
namespace Nurse\Controller;
use Think\Controller;
class SearchController extends Controller {
public function index(){
  
  $this->display();
}
public function getPatInfo(){
	$key = I("post.key");
	$a = M()->db(1,"DB_CONFIG1")->query("select * from vi_mz_jcjh where pat_id='".$key."'");
	if(count($a)>0){
		for($i=0;$i<count($a);$i++){
			$a[$i]['pat_name'] = iconv("gbk","utf8",$a[$i]['pat_name']);
			$a[$i]['inspect_name'] = iconv("gbk","utf8",$a[$i]['inspect_name']);
			$a[$i]['pat_sex'] = iconv("gbk","utf8",$a[$i]['pat_sex']);
			$a[$i]['dept_name'] = iconv("gbk","utf8",$a[$i]['dept_name']);
			$bir = strtotime($a[$i]['pat_birthday']);
			$a[$i]['pat_birthday']  = date("Y-m-d",$bir);
		}
		$a[0]['success'] = 1;
		$this->ajaxReturn($a,"JSON");
	}else{
		$rel[0]['success'] = 0;
		$this->ajaxReturn($rel,"JSON");
	}
}
public function addToDL(){
	$str = I("post.str");
	$ary = explode(",",$str);
	$rel = array();
	for($i=0;$i<count($ary);$i++){
		$tmp_ary = explode("|",$ary[$i]);
		$data['reg_id'] = $tmp_ary[0];
		$data['ptno'] = $tmp_ary[1];
		$data['pat_id'] = $tmp_ary[2];
		$data['pat_name'] = $tmp_ary[3];
		$data['pat_birthday'] = $tmp_ary[4];
		$data['pat_sex'] = $tmp_ary[5];
		$data['inspect_code'] = $tmp_ary[6];
		$data['inspect_name'] = $tmp_ary[7];
		$data['dept_code'] = $tmp_ary[8];
		$data['dept_name'] = $tmp_ary[9];
		$data['is_santong'] = $tmp_ary[10];
		$data['reg_type'] = $tmp_ary[11];
		$data['source'] = "HIS";
		$data['reg_time'] = date("Y-m-d H:i:s");
		$h = M()->Table("think_register_tj")->where("reg_id='".$tmp_ary[0]."'")->count();
		if($h==0){
			if($id = M()->Table("think_register_tj")->add($data)){
				$data2['weight'] = $id*10;
				M()->Table("think_register_tj")->where("id=".$id)->save($data2);
			}
			
		}else{
			$rel['have_data'] = 1;
			$this->ajaxReturn($rel,"JSON");
		}
		
		
	}
	$retval = M()->Table("think_register_tj")->order("id asc")->select();
	for($i=0;$i<count($retval);$i++){
		$status = $retval[$i]['status'];
		$st = $retval[$i]['is_santong'];
		switch($status){
			case 0:
			$retval[$i]['status'] = "未呼叫";
			break;
			case 1:
			$retval[$i]['status'] = "已呼叫";
			default:
			$retval[$i]['status'] = "未呼叫";
			break;
		}
		if($st==1){
			$retval[$i]['santong'] = "<font color='green'>是</font>";
		}else{
			$retval[$i]['santong'] = "<font color='red'>否</font>";
		}
		
	}
	$this->ajaxReturn($retval,"JSON");
}

}