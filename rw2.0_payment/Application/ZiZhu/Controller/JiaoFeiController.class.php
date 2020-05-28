<?php
namespace ZiZhu\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
require_once __DIR__ .'/Log.php';
class JiaoFeiController extends CommonController {
public function index()
{
	$zzj_id = I("get.zzj_id");
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
	// $trade_no = '2018062621001004440536948106';
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
	$data['op_xml'] = var_export($row,true);
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
/**********退款*******************************/
//银行卡退款标识
public  function yhk_tk(){
	 $tk_status = I("post.tk_status"); //银行卡退款流水号    
	 $tk_code = substr($tk_status, 0,2); //交易成功码
	 if( $tk_code == "00"){
	 	$tk_Info = "tk_success";  //退款成功
	 }else{
	 	$tk_Info = "tk_fail";  //退款失败
	 }
	 $out_trade_no = I("post.out_trade_no");
	
     $zzj_id = I("post.zzj_id");
   
    $sqls = "update bank_log set tk_status='".$tk_Info."',zzj_id='". $zzj_id."',bank_info1='".$tk_status."' where out_trade_no='".$out_trade_no."'";
	
	 M("bank_log")->query($sqls);

}
/**********支付宝 微信 退款*******************************/
public function ajax_tuikuan(){
   
    $soap = new \SoapClient(null,array('location'=>'http://172.20.14.246/soap/Service.php',uri=>'Service.php'));
    $pay_type = I("post.pay_type");
    $trade_no = I("post.trade_no");
    $out_trade_no = trim(I("post.out_trade_no"));
    $total_amount = trim(I("post.total_amount"));
    $subject = trim(I("post.subject"));
    $source="hos040";
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
public function sfz_getPatInfo(){
 	/*$card_no = I("post.card_no");
 	$card_code = I("post.card_code"); 	
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	$str = '<?xml version="1.0" encoding="GBK" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>085938</trtime>
						<trcode>Z100</trcode>
					</head>
					<body>
						<acttype>'.$card_code.'</acttype>
						<actno>'.$card_no.'</actno>
						<medpos></medpos>
					</body>
				</root>';*/

	/**************日记记录开始**************/
	// $data['card_code'] = I("post.card_code");
	// $data['patient_id'] = I("post.patient_id");
	// $data['card_no'] = I("post.card_no");
	// $data['op_name'] = "获取就诊卡患者信息";
	// $data['direction'] = "发送报文";
	// $data['op_code'] = I("post.op_code");
	// $data['op_xml'] = $str;
	// $data['zzj_id'] = I("post.zzj_id");
	// M("logs")->add($data);
	// $this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
    //$socketaddr = "172.16.77.18";
	// $socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	// $socketport = "2013";
	// $socket = \Socket::singleton();
	// //链接
	// $aa = $socket->connect($socketaddr,$socketport);
	// //写数据
	// $sockresult = $socket->sendrequest($str);
	// //读数据
	// $response = $socket->waitforresponse();
	// $socket->disconnect (); //关闭链接
	// if($response == "" || $response == false){
 //        $rel['head']['succflag']="2";
 //        $rel['head']['retmsg']="请求超时,请重试";
 //        $this->ajaxReturn($rel,"JSON");
	// }else{
		// $response = strstr($response,'<');
		// $temp=iconv("gbk","utf-8//IGNORE",$response);
		// $result1 = ltrim($temp,"^");
		// $result2 = strstr($result1,'<');
		// $result3 = str_replace("gbk","utf8",$result2);	
		$result3 = '<?xml version="1.0" encoding="utf8"?><root><head><actdate>20190306</actdate><trdate>20190306</trdate><trtime>181848</trtime><trcode>Z100</trcode><hisseq>0</hisseq><bhpseq></bhpseq><jdseq></jdseq><filenum>0</filenum><succflag>1</succflag><retcode>000</retcode><retmsg></retmsg></head><body><medid>91570123</medid><name>陈静</name><sex>2</sex><age>23</age><birthday>1995-10-26</birthday><addr>四川</addr><idno>51132419951026580X</idno><phone>18910692035</phone><medpos></medpos><medcardno></medcardno><medflag>0</medflag><fylb>00</fylb><Msg></Msg><Personcount></Personcount><sfyzjg></sfyzjg><szyz></szyz><tsbzdm></tsbzdm></body></root>';	
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);

		/**************日记记录开始**************/
		// $data['card_code'] = I("post.card_code");
		// $data['patient_id'] = $card_no ;
		// $data['card_no'] = I("post.card_no");
		// $data['op_name'] = "获取就诊卡患者信息";
		// $data['direction'] = "返回报文";
		// $data['op_code'] = I("post.op_code");
		// $data['op_xml'] = $result3;
		// $data['zzj_id'] = I("post.zzj_id");
		// M("logs")->add($data);
		// $this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/

		if($rel['head']['succflag']=="1"){
			// 获取病人ID
			$patient_id = $rel['body']['medid'];
			// 获取患者处方信息
			$getCF = $this->getChuFang();
			
			$rel['chufang'] = $getCF;
			 //判断处方信息是否为空
			if($rel['chufang']['datarow']["0"]['attr'] ==""){
	              $rel['chufang']['datarow']["0"]['attr']="0";
			}
	        //判断处方药品信息是否为空
			if($rel['chufang']['datarow']["0"]['sub'] ==""){
	              $rel['chufang']['datarow']["0"]['sub']="0";
			}

			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}


	// }
	
}
public function ic_getPatInfo(){
 	/*$card_no = I("post.card_no");
 	$card_code = I("post.card_code"); 	
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	$str = '<?xml version="1.0" encoding="GBK" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>085938</trtime>
						<trcode>Z100</trcode>
					</head>
					<body>
						<acttype>'.$card_code.'</acttype>
						<actno>'.$card_no.'</actno>
						<medpos></medpos>
					</body>
				</root>';*/

	/**************日记记录开始**************/
	// $data['card_code'] = I("post.card_code");
	// $data['patient_id'] = I("post.patient_id");
	// $data['card_no'] = I("post.card_no");
	// $data['op_name'] = "获取就诊卡患者信息";
	// $data['direction'] = "发送报文";
	// $data['op_code'] = I("post.op_code");
	// $data['op_xml'] = $str;
	// $data['zzj_id'] = I("post.zzj_id");
	// M("logs")->add($data);
	// $this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
    //$socketaddr = "172.16.77.18";
	// $socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	// $socketport = "2013";
	// $socket = \Socket::singleton();
	// //链接
	// $aa = $socket->connect($socketaddr,$socketport);
	// //写数据
	// $sockresult = $socket->sendrequest($str);
	// //读数据
	// $response = $socket->waitforresponse();
	// $socket->disconnect (); //关闭链接
	// if($response == "" || $response == false){
 //        $rel['head']['succflag']="2";
 //        $rel['head']['retmsg']="请求超时,请重试";
 //        $this->ajaxReturn($rel,"JSON");
	// }else{
		// $response = strstr($response,'<');
		// $temp=iconv("gbk","utf-8//IGNORE",$response);
		// $result1 = ltrim($temp,"^");
		// $result2 = strstr($result1,'<');
		// $result3 = str_replace("gbk","utf8",$result2);	
		$result3 = '<?xml version="1.0" encoding="utf8"?><root><head><actdate>20190306</actdate><trdate>20190306</trdate><trtime>181848</trtime><trcode>Z100</trcode><hisseq>0</hisseq><bhpseq></bhpseq><jdseq></jdseq><filenum>0</filenum><succflag>1</succflag><retcode>000</retcode><retmsg></retmsg></head><body><medid>91570123</medid><name>陈静</name><sex>2</sex><age>23</age><birthday>1995-10-26</birthday><addr>四川</addr><idno>51132419951026580X</idno><phone>18910692035</phone><medpos></medpos><medcardno></medcardno><medflag>0</medflag><fylb>00</fylb><Msg></Msg><Personcount></Personcount><sfyzjg></sfyzjg><szyz></szyz><tsbzdm></tsbzdm></body></root>';	
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);

		/**************日记记录开始**************/
		// $data['card_code'] = I("post.card_code");
		// $data['patient_id'] = $card_no ;
		// $data['card_no'] = I("post.card_no");
		// $data['op_name'] = "获取就诊卡患者信息";
		// $data['direction'] = "返回报文";
		// $data['op_code'] = I("post.op_code");
		// $data['op_xml'] = $result3;
		// $data['zzj_id'] = I("post.zzj_id");
		// M("logs")->add($data);
		// $this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/

		if($rel['head']['succflag']=="1"){
			// 获取病人ID
			$patient_id = $rel['body']['medid'];
			// 获取患者处方信息
			// $getCF = $this->getChuFang($card_no,$card_code,$op_code,$patient_id,$zzj_id);
			$getCF = $this->getChuFang();
			
			$rel['chufang'] = $getCF;
			 //判断处方信息是否为空
			if($rel['chufang']['datarow']["0"]['attr'] ==""){
	              $rel['chufang']['datarow']["0"]['attr']="0";
			}
	        //判断处方药品信息是否为空
			if($rel['chufang']['datarow']["0"]['sub'] ==""){
	              $rel['chufang']['datarow']["0"]['sub']="0";
			}

			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}


	// }
	
}
// 医保卡获取病人信息
public function yibao_getPatInfo(){
 	/*$card_no = I("post.card_no");
 	$card_code = I("post.card_code"); 	
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
	$str = '<?xml version="1.0" encoding="GBK" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>085938</trtime>
						<trcode>Z100</trcode>
					</head>
					<body>
						<acttype>'.$card_code.'</acttype>
						<actno>\</actno>
						<medpos></medpos>
					</body>
				</root>';*/

	/**************日记记录开始**************/
	// $data['card_code'] = I("post.card_code");
	// $data['patient_id'] = I("post.patient_id");
	// $data['card_no'] = I("post.card_no");
	// $data['op_name'] = "获取医保卡患者信息";
	// $data['direction'] = "发送报文";
	// $data['op_code'] = I("post.op_code");
	// $data['op_xml'] = $str;
	// $data['zzj_id'] = I("post.zzj_id");
	// M("logs")->add($data);
	// $this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
    //$socketaddr = "172.16.77.18";
	// $socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	// $socketport = "2013";
	// $socket = \Socket::singleton();
	// //链接
	// $aa = $socket->connect($socketaddr,$socketport);
	// //写数据
	// $sockresult = $socket->sendrequest($str);
	// //读数据
	// $response = $socket->waitforresponse();
	// $socket->disconnect (); //关闭链接
	// if($response == "" || $response == false){
 //        $rel['head']['succflag']="2";
 //        $rel['head']['retmsg']="请求超时,请重试";
 //        $this->ajaxReturn($rel,"JSON");
	// }else{
		// $response = strstr($response,'<');
		// $temp=iconv("gbk","utf-8//IGNORE",$response);
		// $result1 = ltrim($temp,"^");
		// $result2 = strstr($result1,'<');
		// $result3 = str_replace("gbk","utf8",$result2);	
		$result3 = '<?xml version="1.0" encoding="utf8"?><root><head><actdate>20190306</actdate><trdate>20190306</trdate><trtime>181730</trtime><trcode>Z100</trcode><hisseq>0</hisseq><bhpseq></bhpseq><jdseq></jdseq><filenum>0</filenum><succflag>1</succflag><retcode>000</retcode><retmsg></retmsg></head><body><medid>3391085</medid><name>张丽娟</name><sex>2</sex><age>36</age><birthday>1982-04-28</birthday><addr></addr><idno>130824198204284026</idno><phone>13911187411</phone><medpos></medpos><medcardno>108207660001</medcardno><medflag>1</medflag><fylb>81</fylb><Msg></Msg><Personcount>0.00</Personcount><sfyzjg>110</sfyzjg><szyz></szyz><tsbzdm>****</tsbzdm></body></root>';	
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);

