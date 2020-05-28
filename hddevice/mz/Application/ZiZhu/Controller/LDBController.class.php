<?php
namespace ZiZhu\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
class IndexController extends CommonController {
public function index(){
	
	$id = I("get.id");
   $this->assign("id",$id);
   $this->display();
}

//获取服务器时间
public function getdangqian_timme(){
	date_default_timezone_set('PRC');
	$time = date('Y年m月d日',time());
	$time_1 = "星期".mb_substr( "日一二三四五六",date("w"),1,"utf-8" );
	$time_2 = date('H:i',time());
	$rel['rq'] = $time;
	$rel['xq'] = $time_1;
	$rel['sj'] = $time_2;
	$this->ajaxReturn($rel,"JSON");
}

public function print_status(){
 	$zzj_id = I("post.zzj_id");
 	$print_status =M("devstatus")->where("DevName='{$zzj_id}'")->getField('Paper_End,Paper_Jam');
 	foreach ($print_status as $key => $value) {
 		$print_status['Paper_End']=$key;
 		$print_status['Paper_Jam']=$value;
 	}
 	$this->ajaxReturn($print_status,"JSON");
 }
public function getPatInfo(){
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
	$kaid = I("post.kaid");
	$zzj_id = I("post.zzj_id");
	$business_type = I("post.business_type");
/*	$kaid = "000113964700";
	$zzj_id = "ZZJ029";
	$business_type ="自助缴费";*/
	if(strpos($kaid,"H")!==false){
		$hao_ming="07";
	}
	/*******************修改判断用户的卡类型，06身份证，07就诊卡，08用户自己输入的id号******************************/
	else if(strlen($kaid)>=18){
		$hao_ming="06";
	}else if(strlen($kaid)==12){
		$hao_ming="08";
	}
	$s = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root><commitdata><data><datarow hao_ming =\"".$hao_ming."\" code_value =\"".$kaid."\" patient_name=\"\" business_type =\"".$business_type."\" /></data></commitdata><returndata/><operateinfo><info method=\"YYT_QRY_PATI\" opt_id=\"".zzj_id."\" opt_name=\"".$zzj_id."\" opt_ip=\"".get_client_ip()."\" opt_date=\"".date("Y-m-d")."\" guid=\"{".generate_code(10)."}\" token=\"AUTO-YYRMYY-".date("Y-m-d")."\"  /></operateinfo><result><info /></result></root>";
	$data['card_code'] = '21';
	$data['card_no'] = $kaid;
	$data['op_name'] = date("Y-m-d H:i:s")."获取就诊卡患者信息";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $s;
	//$data['op_date']=date('Y-m-d');
	$data['op_code'] = I("post.op_code");
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取自费患者身份信息，发送报文信息：".$s));
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_QRY_PATI';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
/*	if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	$this->writeLog(utf8_to_gbk("获取自费患者身份信息，返回报文信息：".$result));
	$data2['card_code'] = '21';
	$data2['card_no'] = $kaid;
	$data2['op_xml'] = $result;
	$data2['op_name'] = date("Y-m-d H:i:s")."获取就诊卡患者信息";
	$data2['op_code'] = I("post.op_code");
	$data2['direction'] = "返回报文";
	M("logs")->add($data2);
	//print_r($doc);
	//返回是否成功状态及相关信息
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['patInfo']['result'] = $result_return;
	if($result_return['execute_flag']==0){
		/**返回的患者信息
		**@addition_no1 医保卡号
		**@amount
		**@balance 余额
		**@birth_place 出生地
		**@birthday 生日
		**@build_date 建卡日期
		**@card_code 卡类型 21为院内就诊卡
		**@card_no  卡号
		**@charge_type 费用类型
		**@country_chn 国家名称
		**@country_code 国家代码
		**@home_district 区域代码
		**@home_district_chn 区域名称
		**@home_street 家庭住址
		**@home_tel 家庭电话
		**@home_zipcode 家庭邮政编码
		**@marry_chn 婚姻状态
		**@marry_code 婚姻代码
		**@mobile 手机号码
		**@name  患者姓名
		**@nation_chn 民族名称
		**@nation_code 民族代码
		**@p_bar_code 就诊卡号
		**@patient_id HIS编号
		**@response_chn 身份
		**@response_type 身份代码
		**@sex 性别
		**@sex_chn 姓别名称
		**@social_no 身份证号
		**@use_times 使用次数
		**/
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		$rel['patInfo']['datarow'] = $datarow_ary;
		if($result_return['execute_flag']==0){
			$getCF = $this->getChuFang($datarow_ary['patient_id'],$datarow_ary['card_code'],$datarow_ary['card_no'],$zzj_id);
			$rel['chufang'] = $getCF;
		}
	}
	if($result_return['execute_message']=="有就诊未缴挂号费记录,请先补缴挂号费!"){
		$getxx = $this->TwogetPatInfo($kaid,$zzj_id);
		$rel['TwopatInfo'] = $getxx;
	}
	
	$this->writeLog(utf8_to_gbk("获取自费患者就诊记录，返回报文信息转数组：".var_export($rel,true)));
	/*var_dump($rel);*/
	$this->ajaxReturn($rel,"JSON");

}


public function TwogetPatInfo($kaid,$zzj_id){
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');

	$business_type = "预约取号";
	if(strpos($kaid,"H")!==false){
		$hao_ming="07";
	}
	/*******************修改判断用户的卡类型，06身份证，07就诊卡，08用户自己输入的id号******************************/
	else if(strlen($kaid)>=18){
		$hao_ming="06";
	}else if(strlen($kaid)==12){
		$hao_ming="08";
	}
	$s = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root><commitdata><data><datarow hao_ming =\"".$hao_ming."\" code_value =\"".$kaid."\" patient_name=\"\" business_type =\"".$business_type."\" /></data></commitdata><returndata/><operateinfo><info method=\"YYT_QRY_PATI\" opt_id=\"".zzj_id."\" opt_name=\"".$zzj_id."\" opt_ip=\"".get_client_ip()."\" opt_date=\"".date("Y-m-d")."\" guid=\"{".generate_code(10)."}\" token=\"AUTO-YYRMYY-".date("Y-m-d")."\"  /></operateinfo><result><info /></result></root>";

	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_QRY_PATI';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
/*	if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	
	//print_r($doc);
	//返回是否成功状态及相关信息
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	if($result_return['execute_flag']==0){
		/**返回的患者信息
		**@addition_no1 医保卡号
		**@amount
		**@balance 余额
		**@birth_place 出生地
		**@birthday 生日
		**@build_date 建卡日期
		**@card_code 卡类型 21为院内就诊卡
		**@card_no  卡号
		**@charge_type 费用类型
		**@country_chn 国家名称
		**@country_code 国家代码
		**@home_district 区域代码
		**@home_district_chn 区域名称
		**@home_street 家庭住址
		**@home_tel 家庭电话
		**@home_zipcode 家庭邮政编码
		**@marry_chn 婚姻状态
		**@marry_code 婚姻代码
		**@mobile 手机号码
		**@name  患者姓名
		**@nation_chn 民族名称
		**@nation_code 民族代码
		**@p_bar_code 就诊卡号
		**@patient_id HIS编号
		**@response_chn 身份
		**@response_type 身份代码
		**@sex 性别
		**@sex_chn 姓别名称
		**@social_no 身份证号
		**@use_times 使用次数
		**/
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		$rel['datarow'] = $datarow_ary;
		
	}

	return $rel;
	
	//$this->ajaxReturn($rel,"JSON");

} 




