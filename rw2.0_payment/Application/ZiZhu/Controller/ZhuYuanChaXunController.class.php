<?php
namespace ZiZhu\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
require_once __DIR__ .'/Log.php';
class ZhuYuanChaXunController extends CommonController {
public function zyh_getPatInfo_cs(){
	 $zyh = I("post.zhuyuanhao");  //卡号
 	$acttype = I("post.card_code"); //卡类别
 	$riqi = I("post.riqi");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
   
    /**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "查询HIS交易记录";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = " exec p_yyt_zyfymx '".$zyh."'";
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
    $row = M()->db(2,"DB_CONFIG2")->query(" EXEC p_yyt_zyfymx '".$zyh."'");
    /**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "查询HIS交易记录";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = var_export($row,"true");
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
   echo"<pre>";
    var_dump($row);
    exit;
   
   
    
}
/*就诊卡挂号患者信息查询
*/


//住院号获取病人信息
public function zyh_getPatInfo(){

 	$zyh = I("post.zhuyuanhao");  //卡号
 	$acttype = I("post.card_code"); //卡类别
 	$riqi = I("post.riqi");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");

 	$str = '<?xml version="1.0" encoding="GBK" ?>
<root>
	<head>
		<actdate>20131203</actdate>
		<trdate>20131203</trdate>
		<trtime>085938</trtime>
		<trcode>YQXYYZZJ501</trcode>
	</head>
	<body>
		<zyh>'.$zyh.'</zyh>
		<rq>'.$riqi.'</rq>
	</body>
</root>';

	/**************日记记录开始**************/
	$data['card_no'] = I("post.card_no");
	$data['card_code'] = I("post.card_code");		
	$data['op_name'] = "获取住院患者信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
		
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	//$socketaddr = "172.16.77.18";
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据

	

	$response = $socket->waitforresponse();
	
    // file_put_contents("test.txt",$response);
	$socket->disconnect (); //关闭链接
	
	if($response == "" || $response == false ){
        $rel['head']['succflag']="2";
        $rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		//$temp = mb_convert_encoding($response,"utf-8","gbk");
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);	
		$doc = simplexml_load_string($result3);
		// \Log::Write("jiafei".$result3."2222");
		$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
          
		/*************日记记录开始*************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id"); //获取病案号 病人唯一id
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取住院患者信息";
		$data['direction'] = "返回报文";
		$data['op_code'] = $op_code;
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$sql = M("logs")->getLastSql();
		
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/

		if( $rel["head"]["succflag"] == "1" &&  $rel["body"]['feeinfos']['feeinfo'] != ""){
             
           $array = $rel["body"]['feeinfos']['feeinfo'];
		   if (count($array) == count($array, 1)) {
		   	  //转为二维数组
		   	   $rel["body"]['feeinfos']['fee']["0"]= $array;	   	 				
			}else{
				 $rel["body"]['feeinfos']['fee']= $array;
			}
			

		}else{
			 $rel["body"]['feeinfos']['fee']= "";
		}
		// echo "<pre>";
		//  var_dump($rel);
		//  exit;
	   
		$this->ajaxReturn($rel,"JSON");
	}
	
}

function jzk_getGhPatInfo(){
	$card_no = I("post.card_no");
 	$patient_id = I("post.patient_id");
 	$card_code = I("post.card_code");
 	$card_code = "2000";
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	header("Content-Type: text/html; charset=UTF-8");
  	$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
	$str = '<Request><CardNO>'.$kaid.'</CardNO><CardType>'.$card_code.'</CardType><PatientID>'.$patient_id.'</PatientID><UserID>'.$zzj_id.'</UserID></Request>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "获取就诊卡患者信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->xmlpara = $str;
	$row = $soap->GetPatInfo($stdClass);
	$result = $row->GetPatInfoResult;
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "获取就诊卡患者信息";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$patient_id = $rel['PatientID'];
	if($rel['ResultCode']==="0000"){
		$rel['room']=$this->getCzRoom($card_code,$zzj_id,$op_code,$patient_id,$card_no);
		$this->ajaxReturn($rel,"JSON");
	}else{
		
		$this->ajaxReturn($rel,"JSON");
	}
	
}
/*出诊科室查询
*/
function getCzRoom($card_code,$zzj_id,$op_code,$patient_id,$card_no){
	$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
	$str = '<Request><CardNO>'.$card_no.'</CardNO><PatientID>'.$patient_id.'</PatientID><CardType>'.$card_code.'</CardType><UserID>'.$zzj_id.'</UserID></Request>';
	// $str = '<Request><CardNO>01475047</CardNO><PatientID>1616331</PatientID><CardType>2000</CardType><UserID></UserID></Request>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = $card_no;
	$data['op_name'] = "查询科室信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->xmlpara = $str;
	$row = $soap->DOCKSList($stdClass);
	$result = $row->DOCKSListResult;
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);
	// var_dump($rel);
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = $card_no;
	$data['op_name'] = "查询科室信息";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	if($rel['ResultCode'] === '0000'){
		$rel_s = $rel['Departments']['Department'];
		return $rel_s;
	}
}
/*医生排班信息查询
	@params ksdm 	科室代码
	根据科室代码去查询医生的排班信息
*/

function getDoctorOrder(){
	$card_no = I("post.card_no");
 	$patient_id = I("post.patient_id");
 	$card_code = I("post.card_code");
 	$card_code = "2000";
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
 	$ksdm = I("post.ksdm");
 	date_default_timezone_set('PRC');
	$date = date("Y-m-d",time());
	header("Content-Type: text/html; charset=UTF-8");
  	$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
	$str = '<Request><DepartmentCode>'.$ksdm.'</DepartmentCode><ChooseDate>'.$date.'</ChooseDate><UserID>'.$zzj_id.'</UserID><CardType>'.$card_code.'</CardType><PatientID>'.$patient_id.'</PatientID></Request>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "查询可挂号医生列表";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->xmlpara = $str;
	$row = $soap->DOCHBList($stdClass);
	$result = $row->DOCHBListResult;
	// $result = '<Response><ResultCode>0000</ResultCode><ResultContent></ResultContent><Schedules><Schedule><ScheduleItemCode>20180930内分泌普通号下午</ScheduleItemCode><ServiceDate>2018-09-30</ServiceDate><WeekDay></WeekDay><SessionCode></SessionCode><SessionName>下午</SessionName><DepartmentCode>110201</DepartmentCode><DepartmentName>内分泌门诊1</DepartmentName><ClinicRoomCode></ClinicRoomCode><ClinicRoomName>内分泌普通号</ClinicRoomName><DoctorCode></DoctorCode><DoctorName></DoctorName><DoctorTitleCode></DoctorTitleCode><DoctorTitle>普通1</DoctorTitle><DoctorSpec></DoctorSpec><DoctorSessTypeCode></DoctorSessTypeCode><DoctorSessType></DoctorSessType><ServiceCode></ServiceCode><ServiceName></ServiceName><Fee>50</Fee><RegFee>50</RegFee><CheckupFee>0</CheckupFee><ServiceFee></ServiceFee><OtherFee></OtherFee><AvailableNum></AvailableNum><AdmitAddress>老门诊一层</AdmitAddress><AdmitTimeRange></AdmitTimeRange><Note></Note></Schedule><Schedule><ScheduleItemCode>20180930内分泌普通号下午</ScheduleItemCode><ServiceDate>2018-09-30</ServiceDate><WeekDay></WeekDay><SessionCode></SessionCode><SessionName>下午</SessionName><DepartmentCode>110201</DepartmentCode><DepartmentName>内分泌门诊1</DepartmentName><ClinicRoomCode></ClinicRoomCode><ClinicRoomName>内分泌普通号</ClinicRoomName><DoctorCode></DoctorCode><DoctorName></DoctorName><DoctorTitleCode></DoctorTitleCode><DoctorTitle>普通1</DoctorTitle><DoctorSpec></DoctorSpec><DoctorSessTypeCode></DoctorSessTypeCode><DoctorSessType></DoctorSessType><ServiceCode></ServiceCode><ServiceName></ServiceName><Fee>50</Fee><RegFee>50</RegFee><CheckupFee>0</CheckupFee><ServiceFee></ServiceFee><OtherFee></OtherFee><AvailableNum></AvailableNum><AdmitAddress>老门诊一层</AdmitAddress><AdmitTimeRange></AdmitTimeRange><Note></Note></Schedule><Schedule><ScheduleItemCode>20180930内分泌普通号下午</ScheduleItemCode><ServiceDate>2018-09-30</ServiceDate><WeekDay></WeekDay><SessionCode></SessionCode><SessionName>下午</SessionName><DepartmentCode>110201</DepartmentCode><DepartmentName>内分泌门诊1</DepartmentName><ClinicRoomCode></ClinicRoomCode><ClinicRoomName>内分泌普通号</ClinicRoomName><DoctorCode></DoctorCode><DoctorName></DoctorName><DoctorTitleCode></DoctorTitleCode><DoctorTitle>普通1</DoctorTitle><DoctorSpec></DoctorSpec><DoctorSessTypeCode></DoctorSessTypeCode><DoctorSessType></DoctorSessType><ServiceCode></ServiceCode><ServiceName></ServiceName><Fee>50</Fee><RegFee>50</RegFee><CheckupFee>0</CheckupFee><ServiceFee></ServiceFee><OtherFee></OtherFee><AvailableNum></AvailableNum><AdmitAddress>老门诊一层</AdmitAddress><AdmitTimeRange></AdmitTimeRange><Note></Note></Schedule><Schedule><ScheduleItemCode>20180930内分泌普通号下午</ScheduleItemCode><ServiceDate>2018-09-30</ServiceDate><WeekDay></WeekDay><SessionCode></SessionCode><SessionName>下午</SessionName><DepartmentCode>110201</DepartmentCode><DepartmentName>内分泌门诊1</DepartmentName><ClinicRoomCode></ClinicRoomCode><ClinicRoomName>内分泌普通号</ClinicRoomName><DoctorCode></DoctorCode><DoctorName></DoctorName><DoctorTitleCode></DoctorTitleCode><DoctorTitle>普通1</DoctorTitle><DoctorSpec></DoctorSpec><DoctorSessTypeCode></DoctorSessTypeCode><DoctorSessType></DoctorSessType><ServiceCode></ServiceCode><ServiceName></ServiceName><Fee>50</Fee><RegFee>50</RegFee><CheckupFee>0</CheckupFee><ServiceFee></ServiceFee><OtherFee></OtherFee><AvailableNum></AvailableNum><AdmitAddress>老门诊一层</AdmitAddress><AdmitTimeRange></AdmitTimeRange><Note></Note></Schedule><Schedule><ScheduleItemCode>20180930内分泌普通号下午</ScheduleItemCode><ServiceDate>2018-09-30</ServiceDate><WeekDay></WeekDay><SessionCode></SessionCode><SessionName>下午</SessionName><DepartmentCode>110201</DepartmentCode><DepartmentName>内分泌门诊1</DepartmentName><ClinicRoomCode></ClinicRoomCode><ClinicRoomName>内分泌普通号1</ClinicRoomName><DoctorCode></DoctorCode><DoctorName></DoctorName><DoctorTitleCode></DoctorTitleCode><DoctorTitle>普通1</DoctorTitle><DoctorSpec></DoctorSpec><DoctorSessTypeCode></DoctorSessTypeCode><DoctorSessType></DoctorSessType><ServiceCode></ServiceCode><ServiceName></ServiceName><Fee>50</Fee><RegFee>50</RegFee><CheckupFee>0</CheckupFee><ServiceFee></ServiceFee><OtherFee></OtherFee><AvailableNum></AvailableNum><AdmitAddress>老门诊一层</AdmitAddress><AdmitTimeRange></AdmitTimeRange><Note></Note></Schedule><Schedule><ScheduleItemCode>20180930内分泌普通号下午</ScheduleItemCode><ServiceDate>2018-09-30</ServiceDate><WeekDay></WeekDay><SessionCode></SessionCode><SessionName>下午</SessionName><DepartmentCode>110201</DepartmentCode><DepartmentName>内分泌门诊1</DepartmentName><ClinicRoomCode></ClinicRoomCode><ClinicRoomName>内分泌普通号</ClinicRoomName><DoctorCode></DoctorCode><DoctorName></DoctorName><DoctorTitleCode></DoctorTitleCode><DoctorTitle>普通1</DoctorTitle><DoctorSpec></DoctorSpec><DoctorSessTypeCode></DoctorSessTypeCode><DoctorSessType></DoctorSessType><ServiceCode></ServiceCode><ServiceName></ServiceName><Fee>50</Fee><RegFee>50</RegFee><CheckupFee>0</CheckupFee><ServiceFee></ServiceFee><OtherFee></OtherFee><AvailableNum></AvailableNum><AdmitAddress>老门诊一层</AdmitAddress><AdmitTimeRange></AdmitTimeRange><Note></Note></Schedule></Schedules></Response>';
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "查询可挂号医生列表";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	if($rel['ResultCode']==="0000"){
		if(isset($rel['Schedules']['Schedule'][0])){
			//对个医生
			$rel['datarow']=$rel['Schedules']['Schedule'];
			// echo "多个医生";
		}else{
			//单个医生
			$rel['datarow'][]=$rel['Schedules']['Schedule'];
		}
		$this->ajaxReturn($rel,"JSON");
	}else{
		
		$this->ajaxReturn($rel,"JSON");
	}
		
}


//获取就诊序号
public function OPRcptNo(){
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	header("Content-Type: text/html; charset=UTF-8");
 	$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
	$str = '';
	// var_dump($str);exit;
	/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "获取就诊序号";
	$data['direction'] = "发送报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $str;
	$data['zzj_id'] = $zzj_id;
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->xmlpara = $str;
	$row = $soap->OPVisitNo($stdClass);
	// var_dump($row);exit;
	$result = $row->OPVisitNoResult;
	// var_dump($result);exit();
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	// print_r($rel);exit;
	/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "获取就诊序号";
	$data['direction'] = "返回报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $result;
	$data['zzj_id'] = $zzj_id;
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	if($rel['ResultCode']==="0000"){
		$this->ajaxReturn($rel,"JSON");
	}else{
		$this->ajaxReturn($rel,"JSON");
	}
	
}
//获取挂号医保XML
public function GetDivideParam_GH(){
	$ScheduleItemCode = I("post.ScheduleItemCode");
	$FundType = I("post.FundType");
	$VisitNo = I("post.VisitNo");
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
 	$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
	$str = '<Request>
			<CardNO>'.$card_no.'</CardNO>
			<CardType>'.$card_code.'</CardType>
			<PatientID>'.$patient_id.'</PatientID>
			<VisitNo>'.$VisitNo.'</VisitNo>
			<ScheduleItemCode>'.$ScheduleItemCode.'</ScheduleItemCode>
			<FundType>'.$FundType.'</FundType>
			<UserID>'.$zzj_id.'</UserID>
			</Request>';
	// var_dump($str);exit;
	/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "获取挂号医保XML";
	$data['direction'] = "发送报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $str;
	$data['zzj_id'] = $zzj_id;
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->xmlpara = $str;
	$row = $soap->GetDivideParam_GH($stdClass);
	// var_dump($row);exit;
	$result = $row->GetDivideParam_GHResult;
	// var_dump($result);exit();
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	// print_r($rel);exit;
	/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "获取挂号医保XML";
	$data['direction'] = "返回报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $result;
	$data['zzj_id'] = $zzj_id;
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	if($rel['ResultCode']==="0000"){
		$result = strstr($result,"<?xml");
		$response = strstr($result,"]]></DivideParam>",true);
		$response = str_replace("'", '"', $response);
		$data['card_code'] = $card_code;
		$data['patient_id'] = $patient_id;
		$data['card_no'] = $card_no;
		$data['op_name'] = "医保XML";
		$data['direction'] = "发送报文";
		$data['op_code'] = $op_code;
		$data['op_xml'] = $response;
		$data['zzj_id'] = $zzj_id;
		M("logs")->add($data);
		$rel['GH_xml'] = $response;
		$this->ajaxReturn($rel,"JSON");
	}else{
		$this->ajaxReturn($rel,"JSON");
	}
	
}
//挂号调用医保Dll获取患者信息后，通过ajax传入这里获取出诊科室列表 
function YbXmlParseGhao(){
	$xml = I("post.input_xml");
	$op_code = I("post.op_code");
	$card_code = I("post.card_code");
	$zzj_id = I("post.zzj_id");
	$xml = htmlspecialchars_decode($xml);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	//$data['card_no'] =  $card_no;
	$data['op_name'] = "医保中心返回医保卡信息";
	$data['direction'] = "返回报文";
	$data['op_xml'] = $result;
	$data['op_code'] = $op_code;
	M("logs")->add($data);
	$doc = simplexml_load_string($result);
	$state = (array)$doc->state;
    $isinredlist = (array)$doc->output->net;
    $rel['personcount'] = $isinredlist['personcount'];
    $card_no = (array)$doc->output->ic;
    $rel['FundType'] = $card_no['fundtype'];

    //success 1.判断医保卡是否读卡成功 2.是否在红名单  
    //  3. 是否是本人定点医院 $isinredlist['isspecifiedhosp']=="1"
	if($state["@attributes"]['success']=="true" && $isinredlist['isinredlist']=="true" )
	{
		if(array_key_exists('warning',$state))
		{
			$war = (array)$state['warning'];
			$info = $war['@attributes']['info'];
			$rel['warning'] = 1;
			$rel['warInfo'] = $info;
		}else
		{
			$rel['warning'] = 0;
		};
		$card_no = $card_no['card_no'];
		$rel['error'] = "1";
	}else
	{
		if($isinredlist['isinredlist']=="false")
		{
			$rel['noredFlag'] = 1;
			$rel['noredInfo'] = '该卡不在红名单';
		}else
		{
			$rel['noredFlag'] = 0;
		}
		$rel['error'] = "读卡失败，请去窗口处理";
		$this->ajaxReturn($rel,"JSON");
	}
	//参保人员类别
	$persontype = $isinredlist['persontype'];
	// $this->writeLog(iconv("utf8","gb2312","调用医保DLL获取患者医保返回信息").$xml,"INFO");
	//根据医保卡号获取患者pat_id
	$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
	$str = '<Request><CardNO>'.$card_no.'</CardNO><CardType>'.$card_code.'</CardType><PatientID></PatientID><UserID>'.$zzj_id.'</UserID></Request>';
 	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['card_no'] =  $card_no;
	$data['op_name'] = "调用医保DLL获取患者医保信息";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $str;
	$data['op_code'] = $op_code;
	M("logs")->add($data);
	$stdClass->xmlpara = $str;
	$row = $soap->GetPatInfo($stdClass);
	$result = $row->GetPatInfoResult;
	$doc = simplexml_load_string($result);
	$re = json_decode(json_encode($doc),true);
    $rel['pat_info'] = $re;
    $rel['pat_info']['card_no'] = $card_no;
    /**************日记记录开始***********/
	$data1['card_code'] = $card_code;
	$data1['card_no'] =  $card_no;
	$data1['op_name'] = "调用医保DLL获取患者医保信息";
	$data1['direction'] = "返回报文";
	$data1['op_xml'] = $result;
	$data1['op_code'] = $op_code;
	M("logs")->add($data1);
	/******************获取出诊科室*********************/
	$showtime=date("Ymd");//当前时间
	$rel['room'] = $this->getCzRoom($card_code,$zzj_id,$op_code,$patient_id,$card_no);
	//print_r($rel);
	$this->ajaxReturn($rel,"JSON");	
}
//挂号调用京医通Dll获取患者信息后，通过ajax传入这里获取出诊科室列表 
function JYTXmlParseGhao(){
	$xml = I("post.input_xml");
	$op_code = I("post.op_code");
	$card_code = I("post.card_code");
	$zzj_id = I("post.zzj_id");
	$xml = htmlspecialchars_decode($xml);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	//$data['card_no'] =  $card_no;
	$data['op_name'] = "京医通获取患者信息";
	$data['direction'] = "前台返回";
	$data['op_xml'] = $result;
	$data['op_code'] = $op_code;
	M("logs")->add($data);
	$doc = simplexml_load_string($result);
	$state = (array)$doc->state;
    $netinfo = (array)$doc->cardinfo->netinfo;
    $cardinfo = (array)$doc->cardinfo;
    $rel['balance'] = $cardinfo['balance'];
    //success判断医保卡是否读卡成功 
    //$netinfo['islocalblacklist']是否查询本地黑名单
    //$netinfo['reportloststatus'] 黑名单状态 0-未挂失，不在黑名单，1-正式挂失,2-临时挂失，3.已退卡，4.已冻结
	if($state["@attributes"]['success']=="true" && $netinfo['islocalblacklist']=="false" && $netinfo['reportloststatus']=="0" ){
		if(array_key_exists('warning',$state)){
			$war = (array)$state['warning'];
			$info = $war['@attributes']['info'];
			$rel['warning'] = 1;
			$rel['warInfo'] = $info;
		}else{
			$rel['warning'] = 0;
		}
		$card_no = $cardinfo['cardno'];
		$rel['balance'] = $cardinfo['balance'];
		$rel['error'] = 0;
		//根据医保卡号获取患者pat_id
		$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
		$str = '<Request><CardNO>'.$card_no.'</CardNO><CardType>'.$card_code.'</CardType><PatientID></PatientID><UserID>'.$zzj_id.'</UserID></Request>';
	 	/**************日记记录开始***********/
		$data['card_code'] = $card_code;
		$data['card_no'] =  $card_no;
		$data['op_name'] = "获取HIS京医通患者信息";
		$data['direction'] = "发送报文";
		$data['op_xml'] = $str;
		$data['op_code'] = $op_code;
		M("logs")->add($data);
		$stdClass->xmlpara = $str;
		$row = $soap->GetPatInfo($stdClass);
		$result = $row->GetPatInfoResult;
		$doc = simplexml_load_string($result);
		$re = json_decode(json_encode($doc),true);
	    $rel['pat_info'] = $re;
	    $rel['pat_info']['card_no'] = $card_no;
	    /**************日记记录开始***********/
		$data1['card_code'] = $card_code;
		$data1['card_no'] =  $card_no;
		$data1['op_name'] = "获取HIS京医通患者信息";
		$data1['direction'] = "返回报文";
		$data1['op_xml'] = $result;
		$data1['op_code'] = $op_code;
		M("logs")->add($data1);
		/******************获取出诊科室*********************/
		$showtime=date("Ymd");//当前时间
		$rel['room'] = $this->getCzRoom($card_code,$zzj_id,$op_code,$patient_id,$card_no);
		/**************日记记录开始***********/
		$data1['card_code'] = $card_code;
		$data1['card_no'] =  $card_no;
		$data1['op_name'] = "返回到前台数据";
		$data1['direction'] = "返回报文";
		$data1['op_xml'] = var_export($rel,"true");
		$data1['op_code'] = $op_code;
		M("logs")->add($data1);
		/******************获取出诊科室*********************/
		//print_r($rel);
		$this->ajaxReturn($rel,"JSON");	
	}else{
		$rel['errorInfo'] = "读卡失败，请去窗口处理";
		if(array_key_exists('error',$state)){
			$war = (array)$state['error'];
			$info = $war['@attributes']['info'];
			$rel['error'] = 1;
			$rel['errorInfo'] = $info;
		}else{
			$rel['error'] = 0;
		}
		$this->ajaxReturn($rel,"JSON");
	}
	
}
//医保卡激活
public function ActivateMedicarecard(){
	$yibao_patinfo = I("post.yibao_patinfo");
	$Mobile_phone = I("post.Mobile_phone");
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");

	$yibao_patinfo = htmlspecialchars_decode($yibao_patinfo);
	$yibao_patinfo = str_replace("UTF-16","utf8",$yibao_patinfo);
	$yibao_patinfo = str_replace("\\\"", "\"", $yibao_patinfo);

	$Ybxml  = $yibao_patinfo;
	$Ybxml = strstr($Ybxml,"<output>");
	$Ybxml = strstr($Ybxml,"</root>",true);

	$doc = simplexml_load_string($yibao_patinfo);
   
    $ic = (array)$doc->output->ic;
    $MedicarecardNO =  $ic['card_no'];//医保卡号
    $IDCardNO =  $ic['id_no'];//身份证号
    $PatientName =  $ic['personname'];//姓名
    $Sex =  $ic['sex'];//性别
    if($Sex=="1"){
    	$Sex = "男";
    }
    if($Sex=="2"){
    	$Sex = "女";
    }
    $Birthday =  $ic['birthday'];//出生日期
    $CardTypeCode = "3000";
    


	header("Content-Type: text/html; charset=UTF-8");
 	$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
	$str = '<Request>
			<MedicarecardNO>'.$MedicarecardNO.'</MedicarecardNO>
			<IDCardNO>'.$IDCardNO.'</IDCardNO>
			<PatientName>'.$PatientName.'</PatientName>
			<Sex>'.$Sex.'</Sex>
			<Birthday>'.$Birthday.'</Birthday>
			<CardTypeCode>'.$CardTypeCode.'</CardTypeCode>
			<UserID>'.$zzj_id.'</UserID>
			<Mobile_phone>'.$Mobile_phone.'</Mobile_phone>
			<Ybxml>'.$Ybxml.'</Ybxml>
			</Request>';
	// var_dump($str);exit;
	/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $MedicarecardNO;
	$data['op_name'] = "医保卡激活";
	$data['direction'] = "发送报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $str;
	$data['zzj_id'] = $zzj_id;
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->xmlpara = $str;
	$row = $soap->ActivateMedicarecard($stdClass);
	// var_dump($row);exit;
	$result = $row->ActivateMedicarecardResult;
	// var_dump($result);exit();
	// $result = '<Response><ResultCode>0000</ResultCode><ResultContent></ResultContent><PatientID>125715836</PatientID><PatName>李永江</PatName></Response>';
	// $result = '<Response><ResultCode>0001</ResultCode><ResultContent>激活失败</ResultContent><PatientID>125715836</PatientID><PatName>李永江</PatName></Response>';
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	// print_r($rel);exit;
	/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $MedicarecardNO;
	$data['op_name'] = "医保卡激活";
	$data['direction'] = "返回报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $result;
	$data['zzj_id'] = $zzj_id;
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	if($rel['ResultCode']==="0000"){
		$patient_id = $rel['PatientID'];
		$card_no = $MedicarecardNO;
		$rel['pat_info']['PatName'] = $PatientName;//患者姓名
        $rel['pat_info']['PatientID'] = $patient_id; //患者ID
        $rel['pat_info']['IDCard']  =   $IDCardNO      ;//患者身份证号
        $rel['pat_info']['PatSex'] = $Sex;//患者性别
        $rel['pat_info']['PatAge'] = '';//患者年龄
		$rel['room'] = $this->getCzRoom($card_code,$zzj_id,$op_code,$patient_id,$card_no);
		$this->ajaxReturn($rel,"JSON");
	}else{
		$this->ajaxReturn($rel,"JSON");
	}
	
}
//判断医保分解结果
public function DivideInfo_GH(){
	$DivideInfo = I("post.DivideInfo");
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	$xml = htmlspecialchars_decode($DivideInfo);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	$doc = simplexml_load_string($result);
	$tradeinfo = $doc->output->tradeinfo;
	$tradeinfo = (array)$tradeinfo;
	$tradeno  = $tradeinfo['tradeno'];//医保分解交易流水号
	/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "医保分解xml结果";
	$data['direction'] = "返回报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $result;
	$data['zzj_id'] = $zzj_id;
	M("logs")->add($data);
	$success = $doc->state;
	$success = (array)$success;
	$state = $doc->state;
	$success = $success['@attributes']['success'];
	if ($success != 'true') {
		$error = (array)$state->error;
		$rel['error'] = $error['@attributes']['info'];
	}else{
		$rel['MedInsurTranNo'] = $tradeno;
		$rel['success'] = $success;
	}
	$this->ajaxReturn($rel,"JSON");
	
}
//判断医保分解结果
public function GetDivideResult_GH(){
	$DivideInfo = I("post.DivideInfo");
	$DivideInfo = htmlspecialchars_decode($DivideInfo);
	$DivideInfo = str_replace("UTF-16","utf8",$DivideInfo);
	$DivideInfo = str_replace("\\\"", "\"", $DivideInfo);
	
	$TradeInfo = I("post.TradeInfo");
	$TradeInfo = htmlspecialchars_decode($TradeInfo);
	$TradeInfo = str_replace("UTF-16","utf8",$TradeInfo);
	$TradeInfo = str_replace("\\\"", "\"", $TradeInfo);
	$TradeInfo = '<?xml version="1.0" encoding="utf8" standalone="yes"?>
<root version="1.00.0408"><state success="true"></state><output><personcountaftersub>0.00</personcountaftersub><certid>0000000001fb4863</certid><sign>j3zQkUbPaJs7Lyu+xW1P6UEyLaJUCEXjwG2YisMCJ3R8qASdDUh0NVuL5YIUB1Ar36/9wUDau5+OmE/ujZbKzMGyoVG1ZGgqbjDcKDVA6Ml9op9bERWsmG3/1wCS8+GKbyjhSrcUaXMs4odfoaz/tlbbHAWGQbrYr9jUlGRYkVg=</sign></output></root>';
	$VisitNo = I("post.VisitNo");
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
	$str = '<Request>
				<DivideInfo><![CDATA['.$DivideInfo.']]></DivideInfo>
				<TradeInfo><![CDATA['.$TradeInfo.']]></TradeInfo>
				<VisitNo>'.$VisitNo.'</VisitNo>
				<UserID>'.$zzj_id.'</UserID>
			</Request>';
	// var_dump($str);exit;
	/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "解析挂号医保XML";
	$data['direction'] = "发送报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $str;
	$data['zzj_id'] = $zzj_id;
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->xmlpara = $str;
	$row = $soap->GetDivideResult_GH($stdClass);
	// var_dump($row);exit;
	$result = $row->GetDivideResult_GHResult;
	// var_dump($result);exit();
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	// print_r($rel);exit;
	/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "解析挂号医保XML";
	$data['direction'] = "返回报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $result;
	$data['zzj_id'] = $zzj_id;
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	if($rel['ResultCode']==="0000"){
		$this->ajaxReturn($rel,"JSON");
	}else{
		$this->ajaxReturn($rel,"JSON");
	}
	
}

/**********退款*******************************/
public function ajax_tuikuan(){
	//$soap = new \SoapClient('http://182.254.218.183/soap/Service.php?wsdl');  
	// $soap = new \SoapClient(null,array('location'=>'http://123.206.31.148/soap/Service.php',uri=>'Service.php')); 
	// $soap = new \SoapClient('http://127.0.0.1/soap/Service.php?wsdl');
	$soap = new \SoapClient(null,array('location'=>'http://127.0.0.1:8088/soap/Service.php',uri=>'Service.php'));
	$pay_type = I("post.pay_type");
	$trade_no = I("post.trade_no");
	$out_trade_no = trim(I("post.out_trade_no"));
	$total_amount = trim(I("post.total_amount"));
	$subject = trim(I("post.subject"));
	$source="hos188";
	$operator_id = trim(I("post.operator_id"));
	switch($pay_type){
		case "alipay":
		$op_name = $operator_id;
		$row = $soap->refund($trade_no,$total_amount,$out_trade_no,$op_name);
		break;
		case "wxpay":
		$refund_fee = $total_amount."|".$operator_id;
		$row = $soap->WxRefused($out_trade_no,$total_amount,$refund_fee);
		break;
	}
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;
	//print_r($rel); 
	$this->ajaxReturn($rel,"JSON");
}
public function index()
{
	$zzj_id = I("get.zzj_id");
	//var_dump($zzj_id);
    $this->assign("zzj_id",$zzj_id);
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
function huoqu_time(){
	// 查询HIS服务器时间SQL
	$sss_sql = "SELECT * FROM V_ZZJ_FWQ_SJ";
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "查询HIS服务器时间";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $sss_sql;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$rels = M()->db(1,"DB_CONFIG1")->query($sss_sql);
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "查询HIS服务器时间";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = var_export($rels,"true");
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//HIS服务器时间 如2017-11-23 10:37:30
	$fwqsj = $rels[0]['fwqsj'];
	// HIS服务器的时间戳
	$time = strtotime($fwqsj);
	// var_dump($time);exit;
	// $a = M()->getLastSql();
	date_default_timezone_set('PRC');
	
	$xiawu = date("Y-m-d",$time)." "."13:20:00";
	$xiawu1 = strtotime($xiawu);
	
	if($time>$xiawu1 ){
		$rel['xianshi'] ="1";//1是可以挂号,2是不可以挂号
	}else{
		$rel['xianshi'] ="2";
	}
	$xq = mb_substr( "日一二三四五六",date("w"),1,"utf-8" );
	$rel['time'] = $time;
	$rel['xq'] = $xq;
	$this->ajaxReturn($rel,"JSON");
}

//身份证获取病人信息
public function sfz_getPatInfo(){
 	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	// $card_no = "10100832";
 	// $card_no = "12315643300";
 	$card_code = "3";
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	header("Content-Type: text/html; charset=UTF-8");
	$soap = new \SoapClient("http://223.223.223.233:8083/chart/services/WxService?wsdl");
	$str = '<?xml version="1.0" encoding="gb2312"?>
			<root>
				<commitdata>
				<data>
					<datarow>
						<hao_ming>'.$card_code.'</hao_ming>
						<code_value>'.$card_no.'</code_value>
					</datarow>
				</data>
				</commitdata>
				<operateinfo>
					<info>
						<Method>YYT_QRY_PATI</Method> 
						<opt_id>00000</opt_id>
						<opt_name>00000</opt_name>
						<opt_ip>80000001</opt_ip>
						<opt_date>2014-06-06</opt_date>
						<opt_id>00000</opt_id>
						<opt_name>00000</opt_name>
						<opt_ip>80000001</opt_ip>
						<opt_date>2014-06-06</opt_date>
						<Token>AUTO-YYRMYY-20140701</Token>
					</info>
				</operateinfo>
				<result>
				</result>
			</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "获取就诊卡患者信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->arg0 = $str;
	$row = $soap->YYT_QRY_PATI($stdClass);
	$result = $row->return;
	// var_dump($result);exit();
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	// echo "<pre>";
	// var_dump($rel);exit();
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "获取就诊卡患者信息";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	if($rel['result']['info']['resultcode']=="0"){
		$rel['yijikeshi'] = $this->yijikeshi_list();
		$this->ajaxReturn($rel,"JSON");
	}else{
		$this->ajaxReturn($rel,"JSON");
	}
}
// 医保卡获取病人信息
public function yibao_getPatInfo(){
 	$card_code = "1";
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
 	date_default_timezone_set('PRC');
 	$date = date("Y-m-d H:i:s",time());
	// header("Content-Type: text/html; charset=UTF-8");
	$str = '<?xml version="1.0" encoding="UTF-8"?><root><commitdata><data><datarow><hao_ming>07</hao_ming><code_value>057433028128117256</code_value><is_jianka>2</is_jianka><patient_name></patient_name></datarow></data></commitdata><operateinfo><info><Method>YYT_QRY_PATI</Method><opt_name>'.$zzj_id.'</opt_name><opt_date>'.$date.'</opt_date></info></operateinfo><result></result></root>';
	/**************日记记录开始**************/
	$data['card_code'] = "1";
	$data['patient_id'] = "";
	$data['card_no'] = "";
	$data['op_name'] = "获取医保卡患者信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	set_time_limit (0); 
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2000";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect(); //关闭链接
	if($response==""){
		$rel['result']['info']['resultcode']="1";
		$rel['result']['info']['errormsg'] = "请求超时,请重试";
		$this->ajaxReturn($rel,"JSON");
	}else{
		$response = str_replace("GB2312","utf8",$response);
		$xml =iconv("gbk","UTF-8//IGNORE",$response);
		// $result = str_replace("gb2312","utf8",$result);
		// $xml =iconv("gb2312","utf8",$response);
		$doc = simplexml_load_string($xml);
		$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		/**************日记记录开始**************/
		$data['card_code'] = "1";
		$data['patient_id'] = "";
		$data['card_no'] = "";
		$data['op_name'] = "获取医保卡患者信息";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		// $data['op_xml'] =  var_export($rel,"true");
		$data['op_xml'] =  $xml;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		if($rel['result']['info']['resultcode']==0){
			$rel['yijikeshi'] = $this->yijikeshi_list();
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}
}
/**********生成二维码*******************************/
public function ajaxGetPayUrl(){
	//$soap = new \SoapClient('http://182.254.218.183/soap/Service.php?wsdl');  
	// $soap = new \SoapClient(null,array('location'=>'http://123.206.31.148/soap/Service.php',uri=>'Service.php')); 
	// $soap = new \SoapClient('http://127.0.0.1/soap/Service.php?wsdl');
	$soap = new \SoapClient(null,array('location'=>'http://127.0.0.1:8080/soap/Service.php',uri=>'Service.php')); 
	// var_dump($soap);exit;
	$pay_type = I("post.pay_type");
	$out_trade_no = trim(I("post.out_trade_no"));
	$total_amount = trim(I("post.total_amount"));
	$subject = trim(I("post.subject"));
	$pay_type = I("post.pay_type");
	$source="hos188";
	switch($pay_type){
		case "alipay":
		// echo $subject;exit;
		$row = $soap->getPayUrl($out_trade_no,$total_amount,$subject,$source);
		// var_dump($row);exit;
		break;
		case "wxpay":
		$row = $soap->getWeiXinPayUrl($out_trade_no,$total_amount,$subject,$source);
		break;
	}
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;
	//print_r($rel); 
	$this->ajaxReturn($rel,"JSON");
}
/**********查询交易状态*******************************/
public function ajaxGetPayStatus(){
	//$soap = new \SoapClient('http://182.254.218.183/soap/Service.php?wsdl'); 
	// $soap = new \SoapClient(null,array('location'=>'http://123.206.31.148/soap/Service.php',uri=>'Service.php')); 
	$soap = new \SoapClient(null,array('location'=>'http://127.0.0.1:8080/soap/Service.php',uri=>'Service.php')); 
	$out_trade_no = trim(I("post.out_trade_no"));
	$source="hos188";
	$pay_type = I("post.pay_type");
	switch($pay_type){
		case "alipay":
		$row = $soap->getPayStatus($out_trade_no,$source);
		break;
		case "wxpay":
		$row = $soap->getWxPayStatus($out_trade_no);
		break;
	}
	
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = (array)$message;
	//print_r($rel);
	$this->ajaxReturn($rel,"JSON");
}
public function YYT_GH_CALC(){
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
 	$ksdm = I("post.ksdm");
 	$ysdm = I("post.ysdm");
 	if($ysdm==""){
 		$ysdm = "0";
 	}
 	$ghjb = I("post.hbbm");
 	$sxw = I("post.sxw");
 	$patient_id = I("post.patient_id");
 	$gh_flag ="1";//1是挂号,2是预约
	header("Content-Type: text/html; charset=UTF-8");
	$soap = new \SoapClient("http://223.223.223.233:8083/chart/services/WxService?wsdl");
	$str = '<?xml version="1.0" encoding="gb2312"?>
			<root>
				<commitdata>
				<data>
					<datarow>
						<card_code>'.$card_code.'</card_code>
						<card_no>'.$card_no.'</card_no>
						<zzj_id>'.$zzj_id.'</zzj_id>
						<mzks>'.$ksdm.'</mzks>
						<ysdm>'.$ysdm.'</ysdm>
						<ghjb>'.$ghjb.'</ghjb>
						<sxw>'.$sxw.'</sxw>
						<recornd_sn></recornd_sn>
						<patient_id>'.$patient_id.'</patient_id>
						<responce_type></responce_type>
						<gh_flag>0</gh_flag>
					</datarow>
				</data>
				</commitdata>
				<operateinfo>
					<info>
						<Method>YYT_GH_CALC</Method>
						<opt_id>00000</opt_id>
						<opt_name>00000</opt_name>
						<opt_ip>80000001</opt_ip>
						<opt_date>2014-06-06</opt_date>
						<opt_id>00000</opt_id>
						<opt_name>00000</opt_name>
						<opt_ip>80000001</opt_ip>
						<opt_date>2014-06-06</opt_date>
						<Token>AUTO-YYRMYY-20140701</Token>
					</info>
				</operateinfo>
				<result>
				</result>
			</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "就诊卡患者挂号划价";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->arg0 = $str;
	$row = $soap->YYT_GH_CALC($stdClass);
	$result = $row->return;
	// var_dump($result);exit();
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	// echo "<pre>";
	// var_dump($rel);exit();
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "就诊卡患者挂号划价";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	if($rel['result']['info']['resultcode']=="0"){
		$this->ajaxReturn($rel,"JSON");
	}else{
		$this->ajaxReturn($rel,"JSON");
	}
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
//京医通缴费接口
public function payOut_out(){
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
	$data['op_name'] = "京医通缴费接口从前台发送到后台";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	// var_dump($result);exit();
	/*$result = '<root version="1.0"><state success="false" needconfirm="true"><warning no="0" info=""/><warning no="1" info=""/></state><balancebefore>100.00</balancebefore><balanceafter>200.00</balanceafter><plantext></plantext><sign></sign><tradetime>20130220103001</tradetime></root>';*/
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	$state = (array)$doc->state;
	if($state["@attributes"]['success']==="true" && $state["@attributes"]['needconfirm']==="false"){
		$tradeno = (array)$doc;
		$rel['ResultCode'] = "0000";
		$rel['needconfirm'] = "0";
		$rel['balancebefore'] = $tradeno['balancebefore'];
		$rel['balanceafter'] = $tradeno['balanceafter'];
		$this->ajaxReturn($rel,"JSON");
	}elseif($state["@attributes"]['success']==="false" && $state["@attributes"]['needconfirm']==="true"){
		$rel['ResultCode'] = "0001";
		$rel['needconfirm'] = "1";
		$this->ajaxReturn($rel,"JSON");
	}else{
		$rel['needconfirm'] = "0";
		$rel['ResultCode'] = "0001";
		$this->ajaxReturn($rel,"JSON");
	}
	
}
//查询京医通交易确认状态
public function QueryConfirmTrade(){
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
	$data['op_name'] = "京医通交易确认状态从前台发送到后台";
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
	if($state["@attributes"]['success']==="true" && $state["@attributes"]['needconfirm']==="false"){
		$tradeno = (array)$doc;
		$rel['balancebefore'] = $tradeno['balancebefore'];
		$rel['balanceafter'] = $tradeno['balanceafter'];
		$rel['tradestate'] = $tradeno['tradestate'];
		if($tradeno['tradestate']=="ok"){
			$rel['ResultCode'] = "0000";
			$this->ajaxReturn($rel,"JSON");
		}else{
			$rel['ResultCode'] = "0001";
			$this->ajaxReturn($rel,"JSON");
		}
	}else{
		$rel['ResultCode'] = "0001";
		$this->ajaxReturn($rel,"JSON");
	}
	
}
//查询京医通交易确认状态
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
public function jyt_guahao_save(){
	$card_no = I("post.card_no");
	$rq_hb_sj = I("post.rq_hb_sj");
	$VisitNo = I("post.VisitNo");//就诊号
	$patient_name = I("post.patient_name");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");//病人ID
 	$op_code = I("post.op_code");//操作码
 	$PayAccount = I("post.PayAccount");//支付账户582***@qq.com
 	$pay_type = I("post.pay_type");//支付类型alipay wxpay bank
 	$RcptNOJyt = '';
 	$TermID = '';
 	switch($pay_type){
		case "alipay":
			$pay_type = '支付宝';
		break;
		case "wxpay":
			$pay_type = '微信';
		break;
		case "bank":
			$pay_type = '银医卡';
		break;
		case "jytpay":
			$pay_type = '京医通';
			$RcptNOJyt = I("post.jyt_tradeno");
		break;
	}
	$CardNOJyt = $card_no;
 	$stream_no = I("post.stream_no");//订单号
 	$trade_no = I("post.trade_no");//交易流水号
 	$ysfwf = I("post.ysfwf");//医事服务费
 	$charge_total = I("post.charge_total");//总金额
 	$cash = I("post.cash");//自费金额
 	$zhzf = I("post.zhzf");//账户支付金额
 	$tczf = I("post.tczf");//医保支付金额
 	$pay_seq = $zzj_id.$op_code;//预约ID
 	$ksdm = I("post.ksdm");//科室代码
 	$ysdm = I("post.ysdm");//医生代码
 	$trade_time = date("Y-m-d H:i:s",time());//交易时间
 	$zzj_id = I("post.zzj_id");
	header("Content-Type: text/html; charset=UTF-8");
	$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
	$str = '<Request>
		<CardType>'.$card_code.'</CardType>
		<ScheduleItemCode>'.$rq_hb_sj.'</ScheduleItemCode>
		<CardNO>'.$card_no.'</CardNO>
		<PatientID>'.$patient_id.'</PatientID>
		<PatientName>'.$patient_name.'</PatientName>
		<BankCardNo>'.$PayAccount.'</BankCardNo>
		<RegFee>'.$ysfwf.'</RegFee>
		<PersonalPay>'.$cash.'</PersonalPay>
		<MedInsurePay>'.$tczf.'</MedInsurePay>
		<MedInsureTradeNO></MedInsureTradeNO>
		<BankTradeNO>'.$trade_no.'</BankTradeNO>
		<UserID>'.$zzj_id.'</UserID>
		<VisitNo>'.$VisitNo.'</VisitNo>
		<PersoncountPay>0.00</PersoncountPay>
		<ReferNum>'.$stream_no.'</ReferNum>
		<TermID>'.$TermID.'</TermID>
		<PayWay>'.$pay_type.'</PayWay>
		<CardNOJyt>'.$CardNOJyt.'</CardNOJyt>
		<RcptNOJyt>'.$RcptNOJyt.'</RcptNOJyt>
		</Request>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "自助挂号保存";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->xmlpara = $str;
	$row = $soap->OPRegist($stdClass);
	// var_dump($row);exit;
	$result = $row->OPRegistResult;
	/*$result ='<Response><ResultCode>0001</ResultCode><ResultContent>HIS返回失败</ResultContent><OrderCode></OrderCode><SeqCode>2</SeqCode><AdmitRange></AdmitRange><AdmitAddress>老门诊一层</AdmitAddress><OrderContent></OrderContent><PatientID>1822324</PatientID><Fee> 50.00</Fee><RegFee> 50.00</RegFee><CheckupFee>0</CheckupFee><PersonalPay> 50.00</PersonalPay><MedInsurePay>0.00</MedInsurePay><MedInsureTradeNO></MedInsureTradeNO></Response>';*/
	// var_dump($result);exit();
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	// echo "<pre>";
	// var_dump($rel);exit();
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "自助挂号保存";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	if($rel['ResultCode']=="0000"){
		$this->ajaxReturn($rel,"JSON");
	}else{
		$this->ajaxReturn($rel,"JSON");
	}
}
public function ic_guahao_save(){
	$card_no = I("post.card_no");
	$rq_hb_sj = I("post.rq_hb_sj");
	$VisitNo = I("post.VisitNo");//就诊号
	$patient_name = I("post.patient_name");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");//病人ID
 	$op_code = I("post.op_code");//操作码
 	$PayAccount = I("post.PayAccount");//支付账户582***@qq.com
 	$pay_type = I("post.pay_type");//支付类型alipay wxpay bank
 	switch($pay_type){
		case "alipay":
			$pay_type = '支付宝';
		break;
		case "wxpay":
			$pay_type = '微信';
		break;
		case "bank":
			$pay_type = '银医卡';
		break;
	}
 	$stream_no = I("post.stream_no");//订单号
 	$trade_no = I("post.trade_no");//交易流水号
 	$ysfwf = I("post.ysfwf");//医事服务费
 	$charge_total = I("post.charge_total");//总金额
 	$cash = I("post.cash");//自费金额
 	$zhzf = I("post.zhzf");//账户支付金额
 	$tczf = I("post.tczf");//医保支付金额
 	$pay_seq = $zzj_id.$op_code;//预约ID
 	$ksdm = I("post.ksdm");//科室代码
 	$ysdm = I("post.ysdm");//医生代码
 	
 	$trade_time = date("Y-m-d H:i:s",time());//交易时间
 	$zzj_id = I("post.zzj_id");
	header("Content-Type: text/html; charset=UTF-8");
	$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
	$str = '<Request>
		<CardType>'.$card_code.'</CardType>
		<ScheduleItemCode>'.$rq_hb_sj.'</ScheduleItemCode>
		<CardNO>'.$card_no.'</CardNO>
		<PatientID>'.$patient_id.'</PatientID>
		<PatientName>'.$patient_name.'</PatientName>
		<BankCardNo>'.$PayAccount.'</BankCardNo>
		<RegFee>'.$ysfwf.'</RegFee>
		<PersonalPay>'.$cash.'</PersonalPay>
		<MedInsurePay>'.$tczf.'</MedInsurePay>
		<MedInsureTradeNO></MedInsureTradeNO>
		<BankTradeNO>'.$trade_no.'</BankTradeNO>
		<UserID>'.$zzj_id.'</UserID>
		<VisitNo>'.$VisitNo.'</VisitNo>
		<PersoncountPay>0.00</PersoncountPay>
		<ReferNum>'.$stream_no.'</ReferNum>
		<TermID>020051010128037</TermID>
		<PayWay>'.$pay_type.'</PayWay>
		<CardNOJyt></CardNOJyt>
		<RcptNOJyt></RcptNOJyt>
		</Request>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "自助挂号保存";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$stdClass->xmlpara = $str;
	$row = $soap->OPRegist($stdClass);
	// var_dump($row);exit;
	$result = $row->OPRegistResult;
	// $result = '<Response>
	// <ResultCode>0000</ResultCode>
	// <ResultContent></ResultContent>
	// <OrderCode></OrderCode>
	// <SeqCode>001</SeqCode>
	// <AdmitRange>2018-09-30 17:34:45</AdmitRange>
	// <AdmitAddress>老门诊一层</AdmitAddress>
	// <OrderContent></OrderContent>
	// <PatientID>1616331</PatientID>
	// <Fee>50.00</Fee>
	// <RegFee>50.00</RegFee>
	// <CheckupFee>{10}</CheckupFee>
	// <PersonalPay>{6}</PersonalPay>
	// <MedInsurePay>{7}</MedInsurePay>
	// <MedInsureTradeNO>{8}</MedInsureTradeNO>
	// </Response>';
	// var_dump($result);exit();
	$doc = simplexml_load_string($result);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	// echo "<pre>";
	// var_dump($rel);exit();
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "自助挂号保存";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	if($rel['ResultCode']=="0000"){
		$this->ajaxReturn($rel,"JSON");
	}else{
		$this->ajaxReturn($rel,"JSON");
	}
}
public function yb_guahao_save(){
	$xml = I("post.input_xml");
	$MedInsurTranNo = I("post.MedInsurTranNo");//医保交易流水号
	$rq_hb_sj = I("post.rq_hb_sj");
	$VisitNo = I("post.VisitNo");//就诊号
	$patient_name = I("post.patient_name");
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");//病人ID
 	$op_code = I("post.op_code");//操作码
 	$PayAccount = I("post.PayAccount");//支付账户582***@qq.com
 	$pay_type = I("post.pay_type");//支付类型alipay wxpay bank
 	switch($pay_type){
		case "alipay":
			$pay_type = '支付宝';
		break;
		case "wxpay":
			$pay_type = '微信';
		break;
		case "bank":
			$pay_type = '银医卡';
		break;
	}
 	$stream_no = I("post.stream_no");//订单号
 	$trade_no = I("post.trade_no");//交易流水号
 	$ysfwf = I("post.ysfwf");//医事服务费
 	$charge_total = I("post.charge_total");//总金额
 	$cash = I("post.cash");//自费金额
 	$zhzf = I("post.zhzf");//账户支付金额
 	$tczf = I("post.tczf");//医保支付金额
 	$ksdm = I("post.ksdm");//科室代码
 	$ysdm = I("post.ysdm");//医生代码
 	$zzj_id = I("post.zzj_id");
	$xml = htmlspecialchars_decode($xml);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	$doc = simplexml_load_string($result);
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "医保保存";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $result;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$state = (array)$doc->state;
	$yb_zt = "";
	if($state["@attributes"]['success']==="true"){
		$yb_zt = 0;
		$soap = new \SoapClient('http://172.16.252.28/WBS_TEST/HIS.asmx?wsdl');
		$str = '<Request>
				<CardType>'.$card_code.'</CardType>
				<ScheduleItemCode>'.$rq_hb_sj.'</ScheduleItemCode>
				<CardNO>'.$card_no.'</CardNO>
				<PatientID>'.$patient_id.'</PatientID>
				<PatientName>'.$patient_name.'</PatientName>
				<BankCardNo>'.$PayAccount.'</BankCardNo>
				<RegFee>'.$ysfwf.'</RegFee>
				<PersonalPay>'.$cash.'</PersonalPay>
				<MedInsurePay>'.$tczf.'</MedInsurePay>
				<MedInsureTradeNO>'.$MedInsurTranNo.'</MedInsureTradeNO>
				<BankTradeNO>'.$trade_no.'</BankTradeNO>
				<UserID>'.$zzj_id.'</UserID>
				<VisitNo>'.$VisitNo.'</VisitNo>
				<PersoncountPay>0.00</PersoncountPay>
				<ReferNum>'.$stream_no.'</ReferNum>
				<TermID>020051010128037</TermID>
				<PayWay>'.$pay_type.'</PayWay>
				<CardNOJyt></CardNOJyt>
				<RcptNOJyt></RcptNOJyt>
				</Request>';
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "自助挂号保存";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		$stdClass->xmlpara = $str;
		$row = $soap->OPRegist($stdClass);
		// var_dump($row);exit;
		$result = $row->OPRegistResult;
		// var_dump($result);exit();
		/*$result = '<Response><ResultCode>0001</ResultCode><ResultContent>HIS失败</ResultContent><OrderCode>20181009儿科普通号下午1B</OrderCode><SeqCode>1</SeqCode><AdmitRange>下午</AdmitRange><AdmitAddress>二层</AdmitAddress><OrderContent></OrderContent><PatientID>125715836</PatientID><Fee> 50.00</Fee><RegFee> 50.00</RegFee><CheckupFee>0</CheckupFee><PersonalPay> 10.00</PersonalPay><MedInsurePay>40.00</MedInsurePay><MedInsureTradeNO></MedInsureTradeNO></Response>';*/
		$doc = simplexml_load_string($result);
		$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		// echo "<pre>";
		// var_dump($rel);exit();
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "自助挂号保存";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		$rel['yb_zt'] = $yb_zt;
		if($rel['ResultCode']=="0000"){
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}else{
		$yb_zt = 1;
		/*$pay_type = I("post.pay_type");
		$trade_no = I("post.trade_no");
		$out_trade_no = trim(I("post.out_trade_no"));
		$operator_id = trim(I("post.operator_id"));
		$total_amount = I("post.cash")
		$rel['message'] = $this->ajax_tuikuan($pay_type,$trade_no,$out_trade_no,$total_amount,$operator_id);*/
		$rel['yb_zt'] = $yb_zt;
    	$rel['ResultContent'] = '医保失败';
	}
	
}
/***********************医保撤销********************************/
public function yb_refund_result(){
	$xml = I("post.patinfo");
	/*$xml = '<?xml version="1.0" encoding="utf8" standalone="yes"?><root version="1.00.0408"><state success="false"><error no="9001" info="社保卡无应答！---(错误函数号：5 错误号：0xfffffffd)"></error></state></root>';*/
	$xml1 = I("post.patinfo1");
	/*$xml1 = '<?xml version="1.0" encoding="utf8" standalone="yes"?><root version="1.00.0408"><state success="false"><error no="9004" info="请先调用，GetPersonInfo方法！"></error></state></root>';*/
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");//病人ID
 	$op_code = I("post.op_code");//操作码
 	$zzj_id = I("post.zzj_id");//自助机id
	$xml = htmlspecialchars_decode($xml);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	$doc = simplexml_load_string($result);
	$xml1 = htmlspecialchars_decode($xml1);
	$result1 = str_replace("UTF-16","utf8",$xml1);
	$result1 = str_replace("\\\"", "\"", $result1);
	$doc1 = simplexml_load_string($result1);
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "医保回退";
	$data['direction'] = "判断医保是否成功";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = "医保病人信息：".$result."医保回退返回：".$result1;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$state = (array)$doc->state;
	$state1 = (array)$doc1->state;
	$yb_refund_flag = "";
	if($state["@attributes"]['success']==="true" && $state1["@attributes"]['success']==="true"){
		$yb_refund_flag = 0;
		$rel['yb_refund_flag'] = $yb_refund_flag;
		
	}else{
		$yb_refund_flag = 1;
		$rel['yb_refund_flag'] = $yb_refund_flag;
    	// $rel['ResultContent'] = '医保失败';
	}
	$this->ajaxReturn($rel,"JSON");
}
public function YIBAO_GH_CALC(){
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
 	$ksdm = I("post.ksdm");
 	$ysdm = I("post.ysdm");
 	if($ysdm==""){
 		$ysdm = "0";
 	}
 	$ghjb = I("post.hbbm");
 	$sxw = I("post.sxw");
 	$patient_id = I("post.patient_id");
 	date_default_timezone_set('PRC');
 	$date = date("Y-m-d H:i:s",time());
	// header("Content-Type: text/html; charset=UTF-8");
	$str = '<?xml version="1.0" encoding="UTF-8"?><root><commitdata><data><datarow><card_code>'.$card_code.'</card_code><card_no>'.$card_no.'</card_no><zzj_id>'.$zzj_id.'</zzj_id><ksdm>'.$ksdm.'</ksdm><ysdm>'.$ysdm.'</ysdm><ghjb>'.$ghjb.'</ghjb><sxw>'.$sxw.'</sxw><recornd_sn></recornd_sn><stream_no></stream_no><trade_no></trade_no><cheque_type></cheque_type><bk_card_no></bk_card_no><lx>0</lx><trade_time></trade_time><patient_id>'.$patient_id.'</patient_id><responce_type></responce_type><gh_flag>0</gh_flag></datarow></data></commitdata><operateinfo><info><Method>YYT_GH_CALC</Method><opt_name>'.$zzj_id.'</opt_name><opt_date>'.$date.'</opt_date></info></operateinfo><result></result></root>';
		/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "医保卡患者挂号划价";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	set_time_limit (0); 
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2000";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect(); //关闭链接
	if($response==""){
		$rel['result']['info']['resultcode']="1";
		$rel['result']['info']['errormsg'] = "请求超时,请重试";
		$this->ajaxReturn($rel,"JSON");
	}else{
		$response = str_replace("GB2312","utf8",$response);
		$xml =iconv("gbk","UTF-8//IGNORE",$response);
		// \Log::write($xml);
		// $result = str_replace("gb2312","utf8",$result);
		// $xml =iconv("gb2312","utf8",$response);
		$doc = simplexml_load_string($xml);
		$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		/**************日记记录开始**************/
		$data['card_code'] = $card_code;
		$data['patient_id'] = $patient_id;
		$data['card_no'] = $card_no;
		$data['op_name'] = "医保卡患者挂号划价";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		// $data['op_xml'] =  var_export($rel,"true");
		$data['op_xml'] =  $xml;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		if($rel['result']['info']['resultcode']==0){
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}	
}
public function yibao_guahao_save_ceshi(){
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");//病人ID
 	$op_code = I("post.op_code");//操作码
 	$PayAccount = I("post.PayAccount");//支付账户582***@qq.com
 	$pay_type = I("post.pay_type");//支付类型alipay wxpay bank
 	$stream_no = I("post.stream_no");//订单号
 	$trade_no = I("post.trade_no");//交易流水号
 	$ServiceFee = I("post.ysfwf");//医事服务费
 	$charge_total = I("post.charge_total");//总金额
 	$cash = I("post.cash");//自费金额
 	$zhzf = I("post.zhzf");//账户支付金额
 	$tczf = I("post.tczf");//医保支付金额
 	$pay_seq = $zzj_id.$op_code;//预约ID
 	$ksdm = I("post.ksdm");//科室代码
 	$ysdm = I("post.ysdm");//医生代码
 	if($ysdm==""){
 		$ysdm = "0";
 	}
 	$hbbm = I("post.hbbm");//号别编码 挂号级别
 	$sxw = I("post.sxw");//上下午
 	date_default_timezone_set('PRC');
 	$trade_time = date("Y-m-d H:i:s",time());//交易时间
 	$zzj_id = I("post.zzj_id");
 	$opt_date = date("Y-m-d H:i:s",time());
	header("Content-Type: text/html; charset=UTF-8");
	// crxs 0是自助机,1是微信的
	$str = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow><card_code>'.$card_code.'</card_code><card_no>'.$card_no.'</card_no><crxs>0</crxs><zzj_id>'.$zzj_id.'</zzj_id><ksdm>'.$ksdm.'</ksdm><ysdm>'.$ysdm.'</ysdm><ghjb>'.$hbbm.'</ghjb><sxw>'.$sxw.'</sxw><recornd_sn></recornd_sn><stream_no>'.$stream_no.'</stream_no><trade_no>'.$trade_no.'</trade_no><cheque_type>'.$pay_type.'</cheque_type><bk_card_no>'.$PayAccount.'</bk_card_no><lx>1</lx><trade_time>'.$trade_time.'</trade_time><patient_id>'.$patient_id.'</patient_id><responce_type></responce_type><gh_flag>0</gh_flag></datarow></data></commitdata><operateinfo><info><Method>YYT_GH_CALC</Method><opt_name>'.$zzj_id.'</opt_name><opt_date>'.$opt_date.'</opt_date></info></operateinfo><result></result></root>';
		/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "医保卡患者挂号保存";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	set_time_limit (0); 
	// $socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	// $socketport = "2000";
	// $socket = \Socket::singleton();
	// //链接
	// $aa = $socket->connect($socketaddr,$socketport);
	// //写数据
	// $sockresult = $socket->sendrequest($str);
	// //读数据
	// $response = $socket->waitforresponse();
	// $socket->disconnect(); //关闭链接
	// $result = str_replace("gb2312","utf8",$result);
	// $xml =iconv("gb2312","utf8",$response);
	$response=="123";
	if($response==""){
		$rel['result']['info']['resultcode']="1";
		$rel['result']['info']['errormsg'] = "请求超时,请重试";
		$this->ajaxReturn($rel,"JSON");
	}else{
		$xml = '<?xml version="1.0" encoding="UTF-8"?><root><commitdata><data><datarow><record_sn></record_sn><responce_type>社会基本医疗保险</responce_type><patient_id>40463</patient_id><card_code>1</card_code><card_no>122894700001</card_no><charge_total>30.00</charge_total><cash>2.00</cash><zhzf>0</zhzf><tczf>28.00</tczf><stream_no>1</stream_no><record_id>0</record_id><bank_date>2018-06-27 09:33:14</bank_date></datarow></data></commitdata><returndata><data><datarow><patient_id>40463</patient_id><times>201806274151</times><sex>女</sex><age>3</age><patient_name>韩佳钰</patient_name><request_date>2018-06-27</request_date><gh_sequence>2</gh_sequence><ampm>1</ampm><unit_name>EZ</unit_name><doctor_name></doctor_name><diagnosis_address></diagnosis_address><diagnosis_time></diagnosis_time><clinic_type>QTYSFWFPT</clinic_type><receipt_sn></receipt_sn><charge_total>30.00</charge_total><cash>2.00</cash><zhzf>0</zhzf><tczf>28.00</tczf><receipt_sn>0</receipt_sn><cash_date>2018-06-27</cash_date><sequence_no>3</sequence_no><outpatient_no>3</outpatient_no><clinic_name>普通号</clinic_name><response_type>社会基本医疗保险</response_type><location_info>儿科中西医门诊在二楼中厅</location_info><shuoming_info>1</shuoming_info></datarow></data></returndata><operateinfo><info><method>yyt_gh_calc</method><opt_name>zzfw6</opt_name><opt_date>2018-06-27 09:33:03</opt_date></info></operateinfo><result><info><resultcode>1</resultcode><errormsg>执行成功</errormsg><account>2018-06-27 09:33:06</account></info></result></root>';
	    // $xml = str_replace("GB2312","utf8",$xml);
		// $xml =iconv("gbk","UTF-8//IGNORE",$response);
		$doc = simplexml_load_string($xml);
		$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		/**************日记记录开始**************/
		$data['card_code'] = $card_code;
		$data['patient_id'] = $patient_id;
		$data['card_no'] = $card_no;
		$data['op_name'] = "医保卡患者挂号保存";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		// $data['op_xml'] =  var_export($rel,"true");
		$data['op_xml'] =  $xml;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		if($rel['result']['info']['resultcode']==0){
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}
}

public function yibao_guahao_save(){
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");//病人ID
 	$op_code = I("post.op_code");//操作码
 	$PayAccount = I("post.PayAccount");//支付账户582***@qq.com
 	$pay_type = I("post.pay_type");//支付类型alipay wxpay bank
 	$stream_no = I("post.stream_no");//订单号
 	$trade_no = I("post.trade_no");//交易流水号
 	$ServiceFee = I("post.ysfwf");//医事服务费
 	$charge_total = I("post.charge_total");//总金额
 	$cash = I("post.cash");//自费金额
 	$zhzf = I("post.zhzf");//账户支付金额
 	$tczf = I("post.tczf");//医保支付金额
 	$pay_seq = $zzj_id.$op_code;//预约ID
 	$ksdm = I("post.ksdm");//科室代码
 	$ysdm = I("post.ysdm");//医生代码
 	if($ysdm==""){
 		$ysdm = "0";
 	}
 	$hbbm = I("post.hbbm");//号别编码 挂号级别
 	$sxw = I("post.sxw");//上下午
 	date_default_timezone_set('PRC');
 	$trade_time = date("Y-m-d H:i:s",time());//交易时间
 	$zzj_id = I("post.zzj_id");
 	$opt_date = date("Y-m-d H:i:s",time());
	header("Content-Type: text/html; charset=UTF-8");
	// crxs 0是自助机,1是微信的
	$str = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow><card_code>'.$card_code.'</card_code><card_no>'.$card_no.'</card_no><crxs>0</crxs><zzj_id>'.$zzj_id.'</zzj_id><ksdm>'.$ksdm.'</ksdm><ysdm>'.$ysdm.'</ysdm><ghjb>'.$hbbm.'</ghjb><sxw>'.$sxw.'</sxw><recornd_sn></recornd_sn><stream_no>'.$stream_no.'</stream_no><trade_no>'.$trade_no.'</trade_no><cheque_type>'.$pay_type.'</cheque_type><bk_card_no>'.$PayAccount.'</bk_card_no><lx>1</lx><trade_time>'.$trade_time.'</trade_time><patient_id>'.$patient_id.'</patient_id><responce_type></responce_type><gh_flag>0</gh_flag></datarow></data></commitdata><operateinfo><info><Method>YYT_GH_CALC</Method><opt_name>'.$zzj_id.'</opt_name><opt_date>'.$opt_date.'</opt_date></info></operateinfo><result></result></root>';
		/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "医保卡患者挂号保存";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	set_time_limit (0); 
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2000";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect(); //关闭链接
	// $result = str_replace("gb2312","utf8",$result);
	// $xml =iconv("gb2312","utf8",$response);
	if($response==""){
		$rel['result']['info']['resultcode']="1";
		$rel['result']['info']['errormsg'] = "请求超时,请重试";
		$this->ajaxReturn($rel,"JSON");
	}else{
	    $response = str_replace("GB2312","utf8",$response);
		$xml =iconv("gbk","UTF-8//IGNORE",$response);
		$doc = simplexml_load_string($xml);
		$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		/**************日记记录开始**************/
		$data['card_code'] = $card_code;
		$data['patient_id'] = $patient_id;
		$data['card_no'] = $card_no;
		$data['op_name'] = "医保卡患者挂号保存";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		// $data['op_xml'] =  var_export($rel,"true");
		$data['op_xml'] =  $xml;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		if($rel['result']['info']['resultcode']==0){
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}
}
//获取诊间加号记录
public function getPatReservationRecord(){
	//需要三个参数 病人id  卡类型 卡号 21代表就诊卡 20 代表社保卡
	$patient_id = I("post.patient_id");
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
    $zzj_id = I("post.zzj_id");
    $op_code = I("post.op_code");
   // var_dump($patient_id);
    $soap = new \SoapClient('http://172.168.1.207:8088/chisWebServics?service=HISImpl');
    $s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow  patient_id="'.$patient_id.'" card_code="'.$card_code.'" card_no="'.$card_no.'" /></data></commitdata><returndata/><operateinfo><info method="YYT_QRY_APP" opt_id="'.$zzj_id.'" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="2014-06-06" guid="'.$op_code.'" token="AUTO-YYRMYY-'.date("Y-m-d").'" /></operateinfo><result><info /></result></root>';
    /**********日志记录开始*******************************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "预约记录查询中";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $s;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk($log_header."发送报文信息：".$s));
/**********日志记录结束*******************************/
    $params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_QRY_DOCTOR_ADD';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	$data2['card_code'] = $card_code;
	$data2['op_xml'] = $result;
	$data2['op_name'] = "获取换预约信息但会报文";
	$data2['op_code'] = I("post.op_code");
	$data2['direction'] = "返回报文";
	M("logs")->add($data2);
	$result_ary = $doc->result->info;
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel['result'] = $result_return;
	$datarow =  $doc->returndata->data->datarow;
	foreach($datarow as $val)
	{
			$datarow_attrs = array();
			foreach($val->attributes() as $a => $b)
			{
				$b = (array)$b;
				$datarow_attrs[$a] = $b[0];
			}
           $rel["datarow"][]=$datarow_attrs;
	}
	//var_dump($rel);
	$this->ajaxReturn($rel,"JSON");
}
//预约取号划价
public function yuyue_huajia(){
	$soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	$ksdm = I("post.ksdm");
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
	$pati_id = I("post.patient_id");
	$op_code = I("post.op_code");
	$zzj_id = I("post.zzj_id");
	$pbmxxh =I("post.pbmxxh");
	$s="<zzxt><transcode>202</transcode><table><zzjdbh>".$zzj_id."</zzjdbh><flag>0</flag><patid>".$pati_id."</patid><ghlb>9</ghlb><ksdm>".$ksdm."</ksdm><ysdm></ysdm><czyh>".$zzj_id."</czyh><cfzbz>0</cfzbz><pbmxxh>".$pbmxxh."</pbmxxh><sjh></sjh><lybz>1</lybz></table></zzxt>";
	//记录日志
	$data['card_no'] = $card_no;
	$data['card_code'] = $card_code; 
	$data['op_name'] = "预约挂号划价预算";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $s;
	$data['op_code'] = $op_code;
	M("logs")->add($data);
	//结束日志
	$params->AXml = $s;
	$row = $soap->CallService($params);
	$CallService = $row->{"CallServiceResult"};
	// $CallService = '<zzxt><result><retcode>0</retcode><retmsg>交易成功</retmsg></result><table><patid>647113</patid><sjh>20170705zzj0110060</sjh><fyze>30</fyze><ysje>30</ysje><zhzf>0</zhzf><jlxh></jlxh><ghlb>0</ghlb><isyb>0</isyb><cardno>107489721006</cardno></table></zzxt>';
    $OB_CallService = simplexml_load_string($CallService);
    $rel = json_decode(json_encode($OB_CallService),ture);
    $data2['card_code'] = $card_code;
	$data2['card_no'] = $card_no;
	$data2['op_xml'] = $CallService;
	$data2['op_name'] = "预约挂号划价预算";
	$data2['op_code'] = I("post.op_code");
	$data2['direction'] = "返回报文";
	M("logs")->add($data2);
	$this->ajaxReturn($rel,"JSON");
}

//取号划价
	public function quhao_huajia(){
	$record_sn = I("post.record_sn");
	$patient_id =I("post.patient_id");
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
	$req_type = I("post.req_type");
	$zzj_id = I("post.zzj_id");
	$op_code = I("post.op_code");
	$soap = new \SoapClient('http://172.168.1.207:8088/chisWebServics?service=HISImpl');
	$s='<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow record_sn="'.$record_sn.'" patient_id="'.$patient_id.'" card_code="'.$card_code.'" card_no="'.$card_no.'" req_type="'.$req_type.'"  gh_flag="3" /></data></commitdata><returndata/><operateinfo><info method="YYT_GH_CALC" opt_id="'.$zzj_id.'" opt_name="" opt_ip="'.get_client_ip().'" opt_date="'.date("Y-m-d").'" guid="'.generate_code(10).'" token="AUTO-YYRMYY-'.date("Y-m-d").'"/></operateinfo><result><info /></result></root>';
/**********日志记录开始*******************************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "预约取号划价划价";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $s;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk($log_header."发送报文信息：".$s));
/**********日志记录结束*******************************/
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_GH_CALC';
	$params->requestData = $s;
	$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	/**************日记记录开始***********/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = $log_header."划价";
	$data['direction'] = "HIS返回报文";
	$data['op_xml'] = $result;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk($log_header."HIS返回报文：".$result));
	$doc = simplexml_load_string($result);
	$result_ary = $doc->result->info;
	//var_dump($result_ary);
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
/**********预约挂号His费用确认*******************************/
public function yyt_gh_save(){
	$soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
	$patient_id = I("post.patient_id");
	$pay_charge_total = I("post.pay_charge_total");
	$pay_seq = I("post.pay_seq");//收据号
	$trade_no = I("post.trade_no");//流水号
	$stream_no = I("post.stream_no");//订单号 咱们的
	$op_code = I("post.op_code");
	$zzj_id = I("post.zzj_id");
	$pay_type = I("post.pay_type");//交易类型
	$idCard = I("post.idCard");//支付账号
	$ksdm = I("post.ksdm");//科室代码
	$jssjh = I("post.jssjh");//结算收据号
	$RespCode = I("post.RespCode");//交易返回码
	// $RespInfo = I("post.RespInfo");//返回码中文解释
	$idCard = I("post.idCard");//卡号
	$Amount = I("post.Amount");//交易返回金额
	$Batch = I("post.Batch");//交易返回批次号
	$TransDate = I("post.TransDate");//交易返回交易日期
	$TransTime = I("post.TransTime");//交易返回交易时间
	$Ref = I("post.Ref");//交易返回系统参考号
	$Auth = I("post.Auth");//授权号
	$Memo = I("post.Memo");//附加信息
	$Lrc = I("post.Lrc");//Lrc
	$str = "<zzxt><transcode>202</transcode><table><zzjdbh>".$zzj_id."</zzjdbh><flag>1</flag><patid>".$patient_id."</patid><ghlb>9</ghlb><ksdm>".$ksdm."</ksdm><ysdm></ysdm><czyh>".$zzj_id."</czyh><sjh>".$jssjh."</sjh><lybz>1</lybz><useyb>0</useyb><ybzd01></ybzd01><ybzd02></ybzd02><ybzd03></ybzd03><ybzd04></ybzd04><ybzd05></ybzd05><ybzd06></ybzd06><ybzd07></ybzd07><ybzd08></ybzd08><ybzd09></ybzd09><ybzd10></ybzd10><ybzd11></ybzd11><ybzd12></ybzd12><ybzd13></ybzd13><ybzd14></ybzd14><ybzd15></ybzd15><ybzd16></ybzd16><ybzd17></ybzd17><ybzd18></ybzd18><ybzd19></ybzd19><ybzd20></ybzd20><ybzd21></ybzd21><ybzd22></ybzd22><ybzd23></ybzd23><ybzd24></ybzd24><ybzd25></ybzd25><usebank>".$pay_type."</usebank><yhzd01>".$pay_charge_total."</yhzd01><yhzd02></yhzd02><yhzd03></yhzd03><yhzd04></yhzd04><yhzd05></yhzd05><yhzd06></yhzd06><yhzd07></yhzd07><yhzd08></yhzd08><yhzd09></yhzd09><yhzd10></yhzd10><yhzd11></yhzd11><yhzd12></yhzd12><yhzd13></yhzd13><yhzd14></yhzd14><yhzd15></yhzd15><ylzd01>".$trade_no."</ylzd01><ylzd02>".$stream_no."</ylzd02><ylzd03>".$idCard."</ylzd03><ylzd04></ylzd04><ylzd05></ylzd05><ylzd06></ylzd06></table></zzxt>";
	if($pay_type == 1){
		$str = "<zzxt><transcode>202</transcode><table><zzjdbh>".$zzj_id."</zzjdbh><flag>1</flag><patid>".$patient_id."</patid><ghlb>9</ghlb><ksdm>".$ksdm."</ksdm><ysdm></ysdm><czyh>".$zzj_id."</czyh><sjh>".$jssjh."</sjh><lybz>1</lybz><useyb>0</useyb><ybzd01></ybzd01><ybzd02></ybzd02><ybzd03></ybzd03><ybzd04></ybzd04><ybzd05></ybzd05><ybzd06></ybzd06><ybzd07></ybzd07><ybzd08></ybzd08><ybzd09></ybzd09><ybzd10></ybzd10><ybzd11></ybzd11><ybzd12></ybzd12><ybzd13></ybzd13><ybzd14></ybzd14><ybzd15></ybzd15><ybzd16></ybzd16><ybzd17></ybzd17><ybzd18></ybzd18><ybzd19></ybzd19><ybzd20></ybzd20><ybzd21></ybzd21><ybzd22></ybzd22><ybzd23></ybzd23><ybzd24></ybzd24><ybzd25></ybzd25><usebank>".$pay_type."</usebank><yhzd01>".$pay_charge_total."</yhzd01><yhzd02>".$trade_no."</yhzd02><yhzd03></yhzd03><yhzd04></yhzd04><yhzd05>".$TransDate."</yhzd05><yhzd06>".$TransTime."</yhzd06><yhzd07>".$idCard."</yhzd07><yhzd08></yhzd08><yhzd09></yhzd09><yhzd10>".$Batch."</yhzd10><yhzd11>".$Auth."</yhzd11><yhzd12>".$Memo."</yhzd12><yhzd13>".$Lrc."</yhzd13><yhzd14>".$Ref."</yhzd14><yhzd15>".$RespInfo."</yhzd15><ylzd01></ylzd01><ylzd02></ylzd02><ylzd03></ylzd03><ylzd04></ylzd04><ylzd05></ylzd05><ylzd06></ylzd06></table></zzxt>";
	}
	$params->AXml = $str;
	$row = $soap->CallService($params);
	$CallService = $row->{"CallServiceResult"};
    $OB_CallService = simplexml_load_string($CallService);
    $rel = json_decode(json_encode($OB_CallService),ture);
    //发送日志
    $data['patient_id'] = $patient_id;
	$data['op_name'] = "自费挂号HIS保存";
	$data['direction'] = "发送报文";
	$data['op_code'] = $op_code;
	$data['op_xml'] = $str;
	M("logs")->add($data);
	//返回日志
	$data1['patient_id'] = $patient_id;
	$data1['op_name'] = "自费挂号HIS保存";
	$data1['direction'] = "返回报文";
	$data1['op_code'] = $op_code;
	$data1['op_xml'] = $CallService;
	M("logs")->add($data1);
    $this->ajaxReturn($rel,"JSON");

}
//检查自助机是否缺纸
function jian_ce(){
	$zzj_id = I("post.zzj_id");
	$devstatus=M("devstatus");
	$list=$devstatus->where("DevName='$zzj_id'")->find();
	$this->ajaxReturn($list,"JSON");
}
//诊间取号保存
public function yyt_qh_save(){
	$soap = new \SoapClient('http://172.168.1.207:8088/chisWebServics?service=HISImpl');
	$record_sn  = I("post.record_sn");
	$patient_id = I("post.patient_id");
	$card_code = I("post.card_code");
	$zzj_id = "ZZ001";//I("post.zzj_id");
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
	//$zzj_id = I("post.zzj_id");
	//var_dump(I("post.zzj_id"));
	$bk_card_no=  I("post.bk_card_no");
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
	$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow record_sn="'.$record_sn.'"  pay_seq="'.$pay_seq.'" responce_type="" patient_id="'.$patient_id.'" card_code="'.$card_code.'" card_no="'.$card_no.'" charge_total="'.$charge_total.'" cash="'.$cash.'" zhzf="'.$zhzf.'" tczf="'.$tczf.'"  record_id="" gh_sequence="" bk_card_no="" trade_no="'.$trade_no.'" stream_no="'.$stream_no.'" addition_no1="" trade_time="'.date("Y-m-d").'" cheque_type="'.$cheque_type.'" gh_flag="3" bank_type=""/></data></commitdata><returndata/><operateinfo><info method="YYT_GH_SAVE" opt_id="'.$zzj_id.'" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.date("Y-m-d").'" guid="'.$stream_no.'" token="AUTO-YYRMYY-'.date("Y-m-d").'"  /></operateinfo><result><info /></result></root>';
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "预约取号就诊卡HIS费用确认";
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
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "预约取号就诊卡HIS费用确认";
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
		if($rel['result']['execute_flag']=="-3" || $rel['result']['execute_flag']=="-2"){
        $rel['pay_result']["code"]="9999";
		}else{
		switch($pay_type){
		case "alipay"://支付宝退费
		$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');
		$row = $soap->refund($trade_no,$cash,$stream_no,"zzj");
		$xml = simplexml_load_string($row);
		$xml = (array)$xml;
		
		$rel['pay_result'] = (array)$xml['Message'];
		break;
		}
	}
	}
	$rel['datarow'] = $datarow_ary;
	//var_dump($rel);
	$this->ajaxReturn($rel,"JSON");

}
//挂号费用确认解析
function YbSfConfirmXmlParseBhao(){
	$xml =iconv("utf8","gb2312",htmlspecialchars_decode(I("post.input_xml")));
	$doc = simplexml_load_string($xml);	
	/**************日记记录开始***********/
	$data['card_code'] = '20';
	$data['op_name'] = "挂号医保费用确认";
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
	}else{
		if($rel['result']['execute_flag']=="-3" || $rel['result']['execute_flag']=="-2"){
          $rel['pay_result']["code"]="9999";
		}else{
		switch($pay_type){
		case "alipay"://支付宝退费
		$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');
		$confirm_input_ary = $doc->commitdata->data->datarow;
		$save_input_xml_ary = array();
		foreach($confirm_input_ary->attributes() as $a => $b)
		{
			$b = (array)$b;
			$save_input_xml_ary[$a] = $b[0];
		}
		$row = $soap->refund($save_input_xml_ary['trade_no'],$save_input_xml_ary['cash'],$save_input_xml_ary['stream_no'],"zzj");
		$xml = simplexml_load_string($row);
		$xml = (array)$xml;
		$rel['pay_result'] = (array)$xml['Message'];
		break;
		}
	}
		
		}
	//$this->writeLog(utf8_to_gbk("医保费用确认返回XML转 数组：".var_export($rel,true)));
	//iconv("utf8","gb2312",htmlspecialchars_decode(I("post.input_xml")))
	$this->writeLog(iconv("utf8","gbk","医保费用确认返回XML转数组：".var_export($rel,true)));
	$this->ajaxReturn($rel,"JSON");
}
//预约取号费用确认解析
function YbSfConfirmXmlParseGhao(){
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
	$patient_id = I("post.patient_id");
	$pay_charge_total = I("post.pay_charge_total");//自费金额
	$pay_seq = I("post.pay_seq");//收据号
	$trade_no = I("post.trade_no");//流水号
	$stream_no = I("post.stream_no");//订单号 咱们的
	$op_code = I("post.op_code");
	$zzj_id = I("post.zzj_id");
	$pay_type = I("post.pay_type");//交易类型
	$idCard = I("post.idCard");//支付账号
	$ksdm = I("post.ksdm");//科室代码
	$jssjh = I("post.jssjh");//结算收据号
	$ybdf = I("post.yb_dfje");//医保垫付金额
	$yb_jssjh = I("post.yb_fjlsh");//医保分解流水
	$xml = I("post.input_xml");
	$yb_zlf = I("post.yb_zlf");//医保挂号总费用
	//银行字段
	$RespCode = I("post.RespCode");//交易返回码
	// $RespInfo = I("post.RespInfo");//返回码中文解释
	$idCard = I("post.idCard");//卡号
	$Amount = I("post.Amount");//交易返回金额
	$Batch = I("post.Batch");//交易返回批次号
	$TransDate = I("post.TransDate");//交易返回交易日期
	$TransTime = I("post.TransTime");//交易返回交易时间
	$Ref = I("post.Ref");//交易返回系统参考号
	$Auth = I("post.Auth");//授权号
	$Memo = I("post.Memo");//附加信息
	$Lrc = I("post.Lrc");//Lrc
	//医保字段
	$ybzd1= I("post.ybzd1");
	$ybzd2= I("post.ybzd2");
	$ybzd3= I("post.ybzd3");
	$ybzd4= I("post.ybzd4");
	$ybzd5= I("post.ybzd5");
	$ybzd6= I("post.ybzd6");
	$ybzd7= I("post.ybzd7");
	$ybzd8= I("post.ybzd8");
	$ybzd9= I("post.ybzd9");
	$ybzd10= I("post.ybzd10");
	$ybzd11= I("post.ybzd11");
	$ybzd12= I("post.ybzd12");
	$ybzd13= I("post.ybzd13");
	$ybzd14= I("post.ybzd14");
	$ybzd15= I("post.ybzd15");
	$ybzd16= I("post.ybzd16");
	$ybzd17= I("post.ybzd17");
	$ybzd18= I("post.ybzd18");
	$ybzd19= I("post.ybzd19");
	$ybzd20= I("post.ybzd20");
	$ybzd21= I("post.ybzd21");
	$ybzd22= I("post.ybzd22");
	$ybzd23= I("post.ybzd23");
	$ybzd24= I("post.ybzd24");
	$ybzd25= I("post.ybzd25");
	$ybzd26= I("post.ybzd26");
	$ybzd27= I("post.ybzd27");
	$ybzd28= I("post.ybzd28");
	$ybzd29= I("post.ybzd29");
	$ybzd30= I("post.ybzd30");
	$ybzd31= I("post.ybzd31");
	$ybzd32= I("post.ybzd32");
	$ybzd33= I("post.ybzd33");
	$ybzd34= I("post.ybzd34");
	$ybzd35= I("post.ybzd35");
	$ybzd36= I("post.ybzd36");
	$ybzd37= I("post.ybzd37");
	$ybzd38= I("post.ybzd38");
	$ybzd39= I("post.ybzd39");
	$ybzd40= I("post.ybzd40");
	$ybzd41= I("post.ybzd41");
	$ybzd42= I("post.ybzd42");
	$ybzd43= I("post.ybzd43");
	$ybzd44= I("post.ybzd44");
	$ybzd45= I("post.ybzd45");
	$ybzd46= I("post.ybzd46");
	$ybzd47= I("post.ybzd47");
	$ybzd48= I("post.ybzd48");
	$ybzd49= I("post.ybzd49");
	$ybzd50= I("post.ybzd50");
	$xml = htmlspecialchars_decode($xml);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	$doc = simplexml_load_string($result);
	//发送日志
    $data2['patient_id'] = $patient_id;
	$data2['op_name'] = "医保分解保存";
	$data2['direction'] = "返回报文";
	$data2['op_code'] = $op_code;
	$data2['op_xml'] = $result;
	M("logs")->add($data2);
	$state = (array)$doc->state;
	$yb_zt = "";
	if($state["@attributes"]['success']==="true"){
		$yb_zt = 0;
		$soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
		$str = "<zzxt><transcode>202</transcode><table><zzjdbh>".$zzj_id."</zzjdbh><flag>1</flag><patid>".$patient_id."</patid><ghlb>9</ghlb><ksdm>".$ksdm."</ksdm><ysdm></ysdm><czyh>".$zzj_id."</czyh><sjh>".$jssjh."</sjh><lybz>1</lybz><useyb>1</useyb><ybzd01>".$ybzd1."</ybzd01><ybzd02>".$ybzd2."</ybzd02><ybzd03>".$ybzd3."</ybzd03><ybzd04>".$ybzd4."</ybzd04><ybzd05>".$ybzd5."</ybzd05><ybzd06>".$ybzd6."</ybzd06><ybzd07>".$ybzd7."</ybzd07><ybzd08>".$ybzd8."</ybzd08><ybzd09>".$ybzd9."</ybzd09><ybzd10>".$ybzd10."</ybzd10><ybzd11>".$ybzd11."</ybzd11><ybzd12>".$ybzd12."</ybzd12><ybzd13>".$ybzd13."</ybzd13><ybzd14>".$ybzd14."</ybzd14><ybzd15>".$ybzd15."</ybzd15><ybzd16>".$ybzd16."</ybzd16><ybzd17>".$ybzd17."</ybzd17><ybzd18>".$ybzd18."</ybzd18><ybzd19>".$ybzd19."</ybzd19><ybzd20>".$ybzd20."</ybzd20><ybzd21>".$ybzd21."</ybzd21><ybzd22>".$ybzd22."</ybzd22><ybzd23>".$ybzd23."</ybzd23><ybzd24>".$ybzd24."</ybzd24><ybzd25>".$ybzd25."</ybzd25><ybzd26>".$ybzd26."</ybzd26><ybzd27>".$ybzd27."</ybzd27><ybzd28>".$ybzd28."</ybzd28><ybzd29>".$ybzd29."</ybzd29><ybzd30>".$ybzd30."</ybzd30><ybzd31>".$ybzd31."</ybzd31><ybzd32>".$ybzd32."</ybzd32><ybzd33>".$ybzd33."</ybzd33><ybzd34>".$ybzd34."</ybzd34><ybzd35>".$ybzd35."</ybzd35><ybzd36>".$ybzd36."</ybzd36><ybzd37>".$ybzd37."</ybzd37><ybzd38>".$ybzd38."</ybzd38><ybzd39>".$ybzd39."</ybzd39><ybzd40>".$ybzd40."</ybzd40><ybzd41>".$ybzd41."</ybzd41><ybzd42>".$ybzd42."</ybzd42><ybzd43>".$ybzd43."</ybzd43><ybzd44>".$ybzd44."</ybzd44><ybzd45>".$ybzd45."</ybzd45><ybzd46>".$ybzd46."</ybzd46><ybzd47>".$ybzd47."</ybzd47><ybzd48>".$ybzd48."</ybzd48><ybzd49>".$ybzd50."</ybzd49><ybzd50>".$ybzd49."</ybzd50><usebank>".$pay_type."</usebank><yhzd01>".$pay_charge_total."</yhzd01><yhzd02></yhzd02><yhzd03></yhzd03><yhzd04></yhzd04><yhzd05></yhzd05><yhzd06></yhzd06><yhzd07></yhzd07><yhzd08></yhzd08><yhzd09></yhzd09><yhzd10></yhzd10><yhzd11></yhzd11><yhzd12></yhzd12><yhzd13></yhzd13><yhzd14></yhzd14><yhzd15></yhzd15><ylzd01>".$trade_no."</ylzd01><ylzd02>".$stream_no."</ylzd02><ylzd03>".$idCard."</ylzd03><ylzd04></ylzd04><ylzd05></ylzd05><ylzd06></ylzd06></table></zzxt>";
			if($pay_type == 1){
				$str = "<zzxt><transcode>202</transcode><table><zzjdbh>".$zzj_id."</zzjdbh><flag>1</flag><patid>".$patient_id."</patid><ghlb>9</ghlb><ksdm>".$ksdm."</ksdm><ysdm></ysdm><czyh>".$zzj_id."</czyh><sjh>".$jssjh."</sjh><lybz>1</lybz><useyb>1</useyb><ybzd01>".$ybzd1."</ybzd01><ybzd02>".$ybzd2."</ybzd02><ybzd03>".$ybzd3."</ybzd03><ybzd04>".$ybzd4."</ybzd04><ybzd05>".$ybzd5."</ybzd05><ybzd06>".$ybzd6."</ybzd06><ybzd07>".$ybzd7."</ybzd07><ybzd08>".$ybzd8."</ybzd08><ybzd09>".$ybzd9."</ybzd09><ybzd10>".$ybzd10."</ybzd10><ybzd11>".$ybzd11."</ybzd11><ybzd12>".$ybzd12."</ybzd12><ybzd13>".$ybzd13."</ybzd13><ybzd14>".$ybzd14."</ybzd14><ybzd15>".$ybzd15."</ybzd15><ybzd16>".$ybzd16."</ybzd16><ybzd17>".$ybzd17."</ybzd17><ybzd18>".$ybzd18."</ybzd18><ybzd19>".$ybzd19."</ybzd19><ybzd20>".$ybzd20."</ybzd20><ybzd21>".$ybzd21."</ybzd21><ybzd22>".$ybzd22."</ybzd22><ybzd23>".$ybzd23."</ybzd23><ybzd24>".$ybzd24."</ybzd24><ybzd25>".$ybzd25."</ybzd25><ybzd26>".$ybzd26."</ybzd26><ybzd27>".$ybzd27."</ybzd27><ybzd28>".$ybzd28."</ybzd28><ybzd29>".$ybzd29."</ybzd29><ybzd30>".$ybzd30."</ybzd30><ybzd31>".$ybzd31."</ybzd31><ybzd32>".$ybzd32."</ybzd32><ybzd33>".$ybzd33."</ybzd33><ybzd34>".$ybzd34."</ybzd34><ybzd35>".$ybzd35."</ybzd35><ybzd36>".$ybzd36."</ybzd36><ybzd37>".$ybzd37."</ybzd37><ybzd38>".$ybzd38."</ybzd38><ybzd39>".$ybzd39."</ybzd39><ybzd40>".$ybzd40."</ybzd40><ybzd41>".$ybzd41."</ybzd41><ybzd42>".$ybzd42."</ybzd42><ybzd43>".$ybzd43."</ybzd43><ybzd44>".$ybzd44."</ybzd44><ybzd45>".$ybzd45."</ybzd45><ybzd46>".$ybzd46."</ybzd46><ybzd47>".$ybzd47."</ybzd47><ybzd48>".$ybzd48."</ybzd48><ybzd49>".$ybzd50."</ybzd49><ybzd50>".$ybzd49."</ybzd50><usebank>".$pay_type."</usebank><yhzd01>".$pay_charge_total."</yhzd01><yhzd02>".$trade_no."</yhzd02><yhzd03></yhzd03><yhzd04></yhzd04><yhzd05>".$TransDate."</yhzd05><yhzd06>".$TransTime."</yhzd06><yhzd07>".$idCard."</yhzd07><yhzd08></yhzd08><yhzd09></yhzd09><yhzd10>".$Batch."</yhzd10><yhzd11>".$Auth."</yhzd11><yhzd12>".$Memo."</yhzd12><yhzd13>".$Lrc."</yhzd13><yhzd14>".$Ref."</yhzd14><yhzd15>".$RespInfo."</yhzd15><ylzd01></ylzd01><ylzd02></ylzd02><ylzd03></ylzd03><ylzd04></ylzd04><ylzd05></ylzd05><ylzd06></ylzd06></table></zzxt>";
			}
			$params->AXml = $str;
			$row = $soap->CallService($params);
			$CallService = $row->{"CallServiceResult"};
		    $OB_CallService = simplexml_load_string($CallService);
		    $rel = json_decode(json_encode($OB_CallService),ture);
		    //发送日志
		    $data['patient_id'] = $patient_id;
			$data['op_name'] = "医保挂号HIS保存";
			$data['direction'] = "发送报文";
			$data['op_code'] = $op_code;
			$data['op_xml'] = $str;
			M("logs")->add($data);
			//返回日志
			$data1['patient_id'] = $patient_id;
			$data1['op_name'] = "医保挂号HIS保存";
			$data1['direction'] = "返回报文";
			$data1['op_code'] = $op_code;
			$data1['op_xml'] = $CallService;
			M("logs")->add($data1);
	}else{
		$yb_zt = 1;
	}
	$rel['yb_zt'] = $yb_zt;
    $this->ajaxReturn($rel,"JSON");
}
//预约取号医保卡获取患者信息
function YbXmlParse(){
	$xml = I("post.input_xml");
	$op_code = I("post.op_code");
	$card_code = I("post.card_code");
	$zzj_id = I("post.zzj_id");
	$xml = htmlspecialchars_decode($xml);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	$doc = simplexml_load_string($result);
	$state = (array)$doc->state;
    $isinredlist = (array)$doc->output->net;
    $card_no = (array)$doc->output->ic;
	if($state["@attributes"]['success']=="true" && $isinredlist['isinredlist']=="true" && $isinredlist['isspecifiedhosp']=="1"){
		$card_no = $card_no['card_no'];
		$rel['error'] = "1";
	}else{
		$rel['error'] = "读卡失败,请去窗口处理";
		$this->ajaxReturn($rel,"JSON");
	}
	//参保人员类别
	$persontype = $isinredlist['persontype'];
	// $this->writeLog(iconv("utf8","gb2312","调用医保DLL获取患者医保返回信息").$xml,"INFO");
	//根据医保卡号获取患者pat_id
	$soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	$str = "<zzxt><transcode>101</transcode><table><cardno>".$card_no."</cardno><cardtype>".$card_code."</cardtype><czyh>".$zzj_id."</czyh><ybrylx>".$persontype."</ybrylx></table></zzxt>";
 	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['card_no'] =  $card_no;
	$data['op_name'] = "调用医保DLL获取患者医保信息";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $str;
	$data['op_code'] = $op_code;
	M("logs")->add($data);
	$params->AXml = $str;
	$row = $soap->CallService($params);
	$CallService = $row->{"CallServiceResult"};
    $OB_CallService = simplexml_load_string($CallService);
    $rel['patInfo'] = json_decode(json_encode($OB_CallService),ture);
    /**************日记记录开始***********/
	$data1['card_code'] = $card_code;
	$data1['card_no'] =  $card_no;
	$data1['op_name'] = "调用医保DLL获取患者医保信息";
	$data1['direction'] = "返回报文";
	$data1['op_xml'] = $CallService;
	$data1['op_code'] = $op_code;
	M("logs")->add($data1);
	/******************获取预约记录*********************/
	$pat_id = $rel['patInfo']['table']['patid'];
	$yuyue_xinxi = $this->yuyue_xinxi($card_no,$card_code,$op_code,$pat_id);
	$rel['datarow'] = $yuyue_xinxi['datarow'];
	$rel['xinxi_zt'] = $yuyue_xinxi;
	$this->ajaxReturn($rel,"JSON");
}
/**********挂号划价*******************************/
function reg_huajia(){
	$soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	$card_code = I("post.card_code");
    $card_no = I("post.card_no"); 
    $patient_id = I("post.patient_id");
    $op_code = I("post.op_code");
    $zzj_id = I("post.zzj_id");
    $req_type = I("post.req_type");//0213
    $pbmxxh = I("post.pbmxxh");
	$s="<zzxt><transcode>202</transcode><table><zzjdbh>".$zzj_id."</zzjdbh><flag>0</flag><patid>".$patient_id."</patid><ghlb>9</ghlb><ksdm>".$req_type."</ksdm><ysdm></ysdm><czyh>".$zzj_id."</czyh><cfzbz>0</cfzbz><pbmxxh>".$pbmxxh."</pbmxxh><sjh></sjh><lybz>1</lybz></table></zzxt>";
	//记录日志
	$data['card_no'] = $card_no;
	$data['card_code'] = $card_code; 
	$data['op_name'] = "预约挂号划价预算";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $s;
	$data['op_code'] = $op_code;
	M("logs")->add($data);
	//结束日志
	$params->AXml = $s;
	$row = $soap->CallService($params);
	$CallService = $row->{"CallServiceResult"};
    $OB_CallService = simplexml_load_string($CallService);
    $rel = json_decode(json_encode($OB_CallService),ture);
    $data2['card_code'] = $card_code;
	$data2['card_no'] = $card_no;
	$data2['op_xml'] = $CallService;
	$data2['op_name'] = "预约挂号划价预算";
	$data2['op_code'] = I("post.op_code");
	$data2['direction'] = "返回报文";
	M("logs")->add($data2);
	$this->ajaxReturn($rel,"JSON");
}
//解析医保划价返回XML
function YbHalcParseGhao(){
	$xml = I("post.input_xml");
	$xml = htmlspecialchars_decode($xml);
	$result = str_replace("UTF-16","utf8",$xml);
	$result = str_replace("\\\"", "\"", $result);
	$doc = simplexml_load_string($result);
	$data['op_xml'] = $result;
	M("logs")->add($data);
	$rel = $doc->output->payinfo;
	$rel = (array)$rel;
	$state = (array)$doc->state;
	$tradeinfo = $doc->output->tradeinfo;
	$tradeinfo = (array)$tradeinfo;
	$tradeno  = $tradeinfo['tradeno'];//医保分解交易流水号
	$money = $rel['mzfee'];//总金额
	$money_zf =$rel['mzfeeout'] ;//自付金额
	$money_ybzf = $rel['mzfeein'];//医保支付
	$rel_s['money'] =$money;
	$rel_s['money_zf'] = $money_zf;
	$rel_s['money_ybzf'] = $money_ybzf;
	$rel_s['tradeno'] = $tradeno;
	//交易信息
	$tradeinfo = $doc->output->tradeinfo;
	$tradeinfo = (array)$tradeinfo;
	$ybzd01 = $tradeinfo['tradeno'];
	$ybzd02 = $tradeinfo['feeno'];
	$ybzd03 = $tradeinfo['tradedate'];
	//汇总支付信息
	$sumpay = $doc->output->sumpay;
	$sumpay = (array)$sumpay;
	$ybzd04 = $sumpay['feeall'];
	$ybzd05 = $sumpay['fund'];
	$ybzd06 = $sumpay['cash'];
	$ybzd07 = $sumpay['personcountpay'];
	//支付信息
	$payinfo = $doc->output->payinfo;
	$payinfo = (array)$payinfo;
	$ybzd08 = $payinfo['mzfee'];
	$ybzd09 = $payinfo['mzfeein'];
	$ybzd10 = $payinfo['mzfeeout'];
	$ybzd11 = $payinfo['mzpayfirst'];
	$ybzd12 = $payinfo['mzselfpay2'];
	$ybzd13 = $payinfo['mzbigpay'];
	$ybzd14 = $payinfo['mzbigselfpay'];
	$ybzd15 = $payinfo['mzoutofbig'];
	$ybzd16 = $payinfo['bcpay'];
	$ybzd17 = $payinfo['jcbz'];
	//分类汇总信息
	$medicatalog = $doc->output->medicatalog;
	$medicatalog = (array)$medicatalog;
	$ybzd18 = $medicatalog['medicine'];
	$ybzd19 = $medicatalog['tmedicine'];
	$ybzd20 = $medicatalog['therb'];
	$ybzd21 = $medicatalog['examine'];
	$ybzd22 = $medicatalog['ct'];
	$ybzd23 = $medicatalog['mri'];
	$ybzd24 = $medicatalog['ultrasonic'];
	$ybzd25 = $medicatalog['oxygen'];
	$ybzd26 = $medicatalog['operation'];
	$ybzd27 = $medicatalog['treatment'];
	$ybzd28 = $medicatalog['xray'];
	$ybzd29 = $medicatalog['labexam'];
	$ybzd30 = $medicatalog['bloodt'];
	$ybzd31 = $medicatalog['orthodontics'];
	$ybzd32 = $medicatalog['prosthesis'];
	$ybzd33 = $medicatalog['forensic_expertise'];
	$ybzd34 = $medicatalog['material'];
	$ybzd35 = $medicatalog['other'];
	//新单据分类汇总信息
	$medicatalog2 = $doc->output->medicatalog2;
	$medicatalog2 = (array)$medicatalog2;
	$ybzd36 = $medicatalog2['diagnosis'];
	$ybzd37 = $medicatalog2['examine'];
	$ybzd38 = $medicatalog2['labexam'];
	$ybzd39 = $medicatalog2['treatment'];
	$ybzd40 = $medicatalog2['operation'];
	$ybzd41 = $medicatalog2['material'];
	$ybzd42 = $medicatalog2['medicine'];
	$ybzd43 = $medicatalog2['therb'];
	$ybzd44 = $medicatalog2['tmedicine'];
	$ybzd45 = $medicatalog2['medicalservice'];
	$ybzd46 = $medicatalog2['commonservice'];
	$ybzd47 = $medicatalog2['registfee'];
	$ybzd48 = $medicatalog2['otheropfee'];
	//新单据分类汇总信息
	$version = (array)$doc;
	$version = $version['@attributes']['version'];
	$ybzd49  = $version;
	//新单据分类汇总信息
	$success = $doc->state;
	$success = (array)$success;
	$success = $success['@attributes']['success'];
	$ybzd50  = $success;
	$rel_s['ybzd1'] = $ybzd01;
	$rel_s['ybzd2'] = $ybzd02;
	$rel_s['ybzd3'] = $ybzd03;
	$rel_s['ybzd4'] = $ybzd04;
	$rel_s['ybzd5'] = $ybzd05;
	$rel_s['ybzd6'] = $ybzd06;
	$rel_s['ybzd7'] = $ybzd07;
	$rel_s['ybzd8'] = $ybzd08;
	$rel_s['ybzd9'] = $ybzd09;
	$rel_s['ybzd10'] = $ybzd10;
	$rel_s['ybzd11'] = $ybzd11;
	$rel_s['ybzd12'] = $ybzd12;
	$rel_s['ybzd13'] = $ybzd13;
	$rel_s['ybzd14'] = $ybzd14;
	$rel_s['ybzd15'] = $ybzd15;
	$rel_s['ybzd16'] = $ybzd16;
	$rel_s['ybzd17'] = $ybzd17;
	$rel_s['ybzd18'] = $ybzd18;
	$rel_s['ybzd19'] = $ybzd19;
	$rel_s['ybzd20'] = $ybzd20;
	$rel_s['ybzd21'] = $ybzd21;
	$rel_s['ybzd22'] = $ybzd22;
	$rel_s['ybzd23'] = $ybzd23;
	$rel_s['ybzd24'] = $ybzd24;
	$rel_s['ybzd25'] = $ybzd25;
	$rel_s['ybzd26'] = $ybzd26;
	$rel_s['ybzd27'] = $ybzd27;
	$rel_s['ybzd28'] = $ybzd28;
	$rel_s['ybzd29'] = $ybzd29;
	$rel_s['ybzd30'] = $ybzd30;
	$rel_s['ybzd31'] = $ybzd31;
	$rel_s['ybzd32'] = $ybzd32;
	$rel_s['ybzd33'] = $ybzd33;
	$rel_s['ybzd34'] = $ybzd34;
	$rel_s['ybzd35'] = $ybzd35;
	$rel_s['ybzd36'] = $ybzd36;
	$rel_s['ybzd37'] = $ybzd37;
	$rel_s['ybzd38'] = $ybzd38;
	$rel_s['ybzd39'] = $ybzd39;
	$rel_s['ybzd40'] = $ybzd40;
	$rel_s['ybzd41'] = $ybzd41;
	$rel_s['ybzd42'] = $ybzd42;
	$rel_s['ybzd43'] = $ybzd43;
	$rel_s['ybzd44'] = $ybzd44;
	$rel_s['ybzd45'] = $ybzd45;
	$rel_s['ybzd46'] = $ybzd46;
	$rel_s['ybzd47'] = $ybzd47;
	$rel_s['ybzd48'] = $ybzd48;
	$rel_s['ybzd49'] = $ybzd49;
	$rel_s['ybzd50'] = $ybzd50;
	if($state["@attributes"]['success']==="true"){
		$rel_s['result'] = 0;
		$this->ajaxReturn($rel_s,"JSON");
	}
}
public function witeJyRecordToDataBase(){
	$data['zzj_id'] = I("post.zzj_id");
	$data['card_type']=I("post.card_type");
	$data['business_type']=I("post.business_type");
	$data['pat_card_no']=I("post.pat_card_no");
	$data['id_card_no']=I("post.id_card_no");
	$data['pat_id']=I("post.pat_id");
	$data['pat_name']=I("post.pat_name");
	$data['pat_sex']=I("post.pat_sex");
	$data['charge_total']=I("post.charge_total");
	$data['cash']=I("post.cash");
	$data['zhzf']=I("post.zhzf");
	$data['tczf']=I("post.tczf");
	$data['trading_state']=I("post.trading_state");
	$data['his_state']=I("post.his_state");
	$data['trade_no']=I("post.trade_no");
	$data['stream_no'] = I("post.stream_no");
	$data['pay_type'] = I("post.pay_type");
	$data['control_time'] = date("Y-m-d H:i:s");
	M("auto_log")->add($data);
	// echo M("auto_log")->getLastSql();
	$this->writeLog(iconv("utf8","gb2312","交易记录入库信息：").var_export($data,true)); 
	$this->writeLog(iconv("utf8","gb2312","交易记录入库错误信息：").mysql_error()); 
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
	$data['op_date'] = date("Y-m-d h:i:s");
	M("bank_log")->add($data);
	$this->writeLog(iconv("utf8","gb2312","交易记录入库信息：").var_export($data,true)); 
	$this->writeLog(iconv("utf8","gb2312","交易记录入库错误信息：").mysql_error()); 
}
//支付宝获取二维码方法
public function getAliPayCode(){
	$wsdl = "http://127.0.0.1/soap/Service.php?wsdl";
	$soap = new \SoapClient($wsdl);   
	$out_trade_no = trim(I("post.out_trade_no"));
	$total_amount =trim(I("post.total_amount"));
	$subject = trim(I("post.subject"));
	$source="hos188";
	$row = $soap->getPayUrl($out_trade_no,$total_amount,$subject,$source);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;
	$this->ajaxReturn($rel,"JSON");
}
//微信获取二维码
function getWeiXinPayCode(){
	$soap = new \SoapClient('http://127.0.0.1/soap/Service.php?wsdl');
	$out_trade_no = trim(I("post.out_trade_no"));
	$total_amount = trim(I("post.total_amount"));
	$subject = trim(I("post.subject"));
	$source = "hos008";
	//echo $out_trade_no."#".$total_amount."#".$subject;
	$row = $soap->getWeiXinPayUrl($out_trade_no,$total_amount,$subject,$source);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;
	$this->ajaxReturn($rel,"JSON");
}
//获取微信支付状态
public function ajaxWxGetPayStatus(){
	$soap = new \SoapClient('http://127.0.0.1/soap/Service.php?wsdl');   
	$out_trade_no = trim(I("post.out_trade_no"));
	$source="hos008";
	$row = $soap->getWxPayStatus($out_trade_no);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;
	$this->ajaxReturn($rel,"JSON");
}

//日志记录方法
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
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
}




}