		/**************日记记录开始**************/
		// $data['card_code'] = I("post.card_code");
		// $data['patient_id'] = I("post.patient_id");
		// $data['card_no'] = I("post.card_no");
		// $data['op_name'] = "获取医保卡患者信息";
		// $data['direction'] = "返回报文";
		// $data['op_code'] = I("post.op_code");
		// $data['op_xml'] = $result3;
		// $data['zzj_id'] = I("post.zzj_id");
		// M("logs")->add($data);
		// $this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/

		if($rel['head']['succflag']=="1"){
			// 获取病人ID
			$patient_id = $rel['body']['medid'];
			// 获取患者处方信息
			$getCF = $this->yb_getChuFang();
			// $getCF = $this->getChuFang($card_no,$card_code,$op_code,$patient_id,$zzj_id);
			$rel['chufang'] = $getCF;
			 //判断处方信息是否为空
			if($rel['chufang']['datarow']["0"]['attr'] ==""){
	              $rel['chufang']['datarow']["0"]['attr']="0";
			}
	        //判断处方药品信息是否为空
			if($rel['chufang']['datarow']["0"]['sub'] ==""){
	              $rel['chufang']['datarow']["0"]['sub']="0";
			}

			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}

	// }
	
}
//获取就诊卡处方信息
// function getChuFang($card_no,$card_code,$op_code,$patient_id,$zzj_id){
function getChuFang(){
	/*$str = '<?xml version="1.0" encoding="GBK" ?>
					<root>
						<head>
							<actdate>20131203</actdate>
							<trdate>20131203</trdate>
							<trtime>085946</trtime>
							<trcode>Z301</trcode>
						</head>
						<body>
							<acttype>'.$card_code.'</acttype>
							<actno>'.$card_no.'</actno>
							<medid>'.$patient_id.'</medid>
						</body>
					</root>';*/
	/**************日记记录开始**************/
	// $data['card_code'] = I("post.card_code");
	// $data['patient_id'] = I("post.patient_id");
	// $data['card_no'] = I("post.card_no");
	// $data['op_name'] = "获取就诊卡患者处方信息";
	// $data['direction'] = "发送报文";
	// $data['op_code'] = I("post.op_code");
	// $data['op_xml'] = $str;
	// $data['zzj_id'] = I("post.zzj_id");
	// M("logs")->add($data);
	// $this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	// $socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	// $socketport = "2013";
	// $socket = \Socket::singleton();
	// //链接
	// $aa = $socket->connect($socketaddr,$socketport);
	// //写数据
	// $sockresult = $socket->sendrequest($str);
	// //读数据
	// $response = $socket->waitforresponse();
	// $socket->disconnect (); //关闭链接
	// $response = strstr($response,'<');
	// $temp=iconv("gbk","utf-8//IGNORE",$response);
	// $result1 = ltrim($temp,"^");
	// $result2 = strstr($result1,'<');
	// $result3 = str_replace("gbk","utf8",$result2);	
	$result3 = '<?xml version="1.0" encoding="utf8"?><root><head><actdate>20190307</actdate><trdate>20190307</trdate><trtime>080120</trtime><trcode>Z301</trcode><hisseq>0</hisseq><bhpseq></bhpseq><jdseq></jdseq><filenum>0</filenum><succflag>1</succflag><retcode>000</retcode><retmsg></retmsg></head><body><medid>91637987</medid><name>安浩</name><blocktamt>0.00</blocktamt><chargetamt>1800.00</chargetamt><rowcount>1</rowcount><detail><meddate>2019-03-07</meddate><departmentname>皮肤科门诊</departmentname><docname>籍红</docname><chargeid>K000001</chargeid><chargetype>2</chargetype><chargeamt>1800.00</chargeamt><djsm>否</djsm></detail></body></root>';	
	$doc = simplexml_load_string($result3);
	$rel = json_decode(json_encode($doc),true);
	/**************日记记录开始**************/
	// $data['card_code'] = I("post.card_code");
	// $data['patient_id'] = I("post.patient_id");
	// $data['card_no'] = I("post.card_no");
	// $data['op_name'] = "获取就诊卡患者处方信息";
	// $data['direction'] = "返回报文";
	// $data['op_code'] = I("post.op_code");
	// $data['op_xml'] = $result3;
	// $data['zzj_id'] = I("post.zzj_id");
	// M("logs")->add($data);
	// $this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/  
	$rel_cf['result']['execute_flag'] = $rel['head']['succflag'];
	$rel_cf['result']['execute_message'] = $rel['head']['retmsg'];      
	if($rel['body']['rowcount'] == "1"){
		$rel_cf['datarow']["0"]['attr'] = $rel['body']['detail'];
		/*$str = '<?xml version="1.0" encoding="GBK" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>085946</trtime>
						<trcode>Z302</trcode>
					</head>
					<body>
						<acttype>'.$card_code.'</acttype>
						<actno>'.$card_no.'</actno>
						<medid>'.$patient_id.'</medid>
						<meddate>2015-05-20</meddate>
						<chargetype>2</chargetype>
						<chargeid>'.$rel['body']['detail']["chargeid"].'</chargeid>
					</body>
				</root>';*/
		/**************日记记录开始**************/
		// $data['card_code'] = I("post.card_code");
		// $data['patient_id'] = I("post.patient_id");
		// $data['card_no'] = I("post.card_no");
		// $data['op_name'] = "获取就诊卡患者缴费明细信息_单条";
		// $data['direction'] = "发送报文";
		// $data['op_code'] = I("post.op_code");
		// $data['op_xml'] = $str;
		// $data['zzj_id'] = I("post.zzj_id");
		// M("logs")->add($data);
		// $this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		//实例化socket
		
		// $socketaddr = "172.16.77.18";
		// $socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		// $socketport = "2013";
		// $socket = \Socket::singleton();
		// //链接
		// $aa = $socket->connect($socketaddr,$socketport);
		// //写数据
		// $sockresult = $socket->sendrequest($str);
		// //读数据
		// $response_mx = $socket->waitforresponse();
		// $socket->disconnect (); //关闭链接		
		// $response_mx = strstr($response_mx,'<');
		// $temp_mx=iconv("gbk","utf-8//IGNORE",$response_mx);
		// $result1_mx = ltrim($temp_mx,"^");
		// $result2_mx = strstr($result1_mx,'<');
		// $result3_mx = str_replace("gbk","utf8",$result2_mx);
		$result3_mx = '<?xml version="1.0" encoding="utf8"?><root><head><actdate>20190307</actdate><trdate>20190307</trdate><trtime>080121</trtime><trcode>Z302</trcode><hisseq>0</hisseq><bhpseq></bhpseq><jdseq></jdseq><filenum>0</filenum><succflag>1</succflag><retcode>000</retcode><retmsg></retmsg></head><body><medid>91637987</medid><name>安浩</name><meddate>1900-01-01</meddate><departmentname>皮肤科门诊</departmentname><docname>籍红</docname><chargeid>K000001</chargeid><chargetype>2</chargetype><chargeamt>1800.00</chargeamt><rowcount>1</rowcount><detail><itemname>光动力疗法</itemname><itemspec></itemspec><unit>光斑</unit><quantity>9.0000</quantity><itemclass>治疗费</itemclass><price>200.0000</price><amt>1800.0000</amt><ybzfbl>1.00</ybzfbl></detail></body></root>';
		$doc_mx = simplexml_load_string($result3_mx);
		$rel_mx = json_decode(json_encode($doc_mx),true);
	    /**************日记记录开始**************/
		// $data['card_code'] = I("post.card_code");
		// $data['patient_id'] = I("post.patient_id");
		// $data['card_no'] = I("post.card_no");
		// $data['op_name'] = "获取就诊卡患者缴费明细信息_单条";
		// $data['direction'] = "返回报文";
		// $data['op_code'] = I("post.op_code");
		// $data['op_xml'] = $result3_mx;
		// $data['zzj_id'] = I("post.zzj_id");
		// M("logs")->add($data);
		// $this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/

		for ($x=0; $x < $rel_mx['body']['rowcount'] ; $x++) {

			if ($rel_mx['body']['rowcount'] == 1) {
			 	$mx[$x] = $rel_mx['body']['detail'];
			 }else{
			 	$mx[$x] = $rel_mx['body']['detail'][$x];
			 } 
			 
		}
		$rel_cf['datarow']["0"]['sub'] = $mx;

	}else{
		for ($i=0; $i < $rel['body']['rowcount']; $i++) { 
				$rel_cf['datarow'][$i]['attr'] = $rel['body']['detail'][$i];
				$str = '<?xml version="1.0" encoding="GBK" ?>
						<root>
							<head>
								<actdate>20131203</actdate>
								<trdate>20131203</trdate>
								<trtime>085946</trtime>
								<trcode>Z302</trcode>
							</head>
							<body>
								<acttype>'.$card_code.'</acttype>
								<actno>'.$card_no.'</actno>
								<medid>'.$patient_id.'</medid>
								<meddate>2015-05-20</meddate>
								<chargetype>2</chargetype>
								<chargeid>'.$rel['body']['detail'][$i]["chargeid"].'</chargeid>
							</body>
						</root>';
				/**************日记记录开始**************/
				$data['card_code'] = I("post.card_code");
				$data['patient_id'] = I("post.patient_id");
				$data['card_no'] = I("post.card_no");
				$data['op_name'] = "获取就诊卡患者缴费明细信息_多条";
				$data['direction'] = "发送报文";
				$data['op_code'] = I("post.op_code");
				$data['op_xml'] = $str;
				$data['zzj_id'] = I("post.zzj_id");
				M("logs")->add($data);
				$this->writeLog($log_txt,$log_type);
				/**********日志记录结束*******************/
				//实例化socket
				
				// $socketaddr = "172.16.77.18";
				$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
				$socketport = "2013";
				$socket = \Socket::singleton();
				//链接
				$aa = $socket->connect($socketaddr,$socketport);
				//写数据
				$sockresult = $socket->sendrequest($str);
				//读数据
				$response_mx = $socket->waitforresponse();
				$socket->disconnect (); //关闭链接		
				$response_mx = strstr($response_mx,'<');
				$temp_mx=iconv("gbk","utf-8//IGNORE",$response_mx);
				$result1_mx = ltrim($temp_mx,"^");
				$result2_mx = strstr($result1_mx,'<');
				$result3_mx = str_replace("gbk","utf8",$result2_mx);
				$doc_mx = simplexml_load_string($result3_mx);
				$rel_mx = json_decode(json_encode($doc_mx),true);
			    /**************日记记录开始**************/
				$data['card_code'] = I("post.card_code");
				$data['patient_id'] = I("post.patient_id");
				$data['card_no'] = I("post.card_no");
				$data['op_name'] = "获取就诊卡患者缴费明细信息_多条";
				$data['direction'] = "返回报文";
				$data['op_code'] = I("post.op_code");
				$data['op_xml'] = $result3_mx;
				$data['zzj_id'] = I("post.zzj_id");
				M("logs")->add($data);
				$this->writeLog($log_txt,$log_type);
				/**********日志记录结束*******************/
				for ($x=0; $x < $rel_mx['body']['rowcount'] ; $x++) {
					if ($rel_mx['body']['rowcount'] == 1) {
					 	$mx[$x] = $rel_mx['body']['detail'];
					 }else{
					 	$mx[$x] = $rel_mx['body']['detail'][$x];
					 } 
				}
				$rel_cf['datarow'][$i]['sub'] = $mx;
			}

	}

	return $rel_cf;
}


