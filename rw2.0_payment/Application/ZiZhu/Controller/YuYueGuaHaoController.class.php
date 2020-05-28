<?php
namespace ZiZhu\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
require_once __DIR__ .'/Log.php';
class YuYueGuaHaoController extends CommonController {
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
public function his_record(){
    $trade_no=I("post.trade_no");
	
    /**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "查询HIS交易记录";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = "select * from v_yyt_jydz_rw_check where trade_no='". $trade_no."'";
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
    $row = M()->db(1,"DB_CONFIG1")->query("select * from v_yyt_jydz_rw_check where trade_no='". $trade_no."'");
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
    $nums = count($row);
     
    if($nums=="1"){
        $res['success'] ="SUCCESS";
        $this->ajaxReturn($res,"json");
    }else{
        $res['success'] ="FAIL";
        $this->ajaxReturn($res,"json");
    }
}

/**********支付宝 微信 退款*******************************/
public function ajax_tuikuan(){
   
    $soap = new \SoapClient(null,array('location'=>'http://172.18.1.23/soap/Service.php',uri=>'Service.php'));
    $pay_type = I("post.pay_type");
    $trade_no = I("post.trade_no");
    $out_trade_no = trim(I("post.out_trade_no"));
    $total_amount = trim(I("post.total_amount"));
    $subject = trim(I("post.subject"));
    $source="hos031";
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
    $this->ajaxReturn($rel,"JSON");
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

	if($transaction_type == '3'){ 
	    $transaction_type = '自费';
	}else{
	    $transaction_type = '医保';
	}

	// transaction_mode  支付方式
	$transaction_mode = I("post.transaction_mode");
	if($transaction_mode == 'alipay'){
		$transaction_mode = "支付宝";
	}else if($transaction_mode == 'yhk'){
		$transaction_mode = "银行卡";
	}else{
		$transaction_mode = "微信";
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
function huoqu_time(){
	// 查询HIS服务器时间SQL
	$str = '<?xml version="1.0" encoding="GBK" ?>
	<root>
		<head>
			<actdate>20131203</actdate>
			<trdate>20131203</trdate>
			<trtime>082743</trtime>
			<trcode>YQXYYZZJ000</trcode>
		</head>
	</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "查询HIS服务器时间";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
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


// 就诊卡获取病人信息
public function ic_getPatInfo(){
 	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	// $card_no = "10100832";
 	// $card_no = "12315643300";
 	$card_code = "3";
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	$str = '<?xml version="1.0" encoding="GBK" ?>
		<root>
			<head>
				<actdate>'.date("Ymd",time()).'</actdate>
				<trdate>'.date("Ymd",time()).'</trdate>
				<trtime>'.date("His",time()).'</trtime>
				<trcode>Z100</trcode>
			</head>
			<body>
				<acttype>3</acttype>
				<actno>'.$card_no.'</actno>
				<medpos></medpos>
			</body>
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
	//实例化socket
    //$socketaddr = "172.16.77.18";
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
       	$rel['head']['succflag']="2";
		$rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);	
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取就诊卡患者信息";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		if($rel['head']['succflag']=="1"){
			$rel['yijikeshi'] = $this->yijikeshi_list();
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}
}
// 身份证获取病人信息
public function sfz_getPatInfo(){
 	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	// $card_no = "10100832";
 	// $card_no = "12315643300";
 	$card_code = "2";
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	$str = '<?xml version="1.0" encoding="GBK" ?>
		<root>
			<head>
				<actdate>'.date("Ymd",time()).'</actdate>
				<trdate>'.date("Ymd",time()).'</trdate>
				<trtime>'.date("His",time()).'</trtime>
				<trcode>Z100</trcode>
			</head>
			<body>
				<acttype>2</acttype>
				<actno>'.$card_no.'</actno>
				<medpos></medpos>
			</body>
		</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "获取身份证患者信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
    //$socketaddr = "172.16.77.18";
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
       	$rel['head']['succflag']="2";
		$rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);	
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取身份证患者信息";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		if($rel['head']['succflag']=="1"){
			$rel['yijikeshi'] = $this->yijikeshi_list();
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}
}
/**********************查询一级科室*****************************/
public function yijikeshi_list(){
    $regclass = I("post.regclass");	
    $card_no = I("post.card_no");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$zzj_id =  I("post.zzj_id");
    // 查询HIS服务器时间
	$str = '<?xml version="1.0" encoding="GBK" ?>
	<root>
		<head>
			<actdate>20131203</actdate>
			<trdate>20131203</trdate>
			<trtime>082743</trtime>
			<trcode>Z000</trcode>
		</head>
	</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "查询HIS服务器时间";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/	
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
        $rel['head']['succflag']="2";
        $rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);

		$doc = simplexml_load_string($result3);
		$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "查询HIS服务器时间";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/	
	    $His_time =  $rels["body"]["CurrentDateTime"];
	    //HIS服务器时间 如2017-11-23 10:37:30
		$fwqsj = $His_time;
		$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20131203</actdate>
					<trdate>20131203</trdate>
					<trtime>090022</trtime>
					<trcode>Z208</trcode>
				</head>
				<body>
					<regclass>2</regclass>
					<ksdj>1</ksdj>
					<ksdm></ksdm>
				</body>
				
			</root>';
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取一级科室列表(预约)";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/	
		//实例化socket
		$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		$socketport = "2013";
		$socket = \Socket::singleton();
		//链接
		$aa = $socket->connect($socketaddr,$socketport);
		
