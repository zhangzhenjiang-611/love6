<?php
/*
 * @desc 对接HIS层接口类
 * @author wangxinghua
 * @final 2019-12-31
 */
class His
{
	/*
	 * @desc 调用接口处理并返回
	 * @author wangxinghua
	 * @final 2019-12-31
	 */	
	private function call($method,$request)
	{
		$his_url = C('RWZB_HIS_HOST_URL').'/'.$method;

		/*
		$request['appid'] = C('APPID');
   		$request['timestamp'] = time();
   		$request['sign'] = sign($request);
   		*/
   		$config = require './Application/Common/Conf/his_json_data.php';
   		if(C('IS_HIS_TEST') && isset($config[$method]))
   		{
   			$response = $config[$method];
   		}
   		else
   		{
			$response = curl_http($his_url,$request);
   		}
		$response = json_decode($response,true);
		return $response;
	}

	/*-----------患者相关方法start----------*/
	/*
	 * @desc 获取患者信息接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function GetPatient($params)
	{
		switch ($params['cardType']) {
			case '1':
				$in['insurCardNo'] = $params['cardNo'];
				break;
			case '2':
			case '4':
				$in['cardNo'] = $params['cardNo'];
				break;
			case '3':
				$in['idCard'] = $params['idNo'];
				$in['cardType'] = 1;
				break;	
		}
		$in['name'] = $params['name'];
		$in['gender'] = $params['sex'];

		$out = $this->call('patient_query', $in);
		return $out;
	}
	/*
	 * @desc 患者建档接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function CreatePatient($in)
	{
		$out = $this->call('patient_reg', $in);
		if(isset($out['data']['idCard']))
		{
			$out['data']['idNo'] = $out['data']['idCard'];
		    unset($out['data']['idCard']);
		}
		return $out;
	}
	
	/*-----------患者相关方法end------------*/

	/*-----------医生、科室、号源、排班相关方法start----------*/
	/*
	 * @desc 获取出诊科室接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function GetVisitDepartments($in)
	{
		$out = $this->call('treat_departs', $in);
		$res = array();
		if($out['code'] == '0')
		{
			foreach ($out['data'] as $v)
			{
				if(!isset($v['pcode']) || $v['pcode'] == '')
				{
					$res[$v['code']]['deptCode'] = $v['code'];
					$res[$v['code']]['deptName'] = $v['name'];
					$res[$v['code']]['deptLevel'] = 1;
				}
				else
				{
					$res[$v['pcode']]['second'][] = array(
						'deptCode' => $v['code'],
						'deptName' => $v['name'],
						'deptLevel' => 2,
					);
				}
			}
		}
		$res = array_values($res);
		return $res;
	}

	/*
	 * @desc 获取号源详细信息接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function GetTickets($in)
	{
		$out = $this->call('tickets', $in);
		$res = array();
		if($out['code'] == '0')
		{
			foreach ($out['data'] as $v)
			{
				if(empty($v['dctName'])) continue;
				if($v['ampm'] == 1)
				{
					$key = 'shangwu';
					$half = '上午';
				}
				else if($v['ampm'] == 2)
				{
					$key = 'xiawu';
					$half = '下午';
				}
				$doctPic = web_server_url().__ROOT__.'/public/img/doctor0.jpg';
				$res[$key][] = array(
                    'dsNo' => $v['no'],
                    'deptCode' => $v['dpcode'],
                    'deptName' => $v['dpName'],
                    'doctCode' => $v['dctcode'],
                    'doctName' => $v['dctName'],
                    'doctPic' => $doctPic,
                    'doctTitle' => $v['empTitle'],
                    'serviceFee' => $v['fee'] / 100,
                    'serviceDate' => $v['date'],
                    'sourceNumber' => intval($v['left']),
                    'sourceHalf' => $half,
                    'specialty' => $v['specialty'],
                    'reqType' => $v['reqType']
                );
			}
		}
        return $res;
	}
	/*
	 * @desc 获取号源简要信息接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function GetTicketsShort($in)
	{
		$out = $this->call('short_tickets', $in);
		if($out['code'] == '0')
			return $out['data'];
		else
			return array();
	}
	
	/*-----------医生、科室、号源、排班相关方法end------------*/
	