//获取医保卡处方信息
// function yb_getChuFang($card_code,$op_code,$patient_id,$zzj_id){
function yb_getChuFang(){
	/*$str = '<?xml version="1.0" encoding="GBK" ?>
					<root>
						<head>
							<actdate>20131203</actdate>
							<trdate>20131203</trdate>
							<trtime>085946</trtime>
							<trcode>Z301</trcode>
						</head>
						<body>
							<acttype>1</acttype>
							<actno>\</actno>
							<medid>'.$patient_id.'</medid>
						</body>
					</root>';*/
	/**************日记记录开始**************/
	// $data['card_code'] = I("post.card_code");
	// $data['patient_id'] = I("post.patient_id");
	// $data['card_no'] = I("post.card_no");
	// $data['op_name'] = "获取就诊卡患者处方信息";
	// $data['direction'] = "发送报文";
	// $data['op_code'] = I("post.op_code");
	// $data['op_xml'] = $str;
	// $data['zzj_id'] = I("post.zzj_id");
	// M("logs")->add($data);
	// $this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
	//$zzj_id = "zzj001";
	// $socketaddr = "172.16.77.18";
	// $socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	// $socketport = "2013";
	// $socket = \Socket::singleton();
	// //链接
	// $aa = $socket->connect($socketaddr,$socketport);
	// //写数据
	// $sockresult = $socket->sendrequest($str);
	// //读数据
	// $response = $socket->waitforresponse();
	// $socket->disconnect (); //关闭链接
	
	// $response = strstr($response,'<');
	// $temp=iconv("gbk","utf-8//IGNORE",$response);
	// $result1 = ltrim($temp,"^");
	// $result2 = strstr($result1,'<');
	// $result3 = str_replace("gbk","utf8",$result2);	
	$result3 = '<?xml version="1.0" encoding="utf8"?><root><head><actdate>20190307</actdate><trdate>20190307</trdate><trtime>074328</trtime><trcode>Z301</trcode><hisseq>0</hisseq><bhpseq></bhpseq><jdseq></jdseq><filenum>0</filenum><succflag>1</succflag><retcode>000</retcode><retmsg></retmsg></head><body><medid>91536729</medid><name>董媛明</name><blocktamt>0.00</blocktamt><chargetamt>25.15</chargetamt><rowcount>1</rowcount><detail><meddate>2019-03-07</meddate><departmentname>妇产科门诊</departmentname><docname>肖欢</docname><chargeid>C000001</chargeid><chargetype>2</chargetype><chargeamt>25.15</chargeamt><djsm>否</djsm></detail></body></root>';	
	$doc = simplexml_load_string($result3);
	$rel = json_decode(json_encode($doc),true);
	/**************日记记录开始**************/
	// $data['card_code'] = I("post.card_code");
	// $data['patient_id'] = I("post.patient_id");
	// $data['card_no'] = I("post.card_no");
	// $data['op_name'] = "获取就诊卡患者处方信息";
	// $data['direction'] = "返回报文";
	// $data['op_code'] = I("post.op_code");
	// $data['op_xml'] = $result3;
	// $data['zzj_id'] = I("post.zzj_id");
	// M("logs")->add($data);
	// $this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
    
	$rel_cf['result']['execute_flag'] = $rel['head']['succflag'];
	$rel_cf['result']['execute_message'] = $rel['head']['retmsg'];

	if($rel['body']['rowcount'] == "1"){
		$rel_cf['datarow']["0"]['attr'] = $rel['body']['detail'];
		/*$str = '<?xml version="1.0" encoding="GBK" ?>
				<root>
					<head>
						<actdate>20131203</actdate>
						<trdate>20131203</trdate>
						<trtime>085946</trtime>
						<trcode>Z302</trcode>
					</head>
					<body>
						<acttype>1</acttype>
						<actno>\</actno>
						<medid>'.$patient_id.'</medid>
						<meddate>2015-05-20</meddate>
						<chargetype>2</chargetype>
						<chargeid>'.$rel['body']['detail']["chargeid"].'</chargeid>
					</body>
				</root>';*/
		/**************日记记录开始**************/
		// $data['card_code'] = I("post.card_code");
		// $data['patient_id'] = I("post.patient_id");
		// $data['card_no'] = I("post.card_no");
		// $data['op_name'] = "获取就诊卡患者缴费明细信息";
		// $data['direction'] = "发送报文";
		// $data['op_code'] = I("post.op_code");
		// $data['op_xml'] = $str;
		// $data['zzj_id'] = I("post.zzj_id");
		// M("logs")->add($data);
		// $this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
		//实例化socket
		
		// $socketaddr = "172.16.77.18";
		// $socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
		// $socketport = "2013";
		// $socket = \Socket::singleton();
		// //链接
		// $aa = $socket->connect($socketaddr,$socketport);
		// //写数据
		// $sockresult = $socket->sendrequest($str);
		// //读数据
		// $response_mx = $socket->waitforresponse();
		// $socket->disconnect (); //关闭链接		
		// $response_mx = strstr($response_mx,'<');
		// $temp_mx=iconv("gbk","utf-8//IGNORE",$response_mx);
		// $result1_mx = ltrim($temp_mx,"^");
		// $result2_mx = strstr($result1_mx,'<');
		// $result3_mx = str_replace("gbk","utf8",$result2_mx);
		$result3_mx = '<?xml version="1.0" encoding="utf8"?><root><head><actdate>20190307</actdate><trdate>20190307</trdate><trtime>074329</trtime><trcode>Z302</trcode><hisseq>0</hisseq><bhpseq></bhpseq><jdseq></jdseq><filenum>0</filenum><succflag>1</succflag><retcode>000</retcode><retmsg></retmsg></head><body><medid>91536729</medid><name>董媛明</name><meddate>1900-01-01</meddate><departmentname>妇产科门诊</departmentname><docname>肖欢</docname><chargeid>C000001</chargeid><chargetype>2</chargetype><chargeamt>25.15</chargeamt><rowcount>2</rowcount><detail><itemname>肌肉注射</itemname><itemspec></itemspec><unit>次</unit><quantity>1.0000</quantity><itemclass>治疗费</itemclass><price>3.5000</price><amt>3.5000</amt><ybzfbl>0.00</ybzfbl></detail><detail><itemname>注射用尿促性素</itemname><itemspec>75iu/瓶 (丽珠)</itemspec><unit>瓶</unit><quantity>1.0000</quantity><itemclass>西药费</itemclass><price>21.6500</price><amt>21.6500</amt><ybzfbl>0.00</ybzfbl></detail></body></root>';
		$doc_mx = simplexml_load_string($result3_mx);
		$rel_mx = json_decode(json_encode($doc_mx),true);
	    /**************日记记录开始**************/
		// $data['card_code'] = I("post.card_code");
		// $data['patient_id'] = I("post.patient_id");
		// $data['card_no'] = I("post.card_no");
		// $data['op_name'] = "获取就诊卡患者缴费明细信息";
		// $data['direction'] = "返回报文";
		// $data['op_code'] = I("post.op_code");
		// $data['op_xml'] = $result3_mx;
		// $data['zzj_id'] = I("post.zzj_id");
		// M("logs")->add($data);
		// $this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/

		for ($x=0; $x < $rel_mx['body']['rowcount'] ; $x++) {

			if ($rel_mx['body']['rowcount'] == 1) {
			 	$mx[$x] = $rel_mx['body']['detail'];
			 }else{
			 	$mx[$x] = $rel_mx['body']['detail'][$x];
			 } 
			 
		}
		$rel_cf['datarow']["0"]['sub'] = $mx;

	}else{
		for ($i=0; $i < $rel['body']['rowcount']; $i++) { 
				$rel_cf['datarow'][$i]['attr'] = $rel['body']['detail'][$i];
				$str = '<?xml version="1.0" encoding="GBK" ?>
						<root>
							<head>
								<actdate>20131203</actdate>
								<trdate>20131203</trdate>
								<trtime>085946</trtime>
								<trcode>Z302</trcode>
							</head>
							<body>
								<acttype>1</acttype>
								<actno>\</actno>
								<medid>'.$patient_id.'</medid>
								<meddate>2015-05-20</meddate>
								<chargetype>2</chargetype>
								<chargeid>'.$rel['body']['detail'][$i]["chargeid"].'</chargeid>
							</body>
						</root>';
				/**************日记记录开始**************/
				$data['card_code'] = I("post.card_code");
				$data['patient_id'] = I("post.patient_id");
				$data['card_no'] = I("post.card_no");
				$data['op_name'] = "获取就诊卡患者缴费明细信息";
				$data['direction'] = "发送报文";
				$data['op_code'] = I("post.op_code");
				$data['op_xml'] = $str;
				$data['zzj_id'] = I("post.zzj_id");
				M("logs")->add($data);
				$this->writeLog($log_txt,$log_type);
				/**********日志记录结束*******************/
				//实例化socket
				
				// $socketaddr = "172.16.77.18";
				$socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
				$socketport = "2013";
				$socket = \Socket::singleton();
				//链接
				$aa = $socket->connect($socketaddr,$socketport);
				//写数据
				$sockresult = $socket->sendrequest($str);
				//读数据
				$response_mx = $socket->waitforresponse();
				$socket->disconnect (); //关闭链接		
				$response_mx = strstr($response_mx,'<');
				$temp_mx=iconv("gbk","utf-8//IGNORE",$response_mx);
				$result1_mx = ltrim($temp_mx,"^");
				$result2_mx = strstr($result1_mx,'<');
				$result3_mx = str_replace("gbk","utf8",$result2_mx);
				$doc_mx = simplexml_load_string($result3_mx);
				$rel_mx = json_decode(json_encode($doc_mx),true);
			    /**************日记记录开始**************/
				$data['card_code'] = I("post.card_code");
				$data['patient_id'] = I("post.patient_id");
				$data['card_no'] = I("post.card_no");
				$data['op_name'] = "获取就诊卡患者缴费明细信息";
				$data['direction'] = "返回报文";
				$data['op_code'] = I("post.op_code");
				$data['op_xml'] = $result3_mx;
				$data['zzj_id'] = I("post.zzj_id");
				M("logs")->add($data);
				$this->writeLog($log_txt,$log_type);
				/**********日志记录结束*******************/

				for ($x=0; $x < $rel_mx['body']['rowcount'] ; $x++) {

					if ($rel_mx['body']['rowcount'] == 1) {
					 	$mx[$x] = $rel_mx['body']['detail'];
					 }else{
					 	$mx[$x] = $rel_mx['body']['detail'][$x];
					 } 
					 
				}
				$rel_cf['datarow'][$i]['sub'] = $mx;
			}

	}

	return $rel_cf;
}

