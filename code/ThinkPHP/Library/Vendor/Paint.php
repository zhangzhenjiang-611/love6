<?php
/*
 * @desc 打印凭条内容处理类
 * @author wangxinghua
 * @final 2019-12-31
 */
class Paint{
	private static $_width = 300;
	private static $_height = 500;
	private static $_hos_name = '融威众邦';
	/*
	 * @desc 返回打印凭条信息串
	 * @author wangxinghua
	 * @final 2019-12-31
	 */	
	public static function printStr($param)
	{
		$s1 = '';
		$pos = 0;
		
		//公共部分
		$head_s1 = "<?xml version='1.0' encoding='utf-8'?><print_info width='".self::$_width."' height='".self::$_height."'>";
		//医院信息部分
		self::$_hos_name = D('Config', 'Logic')->getConfValue('hos.name');
		$businessText = D('Process', 'Logic')->getModuleName($param['business_type']);

		$hos_s1 = '';
		$hos_s1 .= "<tr font='黑体' type='text' size='12' x='15' y='" . ($pos += 0) ."' >".self::$_hos_name."</tr>";
		$hos_s1 .= "<tr font='黑体' type='text' size='12' x='85' y='" . ($pos += 25) ."' >".$businessText."凭证</tr>";
		$hos_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 15) . "' >----------------------------------</tr>";
		//患者信息部分

