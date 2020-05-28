<?php
namespace ZiZhu\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
class ChaXunController extends CommonController {
	public function index(){

		$zzj_id = I("get.zzj_id");
		$this->assign("zzj_id",$zzj_id);
		$this->display();
	}

	//获取服务器时间
	public function getdangqian_timme(){
		$time = date('Y年m月d日',time());
		$time_1 = "星期".mb_substr( "日一二三四五六",date("w"),1,"utf-8" );
		$time_2 = date('H:i',time());
		$rel['rq'] = $time;
		$rel['xq'] = $time_1;
		$rel['sj'] = $time_2;
		$this->ajaxReturn($rel,"JSON");
	}

	
	/**********挂号获取患者信息*******************************/
	public function getCxPatInfo()
	{
//		 $soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
//		 $soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
		$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
		$kaid = I("post.kaid"); //"14220119491221553X";
		$zzj_id = I("post.zzj_id");
		$business_type = I("post.business_type");
		$cx_time = I("post.cx_time");
		// 获取当前要查询的日期
		if($cx_time=='1'){
			$start_time = I("post.start_week_time");
			$end_time = I("post.end_week_time");
		}else if($cx_time=='2'){
			$start_time = I("post.start_month_time");
			$end_time = I("post.end_month_time");
		}else{
			$start_time = I('post.start_week_time');
			$end_time = I('post.end_week_time');
		}
//		 $kaid = '000144001800';
//		 $zzj_id = "zzj029";
//		 $business_type ="自助查询";
		if (strpos($kaid, "H") !== false) {
			$hao_ming = "07";
		} else if (strlen($kaid) >= 18) {
			$hao_ming = "06";
		} else if (strlen($kaid) == 12) {
			$hao_ming = "08";
		}
		//$kaid = "14220119491221553X";  2
		//$hao_ming="06";
		//$zzj_id =ZZJ01;
		$s = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root><commitdata><data><datarow hao_ming =\"" . $hao_ming . "\" code_value =\"" . $kaid . "\" patient_name=\"\" business_type =\"" . $business_type . "\" /></data></commitdata><returndata/><operateinfo><info method=\"YYT_QRY_PATI\" opt_id=\"" . $zzj_id . "\" opt_name=\"" . $zzj_id . "\" opt_ip=\"" . get_client_ip() . "\" opt_date=\"" . date("Y-m-d") . "\" guid=\"{" . generate_code(10) . "}\" token=\"AUTO-YYRMYY-" . date("Y-m-d") . "\"  /></operateinfo><result><info /></result></root>";
		$data['card_code'] = '21';
		$data['card_no'] = $kaid;
		$data['patient_id'] = $kaid;
		$data['op_name'] = "获取挂号就诊卡患者信息";
		$data['direction'] = "发送报文";
		$data['op_xml'] = $s;
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		$this->writeLog(utf8_to_gbk("获取挂号自费患者身份信息，发送报文信息：" . $s));
		$params->userName = '10002';
		$params->password = '12345';
		$params->businessType = 'YYT_QRY_PATI';
		$params->requestData = $s;
		 $row = $soap->FounderRequestData($params);
//		$row = $soap->HISImpl___FounderRequestData($params);

		$result = $row->responseData;
		$result = str_replace("gb2312", "utf8", $result);
		$doc = simplexml_load_string($result);
		// echo "<pre>";
		// var_dump($doc);exit;
		$this->writeLog(utf8_to_gbk("获取挂号自费患者身份信息，返回报文信息：" . $result));
		$data2['card_code'] = '21';
		$data2['card_no'] = $kaid;
		$data2['op_xml'] = $result;
		$data2['op_name'] = "his返回挂号患者就诊卡信息";
		$data2['op_code'] = I("post.op_code");
		$data2['direction'] = "返回报文";
		M("logs")->add($data2);
		$result_ary = $doc->result->info;
		// var_dump($result_ary);exit;
		$result_return = array();
		foreach ($result_ary->attributes() as $a => $b) {
			$b = (array) $b;
			$result_return[$a] = $b[0];
		}
		$rel['patGhInfo']['result'] = $result_return;
		// var_dump($rel);exit;
		if ($result_return['execute_flag'] == 0) {
			$datarow = $doc->returndata->data->datarow;
			$datarow_ary = array();
			foreach ($datarow->attributes() as $a => $b) {
				$b = (array) $b;
				$datarow_ary[$a] = $b[0];
			}
			$rel['patGhInfo']['datarow'] = $datarow_ary;
			// var_dump($rel);exit;
			if ($result_return['execute_flag'] == 0) {
				$query_type = '%';
				$getPatGhRecord = $this->getPatGhRecord($start_time, $end_time, $datarow_ary['patient_id'], $datarow_ary['card_code'], $datarow_ary['card_no'], $query_type, $zzj_id);
				// var_dump($getPatGhRecord);
				$rel['gh_data'] = $getPatGhRecord;
			}
		}
		$this->ajaxReturn($rel, "JSON");
	}