		//写数据
		$sockresult = $socket->sendrequest($str);
		//读数据
		$response = $socket->waitforresponse();
		$socket->disconnect (); //关闭链接
		if($response == ""  || $response == false){
	        $rel['head']['succflag']="2";
	        $rel['head']['retmsg']="请求超时,请重试";
	        $this->ajaxReturn($rel,"JSON");
		}else{
			$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result5 = str_replace("gbk","utf8",$result2);	
			$result3 =  str_replace("<*", "", $result5);	
			$doc = simplexml_load_string($result3);
			$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
			 
			/**************日记记录开始**************/
			$data['card_code'] = I("post.card_code");
			$data['patient_id'] = I("post.patient_id");
			$data['card_no'] = I("post.card_no");
			$data['op_name'] = "获取一级科室列表(预约)";
			$data['direction'] = "返回报文";
			$data['op_code'] = I("post.op_code");
			$data['op_xml'] = $result3;
			$data['zzj_id'] = I("post.zzj_id");
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
			$arr =  $rel["body"]["detail"];
			if($rel["head"]["succflag"]=="1"){
				return $arr;
			}else{
				$this->ajaxReturn($rel,"JSON");
			}
		}
	}
}
/**********************查询二级科室*****************************/
public function erjikeshi_list(){
    $regclass = I("post.regclass");	
    $card_no = I("post.card_no");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$zzj_id =  I("post.zzj_id");
	$ksdm =  I("post.ksdm");
    // 查询HIS服务器时间
	$str = '<?xml version="1.0" encoding="GBK" ?>
	<root>
		<head>
			<actdate>20131203</actdate>
			<trdate>20131203</trdate>
			<trtime>082743</trtime>
			<trcode>Z000</trcode>
		</head>
	</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "查询HIS服务器时间";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/	
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
        $rel['head']['succflag']="2";
        $rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);

		$doc = simplexml_load_string($result3);
		$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "查询HIS服务器时间";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/	
	    $His_time =  $rels["body"]["CurrentDateTime"];
	    //HIS服务器时间 如2017-11-23 10:37:30
		$fwqsj = $His_time;
		$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20131203</actdate>
					<trdate>20131203</trdate>
					<trtime>090022</trtime>
					<trcode>Z208</trcode>
				</head>
				<body>
					<regclass>2</regclass>
					<ksdj>2</ksdj>
					<ksdm>'.$ksdm.'</ksdm>
				</body>
			</root>';
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取二级科室列表(预约)";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/	
		//实例化socket
		$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		$socketport = "2013";
		$socket = \Socket::singleton();
		//链接
		$aa = $socket->connect($socketaddr,$socketport);
		
		//写数据
		$sockresult = $socket->sendrequest($str);
		//读数据
		$response = $socket->waitforresponse();
		$socket->disconnect (); //关闭链接
		if($response == ""  || $response == false){
	        $rel['head']['succflag']="2";
	        $rel['head']['retmsg']="请求超时,请重试";
	        $this->ajaxReturn($rel,"JSON");
		}else{
			$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result5 = str_replace("gbk","utf8",$result2);	
			$result3 =  str_replace("<*", "", $result5);
			$doc = simplexml_load_string($result3);
			$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
			 
			/**************日记记录开始**************/
			$data['card_code'] = I("post.card_code");
			$data['patient_id'] = I("post.patient_id");
			$data['card_no'] = I("post.card_no");
			$data['op_name'] = "获取二级科室列表(预约)";
			$data['direction'] = "返回报文";
			$data['op_code'] = I("post.op_code");
			$data['op_xml'] = $result3;
			$data['zzj_id'] = I("post.zzj_id");
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
			$arr =  $rel["body"]["detail"];
			if($rel["head"]["succflag"]=="1"){
		        // $res["head"]["succflag"]="1";
		       
		        if($rel["body"]["rowcount"]=="0"){
		            $rel['erjikeshi'] ="";         // 当专家列表为空
			   	}elseif($rel["body"]["rowcount"]=="1"){
					        
				  	// 当专家列表只有一个  转为二位数组
					$rel['erjikeshi']["0"] =  $rel["body"]["detail"];
				}else{
					
					$rel['erjikeshi'] = $rel["body"]["detail"]; // 当有多个医生列表直接赋值
				}
				$this->ajaxReturn($rel,"JSON");
			}else{
				 // var_dump($rel);
				 // exit;
				$this->ajaxReturn($rel,"JSON");
			}
		}
	}
}
//获取普通科室排班列表
public function get_paiban_list(){
    $regclass = I("post.regclass");	
    $card_no = I("post.card_no");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$zzj_id =  I("post.zzj_id");
    $ksdm   =  I("post.ksdm");
	 // 查询HIS服务器时间SQL
	$str = '<?xml version="1.0" encoding="GBK" ?>
		<root>
			<head>
				<actdate>20131203</actdate>
				<trdate>20131203</trdate>
				<trtime>082743</trtime>
				<trcode>Z000</trcode>
			</head>
		</root>';	
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
	      $rel['head']['succflag']="2";
	      $rel['head']['retmsg']="请求超时,请重试";
	      $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result5= str_replace("gbk","utf8",$result2);
		$result3 =  str_replace("<*", "", $result5);
		$doc = simplexml_load_string($result3);
		$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    $His_time =  $rels["body"]["CurrentDateTime"];	     
	    //HIS服务器时间 如2017-11-23 10:37:30
	    $meddate  = I("post.chose_time");	   
		$str = '<?xml version="1.0" encoding="GBK" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>090023</trtime>
						<trcode>Z206</trcode>
					</head>
					<body>
						<regtype>2</regtype>
						<regclass>'.$regclass.'</regclass>
						<meddate>'.$meddate.'</meddate>
						<streak>9</streak>
						<departmentid>'.$ksdm.'</departmentid>
						<ksdj>2</ksdj>
					</body>
				</root>';
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取该二级科室下的预约医生列表";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/    	
		//实例化socket
		$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		$socketport = "2013";
		$socket = \Socket::singleton();
		//链接
		$aa = $socket->connect($socketaddr,$socketport);
		//写数据
		$sockresult = $socket->sendrequest($str);
		//读数据
		$response = $socket->waitforresponse();
		$socket->disconnect (); //关闭链接
		if($response == "" || $response == false){
			$rel['head']['succflag']="2";
			$rel['head']['retmsg']="请求超时,请重试";
			$this->ajaxReturn($rel,"JSON");
	    }else{
	    	$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result3 = str_replace("gbk","utf8",$result2);	
			$doc = simplexml_load_string($result3);
			$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		    /**************日记记录开始**************/
			$data['card_code'] = I("post.card_code");
			$data['patient_id'] = I("post.patient_id");
			$data['card_no'] = I("post.card_no");
			$data['op_name'] = "获取该二级科室下的预约医生列表";
			$data['direction'] = "返回报文";
			$data['op_code'] = I("post.op_code");
			$data['op_xml'] = $result3;
			$data['zzj_id'] = I("post.zzj_id");
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/ 
			   //判短his是否返回成功
			if($rel["head"]["succflag"] == "2"){	  	  
			  	   $this->ajaxReturn($rel,"JSON");
			}else{
			  	$res["head"]["succflag"]="1";
			  	if($rel["body"]["rowcount"]=="0"){
		            $res['doctor'] ="";         // 当专家列表为空
			   	}elseif($rel["body"]["rowcount"]=="1"){
					//获取上下午
					if($rel["body"]["detail"]["streak"] == "1"){
						$rel["body"]["detail"]["streak"] ="上午";
					}elseif ($rel["body"]["detail"]["streak"] == "2") {
						$rel["body"]["detail"]["streak"] ="下午";
					}          
				  	// 当专家列表只有一个  转为二位数组
					$res['doctor']["0"] =  $rel["body"]["detail"];
				}else{
					for($i=0;$i<$rel["body"]["rowcount"];$i++){
						if($rel["body"]["detail"][$i]["streak"] == "1"){
						   $rel["body"]["detail"][$i]["streak"] ="上午";
					    }elseif($rel["body"]["detail"][$i]["streak"] == "2") {		    	
						   $rel["body"]["detail"][$i]["streak"] ="下午";
					    }	
					}
					$res['doctor'] = $rel["body"]["detail"]; // 当有多个医生列表直接赋值
				}
		       $this->ajaxReturn($res,"JSON");
			}
	    }
	}
}

// 医保卡获取病人信息
public function yibao_getPatInfo(){
 	$actno = I("post.card_no");  //卡号
 	$acttype = I("post.card_code"); //卡类别 	
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
 	$str = '<?xml version="1.0" encoding="GBK" ?>
		<root>
			<head>
				<actdate>'.date("Ymd",time()).'</actdate>
				<trdate>'.date("Ymd",time()).'</trdate>
				<trtime>'.date("His",time()).'</trtime>
				<trcode>Z100</trcode>
			</head>
			<body>
				<acttype>1</acttype>
				<actno>\</actno>
				<medpos></medpos>
			</body>
		</root>';
	/**************日记记录开始**************/
	$data['card_no'] = I("post.card_no");
	$data['card_code'] = I("post.card_code");		
	$data['op_name'] = "获取医保卡患者信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/		
	//实例化socket
	//$zzj_id ="zzj001";
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
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
        $rel['head']['succflag']="2";
        $rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);
	/*	$result3 = '<?xml version="1.0" encoding="utf8"?><root><head><actdate>20190114</actdate><trdate>20190114</trdate><trtime>134831</trtime><trcode>Z100</trcode><hisseq>0</hisseq><bhpseq></bhpseq><jdseq></jdseq><filenum>0</filenum><succflag>2</succflag><retcode>110</retcode><retmsg>该诊疗卡未建档!</retmsg></head><body></body></root>';*/
		$doc = simplexml_load_string($result3);
		// \Log::Write("jiafei".$result3."2222");
		$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		/*************日记记录开始*************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] =I("post.patient_id"); //获取病案号 病人唯一id
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取医保卡患者信息";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		if($rel['head']['succflag']=="1"){
			$rel['yijikeshi'] = $this->yijikeshi_list();
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}
}
/***********************医保卡更新保存****************************/

