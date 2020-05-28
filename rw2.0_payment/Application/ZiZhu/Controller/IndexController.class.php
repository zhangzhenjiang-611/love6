<?php
namespace ZiZhu\Controller;
use Think\Controller;
class IndexController extends CommonController {
public function index(){
   $this->display();
}
public function getdangqian_timme(){
	$time = date('Y年m月d日',time());
	$time_1 = "星期".mb_substr( "日一二三四五六",date("w"),1,"utf-8" );
	$time_2 = date('H:i',time());
	$rel['rq'] = $time;
	$rel['xq'] = $time_1;
	$rel['sj'] = $time_2;
	$this->ajaxReturn($rel,"JSON");
}
public function fakaji(){
	$k0 = I("post.k0");
	$k1 = I("post.k1");
	$k2 = I("post.k2");
	$k3 = I("post.k3");
	$zzj_id = I("post.zzj_id");
	$time = date('Y-m-d H:i:s',time());
	$sqls = "update fkj_state set reader_no='".$k0."',channel_no='".$k1."',hairpin='".$k2."',card='".$k3."',time='".$time."' where zzj_id='".$zzj_id."'";
	M("fkj_state")->query($sqls);
	// echo M("fakaji")->getLastSql();exit;
	$this->ajaxReturn($rel,"JSON");
}
//京医通生成新的交易流水号
public function GenerateTradeNo(){
	$xml = I("post.input_xml");
	$xml = htmlspecialchars_decode($xml);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "京医通生成新的交易流水号从前台发送到后台";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	// var_dump($result);exit();
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	$state = (array)$doc->state;
	if($state["@attributes"]['success']==="true"){
		$tradeno = (array)$doc;
		$rel['ResultCode'] = "0000";
		$rel['tradeno'] = $tradeno['tradeno'];
		$this->ajaxReturn($rel,"JSON");
	}else{
		$rel['ResultCode'] = "0001";
		$this->ajaxReturn($rel,"JSON");
	}
	
}
//查询京医通退款接口
public function Charge(){
	$xml = I("post.input_xml");
	$xml = htmlspecialchars_decode($xml);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "京医通异常退费从前台发送到后台";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	// var_dump($result);exit();
	/*$result= '<?xml version="1.0" encoding="utf8" standalone="yes"?><root version="2.1.1"><state success="true" needconfirm="false"></state></root>';*/
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	$state = (array)$doc->state;
	if($state["@attributes"]['success']==="true" && $state["@attributes"]['needconfirm']==="false"){
		$rel['ResultCode'] = "0000";
		$this->ajaxReturn($rel,"JSON");
		
	}else{
		$rel['ResultCode'] = "0001";
		$this->ajaxReturn($rel,"JSON");
	}
	
}
//京医通退卡接口
public function CardManageReturnCard(){
	$xml = I("post.input_xml");
	$xml = htmlspecialchars_decode($xml);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	$card_no = I("post.card_no");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	/**************日记记录开始**************/
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "京医通退卡从前台发送到后台";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	// var_dump($result);exit();
	/*$result= '<?xml version="1.0" encoding="utf8" standalone="yes"?><root version="2.1.1"><state success="false" needconfirm="false"></state></root>';*/
	/*$result = '<?xml version="1.0" encoding="utf-8"?>
				<root version="1.0">
					<state success="true" needconfirm="false">
						<warning no="0" info=""/>
						<warning no="1" info=""/>
					</state>
					<cardno>11223344556677889900</cardno>
					<plantext>包含中文，待定</plantext>
					<sign></sign>
					<tradetime>20130220103001</tradetime>
				</root>
				';*/
	$doc = simplexml_load_string($result);
	$state = (array)$doc->state;
	if($state["@attributes"]['success']==="true" && $state["@attributes"]['needconfirm']==="false"){
		$rel['ResultCode'] = "0000";
		$this->ajaxReturn($rel,"JSON");
		
	}else{
		$rel['ResultCode'] = "0001";
		$this->ajaxReturn($rel,"JSON");
	}
}


function writeLogs(){
	header("Content-type: text/html; charset=utf-8");
	$log_txt =I("post.log_txt");
	$log_type =I("post.log_type");
	
	/**************日记记录开始***********/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = I("post.log_txt");
	$data['direction'] = I("post.direction");
	$data['op_code'] = I("post.op_code");
	M("logs")->add($data);
	//echo M()->getLastSql();
	$this->writeLog(utf8_to_gbk($log_txt),$log_type);
	/**********日志记录结束*******************************/
}




}