function getChuFang($patient_id,$card_code,$card_no,$zzj_id){
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
	$s = "<?xml version='1.0' encoding='utf-8'?><root><commitdata><data><datarow patient_id='".$patient_id."' card_code='".$card_code."' card_no='".$card_no."' query_type='2'  times='0' start_date='".date("Y-m-d")."' end_date='".date("Y-m-d")."' /></data></commitdata><returndata/><operateinfo><info method='YYT_QRY_ORDER' opt_id='".$zzj_id."' opt_name='".$zzj_id."' opt_ip='".get_client_ip()."' opt_date='".date("Y-m-d")."' guid='{".generate_code(10)."}' token='AUTO-YYRMYY-".date("YmdHis")."'  /></operateinfo><result><info /></result></root>";
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s")."获取自费患者处方";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $s;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取自费患者".$pat_code."处方记录传入报文数据：").$s,"INFO");
	/***********日志记录结束****************/
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_QRY_ORDER';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
	/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s")."获取自费患者处方";
	$data['direction'] = "返回报文";
	$data['op_xml'] = $result;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取自费患者".$pat_code."处方记录返回报文数据：".$result),"INFO");
	/********日志记录结束=*******/
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	/*****解析节点data的属性
	**@patient_id 门诊号
	**@pay_seq 划价流水号
	**@count 记录数
	**@charge_total 总金额
	**@cash 个人支付金额
	**@zhzf 医保账户支付
	**@gczf 医保统筹支付
	**@yb_limit_flag 0
	**/
	$data_attr = $doc->returndata->data;
	$data_attr_ary = array();
	foreach($data_attr->attributes() as $a => $b)
    {
		$b = (array)$b;
  		$data_attr_ary[$a] = $b[0];
    }
	$rel['data'] = $data_attr_ary;
	
	
	$datarow =  $doc->returndata->data->datarow;
	$datarow_attr_ary = array();
	$i = 0;
	foreach($datarow as $val){
		$datarow_attrs = array();
		$datarow_sub = array();
		/***解析datarow节点属性
		**@patient_id 病人ID
		**@times 就诊次数
		**@order_no 处方号
		**@ordered_by 开方科室
		**@ordered_doctor 开方医生
		**@row_count 记录数
		**@order_charge 处方金额
		**@req_class 开单类型(诊疗、草药、急诊西药处方等)
		**/
		foreach($val->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_attrs[$a] = $b[0];
		}
		$sub = $val->sub->item;
		/***解析item节点属性
		**@amount 数量
		**@apply_unit 
		**@caoyao_fu 付数 草药相关
		**@charge
		**@charge_no
		**@clinic_cost_item_no 开单项目序号 该病人该次就诊，项目唯一ID
		**@costs 应收费用
		**@detail_name 名称
		**@doctor_code 开单医生（编码）
		**@item_code 项目代码
		**@item_name 项目名称
		**@item_spec 项目类型
		**@order_date 开方日期
		**@order_no 处方号
		**@ordered_by 开单科室
		**@ordered_doctor 开单医生（名称）**@
		**@patient_id 患者ID号
		**@pay_cash 支付金额
		**@pay_seq 支付顺序号
		**@pay_type 付费类型
		**@performed_by 执行科室代码
		**@prices 单价
		**@record_sn 记录号
		**@req_class 开单类别 中药，西药、检验、检查等
		**@times 就诊次数
		**@units 单位 
		**@yb_flag  医保标识
		**/
		foreach($sub as $val2){
			foreach($val2->attributes() as $a => $b){
				$b = (array)$b;
				$datarow_sub[$a] = $b[0];
			}	
			$datarow_attr_ary[$i]['sub'][] = $datarow_sub;
		}
		$datarow_attr_ary[$i]['attr'] = $datarow_attrs;
		$i++;
	}
	$rel['datarow'] = $datarow_attr_ary;
	/**返回是否成功信息
	**@execute_flag 0-成功 -1-不成功
	**@execute_message 提示信息
	**@account 操作时间
	**/
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	
	return $rel;
}
function ajax_huajia(){
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
	$patient_id = I("post.patient_id");
	$query_type = 2;//0-全部 1-已缴费信息 2-未缴费信息
	$times = I("post.times");
	$pay_seq = I("post.pay_seq");
	$start_date = date("Y-m-d");
	$end_date = date("Y-m-d");
	$times_order_no = I("post.times_order_no");
	$zzj_id = I("post.zzj_id");
	switch($card_code){
		case 21://就诊卡
		$method = "YYT_SF_CALC";
		$log_header = "自费";
		break;
		case 20://医保卡
		$method = "YYT_YB_SF_CALC";
		$log_header = "医保";
		break;
	}
	$xml_input = "<?xml version='1.0' encoding='gb2312'?><root><commitdata><data><datarow patient_id='".$patient_id."' card_code='".$card_code."' card_no='".$card_no."' query_type='".$query_type."'  times='0' start_date='".$start_date."' end_date='".$end_date."' pay_seq='".$pay_seq."' times_order_no='".$times_order_no."'/></data></commitdata><returndata/><operateinfo><info method='".$method."' opt_id='".$zzj_id."' opt_name='".$zzj_id."' opt_ip='".get_client_ip()."' opt_date='".date("Y-m-d")."' guid='{".generate_code(10)."}' token='AUTO-YYRMYY-".date("Ymd")."'  /></operateinfo><result><info /></result></root>";
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s").$log_header."划价";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $xml_input;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk($log_header."划价发送报文：").$xml_input);
	/**************日志记录结束*********************/
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = $method;
	$params->requestData = $xml_input;
	$row = $soap->HISImpl___FounderRequestData($params);
	/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s").$log_header."划价";
	$data['direction'] = "HIS返回报文";
	$data['op_xml'] = $result;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk($log_header."划价HIS返回报文：".$result));
	/**********日志记录结束**************/
	$doc = simplexml_load_string($result);
	//print_r($doc);
	//返回是否成功状态及相关信息
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	//返回的划价信息
	$datarow =  $doc->returndata->data->datarow;
	$datarow_ary = array();
	foreach($datarow->attributes() as $a => $b)
	{
		$b = (array)$b;
		$datarow_ary[$a] = $b[0];
	}
	$rel['datarow'] = $datarow_ary;
	$this->ajaxReturn($rel,"JSON");
	
	
}
function yyt_sf_save(){
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
	$patient_id = I("post.patient_id");
	$card_code = I("post.card_code");
	$zzj_id = I("post.zzj_id");
	$card_no = I("post.card_no");
	$responce_type = I("post.responce_type");
	$times = I("post.times");
	$charge_total = I("post.charge_total");
	$pay_charge_total = I("post.pay_charge_total");
	$zhzf = I("post.zhzf");
	$tczf = I("post.tczf");
	$pay_seq = I("post.pay_seq");
	$trade_no = I("post.trade_no");
	$stream_no = I("post.stream_no");
	$pay_type = I("post.pay_type");
	$zzj_id = I("post.zzj_id");
	$source= "hos122";
	if($pay_charge_total==0)
	{
	  $cheque_type="1";
	}else{
			if($pay_type==alipay)
			{
				$cheque_type="c";
				$bk_card_no=  I("post.bk_card_no");
			}else
			{
				$cheque_type="9";
				$bk_card_no=  I("post.bk_card_no");
			}
		}
	$xml_input="<?xml version='1.0' encoding='gb2312'?><root><commitdata><data><datarow pay_seq='".$pay_seq."' patient_id='".$patient_id."' card_code='".$card_code."' card_no='".$card_no."' responce_type='".$responce_type."' settle_flag='1' times='".$times."' charge_total='".$charge_total."' cash='".$pay_charge_total."' zhzf='".$zhzf."' tczf='".$tczf."' bk_card_no='".$bk_card_no."' trade_no='".$trade_no."' stream_no='".$stream_no."' addition_no1='' trade_time='".date("Y-m-d")."' cheque_type='".$cheque_type."' yb_flag='0' start_date='".date("Y-m-d")."' end_date='".date("Y-m-d")."' bank_type=''/></data></commitdata><returndata/><operateinfo><info method='YYT_SF_SAVE' opt_id='".$zzj_id."' opt_name='".$zzj_id."' opt_ip='".get_client_ip()."' opt_date='".date("Y-m-d")."' guid='".$stream_no."' token='AUTO-YYRMYY-".date("Ymd")."'  /></operateinfo><result><info /></result></root>";
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s")."就诊卡HIS费用确认";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $xml_input;
	$data['op_code'] = I("post.op_code");
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("自费HIS保存回写发送报文：".$xml_input));
	/************日志记录结束**************/
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_SF_SAVE';
	$params->requestData = $xml_input;
	$row = $soap->HISImpl___FounderRequestData($params);
	/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s")."就诊卡HIS费用确认";
	$data['direction'] = "返回报文";
	$data['op_xml'] = $result;
	M("logs")->add($data);	
	$this->writeLog(utf8_to_gbk("自费HIS缴费确认返回信息：".$result));
	/************日志记录结束**************/
	
	/**返回是否成功信息
	**@execute_flag 0-成功 -1-不成功
	**@execute_message 提示信息
	**@account 操作时间
	**/
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	//$result_return['execute_flag']=-1;
	if($result_return['execute_flag']==0){
		/*****解析节点data的属性
		**@row_count 记录数
		**@charge_total 总金额 
		**@balance 余额 
		**@trade_no 支付方式交易流水号（银行卡支付 就是银行交易流水号 支付宝就是支付宝交易流水号）
		**@trade_date 交易日期 
		**@stream_no 自助机流水号
		**@cash 个人支付金额 
		**@zhzf 医保支付金额
		**@other_fee 其他支付金额 
		**@cheque_type 支付类型 编码（c-银行卡 9-IC卡）
		**@cheque_name 支付类型名称 （银行卡(c) IC卡(9)）
		**@zhzf
		**/
		$data_attr = $doc->returndata->data;
		$data_attr_ary = array();
		foreach($data_attr->attributes() as $a => $b)
		{
			$b = (array)$b;
			$data_attr_ary[$a] = $b[0];
		}
		$rel['data'] = $data_attr_ary;
		
		$datarow =  $doc->returndata->data->datarow;
		$datarow_attr_ary = array();
		$i = 0;
		foreach($datarow as $val){
			$datarow_attrs = array();
			$datarow_sub = array();
			/***解析datarow节点属性
			**@patient_id 病人ID
			**@patient_name 患者姓名
			**@times 就诊次数
			**@order_no 处方号
			**@exec_sn 执行科室编码 
			**@exec_name 执行科室名称 
			**@apply_name 申请科室名称 
			**@order_charge 处方金额
			**@ledger_sn 结账次数 
			**@receipt_sn 结算流水号 
			**@order_type 
			**@comment
			**/
			foreach($val->attributes() as $a => $b)
			{
				$b = (array)$b;
				$datarow_attrs[$a] = $b[0];
			}
			$sub = $val->item;
			/***解析item节点属性(处方明细)
			**@charge_name 项目名称
			**@specification 规格 
			**@charge_price 单价 
			**@charge_amount 数量 
			**@bill_name 账单名称 
			**@order_no 处方号
			**/
			foreach($sub as $val2){
				foreach($val2->attributes() as $a => $b){
					$b = (array)$b;
					$datarow_sub[$a] = $b[0];
				}	
				$datarow_attr_ary['mingxi'][] = $datarow_sub;
			}
			$datarow_attr_ary['attr'] = $datarow_attrs;
			$i++;
		}
		$rel['datarow'] = $datarow_attr_ary;
	}else{//费用确认失败 开始退费
		if($rel['result']['execute_flag']!=-1)
		{
	        $rel['pay_result']["code"]=$rel['result']['execute_flag'];
	        if($rel['result']['execute_flag']==-2)
	        {
	        	$rel["pay_result"]["SubMsg"]="已经重在完整交易";
	        }
	         if($rel['result']['execute_flag']==-3)
	        {
	        	$rel["pay_result"]["SubMsg"]="his收款失败,请去窗口处理";
	        }
		}else
		{
			//退款代码
			if($cash!==0){
				switch($pay_type){
				case "alipay"://支付宝退费
				$a=M("alipay_logs")->db(2,"DB_CONFIG3")->where("trade_no='{$trade_no}'")->find();
				if($a){
					//var_dump($trade_no,$cash,$stream_no,$source);
					$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');
					$row = $soap->refund($trade_no,$cash,$stream_no,$source);
					$row = str_replace("gb2312", "utf8", $row);
					$xml = simplexml_load_string($row);
					$xml = (array)$xml;
					$rel['pay_result'] = (array)$xml['Message'];

				}
				
				break;


				case "zf_bank":
				$a=M("bank_log")->where("out_trade_no='{$stream_no}'")->find();
				$rel['bank']=$a;
				break;
		   		 }
			}
	    }
	}
	
	
	//$rel['result']['execute_flag']=-1;
	$this->writeLog(utf8_to_gbk("自费收费成功返回数组".var_export($rel,true)));
	$this->ajaxReturn($rel,"JSON");

}
//医保费用确认返回XML解析
function YbSfConfirmXmlParse(){
	// $xml = str_replace("gb2312","utf8",htmlspecialchars_decode(I("post.input_xml")));
	$xml =iconv("utf8","gb2312",htmlspecialchars_decode(I("post.input_xml")));
	$doc = simplexml_load_string($xml);	
	$source= "hos122";
    $trade_no=I("post.trade_no");
    $cash=I("post.cash");
    $stream_no=I("post.stream_no");
	/**************日记记录开始***********/
	$data['card_code'] = '20';
	$data['op_name'] = date("Y-m-d H:i:s")."医保费用确认";
	$data['direction'] = "返回报文";
	$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
	$data['op_code'] = I("post.op_code");
	$pay_type = I("post.pay_type");
	M("logs")->add($data);
	/******************日志记录结束*********************/
	$this->writeLog(utf8_to_gbk("医保费用确认返回XML：").$xml);
	/**返回是否成功信息
	**@execute_flag 0-成功 -1-不成功
	**@execute_message 提示信息
	**@account 操作时间
	**/
	$result_ary = $doc->result->info;
	$result_return = array($result_return);
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	if($result_return['execute_flag']==0){
		/*****解析节点data的属性
		**@row_count 记录数
		**@charge_total 总金额 
		**@balance 余额 
		**@trade_no 支付方式交易流水号（银行卡支付 就是银行交易流水号 支付宝就是支付宝交易流水号）
		**@trade_date 交易日期 
		**@stream_no 自助机流水号
		**@cash 个人支付金额 
		**@zhzf 医保支付金额
		**@other_fee 其他支付金额 
		**@cheque_type 支付类型 编码（c-银行卡 9-IC卡）
		**@cheque_name 支付类型名称 （银行卡(c) IC卡(9)）
		**@zhzf
		**/
		$data_attr = $doc->returndata->data;
		$data_attr_ary = array();
		foreach($data_attr->attributes() as $a => $b)
		{
			$b = (array)$b;
			$data_attr_ary[$a] = $b[0];
		}
		$rel['data'] = $data_attr_ary;
		
		$datarow =  $doc->returndata->data->datarow;
		$datarow_attr_ary = array();
		$i = 0;
		foreach($datarow as $val){
			$datarow_attrs = array();
			$datarow_sub = array();
			/***解析datarow节点属性
			**@patient_id 病人ID
			**@patient_name 患者姓名
			**@times 就诊次数
			**@order_no 处方号
			**@exec_sn 执行科室编码 
			**@exec_name 执行科室名称 
			**@apply_name 申请科室名称 
			**@order_charge 处方金额
			**@ledger_sn 结账次数 
			**@receipt_sn 结算流水号 
			**@order_type 
			**@comment
			**/
			foreach($val->attributes() as $a => $b)
			{
				$b = (array)$b;
				$datarow_attrs[$a] = $b[0];
			}
			$sub = $val->item;
			/***解析item节点属性(处方明细)
			**@charge_name 项目名称
			**@specification 规格 
			**@charge_price 单价 
			**@charge_amount 数量 
			**@bill_name 账单名称 
			**@order_no 处方号
			**/
			foreach($sub as $val2){
				foreach($val2->attributes() as $a => $b){
					$b = (array)$b;
					$datarow_sub[$a] = $b[0];
				}	
				$datarow_attr_ary['mingxi'][] = $datarow_sub;
			}
			$datarow_attr_ary['attr'] = $datarow_attrs;
			$i++;
		}
		$rel['datarow'] = $datarow_attr_ary;
	}else{
		if($rel['result']['execute_flag']!=-1)
		{
	        $rel['pay_result']["code"]=$rel['result']['execute_flag'];
	        if($rel['result']['execute_flag']==-2)
	        {
	        	$rel["pay_result"]["SubMsg"]="已经重在完整交易";
	        }
	         if($rel['result']['execute_flag']==-3)
	        {
	        	$rel["pay_result"]["SubMsg"]="his收款失败,请去窗口处理";
	        }
		}else
		{
			//退款代码
			if($cash!==0){
				switch($pay_type){
				case "alipay"://支付宝退费
				$a=M("alipay_logs")->db(2,"DB_CONFIG3")->where("trade_no='{$trade_no}'")->find();
				if($a){
					//var_dump($trade_no,$cash,$stream_no,$source);
					$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');
					$row = $soap->refund($trade_no,$cash,$stream_no,$source);
					$row = str_replace("gb2312", "utf8", $row);
					$xml = simplexml_load_string($row);
					$xml = (array)$xml;
					$rel['pay_result'] = (array)$xml['Message'];

				}
				
				break;


				case "zf_bank":
				$a=M("bank_log")->where("out_trade_no='{$stream_no}'")->find();
				$rel['bank']=$a;
				break;
		   		 }
			}
	    }
	}
	
	
	
	$this->writeLog(utf8_to_gbk("医保费用确认返回XML转 数组：".var_export($rel,true)));
	$this->ajaxReturn($rel,"JSON");
}
//解析医保划价返回XML
function YbHalcParse(){
	$xml =iconv("utf8","gb2312//IGNORE",htmlspecialchars_decode(I("post.input_xml")));
	
	$this->writeLog(utf8_to_gbk("医保划价返回XML:").$xml,"INFO");
	//返回是否成功状态及相关信息
	$doc = simplexml_load_string($xml);
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	if($result_return['execute_flag']==0){
		//返回的划价信息
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		$rel['datarow'] = $datarow_ary;
		/**************日记记录开始***********/
		$data['card_code'] = '20';
		$data['patient_id'] = $datarow_ary['patient_id'];
		$data['op_name'] = date("Y-m-d H:i:s")."医保划价";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
	}else{
		/**************日记记录开始***********/
		$data['card_code'] = '20';
		$data['op_name'] = date("Y-m-d H:i:s")."医保划价";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
	}
	
	//$this->writeLog(utf8_to_gbk("医保划价返回XML转数组:".var_export($rel,true))); 
	$this->ajaxReturn($rel,"JSON");
	
}
//医保Dll获取患者信息后，通过ajax传入这里获取患者的就诊记录 
function YbXmlParse(){
	$xml = iconv("utf8","gb2312//IGNORE",htmlspecialchars_decode(I("post.input_xml")));
	$doc = simplexml_load_string($xml);
	$this->writeLog(iconv("utf8","gb2312//IGNORE","调用医保DLL获取患者医保返回信息").$xml,"INFO");

	//返回是否成功状态及相关信息
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	if($result_return['execute_flag']==0){
		
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		/**************日记记录开始***********/
		$data['card_code'] = '20';
		$data['patient_id'] = $datarow_ary['patient_id'];
		$data['card_no'] =  $datarow_ary['card_no'];
		$data['op_name'] = date("Y-m-d H:i:s")."调用医保DLL获取患者医保信息";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
		$rel['yb_input_data'] = $datarow_ary;
		//$rel['datarow'] = $datarow_ary;
		//提交去获取就诊记录
		$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
		$input_xml = "<?xml version='1.0' encoding='gb2312'?><root><commitdata><data><datarow patient_id='".$datarow_ary["patient_id"]."' card_code='20' card_no='".$datarow_ary["code"]."' query_type='2'  times='0' start_date='".date("Y-m-d")."' end_date='".date("Y-m-d")."' /></data></commitdata><returndata/><operateinfo><info method='YYT_QRY_ORDER' opt_id='70000' opt_name='ZZJ01' opt_ip='".get_client_ip()."' opt_date='".date("Y-m-d")."' guid='{".generate_code(10)."}' token='AUTO-YYRMYY-".date("Ymd")."'  /></operateinfo><result><info /></result></root>";
		
		/**************日记记录开始***********/
		$data['card_code'] = '20';
		$data['patient_id'] = $datarow_ary['patient_id'];
		$data['card_no'] =  $datarow_ary['card_no'];
		$data['op_name'] = date("Y-m-d H:i:s")."医保患者获取处方信息";
		$data['direction'] = "发送报文";
		$data['op_xml'] = $input_xml;
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
		
		$this->writeLog(iconv("utf8","gb2312//IGNORE","医保患者".$datarow_ary["patient_id"]."获取就诊记录传入报文：".$input_xml));
		$params->userName = '10002'; 
		$params->password = '12345';
		$params->businessType = 'YYT_QRY_ORDER';
		$params->requestData = $input_xml; 
		$row = $soap->HISImpl___FounderRequestData($params);
		/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
		$result = $row->responseData;
		/**************日记记录开始***********/
		$data['card_code'] = '20';
		$data['patient_id'] = $datarow_ary['patient_id'];
		$data['card_no'] =  $datarow_ary['card_no'];
		$data['op_name'] = date("Y-m-d H:i:s")."医保患者获取处方信息";
		$data['direction'] = "返回报文";
		$data['op_xml'] = $result;
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
		$this->writeLog(iconv("utf8","gb2312//IGNORE","医保患者".$datarow_ary["patient_id"]."获取就诊记录返回报文：".$result)); 
		$result = str_replace("gb2312","utf8",$result);
		$doc = simplexml_load_string($result);
		/**返回是否成功信息
		**@execute_flag 0-成功 -1-不成功
		**@execute_message 提示信息
		**@account 操作时间
		**/
		$result_ary = $doc->result->info;
		$result_return = array();
		foreach($result_ary->attributes() as $a => $b)
		{
			$b = (array)$b;
			$result_return[$a] = $b[0];
		}
		$rel['result'] = $result_return;
		if($result_return['execute_flag']==0){
			/*****解析节点data的属性**/
			$data_attr = $doc->returndata->data;
			$data_attr_ary = array();
			foreach($data_attr->attributes() as $a => $b)
			{
				$b = (array)$b;
				$data_attr_ary[$a] = $b[0];
			}
			$rel['data'] = $data_attr_ary;
			
			
			$datarow =  $doc->returndata->data->datarow;
			$datarow_attr_ary = array();
			$i = 0;
			foreach($datarow as $val){
				$datarow_attrs = array();
				$datarow_sub = array();
				/***解析datarow节点属性**/
				foreach($val->attributes() as $a => $b)
				{
					$b = (array)$b;
					$datarow_attrs[$a] = $b[0];
				}
				$sub = $val->sub->item;
				/***解析item节点属性**/
				foreach($sub as $val2){
					foreach($val2->attributes() as $a => $b){
						$b = (array)$b;
						$datarow_sub[$a] = $b[0];
					}	
					$datarow_attr_ary[$i]['sub'][] = $datarow_sub;
				}
				$datarow_attr_ary[$i]['attr'] = $datarow_attrs;
				$i++;
			}
			$rel['datarow'] = $datarow_attr_ary;
		}
		
		
		
		
	}else if($result_return['execute_message']=="有就诊未缴挂号费记录,请先补缴挂号费!" || $result_return['execute_message']=="特病患者请去窗口缴费!" || $result_return['execute_message']=="留观患者请去窗口缴费!"){

		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		$rel['result'] = $result_return;
		$rel['yb_input_data'] = $datarow_ary;

	}else{
		$rel['result'] = $result_return;
		/**************日记记录开始***********/
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
	}
	
	//$this->writeLog(iconv("utf8","gb2312","医保患者获取就诊记录返回前台页面数据：".var_export($rel,true))); 
	
	$this->ajaxReturn($rel,"JSON");
	
}
function getAliPayCode(){
	$wsdl = "http://172.168.0.89/soap1/Service.php?wsdl";

	$soap = new \SoapClient($wsdl);   
	$out_trade_no = trim(I("post.out_trade_no"));
	$total_amount =trim(I("post.total_amount"));
	$subject = trim(I("post.subject"));
	$source="hos122";

	//echo $out_trade_no."#".$total_amount."#".$subject;
	$row = $soap->getPayUrl($out_trade_no,$total_amount,$subject,$source);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;
	$this->ajaxReturn($rel,"JSON");
}
public function ajaxGetPayStatus(){
	$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');   
	$out_trade_no = trim(I("post.out_trade_no"));
		$out_trade_no = trim(I("post.out_trade_no"));
		$data['patient_id'] =$out_trade_no;
		$data['op_name'] = date("Y-m-d H:i:s")."查询支付状态";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
	$source="hos122";
	$row = $soap->getPayStatus($out_trade_no,$source);
		$data['patient_id'] =$out_trade_no;
		$data['op_name'] = date("Y-m-d H:i:s")."查询支付状态";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;
	$this->ajaxReturn($rel,"JSON");
}
public function refund(){
	$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');
	$row = $soap->refund('2017050921001004890240995006','0.01','140626018700201705094636','hos122');
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	print_r($xml);
	}
