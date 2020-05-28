<?php
namespace Home\Controller;
use Think\Controller;
class PayController extends BaseController
{
	public $Call;
	public $timeExpress = '5';
	/**
	 * @desc 构造函数
	 * @author wangxinghua
	 * @final 2020-03-04
	 */
	public function __construct()
	{
		vendor('pay.Call');
		$this->Call = new \Call();
	}
	/**
	 * @desc 获取配置
	 * @author wangxinghua
	 * @final 2020-03-04
	 */
	private function getPayConfig($hos_code, $password, $pay_type)
	{
		$map = array('hos_code' => $hos_code, 'password' => $password);
		if($password == '#NOTIFY#')//异步通知不需要传password
		{
			unset($map['password']);
		}
		$row = M('Hospital')->where($map)->field("{$pay_type}_config")->find();
		if($row)
		{
			$config = json_decode($row[$pay_type.'_config'], true);
			if(!$config)
			{
				$result = array(
				'code' => '102',
				'msg' => '医院支付配置信息有误,无法解析'
				);
				$this->ajaxReturnS($result,'json');	
			}
			$config['timeExpress'] = $this->timeExpress;
			return $config;
		}
		else
		{
			$result = array(
				'code' => '101',
				'msg' => '未找到医院支付配置记录或参数password错误'
			);
			$this->ajaxReturnS($result,'json');
		}
	}
	/**
	 * @desc 条码支付
	 * @author wangxinghua
	 * @final 2020-03-04
	 */
	public function barpay()
	{
		$validate = array(
			array('hos_code',1,''),
			array('password',1,''),
			array('pay_type',1,''),
			array('trade_type',1,''),
			array('auth_code',1,''),
			array('total_amount',1,''),
			array('receipt_amount',1,''),
			array('subject',1,''),
			array('body',0,''),
			array('terminal_code',1,''),
		);
		$params = $this->validate($validate);
		//获取支付配置
		$PAY_CONFIG = $this->getPayConfig($params['hos_code'], $params['password'], $params['pay_type']);
		//生成商户订单号
		$params['trade_no'] = man_trade_no($params['hos_code'], $params['pay_type']);

		$Process = D('Process','Logic');
		//插入表记录
		$params['trade_status'] = 2;
		$insert_id = $Process->savePay($params);
		//调条码支付接口
		$params['PAY_CONFIG'] = $PAY_CONFIG;
		$pay_res = $this->Call->execute('barpay', $params);
		//修改表记录
		$pay_res['data']['id'] = $insert_id;
		$Process->savePay($pay_res['data']);

		//返回数据
		$result['code'] = $pay_res['code'];
		$result['msg'] = $pay_res['msg'];
		$result['data'] = $Process->getPayOne(array('id' => $insert_id),
			'trade_no,out_trade_no,pay_type,trade_status','buyer_id',
			'buyer_account','create_time','notify_time');
		$this->ajaxReturnS($result,'json');
	}

	/**
	 * @desc 二维码支付
	 * @author wangxinghua
	 * @final 2020-03-08
	 */
	public function qrpay()
	{
		$validate = array(
			array('hos_code',1,''),
			array('password',1,''),
			array('pay_type',1,''),
			array('trade_type',1,''),
			array('total_amount',1,''),
			array('receipt_amount',1,''),
			array('subject',1,''),
			array('body',0,''),
			array('terminal_code',1,''),
		);
		$params = $this->validate($validate);
		//获取支付配置
		$PAY_CONFIG = $this->getPayConfig($params['hos_code'], $params['password'], $params['pay_type']);

		$params['trade_no'] = man_trade_no($params['hos_code'], $params['pay_type']);
		$Process = D('Process','Logic');
		//插入表记录
		$params['trade_status'] = 2;
		$insert_id = $Process->savePay($params);
		//调二维码支付接口
		$params['PAY_CONFIG'] = $PAY_CONFIG;
		$pay_res = $this->Call->execute('qrpay', $params);

		//修改表记录
		$pay_res['data']['id'] = $insert_id;
		$Process->savePay($pay_res['data']);

		//返回数据
		$result['code'] = $pay_res['code'];
		$result['msg'] = $pay_res['msg'];
		$result['data'] = $Process->getPayOne(array('id' => $insert_id),
			'trade_no,pay_type,qr_code','create_time');
		$this->ajaxReturnS($result,'json');
	}

