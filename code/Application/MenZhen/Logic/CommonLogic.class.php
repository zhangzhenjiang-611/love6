<?php
/**
 * 通用操作逻辑处理类
 */
use Think\Model;
namespace MenZhen\Logic;
class CommonLogic{
	/**
	 * 获取模块数据
	 */
	public function moduleList($zzjCode)
	{
		$result = array();
		$dev_id = M('Device')->where("code='$zzjCode' AND state=1")->getField('id');
		if($dev_id)
		{
			$m_rows = M('Module')->field('id,name,index,service_type,description')->order('sort asc')->select();

			$dm_rows = M('Dev_mod')->where('dev_id='.$dev_id . ' or dev_id=0')->getField('mod_id,id,is_disabled,service_date,service_date,service_start_time,service_end_time,disabled_type,msg');

			$w_kv = array(
				1 => '一',
				2 => '二',
				3 => '三',
				4 => '四',
				5 => '五',
				6 => '六',
				7 => '日',
			);
			//时间处理
			$d = date('mdNHis');

			$md = date('Y-m-d');//月日
			$w = $d{4};//周
			$t = $d{5}.$d{6}.$d{7}.$d{8}.$d{9}.$d{10};

			$is_holiday = M('Holiday')->where("start_date <='$md' and end_date>= '$md'")->getField('id');
			//组织
			foreach($m_rows as $k => $m)
			{
				$servicekv = array('1' => 'menzhen', '2' => 'zhuyuan', '3' => 'other');
				$result[$servicekv[$m['service_type']]][$k] = array(
					'index' => $m['index'],
					'name' => $m['name'],
					'isEnabled' => 1,//默认启用
					'disabledType' => 1,//默认隐藏
					'msg' => ''
				);
				if(isset($dm_rows[$m['id']]))
				{
					$d = $dm_rows[$m['id']];
					$disabledType = $d['disabled_type'];
					$msg = $d['msg'] ? $d['msg']: '';

					if($d['is_disabled'] == 0)
					{
						$isEnabled = 1;

						$service_date = decbin($d['service_date']);
						$service_date = sprintf("%08s", $service_date);

						//一周
						if($service_date{$w-1} == 1)
						{
							if(!($t >= $d['service_start_time'] && $t <= $d['service_end_time']))
							{
								$isEnabled = 0;
								$disabledType = 2;
								$msg = "本机器服务时间是:".implode('-',str_split($d['service_start_time'],2))."到".implode('-',str_split($d['service_end_time'],2));
							}
						}
						else
						{
							$isEnabled = 0;
							$disabledType = 2;
							$msg = "本机器在周{$w_kv[$w]}不提供服务";
						}
						//节假日
						if($service_date{7} == 0 && $is_holiday)
						{
							$isEnabled = 0;
							$disabledType = 2;
							$msg = "本机器在节假日不提供服务";
						}
					}
					else
						$isEnabled = 0;

					$result[$servicekv[$m['service_type']]][$k]['isEnabled'] = $isEnabled;
					$result[$servicekv[$m['service_type']]][$k]['disabledType'] = $disabledType;
					$result[$servicekv[$m['service_type']]][$k]['msg'] = $msg;
				}
			}		
		}
		$result['imgurl'] = web_server_url().__ROOT__.'/public/img/business_module/frontend/';
		return $result;
	}
	/**
	 * 获取打印机状态数据
	 */
	public function printStatus($zzjCode)
	{
		$result = array();
		$row = M('Printer_state')->where("zzj_code='$zzjCode'")->field('type,is_paper_jam,is_paper_end')->select();
		if($row)
		{
			$kv = array('1' => '216', '2' => 'k80');
			$result = array();
			foreach($row as $r)
			{
				$result[] = array(
					'type' => $kv[$r['type']],
					'isEnd' => $r['is_paper_end'],
					'isJam' => $r['is_paper_jam'],
				);
			}
		}
		return $result;
	}
	/**
	 * 记录故障日志
	 */
	public function frentendHflog($param)
	{
		$result = M('Hflog')->add($param);
		return $result;
	}
	/**
	 * 记录操作日志
	 */
	public function frentendOplog($param)
	{
		$result = 0;

		$ym = date('Ym');
		$pre = C('DB_PREFIX');
		$table = $pre.'frentend_oplog'.$ym;
		$model = new \Think\Model();
		//分表先创建表
		$model->execute("
CREATE TABLE IF NOT EXISTS `$table`(
`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`zzj_code` varchar(18) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '自助机编号',
`op_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作名称',
`op_content` mediumtext COLLATE utf8mb4_unicode_ci COMMENT '操作内容',
`op_time` datetime not null COMMENT '操作时间',
`create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
`modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
PRIMARY KEY (`id`),
KEY `i_op_zzj_code` (`zzj_code`),
KEY `i_op_name` (`op_name`),
KEY `i_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='自助机前端操作日志表按年月分表'");

		$result = M('Frentend_oplog'.$ym)->add($param);
		return $result;
	}
	/**
	 * 查询单条自助机信息
	 */
	public function getDev($param, $field = '')
	{
		if($field)
			$res = M('Device')->field($field)->where($param)->find();
		else
			$res = M('Device')->where($param)->find();
		return $res;
	}
	/**
	 * 保存一条自助机信息
	 */
	public function saveDev($param)
	{
		$map = array('mac' => $param['mac']);
		if($this->getDev($map))
			$res = M('Device')->where($map)->save($param);
		else
			$res = M('Device')->add($param);
		return $res;
	}
	/**
	 * 查询banner
	 */
	public function getBanner()
	{
		$res = M('Banner')->field('url')->where('status=1')->select();
		return $res;
	}
	/**
	 * 获取支付方式配置
	 */
	public function getPayConfig($param)
	{
		$res = M('Pay_mod')->join(array('__PAYMENT__MODULE__ ON __PAY_MOD__.pay_id=__PAYMENT__MODULE__.id',
			'__module__ ON __PAY_MOD__.mod_id=__MODULE__.id'))->field('__PAY_MOD__.name,__PAY_MOD__.state')->where($param)->select();
		return $res;
	}
	/**
	 * 新增|修改自助机 
	 */
	public function setDev($param)
	{
		$type = array('S' => 2, 'H' => 1);
		$data = array(
			'mac' => $param['mac'],
			'ip' => $param['ip'],
			'position_id' => 0,
			'type_id' => $type[$param['devType']],
			'state' => 1,
			'heartbeat_time' => date('Y-m-d H:i:s'),
		);
		if($param['mac'])
		{
			$Dev = M('Device');
			$row = $Dev->where(array('mac' => $param['mac']))->field('id')->find();
			if($row)
				$res = $Dev->where(array('mac' => $param['mac']))->save($param);
			else
				$res = $Dev->add($param);
			return $res;
		}
		else
			return true;
	}
	/**
	 * 获取下发指令
	 */
	public function getCommand($param)
	{
		$rows = M('Common_log')->join(array('__COMMAND__ ON __COMMAND_LOG__.command_id=__COMMAND__.id'))->where(array('dev_code' => $param['devCode'],'status' => 0))->field('command_name,seq,__COMMAND_LOG__.id,content')->select();
		return $rows;
	}
	/**
	 * 指令下发表
	 */
	public function setCommand($param)
	{
		foreach ($param as $k => $p) 
		{
			$param[$k]['status'] = 1;
		}
		$res = M('Common_log')->save($param);
		return $res;
	}
	/**
	 * 指令下发表
	 */
	public function setPrintState($param)
	{
		$data = array();
		$status = "";

		$status_kv = array(
			'102' => 'is_paper_near_end', //纸量充足状态:1纸量不足0纸量充足
  			'x' => 'is_ticket_out', //出纸口状态:1有纸0无纸
  			'100' => 'is_paper_jam'	, //卡纸状态:1卡纸0没卡纸,
  			'x' => 'is_cover_open', //胶辊状态:1关闭0没关闭,
  			'101' => 'is_paper_end',  //有无纸状态:1无纸0有纸,
  		);
		foreach ($param['hardList'] as $p) 
		{
			if($p == 'printer_k80')
			{
				$data['type'] = 2;
				$status = explode('|', $p['status']);
			}
			else if($p == 'printer_216')
			{
				$data['type'] = 1;
				$status = explode('|', $p['status']);
			}
			else
				continue;
			if($status)
			{
				foreach ($status as $s) 
				{
					if(isset($status[$s]))
						$data[$status[$s]] = 0;
					else
						$data[$status[$s]] = 1;
				}
			}
		}
		$res = M('Print_state')->where(array('dev_code' => $param['devCode']))->save($data);
		return $res;
	}
}