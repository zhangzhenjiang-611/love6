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

class NativeNotifyCallBack extends WxPayNotify
{
	public function unifiedorder($openId, $product_id)
	{
		//统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody("test");
		$input->SetAttach("test");
		$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
		$input->SetTrade_type("NATIVE");
		$input->SetOpenid($openId);
		$input->SetProduct_id($product_id);
		$result = WxPayApi::unifiedOrder($input);
		Log::DEBUG("unifiedorder:" . json_encode($result));
		return $result;
	}
	
	public function NotifyProcess($data, &$msg)
	{
		//echo "处理回调";
		Log::DEBUG("开始处理回调");
		$db = new ezSQL_mysql('root','801147Qaz!1~','alipay','211.159.153.49','gb2312');
		$db->query("set names utf8");
		Log::DEBUG("初始化数据库");
		//Log::DEBUG("call back:" . json_encode($data));
		//Log::DEBUG("call backs:" . var_export($data,true)); 
		if(!array_key_exists("openid", $data))
		{
			$msg = "回调数据异常";
			Log::DEBUG("回调数据异常");
			return false;
		}
		

		Log::DEBUG("call back@###:".json_encode($data));
		$this->SetData("appid", $result["appid"]);
		$this->SetData("mch_id", $result["mch_id"]);
		$this->SetData("nonce_str", WxPayApi::getNonceStr());
		$this->SetData("prepay_id", $result["prepay_id"]);
		$this->SetData("result_code", "SUCCESS");
		$this->SetData("err_code_des", "OK");
		
		$sql1 = "select out_trade_no  from think_alipay_record where out_trade_no='".$data['out_trade_no']."'";
	
		$h1 = $db->query($sql1);
		if(!$h1){
			$sql2 = "insert into think_alipay_record (`source`,`pay_type`,`out_trade_no`,`trade_no`,`subject`,`trade_status`,`total_amount`,`receipt_amount`,`notify_time`,`gmt_payment`) values ('hos001','weixin',$data['out_trade_no'],$data['transaction_id'],$data['attach'],$data['result_code'],$data['total_fee'],$data['cash_fee'],date('Y-m-d H:i:s'),date('Y-m-d H:i:s',strtotime($data['time_end'])))";
			$db->query($sql2);
		}
		Log::DEBUG("更新数据SQL:" . $sql2); 
		return true;
	}
}

Log::DEBUG("begin notify!");
$notify = new NativeNotifyCallBack();
$notify->Handle(true);
