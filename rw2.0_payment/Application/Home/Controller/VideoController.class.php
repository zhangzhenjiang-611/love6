<?php
namespace Home\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
require_once __DIR__ . '/Log.php';
class VideoController extends Controller {
public function index(){
	$vedio=M('drug_vedio')->select();
	// echo M('drug_vedio')->_sql();
  // $this->assign('video',$vedio);

  $this->assign('vedio',$vedio);
	// var_dump($vedio);
	$this->display();
}
public function video(){
	$param = I("get.param");
	 // echo $param;
	$this->param = $param;
	$this->display();

}

}