// 就诊卡缴费划价
public function ic_jiaofei_huajia(){
	/*$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
 	$patient_id = I("post.patient_id");//病人ID
 	$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20140410</actdate>
					<trdate>20140410</trdate>
					<trtime>084036</trtime>
					<trcode>Z304</trcode>
				</head>
				<body>
					<acttype>3</acttype>
					<actno>'.$card_no.'</actno>
					<medid>'.$patient_id.'</medid>
					<medpos></medpos>
					<medflag>0</medflag>
					<medpay>0</medpay>
				</body>
			</root';*/
	/**************日记记录开始**************/
	// $data['card_code'] = I("post.card_code");
	// $data['patient_id'] = I("post.patient_id");
	// $data['card_no'] = I("post.card_no");
	// $data['op_name'] = "自费患者划价";
	// $data['direction'] = "发送报文";
	// $data['op_code'] = I("post.op_code");
	// $data['op_xml'] = $str;
	// $data['zzj_id'] = I("post.zzj_id");
	// M("logs")->add($data);
	// $this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
	// $socketaddr = "172.16.77.18";
	// $socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	// $socketport = "2013";
	// $socket = \Socket::singleton();
	// //链接
	// $aa = $socket->connect($socketaddr,$socketport);
	// //写数据
	// $sockresult = $socket->sendrequest($str);
	// //读数据
	// $response = $socket->waitforresponse();
	// $socket->disconnect (); //关闭链接
	// if($response == "" || $response == false){
 //        $rel['head']['succflag']="2";
 //        $rel['head']['retmsg']="请求超时,请重试";
 //        $this->ajaxReturn($rel,"JSON");
	// }else{
		// $response = strstr($response,'<');
		// $temp=iconv("gbk","utf-8//IGNORE",$response);
		// $result1 = ltrim($temp,"^");
		// $result2 = strstr($result1,'<');
		// $result3 = str_replace("gbk","utf8",$result2);	
		$result3 = '<?xml version="1.0" encoding="utf8"?><root><head><actdate>20190307</actdate><trdate>20190307</trdate><trtime>080123</trtime><trcode>Z304</trcode><hisseq>0</hisseq><bhpseq></bhpseq><jdseq></jdseq><filenum>0</filenum><succflag>1</succflag><retcode>000</retcode><retmsg></retmsg></head><body><medid>91637987</medid><name>安浩</name><personamt>1800.00</personamt><medflag>0</medflag><Yhje>0.00</Yhje><Ybzf>0.00</Ybzf><Grzhzf>0.00</Grzhzf><chargetamt>1800.00</chargetamt><rctpno>20190307080123353</rctpno></body></root>';	
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);
		/**************日记记录开始**************/
		// $data['card_code'] = I("post.card_code");
		// $data['patient_id'] = I("post.patient_id");
		// $data['card_no'] = I("post.card_no");
		// $data['op_name'] = "自费患者划价";
		// $data['direction'] = "返回报文";
		// $data['op_code'] = I("post.op_code");
		// $data['op_xml'] = $result3;
		// $data['zzj_id'] = I("post.zzj_id");
		// M("logs")->add($data);
		// $this->writeLog($log_txt,$log_type);
		/***********日志记录结束****************/

		//处理交易时间格式
	    $time  =  $rel['head']['actdate'];  
	    $time = strtotime($time);
		$rel['head']['actdate'] = date("Y-m-d ",$time);//交易时间
		$this->ajaxReturn($rel,"JSON");

	// }
	

}
// 医保缴费划价
public function yibao_jiaofei_huajia(){
	/*$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$op_code = I("post.op_code");
 	$zzj_id = I("post.zzj_id");
 	$patient_id = I("post.patient_id");//病人ID
 	$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20140410</actdate>
					<trdate>20140410</trdate>
					<trtime>084036</trtime>
					<trcode>Z304</trcode>
				</head>
				<body>
					<acttype>1</acttype>
					<actno>\</actno>
					<medid>'.$patient_id.'</medid>
					<medpos></medpos>
					<medflag>1</medflag>
					<medpay>1</medpay>
				</body>
			</root';*/
	/**************日记记录开始**************/
	// $data['card_code'] = I("post.card_code");
	// $data['patient_id'] = I("post.patient_id");
	// $data['card_no'] = I("post.card_no");
	// $data['op_name'] = "自费患者划价";
	// $data['direction'] = "发送报文";
	// $data['op_code'] = I("post.op_code");
	// $data['op_xml'] = $str;
	// $data['zzj_id'] = I("post.zzj_id");
	// M("logs")->add($data);
	// $this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
	// $socketaddr = "172.16.77.18";
	// $socketaddr = M("zizhuji")->where("zzj_id='".$zzj_id."'")->getField("ip");
	// $socketport = "2013";
	// $socket = \Socket::singleton();
	// //链接
	// $aa = $socket->connect($socketaddr,$socketport);
	// //写数据
	// $sockresult = $socket->sendrequest($str);
	// //读数据
	// $response = $socket->waitforresponse();
	// $socket->disconnect (); //关闭链接
	// if($response == "" || $response == false){
 //        $rel['head']['succflag']="2";
 //        $rel['head']['retmsg']="请求超时,请重试";
 //        $this->ajaxReturn($rel,"JSON");
	// }else{
		// $response = strstr($response,'<');
		// $temp=iconv("gbk","utf-8//IGNORE",$response);
		// $result1 = ltrim($temp,"^");
		// $result2 = strstr($result1,'<');
		// $result3 = str_replace("gbk","utf8",$result2);	
		$result3 = '<?xml version="1.0" encoding="utf8"?><root><head><actdate>20190307</actdate><trdate>20190307</trdate><trtime>074331</trtime><trcode>Z304</trcode><hisseq>0</hisseq><bhpseq></bhpseq><jdseq></jdseq><filenum>0</filenum><succflag>1</succflag><retcode>000</retcode><retmsg></retmsg></head><body><medid>91536729</medid><name>董媛明</name><personamt>22.70</personamt><medflag>1</medflag><Yhje>0.00</Yhje><Ybzf>2.45</Ybzf><Grzhzf>0.00</Grzhzf><chargetamt>25.15</chargetamt><rctpno>20190307074331856</rctpno></body></root>';	
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);
		/**************日记记录开始**************/
		// $data['card_code'] = I("post.card_code");
		// $data['patient_id'] = I("post.patient_id");
		// $data['card_no'] = I("post.card_no");
		// $data['op_name'] = "自费患者划价";
		// $data['direction'] = "返回报文";
		// $data['op_code'] = I("post.op_code");
		// $data['op_xml'] = $result3;
		// $data['zzj_id'] = I("post.zzj_id");
		// M("logs")->add($data);
		// $this->writeLog($log_txt,$log_type);
		/***********日志记录结束****************/

		//处理交易时间格式
	    $time  =  $rel['head']['actdate'];  
	    $time = strtotime($time);
		$rel['head']['actdate'] = date("Y-m-d ",$time);//交易时间
		$this->ajaxReturn($rel,"JSON");

	// }
	

}

