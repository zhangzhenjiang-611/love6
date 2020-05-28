<?php
/*
 * @desc 凭条控制器
 * @author wangxinghua
 * @final 2019-12-31
 */
namespace MenZhen\Controller;
use Think\Controller;
class TicketapeController extends BaseController {
	/*
	 * @desc 补打次数增加
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function incNum()
	{
		$zzjCode = I('post.zzjCode');
		$id = I('post.id');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	       	array('id', 1, '','',''),
	    );
	    $this->validate($rules);

	    $row = D('Process','Logic')->getPaint(array('id' => $id),'num');

	    $lnum = D('Config','Logic')->getConfValue('ticketape.limit.num');
		
		if($row['num'] >= $lnum)
		{
			$this->err_result("补打次数已达上限{$lnum}次，不能补打");
		}
		$res = D('Process','Logic')->incPaintNum($id);
		if($res)
		{
			$result = array(
				"code" => "000",
				"message" => "成功",
				"business" => array('num' => $row['num']+1)
			);
		}
		else
		{
			$result = array(
				"code" => "201",
				"message" => "失败",
				"business" => array()
			);
		}
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 待补打列表
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function lists()
	{
		$zzjCode = I('post.zzjCode');
		$patientId = I('post.patientId');
		$businessType = I('post.businessType');

		$rules = array(
	        array('zzjCode', 1, '', '' ,''),
	       	array('patientId', 1, '','',''),
	    );
	    $this->validate($rules);

	    //获取是否允许缴费项目勾选
		$config = D('Config','Logic')->getConfValue(array('ticketape.limit.num','ticketape.limit.time'));

	    $business['limitNum'] = $config['ticketape.limit.num'];
	    $business['limitTime'] = $config['ticketape.limit.time'];

	    $param = array(
	    	'patient_id' => $patientId
	    );

	    if($businessType)
	    	$param['businessType'] = $businessType;

	    if($business['limitNum'] != "")
	    {
	    	$param['num'] = array('lt',$business['limitNum']);
	    }
	   	if($business['limitTime'] != "")
	    {
	    	$param['create_time'] = array('gt',date('Y-m-d H:i:s',(time()-$business['limitTime']*86400)));
	    }
	    $business['taList'] = D('Process','Logic')->getPaints($param);

		$result = array(
			"code" => "000",
			"message" => "成功",
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
}