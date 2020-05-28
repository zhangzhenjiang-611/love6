<?php
namespace ZiZhu\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
class QxyyController extends CommonController {
public function index()
{
	$zzj_id = I("get.zzj_id");
	//var_dump($zzj_id);
    $this->assign("zzj_id",$zzj_id);
   	$this->display();
}


public function getPatInfo1(){
/*	$soap = new \SoapClient('http://172.168.0.231:8088/chisWebServics?service=HISImpl');
	$kaid = I("post.kaid");
	$zzj_id = I("post.zzj_id");
	$business_type = I("post.business_type");
	if(strpos($kaid,"H")!==false){
		$hao_ming="07";
	}
	
	else if(strlen($kaid)>=18){
		$hao_ming="06";
	}else if(strlen($kaid)==12){
		$hao_ming="08";
	}
	$s = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root><commitdata><data><datarow hao_ming =\"".$hao_ming."\" code_value =\"".$kaid."\" patient_name=\"\" business_type =\"".$business_type."\" /></data></commitdata><returndata/><operateinfo><info method=\"YYT_QRY_PATI\" opt_id=\"".zzj_id."\" opt_name=\"".$zzj_id."\" opt_ip=\"".get_client_ip()."\" opt_date=\"".date("Y-m-d")."\" guid=\"{".generate_code(10)."}\" token=\"AUTO-YYRMYY-".date("Y-m-d")."\"  /></operateinfo><result><info /></result></root>";
	$data['card_code'] = '21';
	$data['card_no'] = $kaid;
	$data['op_name'] = "获取就诊卡患者信息";
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
	if (is_callable(array($soap, 'FounderRequestData'))) {
		$row = $soap->FounderRequestData($params);
	}else{
		$row = $soap->HISImpl___FounderRequestData($params);
	}
	$result = $row->responseData;
	$result = str_replace("gb2312","utf8",$result);*/
	$result='<?xml version="1.0" encoding="utf8"?>
<root><commitdata><data><datarow hao_ming="07" code_value="H508635413" patient_name="" business_type="自助缴费"/></data></commitdata><returndata><data><datarow patient_id="000124210100" name="姚浩余" sex="1" sex_chn="男" birthday="1991-07-27T00:00:00" social_no="370281199107270013" marry_code=" " marry_chn="" p_bar_code="H508635413" country_code="cn" country_chn="中国" nation_code="HA" nation_chn="汉族" birth_place="370281" home_district="" home_district_chn="370281" home_street="山东省胶州市北京东路326号3号楼1单元602户" home_tel="" mobile="18513853616" home_zipcode="      " addition_no1="" response_type="01" response_chn="普通(公费)" charge_type="" card_code="21" card_no="H508635413" build_date="2018-04-07T09:29:36" use_times="3" balance="0.00" amount="1"/></data></returndata><operateinfo><info method="YYT_QRY_PATI" opt_id="zzj_id" opt_name="ZZ005" opt_ip="172.168.61.105" opt_date="2018-04-08" guid="{9183344711}" token="AUTO-YYRMYY-2018-04-08"/></operateinfo><result><info execute_flag="0" execute_message="执行成功" account="2018-04-08 09:10:38"/></result></root>';
	$result = str_replace("gb2312","utf8",$result);
	$doc = simplexml_load_string($result);
	$this->writeLog(utf8_to_gbk("获取自费患者身份信息，返回报文信息：".$result));
	/*$data2['card_code'] = '21';
	$data2['card_no'] = $kaid;
	$data2['op_xml'] = $result;
	$data2['op_name'] = "获取就诊卡患者信息";
	$data2['op_code'] = I("post.op_code");
	$data2['direction'] = "返回报文";
	M("logs")->add($data2);*/
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
	
	$this->writeLog(utf8_to_gbk("获取自费患者就诊记录，返回报文信息转数组：".var_export($rel,true)));
	$this->ajaxReturn($rel,"JSON");

} 



//获取患者信息
public function getPatInfo(){
	// //实例化websever  
	// $soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	// //var_dump($soap);
	// //前台传递过来的卡号
	// $kaid = I("post.kaid");
	// //前台传递过来的自助机ID
	// $zzj_id = I("post.zzj_id");
	// $card_code = I("post.card_code");
	// $op_code = I("post.op_code");
	// //前台传递过来的业务类型 自助挂号，自助缴费，预约挂号
	// $business_type = I("post.business_type");
	// $str = "<zzxt><transcode>101</transcode><table><cardno>1</cardno><cardtype>1</cardtype><czyh>zzj001</czyh></table></zzxt>";
	// $data['card_no'] = I("post.kaid");
	// $data['card_code'] = I("post.card_code"); 
	// $data['op_name'] = "获取就诊卡患者信息";
	// $data['direction'] = "发送报文";
	// $data['op_xml'] = $str;
	// $data['op_code'] = I("post.op_code");
	// M("logs")->add($data);
	// $params->AXml = $str;
	// $row = $soap->CallService($params);
	// $CallService = $row->{"CallServiceResult"};
	$CallService = '<zzxt><result><retcode>0</retcode><retmsg>交易成功</retmsg></result><table><brzt>T</brzt><patid>1247048</patid><cardno>1</cardno><cardtype>1 </cardtype><pzlx>4 </pzlx><hzxm>皮耘硕</hzxm><ybdm>14 </ybdm><ybsm>新门诊医保</ybsm><sex>男 </sex><sfzh>110113201602143839</sfzh><lxdz></lxdz><ghbz>1</ghbz><lxdh>13911060252</lxdh><zhye>0</zhye><ispwd></ispwd><djrq></djrq><lrrq>20160325 </lrrq><gsbz>0</gsbz><birth>20160214 </birth><blh>20160000021754</blh><brnl>1</brnl></table></zzxt>';
    $OB_CallService = simplexml_load_string($CallService);
    $rel['patInfo'] = json_decode(json_encode($OB_CallService),ture);
 //    $data2['card_code'] = I("post.card_code");
	// $data2['card_no'] = I("post.kaid");
	// $data2['op_xml'] = $CallService;
	// $data2['op_name'] = "获取就诊卡患者信息";
	// $data2['op_code'] = I("post.op_code");
	// $data2['direction'] = "返回报文";
	// M("logs")->add($data2);
	$pat_id = $rel['patInfo']['table']['patid'];
	$yuyue_xinxi = $this->yuyue_xinxi($kaid,$card_code,$op_code,$pat_id);
	$rel['datarow'] = $yuyue_xinxi['datarow'];
	$rel['xinxi_zt'] = $yuyue_xinxi;
	$this->ajaxReturn($rel,"JSON");

} 
//获取患者预约挂号信息
public function yuyue_xinxi($kaid,$card_code,$op_code,$pat_id){
	//实例化websever  
	// $soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	// $str = "<zzxt><transcode>304</transcode><table><patid>1247048</patid><bz>1</bz></table></zzxt>";
	// $data['card_no'] = $kaid;
	// $data['card_code'] = $card_code; 
	// $data['op_name'] = "预约挂号信息";
	// $data['direction'] = "发送报文";
	// $data['op_xml'] = $str;
	// $data['op_code'] = $op_code;
	// $op_code = I("post.op_code");
	// M("logs")->add($data);
	// $params->AXml = $str;
	// $row = $soap->CallService($params);
	// $CallService = $row->{"CallServiceResult"};
	$CallService = '<zzxt><result><retcode>0</retcode><retmsg>交易成功</retmsg></result><tablecount>1</tablecount><table1><yylsh>201361</yylsh><yyksmc>乳腺外科</yyksmc><yyysmc></yyysmc><yyxh>1555</yyxh><yyrq>20170515</yyrq><sjd>上午</sjd><yylb>科室</yylb><ksdm>0212</ksdm><ysdm></ysdm><pbmxxh>26016</pbmxxh></table1></zzxt>';
    $OB_CallService = simplexml_load_string($CallService);
    $rel = json_decode(json_encode($OB_CallService),ture);
 //    $data2['card_code'] = $card_code;
	// $data2['card_no'] = $kaid;
	// $data2['op_xml'] = $CallService;
	// $data2['op_name'] = "预约挂号信息";
	// $data2['op_code'] = $op_code;
	// $data2['direction'] = "返回报文";
	// M("logs")->add($data2);
	for ($x=1; $x<=$rel['tablecount']; $x++){
        $rel['datarow'][]=$rel['table'.$x];
    }
	return $rel;

} 
//获取患者预约信息 
public function patReservationRecord(){
	$kaid = I("post.kaid");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$pat_id = I("post.pat_id");
	$kaid = I("post.kaid");
	$yuyue_xinxi = $this->yuyue_xinxi($kaid,$card_code,$op_code,$pat_id);
	$rel['datarow'] = $yuyue_xinxi['datarow'];
	$rel['xinxi_zt'] = $yuyue_xinxi;
	$this->ajaxReturn($rel,"JSON");
}
public function get_room_yuyuekeshi(){
	// $kaid = I("post.kaid");
	// $card_code = I("post.card_code");
	// $op_code = I("post.op_code");
	// $kaid = I("post.kaid");
	// $time_ks = time()+1*24*3600;
	// $room_time = date("Ymd",$time_ks);//当前时间加一天
	// $time = time()+7*24*3600;//后四十天时间
 //    $room_time_s = date('Ymd',$time);
	// //实例化websever  
	// $soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	// $str = "<zzxt><transcode>301</transcode><table><lb>0</lb><kssj>20170810</kssj><jssj>20170816</jssj></table></zzxt>";
	// $data['card_no'] = $kaid;
	// $data['card_code'] = $card_code; 
	// $data['op_name'] = "预约科室信息";
	// $data['direction'] = "发送报文";
	// $data['op_xml'] = $str;
	// $data['op_code'] = $op_code;
	// $op_code = I("post.op_code");
	// M("logs")->add($data);
	// $params->AXml = $str;
	// $row = $soap->CallService($params);
	// $CallService = $row->{"CallServiceResult"};
	$CallService = '<zzxt><result><retcode>0</retcode><retmsg>交易成功</retmsg></result><tablecount>11</tablecount><table1><ysdm></ysdm><ysmc></ysmc><ksdm>011 </ksdm><ksmc>内科</ksmc></table1><table2><ysdm></ysdm><ysmc></ysmc><ksdm>021 </ksdm><ksmc>外科</ksmc></table2><table3><ysdm></ysdm><ysmc></ysmc><ksdm>041 </ksdm><ksmc>儿科</ksmc></table3><table4><ysdm></ysdm><ysmc></ysmc><ksdm>051 </ksdm><ksmc>眼科</ksmc></table4><table5><ysdm></ysdm><ysmc></ysmc><ksdm>061 </ksdm><ksmc>耳鼻喉科</ksmc></table5><table6><ysdm></ysdm><ysmc></ysmc><ksdm>204 </ksdm><ksmc>皮肤科</ksmc></table6><table7><ysdm></ysdm><ysmc></ysmc><ksdm>205 </ksdm><ksmc>产科</ksmc></table7><table8><ysdm></ysdm><ysmc></ysmc><ksdm>206 </ksdm><ksmc>妇科</ksmc></table8><table9><ysdm></ysdm><ysmc></ysmc><ksdm>207 </ksdm><ksmc>计划生育科</ksmc></table9><table10><ysdm></ysdm><ysmc></ysmc><ksdm>208 </ksdm><ksmc>妇科内分泌</ksmc></table10><table11><ysdm></ysdm><ysmc></ysmc><ksdm>209 </ksdm><ksmc>乳腺外科</ksmc></table11></zzxt>';
    $OB_CallService = simplexml_load_string($CallService);
    $rel = json_decode(json_encode($OB_CallService),ture);
 //    $data2['card_code'] = $card_code;
	// $data2['card_no'] = $kaid;
	// $data2['op_xml'] = $CallService;
	// $data2['op_name'] = "预约科室信息";
	// $data2['op_code'] = $op_code;
	// $data2['direction'] = "返回报文";
	// M("logs")->add($data2);
	for ($x=1; $x<=$rel['tablecount']; $x++){
        $rel['room'][]=$rel['table'.$x];
    }
	$this->ajaxReturn($rel,"JSON");
}
public function keshi_list(){
	// $kaid = I("post.kaid");
	// $card_code = I("post.card_code");
	// $op_code = I("post.op_code");
	// $kaid = I("post.kaid");
	// $ksdm = I("post.ksdm");
	// $time_ks = time()+1*24*3600;
	// $room_time = date("Ymd",$time_ks);//当前时间加一天
	// $time = time()+7*24*3600;//后四十天时间
 //    $room_time_s = date('Ymd',$time);
	// //实例化websever  
	// $soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	// $str = "<zzxt><transcode>301</transcode><table><lb>0</lb><kssj>20170810</kssj><jssj>20170816</jssj><ejksdm>011 </ejksdm></table></zzxt>";
	// $data['card_no'] = $kaid;
	// $data['card_code'] = $card_code; 
	// $data['op_name'] = "预约二级科室信息";
	// $data['direction'] = "发送报文";
	// $data['op_xml'] = $str;
	// $data['op_code'] = $op_code;
	// $op_code = I("post.op_code");
	// M("logs")->add($data);
	// $params->AXml = $str;
	// $row = $soap->CallService($params);
	// $CallService = $row->{"CallServiceResult"};
	$CallService = '<zzxt><result><retcode>0</retcode><retmsg>交易成功</retmsg></result><tablecount>1</tablecount><table1><ysdm></ysdm><ysmc></ysmc><ksdm>0111</ksdm><ksmc>内科</ksmc></table1></zzxt>';
    $OB_CallService = simplexml_load_string($CallService);
    $rel = json_decode(json_encode($OB_CallService),ture);
 //    $data2['card_code'] = $card_code;
	// $data2['card_no'] = $kaid;
	// $data2['op_xml'] = $CallService;
	// $data2['op_name'] = "预约二级科室信息";
	// $data2['op_code'] = $op_code;
	// $data2['direction'] = "返回报文";
	// M("logs")->add($data2);
	for ($x=1; $x<=$rel['tablecount']; $x++){
        $rel['room'][]=$rel['table'.$x];
    }
	$this->ajaxReturn($rel,"JSON");
}

//二级科室
// public function get_room_yuyuekeshi_erji(){
// 	$kaid = I("post.kaid");
// 	$card_code = I("post.card_code");
// 	$op_code = I("post.op_code");
// 	$kaid = I("post.kaid");
// 	$ksdm = I("post.ksdm");
// 	$time_ks = time()+1*24*3600;
// 	$room_time = date("Ymd",$time_ks);//当前时间加一天
// 	$time = time()+7*24*3600;//后四十天时间
//     $room_time_s = date('Ymd',$time);
// 	//实例化websever  
// 	$soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
// 	$str = "<zzxt><transcode>301</transcode><table><lb>0</lb><kssj>".$room_time."</kssj><jssj>".$room_time_s."</jssj></table></zzxt>";
// 	$data['card_no'] = $kaid;
// 	$data['card_code'] = $card_code; 
// 	$data['op_name'] = "预约科室信息";
// 	$data['direction'] = "发送报文";
// 	$data['op_xml'] = $str;
// 	$data['op_code'] = $op_code;
// 	$op_code = I("post.op_code");
// 	M("logs")->add($data);
// 	$params->AXml = $str;
// 	$row = $soap->CallService($params);
// 	$CallService = $row->{"CallServiceResult"};
//     $OB_CallService = simplexml_load_string($CallService);
//     $rel = json_decode(json_encode($OB_CallService),ture);
//     $data2['card_code'] = $card_code;
// 	$data2['card_no'] = $kaid;
// 	$data2['op_xml'] = $CallService;
// 	$data2['op_name'] = "预约科室信息";
// 	$data2['op_code'] = $op_code;
// 	$data2['direction'] = "返回报文";
// 	M("logs")->add($data2);
// 	for ($x=1; $x<=$rel['tablecount']; $x++){
//         $rel['room'][]=$rel['table'.$x];
//     }
// 	$this->ajaxReturn($rel,"JSON");
// }
//预约取消
public function yuyue_quxiao(){
	// $soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	// $yylsh = I("post.yylsh");
	// $card_code = I("post.card_code");
	// $card_no = I("post.card_no");
	// $pati_id = I("post.patient_id");
	// $op_code = I("post.op_code");
	// $zzj_id = I("post.zzj_id");
	// $s="<zzxt><transcode>305</transcode><table><yylsh>226385</yylsh><czyh>zzj001</czyh></table></zzxt>";
	// //记录日志
	// $data['card_no'] = $card_no;
	// $data['card_code'] = $card_code; 
	// $data['op_name'] = "预约取消";
	// $data['direction'] = "发送报文";
	// $data['op_xml'] = $s;
	// $data['op_code'] = $op_code;
	// M("logs")->add($data);
	// //结束日志
	// $params->AXml = $s;
	// $row = $soap->CallService($params);
	// $CallService = $row->{"CallServiceResult"};
	$CallService = '<zzxt><result><retcode>0</retcode><retmsg>交易成功</retmsg></result><tablecount>1</tablecount><table1><yylsh>226385</yylsh></table1></zzxt>';
    $OB_CallService = simplexml_load_string($CallService);
    $rel = json_decode(json_encode($OB_CallService),ture);
 //    $data2['card_code'] = $card_code;
	// $data2['card_no'] = $card_no;
	// $data2['op_xml'] = $CallService;
	// $data2['op_name'] = "预约取消";
	// $data2['op_code'] = I("post.op_code");
	// $data2['direction'] = "返回报文";
	// M("logs")->add($data2);
	$this->ajaxReturn($rel,"JSON");
}

//检查自助机是否缺纸
function jian_ce(){
	$zzj_id = I("post.zzj_id");
	$devstatus=M("devstatus");
	$list=$devstatus->where("DevName='$zzj_id'")->find();
	$this->ajaxReturn($list,"JSON");
}
//预约号源数信息查询
function yuyue_list_hoayuan(){
	// $soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	// $kaid = I("post.kaid");
	// $op_code = I("post.op_code");
	// $zzj_id = I("post.zzj_id");
	// $card_code = I("post.card_code");
	// $pat_id = I("post.pat_id");
	// $ksdm = I("post.ksdm");
	// $time_ks = time()+1*24*3600;
	// $hoayuan_time = date("Ymd",$time_ks);//当前时间加一天
	// $time = time()+7*24*3600;//后四十天时间
 //    $hoayuan_time_s = date('Ymd',$time);
	// $str = "<zzxt><transcode>302</transcode><table><lb>0</lb><kssj>20170810</kssj><jssj>20170816</jssj><kmdm>0111</kmdm></table></zzxt>>";
 // 	/**************日记记录开始***********/
	// $data['card_code'] = $card_code;
	// $data['card_no'] =  $kaid;
	// $data['op_name'] = "获取预约号源信息";
	// $data['direction'] = "发送报文";
	// $data['op_xml'] = $str;
	// $data['op_code'] = $op_code;
	// M("logs")->add($data);
	// $params->AXml = $str;
	// $row = $soap->CallService($params);
	// $CallService = $row->{"CallServiceResult"};
	$CallService = '<zzxt><result><retcode>0</retcode><retmsg>交易成功</retmsg></result><tablecount>10</tablecount><table1><kyys>70</kyys><yyzs>70</yyzs><pbmxid>31373</pbmxid><zxrq>20170810</zxrq><kssj>07:00</kssj><jssj>12:00</jssj><weekday>星期四</weekday><ghf>0.00</ghf><zlf>30.00</zlf><zzlx>上午</zzlx></table1><table2><kyys>50</kyys><yyzs>50</yyzs><pbmxid>31374</pbmxid><zxrq>20170810</zxrq><kssj>13:20</kssj><jssj>17:30</jssj><weekday>星期四</weekday><ghf>0.00</ghf><zlf>30.00</zlf><zzlx>下午</zzlx></table2><table3><kyys>70</kyys><yyzs>70</yyzs><pbmxid>31416</pbmxid><zxrq>20170811</zxrq><kssj>07:00</kssj><jssj>12:00</jssj><weekday>星期五</weekday><ghf>0.00</ghf><zlf>30.00</zlf><zzlx>上午</zzlx></table3><table4><kyys>50</kyys><yyzs>50</yyzs><pbmxid>31417</pbmxid><zxrq>20170811</zxrq><kssj>13:20</kssj><jssj>17:30</jssj><weekday>星期五</weekday><ghf>0.00</ghf><zlf>30.00</zlf><zzlx>下午</zzlx></table4><table5><kyys>70</kyys><yyzs>70</yyzs><pbmxid>31626</pbmxid><zxrq>20170814</zxrq><kssj>07:00</kssj><jssj>12:00</jssj><weekday>星期一</weekday><ghf>0.00</ghf><zlf>30.00</zlf><zzlx>上午</zzlx></table5><table6><kyys>50</kyys><yyzs>50</yyzs><pbmxid>31627</pbmxid><zxrq>20170814</zxrq><kssj>13:20</kssj><jssj>17:30</jssj><weekday>星期一</weekday><ghf>0.00</ghf><zlf>30.00</zlf><zzlx>下午</zzlx></table6><table7><kyys>70</kyys><yyzs>70</yyzs><pbmxid>31679</pbmxid><zxrq>20170815</zxrq><kssj>07:00</kssj><jssj>12:00</jssj><weekday>星期二</weekday><ghf>0.00</ghf><zlf>30.00</zlf><zzlx>上午</zzlx></table7><table8><kyys>50</kyys><yyzs>50</yyzs><pbmxid>31680</pbmxid><zxrq>20170815</zxrq><kssj>13:20</kssj><jssj>17:30</jssj><weekday>星期二</weekday><ghf>0.00</ghf><zlf>30.00</zlf><zzlx>下午</zzlx></table8><table9><kyys>70</kyys><yyzs>70</yyzs><pbmxid>31727</pbmxid><zxrq>20170816</zxrq><kssj>07:00</kssj><jssj>12:00</jssj><weekday>星期三</weekday><ghf>0.00</ghf><zlf>30.00</zlf><zzlx>上午</zzlx></table9><table10><kyys>50</kyys><yyzs>50</yyzs><pbmxid>31728</pbmxid><zxrq>20170816</zxrq><kssj>13:20</kssj><jssj>17:30</jssj><weekday>星期三</weekday><ghf>0.00</ghf><zlf>30.00</zlf><zzlx>下午</zzlx></table10></zzxt>';
    $OB_CallService = simplexml_load_string($CallService);
    $rel = json_decode(json_encode($OB_CallService),ture);
    /**************日记记录开始***********/
	// $data1['card_code'] = $card_code;
	// $data1['card_no'] =  $kaid;
	// $data1['op_name'] = "获取预约号源信息";
	// $data1['direction'] = "返回报文";
	// $data1['op_xml'] = $CallService;
	// $data1['op_code'] = $op_code;
	// M("logs")->add($data1);
	for ($x=1; $x<=$rel['tablecount']; $x++) {
		$rel['table'.$x]['zxrq'] = date("m月d日", strtotime($rel['table'.$x]['zxrq']));
		if($rel['table'.$x]['zzlx'] == "上午"){
			$rel['datarow'][$x]['sw']=$rel['table'.$x];
		}else{
			if($rel['table'.$x]['zzlx'] == "下午"){
				$rel['datarow'][$x-1]['xw']=$rel['table'.$x];
			}
		}
    }
	$data = array();
    foreach($rel['datarow'] as  $value){
        $data[] = $value;
    }
    $rel['datarow'] = $data;
	$this->ajaxReturn($rel,"JSON");

}
//预约信息登记
function yuyue_xinxi_dengji(){
	// $soap = new \SoapClient("http://127.0.0.1:8082/KWSService.asmx?wsdl");
	// $kaid = I("post.kaid");
	// $op_code = I("post.op_code");
	// $zzj_id = I("post.zzj_id");
	// $card_code = I("post.card_code");
	// $pat_id = I("post.pat_id");
	// $pbmxid = I("post.pbmxid");
	// $sjdjl = I("post.sjdjl");
	// $str = "<zzxt><transcode>303</transcode><table><patid>1247048</patid><pbmxid>31728</pbmxid><sjdjl>13:20</sjdjl><czyh>zzj001</czyh><yyhx>0</yyhx></table></zzxt>";
 // 	/**************日记记录开始***********/
	// $data['card_code'] = $card_code;
	// $data['card_no'] =  $kaid;
	// $data['op_name'] = "预约信息登记";
	// $data['direction'] = "发送报文";
	// $data['op_xml'] = $str;
	// $data['op_code'] = $op_code;
	// M("logs")->add($data);
	// $params->AXml = $str;
	// $row = $soap->CallService($params);
	// $CallService = $row->{"CallServiceResult"};
	$CallService = '<zzxt><result><retcode>0</retcode><retmsg>交易成功</retmsg></result><table><xh>226385</xh><yyhx>1011</yyhx><yyrq>20170816</yyrq><sjd>13:20-17:30</sjd></table></zzxt>';
    $OB_CallService = simplexml_load_string($CallService);
    $rel = json_decode(json_encode($OB_CallService),ture);
    /**************日记记录开始***********/
	// $data1['card_code'] = $card_code;
	// $data1['card_no'] =  $kaid;
	// $data1['op_name'] = "预约信息登记";
	// $data1['direction'] = "返回报文";
	// $data1['op_xml'] = $CallService;
	// $data1['op_code'] = $op_code;
	// M("logs")->add($data1);
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
	$source="hos008";
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
//查询订单状态接口方法
public function ajaxGetPayStatus(){
	$soap = new \SoapClient('http://127.0.0.1/soap/Service.php?wsdl');   
	$out_trade_no = trim(I("post.out_trade_no"));
	$source="hos008";
	$row = $soap->getPayStatus($out_trade_no,$source);
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
	M("logs")->add($data);
}




}