<?php
namespace ZiZhu\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
class YuYueController extends CommonController {
public function index()
{
	
	$zzj_id = I("get.zzj_id");
	//var_dump($zzj_id);
    $this->assign("zzj_id",$zzj_id);
   	$this->display();
}

function checkPhone(){ 
      
    $str =I("post.pat_phone");
    preg_match_all("/^1[34578]\d{9}$/", $str, $mobiles);
    if($mobiles[0][0]==null){
    	//号不对
    	$rel['code']="1";
    }else{
    	$rel['code']="2";
    }
   // var_dump($rel);
   
    $this->ajaxReturn($rel,"JSON");
 
    }
function CheckPatientCall(){
	//传参  患者id   1：查询/2：改写    电话
	//    返参1：有电话     0：没电话
	$pat_id=I("post.patient_id");
	$pat_phone=I("post.pat_phone");//13718156105
	$type=I("post.type");
	if($type=="check"){
		$rel =  M()->db(1,"DB_CONFIG1")->query("EXEC SM_CheckPatientCall '".$pat_id."','1',''");
	}else if($type=="update"){
		$rel =  M()->db(1,"DB_CONFIG1")->query("EXEC SM_CheckPatientCall '".$pat_id."','2','".$pat_phone."'");
	}
	$this->ajaxReturn($rel,"JSON");
}
/**********挂号患者信息查询*******************************/
function getGhPatInfo(){
	$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
	//var_dump($soap);
	$kaid = I("post.kaid");//"14220119491221553X";
	$zzj_id = I("post.zzj_id");
	$date = I("post.date");
	$business_type = I("post.business_type");
	if(strpos($kaid,"H")!==false){
		$hao_ming="07";
	}
	else if(strlen($kaid)>=18){
		$hao_ming="06";
	}else if(strlen($kaid)==12){
		$hao_ming="08";
	}
	/*$kaid = "14220119491221553X";
	$hao_ming="06";
	$zzj_id =ZZJ01;*/
	$s = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root><commitdata><data><datarow hao_ming =\"".$hao_ming."\" code_value =\"".$kaid."\" patient_name=\"\" business_type =\"".$business_type."\" /></data></commitdata><returndata/><operateinfo><info method=\"YYT_QRY_PATI\" opt_id=\"".zzj_id."\" opt_name=\"".$zzj_id."\" opt_ip=\"".get_client_ip()."\" opt_date=\"".$date."\" guid=\"{".generate_code(10)."}\" token=\"AUTO-YYRMYY-".date("Y-m-d")."\"  /></operateinfo><result><info /></result></root>";
	$data['card_code'] = '21';
	$data['card_no'] = $kaid;
	$data['op_name'] = "获取挂号就诊卡患者信息";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $s;
	$data['op_code'] = I("post.op_code");
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取挂号自费患者身份信息，发送报文信息：".$s));
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_QRY_PATI';
	$params->requestData = $s;
	
	$row = $soap->FounderRequestData($params);
	
	
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	$this->writeLog(utf8_to_gbk("获取挂号自费患者身份信息，返回报文信息：".$result));
	$data2['card_code'] = '21';
	$data2['card_no'] = $kaid;
	$data2['op_xml'] = $result;
	$data2['op_name'] = "获取预约科室";
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
			$gh_flag=2;
			$getCzRoom = $this->getCzRoom($datarow_ary['patient_id'],$datarow_ary['card_code'],$datarow_ary['card_no'],$zzj_id,$gh_flag,$date);
			$rel['room'] = $getCzRoom;
		}
	}
	//echo "<pre>";
	//var_dump($rel);
	$this->ajaxReturn($rel,"JSON");
}

