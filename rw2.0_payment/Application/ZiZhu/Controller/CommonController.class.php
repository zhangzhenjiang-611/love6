<?php
namespace ZiZhu\Controller;
use Think\Controller;
class CommonController extends Controller {
public function writeLog($str,$log_type=""){
	/*$data['doctor_code'] = $doctor_code;
	$doc_info =  M("doctor_info")->where("doctor_code='".$doctor_code."'")->field("doctor_name,doctor_postitle")->select();
	$data['doctor_name'] = $doc_info[0]['doctor_name'];
	$data['doctor_postitle'] = $doc_info[0]['doctor_postitle'];
	$data['content'] = $content;
	M("logs")->add($data);
	*/
	\Think\Log::write($str);
	
}
}