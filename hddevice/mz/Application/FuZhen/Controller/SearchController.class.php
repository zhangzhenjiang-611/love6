<?php
namespace FuZhen\Controller;
use Think\Controller;
class SearchController extends Controller {
public function index(){
  $santong = M()->Table("santong_txt")->select();
	$this->santong = $santong;
	$this->pat_type = M("pat_type")->order("weight asc")->select();
  $this->display();
}
public function getPatInfo(){
	$key = I("post.key");
	$ts = date("Ymd");
	//$ts="20141105"; 
	$a = M()->db(1,"DB_CONFIG1")->query("select * from vi_mz_jcjh where pat_id='".$ts.$key."'");
	$b = array();
	if(count($a)>0){
		$n = 0;
		for($i=0;$i<count($a);$i++){
			$inspect_code = $a[$i]['inspect_code'];
			$hebin_id = getHeBinId($inspect_code);
		
			if($hebin_id!=""){
				$hving = M("think_local_inspect")->where("inspect_code=".$inspect_code)->count();
				
				if($hving>0){	
					$b[$n]['pat_name'] = iconv("gbk","utf8",$a[$i]['pat_name']);
					//$a[$i]['inspect_name'] = iconv("gbk","utf8",$a[$i]['inspect_name']);
					$b[$n]['reg_id'] = $a[$i]['reg_id'];
					$b[$n]['ptno'] = $a[$i]['ptno'];
					$b[$n]['pat_id'] = $a[$i]['pat_id'];
					$b[$n]['pat_sex'] = iconv("gbk","utf8",$a[$i]['pat_sex']);
					$b[$n]['dept_name'] = iconv("gbk","utf8",$a[$i]['dept_name']);
					$bir = strtotime($a[$i]['pat_birthday']);
					$b[$n]['pat_birthday']  = date("Y-m-d",$bir);
					if($b[$n]['pat_birthday']=='1970-01-01'){
						$b[$n]['pat_birthday']="";
					}
					$hebin_id = getHeBinId($inspect_code);
					$h = M("think_register_tj")->where("inspect_code='".$hebin_id."'  and status=0")->count();
					/*$max_gid = M("think_register_tj")->where("inspect_code='".$a[$i]['inspect_code']."'")->limit("0,1")->order("id desc")->field("id,gid")->select();
					$a[$i]['gid'] = $max_gid[0]['gid']+1;
					
				*/
				
					$b[$n]['wait_no'] = $h; 
					
					//$hebin = M("local_jcxm_hb")->where("find_in_set('".$inspect_code."',inspect_code)")->field("id,hebin_name,inspect_code")->select();
					$hebin_id = getHeBinId($inspect_code);
					
					$b[$n]['inspect_name'] =  M("local_jcxm_hb")->where("id=".$hebin_id)->getField("hebin_name"); 
					$b[$n]['inspect_code'] = $hebin_id;
					$n++;
				}
			}
			
		}
		//print_r($b);   
		$b[0]['success'] = 1;
		$this->ajaxReturn($b,"JSON");
	}else{
		$rel[0]['success'] = 0;
		$this->ajaxReturn($rel,"JSON");
	}
}
public function addToDL(){
	$str = I("post.str");
	$ary = explode(",",$str);
	$rel = array();
	$reval = array();
	for($i=0;$i<count($ary);$i++){
		$tmp_ary = explode("|",$ary[$i]);
		$data = array();
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
		$data['baodao_time'] = date("Y-m-d H:i:s");
		$data['fieldset01'] = $tmp_ary[12];
		
		$h = M()->Table("think_register_tj")->where("reg_id='".$tmp_ary[0]."'")->count();
		if($h==0){
			$max_gid = M()->Table("think_register_tj")->where("inspect_code='".$tmp_ary[6]."'")->field("id,gid")->limit("0,1")->order("id desc")->select();
			if($max_gid[0]['gid']!=""){
				$data['gid'] = $max_gid[0]['gid']+1;
			}else{
				$data['gid'] = 1;
			}
			if($id = M()->Table("think_register_tj")->add($data)){
				$data2['weight'] = $id*10;
				M()->Table("think_register_tj")->where("id=".$id)->save($data2);
			}
			
			if($tmp_ary[10]==0){
				$data['santong'] = "";
			}else{
				$st_name =  M()->query("select name from santong_txt where id=".$tmp_ary[10]);
				$data['santong'] = $st_name[0]['name'];
			}
			
		
			$wait_no = M("think_register_tj")->where("inspect_code='".$tmp_ary[6]."' and status=0")->count();
			$data['wait_no'] = $wait_no;
			$reval[] = $data;
		}/*else{
			$rel['have_data'] = 1;
			$this->ajaxReturn($rel,"JSON");
		}*/
		
	}

	$this->ajaxReturn($reval,"JSON");
	/*$retval = M()->Table("think_register_tj")->order("id asc")->select();
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
	*/
}

}