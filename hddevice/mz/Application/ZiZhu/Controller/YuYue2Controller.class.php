<?php
namespace ZiZhu\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
class YuYue2Controller extends CommonController {
public function index()
{
	
	$zzj_id = I("get.zzj_id");
	//var_dump($zzj_id);
    $this->assign("zzj_id",$zzj_id);
   	$this->display();
}
/**********挂号患者信息查询*******************************/







function getSchedInfo1(){
/*	$unit_sn = I("post.unit_sn");
	$zzj_id = I("post.zzj_id");
	$date1=I("post.date");
	//$unit_sn ="0103010" ;
	//$zzj_id =ZZJ01;
	$soap = new \SoapClient('http://172.168.0.228:8088/chisWebServics?service=HISImpl');
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

		$row = $soap->HISImpl___FounderRequestData($params);
	
	$result = $row->responseData;*/
	$result ='<?xml version="1.0" encoding="utf8"?>
<root><commitdata><data><datarow unit_sn="0208010" doctor_sn="" group_sn="" doctor_py="" clinic_type="" start_date="2018-04-21" end_date="2018-04-21" gh_flag="2"/></data></commitdata><returndata><data><datarow count="0" record_sn="247119" request_date="2018-04-21T00:00:00" ampm="a" unit_sn="0208010" unit_name="泌尿外科门诊" doctor_sn="01229" doctor_name="唐科伟" clinic_type="02" clinic_name="副主任" req_type="04" req_name="" sum_fee="60.00" record_left="5" show_type_name="门诊号" emp_title="副主任医师" open_flag="1"/><datarow count="0" record_sn="233190" request_date="2018-04-21T00:00:00" ampm="p" unit_sn="0208010" unit_name="泌尿外科门诊" doctor_sn="     " doctor_name="" clinic_type="08" clinic_name="专科" req_type="04" req_name="" sum_fee="50.00" record_left="20" show_type_name="门诊号" emp_title="" open_flag="1"/></data></returndata><operateinfo><info method="YYT_QRY_REQUEST" opt_id="700000" opt_name="ZZ029" opt_ip="172.168.61.129" opt_date="2018-04-21" guid="3258005643" token="AUTO-YYRMYY-2018-04-20"/></operateinfo><result><info execute_flag="0" execute_message="执行成功" account="2018-04-20 15:36:49"/>
    </result></root>';
	$result = str_replace("gb2312","utf8",$result);

	$doc = simplexml_load_string($result);

	//var_dump($doc);
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

		/*var_dump($datarow_attr_ary);
		var_dump(count($datarow_attr_ary));*/
		//var_dump($datarow_attr_ary.length);exit;
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

		/*var_dump($arr1);
		var_dump($arr2);exit;*/

		if($arr1 !==""   && $arr2 ==""){
			$datarow = $arr1;
		}else if($arr2 !==""   && $arr1 ==""){
			$datarow = $arr2;
		}else {
			$datarow=array_merge($arr1,$arr2);
		}
		var_dump($datarow);exit;
		

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























function getGhPatInfo(){
	//$soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
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
	$hao_ming="06";*/
	//$zzj_id =ZZJ01;
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
	/*$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);*/
	$result="<?xml version='1.0' encoding='utf8'?><root><commitdata><data><datarow hao_ming='07' code_value='H186701158' patient_name='' business_type='预约挂号'/></data></commitdata><returndata><data><datarow patient_id='000113964700' name='杨文凭' sex='1' sex_chn='男' birthday='1995-06-13T00:00:00' social_no='130725199506130030' marry_code=' ' marry_chn='' p_bar_code='H186701158' country_code='cn' country_chn='中国' nation_code='  ' nation_chn='' birth_place='130000' home_district='' home_district_chn='130000' home_street='' home_tel='' mobile='15230929753' home_zipcode='0     ' addition_no1='' response_type='12' response_chn='普通(自费)' charge_type='' card_code='21' card_no='H186701158' build_date='2017-11-02T13:45:16' use_times='3' balance='0.00' amount='1'/></data></returndata><operateinfo><info method='YYT_QRY_PATI' opt_id='zzj_id' opt_name='ZZ001' opt_ip='172.168.61.11' opt_date='2018-04-03' guid='{7922079449}' token='AUTO-YYRMYY-2018-04-02'/></operateinfo><result><info execute_flag='0' execute_message='执行成功' account='2018-04-02 14:16:22'/></result></root>";
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
	$soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
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
	/*$row = $soap->HISImpl___FounderRequestData($params);//HISImpl___
	$result = $row->responseData;*/
	$result="<?xml version='1.0' encoding='gb2312'?><root><commitdata><data><datarow start_date='2018-04-03' end_date='2018-04-03' gh_flag='2' class_code='%'/></data></commitdata><returndata><data><datarow class_code='01' class_name='内科'><item unit_sn='0101010' unit_name='呼吸内科门诊' dept_position='' yb_limit='0'/><item unit_sn='0102010' unit_name='消化内科门诊' dept_position='' yb_limit='0'/><item unit_sn='0103010' unit_name='神经内科门诊' dept_position='' yb_limit='0'/><item unit_sn='0104010' unit_name='心血管内科门诊' dept_position='' yb_limit='0'/><item unit_sn='0105010' unit_name='肾内科门诊' dept_position='' yb_limit='0'/><item unit_sn='0107020' unit_name='内分泌科门诊' dept_position='' yb_limit='0'/><item unit_sn='0109010' unit_name='风湿免疫科门诊' dept_position='' yb_limit='0'/><item unit_sn='0110010' unit_name='肿瘤血液科门诊' dept_position='' yb_limit='0'/><item unit_sn='0113010' unit_name='老年内科门诊' dept_position='' yb_limit='0'/></datarow><datarow class_code='02' class_name='外科'><item unit_sn='0201070' unit_name='普通外科1门诊' dept_position='' yb_limit='0'/><item unit_sn='0202060' unit_name='普通外科2门诊' dept_position='' yb_limit='0'/><item unit_sn='0203010' unit_name='骨科门诊' dept_position='' yb_limit='0'/><item unit_sn='0203021' unit_name='微创脊柱门诊' dept_position='' yb_limit='0'/><item unit_sn='0204010' unit_name='神经外科门诊' dept_position='' yb_limit='0'/><item unit_sn='0206010' unit_name='胸外科门诊' dept_position='' yb_limit='0'/><item unit_sn='0208010' unit_name='泌尿外科门诊' dept_position='' yb_limit='0'/><item unit_sn='0209030' unit_name='外科门诊' dept_position='' yb_limit='0'/></datarow><datarow class_code='03' class_name='妇儿'><item unit_sn='0301010' unit_name='妇科门诊' dept_position='' yb_limit='0'/><item unit_sn='0302010' unit_name='产科门诊' dept_position='' yb_limit='0'/><item unit_sn='0401000' unit_name='儿科门诊' dept_position='' yb_limit='0'/></datarow><datarow class_code='05' class_name='中医五官'><item unit_sn='0601000' unit_name='眼科门诊' dept_position='' yb_limit='0'/><item unit_sn='0701000' unit_name='耳鼻喉科门诊' dept_position='' yb_limit='0'/><item unit_sn='0801000' unit_name='口腔门诊' dept_position='' yb_limit='0'/><item unit_sn='1001000' unit_name='中医科门诊' dept_position='' yb_limit='0'/><item unit_sn='1101000' unit_name='康复医学科门诊' dept_position='' yb_limit='0'/></datarow><datarow class_code='10' class_name='其他'><item unit_sn='1201000' unit_name='皮肤科门诊' dept_position='' yb_limit='0'/><item unit_sn='3900000' unit_name='精神保健科' dept_position='' yb_limit='0'/></datarow></data></returndata><operateinfo><info method=' YYT_QRY_CLINIC_DEPT ' opt_id='ZZ001' opt_name='ZZ001' opt_ip='172.168.61.11' opt_date='2018-04-03' guid='\{5766212538}\' token='AUTO-YYRMYY-2018-04-02'/></operateinfo><result><info execute_flag='0' execute_message='执行成功' account='2018-04-02 14:16:22'/></result></root>
";
$result = str_replace("gb2312","utf8",$result);
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
	//$result = str_replace("gb2312","utf8",$result);
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
	$soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
	//var_dump($soap);
	//前台传递过来的卡号
	$kaid = "H186701158";//I("post.kaid");
	//前台传递过来的自助机ID
	$zzj_id ="ZZJ01";// I("post.zzj_id");
	//前台传递过来的业务类型 自助挂号，自助缴费，预约挂号
	$business_type = "预约取号";I("post.business_type");


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
	//$row = $soap->HISImpl___FounderRequestData($params);
	$result="<?xml version='1.0' encoding='utf8'?><root><commitdata><data><datarow hao_ming='07' code_value='H186701158' patient_name='' business_type='预约取号'/></data></commitdata><returndata><data><datarow patient_id='000113964700' name='杨文凭' sex='1' sex_chn='男' birthday='1995-06-13T00:00:00' social_no='130725199506130030' marry_code=''  marry_chn='' p_bar_code='H186701158' country_code='cn' country_chn='中国' nation_code=' '   nation_chn='' birth_place='130000' home_district='' home_district_chn='130000' home_street='' home_tel='' mobile='15230929753' home_zipcode='0     ' addition_no1='' response_type='12' response_chn='普通(自费)' charge_type='' card_code='21' card_no='H186701158' build_date='2017-11-02T13:4516' use_times='5' balance='0.00' amount='1'/></data></returndata><operateinfo><info method='YYT_QRY_PATI' opt_id='zzj_id' opt_name='ZZJ01' opt_ip='172.168.61.136' opt_date='2018-04-08' guid='{9345821764}' token='AUTO-YYRMYY-2018-04-08'/></operateinfo><result><info execute_flag='0' execute_message='执行成功' account='2018-04-08 12:36:49'/></result></root>";
	//$result = $row->responseData;
	//$result = str_replace("gb2312","utf8",$result);
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
    $soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
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
	/*$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;*/
	$result = "<?xml version='1.0' encoding='utf8'?><root><commitdata><data><datarow patient_id='000113964700' card_code='21' card_no='H186701158'/></data></commitdata><returndata><data><datarow count='1' patient_id='000113964700' register_sn='114463' request_date='2018-03-20T00:00:00' req_type='04' req_name='' unit_sn='0101010' unit_name='呼吸内科门诊' doctor_sn='' ampm='p' clinic_type='08' clinic_name='专科' record_sn='' gh_sequence='22' record_id='' charge='50.00' register_flag='0' cur_times='0'/></data></returndata><operateinfo><info method='YYT_QRY_APP' opt_id='ZZ001' opt_name='ZZ001' opt_ip='172.168.61.11' opt_date='2014-06-06' guid='20180320131041803' token='AUTO-YYRMYY-2018-03-20'/></operateinfo><result><info execute_flag='0' execute_message='执行成功' account='2018-03-20 13:11:05'/></result></root>";
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
    $soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
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
	/*$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;*/
	$result="<?xml version='1.0' encoding='utf8'?><root><commitdata><data><datarow patient_id='000113964700' card_code='21' card_no='H186701158'/></data></commitdata><returndata><data><datarow count='1' patient_id='000113964700' register_sn='123949' request_date='2018-03-08T00:00:00' req_type='01' req_name='' unit_sn='0101010' unit_name='呼吸内科门诊' doctor_sn='80548' doctor_name='艾瑞超' ampm='p' clinic_type='08' clinic_name='专科' record_sn='' gh_sequence='-1' record_id='' charge='50' register_flag='0' cur_times='3'/></data></returndata><operateinfo><info method='YYT_QRY_DOCTOR_ADD' opt_id='' opt_name='' opt_ip='172.168.61.136' opt_date='2014-06-06' guid='2018030816513584' token='AUTO-YYRMYY-2018-03-08'/></operateinfo><result><info execute_flag='0' execute_message='执行成功' account='2018-03-08 16:49:58'/></result></root>";
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
	$record_sn = I("post.record_sn");
	$patient_id =I("post.patient_id");
	$card_code = I("post.card_code");
	$card_no = I("post.card_no");
	$req_type = I("post.req_type");
	//var_dump($req_type);
	$zzj_id = I("post.zzj_id");
	$op_code = I("post.op_code");
	$soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
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
	/*$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;*/
	$result="<?xml version='1.0' encoding='utf8'?>
<root><commitdata><data><datarow record_sn='126289' patient_id='000113964700' card_code='21' card_no='H186701158' req_type='01' gh_flag='3'/></data></commitdata><returndata><data><datarow patient_id='000113964700' charge_total='50' cash='50' zhzf='0' tczf='0' pay_seq='126289_-1'/></data></returndata><operateinfo><info method='YYT_GH_CALC' opt_id='ZZ001' opt_name='' opt_ip='172.168.61.11' opt_date='2018-03-22' guid='6246053742' token='AUTO-YYRMYY-2018-03-22'/></operateinfo><result><info execute_flag='0' execute_message='挂号划价成功!' account='2018-03-22 13:54:50'/></result></root>";
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
	$soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
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
	/*$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;*/
	$result="<?xml version='1.0' encoding='utf8'?>
<root><commitdata><data><datarow record_sn='128976' patient_id='000113964700' card_code='21' card_no='H186701158' req_type='01' gh_flag='3'/></data></commitdata><returndata><data><datarow patient_id='000113964700' charge_total='50' cash='50' zhzf='0' tczf='0' pay_seq='128976_-1'/></data></returndata><operateinfo><info method='YYT_GH_CALC' opt_id='ZZ001' opt_name='' opt_ip='172.168.61.11' opt_date='2018-04-08' guid='9940405146' token='AUTO-YYRMYY-2018-04-08'/></operateinfo><result><info execute_flag='0' execute_message='挂号划价成功!' account='2018-04-08 09:05:47'/></result></root>
";
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
	$xml="<?xml version='1.0' encoding='gb2312'?><root><commitdata><data><datarow hao_ming='09' code_value='' patient_name='' business_type='预约取号'/></data></commitdata><returndata><data><datarow patient_id='160114031200' times='103' name='魏莹菊' sex_chn='女' sex='2' birthday='1990/10/6' age='28' response_type='93' contract_code='' occupation_type='' charge_type='07' haoming_code='09' code='108121293001' visit_dept='' doctor_code='' visit_date='2018/3/20 9:58:17' social_no='110228199010065427' real_haoming_code='09' home_district='' home_street='北京朝阳区' hic_no='' addition_no1='108121293001' home_tel='13718156105' relation_name='' relation_code='' relation_tel='' real_times='103' p_bar_code='108121293' black_flag='a' max_receipt_sn='0' inpatient_no='' outpatient_no='' marry_code='' insurl_code='BJFZZB' school='' class='' card_no='108121293001' ic_no='10812129300S' id_no='110228199010065427' personname='魏莹菊' fromhosp='' fromhospdate='1899/12/30' fundtype='3' isyt='0' jclevel='0' hospflag='0' persontype='11' isinredlist='true' isspecifiedhosp='1' ischronichosp='' personcount='0' chroniccode='' balance='0' yb_card_no='108121293001' union_flag='1' trade_no='' card_code='20' yb_flag='1'/></data></returndata><operateinfo><info method='YYT_YB_GET_PATI' opt_id='ZZ001' opt_name='ZZ001' opt_ip='80000001' opt_date='2018-03-20' guid='{1521511096}' token='AUTO-YYRMYY-2018-03-20'/></operateinfo><result><info execute_flag='0' execute_message='操作成功!' account='2018-03-20 09:58:18'/></result></root>";
	//$xml = iconv("utf8","gb2312",htmlspecialchars_decode(I("post.input_xml")));
	$xml = str_replace("gb2312","utf8",$xml);
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
	$soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
	//$record_sn  = "165140";//
	$social_no=I("post.social_no");
	$mobile=I("post.mobile");
	$total_fee=I("post.total_fee");
	$record_sn=I("post.record_sn");
	$patient_id = I("post.patient_id");
	$card_code = I("post.card_code");
	//$zzj_id = I("post.zzj_id");
	$card_no = I("post.card_no");	
	$charge_total = I("post.charge_total");	
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
	/*$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);*/
	$result="<?xml version='1.0' encoding='utf8'?><root><commitdata><data><datarow record_sn='231396' patient_id='000113964700' card_code='21' card_no='H186701158' total_fee='' gh_sequence='0' name='杨文凭' social_no='130725199506130030' mobile='15230929753' req_type='04' register_sn='117447'/></data></commitdata><returndata><data><datarow count='1' patient_id='000113964700' register_sn='117447' request_date='2018-04-09T00:00:00' req_type='04' req_name='' unit_sn='0101010' unit_name='呼吸内科门诊' doctor_sn='00128' doctor_name='王秀云' ampm='p' clinic_type='02' clinic_name='副主任' record_sn='' gh_sequence='10' record_id='' charge='' register_flag='0' cur_times='0'/></data></returndata><operateinfo><info method='YYT_APP_REG' opt_id='' opt_name='' opt_ip='172.168.61.136' opt_date='2018-04-08' token='AUTO-YYRMYY-2018-04-08'/></operateinfo><result><info execute_flag='0' execute_message='预约成功!' account='2018-04-08 14:48:17'/></result></root>";
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
		$rel["message"]=$result_return['execute_message'];
		$rel['execute_flag']=$result_return['execute_flag'];
		
	}else{
		$rel["message"]=$result_return['execute_message'];
		$rel['execute_flag']=$result_return['execute_flag'];
	}
	
	
	$this->ajaxReturn($rel,"JSON");

}


function getSchedInfo(){
	$unit_sn = I("post.unit_sn");
	$zzj_id = I("post.zzj_id");
	$date1=I("post.date");
	//$unit_sn ="0103010" ;
	//$zzj_id =ZZJ01;
	$soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
	$s='<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow unit_sn="'.$unit_sn.'" doctor_sn="" group_sn="" doctor_py="" clinic_type="" start_date="'.$date1.'" end_date="'.$date1.'" gh_flag="2"/></data></commitdata><returndata/><operateinfo><info method="YYT_QRY_REQUEST" opt_id="700000" opt_name="'.$zzj_id.'" opt_ip="'.get_client_ip().'" opt_date="'.$date1.'" guid="'.generate_code(10).'" token="AUTO-YYRMYY-'.date("Y-m-d").'" /></operateinfo><result><info />
</result></root>';
/**************日记记录开始***********/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "获取出诊医生";
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
	/*$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);*/
	$result="<?xml version='1.0' encoding='utf8'?>
<root><commitdata><data><datarow unit_sn='0101010' doctor_sn='' group_sn='' doctor_py='' clinic_type='' start_date='2018-04-03' end_date='2018-04-03' gh_flag='2'/></data></commitdata><returndata><data><datarow count='0' record_sn='230666' request_date='2018-04-03T00:00:00' ampm='a' unit_sn='0101010' unit_name='呼吸内科门诊' doctor_sn='00579' doctor_name='武红莉' clinic_type='02' clinic_name='副主任' req_type='04' req_name='' sum_fee='60.00' record_left='5' show_type_name='门诊号' emp_title='副主任医师' open_flag='1'/><datarow count='0' record_sn='230674' request_date='2018-04-03T00:00:00' ampm='p' unit_sn='0101010' unit_name='呼吸内科门诊' doctor_sn='01036' doctor_name='姚彬' clinic_type='07' clinic_name='知名专家' req_type='04' req_name='' sum_fee='100.00' record_left='5' show_type_name='门诊号' emp_title='主任医师' open_flag='1'/><datarow count='0' record_sn='230740' request_date='2018-04-03T00:00:00' ampm='a' unit_sn='0101010' unit_name='呼吸内科门诊' doctor_sn='     ' doctor_name='' clinic_type='08' clinic_name='专科' req_type='04' req_name='' sum_fee='50.00' record_left='20' show_type_name='门诊号' emp_title='' open_flag='1'/><datarow count='0' record_sn='230739' request_date='2018-04-03T00:00:00' ampm='p' unit_sn='0101010' unit_name='呼吸内科门诊' doctor_sn='     ' doctor_name='' clinic_type='08' clinic_name='专科' req_type='04' req_name='' sum_fee='50.00' record_left='20' show_type_name='门诊号' emp_title='' open_flag='1'/></data></returndata><operateinfo><info method='YYT_QRY_REQUEST' opt_id='700000' opt_name='ZZ001' opt_ip='172.168.61.11' opt_date='2018-04-03' guid='3518391808' token='AUTO-YYRMYY-2018-04-02'/></operateinfo><result><info execute_flag='0' execute_message='执行成功' account='2018-04-02 14:16:24'/>
	</result></root>
";
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
	$result_ary = $doc->result->info;
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
		$rel['datarow'] = $datarow_attr_ary;
	}
	  $rel["moring_time"]= strtotime(date("Y-m-d"))+11.5*3600;
      $rel["moon_time"] = strtotime(date("Y-m-d"))+16.5*3600;
      $rel["now_time"] = time();
	//echo "<pre>";
	//var_dump($rel);
	$this->ajaxReturn($rel,"JSON");
}

