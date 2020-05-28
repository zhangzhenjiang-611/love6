<?php
namespace Nurse\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
class SetTxtController extends Controller {
public function index(){

  $relist = array();
  $this->daping_txt = M("settxt")->where("id=1")->getField("con");
  $this->wjyy_txt = M("settxt")->where("id=2")->getField("con");
  $this->hours = M("settime")->where("id=1")->getField("hours");
  $this->minutes = M("settime")->where("id=1")->getField("minutes");
  $this->display();
}

public function ajax_set_txt(){
	$id = I("post.id");
	$data['con'] = I("post.txt");
	if(M("settxt")->where("id=".$id)->save($data)){
		$rel['success'] = 1;
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}
public function setStatus(){
	$pat_code = I("post.pat_code");
	$doctor_code = I("post.doctor_code");
	$data['status'] = 0;
	if(M("register_assign")->where("pat_code='".$pat_code."'")->save($data)){
		$rel['success'] = 1;
		$rel['pat_wait_num'] = M("register_assign")->where("status = 0 and doctor_code='".$doctor_code."'")->count();
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}
public function delRoom(){
	$room = I("post.room");
	if(M("client_uid")->where("room_id=".$room)->delete()){
		$rel['success']  =1;
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
}
public function ajax_send_voice(){
	$this->SendVoice("192.168.1.100","7777",I("post.speech"));
	$this->SendVoice("192.168.1.100","7777",I("post.speech"));
}
public function ajax_set_time(){
	$minutes = I("post.minutes");
	$hours = I("post.hours");
	
	$data['hours'] = $hours;
	$data['minutes'] = $minutes;
	if(M("settime")->where("id=1")->save($data)){
		$rel['success'] = 1;
	}else{
		$rel['success'] = 0;
	}
	$this->ajaxReturn($rel,"JSON");
	
}
public function SendVoice($socketaddr, $socketport,$string) {

		$str=iconv("utf8","gbk",$string);
		$str = $str;
		//$str="";
		$socket = \Socket::singleton();
		$socket->connect($socketaddr,$socketport);
		$send_buffer = pack('N', strlen($str)).$str;
		$sockresult = $socket->sendrequest ($send_buffer);

        $socket->disconnect (); //关闭链接		
  }

}