	/*-----------预约挂号相关方法start----------*/
	/*
	 * @desc 获取预约挂号列表接口(多条)
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function GetAppointTickets($in)
	{
		$out = $this->call('appoint_query_mulity', $in);
		if($out['code'] == '0')
		{
			//只返回一条数据处理
			if(!isset($out['data'][0]))
			{
				$out['data'][0] = $out['data'];
				$out['data'] = array($out['data'][0]);
			}
			$res = array();
			foreach($out['data'] as $v)
			{
				//if($v['payFlag'] == 1 || $v['schDate'] != $in['startDate']) 
					//continue;
			    $res[] = array(
				    'apNo' => $v['regNo'],
	                'deptCode' => $v['dpcode'],
	                'deptName' =>  $v['dpName'],
	                'serviceFee' =>  $v['charge'] / 100,
	                'serviceDate' =>  $v['schDate'],
	                'serviceTime' => $v['suggestTime'],
	                'status' => $v['regFlag'] == 10 ? 0 :1
	            );
            }
			return $res;
		}
		else
			return array();
	}
	/*
	 * @desc 获取预约挂号详情接口(单条)
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function GetAppointTicketOne($params)
	{
		$in['regNo']  = $params['apNo'];
		$in['schDate']  = $params['date'];
		$in['patientId']  = $params['patientId'];
		$in['patientName']  = $params['patientName'];

		$out = $this->call('appoint_query', $in);
		$res = array();
		if($out['code'] == '0')
		{
			$v = $out['data'];
			if(!($v['payFlag'] == 1 || $v['schDate'] != $params['date'])) 
			{
				$res = array(
				    'apNo' => $v['regNo'],
	                'deptCode' => $v['dpcode'],
	                'deptName' =>  $v['dpName'],
	                'serviceFee' =>  $v['charge'] / 100,
	                'serviceDate' =>  $v['schDate'],
	                'serviceTime' => $v['suggestTime'],
	                'status' => $v['regFlag'] == 10 ? 0 :1
	            );
			}
		}
		return $res;
	}
	/*
	 * @desc 预约挂号锁号接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function AppointLockDiv($params)
	{
		$in['ticketCode'] = $params['dsNo'];
		$in['patientId'] = $params['patientId'];
		$half_kv = array('上午' => 1, '下午' => 2);
		$in['period'] = $half_kv[$params['sourceHalf']];
		$in['ticketDate'] = $params['serviceDate'];
		$in['reqType'] = $params['reqType'];
		$in['mobile'] = $params['patientInfo']['mobile'];

		$out = $this->call('appoint_lock', $in);
		return $out;
	}
	/*
	 * @desc 预约挂号确认接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function AppointConfirm($params)
	{
		$in['ticketCode'] = $params['dsNo'];
		$in['patientId'] = $params['patientId'];
		$half_kv = array('上午' => 1, '下午' => 2);
		$in['period'] = $half_kv[$params['sourceHalf']];
		$in['ticketDate'] = $params['serviceDate'];
		$in['reqType'] = $params['reqType'];
		$in['mobile'] = $params['patientInfo']['mobile'];

		$out = $this->call('appoint', $in);
		return $out;
	}
	/*
	 * @desc 预约挂号取消接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function AppointCancel($params)
	{
		$in['patientId'] = $params['patientId'];
		$in['regNo'] = $params['apNo'];
		
		$out = $this->call('appoint_cancel', $in);
		return $out;
	}
	/*
	 * @desc 预约取号确认接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function AppintAcquireConfirm($params)
	{
		$in['patientId'] = $params['patientId'];
		$in['regNo'] = $params['apNo'];

		$out = $this->call('appoint_acquire', $in);
		return $out;
	}
	/*-----------预约挂号相关方法end------------*/

	/*-----------普通挂号相关方法start----------*/
	/*
	 * @desc 号源锁定接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function RegisterLockDiv($params)
	{
		$in['ticketCode'] = $params['dsNo'];
		$in['patientId'] = $params['patientId'];
		$in['name'] = $params['patientName'];
		$half_kv = array('上午' => 1, '下午' => 2);
		$in['period'] = $half_kv[$params['sourceHalf']];

		$out = $this->call('lock_reg', $in);
		return $out;
	}
	/*
	 * @desc 挂号确认接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function RegisterConfirm($params)
	{
		$in['ticketCode'] = $params['dsNo'];
		$in['patientId'] = $params['patientId'];
		$half_kv = array('上午' => 1, '下午' => 2);
		$in['period'] = $half_kv[$params['sourceHalf']];

		$in['payType'] = $params['payType'];
		$in['regIndex'] = 0;
		$in['tradeNo'] = $params['tradeNo'];
		$in['orderNo'] = $params['outTradeNo'];
		$in['payCardNo'] = $params['patientId'].$params['payType'];

		$out = $this->call('pay_confirm', $in);
		return $out;
	}
	
	/*-----------普通挂号相关方法end------------*/