/**********出诊科室查询*******************************/
function getCzRoom($patient_id,$card_code,$card_no,$zzj_id,$gh_flag,$date){
	//$gh_flag=1;
	//$zzj_id=ZZJ01;
	$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
	$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow  start_date="'.$date.'" end_date="'.$date.'" gh_flag="'.$gh_flag.'" class_code="%" /></data></commitdata><returndata/><operateinfo><info method=" YYT_QRY_CLINIC_DEPT " opt_id="'.$zzj_id.'" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.$date.'" guid="\{'.generate_code(10).'}\" token="AUTO-YYRMYY-'.date("Y-m-d").'"  /></operateinfo><result><info /></result></root>';
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "获取出诊科室";
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
	// $row = $soap->HISImpl___FounderRequestData($params);

		$row = $soap->FounderRequestData($params);
	
	$result = $row->responseData;
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "获取出诊科室";
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
//获取患者信息
public function getPatInfo(){
	//实例化websever  
	$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
	//var_dump($soap);
	//前台传递过来的卡号
	$kaid = I("post.kaid");
	//前台传递过来的自助机ID
	$zzj_id = I("post.zzj_id");
	//前台传递过来的业务类型 自助挂号，自助缴费，预约挂号
	$business_type = I("post.business_type");
	//第一步   带H的  就是就诊卡获取患者信息
	if(strpos($kaid,"H")!==false){
		$hao_ming="07";
	}
	/*******************修改判断用户的卡类型，06身份证，07就诊卡，08用户自己输入的id号******************************/
	else if(strlen($kaid)>=18){
	// 卡长度大于或者等于18  的代表身份证
		$hao_ming="06";
	}else if(strlen($kaid)==12){
	//  如果长度大于=12    就代表是社保卡
		$hao_ming="08";
	}
	// 组织报文
	$s = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root><commitdata><data><datarow hao_ming =\"".$hao_ming."\" code_value =\"".$kaid."\" patient_name=\"\" business_type =\"".$business_type."\" /></data></commitdata><returndata/><operateinfo><info method=\"YYT_QRY_PATI\" opt_id=\"".zzj_id."\" opt_name=\"".$zzj_id."\" opt_ip=\"".get_client_ip()."\" opt_date=\"".date("Y-m-d")."\" guid=\"{".generate_code(10)."}\" token=\"AUTO-YYRMYY-".date("Y-m-d")."\"  /></operateinfo><result><info /></result></root>";
	//定义一个变量类型 
	$data['card_code'] = '21';
	$data['card_no'] = $kaid;
	$data['op_name'] = "获取就诊卡患者信息";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $s;
	$data['op_code'] = I("post.op_code");
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取自费患者身份信息，发送报文信息：".$s));
	//这个是websever 帐户名 和密码
	$params->userName = '10002';
	$params->password = '12345';
	//
	$params->businessType = 'YYT_QRY_PATI';
	$params->requestData = $s;
	$row = $soap->FounderRequestData($params);
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	$this->writeLog(utf8_to_gbk("获取自费患者身份信息，返回报文信息：".$result));
	$data2['card_code'] = '21';
	$data2['card_no'] = $kaid;
	$data2['op_xml'] = $result;
	$data2['op_name'] = "获取就诊卡患者信息";
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
	}
	//var_dump($rel);
	$this->writeLog(utf8_to_gbk("获取自费患者就诊记录，返回报文信息转数组：".var_export($rel,true)));
	$this->ajaxReturn($rel,"JSON");

} 