public function witeJyRecordToDataBase(){
	$data['zzj_id'] = I("post.zzj_id");
	$data['dept_code'] = I("post.dept_code");
	$data['dept_name']=I("post.dept_name");
	$data['doctor_code']=I("post.doctor_code");
	$data['doctor_name']=I("post.doctor_name");
	$data['card_type']=I("post.card_type");
	$data['business_type']=I("post.business_type");
	$data['pat_card_no']=I("post.pat_card_no");
	$data['healthcare_card_no']=I("post.healthcare_card_no");
	$data['id_card_no']=I("post.id_card_no");
	$data['pat_id']=I("post.pat_id");
	$data['pat_name']=I("post.pat_name");
	$data['pat_sex']=I("post.pat_sex");
	$data['charge_total']=I("post.charge_total");
	$data['cash']=I("post.cash");
	$data['zhzf']=I("post.zhzf");
	$data['tczf']=I("post.tczf");
	$data['control_time']=date("Y-m-d H:i:s");
	$data['trading_state']=I("post.trading_state");
	$data['healthcare_card_trade_state']=I("post.healthcare_card_trade_state");
	$data['his_state']=I("post.his_state");
	$data['bank_card_id']=I("post.bank_card_id");
	$data['reg_info']=I("post.reg_info");
	$data['trade_no']=I("post.trade_no");
	$data['control_time'] = date("Y-m-d H:i:s");
	$data['stream_no'] = I("post.stream_no");
	$data['pay_type'] = I("post.pay_type");
	$data["tk_status"] =I("post.tk_status");
	M("auto_log")->add($data);
	$this->writeLog(iconv("utf8","gb2312//IGNORE","交易记录入库信息：").var_export($data,true)); 
	$this->writeLog(iconv("utf8","gb2312//IGNORE","交易记录入库错误信息：").mysql_error()); 
}
public function witeJyRecordToDataBaseBank(){
	$data['RespCode'] = I("post.RespCode");
	$data['RespInfo'] = I("post.RespInfo");
	$data['idCard']=I("post.idCard");
	$data['Amount']=I("post.Amount");
	$data['trade_no']=I("post.trade_no");
	$data['Batch']=I("post.Batch");
	$data['TransDate']=I("post.TransDate");
	$data['Ref']=I("post.Ref");
    $data['out_trade_no']=I("out_trade_no");
	$data['business_type']=I("post.business_type");
	$data['pat_id']=I("post.pat_id");
	$data["op_date"]= date("Y-m-d H:i:s");
	$data['pat_name']=I("post.pat_name");
	M("bank_log")->add($data);
	$this->writeLog(iconv("utf8","gb2312//IGNORE","交易记录入库信息：").var_export($data,true)); 
	$this->writeLog(iconv("utf8","gb2312//IGNORE","交易记录入库错误信息：").mysql_error()); 
}
public function witeJyRecordToDataBaseRefundBank(){
	$out_trade_no = I("out_trade_no");
	$refund_status = I("refund_status");
	$sql = "update bank_log set refund_status='".$refund_status."' where out_trade_no='".$out_trade_no."'";
	M("bank_log")->query($sql);
	$this->writeLog(iconv("utf8","gb2312//IGNORE","交易记录入库信息：").var_export($data,true)); 
	$this->writeLog(iconv("utf8","gb2312//IGNORE","交易记录入库错误信息：").mysql_error()); 

}
function writeLogs(){
	$log_txt = I("post.log_txt");
	$log_type = I("post.log_type");
	
	/**************日记记录开始***********/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = date("Y-m-d H:i:s").I("post.log_txt");
	$data['direction'] = I("post.direction");
	$data['op_code'] = I("post.op_code");
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk($log_txt),$log_type);
	/**********日志记录结束*******************************/
	$rel['success'] = 1;
	$this->ajaxReturn($rel,"JSON");
}
function guocheng(){
	$a = M()->db(1,"DB_CONFIG1")->query("EXEC SM_GetAccountTradeInfo_Detail '01','2016-10-08','2016-10-09' ");
	var_export($a);
}
/**********挂号患者信息查询*******************************/
function getGhPatInfo(){
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
	$kaid = I("post.kaid");//"14220119491221553X";
	$zzj_id = I("post.zzj_id");
	$business_type = I("post.business_type");
	/*$kaid = "000113964700";
	$zzj_id = "ZZJ029";
	$business_type ="自助挂号";*/
	if(strpos($kaid,"H")!==false){
		$hao_ming="07";
	}
	else if(strlen($kaid)>=18){
		$hao_ming="06";
	}else if(strlen($kaid)==12){
		$hao_ming="08";
	}
	//$kaid = "14220119491221553X";
	//$hao_ming="06";
	//$zzj_id =ZZJ01;
	$s = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root><commitdata><data><datarow hao_ming =\"".$hao_ming."\" code_value =\"".$kaid."\" patient_name=\"\" business_type =\"".$business_type."\" /></data></commitdata><returndata/><operateinfo><info method=\"YYT_QRY_PATI\" opt_id=\"".zzj_id."\" opt_name=\"".$zzj_id."\" opt_ip=\"".get_client_ip()."\" opt_date=\"".date("Y-m-d")."\" guid=\"{".generate_code(10)."}\" token=\"AUTO-YYRMYY-".date("Y-m-d")."\"  /></operateinfo><result><info /></result></root>";
	$data['card_code'] = '21';
	$data['card_no'] = $kaid;
	$data['op_name'] = date("Y-m-d H:i:s")."获取挂号就诊卡患者信息";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $s;
	$data['op_code'] = I("post.op_code");
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取挂号自费患者身份信息，发送报文信息：".$s));
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_QRY_PATI';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
	/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	$this->writeLog(utf8_to_gbk("获取挂号自费患者身份信息，返回报文信息：".$result));
	$data2['card_code'] = '21';
	$data2['card_no'] = $kaid;
	$data2['op_xml'] = $result;
	$data2['op_name'] = date("Y-m-d H:i:s")."获取挂号就诊卡患者信息";
	$data2['op_code'] = I("post.op_code");
	$data2['direction'] = "返回报文";
	M("logs")->add($data2);
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['patGhInfo']['result'] = $result_return;
	if($result_return['execute_flag']==0)
	{
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		$rel['patGhInfo']['datarow'] = $datarow_ary;
		if($result_return['execute_flag']==0)
		{
			$gh_flag=1;
			$getCzRoom = $this->getCzRoom($datarow_ary['patient_id'],$datarow_ary['card_code'],$datarow_ary['card_no'],$zzj_id,$gh_flag);
			$rel['room'] = $getCzRoom;
		}
	}
	//echo "<pre>";
	//var_dump($rel);
	if($result_return['execute_message']=="有就诊未缴挂号费记录,请先补缴挂号费!"){
		$getxx = $this->TwogetGhPatInfo($kaid,$zzj_id);
		$rel['TwopatGhInfo'] = $getxx;
	}
	//var_dump($rel);
	$this->ajaxReturn($rel,"JSON");
}