	/*-----------缴费相关方法start----------*/
	/*
	 * @desc 获取缴费处方信息接口(多条)
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function GetFeeOrders($params)
	{
		$in['patientId'] = $params['patientId'];
		$in['name'] = $params['patientName'];
		//$out = $this->call('UnpaidFeeList', $in);
		$out = $this->call('paid_fee_list', $in);

		$res = array();	
		if($out['code'] == '0')
		{
			$k = 0;
			foreach($out['data'] as $d)
			{
				if($params['allowSelect'] == 1 && $params['orderNos'])
				{
					if(!in_array($d['feeOrderId'], explode(',', $params['orderNos'])))
					{
						continue;
					}
				}
				$res['orders'][$k] = array(
					'orderNo' => $d['feeOrderId'],
					'deptCode' => $d['dpcode'],
					'deptName' => $d['dpName'],
					'orderFee' => $d['totalFee'] / 100,
					'orderDate' => substr($d['createDate'], 0, 10)
				);
				if(!isset($d['feeItems']))	
				{
					$params_one = array(
						'patientId' => $in['patientId'],
						'feeOrderId' => $d['feeOrderId'],
					);
					$res['orders'][$k]['orderItems'] = $this->GetFeeOrderOne($params_one);	
				}
				else
				{
					foreach ($d['feeItems'] as $fk => $fd) 
					{
						$res['orders'][$k]['orderItems'][$fk] = array(
							'itemNo' => $fd['itemId'],
	                        'itemName' => $fd['itemName'],
	                        'itemNumber' => $fd['itemNum'],
	                        'itemPrice' => $fd['itemUnitPrice'] / 100,
	                        'itemUnit' => $fd['itemUnit'] ? $fd['itemUnit'] : '',
	                        'itemSpec' => $fd['itemSpec'] ? $fd['itemSpec'] : '',
	                        'itemFee' => $fd['itemFee'] / 100
						);
					}
				}
				$k++;
			}
		}
		return $res;
	}
	/*
	 * @desc 获取缴费处方详情接口(单条)
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function GetFeeOrderOne($params)
	{
		$in['patientId'] = $params['patientId'];
		$in['feeOrderId'] = $params['feeOrderId'];

		//$out = $this->call('UnpaidFeeView', $in);
		$out = $this->call('paid_fee_view', $in);

		$res = array();
		if($out['code'] == '0')
		{
			foreach($out['data']['feeItems'] as $k => $d)
			{
				$res[$k] = array(
					'itemNo' => $d['itemId'],
	                'itemName' => $d['itemName'],
	                'itemNumber' => $d['itemNum'],
	                'itemPrice' => $d['itemUnitPrice'] / 100,
	                'itemUnit' => $d['itemUnit'] ? $d['itemUnit'] : '',
	                'itemSpec' => $d['itemSpec'] ? $d['itemSpec'] : '',
	                'itemFee' => $d['itemFee'] / 100
				);
			}
		}
		return $res;
	}
	/*
	 * @desc 缴费支付锁定划价接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function FeeLockDiv($params)
	{
		$in['patientId'] = $params['patientId'];
		$in['itemArr'] = array();
		foreach(explode($params['orderNos']) as $r) 
		{
			$in['itemArr']['feeOrderId'][] = $r;	
		}
		$in['itemArr'] = json_encode($in['itemArr']);
		//$out = $this->call('paid_lock', $in);
		$out = array(
			'code' => 0,
		);
		return $out;
	}
	/*
	 * @desc 缴费支付确认接口
	 * @author wangxinghua
	 * @final 2020-04-16
	 */	
	public function FeeConfirm($params)
	{
		$in['patientId'] = $params['patientId'];
		$in['payType'] = $params['payType'];
		$in['tradeNo'] = $params['outTradeNo'];
		$in['orderNo'] = $params['tradeNo'];
		$in['payCardNo'] = $params['patientId'].$params['payType'];
		$in['total'] = $params['totalFee'];

		$in['itemArr'] = array();
		foreach($params['orderNos'] as $k => $r) 
		{
			$in['itemArr'][] = array(
				'feeOrderId' => $k,	
				'amount' => $r
			);
		}
		$in['itemArr'] = json_encode($in['itemArr']);
		$out = $this->call('paid_confirm', $in);
		//$out = $this->call('PayClinicConfirm', $in);
		return $out;
	}
	
	/*-----------缴费相关方法end------------*/
	/*-----------检验检查报告相关方法start----------*/
	/*-----------检验检查报告相关方法end------------*/

	/*-----------住院相关方法start----------*/
	/*-----------住院相关方法end------------*/
}