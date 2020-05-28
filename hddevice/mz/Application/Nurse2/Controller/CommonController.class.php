<?php
namespace Nurse2\Controller;
use Think\Controller;
require_once __DIR__ . '/Log.php';
class CommonController extends Controller {
public function writeLog($doctor_code,$content){
	$data['doctor_code'] = $doctor_code;
	$doc_info =  M("doctor_info")->where("doctor_code='".$doctor_code."'")->field("doctor_name,doctor_postitle")->select();
	$data['doctor_name'] = $doc_info[0]['doctor_name'];
	$data['doctor_postitle'] = $doc_info[0]['doctor_postitle'];
	$data['content'] = $content;
	M("logs")->add($data);
}
}