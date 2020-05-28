<?php
/*
 * @desc 通用方法控制器
 * @author wangxinghua
 * @final 2019-12-31
 */
namespace MenZhen\Controller;
use Think\Controller;
class CommonController extends BaseController {
	public function gitupdate()
	{
		header('Content-type:text/html;charset=utf-8');
		echo "正在更新...<br>";
		exec('"C:\Program Files\Git\bin\sh.exe" D:\PHPWAMP_IN3\wwwroot\SSLogic\gitscript2.sh "D:/PHPWAMP_IN3/wwwroot/SSLogic"',$output);
		foreach($output as $o)
		{
			echo $o.'<br>';	
			ob_flush();  
    		flush();  
		}
		echo "更新完成<br>";
	}
	/*
	 * @desc 模块列表
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function moduleList()
	{
		$zzjCode = I('post.zzjCode');
		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	    );
	    $this->validate($rules);

	    $business = D('Common','Logic')->moduleList($zzjCode);
	    foreach ($business as $k => $b) 
	    {
	    	foreach ($b as $kk => $bb) 
	    	{
	    		if($bb['isEnabled'] == 0 && $bb['disabledType'] == 1)
	    			unset($business[$k][$kk]);
	    		$business[$k] = array_values($business[$k]);
	    	}
	    }
		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 格式化时间
	 * @author wangxinghua
	 * @final 2019-12-23
	 */
	public function datetimeStr()
	{
		$time = date('Y年m月d日',time());
		$time_1 = "星期".mb_substr( "日一二三四五六",date("w"),1,"utf-8" );
		$time_2 = date('H:i',time());
		$business['date'] = $time;
		$business['week'] = $time_1;
		$business['time'] = $time_2;

		$result = array(
	 		'code' => '000',
	 		'message' => '成功',
	 		'business' => $business
	 	);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 打印状态
	 * @author wangxinghua
	 * @final 2019-12-23
	 */
	public function printStatus()
	{
	 	$zzjCode = I('post.zzjCode');
	 	$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	    );
	    $this->validate($rules);

	    $business = D('Common','Logic')->printStatus($zzjCode);

	    if($business)
	    	$code = '000';
	    else
	    	$code = '201';
	 	$result = array(
	 		'code' => $code,
	 		'message' => '成功',
	 		'business' => $business
	 	);
	 	$this->ajaxReturnS($result,"JSON");
	 }
	 /*
	 * @desc 硬件故障日志
	 * @author wangxinghua
	 * @final 2019-12-23
	 */
	public function hardwareFailureLog()
	{
	    $rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('hardCode', 1, '', '' ,''),
	        array('hardType', 1, '', '' ,''),
	        array('hardBrand', 1, '', '' ,''),
	        array('hardTime', 1, '','/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',''),
	        array('hardError', 1, '', '','')
	    );
	    $logData = $this->validate($rules);
	    
	    $logData = tf2x($logData);
	 	$logData['is_solve'] = I('post.isSolve');
	 	$logData['solve_time'] = I('post.solveTime');
	 	$logData['solve_man'] = I('post.solveMan');
	 	$business = D('Common','Logic')->frentendHflog($logData);

