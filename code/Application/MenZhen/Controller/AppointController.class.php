<?php
/*
 * @desc 预约流程相关控制器
 * @author wangxinghua
 * @final 2020-01-13
 */
namespace MenZhen\Controller;
use Think\Controller;
class AppointController extends BaseController {

	/*
	 * @desc 预约挂号时间数据
	 * @author wangxinghua
	 * @final 2020-01-13
	 */
	public function calendar()
	{
		$zzjCode = I('post.zzjCode');
		$deptCode = I('post.deptCode');

	    $rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('deptCode', 1, '', '' ,''),
	    );
	    $this->validate($rules);

		$d = date('YmdNt');//获取所需要的数据
		$w_kv = array(
			1 => '一',
			2 => '二',
			3 => '三',
			4 => '四',
			5 => '五',
			6 => '六',
			7 => '日',
		);

		$conf = D('Config','Logic')->getConfValue(array('appoint.charge.process','appoint.day.num'));
		$acp = $conf[0];
		$adn = $conf[1];

		$ym = $d{0}.$d{1}.$d{2}.$d{3}.'年'.$d{4}.$d{5}.'月';//年月
		$n = $d{9}.$d{10};//本月有多少天
		$w = $w_kv[$d{8}];//星期几

		$a = array();//日历
		$r = $d{6}.$d{7};//日

		$num = 30;
		Vendor("His");
		$HIS = new \His();
		$regFlag = 2;
		$startDate = date('Y-m-d',strtotime('+ 1 day'));
		$endDate = date('Y-m-d',strtotime("+ ".$adn." day"));
		$request = array(
			'departCode' => $deptCode,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'regFlag' => $regFlag
		);
		$result = $HIS->GetTicketsShort($request);
		
		foreach ($result as $key => $value) {
			if($value['flag'] == 1)
			{
				if($value['left'] > 0)
					$ticket[$key] = array(1,$value['left']);
				else
					$ticket[$key] = 2;
			}
			else
			{
				$ticket[$key]  = array(3,$value['left']);
			}
		}

		$ws = array(); 
		$t = $d{0}.$d{1}.$d{2}.$d{3}.'-'.$d{4}.$d{5}.'-'.$d{6}.$d{7};

		$ww = $d{8};
		for($i=0;$i<$num;$i++)
		{
			$d = date('YmdN',strtotime((-(13+$ww)+$i)." day"));
			$k = $d{0}.$d{1}.$d{2}.$d{3}.'-'.$d{4}.$d{5}.'-'.$d{6}.$d{7};
			$ks = $i;
			$w = $w_kv[$d{8}];

			$i2 = 0;
			$i3 = 3;

			if(isset($ticket[$ks]) && $k > $t)
			{
				if(is_array($ticket[$ks]))
				{
					$i2 = $ticket[$ks][1];
					$i3 = $ticket[$ks][0];
				}
				else
					$i3 = $ticket[$ks];
			}
			$a[$i] = array($k, $w, $i2, $i3);

			if($i<7)
				$ws[] = $w_kv[$d{8}];
		}

		$i = 0;
		$b = array();
		foreach($a as $k=>$v)
		{
			$b[$i][] = $v;
			if(($k+1) % 7 == 0)	
				$i++;
		}

	   	if($acp == 'GH')
	   		$business['isPay'] = 1;
	   	else
	   		$business['isPay'] = 0;

		$business['yearmonth'] = $ym;
		$business['week'] = $ws;
		$business['date'] = $b;

		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 预约挂号划价
	 * @author wangxinghua
	 * @final 2020-01-13
	 */
	public function regDivide()
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
	        array('serviceFee', 0, '', '' ,''),
	        array('serviceDate', 0, '', '' ,''),
	        array('sourceHalf', 0, '', '' ,''),
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

	    //获取是否需要付款
		$acp = D('Config','Logic')->getConfValue('appoint.charge.process');
		
