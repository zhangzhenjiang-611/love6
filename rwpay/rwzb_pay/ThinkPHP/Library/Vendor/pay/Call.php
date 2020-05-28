<?php
require_once 'Wxpay.php';
require_once 'Alipay.php';
require_once 'Unionpay.php';
/**
 * @desc支付入库类，名字保留习惯,期望封装支付宝、微信、银联
 * @author wangxinghua
 * @final 2020-03-05
 */
class Call
{
	/**
	 * @desc 路由器
	 * @params 参数
	 * @author wangxinghua
	 * @final 2020-03-05
	 */
	public function execute($function, $params)
	{
		$class = ucfirst($params['pay_type']);
		try
		{
			$obj = new $class($params['PAY_CONFIG']);
			$result = $obj->$function($params);
		}
		catch(Exception $e)
		{
			echo header("content-type:application/json;charset=utf8");
			exit(json_encode(array(
				'code' => 500,
				'msg' => $e->getMessage()
			)));
		}
		//记录日志
		unset($params['PAY_CONFIG']);
	    $fileName = "Application/Runtime/Logs/{$params['pay_type']}_".date('Ymd') . '.log';
	    $log = '';
	    $log .= 'TIME:' . date('Y-m-d H:i:s') . "\n";
	    $log .= 'METHOD:' . $function . "\n";
	    $log .= 'PARAMS:' . json_encode($params, true) . "\n";
	    $log .= 'RETURN:' . json_encode($result, true) . "\n";
	    $log .= "\t\n";
	    file_put_contents($fileName, $log, FILE_APPEND);
    	return $result;
	}
}