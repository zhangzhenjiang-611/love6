<?php
include_once("ez_sql_core.php");
include_once("ez_sql_mysql.php");
include_once("Socket.php");	
include_once("Log.php");
require_once 'phpqrcode.php'; 
class call{
	private $app_root = 'rwzb_pay_old/';
	private $hos = 'hos001';
	private $pay = 'pay';
	private $pay_server = '211.159.153.49';

	private $local_db_host = 'localhost';
	private $local_db_port = '3306';
	private $local_db_name = 'auto_device';
	private $local_db_user = 'root';
	private $local_db_pwd = '';
	private $local_db_charset = 'utf8';

	private $remote_db_host = 'bj-cdb-6ace98gr.sql.tencentcdb.com';
	private $remote_db_port = '3306';
	private $remote_db_name = 'alipay';
	private $remote_db_user = 'root';
	private $remote_db_pwd = '801147Qaz!@#';
	private $remote_db_charset = 'gb2312';

	private $zj_server = 'localhost:80';

	/**
	*@$out_trade_no 商户订单号
	*@$auth_code 支付授权码(32位) 
	*@$total_amount 支付部金额
	*@$subject 订单标题
	*@$source 商户代码
	**/
	public function barpay($out_trade_no, $auth_code, $total_amount, $subject,$source,$operator_id){
		Log::Write("窗口支付首次传入参数 out_trade_no:".$out_trade_no.",auth_code:".$auth_code.",total_amount:".$total_amount.",subject:".$subject.",source.".$source.",operator_id:".$operator_id);
		require_once "../".$this->pay."_".$this->hos."/alipay/f2fpay/F2fpay.php";
		$db = new ezSQL_mysql($this->local_db_user,$this->local_db_pwd,$this->local_db_name,$this->local_db_host.':'.$this->local_db_port,$this->local_db_charset);
		$db->query("set names utf8");
		$rel = "<?xml version=\"1.0\" encoding=\"gb2312\"?><root>";  
		$rel .= "<Message>";
		if($out_trade_no!=""&&$auth_code!=""){
			$f2fpay = new F2fpay(); 
			$out_trade_no = trim($out_trade_no);
			$auth_code = trim($auth_code);
			$total_amount = trim($total_amount);
			$subject = trim($subject);
			$response = $f2fpay->barpay($out_trade_no, $auth_code, $total_amount, $subject,$source);
			Log::Write("支付宝xml字符串:".var_export($response,true));
			$rel .= "<Code>".$response->alipay_trade_pay_response->code."</Code>"; 
			$rel .= "<Msg>".$response->alipay_trade_pay_response->msg."</Msg>";

			$rel .= "<TradeNo>".$response->alipay_trade_pay_response->trade_no."</TradeNo>";
			$rel .= "<OutTradeNo>".$response->alipay_trade_pay_response->out_trade_no."</OutTradeNo>";
			$rel .= "<BuyerLogonId>".$response->alipay_trade_pay_response->buyer_logon_id."</BuyerLogonId>";
			$rel .= "<BuyerPayAmount>".$response->alipay_trade_pay_response->buyer_pay_amount."</BuyerPayAmount>";
			$rel .= "<BuyerUserId>".$response->alipay_trade_pay_response->buyer_user_id."</BuyerUserId>";
			$rel .= "<InvoiceAmount>".$response->alipay_trade_pay_response->invoice_amount."</InvoiceAmount>";
			$rel .= "<PointAmount>".$response->alipay_trade_pay_response->point_amount."</PointAmount>";
			$rel .= "<ReceiptAmount>".$response->alipay_trade_pay_response->receipt_amount."</ReceiptAmount>";
		
			$rel .= "<TotalAmount>".$response->alipay_trade_pay_response->total_amount."</TotalAmount>";
			$rel .= "<TradeStatus>".$response->alipay_trade_pay_response->trade_status."</TradeStatus>"; 
			if($response->alipay_trade_pay_response->msg!="Success"){
				$rel .= "<SubCode>".$response->alipay_trade_pay_response->sub_code."</SubCode>";
				$rel .= "<SubMsg>".$response->alipay_trade_pay_response->sub_msg."</SubMsg>";
			}else{
				$rel .= "<SubCode>0</SubCode>";
				$rel .= "<SubMsg>0</SubMsg>";
			}
			$rel .= "<CodeEmpty>0</CodeEmpty>";
			$rel .= "</Message>";
		}else{
			$rel .= "<CodeEmpty>1</CodeEmpty>";
		}
		$rel.="</root>";
		//如果支付成功 则入库记录
		if($response->alipay_trade_pay_response->code==10000){

		}
		//开始数据库记录日志
			$sql = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,subject,source,op_name,xml,operator_id) values ('alipay','".$out_trade_no."','".$response->alipay_trade_pay_response->trade_no."','".$total_amount."','".$subject."','".$source."','"."开始窗口支付"."',\"".var_export((array)$response->alipay_trade_pay_response,"true")."\",'".$operator_id."')"; 
			$db->query($sql);
			Log::Write(mysql_error()."扫码台支付日志记录SQL:".$sql);
		//Log::Write("xml字符串:".$rel);
		return $rel;
	}
	/**
	*@$out_trade_no
	*@$total_amount
	*@$subject
	*获取支付地址并生成二维码
	**/
	public function getPayUrl($out_trade_no,$total_amount,$subject,$source,$operator_id=""){
	   /* echo $out_trade_no."<br>";
	    echo $total_amount."<br>";
	    echo $subject."<br>";
	    echo $source."<br>";
	    echo $operator_id."<br>";
	    exit;*/

		Log::Write("支付宝支付传入参数 $out_trade_no,$total_amount,$subject,$source,$operator_id");
		require_once "../".$this->pay."_".$this->hos."/alipay/f2fpay/F2fpay.php";
		$db = new ezSQL_mysql($this->local_db_user,$this->local_db_pwd,$this->local_db_name,$this->local_db_host.':'.$this->local_db_port,$this->local_db_charset);
		$db->query("set names utf8");
		
		if (trim($out_trade_no)!=""){   
			$f2fpay = new F2fpay();
			$out_trade_no = trim($out_trade_no);
			$total_amount = trim($total_amount);
			$subject = trim($subject);
			$response = $f2fpay->qrpay($out_trade_no,  $total_amount, $subject,$source);
			Log::Write("支付宝支付返回参数:".var_export($response,"true"));
			$url = $response->alipay_trade_precreate_response->qr_code; 
			$time = $out_trade_no;
			$filename = "../".$this->pay."_".$this->hos."/erweima/".$time.".png";
			$errorCorrectionLevel = 'H';  
			$matrixPointSize = 10;
			if(!is_file($filename)){
				QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);  
			}

			$sql = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,subject,source,op_name,xml,operator_id) values ('alipay','".$out_trade_no."','','".$total_amount."','".$subject."','".$source."','"."获取支付二维码"."',\"".var_export($response,"true")."\",'".$operator_id."')"; 

			$db->query($sql);

			$logo = "../".$this->pay."_".$this->hos."/logo.png";
			$QR = $filename;
			if ($logo !== FALSE) {   
			    $QR = imagecreatefromstring(file_get_contents($QR));   
			    $logo = imagecreatefromstring(file_get_contents($logo));   
			    $QR_width = imagesx($QR);//二维码图片宽度   
			    $QR_height = imagesy($QR);//二维码图片高度   
			    $logo_width = imagesx($logo);//logo图片宽度   
			    $logo_height = imagesy($logo);//logo图片高度   
			    $logo_qr_width = $QR_width / 5;   
			    $scale = $logo_width/$logo_qr_width;   
			    $logo_qr_height = $logo_height/$scale;   
			    $from_width = ($QR_width - $logo_qr_width) / 2;   
			    //重新组合图片并调整大小   
			    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,   
			    $logo_qr_height, $logo_width, $logo_height);   
			}   
			//输出图片 
			imagepng($QR, $filename);

