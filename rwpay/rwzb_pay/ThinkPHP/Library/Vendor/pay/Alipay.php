<?php
require_once 'alipay-sdk_v3.3.0/f2fpay/model/builder/AlipayTradePayContentBuilder.php';
require_once 'alipay-sdk_v3.3.0/f2fpay/model/builder/AlipayTradePrecreateContentBuilder.php';
require_once 'alipay-sdk_v3.3.0/f2fpay/model/builder/AlipayTradeRefundContentBuilder.php';
require_once 'alipay-sdk_v3.3.0/f2fpay/model/builder/AlipayTradeCancelContentBuilder.php';
require_once 'alipay-sdk_v3.3.0/f2fpay/service/AlipayTradeService.php';
/**
 * @desc 支付宝入口类
 * @author wangxinghua
 * @final 2020-03-05
 */
class Alipay
{
	/**
	 * @desc 条码支付
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-05
	 */
	public function barpay($params)
	{
		$params['total_amount'] /= 100;
		$params['receipt_amount'] /= 100;
		// 创建请求builder，设置请求参数
	    $barPayRequestBuilder = new AlipayTradePayContentBuilder();
	    $barPayRequestBuilder->setOutTradeNo($params['trade_no']);
	    $barPayRequestBuilder->setTotalAmount($params['receipt_amount']);
	    $barPayRequestBuilder->setUndiscountableAmount($params['receipt_amount']);
		$barPayRequestBuilder->setTimeExpress($params['PAY_CONFIG']['timeExpress'].'m');
	    $barPayRequestBuilder->setAuthCode($params['auth_code']);
	    $barPayRequestBuilder->setSubject($params['subject']);
	    $barPayRequestBuilder->setBody($params['body']);
	    $barPayRequestBuilder->setOperatorId($params['terminal_code']);

	    // 调用barPay方法获取当面付应答
	    $barPay = new AlipayTradeService($params['PAY_CONFIG']);
	    $barPayResult = $barPay->barPay($barPayRequestBuilder);

	    $code = '';
	    $msg = '';
	    $trade_status = 0;
	    switch ($barPayResult->getTradeStatus()) {
	        case "SUCCESS":
	        	$code = '000';
	            $msg = "支付成功";
	    		$trade_status = 1;
	            break;
	        case "FAILED":
	        	$code = '100';
	            $msg = "支付失败";
	    		$trade_status = 0;
	            break;
	        case "UNKNOWN":
	        	$code = '200';
	            $msg = "系统异常,订单状态未知";
	    		$trade_status = 4;
	            break;
	        default:
	        	$code = '300';
	            $msg = "不支持的交易状态,交易返回异常";
	    		$trade_status = 4;
	            break;
	    }
	    $res_data = $barPayResult->getResponse();
	    $res_data->trade_status = $barPayResult->getTradeStatus();
	    return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'trade_status' => $trade_status,
	    		'out_trade_no' => $res_data->trade_no,
	    		'buyer_id' => $res_data->buyer_user_id,
	    		'buyer_account' => $res_data->buyer_logon_id,
	    		'notify_time' => $res_data->gmt_payment,
	    		'notify_str' => json_encode($res_data)
	    	)
	    );
	}
	/**
	 * @desc 二维码支付
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-05
	 */
	public function qrpay($params)
	{
		$params['total_amount'] /= 100;
		$params['receipt_amount'] /= 100;
		// 创建请求builder，设置请求参数
		$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
		$qrPayRequestBuilder->setOutTradeNo($params['trade_no']);
		$qrPayRequestBuilder->setTotalAmount($params['receipt_amount']);
		$qrPayRequestBuilder->setUndiscountableAmount($params['receipt_amount']);
		$qrPayRequestBuilder->setSubject($params['subject']);
		$qrPayRequestBuilder->setBody($params['body']);
		$qrPayRequestBuilder->setOperatorId($params['terminal_code']);

		// 调用qrPay方法获取当面付应答
		$qrPay = new AlipayTradeService($params['PAY_CONFIG']);
		$qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);

		$code = '';
	    $msg = '';
	    switch ($qrPayResult->getTradeStatus()) {
	        case "SUCCESS":
	        	$code = '000';
	            $msg = "创建订单二维码成功";
	            break;
	        case "FAILED":
	        	$code = '100';
	            $msg = "创建订单二维码失败";
	            break;
	        case "UNKNOWN":
	        	$code = '200';
	            $msg = "系统异常,订单状态未知";
	            break;
	        default:
	        	$code = '300';
	            $msg = "不支持的返回状态，创建订单二维码返回异常";
	            break;
	    }
	    $res_data = $qrPayResult->getResponse();
	    $res_data->trade_status = $qrPayResult->getTradeStatus();
	    return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'qr_code' => $res_data->qr_code,
	    		'notify_str' => json_encode($res_data)
	    	)
	    );
	}
	/**
	 * @desc 交易查询
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-05
	 */
	public function query($params)
	{
	    //构造查询业务请求参数对象
	    $queryContentBuilder = new AlipayTradeQueryContentBuilder();
		if($params['trade_no'])
	    	$queryContentBuilder->setOutTradeNo($params['trade_no']);
		if($params['out_trade_no'])
	    	$queryContentBuilder->setTradeNo($params['out_trade_no']);

	    //初始化类对象，调用queryTradeResult方法获取查询应答
	    $queryResponse = new AlipayTradeService($params['PAY_CONFIG']);
	    $queryResult = $queryResponse->queryTradeResult($queryContentBuilder);

		$code = '';
	    $msg = '';
	    $trade_status = 0;
	    switch ($queryResult->getTradeStatus()) {
	        case "SUCCESS":
	        	$code = '000';
	            $msg = "查询交易成功";
	            $trade_status = 1;
	            break;
	        case "WAIT_BUYER_PAY":
	        	$code = '000';
	            $msg = "WAIT_BUYER_PAY";
	            $trade_status = 2;
	            break;
	        case "FAILED":
	        	$code = '100';
	            $msg = "查询交易失败或者交易已关闭";
	            $trade_status = 0;
	            break;
	        case "UNKNOWN":
	        	$code = '200';
	            $msg = "系统异常，订单状态未知";
	            $trade_status = 0;
	            break;
	        default:
	        	$code = '300';
	            $msg = "不支持的查询状态，交易返回异常";
	            $trade_status = 0;
	            break;
	    }
	    $res_data = $queryResult->getResponse();
	    $res_data->trade_status = $queryResult->getTradeStatus();
	    return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'trade_status' => $trade_status,
	    		'buyer_id' => $res_data->buyer_user_id,
	    		'buyer_account' => $res_data->buyer_logon_id,
	    		'out_trade_no' => $res_data->trade_no,
	    		'trade_no' => $res_data->out_trade_no,
	    		'notify_str' => json_encode($res_data)
	    	)
	    );
	}
	/**
	 * @desc 交易退款
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-05
	 */
	public function refund($params)
	{
        $params['refund_amount'] /= 100;
		
		//创建退款请求builder,设置参数
		$refundRequestBuilder = new AlipayTradeRefundContentBuilder();
		if($params['trade_no'])
			$refundRequestBuilder->setOutTradeNo($params['trade_no']);
		if($params['out_trade_no'])
		$refundRequestBuilder->setTradeNo($params['out_trade_no']);
		$refundRequestBuilder->setRefundAmount($params['refund_amount']);
		$refundRequestBuilder->setOutRequestNo($params['refund_no']);

		//初始化类对象,调用refund获取退款应答
		$refundResponse = new AlipayTradeService($params['PAY_CONFIG']);
		$refundResult =	$refundResponse->refund($refundRequestBuilder);

		$code = '';
	    $msg = '';
	    $trade_status = 0;
	    switch ($refundResult->getTradeStatus()) {
	        case "SUCCESS":
	        	$code = '000';
	            $msg = "退款成功";
	            $trade_status = 1;
	            break;
	        case "FAILED":
	        	$code = '100';
	            $msg = "退款失败";
	            $trade_status = 0;
	            break;
	        case "UNKNOWN":
	        	$code = '200';
	            $msg = "系统异常,订单状态未知";
	            $trade_status = 4;
	            break;
	        default:
	        	$code = '300';
	            $msg = "不支持的交易状态，交易返回异常";
	            $trade_status = 4;
	            break;
	    }
	    $res_data = $refundResult->getResponse();
	    $res_data->trade_status = $refundResult->getTradeStatus();
	    return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'refund_status' => $trade_status,
	    		'buyer_id' => $res_data->buyer_user_id,
	    		'buyer_account' => $res_data->buyer_logon_id,
	    		'out_trade_no' => $res_data->out_trade_no,
	    		'refund_time' => $res_data->gmt_refund_pay,
	    		'notify_str' => json_encode($res_data)
	    	)
	    );
	}
	/**
	 * @desc 取消订单
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-05
	 */
	public function cancel($params)
	{
	    //构造查询业务请求参数对象
	    $cancelContentBuilder = new AlipayTradeCancelContentBuilder();
		if($params['trade_no'])
	    	$cancelContentBuilder->setOutTradeNo($params['trade_no']);
		if($params['out_trade_no'])
	    	$cancelContentBuilder->setTradeNo($params['out_trade_no']);



	    //初始化类对象，调用queryTradeResult方法获取查询应答
	    $cancelResponse = new AlipayTradeService($params['PAY_CONFIG']);
	    $cancelResult = $cancelResponse->cancelTradeResult($cancelContentBuilder);

		$code = '';
	    $msg = '';
	    $trade_status = 0;
	    switch ($cancelResult->getTradeStatus()) {
	        case "SUCCESS":
	        	$code = '000';
	            $msg = "取消交易成功";
	            $trade_status = 1;
	            break;
	        case "FAILED":
	        	$code = '100';
	            $msg = "取消交易失败或者交易已关闭";
	            $trade_status = 0;
	            break;
	        case "UNKNOWN":
	        	$code = '200';
	            $msg = "系统异常，订单状态未知";
	            $trade_status = 4;
	            break;
	        default:
	        	$code = '300';
	            $msg = "不支持的取消状态，交易返回异常";
	            $trade_status = 4;
	            break;
	    }
	    $res_data = $cancelResult->getResponse();
	    $res_data->trade_status = $cancelResult->getTradeStatus();
	    return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'trade_status' => $trade_status,
	    		'out_trade_no' => $res_data->out_trade_no,
	    		'notify_str' => json_encode($res_data)
	    	)
	    );
	}
	/**
	 * @desc 异步回调
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-05
	 */
	public function notify($params)
	{
		$alipaySevice = new AlipayTradeService($params['PAY_CONFIG']);
		#解决tp框架I方法的在此处的一个bug,双引号转成了&quot;
		$params['post'] = str_replace('&quot;', '"', $params['post']);
		$result = $alipaySevice->check($params['post']);
		/* 实际验证过程建议商户添加以下校验。
		1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
		2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
		3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
		4、验证app_id是否为该商户本身。
		*/
		$code = '';
	    $msg = '';
	    $trade_status = 0;
		if($result)//验证成功
		{		
			switch ($params['post']['trade_status']) {
		        case "TRADE_SUCCESS":
		        	$code = '000';
		            $msg = "支付成功";
		            $trade_status = 1;
		            break;
		        default:
		        	$code = '100';
		            $msg = "支付失败";
		            $trade_status = 4;
		            break;
		    }
		}
		else//验签失败
		{
		    $code = '500';
            $msg = "验签失败";
            $trade_status = 4;
		}

	    return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'trade_status' => $trade_status,
	    		'out_trade_no' => $params['post']['trade_no'],
	    		'trade_no' => $params['post']['out_trade_no'],
	    		'notify_time' => $params['post']['notify_time'],
	    		'notify_str' => json_encode($params['post'])
	    	)
	    );
	}
}