//获取患者预约信息 
public function patReservationRecord(){
	//需要三个参数 病人id  卡类型 卡号 21代表就诊卡 20 代表社保卡
	$patient_id = I("post.patient_id");
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
    $zzj_id = I("post.zzj_id");
    $op_code = I("post.op_code");
    $soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
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
	$params->businessType = 'YYT_QRY_APP';
	$params->requestData = $s;

		$row = $soap->FounderRequestData($params);
	
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
	$datarow=  $doc->returndata->data->datarow;

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
//获取诊间加号记录
public function getPatReservationRecord(){
	//需要三个参数 病人id  卡类型 卡号 21代表就诊卡 20 代表社保卡
	$patient_id = I("post.patient_id");
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
    $zzj_id = I("post.zzj_id");
    $op_code = I("post.op_code");
   // var_dump($patient_id);
    $soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
    $s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow  patient_id="'.$patient_id.'" card_code="'.$card_code.'" card_no="'.$card_no.'" /></data></commitdata><returndata/><operateinfo><info method="YYT_QRY_DOCTOR_ADD" opt_id="'.$zzj_id.'" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="2014-06-06" guid="'.$op_code.'" token="AUTO-YYRMYY-'.date("Y-m-d").'" /></operateinfo><result><info /></result></root>';
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

		$row = $soap->FounderRequestData($params);
	
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	$data2['card_code'] = $card_code;
	$data2['op_xml'] = $result;
	$data2['op_name'] = "获取诊间补挂号费返回报文";
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
	$datarow=  $doc->returndata->data->datarow;
    
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
	$record_sn = I("post.record_sn");    //排班序号
	$patient_id =I("post.patient_id");   //病人ID
	$card_code = I("post.card_code");    //卡类型
	$card_no = I("post.card_no");        //卡号
	$req_type = I("post.req_type");     //号类
	//var_dump($req_type);
	$zzj_id = I("post.zzj_id");
	$op_code = I("post.op_code");
	$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
	$s='<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow record_sn="'.$record_sn.'" patient_id="'.$patient_id.'" card_code="'.$card_code.'" card_no="'.$card_no.'" req_type="'.$req_type.'"  gh_flag="2" /></data></commitdata><returndata/><operateinfo><info method="YYT_GH_CALC" opt_id="'.$zzj_id.'" opt_name="" opt_ip="'.get_client_ip().'" opt_date="'.date("Y-m-d").'" guid="'.generate_code(10).'" token="AUTO-YYRMYY-'.date("Y-m-d").'"/></operateinfo><result><info /></result></root>';
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

		$row = $soap->FounderRequestData($params);
	
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

//取号划价
	public function quhao_huajia(){
	$record_sn = I("post.record_sn");
	$patient_id =I("post.patient_id");
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
	$req_type = I("post.req_type");
	$zzj_id = I("post.zzj_id");
	$op_code = I("post.op_code");
	$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
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

		$row = $soap->FounderRequestData($params);
	
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


	//挂号调用医保Dll获取患者信息后，通过ajax传入这里获取出诊科室列表 
function YbXmlParseGhao(){
	$date=I("post.date");
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
		$data['op_name'] = "调用医保DLL获取患者医保信息";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
		/******************获取出诊科室*********************/
		$gh_flag=2;
		$getCzRoom = $this->getCzRoom($datarow_ary['patient_id'],$datarow_ary['card_code'],$datarow_ary['card_no'],$zzj_id,$gh_flag,$date);
		$rel['room'] = $getCzRoom;
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
/**********预约挂号His费用确认*******************************/
function yyt_gh_save(){
	$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
	//$record_sn  = "165140";//
	$social_no=I("post.social_no");   //身份证号
	$mobile=I("post.mobile");         //手机号
	$total_fee=I("post.total_fee");  //总金额 ？
	$record_sn=I("post.record_sn");  //排班序号
	$patient_id = I("post.patient_id");  //病人id
	$card_code = I("post.card_code");  // 卡类型
	$zzj_id = I("post.zzj_id");
	$card_no = I("post.card_no");	 //卡号
	$charge_total = I("post.charge_total");	    //总金额
	//$req_type="01";//
	$req_type=I("post.req_type");
	$zzj_id = I("post.zzj_id");
	$source= "hos122";
	$name=I("post.name");
	$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow record_sn="'.$record_sn.'"    patient_id="'.$patient_id.'" card_code="'.$card_code.'" card_no="'.$card_no.'" total_fee="'.$total_fee.'"   gh_sequence="0"  name="'.$name.'"  social_no="'.$social_no.'" mobile="'.$mobile.'" req_type="'.$req_type.'"  /></data></commitdata><returndata/><operateinfo><info method="YYT_APP_REG" opt_id="'.$zzj_id.'" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.date("Y-m-d").'" token="AUTO-YYRMYY-'.date("Y-m-d").'"  /></operateinfo><result><info /></result></root>
';
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "挂号就诊卡HIS费用确认";
	$data['direction'] = "发送报文";
	$data['op_xml'] = $s;
	$data['op_code'] = I("post.op_code");
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("自费挂号HIS保存回写发送报文：".$xml_input));
	/************日志记录结束**************/
	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_APP_REG';
	$params->requestData = $s;

	$row = $soap->FounderRequestData($params);
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "挂号就诊卡HIS费用确认";
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

	
	
	if($result_return['execute_flag']==0){
		$data = $doc->returndata->data->datarow;
		$datarow = array();
		foreach ($data->attributes() as $a => $b) {
			$b = (array) $b;
			$datarow[$a] = $b[0];
		}

		$rel['datarow'] = $datarow;

		$rel["message"]=$result_return['execute_message'];
		$rel['execute_flag']=$result_return['execute_flag'];
		
	}else{
		$rel["message"]=$result_return['execute_message'];
		$rel['execute_flag']=$result_return['execute_flag'];
	}
	
	
	$this->ajaxReturn($rel,"JSON");

}

//查询排班信息
function getSchedInfo(){
	$unit_sn = I("post.unit_sn");   //科室代码
	$zzj_id = I("post.zzj_id");
	$date1=I("post.date");
	//$unit_sn ="0103010" ;
	//$zzj_id =ZZJ01;
	$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
	$s='<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow unit_sn="'.$unit_sn.'" doctor_sn="" group_sn="" doctor_py="" clinic_type="" start_date="'.$date1.'" end_date="'.$date1.'" gh_flag="2"/></data></commitdata><returndata/><operateinfo><info method="YYT_QRY_REQUEST" opt_id="700000" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.$date1.'" guid="'.generate_code(10).'" token="AUTO-YYRMYY-'.date("Y-m-d").'" /></operateinfo><result><info />
</result></root>';

	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "获取出诊医生";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $s;
	M("logs")->add($data);
	$this->writeLog(utf8_to_gbk("获取出出诊医生".$pat_code."出诊医生传入报文数据：").$s,"INFO");

	$params->userName = '10002';
	$params->password = '12345';
	$params->businessType = 'YYT_QRY_REQUEST';
	$params->requestData = $s;

		$row = $soap->FounderRequestData($params);
	
	$result = $row->responseData;
/*	$result ='<?xml version="1.0" encoding="utf8"?>
<root><commitdata><data><datarow unit_sn="0101010" doctor_sn="" group_sn="" doctor_py="" clinic_type="" start_date="2018-04-21" end_date="2018-04-21" gh_flag="2"/></data></commitdata><returndata><data><datarow count="0" record_sn="233168" request_date="2018-04-21T00:00:00" ampm="a" unit_sn="0101010" unit_name="呼吸内科门诊" doctor_sn="     " doctor_name="" clinic_type="08" clinic_name="专科" req_type="04" req_name="" sum_fee="50.00" record_left="10" show_type_name="门诊号" emp_title="" open_flag="1"/></data></returndata><operateinfo><info method="YYT_QRY_REQUEST" opt_id="700000" opt_name="ZZ029" opt_ip="172.168.61.129" opt_date="2018-04-21" guid="3350456262" token="AUTO-YYRMYY-2018-04-19"/></operateinfo><result><info execute_flag="0" execute_message="执行成功" account="2018-04-19 10:46:56"/>
    </result></root>';*/
	$result = str_replace("gb2312","utf8",$result);
	/**************日记记录开始***********/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "获取出诊医生";
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

		//var_dump($datarow_attr_ary);exit;
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

//自助挂号
public function yyt_qh_save(){
	$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
	$record_sn  = I("post.record_sn");   //排班标识
	$patient_id = I("post.patient_id");  //病人id
	$card_code = I("post.card_code");    //卡类型
	//$zzj_id = "ZZ001";//I("post.zzj_id");
	$card_no = I("post.card_no");       //卡号
	$responce_type = I("post.responce_type");   //结算身份
	$charge_total = I("post.charge_total");    //总金额
	$cash = I("post.pay_charge_total");
	$zhzf = I("post.zhzf");            //账户支付
	$tczf = I("post.tczf");           //统筹支付
	$pay_seq = I("post.pay_seq");    //划价流水号
	$trade_no = I("post.trade_no");   //银行交易流水号
	$stream_no = I("post.stream_no");  //自助机流水号
	$pay_type = I("post.pay_type");
	$zzj_id = I("post.zzj_id");
	//var_dump(I("post.zzj_id"));
	$bk_card_no=  I("post.bk_card_no");   //银行卡号
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
	$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow record_sn="'.$record_sn.'"  pay_seq="'.$pay_seq.'" responce_type="'.$responce_type.'" patient_id="'.$patient_id.'" card_code="'.$card_code.'" card_no="'.$card_no.'" charge_total="'.$charge_total.'" cash="'.$cash.'" zhzf="'.$zhzf.'" tczf="'.$tczf.'"  record_id="" gh_sequence="" bk_card_no="'.$bk_card_no.'" trade_no="'.$trade_no.'" stream_no="'.$stream_no.'" addition_no1="" trade_time="'.date("Y-m-d").'" cheque_type="'.$cheque_type.'" gh_flag="3" bank_type=""/></data></commitdata><returndata/><operateinfo><info method="YYT_GH_SAVE" opt_id="'.$zzj_id.'" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.date("Y-m-d").'" guid="'.$stream_no.'" token="AUTO-YYRMYY-'.date("Y-m-d").'"  /></operateinfo><result><info /></result></root>
';

	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "挂号就诊卡HIS费用确认";
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

		$row = $soap->FounderRequestData($params);
	
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "挂号就诊卡HIS费用确认";
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
				$a=M("bank_log")->db(2,"DB_CONFIG3")->where("out_trade_no='{$stream_no}'")->find();
				$rel['bank']=$a;
				break;
		   		 }
			}

			/*//退款代码
			switch($pay_type)
			{
				case "alipay"://支付宝退费
				$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');
				$row = $soap->refund($trade_no,$cash,$stream_no,$source);
				$row = str_replace("gb2312", "utf8", $row);
				$xml = simplexml_load_string($row);
				$xml = (array)$xml;
				$rel['pay_result'] = (array)$xml['Message'];
				break;
		    }*/
		     $rel['pay_result']["code"]=$rel['result']['execute_flag'];
		     $rel["pay_result"]["SubMsg"]="交款失败,退款成功,请重新交易";


	   }
	}
	//$rel['result']['execute_flag']==-1;
	$rel['datarow'] = $datarow_ary;
	$this->ajaxReturn($rel,"JSON");

}