function TwogetGhPatInfo($kaid,$zzj_id){
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
/*	$kaid = I("post.kaid");//"14220119491221553X";
	$zzj_id = I("post.zzj_id");
	$business_type = I("post.business_type");*/
	$business_type="预约取号";
	if(strpos($kaid,"H")!==false){
		$hao_ming="07";
	}
	else if(strlen($kaid)>=18){
		$hao_ming="06";
	}else if(strlen($kaid)==12){
		$hao_ming="08";
	}
	//$kaid = "14220119491221553X";
	//$hao_ming="06";
	//$zzj_id =ZZJ01;
	$s = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root><commitdata><data><datarow hao_ming =\"".$hao_ming."\" code_value =\"".$kaid."\" patient_name=\"\" business_type =\"".$business_type."\" /></data></commitdata><returndata/><operateinfo><info method=\"YYT_QRY_PATI\" opt_id=\"".zzj_id."\" opt_name=\"".$zzj_id."\" opt_ip=\"".get_client_ip()."\" opt_date=\"".date("Y-m-d")."\" guid=\"{".generate_code(10)."}\" token=\"AUTO-YYRMYY-".date("Y-m-d")."\"  /></operateinfo><result><info /></result></root>";
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_QRY_PATI';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
	/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	if($result_return['execute_flag']==0)
	{
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		$rel['datarow'] = $datarow_ary;
		
	}
	
	//echo "<pre>";
	//var_dump($rel);
	return $rel;
	//$this->ajaxReturn($rel,"JSON");
}






