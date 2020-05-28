<?php
namespace Home\Controller;
use Think\Controller;
class QuyaoController extends Controller {
public function index(){
  $this->display();
}
public function getQuYaoList(){
	$list = M("medicine_gets")->where("medicine_status=5")->group("pat_name")->order("id desc")->select();
	for($i=0;$i<count($list);$i++){
		$list[$i]['pat_code'] = substr($list[$i]['pat_code'],-4);
	}
	$this->ajaxReturn($list,"JSON");
}
}

    