public function jianka_yb_save_gx(){
	$op_code = I("post.op_code");
	$zzj_id = I("post.zzj_id");
	$patient_phone = I("post.patient_phone");
	$$yibao_address = I("post.yibao_address");
	
	$str = '<?xml version="1.0" encoding="utf-8" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>085938</trtime>
						<trcode>Z103</trcode>
					</head>
					<body>
						<acttype>1</acttype>
						<actno>\</actno>
						<medpos></medpos>
						<hopno></hopno>
						<phone>'.$patient_phone.'</phone>
						<addr>'.$yibao_address.'</addr>
					</body>
				</root>
				';
	/**************日记记录开始**************/
	$data['card_code'] = "1";
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.patient_id");
	$data['op_name'] = "医保建卡保存";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
    //$socketaddr = "172.16.77.18";
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
       	$rel['head']['succflag']="2";
		$rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);	
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);

		/**************日记记录开始**************/
		$data['card_code'] = "1";
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.patient_id");
		$data['op_name'] = "医保建卡保存";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		if($rel['head']['succflag']=="1"){
			// $rel['yijikeshi'] = $this->yijikeshi_list();
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}
}
/**********挂号患者信息查询*******************************/
/***********************医保卡建卡保存****************************/
public function jianka_yb_save(){
	$op_code = I("post.op_code");
	$zzj_id = I("post.zzj_id");
	$patient_phone = I("post.patient_phone");
	$patient_type = I("post.patient_type");
	$patient_name = I("post.patient_name");
	$patient_sex = I("post.patient_sex");
	if($patient_sex=="男"){
		$patient_sex = "0";
	}else{
		$patient_sex = "1";
	}
	$patient_birthday = I("post.patient_birthday");
	$d=strtotime($patient_birthday);
	$patient_birthday = date("Y-m-d",$d);
	$patient_address = trim(I("post.patient_address"));
	$card_no = $patient_sfz;
	$card_code = I("post.card_code");
	$sfz_jianka_yibaokahao = I("sfz_jianka_yibaokahao");
	$str = '<?xml version="1.0" encoding="gbk" ?>
		<root>
			<Request>
				<Header>
					<UserID></UserID>
					<PassWord></PassWord>
					<DeviceID>'.$zzj_id.'</DeviceID>
					<AppCode>01</AppCode>
					<AppTypeCode>01</AppTypeCode>
					<trcode>Z1202</trcode>
					<ReqTime>'.date("Y-m-d H:i:s",time()).'</ReqTime>
					<ReqTraceNo>'.date("YmdHis",time()).$zzj_id.'</ReqTraceNo>
				</Header>
				<Body>
					<CardTypeCode>1</CardTypeCode>
					<CardNo>\</CardNo>
					<BankNo></BankNo>
					<SecurityNo></SecurityNo>
					<PassWordType></PassWordType>
					<PassWordValue></PassWordValue>
					<Name></Name>
					<Sex></Sex>
					<Birthday></Birthday>
					<Age></Age>
					<IDCardType>1</IDCardType>
					<IDCardNo></IDCardNo>
					<Nationality></Nationality>
					<Nation></Nation>
					<Address></Address>
					<PhoneNo>'.$patient_phone.'</PhoneNo>
					<RelativeName></RelativeName>
					<RelativePhoneNo></RelativePhoneNo>
					<Amt></Amt>
					<PayType></PayType>
					<PayInfo>
						<PosID></PosID>
						<BankCardNo></BankCardNo>
						<PayDate></PayDate>
						<PayTime></PayTime>
						<BatchNo></BatchNo>
						<VouchNo></VouchNo>
						<ReferNo></ReferNo>
						<PayAmt></PayAmt>
						<BankCode></BankCode>
					</PayInfo>
				</Body>
			</Request>
		</root>';
	/**************日记记录开始**************/
	$data['card_code'] = "1";
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = $patient_sfz;
	$data['op_name'] = "医保建卡保存";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
    //$socketaddr = "172.16.77.18";
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
       	$rel['head']['succflag']="2";
		$rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);	
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);

		/**************日记记录开始**************/
		$data['card_code'] = "1";
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = $patient_sfz;
		$data['op_name'] = "医保建卡保存";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		if($rel['head']['succflag']=="0"){
			$rel['yijikeshi'] = $this->yijikeshi_list();
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}
}
// 医保卡获取病人信息
public function yibao_getPat_lb_Info(){

 	$actno = I("post.card_no");  //卡号
 	$acttype = I("post.card_code"); //卡类别 	
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
 	$str = '<?xml version="1.0" encoding="GBK" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>085938</trtime>
						<trcode>YQXYYZZJ110</trcode>
					</head>
					<body>
						<acttype>'.$acttype.'</acttype>
						<actno>\</actno>
						<medpos></medpos>
					</body>
				</root>';

	/**************日记记录开始**************/
	$data['card_no'] = I("post.card_no");
	$data['card_code'] = I("post.card_code");		
	$data['op_name'] = "获取医保卡患者信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
		
	//实例化socket
	//$zzj_id ="zzj001";
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
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
        $rel['head']['succflag']="2";
        $rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);	
		$doc = simplexml_load_string($result3);
		$result = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		/*************日记记录开始*************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = $rel["body"]["medid"]; //获取病案号 病人唯一id
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取医保卡患者信息";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		$this->ajaxReturn($result,"JSON");

	}
	
}
/**********************查询专家科室列表*****************************/
public function zj_keshi_list(){
    $regclass = I("post.regclass");	
    $card_no = I("post.card_no");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$zzj_id =  I("post.zzj_id");

    // 查询HIS服务器时间
	$str = '<?xml version="1.0" encoding="GBK" ?>
	<root>
		<head>
			<actdate>20131203</actdate>
			<trdate>20131203</trdate>
			<trtime>082743</trtime>
			<trcode>YQXYYZZJ000</trcode>
		</head>
	</root>';
		
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
        $rel['head']['succflag']="2";
        $rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);
		$doc = simplexml_load_string($result3);
		$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    $His_time =  $rels["body"]["CurrentDateTime"];
	    //HIS服务器时间 如2017-11-23 10:37:30
		$fwqsj = $His_time;
		// HIS服务器的时间戳
		$time = strtotime($fwqsj);
		date_default_timezone_set('PRC');
		
		// 获取当前上下午时间戳
		$shangwu0 = date("Y-m-d",$time)." "."00:00:00";
	   	$shangwu = date("Y-m-d",$time)." "."12:00:00";	     
	   	$xiawu0 = date("Y-m-d",$time)." "."12:01:00";
	   	$xiawu = date("Y-m-d",$time)." "."22:30:00";
        
        $shangwu_start = strtotime($shangwu0);
	   	$shangwu_end = strtotime($shangwu);
		$xiawu_start = strtotime($xiawu0);
		$xiawu_end =  strtotime($xiawu);
		if($time>$shangwu_start&&$time<$shangwu_end){ //1是上午,2是下午
			$streak ="1";
		}else if($time>$xiawu_start&&$time<$xiawu_end){
			$streak ="2";
		}else{
			$rel['head']['succflag']="2";
	        $rel['head']['retmsg']="当前不在挂号时间段,挂号时间上午00:00-11:00,下午1:30-16:30";
	        $this->ajaxReturn($rel,"JSON");
   
		}
	    $meddate  = date("Y-m-d",$time);	
		$str = '<?xml version="1.0" encoding="GBK" ?>
					<root>
						<head>
							<actdate>20131203</actdate>
							<trdate>20131203</trdate>
							<trtime>090022</trtime>
							<trcode>YQXYYZZJ205</trcode>
						</head>
						<body>
							<regtype>1</regtype>
							<regclass>'.$regclass.'</regclass>
							<meddate>'.$meddate.'</meddate>
							<streak>'.$streak.'</streak>
						</body>
					</root>';
	   
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取科室列表";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/	
		//实例化socket
		$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		$socketport = "2013";
		$socket = \Socket::singleton();
		//链接
		$aa = $socket->connect($socketaddr,$socketport);
		
		//写数据
		$sockresult = $socket->sendrequest($str);
		//读数据
		$response = $socket->waitforresponse();
		$socket->disconnect (); //关闭链接
		if($response == ""  || $response == false){
	        $rel['head']['succflag']="2";
	        $rel['head']['retmsg']="请求超时,请重试";
	        $this->ajaxReturn($rel,"JSON");
		}else{
			$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result5 = str_replace("gbk","utf8",$result2);	
			$result3 =  str_replace("<*", "", $result5);	
			$doc = simplexml_load_string($result3);
			$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
			 
			/**************日记记录开始**************/
			$data['card_code'] = I("post.card_code");
			$data['patient_id'] = I("post.patient_id");
			$data['card_no'] = I("post.card_no");
			$data['op_name'] = "获取科室列表";
			$data['direction'] = "返回报文";
			$data['op_code'] = I("post.op_code");
			$data['op_xml'] = $result3;
			$data['zzj_id'] = I("post.zzj_id");
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
               //过滤不需要科室  产科建党21003    门特病235   口腔门诊213  便民225  病理239 老干部238  产科门诊21002
			$arr =  $rel["body"]["detail"];
			$count = count($arr);
		     for ($i=0; $i<$count ; $i++) { 

		        $id =$arr[$i]["departmentid"]; //获取科室id
		        if($id == "21003"||$id == "213"||$id == "235"||$id == "225"|| $id == "239"|| $id == "238" || $id == "21002"){
		        	$rel["body"]["detail"][$i] = "0";
		        }
		        
		     }
		     $rel["body"]["detail"] = array_filter($rel["body"]["detail"]);
		     $rel["body"]["detail"] = array_values($rel["body"]["detail"]);
		     $relsss =  $this->_array_column($rel["body"]["detail"],"departmentid");
             //数组排序
            array_multisort($relsss,SORT_ASC,$rel["body"]["detail"]);
			if($rel["head"]["succflag"]=="1"){
		        // $res["head"]["succflag"]="1";
				$rel['erjikeshi'] = $rel["body"]["detail"];
				  
		        $this->ajaxReturn($rel,"JSON");
			}else{
				 // var_dump($rel);
				 // exit;
				$this->ajaxReturn($rel,"JSON");
			}


		}

	}
		
	
	
}

