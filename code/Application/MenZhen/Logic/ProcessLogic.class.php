<?php
/**
 * 自助业务库操作逻辑处理类
 */
namespace MenZhen\Logic;
class ProcessLogic{
	/**
	 * 查询模块index->name
	 */
	public function getModuleName($index = '')
	{
		if($index) 
		{
			$res = M('Module')->where("`index`='$index'")->getField('name');
			return $res;
		}
		$res = M('Module')->field('index,name')->select();
		$arr = array();
		foreach ($res as $k => $v) {
			$arr[$k] = $v;
		}
		unset($res);
		return $arr;
	}
	/**
	 * 保存一条患者信息
	 */
	public function savePatient($param)
	{
		$map = array('patient_id' => $param['patient_id']);
		if($this->getPatient($map))
			$res = M('Patient')->where($map)->save($param);
		else
			$res = M('Patient')->add($param);
		return $res;
	}
	/**
	 * 查询患者名称
	 */
	public function getPatientNameById($patientId)		
	{
		$res = M('Patient')->where("patient_id='$patientId'")->getField('name');
		return $res;
	}
	/**
	 * 查询患者信息
	 */
	public function getPatient($param, $field = '')		
	{
		if($field)
			$res = M('Patient')->field($field)->where($param)->find();
		else
			$res = M('Patient')->where($param)->find();
		return $res;
	}
	/**
	 * 保存一条支付信息
	 */
	public function savePayment($param)
	{
		if(isset($param['payment_id']))
		{
			$map = array('id' => $param['payment_id']);
			$res = M('Payment')->where($map)->save($param);
		}
		$one = array();
		if(isset($param['trade_no']))
		{
			$map = array('trade_no' => $param['trade_no']);
			$res = M('Payment')->where($map)->getField('id');
		}
		if($one)
			$res = M('Payment')->where($map)->save($param);
		else
			$res = M('Payment')->add($param);
		return $res;
	}
	/**
	 * 查询支付信息
	 */
	public function getPayment($param, $field = '')		
	{
		if($field)
			$res = M('Payment')->field($field)->where($param)->find();
		else
			$res = M('Payment')->where($param)->find();
		return $res;
	}
	/**
	 * 保存一条建档信息
	 */
	public function saveCreate($param)	
	{
		$map = array('id' => $param['id']);
		if($this->getPatient($map))
			$res = M('Create')->where($map)->save($param);
		else
			$res = M('Create')->add($param);
		return $res;
	}
	/**
	 * 保存一条缴费信息
	 */
	public function saveFee($param)		
	{
		$map = array('id' => $param['id']);
		if($this->getPatient($map))
			$res = M('Fee')->where($map)->save($param);
		else
			$res = M('Fee')->add($param);
		return $res;
	}
	/**
	 * 保存一条挂号信息
	 */
	public function saveRegister($param)		
	{
		$map = array('id' => $param['id']);
		if($this->getPatient($map))
			$res = M('Register')->where($map)->save($param);
		else
			$res = M('Register')->add($param);
		return $res;
	}

	/**
	 * 保存一条预约挂号信息
	 */
	public function saveAppoint($param)		
	{
		$map = array('id' => $param['id']);
		if($this->getPatient($map))
			$res = M('Appoint')->where($map)->save($param);
		else
			$res = M('Appoint')->add($param);
		return $res;
	}
	/**
	 * 保存一条取预约号信息
	 */
	public function saveAppointAcquire($param)
	{
		$map = array('id' => $param['id']);
		if($this->getPatient($map))
			$res = M('Appoint_acquire')->where($map)->save($param);
		else
			$res = M('Appoint_acquire')->add($param);
		return $res;
	}
	/**
	 * 保存一条凭条日志
	 */
	public function savePaint($param)		
	{
		$map = array('id' => $param['id']);
		if($this->getPatient($map))
			$res = M('Paint_log')->where($map)->save($param);
		else
			$res = M('Paint_log')->add($param);
		return $res;
	}
	/**
	 * 查询凭条信息
	 */
	public function getPaints($param, $field = 'id,num,create_time,business_type,paint_str,personpay_fee')
	{
		if($field)
			$res = M('Paint_log')->field($field)->where($param)->order('create_time desc')->select();
		else
			$res = M('Paint_log')->where($param)->order('create_time desc')->select();

		$mn = $this->getModuleName();
		foreach ($res as &$r) {
			$r['personpayFee'] = $r['personpay_fee']/100;
			unset($r['personpay_fee']);
			$r['createTime'] = $r['create_time'];
			unset($r['create_time']);
			$r['printStr'] = $r['paint_str'];
			unset($r['paint_str']);
			$r['businessTypeText'] = isset($mn[$r['business_type']]) ? $mn[$r['business_type']] : '未知';
			unset($r['business_type']);
		}
		return $res;
	}
	/**
	 * 查询单条凭条信息
	 */
	public function getPaint($param, $field = '')
	{
		if($field)
			$res = M('Paint_log')->field($field)->where($param)->find();
		else
			$res = M('Paint_log')->where($param)->find();
		return $res;
	}
	/**
	 * 设置补打凭条次数
	 */
	public function incPaintNum($id)
	{
		$res =  M('Paint_log')->where('id='.$id)->setInc('num');
		return $res;
	}
}