	/**
	 * @desc 查询
	 * @author wangxinghua
	 * @final 2020-03-08
	 */
	public function query()
	{
		$validate = array(
			array('hos_code',1,''),
			array('password',1,''),
			array('pay_type',1,''),
			array('trade_no',0,''),
			array('out_trade_no',0,''),
			array('terminal_code',1,''),
		);
		$params = $this->validate($validate);
		if($params['out_trade_no'] == '' && $params['trade_no'] == '')
		{
			$result = array(
				'code' => '103',
				'msg' => 'trade_no和out_trade_no不能同时为空'
			);
			$this->ajaxReturnS($result,'json');
		}
		//获取支付配置
		$PAY_CONFIG = $this->getPayConfig($params['hos_code'], $params['password'], $params['pay_type']);

		//调查询接口
		$params['PAY_CONFIG'] = $PAY_CONFIG;
		$pay_res = $this->Call->execute('query', $params);

		//返回数据
		$result['code'] = $pay_res['code'];
		$result['msg'] = $pay_res['msg'];
		$result['data'] = $pay_res['data'];
		$this->ajaxReturnS($result,'json');
	}
	/**
	 * @desc 退款
	 * @author wangxinghua
	 * @final 2020-03-04
	 */
	public function refund()
	{
		$validate = array(
			array('hos_code',1,''),
			array('password',1,''),
			array('pay_type',1,''),
			array('trade_type',1,''),
			array('trade_no',0,''),
			array('out_trade_no',0,''),
			array('total_amount',1,''),
			array('refund_amount',1,''),
			array('subject',1,''),
			array('terminal_code',1,''),
		);
		$params = $this->validate($validate);
		
		if($params['out_trade_no'] == '' && $params['trade_no'] == '')
		{
			$result = array(
				'code' => '103',
				'msg' => 'trade_no和out_trade_no不能同时为空'
			);
			$this->ajaxReturnS($result,'json');
		}

		//获取支付配置
		$PAY_CONFIG = $this->getPayConfig($params['hos_code'], $params['password'], $params['pay_type']);
		$Process = D('Process','Logic');
		//插入表记录
		//生成退款单号
		$params['refund_no'] = man_trade_no($params['hos_code'], $params['pay_type'].'refund');
		$params['refund_status'] = 2;
		$insert_id = $Process->saveRefund($params);
		//调退款接口
		$params['PAY_CONFIG'] = $PAY_CONFIG;
		$pay_res = $this->Call->execute('refund', $params);

		//修改表记录
		$pay_res['data']['id'] = $insert_id;
		$Process->saveRefund($pay_res['data']);

		//返回数据
		$result['code'] = $pay_res['code'];
		$result['msg'] = $pay_res['msg'];
		$result['data'] = $Process->getRefundOne(array('id' => $insert_id),
			'trade_no,pay_type,refund_amount,refund_time');
		$this->ajaxReturnS($result,'json');
	}