/**********************查询普通科室列表*****************************/
public function pt_keshi_list(){
    $regclass = I("post.regclass");	
    $card_no = I("post.card_no");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$zzj_id =  I("post.zzj_id");

    // 查询HIS服务器时间
	$str = '<?xml version="1.0" encoding="GBK" ?>
	<root>
		<head>
			<actdate>20131203</actdate>
			<trdate>20131203</trdate>
			<trtime>082743</trtime>
			<trcode>YQXYYZZJ000</trcode>
		</head>
	</root>';
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
	      $rel['head']['succflag']="2";
	      $rel['head']['retmsg']="请求超时,请重试";
	      $this->ajaxReturn($rel,"JSON");
	}else{
        $response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);
		$doc = simplexml_load_string($result3);
		$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    $His_time =  $rels["body"]["CurrentDateTime"];
	    //HIS服务器时间 如2017-11-23 10:37:30
		$fwqsj = $His_time;
		// HIS服务器的时间戳
		$time = strtotime($fwqsj);		
		date_default_timezone_set('PRC');
		// 获取当前上下午时间戳
		$shangwu0 = date("Y-m-d",$time)." "."7:30:00";
	   	$shangwu = date("Y-m-d",$time)." "."12:00:00";	     
	   	$xiawu0 = date("Y-m-d",$time)." "."12:01:00";
	   	$xiawu = date("Y-m-d",$time)." "."22:30:00";
        
        $shangwu_start = strtotime($shangwu0);
	   	$shangwu_end = strtotime($shangwu);
		$xiawu_start = strtotime($xiawu0);
		$xiawu_end =  strtotime($xiawu);
		if($time>$shangwu_start&&$time<$shangwu_end){ //1是上午,2是下午
			$streak ="1";
		}else if($time>$xiawu_start&&$time<$xiawu_end){
			$streak ="2";
		}else{
			$rel['head']['succflag']="2";
	        $rel['head']['retmsg']="当前不在挂号时间段,挂号时间上午7:30-11:30,下午1:30-17:30";
	        $this->ajaxReturn($rel,"JSON");
   
		}
	    $meddate  = date("Y-m-d",$time);
		$str = '<?xml version="1.0" encoding="GBK" ?>
					<root>
						<head>
							<actdate>20131203</actdate>
							<trdate>20131203</trdate>
							<trtime>090022</trtime>
							<trcode>YQXYYZZJ205</trcode>
						</head>
						<body>
							<regtype>1</regtype>
							<regclass>'.$regclass.'</regclass>
							<meddate>'.$meddate.'</meddate>
							<streak>'.$streak.'</streak>
						</body>
					</root>';
	   
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取科室列表";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/	
		//实例化socket
		$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		$socketport = "2013";
		$socket = \Socket::singleton();
		//链接
		$aa = $socket->connect($socketaddr,$socketport);
		
		//写数据
		$sockresult = $socket->sendrequest($str);
		//读数据
		$response = $socket->waitforresponse();
		$socket->disconnect (); //关闭链接
		if($response == "" || $response == false){
	      $rel['head']['succflag']="2";
	      $rel['head']['retmsg']="请求超时,请重试";
	      $this->ajaxReturn($rel,"JSON");
		}else{
			$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result5 = str_replace("gbk","utf8",$result2);	
			$result3 =  str_replace("<*", "", $result5);
			$doc = simplexml_load_string($result3);
			$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
			 
			/**************日记记录开始**************/
			$data['card_code'] = I("post.card_code");
			$data['patient_id'] = I("post.patient_id");
			$data['card_no'] = I("post.card_no");
			$data['op_name'] = "获取科室列表";
			$data['direction'] = "返回报文";
			$data['op_code'] = I("post.op_code");
			$data['op_xml'] = $result3;
			$data['zzj_id'] = I("post.zzj_id");
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
		    //过滤不需要科室  产科建党21003    门特病235   口腔门诊213  便民225  病理239 老干部238  产科门诊21002
			$arr =  $rel["body"]["detail"];
			$count = count($arr);
		     for ($i=0; $i<$count ; $i++) { 

		        $id =$arr[$i]["departmentid"]; //获取科室id
		        if($id == "21003"||$id == "213"||$id == "235"||$id == "225"|| $id == "239"|| $id == "238" || $id == "21002"){
		        	$rel["body"]["detail"][$i] = "0";
		        }
		        
		     }
		     $rel["body"]["detail"] = array_filter($rel["body"]["detail"]);
		     $rel["body"]["detail"] = array_values($rel["body"]["detail"]);
		     $relsss =  $this->_array_column($rel["body"]["detail"],"departmentid");
             //数组排序
            array_multisort($relsss,SORT_ASC,$rel["body"]["detail"]);
			if($rel["head"]["succflag"]=="1"){
		        // $res["head"]["succflag"]="1";
				$rel['erjikeshi'] = $rel["body"]["detail"];
		        $this->ajaxReturn($rel,"JSON");
			}else{
				 $this->ajaxReturn($rel,"JSON");
			}


     }
		
	}
		
	
	
}
//数组排序
public  function _array_column(array $array, $column_key, $index_key=null){
            $result = array();
            foreach($array as $arr) {
                if(!is_array($arr)) continue;

                if(is_null($column_key)){
                    $value = $arr;
                }else{
                    $value = $arr[$column_key];
                }

                if(!is_null($index_key)){
                    $key = $arr[$index_key];
                    $result[$key] = $value;
                }else{
                    $result[] = $value;
                }
              }
              return $result; 
            }

