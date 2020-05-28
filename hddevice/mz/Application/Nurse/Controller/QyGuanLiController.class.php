<?php
namespace Nurse\Controller;
use Think\Controller;
class QyGuanLiController extends Controller {
public function index(){
  $list = M("medicine_gets")->order("medicine_status desc,price_data asc")->select();
  for($i=0;$i<count($list);$i++){
	 if($list[$i]['medicine_status']==" "||$list[$i]['medicine_status']==""||$list[$i]['medicine_status']=="1"){
	 	$list[$i]['status_txt'] = "待取药";
		$list[$i]['bgcolor'] = "#ffffff";
	 } 
	 if($list[$i]['medicine_status']=="2"){
	 	$list[$i]['status_txt'] = "已发出";
		$list[$i]['bgcolor'] = "#ADFF2F";
	 }
	 
	 if($list[$i]['medicine_status']=="5"){
	 	$list[$i]['status_txt'] = "已呼叫";
		$list[$i]['bgcolor'] = "#EFFF79";
	 }
  }
  $this->list = $list;
  $this->display();
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

}