//银行卡支付
public  function yhk_pay(){
	$bank_info = I("post.bank_info");
    $total_amount =  I("post.total_amount");
     $out_trade_no =  I("post.out_trade_no");
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
    $rel["RespCode"] = $RespCode; //交易成功码
    $rel["idCard"] =  $idCard;   //银行卡号
     $rel["pz_no"] =   $pz_no ;  //凭证号
    $rel["sc_no"] =   $sc_no ;  //授权号
    $time = strtotime($jy_no);
    $jy_no =  date("Y-m-d H:i:s", $time);
    $rel["jy_no"] =   $jy_no ;  //交易日期时间
    $rel["ck_no"] =  $ck_no;

	$this->ajaxReturn($rel,"JSON");

}
/**********生成二维码*******************************/
public function ajaxGetPayUrl(){	
	// $soap = new \SoapClient(null,array('location'=>'http://172.20.14.246/soap/Service.php',uri=>'Service.php')); 
	// $out_trade_no = trim(I("post.out_trade_no"));
	// $total_amount = trim(I("post.total_amount"));
	// $subject = trim(I("post.subject"));
	// $pay_type = I("post.pay_type");
	// $source="hos040";
	// switch($pay_type){
	// 	case "alipay":
	// 	$row = $soap->getPayUrl($out_trade_no,$total_amount,$subject,$source);
	// 	break;
	// 	case "wxpay":
	// 	$row = $soap->getWeiXinPayUrl($out_trade_no,$total_amount,$subject,$source);
	// 	break;
	// }
	// $xml = simplexml_load_string($row);
	// $xml = (array)$xml;
	// $message = $xml['Message'];
	// $rel['message'] = $message;
	$rel = "";
	$this->ajaxReturn($rel,"JSON");
}
/**********查询交易状态*******************************/
public function ajaxGetPayStatus(){
	//$soap = new \SoapClient('http://182.254.218.183/soap/Service.php?wsdl'); 
	// $soap = new \SoapClient(null,array('location'=>'http://123.206.31.148/soap/Service.php',uri=>'Service.php')); 
	// $soap = new \SoapClient(null,array('location'=>'http://172.20.14.246/soap/Service.php',uri=>'Service.php'));
	// $out_trade_no = trim(I("post.out_trade_no"));
	// $source="hos040";
	// $pay_type = I("post.pay_type");
	// switch($pay_type){
	// 	case "alipay":
	// 	$row = $soap->getPayStatus($out_trade_no,$source);
	// 	break;
	// 	case "wxpay":
	// 	$row = $soap->getWxPayStatus($out_trade_no);
	// 	break;
	// }
	
	// $xml = simplexml_load_string($row);
	// $xml = (array)$xml;
	// $message = $xml['Message'];
	// $rel['message'] = (array)$message;
	//print_r($rel);
	$rel = "";
	$this->ajaxReturn($rel,"JSON");
}