//获取专家列表
public function get_zj_paiban_list(){
    $regclass = I("post.regclass");	
    $card_no = I("post.card_no");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$zzj_id =  I("post.zzj_id");
    $ksdm   =  I("post.ksdm");
	 // 查询HIS服务器时间SQL
	$str = '<?xml version="1.0" encoding="GBK" ?>
	<root>
		<head>
			<actdate>20131203</actdate>
			<trdate>20131203</trdate>
			<trtime>082743</trtime>
			<trcode>Z000</trcode>
		</head>
	</root>';
		
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
	      $rel['head']['succflag']="2";
	      $rel['head']['retmsg']="请求超时,请重试";
	      $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);
		$doc = simplexml_load_string($result3);
		$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    $His_time =  $rels["body"]["CurrentDateTime"];
	    
	     
	    //HIS服务器时间 如2017-11-23 10:37:30
		$fwqsj = $His_time;
		// HIS服务器的时间戳
		$time = strtotime($fwqsj);
		
		date_default_timezone_set('PRC');
		
		// 获取当前上下午时间戳
		$shangwu0 = date("Y-m-d",$time)." "."7:30:00";
	   	$shangwu = date("Y-m-d",$time)." "."12:00:00";	     
	   	$xiawu0 = date("Y-m-d",$time)." "."12:01:00";
	   	$xiawu = date("Y-m-d",$time)." "."22:30:00";
        
        $shangwu_start = strtotime($shangwu0);
	   	$shangwu_end = strtotime($shangwu);
		$xiawu_start = strtotime($xiawu0);
		$xiawu_end =  strtotime($xiawu);
		if($time>$shangwu_start&&$time<$shangwu_end){ //1是上午,2是下午
			$streak ="1";
		}else if($time>$xiawu_start&&$time<$xiawu_end){
			$streak ="2";
		}else{
			$rel['head']['succflag']="2";
	        $rel['head']['retmsg']="当前不在挂号时间段";
	        $this->ajaxReturn($rel,"JSON");
   
		}
	  
	    $meddate  = date("Y-m-d",$time);	   
		$str = '<?xml version="1.0" encoding="GBK" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>090023</trtime>
						<trcode>Z206</trcode>
					</head>
					<body>
						<regtype>1</regtype>
						<regclass>1</regclass>
						<meddate>'.$meddate.'</meddate>
						<streak>'.$streak.'</streak>
						<departmentid>'.$ksdm.'</departmentid>
					</body>
				</root>';
		
		
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取该二级科室下的专家列表";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/    	
		//实例化socket
		$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		$socketport = "2013";
		$socket = \Socket::singleton();
		//链接
		$aa = $socket->connect($socketaddr,$socketport);
		
		//写数据
		$sockresult = $socket->sendrequest($str);
		//读数据
		$response = $socket->waitforresponse();
		$socket->disconnect (); //关闭链接
		if($response == ""){
	      $rel['head']['succflag']="2";
	      $rel['head']['retmsg']="请求超时,请重试";
	      $this->ajaxReturn($rel,"JSON");
	    }else{
	    	    $response = strstr($response,'<');
				$temp=iconv("gbk","utf-8//IGNORE",$response);
				$result1 = ltrim($temp,"^");
				$result2 = strstr($result1,'<');
				$result5 = str_replace("gbk","utf8",$result2);
				$result3 =  str_replace("<*", "", $result5);	
				$doc = simplexml_load_string($result3);
				$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码

			    /**************日记记录开始**************/
				$data['card_code'] = I("post.card_code");
				$data['patient_id'] = I("post.patient_id");
				$data['card_no'] = I("post.card_no");
				$data['op_name'] = "获取该二级科室下的专家列表";
				$data['direction'] = "返回报文";
				$data['op_code'] = I("post.op_code");
				$data['op_xml'] = $result3;
				$data['zzj_id'] = I("post.zzj_id");
				M("logs")->add($data);
				$this->writeLog($log_txt,$log_type);
				/**********日志记录结束*******************/ 

				
				   //判短his是否返回成功
				  if($rel["head"]["succflag"] == "2"){	  	  
				  	   $this->ajaxReturn($rel,"JSON");
				  }else{
				  	 $res["head"]["succflag"]="1";
				  	 if($rel["body"]["rowcount"]=="0"){
			            $res['doctor'] ="";         // 当专家列表为空

				     }elseif($rel["body"]["rowcount"]=="1"){

						//获取上下午
						if($rel["body"]["detail"]["streak"] == "1"){
							$rel["body"]["detail"]["streak"] ="上午";
						}elseif ($rel["body"]["detail"]["streak"] == "2") {
							$rel["body"]["detail"]["streak"] ="下午";
						}          
					  	// 当专家列表只有一个  转为二位数组
						$res['doctor']["0"] =  $rel["body"]["detail"];
					}else{ 

						if($regclass=="2"){
							 //获取上下午
							foreach ($rel["body"]["detail"] as $key => $value) {  //专家列表
								if($value["streak"] == "1"){
								  $rel["body"]["detail"][$key]["streak"] ="上午";
							    }elseif ($value["streak"] == "2") {		    	
								   $rel["body"]["detail"][$key]["streak"] ="下午";

							    }
								
							}
			               $res['doctor'] = $rel["body"]["detail"]; // 当专家列表有多个 直接赋值

						}else{
								//获取上下午
							if($rel["body"]["detail"]["streak"] == "1"){   //普通号列表
								$rel["body"]["detail"]["streak"] ="上午";
							}elseif ($rel["body"]["detail"]["streak"] == "2") {
								$rel["body"]["detail"]["streak"] ="下午";
							}          
						  	// 当专家列表只有一个  转为二位数组
							$res['doctor']["0"] =  $rel["body"]["detail"];

						}

					} 
					
			       $this->ajaxReturn($res,"JSON");

				}

	    }
		
	 

	}
	

}


//获取普通科室排班列表
public function get_pt_paiban_list(){
    $regclass = I("post.regclass");	
    $card_no = I("post.card_no");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$zzj_id =  I("post.zzj_id");
    $ksdm   =  I("post.ksdm");
	 // 查询HIS服务器时间SQL
	$str = '<?xml version="1.0" encoding="GBK" ?>
	<root>
		<head>
			<actdate>20131203</actdate>
			<trdate>20131203</trdate>
			<trtime>082743</trtime>
			<trcode>YQXYYZZJ000</trcode>
		</head>
	</root>';
		
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
	      $rel['head']['succflag']="2";
	      $rel['head']['retmsg']="请求超时,请重试";
	      $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result5= str_replace("gbk","utf8",$result2);
		$result3 =  str_replace("<*", "", $result5);
		$doc = simplexml_load_string($result3);
		$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    $His_time =  $rels["body"]["CurrentDateTime"];	     
	    //HIS服务器时间 如2017-11-23 10:37:30
		$fwqsj = $His_time;
		// HIS服务器的时间戳
		$time = strtotime($fwqsj);		
		date_default_timezone_set('PRC');
		
		// 获取当前上下午时间戳
		$shangwu0 = date("Y-m-d",$time)." "."7:30:00";
	   	$shangwu = date("Y-m-d",$time)." "."12:00:00";	     
	   	$xiawu0 = date("Y-m-d",$time)." "."12:01:00";
	   	$xiawu = date("Y-m-d",$time)." "."22:30:00";
        
        $shangwu_start = strtotime($shangwu0);
	   	$shangwu_end = strtotime($shangwu);
		$xiawu_start = strtotime($xiawu0);
		$xiawu_end =  strtotime($xiawu);
		if($time>$shangwu_start&&$time<$shangwu_end){ //1是上午,2是下午
			$streak ="1";
		}else if($time>$xiawu_start&&$time<$xiawu_end){
			$streak ="2";
		}else{
			$rel['head']['succflag']="2";
	        $rel['head']['retmsg']="当前不在挂号时间段";
	        $this->ajaxReturn($rel,"JSON");
   
		}
	    $meddate  = date("Y-m-d",$time);	   
		$str = '<?xml version="1.0" encoding="GBK" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>090023</trtime>
						<trcode>YQXYYZZJ206</trcode>
					</head>
					<body>
						<regtype>1</regtype>
						<regclass>'.$regclass.'</regclass>
						<meddate>'.$meddate.'</meddate>
						<streak>'.$streak.'</streak>
						<departmentid>'.$ksdm.'</departmentid>
					</body>
				</root>';
		
		
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "获取该二级科室下的医生列表";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/    	
		//实例化socket
		$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		$socketport = "2013";
		$socket = \Socket::singleton();
		//链接
		$aa = $socket->connect($socketaddr,$socketport);
		
		//写数据
		$sockresult = $socket->sendrequest($str);
		//读数据
		$response = $socket->waitforresponse();
		$socket->disconnect (); //关闭链接
		if($response == "" || $response == false){
	      $rel['head']['succflag']="2";
	      $rel['head']['retmsg']="请求超时,请重试";
	      $this->ajaxReturn($rel,"JSON");
	    }else{
	    	$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result3 = str_replace("gbk","utf8",$result2);	
			$doc = simplexml_load_string($result3);
			$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码

		    /**************日记记录开始**************/
			$data['card_code'] = I("post.card_code");
			$data['patient_id'] = I("post.patient_id");
			$data['card_no'] = I("post.card_no");
			$data['op_name'] = "获取该二级科室下的医生列表";
			$data['direction'] = "返回报文";
			$data['op_code'] = I("post.op_code");
			$data['op_xml'] = $result3;
			$data['zzj_id'] = I("post.zzj_id");
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/ 

			
			   //判短his是否返回成功
			  if($rel["head"]["succflag"] == "2"){	  	  
			  	   $this->ajaxReturn($rel,"JSON");
			  }else{
			  	 $res["head"]["succflag"]="1";
			  	 if($rel["body"]["rowcount"]=="0"){
		            $res['doctor'] ="";         // 当专家列表为空

			     }elseif($rel["body"]["rowcount"]=="1"){

					//获取上下午
					if($rel["body"]["detail"]["streak"] == "1"){
						$rel["body"]["detail"]["streak"] ="上午";
					}elseif ($rel["body"]["detail"]["streak"] == "2") {
						$rel["body"]["detail"]["streak"] ="下午";
					}          
				  	// 当专家列表只有一个  转为二位数组
					$res['doctor']["0"] =  $rel["body"]["detail"];

				

				}else{ 

					if($regclass=="2"){
						 //获取上下午
						foreach ($rel["body"]["detail"] as $key => $value) {  //专家列表
							if($value["streak"] == "1"){
							  $rel["body"]["detail"][$key]["streak"] ="上午";
						    }elseif ($value["streak"] == "2") {		    	
							   $rel["body"]["detail"][$key]["streak"] ="下午";

						    }
							
						}
		               $res['doctor'] = $rel["body"]["detail"]; // 当专家列表有多个 直接赋值

					}else{
							//获取上下午
						if($rel["body"]["detail"]["streak"] == "1"){   //普通号列表
							$rel["body"]["detail"]["streak"] ="上午";
						}elseif ($rel["body"]["detail"]["streak"] == "2") {
							$rel["body"]["detail"]["streak"] ="下午";
						}          
					  	// 当专家列表只有一个  转为二位数组
						$res['doctor']["0"] =  $rel["body"]["detail"];

					}
				         
				     
					

				} 
				
		       $this->ajaxReturn($res,"JSON");

			  }

	    }
		
	}
	
      

}


