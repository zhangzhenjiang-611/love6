<?php
/*
 * @desc 支付相关控制器
 * @author wangxinghua
 * @final 2020-01-13
 */
namespace MenZhen\Controller;
use Think\Controller;
class PayController extends BaseController {
	/*
	 * @desc 统一调支付相关方法
	 * @author wangxinghua
	 * @final 2020-03-15
	 */
	private function call($method, $request)
	{
		$request['password'] = C('RWZB_PAY_PASSWORD');
		$trade_type = C('RWZB_PAY_TRADE_TYPE');
		if($request['trade_type'])
			$request['trade_type'] = $trade_type[$request['trade_type']];

		$response = curl_http(C('RWZB_PAY_HOST_URL').'/'.$method, $request);
		$response = json_decode($response,true);
		return $response;
	}
	/*
	 * @desc 选择支付方式获取二维码(C扫B)
	 * @author wangxinghua
	 * @final 2020-01-15
	 */
	public function ewmUrl()
	{
		$zzjCode = I('post.zzjCode');
		$patientId = I('post.patientId');
		$payType = I('post.payType');
		$payFee = I('post.payFee');
		$insurFee = I('post.insurFee');
		$totalFee = I('post.totalFee');
		$businessType = I('post.businessType');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('payType', 1, '', '' ,''),
	        array('payFee', 1, '', '' ,''),
	        array('insurFee', 1, '', '' ,''),
	        array('totalFee', 1, '', '' ,''),
	        array('businessType', 1, '', '' ,''),
	    );
	    $this->validate($rules);

	    if(C('IS_1FEN'))
	    	$payFee = 0.01;
	    //需要查库的
	    $Process = D('Process','Logic');
		$hos_code = D('Config','Logic')->getConfValue('hos.code');
		$businessText = $Process->getModuleName($businessType);
		$patientName = $Process->getPatientNameById($patientId);

		$request = array(
			'hos_code' => $hos_code,
			'pay_type' => $payType,
			'trade_type' => $businessType,
			'total_amount' => $totalFee*100,
			'receipt_amount' => $payFee*100,
			'subject' => $patientName.($businessText),
			'body' => $patientName.($businessText).'支付'.$payFee.'元钱',
			'terminal_code' => $zzjCode,
		);

		$response = $this->call('pay/qrpay',$request);
		if($response['code'] != '000')
		{
			$this->err_result("获取支付链接失败");
		}
		$ewmUrl = $response['data']['qr_code'];
		$tradeNo = $response['data']['trade_no'];
		$outTradeNo = '';
		$payTypeKv = C('PAY_TYPE_KV');
		//支付信息入库
		$param = array(
			'patient_id' => $patientId,
			'patient_name' => $patientName,
			'pay_status' => 2,
			'zzj_code' => $zzjCode,
			'trade_no' => $tradeNo,
			'qr_code' => $ewmUrl,
			'business_type' => $businessType,
			'out_trade_no' => $outTradeNo,
			'pay_type' => $payTypeKv[$payType],
			'total_fee' => $totalFee*100,
			'personpay_fee' => $payFee*100,
			'insur_fee' => $insurFee*100,
			'trade_desc' => $request['body'],
			'pay_time' => date('Y-m-d H:i:s')
		);
		$Process->savePayment($param);

