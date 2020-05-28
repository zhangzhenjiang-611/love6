<?php
namespace Nurse\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
class QyaoController extends Controller {
public function index(){
  $this->list = $this->getQyList();
  $wait_num = M("medicine_gets")->where("medicine_status='0' or medicine_status='' or medicine_status=' '")->count();
  $this->assign("wait_num",$wait_num);
  $this->display();
}
public function getQyList(){

	$list = M("medicine_gets")->order("price_data asc")->select();
	if(count($list)>0){
		for($i=0;$i<count($list);$i++){
			if($list[$i]['medicine_status'] == 1){
				$list[$i]['backcolor'] = "yellow";
				$list[$i]['status_txt'] = "已摆好";
			}
			else if($list[$i]['medicine_status'] == 2){
				$list[$i]['backcolor'] = "#ADFF2F";
				$list[$i]['status_txt'] = "已发出";
			}
			else if($list[$i]['medicine_status'] == 5){
				$list[$i]['backcolor'] = "#EFFF79";
				$list[$i]['status_txt'] = "已呼叫";
			}
			else if($list[$i]['medicine_status'] == " "||$list[$i]['medicine_status'] == "0"){
				$list[$i]['backcolor'] = "#fff";
				$list[$i]['status_txt'] = "待取药";
			}
			
		}
	}else{
		$list = "n";
	}
	
	
	
	
	return $list;
	
}
public function getData(){
	$list = $this->getQyList();
	$this->ajaxReturn($list,"JSON");
}
public function ajaxAddQy(){
	$val = I("post.vals");
	$var_ary = explode("/",$val);
	$pat_code = $var_ary[0];
	$ledger_sn = $var_ary[2];
	$order_no = $var_ary[1];
	
	$condition['pat_code'] = $pat_code;
	$condition['ledger_sn'] = $ledger_sn;
	$condition['order_no'] = $order_no;
	$fa = M("medicine_gets")->where($condition)->select();
	//echo  M("medicine_gets")->where($condition)->select(false);
	if(count($fa)>0){
		if($fa[0]['medicine_status']=="5"){
			$data['medicine_status']="5";	
		}else{
			$data['medicine_status']="1";
		}
		
		
		M("medicine_gets")->where($condition)->save($data);
		$rel['success'] = 1;
		$rel['status_txt'] = "扫码成功";
		$qylist = $this->getQyList();
		$rel['data'] = $this->getQyList();
		$lc = M("medicine_gets")->where("pat_code='".$pat_code."'")->field("pat_name")->select();
		$pinfo['pat_name'] = $lc[0]['pat_name'];
		$pinfo['order_no'] = $order_no;
		$pinfo['ledger_sn'] = $ledger_sn;
		$pinfo['pat_code'] = $pat_code;
		$rel['pinfo'] = $pinfo;
		$wait_num = M("medicine_gets")->where("medicine_status='0' or medicine_status='' or medicine_status=' '")->count();
		$rel['wait_num'] = $wait_num;
		$rel['total'] = M("medicine_gets")->where("pat_code='".$pat_code."'")->count();
		$rel['have_send'] = M("medicine_gets")->where("pat_code='".$pat_code."' and (medicine_status='1' or medicine_status='5')")->count();
		$rel['shengyu'] = $rel['total']-$rel['have_send'];
	}else{
		$rel['success'] = 0;
		$rel['status_txt'] = "无患者处方记录";
	}
	$this->ajaxReturn($rel,"JSON");
}
public function shangDp(){
	$pat_code = I("post.pat_code");
	$pat_name = I("post.pat_name");
	$ledger_sn = I("post.ledger_sn");
	$order_no = I("post.order_no");
	
	$condition["pat_code"] = $pat_code;
	$condition["ledger_sn"] = $ledger_sn;
	$condition["order_no"] = $order_no;
	$condition["medicine_status"] = "1";
	$data['medicine_status'] = "5";
	//echo M("medicine_gets")->where($condition)->select(false); 
	if(M("medicine_gets")->where($condition)->save($data)){
		$data2['medicine_status'] = "5";
		$condition2["medicine_status"] = "1";
		$condition2["pat_code"] = $pat_code;
		M("medicine_gets")->where($condition2)->save($data2);
		$rel['success']= 1;
		
		
	}else{
		$rel['success'] = 0;	
	}
	$this->sendVoice("请患者".$pat_name.",到窗口取药"); 
	$this->ajaxReturn($rel,"JSON");
}
public function sendVoice($speech){
	 //$socketaddr = "172.16.20.240";
	   $socketaddr = "192.168.1.126";  
	   $socketport = "7777"; 
		$str=iconv("utf-8","gbk",$speech);
		$str = $str.",".$str;
		$socket = \Socket::singleton();
		$socket->connect($socketaddr,$socketport);
		$send_buffer = pack('N', strlen($str)).$str;
		$sockresult = $socket->sendrequest ($send_buffer);

        $socket->disconnect (); //关闭链接		
}

}	