//就诊卡划价
public  function  ic_guahao_huajia(){
    $card_no =  I("post.card_no");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$zzj_id =  I("post.zzj_id");
    $medid =   I("post.patient_id");
    $meddate =   I("post.meddate");
    $streak =   I("post.streak");
    $medpay =  I("post.medpay");
    $orderid =   I("post.orderid");
    
    $preid =   I("post.preid");
    if($streak =="上午"){
        $streak="1";
     }elseif($streak =="下午"){
        $streak="2";
     }else{
        $streak="";
     }
   $str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20131203</actdate>
					<trdate>20131203</trdate>
					<trtime>085946</trtime>
					<trcode>Z213</trcode>
				</head>
				<body>
					<acttype>'.$card_code.'</acttype>
					<actno>'.$card_no.'</actno>
					<medid>'.$card_no.'</medid>
					<meddate>'.$meddate.'</meddate>
					<streak>'.$streak.'</streak>
					<medpay>'.$medpay.'</medpay>
					<orderid>'.$orderid.'</orderid>
					<seqnum></seqnum>
					<regtype>1</regtype>
					<preid>1</preid>
				</body>
			</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "就诊卡挂号划价信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/ 	
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
	      $rel['head']['succflag']="2";
	      $rel['head']['retmsg']="请求超时,请重试";
	      $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);
		
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    /**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "就诊卡挂号划价信息";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/ 
	   $this->ajaxReturn($rel,"JSON");
	}
}
//身份证划价
public  function  sfz_guahao_huajia(){
    $card_no =  I("post.card_no");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$zzj_id =  I("post.zzj_id");
    $medid =   I("post.patient_id");
    $meddate =   I("post.meddate");
    $streak =   I("post.streak");
    $medpay =  I("post.medpay");
    $orderid =   I("post.orderid");
    $preid =   I("post.preid");
    if($streak =="上午"){
        $streak="1";
    }elseif($streak =="下午"){
        $streak="2";
    }else{
        $streak="";
    }
   	$str = '<?xml version="1.0" encoding="GBK" ?>
		<root>
			<head>
				<actdate>20131203</actdate>
				<trdate>20131203</trdate>
				<trtime>085946</trtime>
				<trcode>Z213</trcode>
			</head>
			<body>
				<acttype>'.$card_code.'</acttype>
				<actno>'.$card_no.'</actno>
				<medid>'.$medid.'</medid>
				<meddate>'.$meddate.'</meddate>
				<streak>'.$streak.'</streak>
				<medpay>'.$medpay.'</medpay>
				<orderid>'.$orderid.'</orderid>
				<seqnum></seqnum>
				<regtype>1</regtype>
			</body>
		</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "身份证挂号划价信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/ 	
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
	      $rel['head']['succflag']="2";
	      $rel['head']['retmsg']="请求超时,请重试";
	      $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);
		
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    /**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "身份证挂号划价信息";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/ 
	   $this->ajaxReturn($rel,"JSON");
	}
}
//医保卡划价
public  function  yb_guahao_huajia(){
    $card_no =  I("post.card_no");
	$card_code = I("post.card_code");
	$op_code = I("post.op_code");
	$zzj_id =  I("post.zzj_id");
    $medid =   I("post.patient_id");
    $meddate =   I("post.meddate");
    $streak =   I("post.streak");
    $medpay =  I("post.medpay");
    $orderid =   I("post.orderid");   
    $preid =   I("post.preid");
    if($streak =="上午"){
        $streak="1";
     }elseif($streak =="下午"){
        $streak="2";
     }else{
        $streak="";
     }
   $str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20131203</actdate>
					<trdate>20131203</trdate>
					<trtime>085946</trtime>
					<trcode>Z213</trcode>
				</head>
				<body>
					<acttype>'.$card_code.'</acttype>
					<actno>\</actno>
					<medid>'.$medid.'</medid>
					<meddate>'.$meddate.'</meddate>
					<streak>'.$streak.'</streak>
					<medpay>'.$medpay.'</medpay>
					<orderid>'.$orderid.'</orderid>
					<seqnum></seqnum>
					<regtype>1</regtype>
				</body>
			</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "医保卡挂号划价信息";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/ 	
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
	      $rel['head']['succflag']="2";
	      $rel['head']['retmsg']="请求超时,请重试";
	      $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);
		
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    /**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "医保卡挂号划价信息";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $result3;
		$data['zzj_id'] = I("post.zzj_id");

		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/ 
		 // $acttype ="3";
		 // $Personcount = $this->yibao_get($acttype);
		 // $rel["body"]["Personcount"]= $Personcount; 
        $this->ajaxReturn($rel,"JSON");
         

	}
	
}


// 医保卡获取病人信息
public function yibao_gets($acttype){

 	
 	$str = '<?xml version="1.0" encoding="GBK" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>085938</trtime>
						<trcode>YQXYYZZJ100</trcode>
					</head>
					<body>
						<acttype>3</acttype>
						<actno>\</actno>
						<medpos></medpos>
					</body>
				</root>';

	// /**************日记记录开始**************/
	// $data['card_no'] = I("post.card_no");
	// $data['card_code'] = I("post.card_code");		
	// $data['op_name'] = "获取医保卡患者信息";
	// $data['direction'] = "发送报文";
	// $data['op_code'] = I("post.op_code");
	// $data['op_xml'] = $str;
	// $data['zzj_id'] = I("post.zzj_id");
	// M("logs")->add($data);
	// $this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
		
	//实例化socket
	//$zzj_id ="zzj001";
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
	$socket->disconnect (); //关闭链接
	
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);	
		$doc = simplexml_load_string($result3);
		$result = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		/*************日记记录开始*************/
		// $data['card_code'] = I("post.card_code");
		// $data['patient_id'] = $rel["body"]["medid"]; //获取病案号 病人唯一id
		// $data['card_no'] = I("post.card_no");
		// $data['op_name'] = "获取医保卡患者信息";
		// $data['direction'] = "返回报文";
		// $data['op_code'] = I("post.op_code");
		// $data['op_xml'] = $result3;
		// $data['zzj_id'] = I("post.zzj_id");
		// M("logs")->add($data);
		// $this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		//$this->ajaxReturn($result,"JSON");
		 $Personcount = $result["body"]["Personcount"];
		return  $Personcount;

	
	
}