//自助挂号
public function yyt_qh_save1(){
	$soap = new \SoapClient('http://172.168.3.162:8088/chisWebServics?service=HISImpl');
	$record_sn  = I("post.record_sn");
	$patient_id = I("post.patient_id");
	$card_code = I("post.card_code");
	//$zzj_id = "ZZ001";//I("post.zzj_id");
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
	$req_type=I("post.req_type");
	$zzj_id = I("post.zzj_id");
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
	$s = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow record_sn="'.$record_sn.'"  pay_seq="'.$pay_seq.'" responce_type="'.$responce_type.'" patient_id="'.$patient_id.'" card_code="'.$card_code.'" card_no="'.$card_no.'" charge_total="'.$charge_total.'" cash="'.$cash.'" zhzf="'.$zhzf.'" tczf="'.$tczf.'"  record_id="" gh_sequence="" bk_card_no="'.$bk_card_no.'" trade_no="'.$trade_no.'" stream_no="'.$stream_no.'" addition_no1="" trade_time="'.date("Y-m-d").'" req_type="'.$req_type.'" cheque_type="'.$cheque_type.'" gh_flag="2" bank_type=""/></data></commitdata><returndata/><operateinfo><info method="YYT_GH_SAVE" opt_id="'.$zzj_id.'" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.date("Y-m-d").'" guid="'.$stream_no.'" token="AUTO-YYRMYY-'.date("Y-m-d").'"  /></operateinfo><result><info /></result></root>
';

	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "挂号就诊卡HIS费用确认";
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

		$row = $soap->FounderRequestData($params);
	
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	/**************日记记录开始***********/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "挂号就诊卡HIS费用确认";
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
				$a=M("bank_log")->db(2,"DB_CONFIG3")->where("out_trade_no='{$stream_no}'")->find();
				$rel['bank']=$a;
				break;
		   		 }
			}
		
		     $rel['pay_result']["code"]=$rel['result']['execute_flag'];
		     $rel["pay_result"]["SubMsg"]="交款失败,退款成功,请重新交易";


	   }
	}
	
	$rel['datarow'] = $datarow_ary;
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
	$result_datarow= $doc->commitdata->data->datarow;
	//var_dump($result_datarow);exit;
	$result_data =array();
	foreach ($result_datarow->attributes() as $a => $b) {
		$b=(array)$b;
		$result_data[$a] = $b[0];
	}
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
					$trade_no=$rel['datarow']['trade_no'];
					$stream_no=$result_data['stream_no'];
					$cash=$result_data['cash'];
					$trade_no=$result_data['trade_no'];
					$source="hos122";
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
				$stream_no=$result_data['stream_no'];
				$a=M("bank_log")->db(2,"DB_CONFIG3")->where("out_trade_no='{$stream_no}'")->find();
				$rel['bank']=$a;
				break;
		   		 }
			}

			/*switch($pay_type)
			{
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
		   }*/
		    $rel['pay_result']["code"]=$rel['result']['execute_flag'];
		    $rel["pay_result"]["SubMsg"]="交款失败,退款成功,请重新交易";
	    }
		
		}
	//$this->writeLog(utf8_to_gbk("医保费用确认返回XML转 数组：".var_export($rel,true)));
	//iconv("utf8","gb2312",htmlspecialchars_decode(I("post.input_xml")))
	$this->writeLog(iconv("utf8","gbk","医保费用确认返回XML转数组：".var_export($rel,true)));
	$this->ajaxReturn($rel,"JSON");
}




