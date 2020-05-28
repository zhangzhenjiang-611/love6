<?php
/**
 * 支付业务库操作逻辑处理类
 */
namespace Home\Logic;
class ProcessLogic{

	/*----支付相关----*/
	/**
	 * 检查支付记录表是否存在，如果不存在则创建
	 */
	private function checkPayExist()
	{
		$ym = date('Ym');
		$pre = C('DB_PREFIX');
		$table = 'Pay_record'.$ym;
		$model = new \Think\Model();
		if(S($table) == 1)
			return $table;
		//分表先创建表
		$res = $model->execute("
			CREATE TABLE IF NOT EXISTS `{$pre}{$table}`(
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
			  `hos_code` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '医院编号',
			  `pay_type` enum('wxpay','alipay','unionpay') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付方式',
			  `trade_no` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内部订单号(商户)',
			  `out_trade_no` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '外部订单号(第三方)',
			  `subject` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付描述',
			  `body` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '详细描述',
			  `attach` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '附加信息',
			  `qr_code` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '二维码支付code',
			  `auth_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '条码支付授权码',
			  `buyer_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '买家账户ID',
			  `buyer_account` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '买家账户',
			  `trade_status` tinyint(1) NOT NULL COMMENT '支付状态:1-支付成功2-支付中3-支付取消4-支付异常0-支付失败',
			  `total_amount` int(8) NOT NULL COMMENT '订单总额单位分',
			  `receipt_amount` int(8) NOT NULL COMMENT '实际支付金额单位分',
			  `trade_type` enum('GH','YY','JK','QH','JF','ZC','QT') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '交易类型',
			  `terminal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '终端编号',
			  `notify_time` timestamp NULL DEFAULT NULL COMMENT '支付完成时间',
			  `notify_str` tinytext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '返回字符串',
			  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
			  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
			  PRIMARY KEY (`id`),
			  KEY `i_hos_code` (`hos_code`),
			  KEY `i_pay_type` (`pay_type`),
			  KEY `i_trade_no` (`trade_no`),
			  KEY `i_out_trade_no` (`out_trade_no`),
			  KEY `i_buyer_account` (`buyer_account`),
			  KEY `i_trade_status` (`trade_status`),
			  KEY `i_trade_type` (`trade_type`),
			  KEY `i_terminal_code` (`terminal_code`),
			  KEY `i_create_time` (`create_time`),
			  KEY `i_modified_time` (`modified_time`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付记录表';
		");
		if($res !== false)
		{
			S($table,1);
			return $table;
		}
		else
		{
			$this->error("创建表{$pre}{$table}失败");
		}
	}
	/**
	 * 检查上一个分表是否存在
	 */
	private function checkLastExist($table_middle, $fen)
	{
		$table = $table_middle.date($fen,strtotime('-1 month'));
		$model = new \Think\Model();
		$pre = C('DB_PREFIX');
		$res = $model->execute("SHOW TABLES LIKE '%{$pre}{$table}%'");
		if($res)
			return $table;
		else
			return false;
	}
	/**
	 * 保存一条支付记录日志
	 */
	public function savePay($params)
	{
		$table = $this->checkPayExist();
		if($params['id'])
			$res = M($table)->save($params);
		else
			$res = M($table)->add($params);
		//解决分表临界情况
		if(!$res)
		{
			$table = $this->checkLastExist('Pay_record', 'Ym');
			if($table)
			{
				if($params['id'])
					$res = M($table)->save($params);
				else
					$res = M($table)->add($params);
			}
		}
		return $res;
	}

	/**
	 * 获取支付记录(多条)
	 */
	public function getPayList($map, $fields = '')
	{
		$table = $this->checkPayExist();
		if($fields)
			$rows = M($table)->field($fields)->where($map)->select();
		else
			$rows = M($table)->where($map)->select();
		return $rows;
	}
	/**
	 * 获取支付记录(单条)
	 */
	public function getPayOne($map, $fields = '')
	{
		$table = $this->checkPayExist();
		if($fields)
			$rows = M($table)->field($fields)->where($map)->find();
		else
			$rows = M($table)->where($map)->find();
		//解决分表临界情况
		if(empty($rows))
		{
			$table = $this->checkLastExist('Pay_record', 'Ym');
			if($table)
			{
				if($fields)
					$rows = M($table)->field($fields)->where($map)->find();
				else
					$rows = M($table)->where($map)->find();
			}
		}
		return $rows;
	}

	/*----退款相关----*/
	/**
	 * 检查支付记录表是否存在，如果不存在则创建
	 */
	private function checkRefundExist()
	{
		$ym = date('Y');
		$pre = C('DB_PREFIX');
		$table = 'Refund_record'.$ym;
		$model = new \Think\Model();
		if(S($table) == 1)
			return $table;
		//分表先创建表
		$res = $model->execute("
			CREATE TABLE IF NOT EXISTS `{$pre}{$table}`(
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
			  `hos_code` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '医院编号',
			  `pay_type` enum('wxpay','alipay','unionpay') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付方式',
			  `trade_no` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内部订单号(商户)',
			  `out_trade_no` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '外部订单号(第三方)',
			  `refund_no` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '退款单号,一个订单号可以对应多个退款单号',
			  `buyer_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '买家账户ID',
			  `buyer_account` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '买家账户',
			  `subject` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '退款描述',
			  `refund_status` tinyint(1) NOT NULL COMMENT '退款状态:1-退款成功2-退款中3-退款取消4-退款异常0-退款失败',
			  `total_amount` int(8) NOT NULL COMMENT '订单总额单位分',
			  `refund_amount` int(8) NOT NULL COMMENT '退款金额单位分',
			  `trade_type` enum('GH','YY','JK','QH','JF','ZC','QT') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '交易类型',
			  `terminal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '终端编号',
			  `refund_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '退款时间',
			  `notify_str` tinytext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '通知字符串',
			  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
			  `modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
			  PRIMARY KEY (`id`),
			  KEY `i_hos_code` (`hos_code`),
			  KEY `i_pay_type` (`pay_type`),
			  KEY `i_trade_no` (`trade_no`),
			  KEY `i_out_trade_no` (`out_trade_no`),
			  KEY `i_reund_status` (`refund_status`),
			  KEY `i_trade_type` (`trade_type`),
			  KEY `i_terminal_code` (`terminal_code`),
			  KEY `i_create_time` (`create_time`),
			  KEY `i_modified_time` (`modified_time`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='退款记录表';
		");
		if($res !== false)
		{
			S($table,1);
			return $table;
		}
		else
		{
			return false;
		}
	}
	/**
	 * 保存一条退款记录日志
	 */
	public function saveRefund($params)
	{
		$table = $this->checkRefundExist();
		if($params['id'])
			$res = M($table)->save($params);
		else
			$res = M($table)->add($params);
		//解决分表临界情况
		if(!$res)
		{
			$table = $this->checkLastExist('Refund_record', 'Y');
			if($table)
			{
				if($params['id'])
					$res = M($table)->save($params);
				else
					$res = M($table)->add($params);
			}
		}
		return $res;
	}

	/**
	 * 获取退款记录(多条)
	 */
	public function getRefundList($map, $fields = '')
	{
		$table = $this->checkRefundExist();
		if($fields)
			$rows = M($table)->field($fields)->where($map)->select();
		else
			$rows = M($table)->where($map)->select();
		return $rows;
	}
	/**
	 * 获取退款记录(单条)
	 */
	public function getRefundOne($map, $fields = '')
	{
		$table = $this->checkRefundExist();
		if($fields)
			$rows = M($table)->field($fields)->where($map)->find();
		else
			$rows = M($table)->where($map)->find();
		//解决分表临界情况
		if(empty($rows))
		{
			$table = $this->checkLastExist('Refund_record', 'Y');
			if($table)
			{
				if($fields)
					$rows = M($table)->field($fields)->where($map)->find();
				else
					$rows = M($table)->where($map)->find();
			}
		}
		return $rows;
	}
	/**
	 * 错误
	 */
	private function error($msg)
	{
		echo header("content-type:application/json;charset=utf8");
		exit(json_encode(array(
			'code' => 500,
			'msg' => $msg
		)));
	}
}