		$totalFee = $params['serviceFee'];
		if($params['cardType'] == 1 && $params['divStep'] == 1 && $acp == 'GH')
		{
			Vendor("YbMan");
			$request = array(
				'zzj_code' => $params['zzjCode'],
				'data'=> $params
			);
			$divParam = \YbMan::DivideInfoParam($request,'yygh');
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
			$response = $HIS->AppointLockDiv($request);
			if($response['code'] != '0')
			{
				$this->err_result("对不起，锁号失败请重试");
			}
			if($params['cardType'] == 1  && $acp == 'GH')
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
	 * @desc 预约挂号保存
	 * @author wangxinghua
	 * @final 2020-01-15
	 */
	public function regSave()
	{
		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('tradeNo', 0, '','',''),
	       	array('patientId', 1, '','',''),
	        array('cardType', 1, '', '/^(1|2|3|4)$/' ,''),
	        array('cardNo', 1, '', '' ,''),
	        array('dsNo', 1, '', '' ,''),
	        array('deptCode', 0, '', '' ,''),
	        array('deptName', 0, '', '' ,''),
	        array('doctCode', 0, '', '' ,''),
	        array('doctName', 0, '', '' ,''),
	        array('doctTitle', 0, '', '' ,''),
	        array('serviceFee', 0, '', '' ,''),
	        array('serviceDate', 0, '', '' ,''),
	        array('sourceHalf', 0, '', '' ,''),
	        array('specialty', 0, '', '' ,''),
	        array('traResult', 0, '', '' ,''),
	        array('reqType', 0, '', '' ,''),
	    );
	    $params = $this->validate($rules);

	    //获取配置
		$config = D('Config','Logic')->getConfValue(array('appoint.charge.process','enabled.dev.auto.refund'));
		$acp = $config['appoint.charge.process'];
		$allowRefund = $config['enabled.dev.auto.refund'];

    	if($acp == 'GH' && $params['tradeNo'] == "")
    	{
    		$this->err_result("参数tradeNo不能为空");
    	}
	    //如果是医保卡验证参数
	    if($cardType == 1)
	    {
	    	if($acp == 'GH' && $params['traResult'] == "")
	    	{
	    		$this->err_result("参数traResult不能为空");
	    	}
	    }
	    $Process = D('Process','Logic');
		if($acp == 'GH' && $params['tradeNo'])
		{
			//查询支付信息
			$payment = $Process->getPayment(array('trade_no' => $params['tradeNo']));
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

		$appoint_request =  array(
			'patientId' => $params['patientId'],
			'dsNo' => $params['dsNo'],
			'sourceHalf' => $params['sourceHalf'],
			'serviceDate' => $params['serviceDate'],
			'reqType' => $params['reqType'],
			'patientInfo' => $Process->getPatient(array('patient_id' => $params['patientId']), 'mobile')
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
				//HIS预约确认
				Vendor("His");
				$HIS = new \His();
				$response = $HIS->AppointConfirm($appoint_request);
				if($response['code'] == '0')
					$his_state = 1;
				else
					$his_state = 0;
			}
		}
		else//自费
		{
			$insur_state = 2;
			//HIS预约确认
			Vendor("His");
			$HIS = new \His();
			$response = $HIS->AppointConfirm($appoint_request);
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
		$printStr = \Paint::printStr(array(
			'business_type' => 'yygh',
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
			'business_type' => 'yygh',
			'patient_id' => $params['patientId'],
			'print_str' => $printStr
		));
		//预约挂号表
		$Process->saveAppoint(array(
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
	/*
	 * @desc 预约挂号取消
	 * @author wangxinghua
	 * @final 2020-01-15
	 */
	public function regCancel()
	{
		$zzjCode = I('post.zzjCode');
		$apNo = I('post.apNo');
		$patientId = I('post.patientId');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('apNo', 1, '','',''),
	       	array('patientId', 1, '','',''),
	    );
	    $this->validate($rules);
	    Vendor("His");
		$HIS = new \His();
		$request = array(
			'patientId' => $patientId,
			'apNo' => $apNo,
		);
	    $result = array();
	    $response = $HIS->AppointCancel($request);
	    if($response['code'] == '0')
	    {
	    	$result['code'] = '000';
	    	$result['msg'] = '成功';
	    	$result['business'] = array();
	    }
	    else
	    {
	    	$result['code'] = '105';
	    	$result['msg'] = '失败';
	    	$result['business'] = array();
	    }
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 预约取号列表
	 * @author wangxinghua
	 * @final 2020-01-15
	 */
	public function numLists()
	{
		$zzjCode = I('post.zzjCode');
		$patientId = I('post.patientId');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	       	array('patientId', 1, '','',''),
	    );
	    $this->validate($rules);

		$patientName = D('Process','Logic')->getPatientNameById($patientId);
		Vendor("His");
		$HIS = new \His();
		$request = array(
			'patientId' => $patientId,
			'startDate' => date('Y-m-d'),//仅限当天取号
			'endDate' => date('Y-m-d'),//仅限当天取号
			'patientName' => $patientName,
		);

	    $business['tickets'] = $HIS->GetAppointTickets($request);

	   	$acp = D('Config','Logic')->getConfValue('appoint.charge.process');
	   	if($acp == 'QH')
	   		$business['isPay'] = 1;
	   	else
	   		$business['isPay'] = 0;

		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 预约取号划价
	 * @author wangxinghua
	 * @final 2020-01-13
	 */
	public function numDivide()
	{
		$zzjCode = I('post.zzjCode');
		$patientId = I('post.patientId');
		$apNo = I('post.apNo');
		$cardType = I('post.cardType');
		$divStep = I('post.divStep',2);
		$divXml = I('divXml');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	       	array('patientId', 1, '','',''),
	        array('apNo', 1, '', '' ,''),
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

	    //获取是否需要付款
		$acp = D('Config','Logic')->getConfValue('appoint.charge.process');
		
		Vendor("His");
		$HIS = new \His();
	    $request = array(
			'patientId' => $patientId,
			'patientName' => D('Process','Logic')->getPatientNameById($patientId),
			'apNo' => $apNo,
			'date' => date('Y-m-d')
		);

		$ticket = $HIS->GetAppointTicketOne($request);

		$totalFee = $ticket['serviceFee'];

		if($cardType == 1 && $divStep == 1 && $acp == 'QH')
		{
			Vendor("YbMan");
			$param = array(
				'zzj_code' => $zzjCode,
				'data'=> $ticket
			);
			$divParam = \YbMan::DivideInfoParam($param,'yyqh');
			$business = array(
				'divParam'=> $divParam
			);
		}
		else
		{
			if($cardType == 1  && $acp == 'QH')
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
	 * @desc 预约取号保存
	 * @author wangxinghua
	 * @final 2020-01-15
	 */
	public function numSave()
	{
		$zzjCode = I('post.zzjCode');
		$tradeNo = I('post.tradeNo');
		$apNo = I('post.apNo');
		$patientId = I('post.patientId');
		$cardNo = I('post.cardNo');
		$cardType = I('post.cardType');
		$traResult = I('post.traResult');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	       	array('patientId', 1, '','',''),
	        array('apNo', 1, '', '' ,''),
	        array('cardType', 1, '', '/^(1|2|3|4)$/' ,''),
	        array('cardNo', 1, '', '' ,''),
	    );
	    $in = $this->validate($rules);
	    $Process =  D('Process','Logic');
	    //获取配置
		$config = D('Config','Logic')->getConfValue(array('appoint.charge.process','enabled.dev.auto.refund'));
		$acp = $config['appoint.charge.process'];
		$allowRefund = $config['enabled.dev.auto.refund'];

    	if($acp == 'QH' && $tradeNo == "")
    	{
    		$this->err_result("参数tradeNo不能为空");
    	}
	    //如果是医保卡验证参数
	    if($cardType == 1)
	    {
	    	if($acp == 'QH' && $traResult == "")
	    	{
	    		$this->err_result("参数traResult不能为空");
	    	}
	    }

		if($acp == 'QH' && $tradeNo)
		{
			//查询支付信息
			$payment = $Process->getPayment(array('trade_no' => $tradeNo));
			if(empty($payment))
			{
				$this->err_result("找不到tradeNo对应的订单信息");
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
				//HIS预约取号确认
				Vendor("His");
				$HIS = new \His();
				$request = array(
					'patientId' => $patientId,
					'apNo' => $apNo,
				);
				$response = $HIS->AppintAcquireConfirm($request);
				if($response['code'] == '0')
					$his_state = 1;
				else
					$his_state = 0;
			}
		}
		else//自费
		{
			$insur_state = 2;
			//HIS预约取号确认
			Vendor("His");
			$HIS = new \His();
			$request = array(
				'patientId' => $patientId,
				'apNo' => $apNo,
			);
			$response = $HIS->AppintAcquireConfirm($request);
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
		Vendor("His");
		$HIS = new \His();
		$request = array(
			'patientId' => $patientId,
			'patientName' => D('Process','Logic')->getPatientNameById($patientId),
			'apNo' => $apNo,
			'date' => date('Y-m-d')
		);
		$ticket = $HIS->GetAppointTicketOne($request);

		//组织凭条打印内容
		Vendor("Paint");
		$printStr = \Paint::printStr(array(
			'business_type' => 'yyqh',
			'payment' => $payment,
			'code' => $code,
			'in' => $in,
			'out' => $response['data']
		));
		//凭条表
		$Process->savePaint(array(
			'zzj_code' => $zzjCode,
			'business_type' => 'yyqh',
			'patient_id' => $patientId,
			'print_str' => $printStr
		));
		//取号表
		$Process->saveAppointAcquire(array(
			'zzj_code' => $zzjCode,
			'patient_id' => $patientId,
			'card_type' => $cardType,
			'card_no' => $cardNo,
			'appoint_id' => $apNo,
			'appoint_list' => json_encode($ticket),
			'appoint_type' => 1,
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