/**********出诊科室查询*******************************/
function getCzRoom($patient_id,$card_code,$card_no,$zzj_id,$gh_flag){
	//$gh_flag=1;
	//$zzj_id=ZZJ01;
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
	$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow  start_date="'.date("Y-m-d").'" end_date="'.date("Y-m-d").'" gh_flag="'.$gh_flag.'" class_code="%" /></data></commitdata><returndata/><operateinfo><info method=" YYT_QRY_CLINIC_DEPT " opt_id="'.$zzj_id.'" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.date("Y-m-d").'" guid="\{'.generate_code(10).'}\" token="AUTO-YYRMYY-'.date("Y-m-d").'"  /></operateinfo><result><info /></result></root>';
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s")."获取出诊科室";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $s;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取出诊科室".$pat_code."出诊科室传入报文数据：").$s,"INFO");
	/***********日志记录结束****************/
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_QRY_CLINIC_DEPT';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
	/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s")."获取出诊科室"+date("Y-m-d H:i:s");
	$data['direction'] = "返回报文";
	$data['op_xml'] = $result;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取出诊科室".$pat_code."出诊科室返回报文数据：".$result),"INFO");
	/********日志记录结束=*******/
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	if($result_return['execute_flag']==0)
	{
			$datarow = $doc->returndata->data->datarow;
			$datarow_attr_ary = array();
			$i=0;
			foreach($datarow as $val)
			{
				$datarow_attrs = array();
				$datarow_sub = array();
				foreach($val->attributes() as $a => $b)
				{
					$b = (array)$b;
					$datarow_attrs[$a] = $b[0];
				}
				$datarow_attr_ary[$i]['attr'] = $datarow_attrs;
				$sub =$val->item;
				foreach($sub as $val2)
				{
					foreach($val2->attributes() as $a => $b)
					{
						$b = (array)$b;
						$datarow_sub[$a] = $b[0];
					}
					$datarow_attr_ary[$i]['sub'][] = $datarow_sub;
				}
				$datarow_attr_ary[$i]['attr'] = $datarow_attrs;
				$i++;
			}
			$rel['datarow'] = $datarow_attr_ary;
	}
	//echo "<pre>";
	//var_dump($rel);
	return $rel;
	}