//挂号费用确认解析

function YbSfConfirmXmlParseGhao(){
	$xml =iconv("utf8","gb2312",htmlspecialchars_decode(I("post.input_xml")));
	/*$xml = '<?xml version="1.0" encoding="utf8"?>
<root><commitdata><data><datarow record_sn="110103" pay_seq="122529_5" responce_type="93" patient_id="141107022600" card_code="20" card_no="101382872018" charge_total="7" cash="0.01" zhzf="0" tczf="2" record_id="" bk_card_no="noo***@gmail.com" trade_no="2017062921001004380212389832" stream_no="ZZ001000094444700201706291519" addition_no1="" trade_time="2017-03-16" cheque_type="c" gh_flag="1" bank_type=""/></data></commitdata><returndata/><operateinfo><info method="YYT_YB_GH_SAVE" opt_id="ZZ002" opt_name="ZZ002" opt_ip="800000001" opt_date="2017-03-16" guid="{1489627003}" token="AUTO-YYRMYY-2017-03-16"/></operateinfo><result><info execute_flag="-1" execute_message="门诊挂号收费失败,挂号交费失败,更新gh_schedule 失败!" account="2017-03-16 09:16:45"/></result></root>';*/
/*$xml='<?xml version="1.0" encoding="gb2312"?>
<root><commitdata><data><datarow record_sn="114723" pay_seq="114723_22" responce_type="93" patient_id="160114031200" card_code="20" card_no="108121293001" charge_total="50" cash="10" zhzf="0" tczf="40" record_id="" bk_card_no="152****9753" trade_no="2018032121001004050549806278" stream_no="ZZ001160114031200201803215140" addition_no1="" trade_time="2018-03-21" cheque_type="c" gh_flag="2" bank_type=""/></data></commitdata><returndata/><operateinfo><info method="YYT_YB_GH_SAVE" opt_id="ZZ001" opt_name="ZZ001" opt_ip="800000001" opt_date="2018-03-21" guid="ZZ001160114031200201803215140" token="AUTO-YYRMYY-2018-03-21"/></operateinfo><result><info execute_flag="-1" execute_message="门诊挂号收费失败,挂号交费失败,cdsScheduleDetail:查询超时已过期" account="2018-03-21 16:52:25"/></result></root>';
$xml =iconv("utf8","gb2312",htmlspecialchars_decode($xml));*/
	$doc = simplexml_load_string($xml);	
	/**************日记记录开始***********/
	$data['card_code'] = '20';
	$data['op_name'] = "挂号医保费用确认";
	$data['direction'] = "返回报文";
	$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
	$data['op_code'] = I("post.op_code");
	$pay_type =I("post.pay_type");
	M("logs")->add($data);
	/******************日志记录结束*********************/
	$this->writeLog(utf8_to_gbk("医保费用确认返回XML：").$xml);
	/**返回是否成功信息
	**@execute_flag 0-成功 -1-不成功
	**@execute_message 提示信息
	**@account 操作时间
	**/
	$result_datarow= $doc->commitdata->data->datarow;
	//var_dump($result_datarow);exit;
	$result_data =array();
	foreach ($result_datarow->attributes() as $a => $b) {
		$b=(array)$b;
		$result_data[$a] = $b[0];
	}
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

			//退款代码
			if($cash!==0){
					switch($pay_type){
				case "alipay"://支付宝退费
				$a=M("alipay_logs")->db(2,"DB_CONFIG3")->where("trade_no='{$trade_no}'")->find();
				if($a){
					$trade_no=$rel['datarow']['trade_no'];
					$stream_no=$result_data['stream_no'];
					$cash=$result_data['cash'];
					$trade_no=$result_data['trade_no'];
					$source="hos122";
					//var_dump($trade_no,$cash,$stream_no,$source);exit;
					$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');
					$row = $soap->refund($trade_no,$cash,$stream_no,$source);
					$row = str_replace("gb2312", "utf8", $row);
					$xml = simplexml_load_string($row);
					$xml = (array)$xml;
					$rel['pay_result'] = (array)$xml['Message'];

				}
				
				break;



				case "zf_bank":
				$stream_no=$result_data['stream_no'];
				$a=M("bank_log")->where("out_trade_no='{$stream_no}'")->find();
				$rel['bank']=$a;
				break;
		   		 }
			}

			/*
			switch($pay_type)
			{
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
		   }*/
		    $rel['pay_result']["code"]=$rel['result']['execute_flag'];
		    $rel["pay_result"]["SubMsg"]="交款失败,退款成功,请重新交易";
	    }
		
		}
	//$this->writeLog(utf8_to_gbk("医保费用确认返回XML转 数组：".var_export($rel,true)));
	//iconv("utf8","gb2312",htmlspecialchars_decode(I("post.input_xml")))
	$this->writeLog(iconv("utf8","gbk","医保费用确认返回XML转数组：".var_export($rel,true)));
	$this->ajaxReturn($rel,"JSON");
}




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
	$rel['result']=$result_return;
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
		$data['op_name'] = "调用医保DLL获取患者医保信息";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
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