//银行卡支付
public  function yhk_pay(){
	 $bank_info = I("post.bank_info");
     $total_amount =  I("post.total_amount");
     $out_trade_no =  I("post.out_trade_no"); //银行交易单号
     $subject =  I("post.subject");
     $patient_id =  I("post.patient_id");
	 $RespCode = substr($bank_info, 0,2); //交易成功码
	 if( $RespCode == "00"){
	 	$RespInfo = "交易成功";
	 }else{
	 	$RespInfo = "交易失败";
	 }
     $idCard = substr($bank_info, 2,19);  //银行卡号
     $flag = substr($bank_info, 21,1);  //交易类型标志
     $Amount = substr($bank_info, 22,12); //金额
     $fk_no = substr($bank_info, 34,4); //发卡行代码
     $pz_no = substr($bank_info, 38,6); //凭证号
     $sc_no = substr($bank_info, 44,6); //授权号
     $pc_no = substr($bank_info, 50,6); //批次号
     $jy_no = substr($bank_info, 56,14); //交易日期时间
     $ck_no = substr($bank_info,70,12); //参考号

     /**************日记记录开始**************/
	$data['RespCode'] = $RespCode; //交易成功码
	$data['RespInfo'] = $RespInfo;
	$data['refund_status'] = $flag;  //交易类型标志
	$data['idCard'] = $idCard;  //银行卡号
	$data['Amount'] = $Amount;     //银行交易金额
	$data['trade_no'] = $fk_no;  //发卡行代码
	$data['Batch'] = $total_amount;	 //实际交易金额
	$data['TransDate'] = $jy_no;    //交易日期时间
	$data['bank_info'] = $bank_info;  //银行卡交易串
	$data['Ref'] = $ck_no;    //参考号
	$data['Auth'] = $pz_no;  //凭证号
	$data['Memo'] = $sc_no;  //授权号
	$data['Lrc'] = $pc_no;   //批次号
	$data['out_trade_no'] = $out_trade_no;  //银行交易单号
	$data['business_type'] = $subject;  //交易业务类型
	$data['pat_id'] = $patient_id;  //病人id
	M("bank_log")->add($data);
	\Log::Write("银行卡交易串：".$data['business_type'].$bank_info);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	$rel = array();
    $rel["RespCode"] = $RespCode;  //交易成功码
    $rel["idCard"] =  $idCard;  //银行卡号
    $rel["pz_no"] =   $pz_no ;  //凭证号
    $rel["sc_no"] =   $sc_no ;  //授权号
     $time = strtotime($jy_no);
    $jy_no =  date("Y-m-d H:i:s", $time);
    $rel["jy_no"] =   $jy_no ;  //交易日期时间
    $rel["ck_no"] =  $ck_no;    //参考号
	$this->ajaxReturn($rel,"JSON");

}


/**********生成二维码*******************************/
public function ajaxGetPayUrl(){
	//$soap = new \SoapClient('http://182.254.218.183/soap/Service.php?wsdl');  
	// $soap = new \SoapClient(null,array('location'=>'http://123.206.31.148/soap/Service.php',uri=>'Service.php')); 
	// $soap = new \SoapClient('http://127.0.0.1/soap/Service.php?wsdl');
	$soap = new \SoapClient(null,array('location'=>'http://172.20.14.246/soap/Service.php',uri=>'Service.php')); 
	// var_dump($soap);exit;
	$pay_type = I("post.pay_type");
	$out_trade_no = trim(I("post.out_trade_no"));
	$total_amount = trim(I("post.total_amount"));
	$subject = trim(I("post.subject"));
	$pay_type = I("post.pay_type");
	$source="hos040";
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
	$soap = new \SoapClient(null,array('location'=>'http://172.20.14.246/soap/Service.php',uri=>'Service.php')); 
	$out_trade_no = trim(I("post.out_trade_no"));
	$source="hos040";
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
//就诊卡挂号信息保存
public function ic_guahao_save(){
	$card_no = I("post.card_no"); //卡号
 	$card_code = I("post.card_code"); //卡类型
 	$patient_id = I("post.patient_id");//病人ID
 	$meddate =   I("post.meddate"); //就诊时间
 	$zzj_id =  I("post.zzj_id");
    $regclass = I("post.hao_type");	//挂号类别
	$orderid = I("post.orderid");	//排班ID
 	$streak  =  I("post.apm");  //上下午
 	if($streak =="上午"){
        $streak="1";
    }elseif($streak =="下午"){
        $streak="2";
    }else{
        $streak="";
    }
 	$departmentid  =  I("post.department_id"); //科室id
    $docno =  I("post.doc_no");  //医生代码
 	$payway =  I("post.pay_type");  //支付方式
    $medpay =  I("post.medpay"); //医保支付状态
    $rctpno =  I("post.rctp_no");  //收据号
    $PayNo  =  I("post.trade_no"); //支付流水号	
    $bankno = I("post.bankno");//支付账号
	// 查询HIS服务器时间SQL
	$str = '<?xml version="1.0" encoding="GBK" ?>
		<root>
			<head>
				<actdate>20131203</actdate>
				<trdate>20131203</trdate>
				<trtime>082743</trtime>
				<trcode>Z000</trcode>
			</head>
		</root>';	
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
		$rel['head']['succflag']="-1";
		$rel['head']['retmsg']="请求超时,请重试";
		$this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);
		$doc = simplexml_load_string($result3);
		$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    $His_time =  $rels["body"]["CurrentDateTime"];
		$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20131203</actdate>
					<trdate>20131203</trdate>
					<trtime>085955</trtime>
					<trcode>Z202</trcode>
				</head>
				<body>
                    <acttype>'.$card_code.'</acttype>
                    <actno>'.$card_no.'</actno> 
                    <medid>'.$patient_id.'</medid>
                    <meddate>'.$meddate.'</meddate>
                    <streak>'.$streak.'</streak>
                    <medpay>0</medpay>
                    <orderid>'.$orderid.'</orderid>
                    <seqnum></seqnum>
                    <regtype>2</regtype>
                </body>
			</root>';	
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "自助预约保存";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		$socketport = "2013";
		$socket = \Socket::singleton();
		//链接
		$aa = $socket->connect($socketaddr,$socketport);
		
		//写数据
		$sockresult = $socket->sendrequest($str);
		//读数据
		$response = $socket->waitforresponse();
		$socket->disconnect (); //关闭链接
		if($response == "" || $response == false){
	        $rel['head']['succflag']="2";
	        $rel['head']['retmsg']="请求超时,请重试";
	        $this->ajaxReturn($rel,"JSON");
		}else{
	        $response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result3 = str_replace("gbk","utf8",$result2);
			$doc = simplexml_load_string($result3);
			$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		     
			/**************日记记录开始**************/
			$data['card_code'] = I("post.card_code");
			$data['patient_id'] = I("post.patient_id");
			$data['card_no'] = I("post.card_no");
			$data['op_name'] = "自助预约保存";
			$data['direction'] = "返回报文";
			$data['op_code'] = I("post.op_code");
			$data['op_xml'] = $result3;
			$data['zzj_id'] = I("post.zzj_id");
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
		    $rel['head']['date'] = $His_time;      
			if($rel['head']['succflag']=="1"){
				$this->ajaxReturn($rel,"JSON");
			}else{
				$this->ajaxReturn($rel,"JSON");
			}
		}
	}  
}
//身份证挂号信息保存
public function sfz_guahao_save(){
	$card_no = I("post.card_no"); //卡号
 	$card_code = I("post.card_code"); //卡类型
 	$patient_id = I("post.patient_id");//病人ID
 	$meddate =   I("post.meddate"); //就诊时间
 	$zzj_id =  I("post.zzj_id");
    $regclass = I("post.hao_type");	//挂号类别
	$orderid = I("post.orderid");	//排班ID
 	$streak  =  I("post.apm");  //上下午
 	if($streak =="上午"){
        $streak="1";
    }elseif($streak =="下午"){
        $streak="2";
    }else{
        $streak="";
    }
 	$departmentid  =  I("post.department_id"); //科室id
    $docno =  I("post.doc_no");  //医生代码
 	$payway =  I("post.pay_type");  //支付方式
 	//获取支付方式
 	if($payway == "alipay"){
 		$payway ='1';
 	}
 	if($payway == "wxpay"){
 		$payway ='2';
 	}
 	if($payway == "yhk"){
 		$payway ='3';
 	}
    //自费金额为零时
    if($payway == "mianfei"){
 		$payway ='1';
 	}
    $medpay =  I("post.medpay"); //医保支付状态
    $rctpno =  I("post.rctp_no");  //收据号
    $PayNo  =  I("post.trade_no"); //支付流水号	
    $bankno = I("post.bankno");//支付账号
	// 查询HIS服务器时间SQL
	$str = '<?xml version="1.0" encoding="GBK" ?>
		<root>
			<head>
				<actdate>20131203</actdate>
				<trdate>20131203</trdate>
				<trtime>082743</trtime>
				<trcode>Z000</trcode>
			</head>
		</root>';	
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	if($response == "" || $response == false){
		$rel['head']['succflag']="-1";
		$rel['head']['retmsg']="请求超时,请重试";
		$this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);
		$doc = simplexml_load_string($result3);
		$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    $His_time =  $rels["body"]["CurrentDateTime"];
		$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20131203</actdate>
					<trdate>20131203</trdate>
					<trtime>085955</trtime>
					<trcode>Z201</trcode>
				</head>
				<body>
					<acttype>'.$card_code.'</acttype>
					<actno>'.$card_no.'</actno>
					<medid>'.$patient_id.'</medid>
					<regtype>1</regtype>
					<regclass>'.$regclass.'</regclass>
					<meddate>'.$meddate.'</meddate>
					<orderid>'.$orderid.'</orderid>
					<streak>'.$streak.'</streak>
					<departmentid>'.$departmentid.'</departmentid>
					<docno>'.$docno.'</docno>
					<payway>'.$payway.'</payway>
					<seqnum></seqnum>
					<medpay>0</medpay>
					<rctpno>'.$rctpno.'</rctpno>							
					<bankno>'.$bankno.'</bankno>
					<PayNo>'.$PayNo.'</PayNo>
				</body>
			</root>';	
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "自助挂号身份证保存";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		$socketport = "2013";
		$socket = \Socket::singleton();
		//链接
		$aa = $socket->connect($socketaddr,$socketport);
		
		//写数据
		$sockresult = $socket->sendrequest($str);
		//读数据
		$response = $socket->waitforresponse();
		$socket->disconnect (); //关闭链接
		if($response == "" || $response == false){
	        $rel['head']['succflag']="2";
	        $rel['head']['retmsg']="请求超时,请重试";
	        $this->ajaxReturn($rel,"JSON");
		}else{
	        $response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result3 = str_replace("gbk","utf8",$result2);
			$doc = simplexml_load_string($result3);
			$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		     
			/**************日记记录开始**************/
			$data['card_code'] = I("post.card_code");
			$data['patient_id'] = I("post.patient_id");
			$data['card_no'] = I("post.card_no");
			$data['op_name'] = "自助挂号身份证保存";
			$data['direction'] = "返回报文";
			$data['op_code'] = I("post.op_code");
			$data['op_xml'] = $result3;
			$data['zzj_id'] = I("post.zzj_id");
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
		    $rel['head']['date'] = $His_time;      
			if($rel['head']['succflag']=="1"){
				$this->ajaxReturn($rel,"JSON");
			}else{
				$this->ajaxReturn($rel,"JSON");
			}
		}
	}  
}