public function yyt_qh_save(){
	$soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
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
/*	$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;*/
	$result="<?xml version='1.0' encoding='utf8'?><root><commitdata><data><datarow record_sn='128975' pay_seq='128975_-2' responce_type='' patient_id='000113964700' card_code='21' card_no='H186701158' charge_total='50' cash='50' zhzf='0' tczf='0' record_id='' gh_sequence='' bk_card_no='152****9753' trade_no='2018040821001004050518409796' stream_no='ZZ00100011396470020180408090223' addition_no1='' trade_time='2018-04-08' cheque_type='c' gh_flag='3' bank_type='' times='4'/></data></commitdata><returndata><data row_count='1'><datarow patient_id='000113964700' times='4' flag='1' patient_name='杨文凭' sex='男' age='22岁' unit_name='呼吸内科门诊' group_name='' emp_name='艾瑞超' clinic_name='专科' req_name='普通号' req_date='2018-04-08' response_type='普通(自费)' enter_opera='80548' enter_date='2018-04-08T08:55:35' cheque_type='自助支付宝缴费' gh_date='2018-04-08' sequence_no='加2' ampm='上午' receipt_sn='2814580' total_fee='50.00' fee_yb='0.00' fee_zf='50.00' fee_df='0.00' fee_zhzf='0.00' outpatient_no='' suggest_time='就诊时段: 08:00 - 12:00' visit_dept='0101010' req_type='01' location_info='' fz='请患者到相应分诊台分诊报道机报道就诊'/></data></returndata><operateinfo><info method='YYT_GH_SAVE' opt_id='ZZ001' opt_name='ZZ001' opt_ip='172.168.61.11' opt_date='2018-04-08' guid='ZZ00100011396470020180408090223' token='AUTO-YYRMYY-2018-04-08'/></operateinfo><result><info execute_flag='0' execute_message='执行成功' account='2018-04-08 09:02:47'/></result></root>";
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
				/*case "alipay"://支付宝退费
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
				
				break;*/


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



public function yyt_qh_save1(){
	$soap = new \SoapClient('http://172.168.0.229:8088/chisWebServics?service=HISImpl');
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
	$req_type=I("post.req_type");
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
	/*$row = $soap->HISImpl___FounderRequestData($params);
	$result = $row->responseData;*/
	$result ="<?xml version='1.0' encoding='utf8'?><root><commitdata><data><datarow record_sn='113278' pay_seq='113278_7' responce_type='' patient_id='000113964700' card_code='21' card_no='H186701158' charge_total='50' cash='50' zhzf='0' tczf='0' record_id='' gh_sequence='' bk_card_no='152****9753' trade_no='2018032221001004050553488035' stream_no='ZZ001000113964700201803224209' addition_no1='' trade_time='2018-03-22' req_type='04' cheque_type='c' gh_flag='2' bank_type='' times='5'/></data></commitdata><returndata><data row_count='1'><datarow patient_id='000113964700' times='5' flag='1' patient_name='杨文凭' sex='男' age='22岁' unit_name='口腔门诊' group_name='' emp_name='' clinic_name='普通门诊' req_name='诊间预约号' req_date='2018-03-22' response_type='普通(自费)' enter_opera='ZZ001' enter_date='2018-03-22T15:42:33' cheque_type='自助支付宝缴费' gh_date='2018-03-22' sequence_no='7' ampm='下午' receipt_sn='2756344' total_fee='50.00' fee_yb='0.00' fee_zf='50.00' fee_df='0.00' fee_zhzf='0.00' outpatient_no='' suggest_time='就诊时段: 13:00 - 16:30' visit_dept='0801000' req_type='04' location_info='' fz='请患者到相应分诊台分诊报道机报道就诊'/></data></returndata><operateinfo><info method='YYT_GH_SAVE' opt_id='ZZ001' opt_name='ZZ001' opt_ip='172.168.61.11' opt_date='2018-03-22' guid='ZZ001000113964700201803224209' token='AUTO-YYRMYY-2018-03-22'/></operateinfo><result><info execute_flag='0' execute_message='执行成功' account='2018-03-22 15:42:37'/></result></root>";
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
				/*case "alipay"://支付宝退费
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
				
				break;*/


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
	$xml="<?xml version='1.0' encoding='gb2312'?>
<root><commitdata><data><datarow record_sn='110738' pay_seq='110738_21' responce_type='' patient_id='160114031200' card_code='20' card_no='108121293001' charge_total='50' cash='10' zhzf='0' tczf='40' record_id='' bk_card_no='152****9753' trade_no='2018032621001004050567188558' stream_no='ZZ001160114031200201803264353' addition_no1='' trade_time='2018-03-26' cheque_type='c' gh_flag='2' bank_type='' times='107'/></data></commitdata><returndata><data row_count='1'><datarow patient_id='160114031200' times='107' flag='1' patient_name='魏莹菊' sex='女' age='27岁' unit_name='口腔门诊' group_name='' emp_name='' clinic_name='普通门诊' req_name='诊间预约号' req_date='2018-03-26' response_type='医保普通' enter_opera='ZZ001' enter_date='2018-03-26T09:43:45' cheque_type='自助支付宝缴费' gh_date='2018-03-26' sequence_no='21' ampm='上午' receipt_sn='2769092' total_fee='50.00' fee_yb='40.00' fee_zf='10.00' fee_df='0.00' fee_zhzf='0.00' outpatient_no='' suggest_time='就诊时段: 08:00 - 12:00' visit_dept='0801000' req_type='04' location_info='五层' fz='请患者到相应分诊台分诊报道机报道就诊'/></data></returndata><operateinfo><info method='YYT_YB_GH_SAVE' opt_id='ZZ001' opt_name='ZZ001' opt_ip='800000001' opt_date='2018-03-26' guid='ZZ001160114031200201803264353' token='AUTO-YYRMYY-2018-03-26'/></operateinfo><result><info execute_flag='0' execute_message='执行成功' account='2018-03-26 09:44:16'/></result></root>
";


	$xml =iconv("utf8","gb2312",htmlspecialchars_decode($xml));
	$doc = simplexml_load_string($xml);	
	/**************日记记录开始***********/
	$data['card_code'] = '20';
	$data['op_name'] = "挂号医保费用确认";
	$data['direction'] = "返回报文";
	$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
	$data['op_code'] = I("post.op_code");
	$pay_type = "alipay";//I("post.pay_type");
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
				/*case "alipay"://支付宝退费
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
				
				break;*/


				case "zf_bank":
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
	
	$xml = "<?xml version='1.0' encoding='gb2312'?>
<root><commitdata><data><datarow record_sn='110382' pay_seq='110382_4' responce_type='93' patient_id='160114031200' card_code='20' card_no='108121293001' charge_total='50' cash='10' zhzf='0' tczf='40' record_id='' bk_card_no='621226*********7125 ' trade_no='142350153432' stream_no='ZZ001160114031200201803222133' addition_no1='' trade_time='2018-03-22' cheque_type='9' gh_flag='2' bank_type='' times='107'/></data></commitdata><returndata><data row_count='1'><datarow patient_id='160114031200' times='107' flag='1' patient_name='魏莹菊' sex='女' age='27岁' unit_name='口腔门诊' group_name='' emp_name='' clinic_name='普通门诊' req_name='诊间预约号' req_date='2018-03-22' response_type='医保普通' enter_opera='ZZ001' enter_date='2018-03-22T14:21:27' cheque_type='自助银联卡缴费' gh_date='2018-03-22' sequence_no='4' ampm='下午' receipt_sn='2756334' total_fee='50.00' fee_yb='40.00' fee_zf='10.00' fee_df='0.00' fee_zhzf='0.00' outpatient_no='' suggest_time='就诊时段: 13:00 - 16:30' visit_dept='0801000' req_type='04' location_info='五层' fz='请患者到相应分诊台分诊报道机报道就诊'/></data></returndata><operateinfo><info method='YYT_YB_GH_SAVE' opt_id='ZZ001' opt_name='ZZ001' opt_ip='800000001' opt_date='2018-03-22' guid='ZZ001160114031200201803222133' token='AUTO-YYRMYY-2018-03-22'/></operateinfo><result><info execute_flag='0' execute_message='执行成功' account='2018-03-22 14:22:12'/></result></root>";
	$xml =iconv("utf8","gb2312",htmlspecialchars_decode($xml));
	$doc = simplexml_load_string($xml);	
	/**************日记记录开始***********/
	$data['card_code'] = '20';
	$data['op_name'] = "挂号医保费用确认";
	$data['direction'] = "返回报文";
	$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
	$data['op_code'] = I("post.op_code");
	$pay_type = "alipay";//I("post.pay_type");
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

			//退款代码
			if($cash!==0){
				switch($pay_type){
				/*case "alipay"://支付宝退费
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
				
				break;*/


				case "zf_bank":
				$a=M("bank_log")->db(2,"DB_CONFIG3")->where("out_trade_no='{$stream_no}'")->find();
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
	//$xml = iconv("utf8","gb2312//IGNORE",htmlspecialchars_decode(I("post.input_xml")));
	$xml = "<?xml version='1.0' encoding='gb2312'?><root><commitdata><data><datarow hao_ming='09' code_value='' patient_name='' business_type='预约取号'/></data></commitdata><returndata><data><datarow patient_id='160114031200' times='103' name='魏莹菊' sex_chn='女' sex='2' birthday='1990/10/6' age='28' response_type='93' contract_code='' occupation_type='' charge_type='07' haoming_code='09' code='108121293001' visit_dept='' doctor_code='' visit_date='2018/3/20 9:58:17' social_no='110228199010065427' real_haoming_code='09' home_district='' home_street='北京朝阳区' hic_no='' addition_no1='108121293001' home_tel='13718156105' relation_name='' relation_code='' relation_tel='' real_times='103' p_bar_code='108121293' black_flag='a' max_receipt_sn='0' inpatient_no='' outpatient_no='' marry_code='' insurl_code='BJFZZB' school='' class='' card_no='108121293001' ic_no='10812129300S' id_no='110228199010065427' personname='魏莹菊' fromhosp='' fromhospdate='1899/12/30' fundtype='3' isyt='0' jclevel='0' hospflag='0' persontype='11' isinredlist='true' isspecifiedhosp='1' ischronichosp='' personcount='0' chroniccode='' balance='0' yb_card_no='108121293001' union_flag='1' trade_no='' card_code='20' yb_flag='1'/></data></returndata><operateinfo><info method='YYT_YB_GET_PATI' opt_id='ZZ001' opt_name='ZZ001' opt_ip='80000001' opt_date='2018-03-20' guid='{1521511096}' token='AUTO-YYRMYY-2018-03-20'/></operateinfo><result><info execute_flag='0' execute_message='操作成功!' account='2018-03-20 09:58:18'/></result></root>";
	//$xml = iconv("utf8","gb2312//IGNORE",htmlspecialchars_decode($xml));
	$xml = str_replace("gb2312","utf8",$xml);
	$doc = simplexml_load_string($xml);
	//$this->writeLog(iconv("utf8","gb2312//IGNORE","调用医保DLL获取患者医保返回信息").$xml,"INFO");

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
	}else{
		$rel['result'] = $result_return;
		/**************日记记录开始***********/
		$data['op_xml'] = htmlspecialchars_decode(I("post.input_xml"));
		$data['op_code'] = I("post.op_code");
		M("logs")->add($data);
		/******************日志记录结束*********************/
	}
	//var_dump($rel);exit;
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
	$xml="<?xml version='1.0' encoding='gb2312'?><root><commitdata><data><datarow pay_seq='126495_-2' gh_flag='3' patient_id='160114031200' card_code='20' card_no='108121293001' responce_type=''/></data></commitdata><returndata><data><datarow patient_id='160114031200' charge_total='50' cash='10' zhzf='0' tczf='40' pay_seq='126495_-2'/></data></returndata><operateinfo><info method=' YYT_YB_GH_CALC' opt_id='ZZ001' opt_name='ZZ001' opt_ip='80000001' opt_date='2018-03-23' guid='{1521771931}' token='AUTO-YYRMYY-2018-03-23'/></operateinfo><result><info execute_flag='0' execute_message='操作成功!' account='2018-03-23 10:25:38'/></result></root>";

	$xml =iconv("utf8","gb2312",htmlspecialchars_decode($xml));
	
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
	//$row = $soap->getPayUrl($out_trade_no,$total_amount,$subject,$source);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;
	$this->ajaxReturn($rel,"JSON");
}
//查询订单状态接口方法
public function ajaxGetPayStatus(){
	/*$soap = new \SoapClient('http://172.168.0.89/soap1/Service.php?wsdl');   
	$out_trade_no = trim(I("post.out_trade_no"));
	$source="hos122";
	//$row = $soap->getPayStatus($out_trade_no,$source);
	$xml = simplexml_load_string($row);
	$xml = (array)$xml;
	$message = $xml['Message'];
	$rel['message'] = $message;*/
	 $rel = '1';
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













public function tiaoma(){
            //ob_clean();
           //$text=$_SESSION['confirm']['patient_id'];
           $text="123466soasgous";
            // Loading Font
            Vendor('Buildcode.class.BCGFontFile');
            Vendor('Buildcode.class.BCGColor');
            Vendor('Buildcode.class.BCGcode128#barcode');
            Vendor('Buildcode.class.BCGDrawing');
            $class_dir =VENDOR_PATH.'Buildcode';
            $font = new \BCGFontFile($class_dir.'/font/Arial.ttf', 18);

            // Don't forget to sanitize user inputs
           // $text = isset($_GET['text']) ? $_GET['text'] : 'HELLO';

            // The arguments are R, G, B for color.
            $color_black = new \BCGColor(0, 0, 0);
            $color_white = new \BCGColor(255, 255, 255);

            $drawException = null;
            try {
                $code = new \BCGcode128();
                $code->setScale(2); // Resolution
                $code->setThickness(30); // Thickness
                $code->setForegroundColor($color_black); // Color of bars
                $code->setBackgroundColor($color_white); // Color of spaces
                $code->setFont($font); // Font (or 0)
                $code->parse($text); // Text
            } catch(Exception $exception) {
                $drawException = $exception;
            }
            
            /* Here is the list of the arguments
            1 - Filename (empty : display on screen)
            2 - Background color*/ 
            $drawing = new \BCGDrawing('', $color_white);
            if($drawException) {
                $drawing->drawException($drawException);
            } else {
                $drawing->setBarcode($code);
                $drawing->draw();
            }
            
            
            // Header that says it is an image (remove it if you save the barcode to a file)
            header('Content-Type: image/png');
            header('Content-Disposition: inline; filename="barcode.png"');
            
            // Draw (or save) the image into PNG format.
            $drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);
            

        }































}