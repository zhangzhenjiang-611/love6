<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
include_once("ez_sql_core.php");
include_once("ez_sql_mysql.php");
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("开始处理回调");
		$attach = $data['attach'];
		$attach_ary = explode("|", $attach);

		//Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		$db = new ezSQL_mysql('root','801147Qaz!1~','alipay','211.159.153.49','gb2312');
		$db->query("set names utf8");
		Log::DEBUG("初始化数据库");
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			Log::DEBUG("输入参数不正确");
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			Log::DEBUG("订单查询失败");
			return false;
		}
		$sql1 = "select out_trade_no from think_alipay_record where out_trade_no='".$data['out_trade_no']."'";
		Log::DEBUG("更新数据SQL:" . $sql1); 
		$h1 = $db->query($sql1);
		if(!$h1){
			$sql2 = "insert into think_alipay_record (`source`,`pay_type`,`out_trade_no`,`trade_no`,`subject`,`trade_status`,`total_amount`,`receipt_amount`,`notify_time`,`gmt_payment`,`attach`) values ('".$attach_ary[1]."','weixin','".$data['out_trade_no']."','".$data['transaction_id']."','".$attach_ary[0]."','".$data['result_code']."','".($data['total_fee']/100)."','".($data['cash_fee']/100)."','".date('Y-m-d H:i:s')."','".$data['time_end']."','".$attach."')";
			Log::DEBUG("更新数据SQL2:" . $sql2); 
			$db->query($sql2);
			Log::DEBUG("错误:" . mysql_error()); 
		}
		 
		return true;
	}
}
Log::DEBUG("返回报文：".$GLOBALS['HTTP_RAW_POST_DATA']);
Log::DEBUG("begin notifying");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