/*******************************身份证缴费保存****************************************/
public function sfz_jiaofei_save(){
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");//病人ID
 	$op_code = I("post.op_code");//操作码
 	$PayAccount = I("post.PayAccount");//支付账户582***@qq.com
 	$pay_type = I("post.pay_type");//支付类型alipay wxpay bank
 	$stream_no = I("post.stream_no");//订单号
 	$trade_no = I("post.trade_no");//交易流水号
 	$ServiceFee = "";//医事服务费
 	$charge_total = I("post.charge_total");//总金额
 	$cash = I("post.cash");//自费金额
 	$zhzf = I("post.zhzf");//账户支付金额
 	$tczf = I("post.tczf");//医保支付金额
 	$ksdm = I("post.ksdm");//科室代码
 	date_default_timezone_set('PRC');
 	$trade_time = date("Y-m-d H:i:s",time());//交易时间
 	$zzj_id = I("post.zzj_id");
 	$jzid = I("post.jzid");
 	$if_ck = I("post.if_ck");
	header("Content-Type: text/html; charset=UTF-8");
	$str = '<?xml version="1.0" encoding="gb2312"?><root><commitdata><data><datarow><card_code>'.$card_code.'</card_code><card_no>'.$card_no.'</card_no><zzj_id>'.$zzj_id.'</zzj_id><ksdm>'.$ksdm.'</ksdm><jzid>'.$jzid.'</jzid><if_ck>'.$if_ck.'</if_ck><fs>ZJ</fs><recornd_sn></recornd_sn><stream_no>'.$stream_no.'</stream_no><trade_no>'.$trade_no.'</trade_no><cheque_type></cheque_type><bk_card_no>'.$PayAccount.'</bk_card_no><lx>1</lx><trade_time>'.$trade_time.'</trade_time><patient_id>'.$patient_id.'</patient_id><responce_type></responce_type><gh_flag>0</gh_flag><ChannelFlag>倍康</ChannelFlag></datarow></data></commitdata><operateinfo><info><Method>jiaofei_huajia</Method><opt_name>'.$zzj_id.'</opt_name><opt_date>'.$opt_date.'</opt_date></info></operateinfo><result></result></root>';
		/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "身份证患者缴费保存";
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
	$response = str_replace("GB2312","utf8",$response);
	$xml =iconv("gbk","UTF-8//IGNORE",$response);
	// $result = str_replace("gb2312","utf8",$result);
	// $xml =iconv("gb2312","utf8",$response);
    $socket->disconnect(); //关闭链接
	$doc = simplexml_load_string($xml);
	$rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码

	/**************日记记录开始**************/
	$data['card_code'] = $card_code;
	$data['patient_id'] = $patient_id;
	$data['card_no'] = $card_no;
	$data['op_name'] = "身份证患者缴费保存";
	$data['direction'] = "返回报文";
	$data['op_code'] = I("post.op_code");
	// $data['op_xml'] =  var_export($rel,"true");
	$data['op_xml'] = $xml;
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


/*******************************就诊卡缴费保存****************************************/
public function ic_jiaofei_save(){
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");//病人ID
 	$op_code = I("post.op_code");//操作码
 	$PayAccount = I("post.PayAccount");//支付账户582***@qq.com
 	$payway = I("post.pay_type");//支付类型alipay wxpay bank
 	$name = I("post.patient_name");
 	//自费金额为零时
    
 	if($payway == "alipay"){
 		$payway ='1';
 	}
 	if($payway == "wxpay"){
 		$payway ='2';
 	}

 	if($payway == "yhk"){
 		$payway ='3';
 	}

    if($payway == "" && $cash =="0"){
 		$payway ='1';
 	}
 	$stream_no = I("post.stream_no");//订单号
 	$trade_no = I("post.trade_no");//交易流水号
 	$PayNo  =  trim(I("post.stream_no")).",".trim(I("post.trade_no")); //支付订单号
 	$ServiceFee = "";//医事服务费
 	$charge_total = I("post.charge_total");//总金额
 	$cash = I("post.cash");//自费金额
 	$zhzf = I("post.zhzf");//账户支付金额
 	$tczf = I("post.tczf");//医保支付金额
 	$ksdm = I("post.ksdm");//科室代码
 	date_default_timezone_set('PRC');
 	$trade_time = date("Y-m-d H:i:s",time());//交易时间
 	$zzj_id = I("post.zzj_id");
 	$jzid = I("post.jzid");
 	$order_no = I("post.order_no");
 	$if_ck = I("post.if_ck");
 	$djh = I("post.djh");
	$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20140410</actdate>
					<trdate>20140410</trdate>
					<trtime>084036</trtime>
					<trcode>Z303</trcode>
				</head>
				<body>
					<acttype>3</acttype>
					<actno>'.$card_no.'</actno>
					<medid>'.$patient_id.'</medid>
					<medpos></medpos>
					<medflag>0</medflag>
					<medpay>0</medpay>
					<payway>'.$payway.'</payway>
					<medpay>0</medpay>
					<chargetamt>'.$cash.'</chargetamt>
					<PayAmt>'.$cash.'</PayAmt>
					<PayType>'.$pay_type.'</PayType>
					<bankno>'.$PayAccount.'</bankno>
					<PayNo>'.$PayNo.'</PayNo>
					<rctpno>'.$djh.'</rctpno>
				</body>
			</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "就诊卡缴费保存";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
	// $socketaddr = "172.16.77.18";
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
		/***********日志记录结束****************/
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "就诊卡缴费保存";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = var_export($rel,"true");
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
	    $rel['head']['date'] =  $this->huoqu_time_set($zzj_id);
	    //缴费数据上传
	    if ($rel['head']['succflag']=="1") {
	    	$data_sj['pay_status'] = 1;
	    	$data_sj['mobile'] = "";
	    }else{
	    	$data_sj['pay_status'] = 2;
	    }
	    $data_sj['machine_no'] = I("post.zzj_id");
	    $data_sj['patient_name'] = I("post.patient_name");
	    $data_sj['his_patient_id'] = I("post.patient_id");
	    $data_sj['insur_card_no'] = "";
	    $data_sj['his_card_no'] = I("post.card_no");
	    $data_sj['local_card_no'] = "";
	    $data_sj['patient_type'] = 2;
	    $data_sj['payment_log_type'] = 1;
	    $data_sj['trans_amount'] = I("post.cash");
	    $data_sj['coor_amount'] = '';
	    $data_sj['personal_amount'] = I("post.cash");
	    $data_sj['balance_amount'] = '';
	    if (I("post.pay_type") == 'alipay') {
	    	$data_sj['pay_type'] = 3;
	    }elseif (I("post.pay_type") == 'yhk') {
	    	$data_sj['pay_type'] = 1;
	    	$data_sj['bank_card'] = I("post.bankno");
	    	$data_sj['bank_name'] = '';
	    }elseif (I("post.pay_type") == 'wxpay') {
	    	$data_sj['pay_type'] = 4;
	    }
	    $data_sj['trade_no'] = I("post.trade_no");
	    $data_sj['order_no'] = I("post.stream_no");
	    $data_sj['created_time'] = date("Y-m-d h:i:s");
		M("payment")->add($data_sj);
		if($rel['head']['succflag']=="1"){
             $rctpno =  $rel["body"]["rctpno"]; 
			if($rel["body"]["rowcount"] =="1"){			  
	     	   $rel["mx"]["0"] = $rel["body"]["detail"];
			}else{
			    $rel["mx"] = $rel["body"]["detail"];
			}
			 $rctpno =  $rel["body"]["rctpno"]; 
			 $mingxi = $this->get_ic_jiaofei_mingxi($rctpno,$name,$card_code,$card_no,$patient_id,$op_code,$zzj_id);
			 $rel['daoyin'] = $this->get_daoyin($rctpno,$name,$acttype,$actno,$medid,$op_code,$zzj_id);
			 $rel['jiancha'] = $this->get_jiancha($rctpno,$name,$acttype,$actno,$medid,$op_code,$zzj_id);			
			 if($mingxi != ""){
			 	$rel["mingxi"]  = $mingxi;
			 }else{
			 	$rel["mingxi"] = "";
			 }
			 
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}
	}
	
}
//获取导引单
public function ceshi(){
	 	
	 	$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20140410</actdate>
					<trdate>20140410</trdate>
					<trtime>084036</trtime>
					<trcode>Z805</trcode>
				</head>
				<body>
					<OrderNum>22696971</OrderNum>
				</body>
			</root>';		
		/**************日记记录开始**************/
		$data['card_no'] = $actno;
		$data['card_code'] = $acttype;		
		$data['op_name'] = "导引单";
		$data['direction'] = "发送报文";
		$data['op_code'] = $op_code;
		$data['op_xml'] = $str;
		$data['zzj_id'] = $zzj_id;
		M("logs")->add($data);
		// echo M("logs")->getLastSql();exit();
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/	
		$zzj_id = 'zzj001';	
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

		$socket->disconnect (); //关闭链接
		if($response == "" || $response == false ){
	        $rel['head']['succflag']="-1";
	        $rel['head']['retmsg']="请求超时,请重试";
		    return  $rel;
		}else{
			$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result3 = str_replace("gbk","utf8",$result2);	
			$doc = simplexml_load_string($result3);
		    $rel['jiancha'] = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
			/*************日记记录开始*************/
			$data['card_code'] = $acttype;
			$data['patient_id'] =  $medid; //获取病案号 病人唯一id
			$data['card_no'] = $actno;
			$data['op_name'] = "导引单";
			$data['direction'] = "返回报文";
			$data['op_code'] = $op_code;
			$data['op_xml'] = $result3;
			$data['zzj_id'] = $zzj_id;
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
			$this->ajaxReturn($rel,"JSON");
		}
}
//获取导引单
public function get_jiancha($rctpno,$name,$acttype,$actno,$medid,$op_code,$zzj_id){
	 	
	 	$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20140410</actdate>
					<trdate>20140410</trdate>
					<trtime>084036</trtime>
					<trcode>Z805</trcode>
				</head>
				<body>
					<OrderNum>'.$rctpno.'</OrderNum>
				</body>
			</root>';
		/**************日记记录开始**************/
		$data['card_no'] = $actno;
		$data['card_code'] = $acttype;		
		$data['op_name'] = "检查单";
		$data['direction'] = "发送报文";
		$data['op_code'] = $op_code;
		$data['op_xml'] = $str;
		$data['zzj_id'] = $zzj_id;
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

		$socket->disconnect (); //关闭链接
		if($response == "" || $response == false ){
	        $rel['head']['succflag']="-1";
	        $rel['head']['retmsg']="请求超时,请重试";
		    return  $rel;
		}else{
			$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result3 = str_replace("gbk","utf8",$result2);	
			$doc = simplexml_load_string($result3);
		    $rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
			/*************日记记录开始*************/
			$data['card_code'] = $acttype;
			$data['patient_id'] =  $medid; //获取病案号 病人唯一id
			$data['card_no'] = $actno;
			$data['op_name'] = "检查单";
			$data['direction'] = "返回报文";
			$data['op_code'] = $op_code;
			$data['op_xml'] = $result3;
			$data['zzj_id'] = $zzj_id;
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
			return  $rel;
		}
}
//获取导引单
public function get_daoyin($rctpno,$name,$acttype,$actno,$medid,$op_code,$zzj_id){
	 	
	 	$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20140410</actdate>
					<trdate>20140410</trdate>
					<trtime>084036</trtime>
					<trcode>Z804</trcode>
				</head>
				<body>
					<OrderNum>'.$rctpno.'</OrderNum>
				</body>
			</root>';
		/**************日记记录开始**************/
		$data['card_no'] = $actno;
		$data['card_code'] = $acttype;		
		$data['op_name'] = "导引单";
		$data['direction'] = "发送报文";
		$data['op_code'] = $op_code;
		$data['op_xml'] = $str;
		$data['zzj_id'] = $zzj_id;
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

		$socket->disconnect (); //关闭链接
		if($response == "" || $response == false ){
	        $rel['head']['succflag']="-1";
	        $rel['head']['retmsg']="请求超时,请重试";
		    return  $rel;
		}else{
			$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result3 = str_replace("gbk","utf8",$result2);	
			$doc = simplexml_load_string($result3);
		    $rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
			/*************日记记录开始*************/
			$data['card_code'] = $acttype;
			$data['patient_id'] =  $medid; //获取病案号 病人唯一id
			$data['card_no'] = $actno;
			$data['op_name'] = "导引单";
			$data['direction'] = "返回报文";
			$data['op_code'] = $op_code;
			$data['op_xml'] = $result3;
			$data['zzj_id'] = $zzj_id;
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
			return  $rel;
		}
}
//医保卡获取缴费明细