		$business = array(
			'ewmUrl' => web_server_url().U('pay/ewmQr').'?payUrl='.urlencode($ewmUrl).'&payType='.$payType.'&tradeNo='.$tradeNo,
			'tradeNo' => $tradeNo
		);
		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 二维码图片地址(C扫B)
	 * @author wangxinghua
	 * @final 2020-01-15
	 */
	public function ewmQr()
	{
		Vendor('phpqrcode.phpqrcode');
		$payUrl = urldecode(I('get.payUrl'));
		$payType = I('get.payType');
		$tradeNo = I('get.tradeNo');

		$folder = 'Public/img/pay_ewm/';
		$logo = $folder.$payType.'_logo.png';	
		$file = $folder.$tradeNo.'.png';

		$errorCorrectionLevel = 'H';  
		$matrixPointSize = 10;
		\QRcode::png($payUrl, $file, $errorCorrectionLevel, $matrixPointSize, 2);

		$QR = imagecreatefromstring(file_get_contents($file));   
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
	    header('Content-type:image/png');
		imagepng($QR);
		imagedestroy($QR);
		unlink($file);
	}
	/*
	 * @desc 接收付款码进行付款(B扫C)
	 * @author wangxinghua
	 * @final 2020-01-15
	 */
	public function barpay()
	{
		$zzjCode = I('post.zzjCode');
		$patientId = I('post.patientId');
		$payType = I('post.payType');
		$payFee = I('post.payFee');
		$insurFee = I('post.insurFee');
		$totalFee = I('post.totalFee');
		$authCode = I('post.authCode');
		$businessType = I('post.businessType');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('payType', 1, '', '' ,''),
	        array('authCode', 1, '', '' ,''),
	        array('payFee', 1, '', '' ,''),
	        array('insurFee', 1, '', '' ,''),
	        array('totalFee', 1, '', '' ,''),
	        array('businessType', 1, '', '' ,''),
	    );
	    $this->validate($rules);
	    if(C('IS_1FEN'))
	    	$payFee = 0.01;
	    //需要查库的
	    $Process = D('Process','Logic');
		$hos_code = D('Config','Logic')->getConfValue('hos.code');
		$businessText = $Process->getModuleName($businessType);
		$patientName = $Process->getPatientNameById($patientId);

		$request = array(
			'hos_code' => $hos_code,
			'pay_type' => $payType,
			'trade_type' => $businessType,
			'total_amount' => $totalFee*100,
			'receipt_amount' => $payFee*100,
			'auth_code' => $authCode,
			'subject' => $patientName.($businessText),
			'body' => $patientName.($businessText).'支付'.$payFee.'元钱',
			'terminal_code' => $zzjCode,
		);
		$payTime = date('Y-m-d H:i:s');
		$response = $this->call('pay/barpay',$request);
		$tradeNo = $response['data']['trade_no'];
		$outTradeNo = $response['data']['out_trade_no'];
		$payStatus = $response['data']['trade_status'];

		$payTypeKv = C('PAY_TYPE_KV');
		//支付信息入库
		$param = array(
			'patient_id' => $patientId,
			'patient_name' => $patientName,
			'pay_status' => $payStatus,
			'zzj_code' => $zzjCode,
			'trade_no' => $tradeNo,
			'out_trade_no' => $outTradeNo,
			'auth_code' => $authCode,
			'business_type' => $businessType,
			'out_trade_no' => $outTradeNo,
			'pay_type' => $payTypeKv[$payType],
			'total_fee' => $totalFee*100,
			'personpay_fee' => $payFee*100,
			'insur_fee' => $insurFee*100,
			'pay_time' => $payTime,
			'trade_desc' => $request['body'],
			'pay_confirm_time' => date('Y-m-d H:i:s')
		);
		$Process->savePayment($param);
		if($response['code'] == '000' && $payStatus == 1)
		{
			$result = array(
				"code" => "000",
				"message" => "支付成功",
				"business" => array(
					'tradeNo' => $tradeNo
				)
			);
		}
		else
		{
			$result = array(
				"code" => "501",
				"message" => "支付失败",
				"business" => array(
				)
			);
		}

		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 获取支付状态(用于C扫B)
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function status()
	{
		$zzjCode = I('post.zzjCode');
		$payType = I('post.payType');
		$tradeNo = I('post.tradeNo');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('payType', 1, '', '' ,''),
	        array('tradeNo', 1, '', '' ,''),
	    );
	    $this->validate($rules);

		$hos_code = D('Config','Logic')->getConfValue('hos.code');
		$request = array(
			'hos_code' => $hos_code,
			'pay_type' => $payType,
			'trade_no' => $tradeNo,
			'terminal_code' => $zzjCode,
		);

