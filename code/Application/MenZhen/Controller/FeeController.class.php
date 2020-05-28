<?php
/*
 * @desc 缴费流程相关控制器
 * @author wangxinghua
 * @final 2020-01-13
 */
namespace MenZhen\Controller;
use Think\Controller;
class FeeController extends BaseController {
	/*
	 * @desc 待缴费列表
	 * @author wangxinghua
	 * @final 2020-01-13
	 */
	public function lists()
	{
		$zzjCode = I('post.zzjCode');
		$patientId = I('post.patientId');
		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	       	array('patientId', 1, '','',''),
	    );
	    $this->validate($rules);

		//获取缴费数据
		Vendor("His");
		$HIS = new \His();
		$request = array(
			'patientId' => $patientId,
			'patientName' => D('Process','Logic')->getPatientNameById($params['patientId']),
		);
	    $orders = $HIS->GetFeeOrders($request);
	    $orders = $orders['orders'];

		$allowSelect = D('Config','Logic')->getConfValue('enabled.fee.selected');
		$business = array(
			'orders' => $orders,
			'allowSelect' => $allowSelect,
		);
		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 缴费划价
	 * @author wangxinghua
	 * @final 2020-01-13
	 */
	public function divide()
	{
		$zzjCode = I('post.zzjCode');
		$patientId = I('post.patientId');
		$orderNos = I('post.orderNos');
		$cardType = I('post.cardType');
		$divStep = I('post.divStep',2);
		$divResult = I('post.divResult');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	       	array('patientId', 1, '','',''),
	        array('orderNos', 1, '', '' ,''),
	        array('cardType', 1, '', '/^(1|2|3|4)$/' ,''),
	    );
	    $this->validate($rules);
	    //如果是医保卡验证参数
	    if($cardType == 1)
	    {
	    	if(!preg_match("/^(1|2)$/",$divStep))
	    	{
	    		$this->err_result("参数divStep不合法");
	    	}
	    	if($divStep == 2 && $divResult == "")
	    	{
	    		$this->err_result("参数divResult不能为空");
	    	}
	    }

	    //获取是否允许缴费项目勾选
		$allowSelect = D('Config','Logic')->getConfValue('enabled.fee.selected');
		
		Vendor("His");
		$HIS = new \His();

		//缴费划价锁定
		$request = array(
			'patientId' => $patientId,
			'orderNos' => $orderNos,
		);
		$response =  $HIS->FeeLockDiv($request);
		if($response['code'] != '0')
		{
			$this->err_result("缴费划价失败，请稍后重试");
		}
		//获取缴费数据
		$request = array(
			'patientId' => $patientId,
			'patientName' => D('Process','Logic')->getPatientNameById($params['patientId']),
			'allowSelect' => $allowSelect,
			'orderNos' => $orderNos,
		);

	    $orders = $HIS->GetFeeOrders($request);
	    $orders = $orders['orders'];

		$totalFee = array_sum(array_column($orders, 'orderFee'));