public function yibao_jiaofei_save(){
	$card_no = I("post.card_no");
 	$card_code = I("post.card_code");
 	$patient_id = I("post.patient_id");//病人ID
 	$op_code = I("post.op_code");//操作码
 	$PayAccount = I("post.PayAccount");//支付账户582***@qq.com
 	$payway = I("post.pay_type");//支付类型alipay wxpay bank
 	$cash  =  I("post.cash");
 	//自费金额为零时
    
 	if($payway == "alipay"){
 		$payway ='1';
 	}
 	if($payway == "wxpay"){
 		$payway ='2';
 	}

 	if($payway == "yhk"){
 		$payway ='3';
 	}

    if($payway == "" && $cash =="0"){
 		$payway ='1';
 	}
 	$stream_no = I("post.stream_no");//订单号
 	$trade_no = I("post.trade_no");//交易流水号
 	$PayNo  =  trim(I("post.stream_no")).",".trim(I("post.trade_no")); //支付订单号
 	$ServiceFee = "";//医事服务费
 	$charge_total = I("post.charge_total");//总金额
 	$cash = I("post.cash");//自费金额
 	$zhzf = I("post.zhzf");//账户支付金额
 	$tczf = I("post.tczf");//医保支付金额
 	$ksdm = I("post.ksdm");//科室代码
 	date_default_timezone_set('PRC');
 	$trade_time = date("Y-m-d H:i:s",time());//交易时间
 	$zzj_id = I("post.zzj_id");
 	$jzid = I("post.jzid");
 	$order_no = I("post.order_no");
 	$if_ck = I("post.if_ck");
 	$djh = I("post.djh");
 	$chargetamt = I("post.chargetamt");
	$str = '<?xml version="1.0" encoding="GBK" ?>
			<root>
				<head>
					<actdate>20140410</actdate>
					<trdate>20140410</trdate>
					<trtime>084036</trtime>
					<trcode>Z303</trcode>
				</head>
				<body>
					<acttype>1</acttype>
					<actno>\</actno>
					<medid>'.$patient_id.'</medid>
					<medpos></medpos>
					<medflag>1</medflag>
					<medpay>1</medpay>
					<payway>'.$payway.'</payway>
					<chargetamt>'.$chargetamt.'</chargetamt>
					<PayAmt>'.$cash.'</PayAmt>
					<bankno>'.$PayAccount.'</bankno>
					<PayNo>'.$PayNo.'</PayNo>
					<rctpno>'.$djh.'</rctpno>
				</body>
			</root>';
	/**************日记记录开始**************/
	$data['card_code'] = I("post.card_code");
	$data['patient_id'] = I("post.patient_id");
	$data['card_no'] = I("post.card_no");
	$data['op_name'] = "医保卡缴费保存";
	$data['direction'] = "发送报文";
	$data['op_code'] = I("post.op_code");
	$data['op_xml'] = $str;
	$data['zzj_id'] = I("post.zzj_id");
	M("logs")->add($data);
	$this->writeLog($log_txt,$log_type);
	/**********日志记录结束*******************/
	//实例化socket
	// $socketaddr = "172.16.77.18";
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
		/***********日志记录结束****************/
		$doc = simplexml_load_string($result3);
		$rel = json_decode(json_encode($doc),true);
		/**************日记记录开始**************/
		$data['card_code'] = I("post.card_code");
		$data['patient_id'] = I("post.patient_id");
		$data['card_no'] = I("post.card_no");
		$data['op_name'] = "医保卡缴费保存";
		$data['direction'] = "返回报文";
		$data['op_code'] = I("post.op_code");
		$data['op_xml'] = var_export($rel,"true");
		$data['zzj_id'] = I("post.zzj_id");
		M("logs")->add($data);
		$this->writeLog($log_txt,$log_type);
		/**********日志记录结束*******************/
	       $rel['head']['date'] =  $this->huoqu_time_set($zzj_id);
	     //缴费数据上传
	    if ($rel['head']['succflag']=="1") {
	    	$data_sj['pay_status'] = 1;
	    	$data_sj['mobile'] = "";
	    }else{
	    	$data_sj['pay_status'] = 2;
	    	$data_sj['mobile'] = "";
	    }
	    $data_sj['machine_no'] = I("post.zzj_id");
	    $data_sj['patient_name'] = I("post.patient_name");
	    $data_sj['his_patient_id'] = I("post.patient_id");
	    $data_sj['insur_card_no'] = "";
	    $data_sj['his_card_no'] = I("post.card_no");
	    $data_sj['local_card_no'] = "";
	    $data_sj['patient_type'] = 1;
	    $data_sj['payment_log_type'] = 1;
	    $data_sj['trans_amount'] = I("post.chargetamt");
	    $data_sj['coor_amount'] = I("post.tczf");
	    $data_sj['personal_amount'] = I("post.cash");
	    $data_sj['balance_amount'] = '';
	    if (I("post.pay_type") == 'alipay') {
	    	$data_sj['pay_type'] = 3;
	    }elseif (I("post.pay_type") == 'yhk') {
	    	$data_sj['pay_type'] = 1;
	    	$data_sj['bank_card'] = I("post.bankno");
	    	$data_sj['bank_name'] = '';
	    }elseif (I("post.pay_type") == 'wxpay') {
	    	$data_sj['pay_type'] = 4;
	    }
	    $data_sj['trade_no'] = I("post.trade_no");
	    $data_sj['order_no'] = I("post.stream_no");
	    $data_sj['created_time'] = date("Y-m-d h:i:s");
		M("payment")->add($data_sj);
		
		if($rel['head']['succflag']=="1"){

			if($rel["body"]["rowcount"] =="1"){
			   $rel["mx"]["0"] = $rel["body"]["detail"];
			}else{
			    $rel["mx"] = $rel["body"]["detail"];
			}

			   $rctpno =  $rel["body"]["rctpno"]; 

			$mingxi = $this->get_yb_jiaofei_mingxi($rctpno,$name,$card_code,$card_no,$patient_id,$op_code,$zzj_id);
			$rel['daoyin'] = $this->get_daoyin($rctpno,$name,$acttype,$actno,$medid,$op_code,$zzj_id);
			$rel['jiancha'] = $this->get_jiancha($rctpno,$name,$acttype,$actno,$medid,$op_code,$zzj_id);			
			 if($mingxi != ""){
			 	$rel["mingxi"]  = $mingxi;
			 }else{
			 	$rel["mingxi"] = "";
			 }
			
			$this->ajaxReturn($rel,"JSON");
		}else{
			$this->ajaxReturn($rel,"JSON");
		}

	}
	
}


