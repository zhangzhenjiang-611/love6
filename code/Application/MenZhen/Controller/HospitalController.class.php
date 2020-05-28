<?php
/*
 * @desc 医院相关控制器
 * @author wangxinghua
 * @final 2019-12-31
 */
namespace MenZhen\Controller;
use Think\Controller;
class HospitalController extends BaseController {
	/*
	 * @desc 患者信息
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function patientInfo()
	{
		//接收参数并验证参数
		$zzjCode = I('post.zzjCode');
		$cardType = I('post.cardType');
		$infoXml = I('post.infoXml');
		
	    $rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('cardType', 1, '', '/^(1|2|3|4)$/',''),
	        array('infoXml', 1, '','',''),
	    );
	    $this->validate($rules);

	    //调用ReadCard类解析infoXml
	    Vendor("ReadCard");
	    $RC_Result = \ReadCard::parse($cardType,$infoXml);
	    if($RC_Result['error'] != "")
	    {
	    	$this->err_result($RC_Result['error']);
	    }
		//调HIS获取患者信息接口
		Vendor("His");
		$HIS = new \His();
		$hCardType = array('1'=>'2','2'=>'3','3'=>'1');
		$genderkv = array('未知' => 0, '男' => 1, '女' => 2);
		$request = array(
			'cardType' => $cardType,
			'cardNo' => $RC_Result['cardno'],
			'name' => $RC_Result['name'],
			'sex' => $genderkv[$RC_Result['sex']],
			'idNo' => $RC_Result['idno'],
		);
		$response = $HIS->GetPatient($request);

		//组织数据
		if($response['code'] == '0')
		{
			$JytBalance = 0;
			$YbBalance = 0;
			$gender = array('0'=>'未知','1'=>'男','2'=>'女');
			switch ($hCardType[$cardType]) {
				case '3':
					$cardtypeText = '京医通卡';
					$JytBalance = $RC_Result['balance'];
				break;
				case '2':
					$cardtypeText = '医保卡';
					$YbBalance = $RC_Result['balance'];
				break;
				case '1':
					$cardtypeText = '身份证';
				default:
					break;
			}
			//患者信息入库
			$Process = D('Process','Logic');
			$one = $Process->getPatient(array('patient_id' => $response['data']['patientId']),'id');
			if(empty($one))
			{
				$param = array(
					'patient_id' => $response['data']['patientId'],
					'name' => $response['data']['name'],
					'gender' => $response['data']['gender'],
					'birthday' => $response['data']['birthday'],
					'mobile'=> $response['data']['mobile'],
					'id_no' => $RC_Result['idno'],
					'card_no' => $RC_Result['cardno'],
					'card_type'=> $cardType
				);
				$Process->savePatient($param);
			}
			//组织返回数据
			$birthday = $response['data']['birthday'] ? $response['data']['birthday'] : $RC_Result['birthday'];
			$business = array(
				'patientId' => $response['data']['patientId'],
				'name' => $response['data']['name'],
				'sex' => $gender[$response['data']['gender']],
				'birthday' => $birthday,
				'age' => ceil((time()-strtotime($birthday))/31536000),
				'phone'=> $response['data']['mobile'],
				'idno' => $RC_Result['idno'],
				'cardno' => $RC_Result['cardno'],
				'cardtypeText'=> $cardtypeText,
				'YbBalance' => $YbBalance,
				'JytBalance' => $JytBalance,
			);

			$result = array(
				"code" => '000',
				"message" => '成功',
				"business" => $business
			);
		}
		else
		{
			$result = array(
				"code" => '201',
				"message" => $response['msg'],
				"business" => array()
			);
		}
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 科室信息(包括一级和二级)
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function departmentInfo()
	{
		$zzjCode = I('post.zzjCode');
		$regFlag = I('post.regFlag');

	    $rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('regFlag', 1, '', '/^(1|2)$/',''),
	    );
	    $this->validate($rules);

	    $startDate = date('Ymd');

		if($regFlag == 1)//挂号
		{
			$endDate = $startDate;
		}
		else if($regFlag == 1)//预约
		{
			$endDate = date('Ymd',strtotime("+ ".date('t')."day"));
		}
		//调HIS获取出诊科室列表
		Vendor("His");
		$HIS = new \His();
		$request = array(
			'startDate' => $startDate,
			'endDate' => $endDate,
			'regFlag' => $regFlag
		);
		$business = $HIS->GetVisitDepartments($request);

		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}

	/*
	 * @desc 医生排班信息
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function doctorSchedulingInfo()
	{
		$zzjCode = I('post.zzjCode');
		$deptCode = I('post.deptCode');
		$regFlag = I('post.regFlag');
		$regDate = I('post.regDate');

	    $rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('deptCode', 1, '', '' ,''),
	        array('regFlag', 1, '', '/^(1|2)$/','')
	    );
	    $this->validate($rules);
	    if($regFlag == 2) 
	    {
	    	if($regDate == "")
	    		$this->err_result("参数regDate不能为空");
	    	else if(!preg_match("/^\d{4}-\d{2}-\d{2}$/", $regDate))
	    		$this->err_result("参数regDate不合法");
	    }


		if($regFlag == 1)//挂号
		{
	    	$startDate = date('Y-m-d');
		}
		else if($regFlag == 2)//预约
		{
	    	$startDate = $regDate;
		}
		$endDate = $startDate;
		//调HIS获取号源信息接口
		Vendor("His");
		$HIS = new \His();
		$request = array(
			'departCode' => $deptCode,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'regFlag' => $regFlag
		);
		$business = $HIS->GetTickets($request);

		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 获取建卡配置
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function getCreateConf()
	{
		$conf = D('Config','Logic')->getConfValue(array(
			'enabled.sfz.manual.input',
			'enabled.create',
			'create.patient.fee',
			'create.card.type'
		));

		$business = array();
		$business['sfzManual'] = $conf[0];
		$business['isCreate'] = $conf[1];
		$business['createFee'] = $conf[2]/100;
		$business['createCardType'] = $conf[3];

		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 患者建档
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function createPatient()
	{
		//接收参数并验证参数
	    $rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('tradeNo', 1, '', '' ,''),
	        array('name', 1, '', '' ,''),
	        array('sex', 1, '', '/^(男|女|未知)$/',''),
	        array('birthday', 1, '', '/^\d{4}-\d{2}-\d{2}$/' ,''),
	        array('createCardType', 1, '', '/^(1|2)$/' ,''),
	        array('cardType', 1, '', '/^(1|3)$/' ,''),
	        array('idNo', 1, '', '' ,''),
	        array('insurCardNo', 0, '', '' ,''),
	        array('newCardNo', 0, '', '' ,''),
	        array('phone', 1, '', '/^1(3|5|8)\d{9}$/' ,''),
	        array('address', 0, '', '' ,''),
	    );
	    $in = $this->validate($rules);

	    $in['type'] = I('post.type');
	    $in['isSpecial'] = I('post.isSpecial');
	    $in['isMarried'] = I('post.isMarried');
	    $in['job'] = I('post.job');

	    if(!isIdCard($in['idNo']))
	    {
	    	$this->err_result("身份证号不合法，请重新输入");
	    }
		Vendor("His");
		$HIS = new \His();
		$sex_kv = array('男'=>1,'女'=>2,'未知'=>0);
		//查询患者是否建档
		$request = array();
		$request['cardType'] = $in['cardType'];
		$request['idNo'] = $in['idNo'];
		$request['gender'] = $sex_kv[$in['sex']];
		$request['name'] = $in['name'];
		$response = $HIS->GetPatient($request);
		if($response['code'] == '0' && $response['data']['patientId'])
		{
			$this->err_result("您已经建过档，无需再操作",'111');
		}
		
		//调HIS获取患者建档接口
		$request = array(
			'name' => $in['name'],
			'gender' => $sex_kv[$in['sex']],
			'birthday' => $in['birthday'],
			'idCard' => $in['idNo'],
			'CardType' => 1,
			'bindType' => $in['cardType'] == 3 ? 0 : 1,
			'insurCardNo' => $in['insurCardNo'],
			'marriage' => $in['isMarried'],
			'address' => $in['address'],
			'mobile' => $in['phone']
		);
		$response = $HIS->CreatePatient($request);

		$allowRefund = D('Config','Logic')->getConfValue('enabled.dev.auto.refund');

		$Process = D('Process','Logic');
		if($response['code'] == '0')
		{
			//患者信息入库
			$one = $Process->getPatient(array('patient_id' => $response['data']['patientId']),'id');
			if(empty($one))
			{
				$param = array(
					'patient_id' => $response['data']['patientId'],
					'name' => $response['data']['name'],
					'gender' => $response['data']['gender'],
					'birthday' => $response['data']['birthday'],
					'mobile'=> $response['data']['mobile'],
					'id_no' => $response['data']['idNo'],
					'card_no' => $response['data']['idNo'],
					'card_type'=> $in['cardType']
				);
				$Process->savePatient($param);
			}
		}

		if($in['tradeNo'])
		{
			//查询支付信息
			$payment = $Process->getPayment(array('trade_no' => $in['tradeNo']));
			//更新支付信息
			if($payment)
			{
				$Process->savePayment(array(
					'payment_id' => $payment['id'],
					'patient_id' => $response['data']['patientId'],
					'patient_name' => $in['name'],
					'card_type'=> $in['cardType'],
					'card_no' => $in['idNo']
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
				'personpay_fee' => 0
			);
		}
		if($response['code'] == '0')
		{
			$code = '000';
			$state = 1;
			$business = array(
				'patientId' => $response['data']['patientId'],
			);
		}
		else
		{
			$code = '201';
			$state = 0;
			//自助机自动退款
			$refund_result = array('code' => '100');
			if($allowRefund && $payment['pay_type'] != "")
			{
				$Pay = A("MenZhen/Pay");
				$refund_result = $Pay->refund(array(
					'zzjCode' => $in['zzjCode'],
					'tradeNo' => $in['tradeNo'],
					'payType' => $payment['pay_type'],
					'totalFee' => $payment['total_fee'],
					'refundFee' => $payment['personpay_fee'],
					'businessType' => $payment['business_type'],
				));
			}
			$business = array();
		}
		
		//组织凭条打印内容
		Vendor("Paint");
		$printStr = \Paint::printStr(array(
			'business_type' => 'zzjk',
			'code' => $code,
			'payment' => $payment,
			'in' => $in,
			'out' => $response['data'],
			'allowRefund' => $allowRefund,
    		'refund_result' => $refund_result
		));
		$business['printStr'] = $printStr;
		//凭条表
		$Process->savePaint(array(
			'zzj_code' => $in['zzjCode'],
			'business_type' => 'zzjk',
			'patient_id' => $response['data']['patientId'],
			'paint_str' => $printStr
		));
		//建档表
		$Process->saveCreate(array(
			'zzj_code' => $in['zzjCode'],
			'patient_id' => $response['data']['patientId'],
			'payment_id' => $payment['id'],
			'card_type' => $in['cardType'],
			'name' => $in['name'],
			'id_no' => $in['idNo'],
			'gender' => $in['sex'] ? $in['sex'] : '0',
			'is_married' => $in['isMarried'],
			'is_special' => $in['isSpecial'],
			'birthday' => $in['birthday'],
			'address' => $in['address'],
			'phone' => $in['phone'],
  			'type' => $in['type'],
  			'new_card_no' => $in['type'],
  			'job' => $in['job'],
 			'new_card_no' => $in['newCardNo'],
			'total_fee' => $payment['total_fee'],
			'state' => $state,
		));
		$result = array(
			"code" => $code,
			"message" => $response['msg'],
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
}