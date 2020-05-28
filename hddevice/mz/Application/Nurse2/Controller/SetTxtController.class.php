<?php
namespace Nurse2\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
class SetTxtController extends Controller {
public function index(){
  $row = M("settxt")->select();
  $this->row = $row;
  $this->display();
}
public function edit_do(){
	$data1['con'] = I("post.s1");
	//大屏底部文字 
	M("settxt")->where("id=1")->save($data1);
	//医生登录界面文字
	//$data2['con'] = strip_tags(htmlspecialchars(I("post.s2")));
	$data2['con'] = strip_tags(I("post.s2"));
	M("settxt")->where("id=2")->save($data2);
	//语音呼叫次数
	$data3['con'] = strip_tags(I("post.s3"));
	M("settxt")->where("id=3")->save($data3);
	//两个科室专业的诊室
	$data5['con'] = strip_tags(I("post.s5"));
	M("settxt")->where("id=5")->save($data5);
	//大屏翻屏时间
	$data6['con'] = strip_tags(I("post.s6"));
	M("settxt")->where("id=6")->save($data6);
	$this->success("修改信息成功");
	
}


public function ajax_send_voice(){
	$this->SendVoice("192.168.1.100","7777",I("post.speech"));
	$this->SendVoice("192.168.1.100","7777",I("post.speech"));
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