/**********排班信息查询*******************************/
function getSchedInfo(){
	$unit_sn = I("post.unit_sn");
	$zzj_id = I("post.zzj_id");
	//$unit_sn ="0103010" ;
	//$zzj_id =ZZJ01;
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
	$s='<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow unit_sn="'.$unit_sn.'" doctor_sn="" group_sn="" doctor_py="" clinic_type="" start_date="'.date("Y-m-d").'" end_date="'.date("Y-m-d").'" gh_flag="1"/></data></commitdata><returndata/><operateinfo><info method="YYT_QRY_REQUEST" opt_id="700000" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.date("Y-m-d").'" guid="'.generate_code(10).'" token="AUTO-YYRMYY-'.date("Y-m-d").'" /></operateinfo><result><info />
</result></root>';
/**************日记记录开始***********/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = date("Y-m-d H:i:s")."获取出诊医生";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $s;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取出出诊医生".$pat_code."出诊医生传入报文数据：").$s,"INFO");
/**************日记记录开始***********/
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_QRY_REQUEST';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
	/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	/**************日记记录开始***********/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = date("Y-m-d H:i:s")."获取出诊医生";
	$data['direction'] = "返回报文";
	$data['op_xml'] = $result;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取自费患者".$pat_code."出诊医生返回报文数据：".$result),"INFO");
	/********日志记录结束=*******/
	$doc = simplexml_load_string($result);

	//var_dump($doc);exit;
	$result_ary = $doc->result->info;

	//var_dump($result_ary);exit;
	$result_return=array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	if($result_return['execute_flag']==0)
	{
		$datarow = $doc->returndata->data->datarow;
		
		$datarow_attr_ary = array();
		foreach($datarow as $val)
		{
			$datarow_attrs = array();
			foreach($val->attributes() as $a => $b)
			{
				$b = (array)$b;
				$datarow_attrs[$a] = $b[0];
			}
			$datarow_attr_ary[] = $datarow_attrs;
		}
		//$rel['datarow'] = $datarow_attr_ary;
	}

	//	var_dump($datarow_attr_ary);exit;
if(count($datarow_attr_ary) > 1){

		$arr = array_map(create_function('$n', 'return $n["sum_fee"];'), $datarow_attr_ary);
		$arr=array_multisort($arr,SORT_DESC,$datarow_attr_ary);
		/*$arr1=array();
		$arr2=array();*/

		foreach ($datarow_attr_ary as $key => $value) {
			if($value['ampm']=="a"){
				$arr1[] =$value;
			}else{
				$arr2[] =$value;
			}
		}

		if($arr1 !==""   && $arr2 ==""){
			$datarow = $arr1;
		}else if($arr2 !==""   && $arr1 ==""){
			$datarow = $arr2;
		}else {
			$datarow=array_merge($arr1,$arr2);
		}
		//var_dump($datarow);exit;
		

		$rel['datarow'] = $datarow;

		}else{
			$rel['datarow'] = $datarow_attr_ary;
		}

	  $rel["moring_time"]= strtotime(date("Y-m-d"))+11.5*3600;
      $rel["moon_time"] = strtotime(date("Y-m-d"))+16.5*3600;
      $rel["now_time"] = time();
	//echo "<pre>";
	//var_dump($rel);
	$this->ajaxReturn($rel,"JSON");
}
/**********挂号划价*******************************/
function reg_huajia(){
	$record_sn = I("post.record_sn");
	$patient_id =I("post.patient_id");
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
	$req_type = I("post.req_type");
	$zzj_id = I("post.zzj_id");
	switch($card_code){
		case 21://就诊卡
		$method = "YYT_GH_CALC";
		$log_header = "自费划价";
		break;
		case 20://医保卡
		$method = "YYT_GH_CALC";
		$log_header = "号源占位";
		break;
	}
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
	$s='<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow record_sn="'.$record_sn.'" patient_id="'.$patient_id.'" card_code="'.$card_code.'" card_no="'.$card_no.'" req_type="'.$req_type.'"  gh_flag="1" /></data></commitdata><returndata/><operateinfo><info method="'.$method.'" opt_id="'.$zzj_id.'" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.date("Y-m-d").'" guid="'.generate_code(10).'" token="AUTO-YYRMYY-'.date("Y-m-d").'"/></operateinfo><result><info /></result></root>';
/**********日志记录开始*******************************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s").$log_header."划价";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $s;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk($log_header."发送报文信息：".$s));
/**********日志记录结束*******************************/
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = $method;
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
	/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	/**************日记记录开始***********/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s").$log_header."划价";
	$data['direction'] = "HIS返回报文";
	$data['op_xml'] = $result;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk($log_header."HIS返回报文：".$result));
	$doc = simplexml_load_string($result);
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	//返回的划价信息
	$datarow =  $doc->returndata->data->datarow;
	$datarow_ary = array();
	foreach($datarow->attributes() as $a => $b)
	{
		$b = (array)$b;
		$datarow_ary[$a] = $b[0];
	}
	$rel['datarow'] = $datarow_ary;
	$this->ajaxReturn($rel,"JSON");
	}
