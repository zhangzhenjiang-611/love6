<?php
/*
 * @desc 挂号流程相关控制器
 * @author wangxinghua
 * @final 2020-01-13
 */
namespace MenZhen\Controller;
use Think\Controller;
class RegisterController extends BaseController {
	/*
	 * @desc 挂号费用划价
	 * @author wangxinghua
	 * @final 2020-01-13
	 */
	public function divide()
	{
		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	       	array('patientId', 1, '','',''),
	        array('cardType', 1, '', '/^(1|2|3|4)$/' ,''),
	        array('cardNo', 1, '', '' ,''),
	        array('dsNo', 1, '', '' ,''),
	        array('deptCode', 0, '', '' ,''),
	        array('deptName', 0, '', '' ,''),
	        array('doctCode', 0, '', '' ,''),
	        array('doctName', 0, '', '' ,''),
	        array('doctTitle', 0, '', '' ,''),
	        array('serviceFee', 1, '', '' ,''),
	        array('serviceDate', 1, '', '' ,''),
	        array('sourceHalf', 1, '', '' ,''),
	        array('specialty', 0, '', '' ,''),
	        array('divStep', 0, '', '' ,''),
	        array('divResult', 0, '', '' ,''),
	    );
	    $params = $this->validate($rules);

	    //如果是医保卡验证参数
	    if($params['cardType'] == 1)
	    {
	    	if(!preg_match("/^(1|2)$/",$params['divStep']))
	    	{
	    		$this->err_result("参数divStep不合法");
	    	}
	    	if($params['divStep'] == 2 && $params['divResult'] == "")
	    	{
	    		$this->err_result("参数divResult不能为空");
	    	}
	    }