	/**********自助查询患者缴费信息查询*******************************/
	public function getCxPatInfoJF(){
//		 $soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
//		 $soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
		$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
		$kaid = I("post.kaid");
		$zzj_id = I("post.zzj_id");
		$business_type = I("post.business_type");
//			$kaid = "000144001800";
//			$zzj_id = "zzj029";
//			$business_type ="自助缴费";
		$cx_jf_time = I('post.cx_jf_time');
		if($cx_jf_time=='1'){
			$start_time = I('post.start_week_time_jf');
			$end_time = I('post.end_week_time_jf');
		}else if($cx_jf_time=='2'){
			$start_time = I('post.start_week_time_jf');
			$end_time = I('post.end_week_time_jf');
		}else{
			$start_time = I('post.start_week_time_jf');
			$end_time = I('post.end_week_time_jf');
		}

		if(strpos($kaid,"H")!==false){
			$hao_ming="07";
		}
		/*******************修改判断用户的卡类型，06身份证，07就诊卡，08用户自己输入的id号******************************/
		else if(strlen($kaid)>=18){
			$hao_ming="06";
		}else if(strlen($kaid)==12){
			$hao_ming="08";
		}
		$s = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root><commitdata><data><datarow hao_ming =\"".$hao_ming."\" code_value =\"".$kaid."\" patient_name=\"\" business_type =\"".$business_type."\" /></data></commitdata><returndata/><operateinfo><info method=\"YYT_QRY_PATI\" opt_id=\"".zzj_id."\" opt_name=\"".$zzj_id."\" opt_ip=\"".get_client_ip()."\" opt_date=\"".date("Y-m-d")."\" guid=\"{".generate_code(10)."}\" token=\"AUTO-YYRMYY-".date("Ymd")."\"  /></operateinfo><result><info /></result></root>";
		$data['card_code'] = '21';
		$data['card_no'] = $kaid;
		$data['op_name'] = "获取缴费患者就诊卡信息";
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
		 $row = $soap->FounderRequestData($params);
//		$row = $soap->HISImpl___FounderRequestData($params);

		$result = $row->responseData;
		$result = str_replace("gb2312","utf8",$result);
		$doc = simplexml_load_string($result);
		// var_dump($doc);exit;
		$this->writeLog(utf8_to_gbk("获取自费患者身份信息，返回报文信息：".$result));
		$data2['card_code'] = '21';
		$data2['card_no'] = $kaid;
		$data2['op_xml'] = $result;
		$data2['op_name'] = "his返回缴费患者就诊卡信息";
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
				$query_type = '0';
				$ledger_sn = '0';//结算次数
				$getPatJFRecord = $this->getPatJFRecord($start_time,$end_time,$datarow_ary['patient_id'],$datarow_ary['card_code'],$datarow_ary['card_no'],$query_type,$zzj_id,$ledger_sn);
				// var_dump($getPatJFRecord);
				$rel['jf_data'] = $getPatJFRecord;
			}
		}
		$this->ajaxReturn($rel,"JSON");
	}

	/*********************预约记录查询患者个人信息 *************************/
	public function getCxPatInfoYY(){
//	    $soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
//		 $soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
        $soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
		$kaid = I("post.kaid");   //编码 依照号名类型来定义，如医保卡号，身份证等等
		$zzj_id = I("post.zzj_id");
//		$kaid='000144001800';
//		$zzj_id='zzj029';
		$business_type = I("post.business_type");
		$cx_time = I("post.cx_time");
		// 获取当前要查询的日期
		if ($cx_time == '1') {
			$start_time = I("post.start_week_time");
			$end_time = I("post.end_week_time");
		} else if ($cx_time == '2') {
			$start_time = I("post.start_month_time");
			$end_time = I("post.end_month_time");
		} else {
			$start_time = I('post.start_week_time');
			$end_time = I('post.end_week_time');
		}
		
		if(strpos($kaid,"H")!==false){
			$hao_ming="07";
		}
		/*******************修改判断用户的卡类型，06身份证，07就诊卡，08用户自己输入的id号******************************/
		else if(strlen($kaid)>=18){
			$hao_ming="06";
		}else if(strlen($kaid)==12){
			$hao_ming="08";
		}
		$s = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root><commitdata><data><datarow hao_ming =\"".$hao_ming."\" code_value =\"".$kaid."\" patient_name=\"\" business_type =\"".$business_type."\" /></data></commitdata><returndata/><operateinfo><info method=\"YYT_QRY_PATI\" opt_id=\"".zzj_id."\" opt_name=\"".$zzj_id."\" opt_ip=\"".get_client_ip()."\" opt_date=\"".date("Y-m-d")."\" guid=\"{".generate_code(10)."}\" token=\"AUTO-YYRMYY-".date("Ymd")."\"  /></operateinfo><result><info /></result></root>";
		// var_dump($s);exit;
		$data['card_code'] = '21';
		$data['card_no'] = $kaid;
		$data['op_name'] = "预约查询获取就诊卡患者信息";
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
		 $row = $soap->FounderRequestData($params);
//		$row = $soap->HISImpl___FounderRequestData($params);

		$result = $row->responseData;
		$result = str_replace("gb2312","utf8",$result);
		$doc = simplexml_load_string($result);
		// var_dump($doc);exit;
		$this->writeLog(utf8_to_gbk("获取自费患者身份信息，返回报文信息：".$result));
		$data2['card_code'] = '21';
		$data2['card_no'] = $kaid;
		$data2['op_xml'] = $result;
		$data2['op_name'] = "his返回患者就诊卡信息";
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
		$rel['patYyInfo']['result'] = $result_return;
		if($result_return['execute_flag']==0){
			/**返回的患者信息
			**@addition_no1 医保卡号
			**@amount
			**@balance 余额S
			**@birth_place 出生地
			**@birthday 生日
			**@build_date 建卡日期S
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
			$rel['patYyInfo']['datarow'] = $datarow_ary;
			
			if($result_return['execute_flag']==0){
				$getPatYyRecord = $this->getPatYyRecord($start_time,$end_time,$datarow_ary['patient_id'],$datarow_ary['card_code'],$datarow_ary['card_no'],$zzj_id);
				// var_dump($getPatYyRecord);
				$rel['yy_data'] = $getPatYyRecord;
			}
		}
		
		$this->ajaxReturn($rel,"JSON");

	}

	//挂号调用医保Dll获取患者信息
	public function YbXmlParseGhao()
	{
		$zzj_id = I("post.zzj_id");
		$cx_time = I('post.cx_time');
		$business_type = I('post.business_type');
		if($cx_time=='1'){
			$start_time = I("post.start_week_time");
			$end_time = I('post.end_week_time');
		}else if($cx_time=='2'){
			$start_time = I("post.start_month_time");
			$end_time = I('post.end_month_time');
		}else{
			$start_time = I("post.start_week_time");
			$end_time = I('post.end_week_time');
		}

		$xml = iconv("utf8", "gb2312//IGNORE", htmlspecialchars_decode(I("post.input_xml")));
		$doc = simplexml_load_string($xml);
		$this->writeLog(iconv("utf8", "gb2312//IGNORE", "调用医保DLL获取患者医保返回信息") . $xml, "INFO");
		//返回是否成功状态及相关信息
		$result_ary = $doc->result->info;
		$result_return = array();
		foreach ($result_ary->attributes() as $a => $b) {
			$b = (array) $b;
			$result_return[$a] = $b[0];
		}
		$rel['result'] = $result_return;
		if ($result_return['execute_flag'] == 0) {
			$datarow = $doc->returndata->data->datarow;
			$datarow_ary = array();
			foreach ($datarow->attributes() as $a => $b) {
				$b = (array) $b;
				$datarow_ary[$a] = $b[0];
			}
			$rel['yb_input_data'] = $datarow_ary;
			/**************日记记录开始***********/
			$data['card_code'] = '20';
			$data['patient_id'] = $datarow_ary['patient_id'];
			$data['card_no'] = $datarow_ary['card_no'];
			$data['op_name'] = "调用医保DLL获取患者医保信息";
			$data['direction'] = "返回报文";
			$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
			$data['op_code'] = I("post.op_code");
			M("logs")->add($data);
			/******************日志记录结束*********************/
			$query_type = '%';
			$getPatGhRecord = $this->getPatGhRecord($start_time, $end_time, $datarow_ary['patient_id'], $datarow_ary['card_code'], $datarow_ary['card_no'], $query_type, $zzj_id);
			// var_dump($getPatGhRecord);
			$rel['gh_data'] = $getPatGhRecord;
		}
		$this->ajaxReturn($rel, "JSON");
	}

	//缴费调用医保Dll获取患者信息
	function YbXmlParseJF()
	{
		$cx_time = I('post.cx_time');
		if ($cx_time == '1') {
			$start_time = I("post.start_week_time");
			$end_time = I('post.end_week_time');
		} else if ($cx_time == '2') {
			$start_time = I("post.start_month_time");
			$end_time = I('post.end_month_time');
		} else {
			$start_time = I("post.start_week_time");
			$end_time = I('post.end_week_time');
		}
		$xml = iconv("utf8", "gb2312//IGNORE", htmlspecialchars_decode(I("post.input_xml")));
		$doc = simplexml_load_string($xml);
		$this->writeLog(iconv("utf8", "gb2312//IGNORE", "调用医保DLL获取患者医保返回信息") . $xml, "INFO");
		//返回是否成功状态及相关信息
		$result_ary = $doc->result->info;
		$result_return = array();
		foreach ($result_ary->attributes() as $a => $b) {
			$b = (array) $b;
			$result_return[$a] = $b[0];
		}
		$rel['result'] = $result_return;
		if ($result_return['execute_flag'] == 0) {

			$datarow = $doc->returndata->data->datarow;
			$datarow_ary = array();
			foreach ($datarow->attributes() as $a => $b) {
				$b = (array) $b;
				$datarow_ary[$a] = $b[0];
			}
			$rel['yb_input_jf_data'] = $datarow_ary;
			/**************日记记录开始***********/
			$data['card_code'] = '20';
			$data['patient_id'] = $datarow_ary['patient_id'];
			$data['card_no'] = $datarow_ary['card_no'];
			$data['op_name'] = "调用医保DLL获取患者医保信息";
			$data['direction'] = "返回报文";
			$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
			$data['op_code'] = I("post.op_code");
			M("logs")->add($data);
			/******************日志记录结束*********************/
			if ($result_return['execute_flag'] == 0) {
				$query_type = '0';
				$ledger_sn = '0'; //结算次数
				$getPatJFRecord = $this->getPatJFRecord($start_time, $end_time, $datarow_ary['patient_id'], $datarow_ary['card_code'], $datarow_ary['card_no'], $query_type, $zzj_id, $ledger_sn);
				// var_dump($getPatJFRecord);exit;

				$rel['jf_data'] = $getPatJFRecord;
			}
		}
		$this->ajaxReturn($rel, "JSON");
	}

	//预约调用医保Dll获取患者信息
	function YbXmlParseYY()
	{
		$cx_time = I('post.cx_time');
		if ($cx_time == '1') {
			$start_time = I("post.start_week_time");
			$end_time = I('post.end_week_time');
		} else if ($cx_time == '2') {
			$start_time = I("post.start_month_time");
			$end_time = I('post.end_month_time');
		} else {
			$start_time = I("post.start_week_time");
			$end_time = I('post.end_week_time');
		}
		$xml = iconv("utf8", "gb2312//IGNORE", htmlspecialchars_decode(I("post.input_xml")));
		$doc = simplexml_load_string($xml);
		$this->writeLog(iconv("utf8", "gb2312//IGNORE", "调用医保DLL获取患者医保返回信息") . $xml, "INFO");
		//返回是否成功状态及相关信息
		$result_ary = $doc->result->info;
		$result_return = array();
		foreach ($result_ary->attributes() as $a => $b) {
			$b = (array) $b;
			$result_return[$a] = $b[0];
		}
		$rel['result'] = $result_return;
		if ($result_return['execute_flag'] == 0) {
			$datarow = $doc->returndata->data->datarow;
			$datarow_ary = array();
			foreach ($datarow->attributes() as $a => $b) {
				$b = (array) $b;
				$datarow_ary[$a] = $b[0];
			}
			$rel['yb_input_yy_data'] = $datarow_ary;
			/**************日记记录开始***********/
			$data['card_code'] = '20';
			$data['patient_id'] = $datarow_ary['patient_id'];
			$data['card_no'] = $datarow_ary['card_no'];
			$data['op_name'] = "调用医保DLL获取患者医保信息";
			$data['direction'] = "返回报文";
			$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
			$data['op_code'] = I("post.op_code");
			M("logs")->add($data);
			/******************日志记录结束*********************/
			if ($result_return['execute_flag'] == 0) {
				$query_type = '0';
				$ledger_sn = '0'; //结算次数
				$getPatYyRecord = $this->getPatYyRecord($start_time, $end_time, $datarow_ary['patient_id'], $datarow_ary['card_code'], $datarow_ary['card_no'], $zzj_id);
				// var_dump($getPatJFRecord);exit;

				$rel['yy_data'] = $getPatYyRecord;
			}
		}
		$this->ajaxReturn($rel, "JSON");
	}


	// 获取患者挂号记录信息
	function getPatGhRecord($start_time, $end_time, $patient_id, $card_code, $card_no, $query_type, $zzj_id)
	{
//		 $soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
//		 $soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
		$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');

		$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow start_date="' . $start_time . '" end_date="' . $end_time . '" query_type="' . $query_type . '" patient_id ="' . $patient_id . '" times="0" card_code="' . $card_code . '" card_no="' . $card_no . '"/></data></commitdata><returndata/><operateinfo><info method="YYT_REG_SELECT" opt_id="' . $zzj_id . '" opt_name="' . $zzj_id . '" opt_ip="' . get_client_ip() . '" opt_date="' . date("Y-m-d") . '" guid="\{' . generate_code(10) . '}\" token="AUTO-YYRMYY-' . date("Y-m-d") . '"/></operateinfo><result><info/></result></root>';
		// var_dump($s);exit;
		$data['card_code'] = '21';
		$data['card_no'] = $card_no;
		$data['patient_id'] = $patient_id;
		$data['op_name'] = "获取患者挂号记录信息";
		$data['direction'] = "发送报文";
		$data['op_xml'] = $s;
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		$this->writeLog(utf8_to_gbk("获取患者挂号记录信息，发送报文信息：" . $s));
		$params->userName = '10002';
		$params->password = '12345';
		$params->businessType = 'YYT_REG_SELECT';
		$params->requestData = $s;
		 $row = $soap->FounderRequestData($params);
//		$row = $soap->HISImpl___FounderRequestData($params);
		$result = $row->responseData;
		$result = str_replace("gb2312", "utf8", $result);
		$doc = simplexml_load_string($result);
		// echo "<pre>";
		// var_dump($doc);exit;
		$this->writeLog(utf8_to_gbk("获取患者挂号记录信息，返回报文信息：" . $result));
		$data2['card_code'] = '21';
		$data2['card_no'] = $card_no;
		$data2['patient_id'] = $patient_id;
		$data2['op_xml'] = $result;
		$data2['op_name'] = "his返回患者挂号记录信息";
		$data2['op_code'] = I("post.op_code");
		$data2['direction'] = "返回报文";
		M("logs")->add($data2);
		$result_ary = $doc->result->info;
		$result_return = array();
		foreach ($result_ary->attributes() as $a => $b) {
			$b = (array) $b;
			$result_return[$a] = $b[0];
		}
		$rel['result'] = $result_return;
		if ($result_return['execute_flag'] == 0) {
			$datarowcount = $doc->returndata->data;
			foreach ($datarowcount->attributes() as $key => $val) {
				$val = (array) $val;
				$rowcount[$key] = $val[0];
			}
			$num = $rowcount['row_count'];
			if ($num == '1') {
				$datarow = $doc->returndata->data->datarow;
				$datarow_ary = array();
				foreach ($datarow->attributes() as $a => $b) {
					$b = (array) $b;
					$datarow_ary[$a] = $b[0];
				}
				$rel['datarow'][$num - 1] = $datarow_ary;
				$rel['datarow'][$num - 1]['id'] = $num;

			} else if ($num > 1) {
				$datarow = $doc->returndata->data;
				$res = json_decode(json_encode($datarow), true);
				$res = $res['datarow'];
				$datarow_ary = array();
				for ($i = 0; $i < count($res); $i++) {
					foreach ($res[$i]['@attributes'] as $a => $b) {
						$b = array($b);
						$datarow_ary[$i][$a] = $b[0];
						$datarow_ary[$i]['id'] = $i + 1;
					}
				}
				$rel['datarow'] = $datarow_ary;
			}
		} else {
			$rel['message'] = $result_return['execute_message'];
		}
		return $rel;
	}

	// 获取患者缴费记录查询
	function getPatJFRecord($start_time, $end_time, $patient_id, $card_code, $card_no, $query_type, $zzj_id, $ledger_sn)
	{
//		 $soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
//		 $soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
		$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
		$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow patient_id="' . $patient_id . '" card_code="' . $card_code . '" card_no="' . $card_no . '" query_type="' . $query_type . '"  ledger_sn="' . $ledger_sn . '" start_date="' . $start_time . '" end_date="' . $end_time . '" /></data></commitdata><returndata/><operateinfo><info method=" YYT_PAY_SELECT " opt_id="' . $zzj_id . '" opt_name="' . $zzj_id . '" opt_ip="' . get_client_ip() . '" opt_date="' . date("Y-m-d") . '" guid="\{' . generate_code(10) . '}\" token="AUTO-YYRMYY-' . date("Ymd") . '"  /></operateinfo><result><info /></result></root>';
		$data['card_code'] = '21';
		$data['card_no'] = $card_no;
		$data['patient_id'] = $patient_id;
		$data['op_name'] = "获取患者缴费记录信息";
		$data['direction'] = "发送报文";
		$data['op_xml'] = $s;
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		$this->writeLog(utf8_to_gbk("获取患者缴费记录信息，发送报文信息：" . $s));
		$params->userName = '10002';
		$params->password = '12345';
		$params->businessType = 'YYT_PAY_SELECT';
		$params->requestData = $s;
		$row = $soap->FounderRequestData($params);
//		$row = $soap->HISImpl___FounderRequestData($params);
		$result = $row->responseData;
		$result = str_replace("gb2312", "utf8", $result);

		//写日志
		$this->writeLog(utf8_to_gbk("获取患者缴费记录信息，返回报文信息：" . $result));
		$data2['card_code'] = '21';
		$data2['card_no'] = $card_no;
		$data2['patient_id'] = $patient_id;
		$data2['op_xml'] = $result;
		$data2['op_name'] = "his返回患者缴费记录信息";
		$data2['op_code'] = I("post.op_code");
		$data2['direction'] = "返回报文";
		M("logs")->add($data2);

        /*☟直接转成数组格式☟*/
        $doc=json_decode(json_encode(simplexml_load_string($result)),true);

        /*☟返回前台数组,这是个数组，内容是数组result['info'];*/
        $rel['result'] = $doc['result']['info']['@attributes'];//请求状态

		if ($rel['result']['execute_flag'] == 0) {//执行成功，

            /*☟返回前台数组,这是个数组，内容是数组commitdata['data']['datarow'];☟*/
            $rel['patinfo']=$doc['commitdata']['data']['datarow']['@attributes'];//患者信息

            /*☟这是个数组，内容是数组returndata['data']；☟*/
            $returndata_data=$doc['returndata']['data'];//具体信息

            /*☟返回前台数组,这是个数组，内容是数组returndata['data']['@attributes']；☟*/
            $rel['data']=$returndata_data['@attributes'];//详情汇总

            $data_datarow=$returndata_data['datarow'];//详细信息
            /*☟判断数据是否多条☟*/
            if($data_datarow['@attributes']){//单条，无需多余处理
                $data_datarow['attr']=$data_datarow['@attributes'];
                $data_datarow['sub']=$data_datarow['item']['@attributes'];
                $data_datarow['attr']['charge_total']=$rel['data']['charge_total'];
                $rel['datarow'][0]['attr']=$data_datarow['attr'];
                $rel['datarow'][0]['sub'][0]=$data_datarow['sub'];
            }else {//多条,各种操作...
                /*☟先把维度降低，移除['@attributes']☟*/
                foreach ($data_datarow as $k=>$v){
                    $data_datarow[$k]['attr']=$v['@attributes'];
                    foreach ($v['item'] as $key=>$value){
                        if (!$value['@attributes']){
                            $data_datarow[$k]['item'][0]=array();
                        }else {
                            $data_datarow[$k]['item'][$key] = $value['@attributes'];
                        }
                    }
                    $tmp_comment[]=$data_datarow[$k]['comment'];//每个详细信息的comment，不知道有啥用。
                }
                /*☟去除重复的数据，以receipt_sn分类☟*/
                $ledger_sn=array();
                $num_arr = array();
                $tmp_arr = array();
                $datarow_attr_ary=array();
                foreach ($data_datarow as $k=>$v){
                        if (!in_array($v['attr']['receipt_sn'], $num_arr)||!in_array($v['attr']['ledger_sn'], $ledger_sn)) {
                            $ledger_sn[]=$v['attr']['ledger_sn'];
                            $num_arr[] = $v['attr']['receipt_sn'];
                            $tmp_arr[$k] = $v;
                            $tmp_arr[$k]['comment'] = $tmp_comment[$k];
                    }
                }
                sort($tmp_arr);//重新排序
                /*******字符串处理结束**********/
                /*****************************/
                /*******将正确数组传给前台******/
                for ($i=0;$i<count($tmp_arr);$i++){
                    $datarow_attr_ary[$i]['attr']=$tmp_arr[$i]['attr'];
                    foreach ($tmp_arr[$i]['item'] as $k=>$v){
                        if ($v){
                            $datarow_attr_ary[$i]['sub'][] = $v;
                        }
                    }
                    $datarow_attr_ary[$i]['attr']['charge_total']=$rel['data']['charge_total'];
                }
                $rel['datarow'] = $datarow_attr_ary;
            }
		} else {//执行失败
			$rel['message'] =$rel['result']['execute_message'];
		}
		return $rel;
	}

	// 预约记录查询
	function getPatYyRecord($start_time, $end_time, $patient_id, $card_code, $card_no, $zzj_id)
	{
//        $soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
//		 $soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
		$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
		$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow patient_id="' . $patient_id . '" card_code="' . $card_code . '" card_no="' . $card_no . '" start_date="' . $start_time . '" end_date="' . $end_time . '"/></data></commitdata><returndata/><operateinfo><info method="YYT_ORD_SELECT" opt_id="' . $zzj_id . '" opt_name="' . $zzj_id . '" opt_ip="' . get_client_ip() . '" opt_date="' . date("Y-m-d") . '" guid="\{' . generate_code(10) . '}\" token="AUTO-YYRMYY-' . date("Ymd") . '" /></operateinfo><result><info /></result></root>';
		// var_dump($s);exit;
		$data['card_code'] = '21';
		$data['card_no'] = $card_no;
		$data['patient_id'] = $patient_id;
		$data['op_name'] = "获取患者预约记录信息";
		$data['direction'] = "发送报文";
		$data['op_xml'] = $s;
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		$this->writeLog(utf8_to_gbk("获取患者预约记录信息，发送报文信息：" . $s));
		$params->userName = '10002';
		$params->password = '12345';
		$params->businessType = 'YYT_ORD_SELECT';
		$params->requestData = $s;
		 $row = $soap->FounderRequestData($params);
//		$row = $soap->HISImpl___FounderRequestData($params);

		$result = $row->responseData;
		$result = str_replace("gb2312", "utf8", $result);
		$doc = simplexml_load_string($result);

		$this->writeLog(utf8_to_gbk("获取患者预约记录信息，返回报文信息：" . $result));
		$data2['card_code'] = '21';
		$data2['patient_id'] = $patient_id;
		$data2['card_no'] = $card_no;
		$data2['op_xml'] = $result;
		$data2['op_name'] = "his返回患者预约记录信息";
		$data2['op_code'] = I("post.op_code");
		$data2['direction'] = "返回报文";
		M("logs")->add($data2);
		$result_ary = $doc->result->info;
		$result_return = array();
		foreach ($result_ary->attributes() as $a => $b) {
			$b = (array) $b;
			$result_return[$a] = $b[0];
		}
		$rel['result'] = $result_return;
		if ($result_return['execute_flag'] == 0) {
			$datarow = $doc->returndata->data->datarow;
			$num = count($datarow);
			if ($num == '1') {
				$datarow = $doc->returndata->data->datarow;
				$datarow_ary = array();
				foreach ($datarow->attributes() as $a => $b) {
					$b = (array) $b;
					$datarow_ary[$a] = $b[0];
				}
				$rel['datarow'][$num - 1] = $datarow_ary;
				$rel['datarow'][$num - 1]['id'] = $num;
			} else if ($num > 1) {
				$datarow = $doc->returndata->data;
				$res = json_decode(json_encode($datarow), true);
				$res = $res['datarow'];
				$datarow_ary = array();
				for ($i = 0; $i < count($res); $i++) {
					foreach ($res[$i]['@attributes'] as $a => $b) {
						$b = array($b);
						$datarow_ary[$i][$a] = $b[0];
						$datarow_ary[$i]['id'] = $i + 1;
					}
				}
				$rel['datarow'] = $datarow_ary;
			}
		} else {
			$rel['message'] = $result_return['execute_message'];
		}
		return $rel;
	}

	// 预约取消
	public function YyRecordCancel()
	{
//	    $soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
//		 $soap = new \SoapClient('http://172.168.0.176:8088/chisWebServics?service=HISImpl');
		$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
		$patient_id = I("post.patient_id");
		$card_code = I('post.card_code');
		$card_no = I('post.card_no');
		$register_sn = I('post.register_sn');
//		 var_dump($register_sn);exit;
		$record_id = I('post.record_id');
		$gh_sequence = I('post.gh_sequence');
		$req_type = I('post.req_type');
		$zzj_id = I('post.zzj_id');
		$op_code = I('post.op_code');
		$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow  patient_id = "' . $patient_id . '" card_code="' . $card_code . '" card_no="' . $card_no . '" register_sn ="' . $register_sn . '"  record_id ="' . $record_id . '" req_type="'.$req_type.'" gh_sequence="' . $gh_sequence . '" /></data></commitdata><returndata/><operateinfo><info method=" YYT_APP_CANCEL " opt_id="' . $zzj_id . '" opt_name="' . $zzj_id . '" opt_ip="' . get_client_ip() . '" opt_date="' . date("Y-m-d") . '" guid="\{' . generate_code(10) . '}\" token="AUTO-YYRMYY-' . date("Ymd") . '"  /></operateinfo><result><info /></result></root>';
		// var_dump($s);exit;
		$data['card_code'] = $card_code;
		$data['card_no'] = $card_no;
		$data['patient_id'] = $patient_id;
		$data['op_name'] = "患者预约取消";
		$data['direction'] = "发送报文";
		$data['op_xml'] = $s;
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		$this->writeLog(utf8_to_gbk("患者预约取消，发送报文信息：" . $s));
		$params->userName = '10002';
		$params->password = '12345';
		$params->businessType = 'YYT_APP_CANCEL';
		$params->requestData = $s;
		 $row = $soap->FounderRequestData($params);
//		$row = $soap->HISImpl___FounderRequestData($params);

		$result = $row->responseData;
		$result = str_replace("gb2312", "utf8", $result);
		$doc = simplexml_load_string($result);
		// var_dump($doc);exit;
		$this->writeLog(utf8_to_gbk("患者预约取消，返回报文信息：" . $result));
		$data2['card_code'] = $card_code;
		$data2['card_no'] = $card_no;
		$data2['patient_id'] = $patient_id;
		$data2['op_xml'] = $result;
		$data2['op_name'] = "获取患者预约取消信息";
		$data2['op_code'] = I("post.op_code");
		$data2['direction'] = "返回报文";
		M("logs")->add($data2);
		$result_ary = $doc->result->info;
		$result_return = array();
		foreach ($result_ary->attributes() as $a => $b) {
			$b = (array) $b;
			$result_return[$a] = $b[0];
		}
		$rel = $result_return;
		$this->ajaxReturn($rel,'JSON');
	}

	// 获取打印机状态
	public function print_status(){
		$zzj_id = I("post.zzj_id");
		$print_status =M("devstatus")->where("DevName='{$zzj_id}'")->getField('Paper_End,Paper_Jam');
		foreach ($print_status as $key => $value) {
			$print_status['Paper_End']=$key;
			$print_status['Paper_Jam']=$value;
		}
		$this->ajaxReturn($print_status,"JSON");
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
		$data['op_name'] = I("post.log_txt");
		$data['direction'] = I("post.direction");
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		// echo M()->_sql();exit;
		$this->writeLog(utf8_to_gbk($log_txt),$log_type);
		/**********日志记录结束*******************************/
		$rel['success'] = 1;
		$this->ajaxReturn($rel,"JSON");
	}
	

}