//保存医保卡缴费信息
public function yb_guahao_save(){
	 //保存医保卡缴费信息
	$card_no = I("post.card_no"); //卡号
 	$card_code = I("post.card_code"); //卡类型
 	$patient_id = I("post.patient_id");//病人ID
 	$meddate =   I("post.meddate"); //就诊时间
 	$zzj_id =  I("post.zzj_id");
    $regclass = I("post.hao_type");	//挂号类别
	$orderid = I("post.orderid");	//排班ID
 	$streak  =  I("post.apm");  //上下午
 	$cash  = I("post.cash");  
 	if($streak =="上午"){
        $streak="1";
     }elseif($streak =="下午"){
        $streak="2";
     }else{
        $streak="";
     }
 	$departmentid  =  I("post.department_id"); //科室id
    $docno =  I("post.doc_no");  //医生代码
 	$payway =  I("post.pay_type");  //支付方式
 	if($payway == "alipay"){
 		$payway ='1';
 	}
 	if($payway == "wxpay"){
 		$payway ='2';
 	}

 	if($payway == "yhk"){
 		$payway ='3';
 	}
 	//自费金额为零时
    if($payway == "" && $cash =="0"){
 		$payway ='1';
 	}
    $medpay =  I("post.medpay"); //医保支付状态
    $rctpno =  I("post.rctp_no");  //收据号
    $PayNo  =  I("post.trade_no"); //支付流水号	
    $bankno = I("post.bankno");//支付账号
	 // 查询HIS服务器时间SQL
	$str = '<?xml version="1.0" encoding="GBK" ?>
	<root>
		<head>
			<actdate>20131203</actdate>
			<trdate>20131203</trdate>
			<trtime>082743</trtime>
			<trcode>YQXYYZZJ000</trcode>
		</head>
	</root>';
		
	//实例化socket
	$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	$socketport = "2013";
	$socket = \Socket::singleton();
	//链接
	$aa = $socket->connect($socketaddr,$socketport);
	
	//写数据
	$sockresult = $socket->sendrequest($str);
	//读数据
	$response = $socket->waitforresponse();
	$socket->disconnect (); //关闭链接
	 if($response=="" || $response == false){
     	$rel['head']['succflag']="-1";
        $rel['head']['retmsg']="请求超时,请重试";
        $this->ajaxReturn($rel,"JSON");
	}else{
		$response = strstr($response,'<');
		$temp=iconv("gbk","utf-8//IGNORE",$response);
		$result1 = ltrim($temp,"^");
		$result2 = strstr($result1,'<');
		$result3 = str_replace("gbk","utf8",$result2);
		$doc = simplexml_load_string($result3);
		$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
	    $His_time =  $rels["body"]["CurrentDateTime"];

		$str = '<?xml version="1.0" encoding="GBK" ?>
						<root>
							<head>
								<actdate>20131203</actdate>
								<trdate>20131203</trdate>
								<trtime>085955</trtime>
								<trcode>Z201</trcode>
							</head>
							<body>
								<acttype>'.$card_code.'</acttype>
								<actno>\</actno>
								<medid>'.$patient_id.'</medid>
								<regtype>1</regtype>
								<regclass>'.$regclass.'</regclass>
								<meddate>'.$meddate.'</meddate>
								<orderid>'.$orderid.'</orderid>
								<streak>'.$streak.'</streak>
								<departmentid>'.$departmentid.'</departmentid>
								<docno>'.$docno.'</docno>
								<payway>'.$payway.'</payway>
								<seqnum></seqnum>
								<medpay>1</medpay>
								<rctpno>'.$rctpno.'</rctpno>							
								<bankno>'.$bankno.'</bankno>
								<PayNo>'.$PayNo.'</PayNo>
							</body>
						</root>
						';

		
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "自助挂号医保卡患者保存";
		$data['direction'] = "发送报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = $str;
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/	
		
		$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		$socketport = "2013";
		$socket = \Socket::singleton();
		//链接
		$aa = $socket->connect($socketaddr,$socketport);
		
		//写数据
		$sockresult = $socket->sendrequest($str);
		//读数据
		$response = $socket->waitforresponse();
		$socket->disconnect (); //关闭链接
	    if($response=="" || $response == false){
	     	$rel['head']['succflag']="2";
	        $rel['head']['retmsg']="请求超时,请重试";
	        $this->ajaxReturn($rel,"JSON");
		}else{
			$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result3 = str_replace("gbk","utf8",$result2);
			$doc = simplexml_load_string($result3);
			$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
		     
			/**************日记记录开始**************/
			$data['card_code'] = I("post.card_code");
			$data['patient_id'] = I("post.patient_id");
			$data['card_no'] = I("post.card_no");
			$data['op_name'] = "自助挂号医保卡患者保存";
			$data['direction'] = "返回报文";
			$data['op_code'] = I("post.op_code");
			$data['op_xml'] = $result3;
			$data['zzj_id'] = I("post.zzj_id");
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
			$rel['head']['date'] = $His_time;
			if($rel['head']['succflag']=="1"){
				$this->ajaxReturn($rel,"JSON");
			}else{
				$this->ajaxReturn($rel,"JSON");
			}
			
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