		$totalFee = $params['serviceFee'];
		if($params['cardType'] == 1 && $params['divStep'] == 1)
		{
			Vendor("YbMan");
			$request = array(
				'zzj_code' => $params['zzjCode'],
				'data'=> $params
			);
			$divParam = \YbMan::DivideInfoParam($request,'jrgh');
			$business = array(
				'divParam'=> $divParam
			);
		}
		else
		{		
			Vendor("His");
			$HIS = new \His();
			$request = array(
				'patientId' => $params['patientId'],
				'patientName' => D('Process','Logic')->getPatientNameById($params['patientId']),
				'dsNo' => $params['dsNo'],
				'sourceHalf' => $params['sourceHalf'],
			);
			$response = $HIS->RegisterLockDiv($request);
			if($response['code'] != '0')
			{
				$this->err_result("对不起，锁号失败请重试");
			}

			if($params['cardType'] == 1)
			{
				Vendor("YbMan");
				$divResult = \YbMan::DivideResultParse($params['divResult']);
				$feeall = $divResult['sumpay']['feeall'];
				$fund = $divResult['sumpay']['fund'];
				$cash = $divResult['sumpay']['cash'];
			}
			else
			{
				$feeall = $totalFee;
				$fund = '0.00';
				$cash = $totalFee;
			}
			$business = array(
				'feeall' => $feeall,
				'fund' => $fund,
				'cash' => $cash
			);
		}
		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business 
		);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 挂号保存
	 * @author wangxinghua
	 * @final 2020-01-15
	 */
	public function save()
	{
		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('tradeNo', 1, '','',''),
	       	array('patientId', 1, '','',''),
	        array('cardType', 1, '', '/^(1|2|3|4)$/' ,''),
	        array('cardNo', 1, '', '' ,''),
	        array('dsNo', 1, '', '' ,''),
	        array('deptCode', 0, '', '' ,''),
	        array('deptName', 0, '', '' ,''),
	        array('doctCode', 0, '', '' ,''),
	        array('doctName', 0, '', '' ,''),
	        array('doctTitle', 0, '', '' ,''),
	        array('serviceFee', 1, '', '' ,''),
	        array('serviceDate', 1, '', '' ,''),
	        array('sourceHalf', 1, '', '' ,''),
	        array('specialty', 0, '', '' ,''),
	        array('traResult', 0, '', '' ,''),
	    );
	    $params = $this->validate($rules);

	    //获取配置
		$config = D('Config','Logic')->getConfValue(array('enabled.dev.auto.refund'));
		$allowRefund = $config['enabled.dev.auto.refund'];

    	if($params['tradeNo'] == "")
    	{
    		$this->err_result("参数tradeNo不能为空");
    	}
	    //如果是医保卡验证参数
	    if($cardType == 1)
	    {
	    	if($params['traResult'] == "")
	    	{
	    		$this->err_result("参数traResult不能为空");
	    	}
	    }

 		$Process = D('Process','Logic');
		if($params['tradeNo'])
		{
			//查询支付信息
			$payment = $Process->getPayment(array('trade_no' => $params['tradeNo']));
			if(empty($payment))
			{
				$this->err_result("此挂号操作的支付信息不存在");
			}
		}
		else
		{
			$payment = array(
				'pay_type' => '',
				'id' => 0,
				'total_fee' => 0,
				'insur_fee' => 0,
				'invoice_fee' => 0,
				'personpay_fee' => 0,
				'business_type' => '',
				'out_trade_no' => ''
			);
		}

		$request = array(
			'patientId' => $params['patientId'],
			'dsNo' => $params['dsNo'],
			'payType' => $payment['pay_type'],
			'tradeNo' => $params['tradeNo'],
			'outTradeNo' => $payment['out_trade_no'],
			'sourceHalf' => $params['sourceHalf'],
		);
		if($cardType == 1)//医保
	    {
			//医保交易确认
			Vendor("YbMan");
			$divResult = \YbMan::TradeResultParse($params['traResult']);
			if($divResult['error'] != "")//医保失败
			{
				$insur_state = 0;
				$his_state = 2;
			}
			else//医保成功
			{
				$insur_state = 1;
				//HIS挂号确认
				Vendor("His");
				$HIS = new \His();
				$response = $HIS->RegisterConfirm($request);
				if($response['code'] == '0')
					$his_state = 1;
				else
					$his_state = 0;
			}
		}
		else//自费
		{
			$insur_state = 2;
			//HIS挂号确认
			Vendor("His");
			$HIS = new \His();
			$response = $HIS->RegisterConfirm($request);
			if($response['code'] == '0')
				$his_state = 1;
			else
				$his_state = 0;
		}

		if($his_state == 1 && $insur_state >= 1)//HIS成功,医保成功
		{
			$code = '000';
			$message = '成功';
		}
		else if($his_state == 0)
		{
			$code = '201';
			$message = 'HIS失败';
		}
		else if($insur_state == 0)
		{
			$code = '202';
			$message = '医保失败';
		}
		else
		{
			$code = '203';
			$message = '失败';
		}

		//自助机自动退款
		$refund_result = array('code' => '100');
		if($code != '000')
		{
			if($allowRefund && $payment['pay_type'] != "")
			{
				$Pay = A("MenZhen/Pay");
				$refund_result = $Pay->refund(array(
					'zzjCode' => $params['zzjCode'],
					'tradeNo' => $params['tradeNo'],
					'payType' => $payment['pay_type'],
					'totalFee' => $payment['total_fee'],
					'refundFee' => $payment['personpay_fee'],
					'businessType' => $payment['business_type'],
					'patientId' => $params['patientId'],
				));
			}
		}
		//组织凭条打印内容
		Vendor("Paint");
		$code = '000';
		$printStr = \Paint::printStr(array(
			'business_type' => 'jrgh',
			'code' => $code,
			'payment' => $payment,
			'in' => $params,
			'out' => $response['data'],
			'allowRefund' => $allowRefund,
    		'refund_result' => $refund_result
		));
		//凭条表
		$Process->savePaint(array(
			'zzj_code' => $params['zzjCode'],
			'business_type' => 'jrgh',
			'patient_id' => $params['patientId'],
			'print_str' => $printStr
		));
		//挂号表
		$Process->saveRegister(array(
			'zzj_code' => $params['zzjCode'],
			'patient_id' => $params['patientId'],
			'card_type' => $params['cardType'],
			'card_no' => $params['cardNo'],
			'ds_no' => $params['dsNo'],
			'doct_code' => $params['doctCode'],
			'doct_name' => $params['doctName'],
			'doct_title' => $params['doctTitle'],
			'specially' => $params['specialty'],
			'dept_code' => $params['deptCode'],
			'dept_name' => $params['deptName'],
			'ds_date' => $params['serviceDate'],
			'ds_period' => $params['sourceHalf'],
			'payment_id' => $payment['id'],
			'total_fee' => $payment['total_fee'],
			'personpay_fee'=> $payment['personpay_fee'],
			'insur_fee' => $payment['insur_fee'],
			'balancepay_fee' => 0,
			'invoice_fee' => $payment['invoice_fee'],
			'his_state' => $his_state,
			'insur_state' => $insur_state
		));

		$business = array(
			'printStr' => $printStr,
		);

		$result = array(
			"code" => $code,
			"message" => $message,
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
}