/**********自助挂号His费用确认*******************************/
function yyt_gh_save(){
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
	$record_sn  = I("post.record_sn");
	$patient_id = I("post.patient_id");
	$card_code = I("post.card_code");
	$zzj_id = I("post.zzj_id");
	$card_no = I("post.card_no");
	$responce_type = I("post.responce_type");
	$charge_total = I("post.charge_total");
	$cash = I("post.pay_charge_total");
	$zhzf = I("post.zhzf");
	$tczf = I("post.tczf");
	$pay_seq = I("post.pay_seq");
	$trade_no = I("post.trade_no");
	$stream_no = I("post.stream_no");
	$pay_type = I("post.pay_type");
	$zzj_id = I("post.zzj_id");
	$bk_card_no=  I("post.bk_card_no");
	$source="hos122";
	if($cash==0)
	{
	  $cheque_type="1";
	}else{
			if($pay_type==alipay)
			{
				$cheque_type="c";
			}else
			{
				$cheque_type="9";
				$bk_card_no=  I("post.bk_card_no");
			}
		}
	$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow record_sn="'.$record_sn.'"  pay_seq="'.$pay_seq.'" responce_type="'.$responce_type.'" patient_id="'.$patient_id.'" card_code="'.$card_code.'" card_no="'.$card_no.'" charge_total="'.$charge_total.'" cash="'.$cash.'" zhzf="'.$zhzf.'" tczf="'.$tczf.'"  record_id="" gh_sequence="" bk_card_no="'.$bk_card_no.'" trade_no="'.$trade_no.'" stream_no="'.$stream_no.'" addition_no1="" trade_time="'.date("Y-m-d").'" cheque_type="'.$cheque_type.'" gh_flag="1" bank_type=""/></data></commitdata><returndata/><operateinfo><info method="YYT_GH_SAVE" opt_id="'.$zzj_id.'" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.date("Y-m-d").'" guid="'.$stream_no.'" token="AUTO-YYRMYY-'.date("Y-m-d").'"  /></operateinfo><result><info /></result></root>
';
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s")."挂号就诊卡HIS费用确认";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $s;
	$data['op_code'] = I("post.op_code");
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("自费挂号HIS保存回写发送报文：".$xml_input));
	/************日志记录结束**************/
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_GH_SAVE';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
	/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = date("Y-m-d H:i:s")."挂号就诊卡HIS费用确认";
	$data['direction'] = "返回报文";
	$data['op_xml'] = $result;
	M("logs")->add($data);	
	$this->writeLog(utf8_to_gbk("自费HIS挂号认返回信息：".$result));
	/************日志记录结束**************/
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	//返回的划价信息
	if($result_return['execute_flag']==0){
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
	}else
	{

		if($rel['result']['execute_flag']!=-1)
		{
	        $rel['pay_result']["code"]=$rel['result']['execute_flag'];
	        if($rel['result']['execute_flag']==-2)
	        {
	        	$rel["pay_result"]["SubMsg"]="已经重在完整交易";
	        }
	         if($rel['result']['execute_flag']==-3)
	        {
	        	$rel["pay_result"]["SubMsg"]="his收款失败,请去窗口处理";
	        }
		}else
		{
			//退款代码
			if($cash!==0){
				switch($pay_type){
				case "alipay"://支付宝退费
				$a=M("alipay_logs")->db(2,"DB_CONFIG3")->where("trade_no='{$trade_no}'")->find();
				if($a){
					//var_dump($trade_no,$cash,$stream_no,$source);
					$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');
					$row = $soap->refund($trade_no,$cash,$stream_no,$source);
					$row = str_replace("gb2312", "utf8", $row);
					$xml = simplexml_load_string($row);
					$xml = (array)$xml;
					$rel['pay_result'] = (array)$xml['Message'];

				}
				
				break;


				case "zf_bank":
				$a=M("bank_log")->where("out_trade_no='{$stream_no}'")->find();
				$rel['bank']=$a;
				break;
		   		 }
			}
			


	   }
	}
	//$rel['result']['execute_flag']==-3;
	$rel['datarow'] = $datarow_ary;
	$this->ajaxReturn($rel,"JSON");

}
//挂号调用医保Dll获取患者信息后，通过ajax传入这里获取出诊科室列表 
function YbXmlParseGhao(){
	$xml = iconv("utf8","gb2312//IGNORE",htmlspecialchars_decode(I("post.input_xml")));
	$doc = simplexml_load_string($xml);
	$this->writeLog(iconv("utf8","gb2312//IGNORE","调用医保DLL获取患者医保返回信息").$xml,"INFO");
	//返回是否成功状态及相关信息
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	if($result_return['execute_flag']==0){
		
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		$rel['yb_input_data'] = $datarow_ary;
		/**************日记记录开始***********/
		$data['card_code'] = '20';
		$data['patient_id'] = $datarow_ary['patient_id'];
		$data['card_no'] =  $datarow_ary['card_no'];
		$data['op_name'] = date("Y-m-d H:i:s")."调用医保DLL获取患者医保信息";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
		/******************获取出诊科室*********************/
		$gh_flag=1;
		$getCzRoom = $this->getCzRoom($datarow_ary['patient_id'],$datarow_ary['card_code'],$datarow_ary['card_no'],$zzj_id,$gh_flag);
		$rel['room'] = $getCzRoom;
	}else if($result_return['execute_message']=="有就诊未缴挂号费记录,请先补缴挂号费!"){

		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		$rel['yb_input_data'] = $datarow_ary;

	}else{
		$rel['result'] = $result_return;
		/**************日记记录开始***********/
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
	} 
	$this->ajaxReturn($rel,"JSON");	
}
//解析医保划价返回XML
function YbHalcParseGhao(){
	$xml =iconv("utf8","gb2312//IGNORE",htmlspecialchars_decode(I("post.input_xml")));
	
	$this->writeLog(utf8_to_gbk("医保划价返回XML:").$xml,"INFO");
	//返回是否成功状态及相关信息
	$doc = simplexml_load_string($xml);
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	if($result_return['execute_flag']==0){
		//返回的划价信息
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		$rel['datarow'] = $datarow_ary;
		/**************日记记录开始***********/
		$data['card_code'] = '20';
		$data['patient_id'] = $datarow_ary['patient_id'];
		$data['op_name'] = date("Y-m-d H:i:s")."医保划价";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
	}else{
		/**************日记记录开始***********/
		$data['card_code'] = '20';
		$data['op_name'] = date("Y-m-d H:i:s")."医保划价";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
	}
	
	//$this->writeLog(utf8_to_gbk("医保划价返回XML转数组:".var_export($rel,true))); 
	$this->ajaxReturn($rel,"JSON");
}
//解锁号源方法
function gh_unlock(){
	$opt_ip = get_client_ip();
	$patient_id = I("post.patient_id");
	$soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
	$s='<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow machine_no="'.$opt_ip.'" patient_id="'.$patient_id .'" /></data></commitdata><returndata/><operateinfo><info method="YYT_GH_UNLOCK" opt_id="00000" opt_name="00000" opt_ip="'.$opt_ip.'" opt_date="'.date("Y-m-d").'" guid="" token=""  /></operateinfo><result><info/></result></root>';
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_GH_UNLOCK';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
	/*if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->HISImpl___FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}*/
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$data['op_code'] = I("post.op_code");
	$data['op_name'] = date("Y-m-d H:i:s")."号源解锁返回报文";
	$data['direction'] = "返回报文";
	$data['op_xml'] = $result;
	M("logs")->add($data);
}
//挂号费用确认解析
function YbSfConfirmXmlParseGhao(){

	// $xml = str_replace("gb2312","utf8",htmlspecialchars_decode(I("post.input_xml")));
	$xml =iconv("utf8","gb2312//IGNORE",htmlspecialchars_decode(I("post.input_xml")));
	$doc = simplexml_load_string($xml);	
	$source= "hos122";
    $trade_no=I("post.trade_no");
    $cash=I("post.cash");
    $stream_no=I("post.stream_no");
	/**************日记记录开始***********/
	$data['card_code'] = '20';
	$data['op_name'] = date("Y-m-d H:i:s")."挂号医保费用确认";
	$data['direction'] = "返回报文";
	$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
	$data['op_code'] = I("post.op_code");
	$pay_type = I("post.pay_type");
	M("logs")->add($data);
	/******************日志记录结束*********************/
	$this->writeLog(utf8_to_gbk("医保费用确认返回XML：").$xml);
	/**返回是否成功信息
	**@execute_flag 0-成功 -1-不成功
	**@execute_message 提示信息
	**@account 操作时间
	**/
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	if($result_return['execute_flag']==0){
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		$rel['datarow'] = $datarow_ary;
	}else
	{
		if($rel['result']['execute_flag']!=-1)
		{
	        $rel['pay_result']["code"]=$rel['result']['execute_flag'];
	        if($rel['result']['execute_flag']==-2)
	        {
	        	$rel["pay_result"]["SubMsg"]="已经重在完整交易";
	        }
	         if($rel['result']['execute_flag']==-3)
	        {
	        	$rel["pay_result"]["SubMsg"]="his收款失败,请去窗口处理";
	        }
		}else
		{
			if($cash!==0){
			
				switch($pay_type){
				case "alipay"://支付宝退费
				$a=M("alipay_logs")->db(2,"DB_CONFIG3")->where("trade_no='{$trade_no}'")->find();
				if($a){
					//var_dump($trade_no,$cash,$stream_no,$source);
					$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');
					$row = $soap->refund($trade_no,$cash,$stream_no,$source);
					$row = str_replace("gb2312", "utf8", $row);
					$xml = simplexml_load_string($row);
					$xml = (array)$xml;
					$rel['pay_result'] = (array)$xml['Message'];

				}
				
				break;



				case "zf_bank":
				$a=M("bank_log")->where("out_trade_no='{$stream_no}'")->find();
				$rel['bank']=$a;
				break;
		   		 }
			}
	    }
		
		}
	//$this->writeLog(utf8_to_gbk("医保费用确认返回XML转 数组：".var_export($rel,true)));
	//iconv("utf8","gb2312",htmlspecialchars_decode(I("post.input_xml")))
	$this->writeLog(iconv("utf8","gbk//IGNORE","医保费用确认返回XML转数组：".var_export($rel,true)));
	$this->ajaxReturn($rel,"JSON");
}
 public function nihao(){
 	$a="流水号【{1489728177}】已经存在完整的交易，不允许再次发起交易!";
 	$b="已经存在完整的交易";
 	if(strpos($a,$b)){
 		echo 1;
 	}
 }
 //用来记录机器开关机状态
	public function setKGJ(){
		$zzj_id = I("post.zzj_id");
		
		$ary = array(
			'condition'=>time()
	    );
        $result = M()->table("devstatus")->where("DevName = '{$zzj_id}'")->save($ary);
	}
 //用来记录每次交易成功的记录
	public function transaction_logs(){
        
        // patient_id    患者id
        $user_id = I("post.patient_id");
		// user_name     患者姓名
		$user_name = I("post.user_name");
		// out_trade_no  订单号
		$out_trade_no = I("post.out_trade_no");
		// transaction_type  交易类型
		$transaction_type = I("post.transaction_type");
		if($transaction_type == '21'){
            $transaction_type = '自费';
		}else{
			$transaction_type = '医保';
		}
		// transaction_mode  支付方式
		$transaction_mode = I("post.transaction_mode");
		if($transaction_mode == 'alipay'){
			$transaction_mode = "支付宝";
		}else{
			$transaction_mode = "银行卡";
		}
		// transaction_conente  交易内容
		$transaction_conente = I("post.transaction_conente");
		// original_cost 原价
		$original_cost = I("post.original_cost");
		// current_price 现价
		$current_price = I("post.current_price");
		// zzj_id        自助机id
        $zzj_id = I("post.zzj_id");

        $ary = array(
        	'user_id'=>$user_id,
        	'user_name'=>$user_name,
        	'out_trade_no'=>$out_trade_no,
            'transaction_type'=>$transaction_type,
            'transaction_mode'=>$transaction_mode,
            'transaction_content'=>$transaction_conente,
            'transaction_c'=>'交易成功',
            'original_cost'=>$original_cost,
            'current_price'=>$current_price,
            'trading_hour'=>date("Y-m-d H:i:s"),
            'zzj_id'=>$zzj_id            
        );
        $result = M()->table("condition_logs")->add($ary);
	}
  //向后退卡密码验证
 public function pwd_yz_tk(){
 	$pwd = I("post.pwd_yz_tk");
 	$list = M("pwd")->where("Uname = '退卡' and pwd={$pwd}")->find();
 	if($list){
        $a = 1;//密码正确
 	}else{
        $a = 0;//密码错误
 	}
 	$this->ajaxReturn($a,"JSON");
 }





public function ic_check(){
 	$soap = new \SoapClient('http://172.168.0.241/soap/Service.php?wsdl');
 	$doctor_sn=I("post.doctor_sn");
 	$unit_sn=I("post.unit_sn");
 	$sequence_no=I("post.sequence_no");
 	//var_dump($soap);
 	$row = $soap->waitinfo($doctor_sn,$unit_sn,$sequence_no);
 	//$row = $soap->waitinfo('','0501030','3');//

 	$xml = simplexml_load_string($row);	
 	$doc = (array)$xml;
 	//var_dump($doc);
 	$rel =  array();
 	foreach($doc as $key=>$val){
 		
 		if(is_object($val)){
 			$val = (array)$val;
 			foreach($val as $vv){
                $rel[$key] = $vv;
 			} 			
 		}else{
            $rel[$key] = $val;
 		}
 	}
//var_dump($rel);
	$this->ajaxReturn($rel,"JSON");	
 }












 
}