	 	if($business)
	 	{
		 	$result = array(
		 		'code' => '000',
		 		'message' => '成功',
		 		'business' => array(
			 	)
		 	);
		}
	 	else
	 	{
		 	$result = array(
		 		'code' => '201',
		 		'message' => '失败',
		 		'business' => array(
			 	)
		 	);
	 	}
	 	$this->ajaxReturnS($result,"JSON");
	 }
	/*
	 * @desc 记录用户操作日志
	 * @author wangxinghua
	 * @final 2019-12-23
	 */
	public function operationLog()
	{
	 	$zzjCode = I('post.zzjCode');
	 	$opName = I('post.opName');
	 	$opContent = I('post.opContent');

	    $rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('opName', 1, '', '',''),
	        array('opContent', 1, '','',''),
	    );
	    $this->validate($rules);

	 	$logData = array();
	 	$logData['zzj_code'] = $zzjCode;
	 	$logData['op_name'] = $opName;
	 	$logData['op_content'] = $opContent;
		$business = D('Common','Logic')->frentendOplog($logData);

	 	if($business)
	 	{
		 	$result = array(
		 		'code' => '000',
		 		'message' => '成功',
		 		'business' => array(
			 	)
		 	);
		}
	 	else
	 	{
		 	$result = array(
		 		'code' => '201',
		 		'message' => '失败',
		 		'business' => array(
			 	)
		 	);
	 	}
	 	$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 解析卡信息
	 * @author wangxinghua
	 * @final 2020-01-20
	 */
	public function parseCardInfo()
	{
		//接收参数并验证参数
		$zzjCode = I('post.zzjCode');
		$cardType = I('post.cardType');
		$infoXml = I('post.infoXml');
		$businessType = I('post.businessType');
	    $rules = array(
	        array('zzjCode', 1, '', '' ,''),
	        array('cardType', 1, '', '/^(1|2|3)$/',''),
	        array('infoXml', 1, '','',''),
	    );
	    $this->validate($rules);
	    //调用ReadCard类解析infoXml
	    Vendor("ReadCard");
	    $RC_Result = \ReadCard::parse($cardType,$infoXml);
	   	if($RC_Result['error'] == "")
	    {
	    	if($businessType == 'zzjk')
	    	{
		    	//查询患者是否建档
		    	$sex_kv = array('男'=>1,'女'=>2,'未知'=>0);
				$request = array();
				$request['cardType'] = $cardType;
				$request['idNo'] = $RC_Result['idno'];
				$request['gender'] = $sex_kv[$RC_Result['sex']];
				$request['name'] = $RC_Result['name'];
				Vendor("His");
				$HIS = new \His();
				$response = $HIS->GetPatient($request);
				if($response['code'] == '0' && $response['data']['patientId'])
				{
					$this->err_result("您已经建过档，无需再操作",'111',$RC_Result);
				}
			}
		 	$result = array(
		 		'code' => '000',
		 		'message' => '成功',
		 		'business' => $RC_Result
		 	);
	    }
	    else
	    {
	    	$result = array(
		 		'code' => '201',
		 		'message' => $RC_Result['error'],
		 		'business' => array()
		 	);
	    }
	 	$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 获取选卡配置
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function getCardConf()
	{
		$conf = D('Config','Logic')->getConfValue(array(
			'enabled.sfz.manual.input',
			'enabled.szf.card',
			'enabled.yb.card',
			'enabled.jyt.card',
			'enabled.jzk.card',
		));

		$business = array();
		$business['sfzManual'] = $conf[0];
		$business['enSfzCard'] = $conf[1];
		$business['enYbCard'] = $conf[2];
		$business['enJytCard'] = $conf[3];
		$business['enJzkCard'] = $conf[4];

		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 获取支付配置
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function getPayConf()
	{
		$rules = array(
	        array('businessType', 1, '', '' ,''),
	    );
	    $in = $this->validate($rules);
		$conf = D('Config','Logic')->getConfValue(array(
			'enabled.wxpay',
			'enabled.alipay',
			'enabled.card.balance.pay',
			'pay.method',
			'enabled.bankpay'
		));
		$business = array();
		$business['enWxpay'] = $conf[0];
		$business['enAlipay'] = $conf[1];
		$business['enCardpay'] = $conf[2];
		$business['enBankpay'] = $conf[4];
		$business['payMethod'] = $conf[3];

		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}

	/*
	 * @desc 获取初始化配置
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function getInitConf()
	{
		$conf = D('Config','Logic')->getConfValue(array(
			'hos.name',
			'hos.code',
			'hos.logo',
			'hos.addr',
			'heartbeat.interval',
			'appoint.day.num'
		));

		//$ws =  web_server_url().__ROOT__.'/public/';
		$business = array();
		$business['hosName'] = $conf[0];
		$business['hosCode'] = $conf[1];
		$business['hosLogo'] = $ws.$conf[2];
		$business['hosAddr'] = $conf[3];
		$business['heartbeatInterval'] = $conf[4];
		$business['appointDesc'] = '可预约明日起'.$conf[5].'日号源(含明日)';

		// $banner = D('Common','Logic')->getBanner();
		// foreach ($banner  as $key => $value)
		// {
		// 	 $business['hosBanner'][$key] = $ws.$value['url'];
		// }
		$banner_list=M('banner')->field('url ,status')->select();
		foreach ($banner_list as $key => $value) {
			$banner_list[$key]['img_url'] = web_server_url().__ROOT__.'/public/'.$value['img_url'];
		}
		$business['hosBanner'] = $banner_list;

		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
}