		$response = $this->call('pay/query',$request);
		if($response['code'] == '000')
		{
			$payStatus = $response['data']['trade_status'];
			//更新订单状态
			$param = array(
				'trade_no' => $tradeNo,
				'pay_status'=> $payStatus,
				'pay_confirm_time' => time(),
			);
			D('Process','Logic')->savePayment($param);
			$result = array(
				"code" => "000",
				"message" => "查询成功",
				"business" => array(
					'trade_no' => $tradeNo,
					'payStatus' => $payStatus
				)
			);
		}
		else
		{
			$result = array(
				"code" => "501",
				"message" => "查询失败,请重试",
				"business" => array(
					'trade_no' => $tradeNo,
					'payStatus' => $payStatus
				)
			);
		}
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 退款接口(用于自助机自动退款)
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function refund($param)
	{
		if($param)
		{
			$ptkv = C('PAY_TYPE_KV');
			$ptkv = array_flip($ptkv);
			foreach ($param as $k => $v)
			{
				if($k == 'payType')
				{
					$$k = $ptkv[$v];

				}
				else
					$$k = $v;
			}
		}
		else
		{
			$zzjCode = I('post.zzjCode');
			$patientId = I('post.patientId');
			$payType = I('post.payType');
			$refundFee = I('post.refundFee');
			$totalFee = I('post.totalFee');
			$businessType = I('post.businessType');
			$tradeNo = I('post.tradeNo');
			$rules = array(
		        array('zzjCode', 1, '', '' ,''),
		        array('payType', 1, '', '' ,''),
		        array('refundFee', 1, '', '' ,''),
		        array('totalFee', 1, '', '' ,''),
		        array('businessType', 1, '', '' ,''),
		        array('tradeNo', 1, '', '' ,''),
			);
			$this->validate($rules);
		}
		if(C('IS_1FEN'))
		{
			$refundFee = $totalFee = 0.01;
		}
		//需要查库的
	    $Process = D('Process','Logic');
		$hos_code = D('Config','Logic')->getConfValue('hos.code');
		$businessText = $Process->getModuleName($businessType);
		$patientName = $Process->getPatientNameById($patientId);

		$request = array(
			'hos_code' => $hos_code,
			'pay_type' => $payType,
			'trade_type' => $businessType,
			'trade_no' => $tradeNo,
			'total_amount' => $totalFee*100,
			'refund_amount' => $refundFee*100,
			'subject' => $patientName.($businessText),
			'body' => $patientName.($businessText).'退款'.$payFee.'元钱',
			'terminal_code' => $zzjCode,
		);
		$response = $this->call('pay/refund',$request);
		if($response['code'] == '000')
		{
			$result = array(
				"code" => "000",
				"message" => "退款成功",
				"business" => array(
					'tradeNo' => $tradeNo
				)
			);
		}
		else
		{
			$result = array(
				"code" => "501",
				"message" => "退款失败,"+$response['msg'],
				"business" => array(
				)
			);
		}
		if(empty($param))
			$this->ajaxReturnS($result,"JSON");
		else
			return $result;
	}
	/*
	 * @desc 取消接口(用于自助机用户超时未支付)
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function cancel()
	{
		$zzjCode = I('post.zzjCode');
		$payType = I('post.payType');
		$tradeNo = I('post.tradeNo');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('payType', 1, '', '' ,''),
	        array('tradeNo', 1, '', '' ,''),
	    );
	    $this->validate($rules);

	    $hos_code = D('Config','Logic')->getConfValue('hos.code');
		$request = array(
			'hos_code' => $hos_code,
			'pay_type' => $payType,
			'trade_no' => $tradeNo,
			'terminal_code' => $zzjCode,
		);
		$response = $this->call('pay/cancel',$request);
		if($response['code'] == '000')
		{
			$result = array(
				"code" => "000",
				"message" => "取消成功",
				"business" => array()
			);
		}
		else
		{
			$result = array(
				"code" => "501",
				"message" => "取消失败,请重试",
				"business" => array()
			);
		}
		$this->ajaxReturnS($result,"JSON");
	}
}