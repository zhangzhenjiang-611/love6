<?php
$dir = dirname(__FILE__);
require_once $dir.'/../AopSdk.php';
require_once $dir.'/../function.inc.php';
require $dir.'/../config.php';
class F2fpay {
	
	
	public function barpay($out_trade_no, $auth_code, $total_amount, $subject,$body='',$source='') {	
		$biz_content="{\"out_trade_no\":\"" . $out_trade_no . "\",";
		$biz_content.="\"scene\":\"bar_code\",";
		$biz_content.="\"auth_code\":\"" . $auth_code . "\",";
		$biz_content.="\"total_amount\":\"" . $total_amount
		. "\",\"discountable_amount\":\"0.00\",";
		$biz_content.="\"subject\":\"" . $subject . "\",\"body\":\"$body\",";
		//$biz_content.="\"goods_detail\":[{\"goods_id\":\"apple-01\",\"goods_name\":\"ipad\",\"goods_category\":\"7788230\",\"price\":\"88.00\",\"quantity\":\"1\"},{\"goods_id\":\"apple-02\",\"goods_name\":\"iphone\",\"goods_category\":\"7788231\",\"price\":\"88.00\",\"quantity\":\"1\"}],";
		//$biz_content.="\"operator_id\":\"op001\",\"store_id\":\"pudong001\",\"terminal_id\":\"t_001\",";
			//$biz_content.="\"extend_params\":{\"sys_service_provider_id\":\"2088221369684173\"},"; 
		$biz_content.="\"timeout_express\":\"5m\"}";

		//echo $biz_content;
		$source="hos001";
		$request = new AlipayTradePayRequest();
		$request->setNotifyUrl("http://211.159.153.49/mz/Home/AliPay/getAsyncInfo/source/".$source);
		$request->setBizContent ( $biz_content );
		$response = aopclient_request_execute ( $request );
		print_r($response);
		return $response;
	}
	
	
	public function qrpay($out_trade_no,$total_amount,$subject,$source) {
		// Log::Write("贾飞参数： $out_trade_no,$total_amount,$subject,$source");
		$biz_content="{\"out_trade_no\":\"" . $out_trade_no . "\",";
		$biz_content.="\"total_amount\":\"" . $total_amount
		. "\",\"discountable_amount\":\"0.00\",";
		$biz_content.="\"subject\":\"".$subject."\",\"body\":\"".$subject."\",";
		$biz_content.="\"goods_detail\":[{\"goods_id\":\"apple-01\",\"goods_name\":\"ipad\",\"goods_category\":\"7788230\",\"price\":\"88.00\",\"quantity\":\"1\"},{\"goods_id\":\"apple-02\",\"goods_name\":\"iphone\",\"goods_category\":\"7788231\",\"price\":\"88.00\",\"quantity\":\"1\"}],";
		$biz_content.="\"operator_id\":\"op001\",\"store_id\":\"pudong001\",\"terminal_id\":\"t_001\",";
		$biz_content.="\"extend_params\":{\"sys_service_provider_id\":\"2088221369684173\"},"; 
		$biz_content.="\"timeout_express\":\"5m\"}";
	  // Log::Write("贾飞参数1".$rel = var_export(json_decode($biz_content,true),"true"));
		//echo $biz_content;
	
		$request = new AlipayTradePrecreateRequest();
		$request->setBizContent ( $biz_content );
		$request->setNotifyUrl("http://211.159.153.49/mz/Home/AliPay/getAsyncInfo/source/".$source);

		$response = aopclient_request_execute ( $request ); 
	
	
	
		return $response;
	}
	
	
	public function query($out_trade_no) {	
		$biz_content="{\"out_trade_no\":\"" . $out_trade_no . "\"}";
		$request = new AlipayTradeQueryRequest();
		$request->setBizContent ( $biz_content );
		$response = aopclient_request_execute ( $request );
		return $response;
	}
	
	
	public function cancel($out_trade_no) {
		$biz_content="{\"out_trade_no\":\"" . $out_trade_no . "\"}";
		$request = new AlipayTradeCancelRequest();
		$request->setBizContent ( $biz_content );
		$response = aopclient_request_execute ( $request );
		return $response;
	}
	
	public function refund($out_trade_no,$refund_amount,$out_request_no) {
		$biz_content = "{\"out_trade_no\":\"". $out_trade_no . "\",\"refund_amount\":\""
						. $refund_amount
						. "\",\"out_request_no\":\""
								. $out_request_no
								. "\",\"refund_reason\":\"reason\",\"store_id\":\"store001\",\"terminal_id\":\"terminal001\"}";
		
		$request = new AlipayTradeRefundRequest();
		$request->setBizContent ( $biz_content );
		$response = aopclient_request_execute ( $request );
		return $response;
	}
	public function getbill($bill_type,$bill_date){
		$biz_content = "{\"bill_type\":\"". $bill_type . "\",\"bill_date\":\"".$bill_date."\"}";
		$request = new AlipayDataBillDownloadurlGetRequest ();
		$request->setBizContent ( $biz_content );
		//$request->setBillDate($bill_date);
		//$request->setBillType($bill_type); 
		//return $biz_content;
		$response = aopclient_request_execute ($request);
		//print_r($response);
		return $response;
		
	}
}