//预约取号医保卡获取患者信息
/*function YbXmlParse_11(){
	$xml = iconv("utf8","gb2312",htmlspecialchars_decode(I("post.input_xml")));
	$doc = simplexml_load_string($xml);
	$this->writeLog(iconv("utf8","gb2312","调用医保DLL获取患者医保返回信息").$xml,"INFO");

	//返回是否成功状态及相关信息
	$result_ary = $doc->result->info;
	$result_return = array();
	foreach($result_ary->attributes() as $a => $b)
	{
		$b = (array)$b;
		$result_return[$a] = $b[0];
	}
	$rel["result"] = $result_return;
	if($result_return['execute_flag']==0){
		
		$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		
		$data['card_code'] = '20';
		$data['patient_id'] = $datarow_ary['patient_id'];
		$data['card_no'] =  $datarow_ary['card_no'];
		$data['op_name'] = "调用医保DLL获取患者医保信息";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		
		$rel['yb_input_data'] = $datarow_ary;
		}else if($result_return['execute_flag']==-1){
			$datarow =  $doc->returndata->data->datarow;
		$datarow_ary = array();
		foreach($datarow->attributes() as $a => $b)
		{
			$b = (array)$b;
			$datarow_ary[$a] = $b[0];
		}
		
		$data['card_code'] = '20';
		$data['patient_id'] = $datarow_ary['patient_id'];
		$data['card_no'] =  $datarow_ary['card_no'];
		$data['op_name'] = "调用医保DLL获取患者医保信息";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		
		$rel['yb_input_data'] = $datarow_ary;
		}
	$this->ajaxReturn($rel,"JSON");
	
}*/
function YbHalcParseGhao(){
	$xml =iconv("utf8","gb2312",htmlspecialchars_decode(I("post.input_xml")));
	
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
		$data['op_name'] = "医保划价";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
	}else{
		/**************日记记录开始***********/
		$data['card_code'] = '20';
		$data['op_name'] = "医保划价";
		$data['direction'] = "返回报文";
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
	}
	
	//$this->writeLog(utf8_to_gbk("医保划价返回XML转数组:".var_export($rel,true))); 
	$this->ajaxReturn($rel,"JSON");
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
	$this->writeLog(iconv("utf8","gb2312","交易记录入库信息：").var_export($data,true)); 
	$this->writeLog(iconv("utf8","gb2312","交易记录入库错误信息：").mysql_error()); 
}
//支付宝获取二维码方法
public function getAliPayCode(){
	$wsdl = "http://172.168.0.89/soap1/Service.php?wsdl";
	$soap = new \SoapClient($wsdl);   
	$out_trade_no = trim(I("post.out_trade_no"));
	$total_amount =trim(I("post.total_amount"));
	$subject = trim(I("post.subject"));
	$source="hos122";
	$row = $soap->getPayUrl($out_trade_no,$total_amount,$subject,$source);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;
	$this->ajaxReturn($rel,"JSON");
}
//查询订单状态接口方法
public function ajaxGetPayStatus(){
	$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');   
	$out_trade_no = trim(I("post.out_trade_no"));
	$source="hos122";
	$row = $soap->getPayStatus($out_trade_no,$source);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;
	// $rel = '1';
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
	M("logs")->add($data);
}

function getTwoWeek(){
	$start=date("Y-m-d",strtotime("+1 day"));
	$nowTime=strtotime("+15 day");
	$end=date("Y-m-d",$nowTime);
	for($i=0;strtotime($start.'+'.$i.' days')<=strtotime($end)&&$i<365;$i++){
	    $time = strtotime($start.'+'.$i.' days');
	    $data_array[] = date('Y-m-d',$time);
	    $rel=$data_array;
	}
	//var_dump($data_array);
	$this->ajaxReturn($rel,"JSON");
}


}