//获取就诊卡缴费明细
public function get_ic_jiaofei_mingxi($rcptno,$name,$acttype,$actno,$medid,$op_code,$zzj_id){
	 	
	 	$str = '<?xml version="1.0" encoding="GBK" ?>
					<root>
						<head>
							<actdate>20140410</actdate>
							<trdate>20140410</trdate>
							<trtime>084036</trtime>
							<trcode>Z901</trcode>
						</head>
						<body>
							<rcptno>'.$rcptno.'</rcptno>
							<rcptbat></rcptbat>
							<name>'.$name.'</name>
							<acttype>'.$acttype.'</acttype>
							<actno>'.$actno.'</actno>
							<medid>'.$medid.'</medid>
						</body>
					</root>
					';

		/**************日记记录开始**************/
		$data['card_no'] = $actno;
		$data['card_code'] = $acttype;		
		$data['op_name'] = "获取就诊卡患者缴费明细信息";
		$data['direction'] = "发送报文";
		$data['op_code'] = $op_code;
		$data['op_xml'] = $str;
		$data['zzj_id'] = $zzj_id;
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

		$socket->disconnect (); //关闭链接
		if($response == "" || $response == false ){
	        $rel['head']['succflag']="-1";
	        $rel['head']['retmsg']="请求超时,请重试";
	        $mingxi ="";
		    return  $mingxi;
		}else{
			$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result3 = str_replace("gbk","utf8",$result2);	
			$doc = simplexml_load_string($result3);
		    $rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
			/*************日记记录开始*************/
			$data['card_code'] = $acttype;
			$data['patient_id'] =  $medid; //获取病案号 病人唯一id
			$data['card_no'] = $actno;
			$data['op_name'] = "获取就诊卡患者缴费明细信息";
			$data['direction'] = "返回报文";
			$data['op_code'] = $op_code;
			$data['op_xml'] = $result3;
			$data['zzj_id'] = $zzj_id;
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
			 $rel['head']['date'] =  $this->huoqu_time_set($zzj_id);
			if($rel['head']['succflag']=="1"){

				if($rel["body"]["rowcount"] =="1"){
				   $rel["mingxi_list"]["0"] = $rel["body"]["detail"];
				}else{
				    $rel["mingxi_list"] = $rel["body"]["detail"];
				}
				 $mingxi=  $rel["mingxi_list"];
				 
				return  $mingxi;
			}else{
				 $mingxi = "";
				return  $mingxi;
			}
		}
}


//获取医保卡缴费明细
public function get_yb_jiaofei_mingxi($rcptno,$name,$acttype,$actno,$medid,$op_code,$zzj_id){
	 	
	 	$str = '<?xml version="1.0" encoding="GBK" ?>
					<root>
						<head>
							<actdate>20140410</actdate>
							<trdate>20140410</trdate>
							<trtime>084036</trtime>
							<trcode>Z901</trcode>
						</head>
						<body>
							<rcptno>'.$rcptno.'</rcptno>
							<rcptbat></rcptbat>
							<name>'.$name.'</name>
							<acttype>'.$acttype.'</acttype>
							<actno>\</actno>
							<medid>'.$medid.'</medid>
						</body>
					</root>
					';

		/**************日记记录开始**************/
		$data['card_no'] = $actno;
		$data['card_code'] = $acttype;		
		$data['op_name'] = "获取医保卡患者缴费明细信息";
		$data['direction'] = "发送报文";
		$data['op_code'] = $op_code;
		$data['op_xml'] = $str;
		$data['zzj_id'] = $zzj_id;
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

		$socket->disconnect (); //关闭链接
		if($response == "" || $response == false ){
	        $rel['head']['succflag']="-1";
	        $rel['head']['retmsg']="请求超时,请重试";
	        $mingxi ="";
		    return  $mingxi;
		}else{
			$response = strstr($response,'<');
			$temp=iconv("gbk","utf-8//IGNORE",$response);
			$result1 = ltrim($temp,"^");
			$result2 = strstr($result1,'<');
			$result3 = str_replace("gbk","utf8",$result2);	
			$doc = simplexml_load_string($result3);
		    $rel = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
			/*************日记记录开始*************/
			$data['card_code'] = $acttype;
			$data['patient_id'] =  $medid; //获取病案号 病人唯一id
			$data['card_no'] = $actno;
			$data['op_name'] = "获取医保卡患者缴费明细信息";
			$data['direction'] = "返回报文";
			$data['op_code'] = $op_code;
			$data['op_xml'] = var_export($rel,"true");
			$data['zzj_id'] = $zzj_id;
			M("logs")->add($data);
			$this->writeLog($log_txt,$log_type);
			/**********日志记录结束*******************/
			 $rel['head']['date'] =  $this->huoqu_time_set($zzj_id);
			if($rel['head']['succflag']=="1"){

				if($rel["body"]["rowcount"] =="1"){
				   $rel["mingxi_list"]["0"] = $rel["body"]["detail"];
				}else{
				    $rel["mingxi_list"] = $rel["body"]["detail"];
				}
				 $mingxi=  $rel["mingxi_list"];
				 
				return  $mingxi;
			}else{
				 $mingxi = "";
				return  $mingxi;
			}
		}
}


function huoqu_time_set($zzj_id){
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
	
	$response = strstr($response,'<');
	$temp=iconv("gbk","utf-8//IGNORE",$response);
	$result1 = ltrim($temp,"^");
	$result2 = strstr($result1,'<');
	$result3 = str_replace("gbk","utf8",$result2);
	/***********日志记录结束****************/
	$doc = simplexml_load_string($result3);
	$rels = json_decode(json_encode($doc),true);//json_encode()将json格式字符串转化成数组,json_decode()对变量进行JSON编码
    
	//HIS服务器时间 如2017-11-23 10:37:30
	$fwqsj = $rels["body"]['CurrentDateTime'];
	 return  $fwqsj;

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