			$imgurl= '/'.$this->app_root.$this->pay."_".$this->hos."/erweima/".$time.".png";

			$rel = "<?xml version=\"1.0\" encoding=\"utf8\"?><root>";
			$rel .= "<Message>";
			$rel .= "<imgurl>http://".$this->zj_server.$imgurl."</imgurl>";
			$rel .= "</Message>";
			$rel.="</root>";
			return $rel;
		}
	}
	/**
	*订单查询接口
	**/
	public function getTrade($out_trade_no,$source){
		require_once "../".$this->pay."_".$this->hos."/alipay/f2fpay/F2fpay.php";
		$f2fpay = new F2fpay();
		$status=""; 
		if (trim($out_trade_no)!=""){ 
			$rel = "<?xml version=\"1.0\" encoding=\"utf8\"?><root>";
			$rel .= "<Message>";
			$response = $f2fpay->query($out_trade_no);
			
				$rel .= "<Code>".$response->alipay_trade_query_response->code."</Code>"; 
				$rel .= "<Msg>".$response->alipay_trade_query_response->msg."</Msg>";
				$rel .= "<TradeNo>".$response->alipay_trade_query_response->trade_no."</TradeNo>";
				$rel .= "<OutTradeNo>".$response->alipay_trade_query_response->out_trade_no."</OutTradeNo>";
				$rel .= "<BuyerLogonId>".$response->alipay_trade_query_response->buyer_logon_id."</BuyerLogonId>";
				$rel .= "<BuyerPayAmount>".$response->alipay_trade_query_response->buyer_pay_amount."</BuyerPayAmount>";
				$rel .= "<BuyerUserId>".$response->alipay_trade_query_response->buyer_user_id."</BuyerUserId>";
				$rel .= "<InvoiceAmount>".$response->alipay_trade_query_response->invoice_amount."</InvoiceAmount>";
				$rel .= "<PointAmount>".$response->alipay_trade_query_response->point_amount."</PointAmount>";
				$rel .= "<ReceiptAmount>".$response->alipay_trade_query_response->receipt_amount."</ReceiptAmount>";
				$rel .= "<SendPayDate>".$response->alipay_trade_query_response->send_pay_date."</SendPayDate>";
				$rel .= "<TotalAmount>".$response->alipay_trade_query_response->total_amount."</TotalAmount>";
				$rel .= "<TradeStatus>".$response->alipay_trade_query_response->trade_status."</TradeStatus>"; 
			$rel .= "</Message>";
			$rel.="</root>";
			return $rel;   
		}
		
	}
	/**
	*查询订单付款状态接口
	**/
	public function getPayStatus($out_trade_no,$source){
		require_once "../".$this->pay."_".$this->hos."/alipay/f2fpay/F2fpay.php";
		$f2fpay = new F2fpay();
		$db = new ezSQL_mysql($this->local_db_user,$this->local_db_pwd,$this->local_db_name,$this->local_db_host.':'.$this->local_db_port,$this->local_db_charset);
		$db->query("set names utf8");
		Log::write("查询订单状态：".$out_trade_no);  
		$status=""; 
		if (trim($out_trade_no)!=""){ 
			$rel = "<?xml version=\"1.0\" encoding=\"utf8\"?><root>";
			$rel .= "<Message>";
			$response = $f2fpay->query($out_trade_no);
			if($response->alipay_trade_query_response->sub_code!=""){
				$status = $response->alipay_trade_query_response->sub_code;
			}else if($response->alipay_trade_query_response->trade_status!=""){
				$status = $response->alipay_trade_query_response->trade_status;
				switch($status){
				case "WAIT_BUYER_PAY":
				//开始数据库记录日志
				$sql_s = "select id from alipay_logs where out_trade_no='".$out_trade_no."' and op_name='等待付款'";
				$result1 = $db->get_row($sql_s);
				if($result1->id!=""){//update
					$sql2 = "update alipay_logs set op_date='".date("Y-m-d H:i:s")."' where out_trade_no='".$out_trade_no."' and op_name='等待付款'"; 		
				}else{//insert
					$sql2 = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,subject,source,op_name,xml,operator_id) values ('alipay','".$out_trade_no."','','','','".$source."','"."等待付款"."',\"".var_export((array)$response->alipay_trade_query_response,"true")."\",'".$operator_id."')"; 		 
				}
				
				$db->query($sql2);
				Log::write(mysql_error().$sql2);
				break;
				case "TRADE_SUCCESS":
				//开始数据库记录日志
				$sql_s2 = "select id from alipay_logs where out_trade_no='".$out_trade_no."' and op_name='付款成功'";
				$result2 = $db->get_row($sql_s2);
				if($result2->id!=""){
					 
				}else{
					if(strpos($out_trade_no,'t')){
					$sql = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,subject,source,op_name,xml,duizhang,operator_id) values ('alipay','".$out_trade_no."','".$response->alipay_trade_query_response->trade_no."','".$response->alipay_trade_query_response->total_amount."','','".$source."','"."付款成功"."',\"".var_export((array)$response->alipay_trade_query_response,"true")."\",'y','".$operator_id."')"; 
					$db->query($sql);
					}else{
						$sql = "insert into alipay_logs (`pay_type`,`out_trade_no`,`trade_no`,`total_amount`,`subject`,`source`,`op_name`,`xml`,`duizhang`,`operator_id`) values ('alipay','".$out_trade_no."','".$response->alipay_trade_query_response->trade_no."','".$response->alipay_trade_query_response->total_amount."','','".$source."','"."付款成功"."',\"".var_export((array)$response->alipay_trade_query_response,"true")."\",'c','".$operator_id."')"; 
						$db->query($sql);
					}
				}	
				
				
				break;
				}
				$rel .= "<Code>".$response->alipay_trade_query_response->code."</Code>"; 
				$rel .= "<Msg>".$response->alipay_trade_query_response->msg."</Msg>";
				$rel .= "<TradeNo>".$response->alipay_trade_query_response->trade_no."</TradeNo>";
				$rel .= "<OutTradeNo>".$response->alipay_trade_query_response->out_trade_no."</OutTradeNo>";
				$rel .= "<BuyerLogonId>".$response->alipay_trade_query_response->buyer_logon_id."</BuyerLogonId>";
				$rel .= "<BuyerPayAmount>".$response->alipay_trade_query_response->buyer_pay_amount."</BuyerPayAmount>";
				$rel .= "<BuyerUserId>".$response->alipay_trade_query_response->buyer_user_id."</BuyerUserId>";
				$rel .= "<InvoiceAmount>".$response->alipay_trade_query_response->invoice_amount."</InvoiceAmount>";
				$rel .= "<PointAmount>".$response->alipay_trade_query_response->point_amount."</PointAmount>";
				$rel .= "<ReceiptAmount>".$response->alipay_trade_query_response->receipt_amount."</ReceiptAmount>";
				$rel .= "<SendPayDate>".$response->alipay_trade_query_response->send_pay_date."</SendPayDate>";
				$rel .= "<TotalAmount>".$response->alipay_trade_query_response->total_amount."</TotalAmount>";
					
			
			}
			$rel .= "<PayStatus>".$status."</PayStatus>"; 
			
			$rel .= "</Message>";
			$rel.="</root>";
			Log::write("查询订单状态返回XML：".$rel); 
			return $rel;   
		}
		
	}
	/**
	*撤消订单接口
	**/
	public function cancel($out_trade_no,$trade_no,$source){
		require_once "../".$this->pay."_".$this->hos."/alipay/f2fpay/F2fpay.php";
		$f2fpay = new F2fpay();
		$rel = "<?xml version=\"1.0\" encoding=\"utf8\"?><root>";
		$rel .= "<Message>";
		if (trim($out_trade_no)!=""){ 
			$response = $f2fpay->cancel($out_trade_no,$trade_no);
			$rel .= "<CodeEmpty>0</CodeEmpty>";
			$rel .= "<Code>".$response->alipay_trade_cancel_response->code."</Code>"; 
			$rel .= "<Msg>".$response->alipay_trade_cancel_response->msg."</Msg>";
			if($response->alipay_trade_cancel_response->Msg=="Success"){
				$rel .= "<OutTrade>".$response->alipay_trade_cancel_response->msg."</OutTrade>";
				$rel .= "<Trade>".$response->alipay_trade_cancel_response->msg."</Trade>";
			}
			
		}else{
			$rel .= "<CodeEmpty>1</CodeEmpty>";    
		}
		$rel .= "</Message>";
			$rel.="</root>";
			return $rel;
	} 

	/**
	*申请退款接口
	**/
	public function refund($trade_no,$total_amount,$source,$operator_id){
		Log::write("支付宝退款入参：$trade_no,$total_amount,$source,$operator_id");
		require_once "../".$this->pay."_".$this->hos."/alipay/f2fpay/F2fpay.php"; 
		$db = new ezSQL_mysql($this->local_db_user,$this->local_db_pwd,$this->local_db_name,$this->local_db_host.':'.$this->local_db_port,$this->local_db_charset);
		$db->query("set names utf8");
		$out_request_no = date("YmdHis");
		$f2fpay = new F2fpay();
		$rel = "<?xml version=\"1.0\" encoding=\"utf8\"?><root>";
		$rel .= "<Message>";
		if (trim($out_trade_no)!=""||trim($trade_no)!=""){ 
			$response = $f2fpay->refund($trade_no,$total_amount,$out_request_no);
			Log::write("response:".var_export($response,true));
			if(!$response)
			{
				$rel .="<code>20000</code>";
				$rel .="<Msg>Service Currently Unavailable </Msg>";
				$rel .="<sub_code>isp.unknow-error</sub_code>";
				$rel .="<sub_msg >系统繁忙</sub_msg>";
			}
			else
			{
				$rel .= "<CodeEmpty>0</CodeEmpty>";
				$rel .= "<Code>".$response->alipay_trade_refund_response->code."</Code>"; 
				if(isset($response->alipay_trade_refund_response->out_trade_no))
				{
					$rel .= "<OutTrade>".$response->alipay_trade_refund_response->out_trade_no."</OutTrade>"; 
					$rel .="<out_refund_no>".$out_request_no."</out_refund_no>";
					$out_trade_no = $response->alipay_trade_refund_response->out_trade_no;
				}
				if(isset($response->alipay_trade_refund_response->trade_no))
				{
					$rel .= "<TradeNo>".$response->alipay_trade_refund_response->trade_no."</TradeNo>";
				}
				$rel .= "<Msg>".$response->alipay_trade_refund_response->msg."</Msg>"; 
				if($response->alipay_trade_refund_response->msg!="Success"){
					$rel .= "<SubCode>".$response->alipay_trade_refund_response->sub_code."</SubCode>"; 
					$rel .= "<SubMsg>".$response->alipay_trade_refund_response->sub_msg."</SubMsg>"; 
					$sql = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,source,op_name,xml,duizhang,operator_id) values ('alipay','".$out_trade_no."','".(isset($response->alipay_trade_refund_response->trade_no) ? $response->alipay_trade_refund_response->trade_no : '')."','".-$response->alipay_trade_refund_response->send_back_fee."','".$source."','退款失败',\"".var_export((array)$response->alipay_trade_refund_response,"true")."\",'f','".$operator_id."')"; 
				}else{
					$sql = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,source,op_name,xml,duizhang,operator_id,refund_no) values ('alipay','".$out_trade_no."','".(isset($response->alipay_trade_refund_response->trade_no) ? $response->alipay_trade_refund_response->trade_no : '')."','".-$response->alipay_trade_refund_response->send_back_fee."','".$source."','退款成功',\"".var_export((array)$response->alipay_trade_refund_response,"true")."\",'t','".$operator_id."','".$out_request_no."')"; 
					
				}
				$db->query($sql);
				$rel .= "<RefundFee>".$response->alipay_trade_refund_response->refund_fee."</RefundFee>"; 
				$rel .= "<SendBackFee>".$response->alipay_trade_refund_response->send_back_fee."</SendBackFee>";
			}
		}else{
			Log::write("退款失败：订单号或者流水号不能为空");
			$rel .= "<CodeEmpty>1</CodeEmpty>"; 
		}
		
		$rel .= "</Message>";
		$rel.="</root>";
		Log::write("退款返回XML".$rel);
		return $rel;
	}
	public function getWeiXinPayUrl($out_trade_no,$total_fee,$body,$source){
		Log::write("微信支付传入参数：$out_trade_no,$total_fee,$body,$source");
		$total_fee = $total_fee*100;
		$operator_id = "wx057"; 
		$db = new ezSQL_mysql($this->local_db_user,$this->local_db_pwd,$this->local_db_name,$this->local_db_host.':'.$this->local_db_port,$this->local_db_charset);
		$db->query("set names utf8");
		Log::write("\r\n/******************************************************/\r\n开始调用 微信 支付");
		require_once "../".$this->pay."_".$this->hos."/wxpay/lib/WxPay.Api.php";    
		require_once "../".$this->pay."_".$this->hos."/wxpay/example/WxPay.NativePay.php";
		$notify = new NativePay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody($body);//商品简单描述
		$input->SetAttach($body);//*附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据\
		$input->SetOut_trade_no($out_trade_no); //商户系统内部的订单号,32个字符内、可包含字母,
		$input->SetTotal_fee($total_fee); //订单总金额，单位为分
		//$input->SetTime_start(date("YmdHis"));
		//$input->SetTime_expire(date("YmdHis", time() + 300));
		$input->SetGoods_tag("test");  //商品标记，代金券或立减优惠功能的参数
		$input->SetNotify_url("http://".$this->pay_server."/mz/Home/AliPay/getWeiXinSynInfo/source/".$source);
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($out_trade_no);
		$result = $notify->GetPayUrl($input);
		//Log::write("参数报文:".var_dump($input,//true));
		$url = $result["code_url"];
		//开始数据库记录日志
		$sql = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,subject,source,op_name,xml,operator_id) values ('weixin','".$out_trade_no."','','".$total_fee."','".$body."','".$source."','"."获取支付二维码"."',\"".var_export($result,"true")."\",'".$operator_id."')"; 
		$db->query($sql);
		$time = $out_trade_no;
			$filename = "../".$this->pay."_".$this->hos."/erweima/".$time.".png";
			// 纠错级别：L、M、Q、H  
			$errorCorrectionLevel = 'H';  
			// 点的大小：1到10  
			$matrixPointSize = 10;
			if(!is_file($filename)){
				QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);  
				
			}
			$logo = "../".$this->pay."_".$this->hos."/wxlogo.png";
			$QR = $filename;
			if ($logo !== FALSE) {   
				$QR = imagecreatefromstring(file_get_contents($QR));   
				$logo = imagecreatefromstring(file_get_contents($logo));   
				$QR_width = imagesx($QR);//二维码图片宽度   
				$QR_height = imagesy($QR);//二维码图片高度   
				$logo_width = imagesx($logo);//logo图片宽度   
				$logo_height = imagesy($logo);//logo图片高度   
				$logo_qr_width = $QR_width / 5;   
				$scale = $logo_width/$logo_qr_width;   
				$logo_qr_height = $logo_height/$scale;   
				$from_width = ($QR_width - $logo_qr_width) / 2;   
				//重新组合图片并调整大小   
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,   
				$logo_qr_height, $logo_width, $logo_height);   
			}   
			imagepng($QR,"../".$this->pay."_".$this->hos."/erweima/".$time.".png");

			$imgurl= '/'.$this->app_root.$this->pay."_".$this->hos."/erweima/".$time.".png";

			$rel = "<?xml version=\"1.0\" encoding=\"utf8\"?><root>";
			$rel .= "<Message>";
			$rel .= "<imgurl>http://".$this->zj_server.$imgurl."</imgurl>";
			$rel .= "</Message>";
			$rel.="</root>";
			Log::write($rel);
			return $rel;
	}
	/**
	*查询微信订单付款状态接口
	**/
	public function getWxPayStatus($out_trade_no){
		require_once "../".$this->pay."_".$this->hos."/wxpay/lib/WxPay.Api.php";
		Log::write("查询微信订单状态：".$out_trade_no);  
		$status=""; 
		if (trim($out_trade_no)!=""){ 
			$rel = "<?xml version=\"1.0\" encoding=\"utf8\"?><root>";
			$rel .= "<Message>";
			$input = new WxPayOrderQuery();
			$input->SetOut_trade_no($out_trade_no);
			$response = WxPayApi::orderQuery($input);
		
			if($response['return_code']=="FAIL"){
				$status = $response['return_msg'];
			}else if($response['return_code']=="SUCCESS"){
				$status = $response['trade_state'];
				$rel .= "<return_code>".$response['return_code']."</return_code>"; 
				$rel .= "<return_msg>".$response['return_msg']."</return_msg>";
				$rel .= "<appid>".$response['appid']."</appid>";
				$rel .= "<mch_id>".$response['mch_id']."</mch_id>";
				$rel .= "<device_info>".$response['device_info']."</device_info>";
				$rel .= "<result_code>".$response['result_code']."</result_code>";
				$rel .= "<openid>".$response['openid']."</openid>";
				$rel .= "<is_subscribe>".$response['is_subscribe']."</is_subscribe>";
				$rel .= "<trade_type>".$response['trade_type']."</trade_type>";
				$rel .= "<bank_type>".$response['bank_type']."</bank_type>";
				$rel .= "<total_fee>".$response['total_fee']."</total_fee>";
				$rel .= "<fee_type>".$response['fee_type']."</fee_type>";
				$rel .= "<transaction_id>".$response['transaction_id']."</transaction_id>";
				$rel .= "<out_trade_no>".$response['out_trade_no']."</out_trade_no>";
				$rel .= "<time_end>".$response['time_end']."</time_end>";
				$rel .= "<trade_state>".$response['trade_state']."</trade_state>";	
			}
			$rel .= "<PayStatus>".$status."</PayStatus>"; 
			$rel .= "</Message>";
			$rel.="</root>";
			Log::write("微信查询订单状态返回XML：".$rel); 
			return $rel; 
		}
		
	}
	/**
	*查询微信订单付款状态接口(窗口支付用)
	**/
	public function getWxPayStatus2($out_trade_no,$source,$operator_id){
		Log::write("查询微信订单状态入参：$out_trade_no,$source,$operator_id");
		require_once "../".$this->pay."_".$this->hos."/wxpay/lib/WxPay.Api.php";
		$db = new ezSQL_mysql($this->local_db_user,$this->local_db_pwd,$this->local_db_name,$this->local_db_host.':'.$this->local_db_port,$this->local_db_charset); 
		 
		$db->query("set names utf8");
		  
		$status=""; 
		if (trim($out_trade_no)!=""){ 
			$rel = "<?xml version=\"1.0\" encoding=\"utf8\"?><root>";
			$rel .= "<Message>";
			$input = new WxPayOrderQuery();
			$input->SetOut_trade_no($out_trade_no);
			$response = WxPayApi::orderQuery($input);
		
			if($response['return_code']=="FAIL"){
				$status = $response['return_msg'];
			}else if($response['return_code']=="SUCCESS"){
				if($response['result_code']=="SUCCESS"){
					$status = $response['trade_state'];
					switch($status){
						case "NOTPAY":
						//开始数据库记录日志
						$sql_s = "select id from alipay_logs where out_trade_no='".$out_trade_no."' and op_name='等待付款'";
						$result1 = $db->get_row($sql_s);
						if($result1->id!=""){//update
							$sql2 = "update alipay_logs set op_date='".date("Y-m-d H:i:s")."' where out_trade_no='".$out_trade_no."' and op_name='等待付款'"; 		
						}else{//insert
							$sql2 = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,subject,source,op_name,xml,operator_id) values ('weixin','".$out_trade_no."','','','','".$source."','"."等待付款"."',\"".var_export((array)$response->alipay_trade_query_response,"true")."\",'".$operator_id."')"; 		 
						}
						
						$db->query($sql2);
						Log::write(mysql_error().$sql2);
						break;
						case "SUCCESS":
						//开始数据库记录日志
						$sql_s2 = "select id from alipay_logs where out_trade_no='".$out_trade_no."' and op_name='付款成功'";
						$result2 = $db->get_row($sql_s2);

						$sql = "insert into alipay_logs (`pay_type`,`out_trade_no`,`trade_no`,`total_amount`,`subject`,`source`,`op_name`,`xml`,`duizhang`,`operator_id`) values ('weixin','".$out_trade_no."','".$response['transaction_id']."','".($response['total_fee']/100)."','','".$source."','"."付款成功"."',\"".var_export($response,"true")."\",'c','".$operator_id."')"; 
						$sql2 = "insert into think_alipay_record (pay_type,out_trade_no,trade_no,total_amount,receipt_amount,subject,source,trade_status,notify_time) values ('weixin','".$out_trade_no."','".$response['transaction_id']."','".($response['total_fee']/100)."','".($response['total_fee']/100)."','".$response['attach']."','".$source."','"."SUCCESS"."','".date("Y-m-d H:i:s")."')";
						$db->query($sql);
						$db2 = new ezSQL_mysql('root','801147Qaz!1~','alipay','211.159.153.49','gb2312');
						$db2->query("set names utf8");
						//$db3 = new ezSQL_mysql('root','801147Qaz!@#','alipay','10.66.196.56','gb2312');
						//$db3->query("set names utf8");
						$db2->query($sql2);
						//$db3->query($sql2);
						Log::write("mysql错误：".mysql_error().$sql2);  
						break;
					}
					$rel .= "<return_code>".$response['return_code']."</return_code>"; 
					$rel .= "<return_msg>".$response['return_msg']."</return_msg>";
					$rel .= "<result_code>".$response['result_code']."</result_code>";
					$rel .= "<total_fee>".$response['total_fee']."</total_fee>";
					$rel .= "<transaction_id>".$response['transaction_id']."</transaction_id>";
					$rel .= "<out_trade_no>".$response['out_trade_no']."</out_trade_no>";
					$rel .= "<trade_state>".$response['trade_state']."</trade_state>";
				}else{
					$rel .= "<result_code>".$response['result_code']."</result_code>";
					$rel .= "<err_code>".$response['err_code']."</err_code>";
					$sql = "insert into alipay_logs (`pay_type`,`out_trade_no`,`trade_no`,`total_amount`,`subject`,`source`,`op_name`,`xml`,`duizhang`,`operator_id`) values ('weixin','".$out_trade_no."','','','','".$source."','"."付款失败"."',\"".var_export($response,"true")."\",'c','".$operator_id."')"; 
					$db->query($sql);
				}	
			}
			$rel .= "</Message>";
			$rel.="</root>";
			Log::write("微信窗口支付查询订单状态返回XML：".var_export($response,true)); 
			return $rel; 
		}
		
	}
	/**
	*获取微信账单
	*/
	public function getWeiXinBill($bill_type="ALL",$bill_date,$source){
		require_once "../".$this->pay."_".$this->hos."/wxpay/lib/WxPay.Api.php";
		$input = new WxPayDownloadBill();
		$input->SetBill_date($bill_date);
		$input->SetBill_type($bill_type);
		$file = WxPayApi::downloadBill($input);
		//Log::write("返回微信账单数据：".$file);
		$obj = simplexml_load_string($file, 'SimpleXMLElement', LIBXML_NOCDATA);
		 $eJSON = json_encode($obj);
		  $dJSON = json_decode($eJSON);
		  $obj = (array)$dJSON;
		
		$rel = "<?xml version=\"1.0\" encoding=\"utf-8\"?><root>";
		if($obj['return_code']=="FAIL"){
			$rel .= "<return_code>".$obj['return_code']."</return_code>";
			$rel .= "<return_msg>".$obj['return_msg']."</return_msg>";
			$rel .= "<error_code>".$obj['error_code']."</error_code>";	
		}else{
			//$rel .= "<data>".$file."</data>";	
			//$xml = simplexml_load_string($file);
			//$xml = (array)$xml;
			//Log::write("返回微信账单数据(数组)：".var_export($xml,true));
			//$data  = $xml['data'];
			$xml = explode("\n",$file);
			$ary = array();
			//Log::write("返回微信账单数据(数组)：".var_export($xml,true));
			for($i=1;$i<(count($xml)-3);$i++){
				$t_str = $xml[$i];
				$t_ary = explode(",",$t_str);

				$rel .= "<RegInfo>";
				$rel .="<out_trade_no>".str_replace('`', "", $t_ary[6])."</out_trade_no>";
				$rel .="<trade_no>".str_replace('`', "", $t_ary[5])."</trade_no>";
				$rel .="<subject>".str_replace('`', "", $t_ary[20])."</subject>";
				
				$rel .="<pay_type>微信</pay_type>";
				if(str_replace('`', "", $t_ary[14])!=0){
					$rel .="<trade_type>退款</trade_type>";
					$rel .="<refund_no>".str_replace('`', "", $t_ary[15])."</refund_no>";
					$rel .="<total_amount>-".str_replace('`', "", $t_ary[16])."</total_amount>";
				}else{
					$rel .="<refund_no>".str_replace('`', "", $t_ary[15])."</refund_no>";
					$rel .="<trade_type>交易</trade_type>";
				$rel .="<total_amount>".str_replace('`', "", $t_ary[12])."</total_amount>";
				}
				$rel .="<body>".str_replace('`', "", $t_ary[20])."</body>";
				$rel .="<trade_date>".str_replace('`', "", $t_ary[0])."</trade_date>"; 
				$rel .= "</RegInfo>";

			}
		}
		
		$rel.="</root>";
		return $rel; 
	}
	/**
	*获取支付宝账单
	*/
	public function getAliPayBill($bill_type,$bill_date,$source){
		\Log::write("传入参数：".$bill_type.",".$bill_date.",".$source);
		require_once "../".$this->pay."_".$this->hos."/alipay/f2fpay/F2fpay.php"; 
		$f2fpay = new F2fpay();
		$bill_dates = explode("-",$bill_date);;
		if(strlen($bill_dates[1])==1){
			$bill_dates[1] = "0".$bill_dates[1];
		}
		if(strlen($bill_dates[2])==1){
			$bill_dates[2] = "0".$bill_dates[2];
		}
		$bill_date = implode("-",$bill_dates);
		$response = $f2fpay->getbill($bill_type,$bill_date);
		$re2 = (array)$response;
		$re3  =(array)$re2['alipay_data_dataservice_bill_downloadurl_query_response'];
		$rel = "<?xml version=\"1.0\" encoding=\"utf-8\"?><root>";
		$url = "";
		if($re3['code']==10000){
			$url = $re3['bill_download_url'];
		}else{
			$url = $re3['sub_msg'];
		}
		if($url!=""){
			
			$res = $this->downfile($url,"",$source,$bill_date);
			if($res['success']==1){
				$path = $res['path'];
				$p_ary = explode(".",$path);
				$this->get_zip_originalsize($path,$p_ary[0]."/1");
				$filesnames = scandir($p_ary[0]);
				//$rel .= "<DownUrl>".$path."</DownUrl>";   
			}
		}
		$ccc = 0;
		$paylist = array();
		foreach ($filesnames as $name) {
			$name = iconv("gbk","utf-8",$name);
			if($name!="."&&$name!=".."&&strpos($name,')')===false){
				$paylist = $this->readCsv($p_ary[0]."/".iconv("utf-8","gb2312",$name));
			}
	    }
	    //\Log::write(var_export($paylist,true));
		if($paylist){
			$ccc = 1;
			for($i=1;$i<count($paylist);$i++){
				$rel .= "<RegInfo>";
				$rel .="<out_trade_no>".$paylist[$i][1]."</out_trade_no>";
				$rel .="<trade_no>".$paylist[$i][0]."</trade_no>";

				// $rel .="<subject>".iconv("gbk","UTF-8//IGNORE",$paylist[$i][3])."</subject>";
				$rel .="<subject>".mb_convert_encoding($paylist[$i][3],"utf-8","gbk")."</subject>";
				$rel .="<trade_type>".iconv("gb2312","utf-8",$paylist[$i][2])."</trade_type>";
				$rel .="<total_amount>".iconv("gb2312","utf-8",$paylist[$i][11])."</total_amount>";
				$rel .="<body>".iconv("gb2312","utf-8",$paylist[$i][24])."</body>";
				$rel .="<trade_date>".iconv("gb2312","utf-8",$paylist[$i][5])."</trade_date>";
				$rel .="<pay_type>支付宝</pay_type>";
				$rel .= "</RegInfo>";
			}
		}
		$rel.="</root>";
		//unlink($path);
		return $rel;
		//return var_export($paylist,true); 
	}
	public function readCsv($files){ 
		//setlocale(LC_ALL, 'zh_CN');
		//setlocale(LC_ALL, 'zh_CN.UTF-8'); 
		setlocale(LC_ALL,NULL);
		$file = fopen($files,'r'); 
		$cc = 0;
		while ($data = fgetcsv($file,1000,",")) {
			//if(strpos($data[0][0],"明细")){
				//$goods_list[] = $data;
			//}
			$cc = 1;
			\Log::write("独一味".var_export($data,true));
			$goods_list[] = $data;
	 	}
		$paylist = array();
		foreach ($goods_list as $arr){
		   if ($arr[1]!=""){
		   		
			   $paylist[] = $arr;
			}
		} 

		fclose($file);

	 	return $paylist;
	}
	/** 
	 * 解压文件 
	 * 需开启配置 php_zip.dll 
	 * filename 要解压的文件全路径 
	 * path 解压文件后保存路径 
	 * id   要解压的文件ID 
	 * phpinfo(); 
	 */  
	function get_zip_originalsize($filename, $path, $id=0) {  
	    //先判断待解压的文件是否存在  
	    if (!file_exists($filename)) {  
	        die("文件 $filename 不存在！");  
	    }  
	    $starttime = explode(' ', microtime()); //解压开始的时间  
	    //将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到  
	    $filename = iconv("utf-8", "gb2312", $filename);  
	    $path = iconv("utf-8", "gb2312", $path);  
	    //打开压缩包  
	    $resource = zip_open($filename);  
	    $i = 1;  
	    //遍历读取压缩包里面的一个个文件  
	    while ($dir_resource = zip_read($resource)) {  
	        //如果能打开则继续  
	        if (zip_entry_open($resource, $dir_resource)) {  
	            //获取当前项目的名称,即压缩包里面当前对应的文件名  
	            $file_name = $path.zip_entry_name($dir_resource);  
	            //以最后一个“/”分割,再用字符串截取出路径部分  
	            $file_path = substr($file_name, 0, strrpos($file_name, "/"));  
	            //如果路径不存在，则创建一个目录，true表示可以创建多级目录  
	            if (!is_dir($file_path)) {  
	                mkdir($file_path, 0777, true);  
	            }  
	            //如果不是目录，则写入文件  
	            if (!is_dir($file_name)) {  
	                //读取这个文件  
	                $file_size = zip_entry_filesize($dir_resource);  
	                //最大读取6M，如果文件过大，跳过解压，继续下一个  
	                if ($file_size < (1024 * 1024 * 6)) {  
	                    $file_content = zip_entry_read($dir_resource, $file_size);  
	                    file_put_contents($file_name, $file_content);  
	                } else {  
	                    //echo "<p> ".$i++." 此文件已被跳过，原因：文件过大， -> ".iconv("gb2312", "utf-8", $file_name)." </p>";  
	                }  
	            }  
	            //关闭当前  
	            zip_entry_close($dir_resource);  
	        }  
	    }  
	    //关闭压缩包  
	    zip_close($resource);  
	    $endtime = explode(' ', microtime()); //解压结束的时间  
	    $thistime = $endtime[0] + $endtime[1] - ($starttime[0] + $starttime[1]);  
	    $thistime = round($thistime, 3); //保留3为小数  
	    //echo "<p>解压完毕！，本次解压花费：$thistime 秒。</p>演示地址：/".$path;  
	} 
	public function curlDownload($remote,$local) {
			
	        $cp = curl_init($remote);
	        $fp = fopen($local,"w");
			curl_setopt($cp, CURLOPT_FILE, $fp);
	        curl_setopt($cp, CURLOPT_HEADER, 0);
			    
	        $res = curl_exec($cp);
	        curl_close($cp);
	        fclose($fp);
			return $res; 
	    }
	function downfile($url,$filename="",$source,$bill_date) { //本地化部分
	if($url=="") return false; 
			if($filename=="") { 
				$ext='.zip';
				$filename=$source."_".$bill_date.$ext; 
				$targetFolder = '/download';
				$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
				//echo $targetPath;
				$ymd = date("Ymd");
				$save_path = $targetPath. "/" . $ymd;
				if (!file_exists($save_path)) {
					mkdir($save_path);
				}  
			$targetFile = rtrim($save_path,'/') . '/' . $filename;
			$localPath = $targetFolder.'/'.$ymd.'/'.$filename;
		} 
		$res = $this->curlDownload ($url,$targetFile);	
		if($res){
			$rel['success'] =1;
		}else{
			$rel['success'] = 0;
		}
		$rel['path'] = $targetFile;
		return $rel;
	}
	//微信支付
	function WxRefused($out_trade_no,$total_fee,$refund_fee,$source,$operator_id){
		Log::write("微信退款入参：$out_trade_no,$total_fee,$refund_fee,$source,$operator_id");
		require_once "../".$this->pay."_".$this->hos."/wxpay/lib/WxPay.Api.php";
		$db = new ezSQL_mysql($this->local_db_user,$this->local_db_pwd,$this->local_db_name,$this->local_db_host.':'.$this->local_db_port,$this->local_db_charset);
		$db->query("set names utf8");	
		$rel = "<?xml version=\"1.0\" encoding=\"utf8\"?><root>";
		$rel .= "<Message>";
		if(trim($out_trade_no)!=""){

			$rel .= "<CodeEmpty>0</CodeEmpty>";
			$input = new WxPayRefund();

			$input->SetOut_trade_no($out_trade_no);
			$input->SetTotal_fee($total_fee*100);
			$input->SetRefund_fee($refund_fee*100);
			$input->SetOut_refund_no(WxPayConfig::MCHID.date("YmdHis"));
			$input->SetOp_user_id(WxPayConfig::MCHID);
			$response = WxPayApi::refund($input);

			$rel.="<return_code>".$response['return_code']."</return_code>";
			$rel.="<err_code_msg>".$response['return_msg']."</err_code_msg>"; 
			if($response['return_code']=="SUCCESS"){
				if($response['result_code']=="SUCCESS"){
					$rel.="<result_code>".$response['result_code']."</result_code>";
					$rel.="<mch_id>".$response['mch_id']."</mch_id>";
					$rel.="<nonce_str>".$response['nonce_str']."</nonce_str>";
					$rel.="<appid>".$response['appid']."</appid>";
					$rel.="<sign>".$response['sign']."</sign>";
					$rel.="<transaction_id>".$response['transaction_id']."</transaction_id>";
					$rel.="<out_trade_no>".$response['out_trade_no']."</out_trade_no>";
					$rel.="<out_refund_no>".$response['out_refund_no']."</out_refund_no>";
					$rel.="<refund_id>".$response['refund_id']."</refund_id>";
					$refund_channel_s = '';
					foreach ($response['refund_channel'] as $k => $v) {
						$refund_channel_s = $k."=".$v."&";
					}
					$refund_channel_s = rtrim($refund_channel_s,"&");
					$rel.="<refund_channel>".$refund_channel_s."</refund_channel>";
					$rel.="<refund_fee>".$response['refund_fee']."</refund_fee>";
					
					$sql = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,source,op_name,xml,duizhang,operator_id,refund_no) values ('weixin','".$out_trade_no."','".$response['transaction_id']."','".-$refund_fee."','".$source."','退款成功',\"".var_export($response,"true")."\",'t','".$operator_id."','".$response['out_refund_no']."')";
					$db->query($sql);
			
				}else{
					$rel.="<result_code>".$response['result_code']."</result_code>";	
					$rel.="<err_code>".$response['err_code']."</err_code>";
					$rel.="<err_code_des>".$response['err_code_des']."</err_code_des>";
					$sql = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,source,op_name,xml,duizhang,operator_id) values ('weixin','".$out_trade_no."','','".-$refund_fee."','".$source."','退款失败',\"".var_export($response,"true")."\",'f','".$operator_id."')"; 
					$db->query($sql);
			
				}
				
			}else{
				$sql = "insert into alipay_logs (pay_type,out_trade_no,trade_no,total_amount,source,op_name,xml,duizhang,operator_id) values ('weixin','".$out_trade_no."','','".-$refund_fee."','".$source."','退款失败',\"".var_export($response,"true")."\",'f','".$operator_id."')"; 
				$db->query($sql);
			}
		}else{
			$rel .= "<CodeEmpty>1</CodeEmpty>";
		}
		$rel .= "</Message>";
		$rel.="</root>";
		Log::write("微信退款返回数据：".var_export($response,true).$rel);
		return $rel;
	}
}
?>