		if($cardType == 1 && $divStep == 1)
		{
			Vendor("YbMan");
			$param = array(
				'zzj_code' => $zzjCode,
				'data'=> $orders 
			);
			$divParam = \YbMan::DivideInfoParam($param,'zzjf');
			$business = array(
				'divParam'=> $divParam
			);
		}
		else
		{
			if($cardType == 1)
			{
				Vendor("YbMan");
				$divResult = \YbMan::DivideResultParse($divResult);
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
	 * @desc 缴费保存
	 * @author wangxinghua
	 * @final 2020-01-15
	 */
	public function save()
	{
		$zzjCode = I('post.zzjCode');
		$tradeNo = I('post.tradeNo');
		$patientId = I('post.patientId');
		$orderNos = I('post.orderNos');
		$cardType = I('post.cardType');
		$cardNo = I('post.cardNo');
		$traResult = I('post.traResult');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('tradeNo', 1, '','',''),
	       	array('patientId', 1, '','',''),
	        array('orderNos', 1, '', '' ,''),
	        array('cardType', 1, '', '/^(1|2|3|4)$/' ,''),
	        array('cardNo', 1, '', '' ,''),
	    );
	    $in = $this->validate($rules);

	    //如果是医保卡验证参数
	    if($cardType == 1)
	    {
	    	if($traResult == "")
	    	{
	    		$this->err_result("参数traResult不能为空");
	    	}
	    }
	    //获取是否允许缴费项目勾选
		$config = D('Config','Logic')->getConfValue(array('enabled.fee.selected','enabled.dev.auto.refund'));
		$allowSelect = $config['enabled.fee.selected'];
		$allowRefund = $config['enabled.dev.auto.refund'];

		$Process = D('Process','Logic');
		if($tradeNo)
		{
			//查询支付信息
			$payment = $Process->getPayment(array('trade_no' => $tradeNo));
			if($payment)
			{
				//更新支付信息
				$Process->savePayment(array(
					'payment_id' => $payment['id'],
					'patient_id' => $patientId,
					'card_type' => $cardType,
					'card_no' => $cardNo
				));
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
				'business_type' => ''
			);
		}

		Vendor("His");
		$HIS = new \His();
		//获取缴费数据
		$request = array(
			'patientId' => $patientId,
			'patientName' => D('Process','Logic')->getPatientNameById($params['patientId']),
			'allowSelect' => $allowSelect,
			'orderNos' => $orderNos,
		);
		$orders = $HIS->GetFeeOrders($request);
	    $orders = $orders['orders'];

		$confirm_request = array(
			'patientId'=> $patientId,
			'orderNos' => array_column($orders, 'orderFee', 'orderNo'),
			'payType' => $payment['pay_type'],
			'tradeNo' => $tradeNo,
			'totalFee' => array_sum(array_column($orders, 'orderFee')) * 100,
			'outTradeNo' => $payment['out_trade_no'],
		);


		if($cardType == 1)//医保
	    {
			//医保交易确认
			Vendor("YbMan");
			$divResult = \YbMan::TradeResultParse($traResult);

			if($divResult['error'] != "")//医保失败
			{
				$insur_state = 0;
				$his_state = 2;
			}
			else//医保成功
			{
				$insur_state = 1;
				//HIS缴费支付确认
				Vendor("His");
				$HIS = new \His();

				$response = $HIS->FeeConfirm($confirm_request);
				if($response['code'] == '0')
					$his_state = 1;
				else
					$his_state = 0;
			}
		}
		else//自费
		{
			$insur_state = 2;
			//HIS缴费支付确认
			Vendor("His");
			$HIS = new \His();
			$response = $HIS->FeeConfirm($confirm_request);

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
				$refund_result = $refund_result = $Pay->refund(array(
					'zzjCode' => $zzjCode,
					'tradeNo' => $tradeNo,
					'payType' => $payment['pay_type'],
					'totalFee' => $payment['total_fee'],
					'refundFee' => $payment['personpay_fee'],
					'businessType' => $payment['business_type'],
					'patientId' => $patientId,
				));
			}
		}
		//组织凭条打印内容
		Vendor("Paint");
		$printStr = \Paint::printStr(array(
			'business_type' => 'zzjf',
			'code' => $code,
			'payment' => $payment,
			'in' => array_merge(array('orders' => $orders), $in),
			'out' => $response['data'],
			'allowRefund' => $allowRefund,
			'refund_result' => $refund_result
		));
		//凭条表
		$Process->savePaint(array(
			'zzj_code' => $zzjCode,
			'business_type' => 'zzjf',
			'patient_id' => $patientId,
			'paint_str' => $printStr
		));
		//缴费表
		$Process->saveFee(array(
			'zzj_code' => $zzjCode,
			'patient_id' => $patientId,
			'card_type' => $cardType,
			'card_no' => $cardNo,
			'fee_ids' => $orderNos,
			'fee_list' => json_encode($orders),
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