	/**
	 * @desc 取消订单
	 * @author wangxinghua
	 * @final 2020-03-08
	 */
	public function cancel()
	{
		$validate = array(
			array('hos_code',1,''),
			array('password',1,''),
			array('pay_type',1,''),
			array('trade_no',0,''),
			array('out_trade_no',0,''),
			array('terminal_code',1,''),
		);
		$params = $this->validate($validate);
		if($params['out_trade_no'] == '' && $params['trade_no'] == '')
		{
			$result = array(
				'code' => '103',
				'msg' => 'trade_no和out_trade_no不能同时为空'
			);
			$this->ajaxReturnS($result,'json');
		}
		//获取支付配置
		$PAY_CONFIG = $this->getPayConfig($params['hos_code'], $params['password'], $params['pay_type']);

		//调取消接口
		$params['PAY_CONFIG'] = $PAY_CONFIG;
		$pay_res = $this->Call->execute('cancel', $params);

		//返回数据
		$result['code'] = $pay_res['code'];
		$result['msg'] = $pay_res['msg'];
		$result['data'] = $pay_res['data'];
		$this->ajaxReturnS($result,'json');
	}
	/**
	 * @desc 异步通知
	 * @author wangxinghua
	 * @final 2020-03-08
	 */
	public function notify()
	{
		$params = array();
		$params['pay_type'] = I('get.pay_type');
		switch ($params['pay_type']) {
			case 'wxpay':
				//获取通知的数据
				$xml = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
				if(empty($xml))
				{
		        	$this->notifyReturn('<xml>
					  <return_code><![CDATA[XML_FAIL]]></return_code>
					  <return_msg><![CDATA[XML_ERROR]]></return_msg>
					</xml>');
				}
				libxml_disable_entity_loader(true);
        		$obj = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        		$trade_no = $obj['out_trade_no'];

        		$Process = D('Process','Logic');
		        $one = $Process->getPayOne(array('trade_no'=>$trade_no), 'id,hos_code');
		        if(empty($one))
		        {
		        	$this->notifyReturn('<xml>
					  <return_code><![CDATA[TRADE_NOT_EXISTS]]></return_code>
					  <return_msg><![CDATA[ERROR]]></return_msg>
					</xml>');
		        }
        		$params['xml'] = $obj;

				$params['hos_code'] = $one['hos_code'];
				$params['password'] = '#NOTIFY#';
				//获取支付配置
				$PAY_CONFIG = $this->getPayConfig($params['hos_code'], $params['password'], $params['pay_type']);
				//调取异步通知接口
				$params['PAY_CONFIG'] = $PAY_CONFIG;
				$pay_res = $this->Call->execute('notify', $params);
				$pay_res['data']['notify_str'] = $xml;

				//修改表记录
				$pay_res['data']['id'] = $one['id'];	
				$res = $Process->savePay($pay_res['data']);

				if($pay_res['code'] == '000' && $res)
				{
					$this->notifyReturn('<xml>
					  <return_code><![CDATA[SUCCESS]]></return_code>
					  <return_msg><![CDATA[OK]]></return_msg>
					</xml>');
				}
				else
				{
					$this->notifyReturn('<xml>
					  <return_code><![CDATA[FAIL]]></return_code>
					  <return_msg><![CDATA[ERROR]]></return_msg>
					</xml>');
				}
				break;
			case 'alipay':
				if(empty(I('post.out_trade_no')))
				{
					$this->notifyReturn("fail_params");
				}
				$trade_no = I('post.out_trade_no');
				$Process = D('Process','Logic');
		        $one = $Process->getPayOne(array('trade_no'=>$trade_no), 'id,hos_code');
		        if(empty($one))
		        {
					$this->notifyReturn("fail_trade_not_exists");
		        }
		        $params['post'] = I('post.');

				$params['hos_code'] = $one['hos_code'];
				$params['password'] = '#NOTIFY#';
				//获取支付配置
				$PAY_CONFIG = $this->getPayConfig($params['hos_code'], $params['password'], $params['pay_type']);
				//调取异步通知接口
				$params['PAY_CONFIG'] = $PAY_CONFIG;
				$pay_res = $this->Call->execute('notify', $params);

				//修改表记录
				$pay_res['data']['id'] = $one['id'];	
				$res = $Process->savePay($pay_res['data']);

				if($pay_res['code'] == '000' && $res)
					$this->notifyReturn('success');
				else
					$this->notifyReturn('fail');
			default:
				$this->notifyReturn('pay_type not support');
				break;
		}
	}
}