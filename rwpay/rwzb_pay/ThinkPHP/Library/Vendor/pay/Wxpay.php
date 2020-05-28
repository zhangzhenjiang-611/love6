<?php
require_once "wxpay_sdk_v3.0.10/lib/WxPay.Api.php";
require_once "wxpay_sdk_v3.0.10/lib/WxPay.Config.php";
require_once "wxpay_sdk_v3.0.10/lib/WxPay.MicroPay.php";
require_once "wxpay_sdk_v3.0.10/lib/WxPay.NativePay.php";
require_once 'wxpay_sdk_v3.0.10/lib/WxPay.Notify.php';
require_once 'wxpay_sdk_v3.0.10/lib/WxPay.Data.php';
/**
 * @desc 微信支付入口类
 * @author wangxinghua
 * @final 2020-03-05
 */
class Wxpay
{
	private $config;
	public function __construct($pay_config)
	{
		$config = new WxPayConfig();
		$config->AppId = $pay_config['APPID'];
		$config->MerchantId = $pay_config['MCHID'];
		$config->SubMerchantId = $pay_config['SUB_MCHID'];
		$config->Key = $pay_config['KEY'];
		$config->AppSecret = $pay_config['APPSECRET'];
		$config->SignType = $pay_config['SignType'];
		$config->NotifyUrl = $pay_config['NOTIFY_URL'];

		$file = dirname(__FILE__)."/wxpay_sdk_v3.0.10/cert/".$pay_config['MCHID'].'_'.$pay_config['SUB_MCHID'].'_cert.pem';
		if(!file_exists($file))
		{
			$content = "-----BEGIN CERTIFICATE-----\n".chunk_split(str_replace(' ','',$pay_config['SSLCERT']),64,"\n")."-----END CERTIFICATE-----\n";
			if(!openssl_pkey_get_public($content))
				throw new Exception("cert.pem文件格式出错");
			$res = file_put_contents($file, $content);
			if($res === false)
				throw new Exception("cert.pem文件生成出错");
		}
		$config->SetSSLCert($file);
		$file = dirname(__FILE__)."/wxpay_sdk_v3.0.10/cert/".$pay_config['MCHID'].'_'.$pay_config['SUB_MCHID'].'_key.pem';
		if(!file_exists($file))
		{
			$content = "-----BEGIN PRIVATE KEY-----\n".chunk_split(str_replace(' ','',$pay_config['SSLKEY']),64,"\n")."-----END PRIVATE KEY-----\n";
			if(!openssl_pkey_get_private($content))
				throw new Exception("cert.pem文件格式出错");
			$res = file_put_contents($file, $content);
			if($res === false)
				throw new Exception('key.pem文件生成出错');
		}
		$config->SetSSLKey($file);
		$this->config = $config;
	}
	/**
	 * @desc 条码支付
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-05
	 */
	public function barpay($params)
	{
		$input = new WxPayMicroPay();
		$input->SetAuth_code($params['auth_code']);
		$input->SetBody($params['subject']);
		$input->SetTotal_fee($params['receipt_amount']);
		$input->SetOut_trade_no($params['trade_no']);

		$microPay = new MicroPay();
		$result = $microPay->pay($this->config, $input, $params['timeExpress']);
		//如果返回成功
		//整理返回数据	
		$code = '';
	    $msg = '';
	    $trade_status = 0;
		if($result['return_code'] == "SUCCESS")
		{
			if($result['result_code'] == 'SUCCESS')
			{
				switch ($result["trade_state"]) {
			        case "SUCCESS":
			        	$code = '000';
			            $msg = "支付成功";
			    		$trade_status = 1;
			            break;
			        case "USERPAYING":
			        	$code = '100';
			            $msg = "支付中";
			    		$trade_status = 2;
			            break;
			        default:
			        	$code = '300';
			            $msg = "不支持的交易状态,交易返回异常";
			    		$trade_status = 4;
			            break;
			    }
			}
			else
			{
				$code = '100';
	            $msg = "支付失败,"+isset($result['err_code']) ? $result['err_code'] : '';
	    		$trade_status = 0;
			}
		}
		else
		{
			$code = '100';
            $msg = "支付失败,"+isset($result['return_msg']) ? $result['return_msg'] : '';
    		$trade_status = 0;
		}
		
		return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'trade_status' => $trade_status,
	    		'out_trade_no' => isset($result['transaction_id']) ? $result['transaction_id'] : '',
	    		'buyer_id' => isset($result['openid']) ? $result['openid'] : '',
	    		'buyer_account' => '',
	    		'attach' => json_encode($result['attach']),
	    		'notify_time' => isset($result['time_end']) ? $result['time_end'] : time(),
	    		'notify_str' => json_encode($result)
	    	)
	    );
	}
	/**
	 * @desc 二维码支付
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-15
	 */
	public function qrpay($params)
	{
		$input = new WxPayUnifiedOrder();
		$input->SetBody($params['subject']);
		$input->SetAttach("");
		$input->SetOut_trade_no($params['trade_no']);
		$input->SetTotal_fee($params['receipt_amount']);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetNotify_url($params['PAY_CONFIG']['NOTIFY_URL']);
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($params['trade_type'] ? $params['trade_type'] : $params['trade_no']);

		$notify = new NativePay();

		$result = $notify->GetPayUrl($input, $this->config);

		$code = '';
	    $msg = '';
		if($result['return_code'] == "SUCCESS")
		{
			if($result['result_code'] == 'SUCCESS')
			{
				$code = '000';
	            $msg = "创建订单二维码成功";
			}
			else
			{
				$code = '100';
	            $msg = "创建订单二维码失败,"+isset($result['err_code']) ? $result['err_code'] : '';
			}
		}
		else
		{
			$code = '100';
            $msg = "创建订单二维码失败,"+isset($result['return_msg']) ? $result['return_msg'] : '';
		}
	    return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'qr_code' => $result["code_url"],
	    		'notify_str' => json_encode($result)
	    	)
	    );
	}
	/**
	 * @desc 交易退款
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-16
	 */
	public function refund($params)
	{
		$input = new WxPayRefund();
		if($params['trade_no'])
				$input->SetOut_trade_no($params['trade_no']);
		if($params['out_trade_no'])
				$input->SetTransaction_id($params['out_trade_no']);
		$input->SetTotal_fee($params['total_amount']);
		$input->SetRefund_fee($params['refund_amount']);
	    $input->SetOut_refund_no($params['refund_no']);
	    $input->SetOp_user_id($params['PAY_CONFIG']['MCHID'].$params['PAY_CONFIG']['SUBMCHID']);
	    $result = WxPayApi::refund($this->config, $input);
		$code = '';
	    $msg = '';
	    $trade_status = 0;
	    if($result['return_code'] == "SUCCESS")
		{
			if($result['result_code'] == 'SUCCESS')
			{
				$code = '000';
	            $msg = "退款成功";
	            $trade_status = 1;
			}
			else
			{
				$code = '100';
	            $msg = "退款失败,"+isset($result['err_code']) ? $result['err_code'].$result['err_code_des'] : '';
	    		$trade_status = 0;
			}
		}
		else
		{
			$code = '100';
            $msg = "退款失败,"+isset($result['return_msg']) ? $result['return_msg'] : '';
    		$trade_status = 0;
		}
	    return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'refund_status' => $trade_status,
	    		'buyer_id' => '',
	    		'buyer_account' => '',
	    		'out_trade_no' => $result['transaction_id'],
	    		'refund_time' => date('Y-m-d H:i:s'),
	    		'notify_str' => json_encode($result)
	    	)
	    );
	}
	/**
	 * @desc 交易查询
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-16
	 */
	public function query($params)
	{
		$input = new WxPayOrderQuery();
		if($params['trade_no'])
			$input->SetOut_trade_no($params['trade_no']);
		if($params['out_trade_no'])
			$input->SetTransaction_id($params['out_trade_no']);

		$result = WxPayApi::orderQuery($this->config, $input);

		$code = '';
	    $msg = '';
	    $trade_status = 0;
	    if($result['return_code'] == "SUCCESS")
		{
			if($result['result_code'] == 'SUCCESS')
			{
				switch ($result["trade_state"]) {
			        case "SUCCESS":
						$code = '000';
	            		$msg = "查询交易成功";
	            		$trade_status = 1;
			            break;
			        case "NOTPAY":
			        case "USERPAYING":
						$code = '000';
	            		$msg = "等待支付中";
	            		$trade_status = 2;
			            break;
			        default:
			        	$code = '300';
			            $msg = $result['trade_state'].'|'.$result['trade_state_desc'];
			    		$trade_status = 0;
			            break;
			    }
			}
			else
			{
				$code = '100';
	            $msg = "查询交易失败或者交易已关闭,"+isset($result['err_code']) ? $result['err_code'].$result['err_code_des'] : '';
	    		$trade_status = 0;
			}
		}
		else
		{
			$code = '100';
            $msg = "查询交易失败或者交易已关闭,"+isset($result['return_msg']) ? $result['return_msg'] : '';
    		$trade_status = 0;
		}
	    return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'trade_status' => $trade_status,
	    		'buyer_id' => $result['openid'],
	    		'buyer_account' => '',
	    		'out_trade_no' => $result['transaction_id'],
	    		'trade_no' => $result['out_trade_no'],
	    		'notify_str' => json_encode($result)
	    	)
	    );
	}
	/**
	 * @desc 取消订单
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-16
	 */
	public function cancel($params)
	{
		$input = new WxPayReverse();
		if($params['trade_no'])
			$input->SetOut_trade_no($params['trade_no']);
		if($params['out_trade_no'])
			$input->SetTransaction_id($params['out_trade_no']);

		$result = WxPayApi::closeOrder($this->config, $input);
		$code = '';
	    $msg = '';
	    $trade_status = 0;
	    if($result['return_code'] == "SUCCESS")
		{
			if($result['result_code'] == 'SUCCESS')
			{
				$code = '000';
        		$msg = "取消交易成功";
        		$trade_status = 1;
			}
			else
			{
				$code = '100';
	            $msg = "取消交易失败或者交易已关闭,"+isset($result['err_code']) ? $result['err_code'].$result['err_code_des'] : '';
	    		$trade_status = 0;
			}
		}
		else
		{
			$code = '100';
            $msg = "取消交易失败或者交易已关闭,"+isset($result['return_msg']) ? $result['return_msg'] : '';
    		$trade_status = 0;
		}
	    return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'trade_status' => $trade_status,
	    		'out_trade_no' => $params['out_trade_no'] ? $params['out_trade_no'] : '',
	    		'notify_str' => json_encode($result)
	    	)
	    );
	}
	/**
	 * @desc 异步回调
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-18
	 */
	public function notify($params)
	{
		//验签
		$obj = new WxPayResults();
		$obj->setValues($params['xml']);
		$obj->CheckSign($this->config);

		$code = '';
	    $msg = '';
	    $trade_status = 0;
		if($params['xml']['return_code'] == "SUCCESS")
		{
			if($params['xml']['result_code'] == 'SUCCESS')
			{
	        	$code = '000';
	            $msg = "支付成功";
	    		$trade_status = 1;
			}
			else
			{
				$code = '100';
	            $msg = "支付失败,"+isset($params['xml']['err_code']) ? $params['xml']['err_code'] : '';
	    		$trade_status = 0;
			}
		}
		else
		{
			$code = '101';
            $msg = "支付失败,"+isset($params['xml']['return_msg']) ? $params['xml']['return_msg'] : '';
    		$trade_status = 0;
		}
		
		return array(
	    	'code' => $code,
	    	'msg' => $msg,
	    	'data' => array(
	    		'trade_status' => $trade_status,
	    		'out_trade_no' => isset($params['xml']['transaction_id']) ? $params['xml']['transaction_id'] : '',
	    		'trade_no' => isset($params['xml']['out_trade_no']) ? $params['xml']['out_trade_no'] : '',
	    		'buyer_id' => isset($params['xml']['openid']) ? $params['xml']['openid'] : '',
	    		'buyer_account' => '',
	    		'attach' => isset($params['xml']['attach']) ? $params['xml']['attach'] : '',
	    		'notify_time' => $params['xml']['time_end']
	    	)
	    );
	}
}