		$patient_s1 = '';
		if($param['business_type'] != 'zzjk')
		{
			$patientInfo =  D('Process','Logic')->getPatient(array('patient_id' => $param['in']['patientId']), 'name,birthday,gender');
			$gender = array('0'=>'未知','1'=>'男','2'=>'女');

		    $patient_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >" .($param['in']['cardType'] == 1 ? '医保卡号' : '就诊卡号') .":". $param['in']['cardNo'] ."</tr>";
	        $patient_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >姓名：". $patientInfo['name'] ."  性别：". $gender[$patientInfo['gender']] ."</tr>";
	        $patient_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >年龄:". intval((time()-strtotime($patientInfo['birthday']))/(365*86400)) ."  费别:". ($param['in']['cardType'] == 1 ? '医保' : '自费') ."</tr>";
	    }
	    else
	    {
		    $patient_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >身份证号:". (substr($param['in']['idNo'],0,6).'******'.substr($param['in']['idNo'], -6)) ."</tr>";
			$patient_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >姓名：". $param['in']['name'] ."  性别：". $param['in']['sex'] ."</tr>";
    		$patient_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >年龄:". intval((time()-strtotime($param['in']['birthday']))/(365*86400)) ."  费别:自费</tr>";
	    }
	    $patient_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 15) . "' >----------------------------------</tr>";
		//业务信息部分
		$business_s1 = '';
		if($param['code'] != '000')
		{
        	$business_s1 .= "<tr font='黑体' type='text' size='15' x='15' y='" . ($pos += 30) . "' >交易异常(". $param['code'] .")</tr>";
	        if($param['allowRefund'] == 1 && $param['payment']['personpay_fee'] >0 && $param['payment']['pay_type'] < 3)
	        {
	        	if($param['refund_result']['code'] == '000')
	        	{
	        		$business_s1 .= "<tr font='黑体' type='text' size='12' x='15' y='" . ($pos += 30) . "' >您本次支付的金额将退回至原账户，</tr>";
	        		$business_s1 .= "<tr font='黑体' type='text' size='12' x='15' y='" . ($pos += 30) . "' >请您查收。</tr>";
	        	}
	        	else
	        	{
	        		$business_s1 .= "<tr font='黑体' type='text' size='12' x='15' y='" . ($pos += 30) . "' >自动退款失败, 请凭此证</tr>";
	        		$business_s1 .= "<tr font='黑体' type='text' size='12' x='15' y='" . ($pos += 30) . "' >到窗口处理。</tr>";
	        	}
	        }
	        else
	        {
	        	$business_s1 .= "<tr font='黑体' type='text' size='12' x='15' y='" . ($pos += 30) . "' >请您凭此证到窗口处理。</tr>";
	        }
	       	$business_s1 .= "<tr font='黑体' type='text' size='12' x='15' y='" . ($pos += 30) . "' >给您带来不便请谅解。</tr>";
		}
		//提示信息部分
		$notice_s1 = '';
        if($business_s1 == '')
        {
			switch ($param['business_type']) 
			{
				case 'zzjf':
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >缴费日期:". substr($param['payment']['create_time'], 0, 10) ."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='115' y='" . ($pos += 20) . "' >缴费明细单</tr>";
	            	$business_s1 .= $hr;
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >类型</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='65' y='" . ($pos += 0) . "' >名称</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='140' y='" . ($pos += 0) . "' >单价</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='180' y='" . ($pos += 0) . "' >数量/单位</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='250' y='" . ($pos += 0) . "' >小计</tr>";
	            	foreach($param['in']['orders'] as $k => $in)
	            	{
	            		$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >".($k+1).".缴费编号:".$in['orderNo']."</tr>";
	            		foreach ($in['orderItems'] as $f)
	            		{
			            	$business_s1 .="<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >". ($f['itemClass'] ? $f['iemClass'] : '暂无') ."</tr>";
			            	$business_s1 .="<tr font='黑体' type='text' size='10' x='55' y='" . ($pos += 0) . "' >". substr($f['itemName'], 0, 12). "</tr>";
			            	$business_s1 .="<tr font='黑体' type='text' size='10' x='135' y='" . ($pos += 0) . "' >". $f['itemPrice']. "</tr>";
			            	$business_s1 .="<tr font='黑体' type='text' size='10' x='175' y='" . ($pos += 0) . "' >". $f['itemNumber'].'/'.($f['itemUnit'] ? $f['itemUnit'] : $f['itemSpec']) ."</tr>";
			            	$business_s1 .="<tr font='黑体' type='text' size='10' x='245' y='" . ($pos += 0) . "' >". $f['itemFee'] ."</tr>";
	            		}	
	            	}
				break;
				case 'zzjk':
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >建卡时间:". date('Y-m-d H:i') ."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >患者ID:". $param['out']['patientId'] ."</tr>";
					$create_card = array('1' => '京医通卡', '2' => '就诊卡');
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >卡类型:". $create_card[$param['in']['createCardType']]."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >卡号:". $param['in']['newCardNo'] ."</tr>";
				break;
				case 'yygh':
					$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >预约科室:".$param['in']['deptName']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >预约医生:".$param['in']['doctName']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >挂号类别:".$param['out']['regClass']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >预约序号:(".$param['in']['sourceHalf'].")第".$param['out']['regIndex']."号</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >预约日期:".$param['in']['serviceDate']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >候诊时间:".$param['out']['suggestTime']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >候诊地址:".$param['out']['visitAddr']."</tr>";
	            	
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >1.就诊当日持有效身份证件、医保卡、就诊卡、</tr>";
		           	$yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >预约挂号凭条到挂号窗口取号或者自助机取号，</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >同时验证预约信息,不符则医院不能提供相应诊</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >服务</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 25) . "' >2.取号时间:上午号 07:00-9:30</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='90' y='" . ($pos += 20) . "' >下午号13:20-15:00</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >过时未取，预约号作废，请在挂号处重新挂号。</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >3.预约后，因故不能按时就诊者请您于前1个</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >工作日15:工作日在自助机取消预约，否则按爽约处理</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >约处理，累计3次将无法进行预约。</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >4.如遇专家停诊，我们将电话通知您，请保持</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >通讯畅通。</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >5.由于专家除参加门诊医疗工作外，还负担</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >急诊抢救，我们将指定同级医师替诊，敬请患</tr>";
		            $yygh_notice .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >者谅解。</tr>";
	            	$notice_s1 = $yygh_notice.$notice_s1;
				break;
				case 'jrgh':
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >就诊科室:".$param['in']['deptName']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >就诊医生:".$param['in']['doctName']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >挂号类别:".$param['out']['regClass']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >就诊序号:(".$param['in']['sourceHalf'].")第".$param['out']['paySeq']."号</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >就诊日期:".$param['in']['serviceDate']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >候诊时间:".$param['out']['suggestTime']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >候诊地址:".$param['out']['visitAddr']."</tr>";
				break;
				case 'yyqh':
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >就诊科室:".$param['out']['deptName']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >就诊医生:".$param['out']['doctName']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >挂号类别:".$param['out']['regClass']."</tr>";

	            	//如果没有上下午信息，根据候诊时间来算出
	            	if(isset($param['out']['visitHalf']))
	            	{
	            		$half = $param['out']['visitHalf'];
	            	}
	            	else
	            	{
		            	$vt = explode('-', $param['out']['visitTime']);
		            	if($vt >= '12:00')
		            		$half = '下午';
		            	else
		            		$half = '上午';
		            }
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >就诊序号:(".$half.")第".$param['out']['visitSeq']."号</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >就诊日期:".$param['out']['visitDate']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >候诊时间:".$param['out']['visitTime']."</tr>";
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >候诊地址:".$param['out']['visitAddr']."</tr>";
				break;
				break;
				case 'zycz':
	            	$business_s1 .="<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >开发中...</tr>";
				break;
			}
		}
		//费用信息部分
		$fee_s1 = '';
        $fee_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 30) . "' >费用总金额: ". ($param['payment']['total_fee']/100) ."元</tr>";
        $fee_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >个人支付:". ($param['payment']['personpay_fee']/100) ."元</tr>";
        $fee_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >医保支付:". ($param['payment']['insur_fee']/100) ."元</tr>";
        $fee_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 20) . "' >卡余额支:". ($param['payment']['balancepay_fee']/100) ."元</tr>";
		//支付信息部分,包括退款信息
		$pay_s1 = '';
		$pay_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 15) . "' >----------------------------------</tr>";
		$pay_s1 .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >机器编号:". $param['in']['zzjCode'] ."</tr>";
		if($param['payment']['personpay_fee'] > 0)
		{
			$paytype = array('1' => '支付宝', '2' => '微信', '3' => '银行卡', '4' => '卡余额');
			$pay_s1 .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >支付方式:". $paytype[$param['payment']['pay_type']] ."</tr>";
			$pay_s1 .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >交易流水号:". $param['payment']['trade_no'] ."</tr>";
		}
		$pay_s1 .= "<tr font='黑体' type='text' size='10' x='15' y='" . ($pos += 15) . "' >----------------------------------</tr>";
        $notice_s1 .= "<tr font='黑体' type='text' size='10' x='5' y='" . ($pos += 20) . "' >如需发票，请到窗口处理</tr>";
        $notice_s1 .= "<tr font='黑体' type='text' size='12' x='5' y='" . ($pos += 25) . "' >温馨提示：请保管好此凭证，</tr>";
        $notice_s1 .= "<tr font='黑体' type='text' size='12' x='5' y='" . ($pos += 25) . "' >做为退费凭证。</tr>";
        $notice_s1 .= "<tr font='黑体' type='text' size='12' x='5' y='" . ($pos += 10) . "' >.</tr>";
        //尾巴
        $tail = '</print_info>';
		$s1 = $head_s1
			  .$hos_s1
			  .$patient_s1
			  .$business_s1
			  .$fee_s1
			  .$pay_s1
			  .$notice_s1
			  .$tail;
		return $s1;
	}
}