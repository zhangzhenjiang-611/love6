<?php
/*
 * @desc 监控控制器负责服务端与壳之间的交互
 * @author wangxinghua
 * @final 2020-04-21
 */
namespace MenZhen\Controller;
use Think\Controller;
class MonitorController extends BaseController {
	/*
	 * @desc 心跳包
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function heartBeat()
	{
		$rules = array(
	        array('devCode', 1),
	        array('devType', 1),
	        array('ip', 1),
	        array('mac', 1),
	        array('hardList', 1),
	    );
	    $in = $this->validate($rules);

	    $Common = D('Common', 'Logic');
	    //新增或修改自助机
		$dev_res = $Common->setDev($in);

		//打印机状态
		$print_res = $Common->setPrintState($in);

		//查询下发指令
		$command = $Common->getCommand($in);

		//组织返回结果
		$result = array();
		$result['devCode'] = $in['devCode'];
		$result['dateTime'] = date('Y-m-d H:i:s');
		foreach ($command as $c)
		{
			$result['commandList'][] = array(
				'id' => $c['id'],
				'name' => $c['command_name'],
				'seq' => $c['seq'],
				'desc' => $c['content']
			);
		}
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 获取配置
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function getConf()
	{
		$rules = array(
	        array('mac', 1),
	    );
	    $in = $this->validate($rules);

	    $one = D('Common', 'Logic')->getDev(array('mac' => $in['mac']), 'code');
	    if(empty($one))
	    {
	    	$this->err_result("找不到设备");
	    }

	    $common_ini = 'Public/config_file/config.ini';
	    $dev_ini = 'Public/config_file/'.$in['mac'].'.ini';

	    $result['content'] = $this->parse_str($dev_ini, $common_ini, $one['code']);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 修改配置
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function setConf()
	{
		$rules = array(
	        array('mac', 1),
	       	array('content', 1),
	    );
	    $in = $this->validate($rules);

	  	$in = $this->validate($rules);

	    $one = D('Common', 'Logic')->getDev(array('mac' => $in['mac']), 'code');
	    if(empty($one))
	    {
	    	$this->err_result("找不到设备");
	    }

	    $common_ini = 'Public/config_file/config.ini';
	    $dev_ini = 'Public/config_file/'.$in['mac'].'.ini';


	    $result['result'] = $this->parse_str($dev_ini, $common_ini, $one['code'], $in, 'set');

		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 解析配置文件
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	private function parse_str($dev_ini, $common_ini, $dev_code, $in = '', $action = 'get')
	{
		$commmon_con = file_get_contents($common_ini);

	    $dev_con = '';
	    if(file_exists($dev_ini))
	    {
	    	$dev_con = file_get_contents($dev_ini);
	    }

		if($action == 'get')
		{
		    //实际值替换变量
		    $commmon_con = str_replace('{{DEV_CODE}}', $dev_code, $commmon_con);

		    if($dev_con)
		    {
		    	$dev_arr = explode("\r\n", $dev_con);	
		    	$h = '';
		    	foreach ($dev_arr as $v)
		    	{
		    		if(substr($v, 0, 1) == '[')
		    		{
		    			$h = $v;
		    		}
		    		if(strpos($v, ';') === false && strpos($v, '=') !== false)
		    		{
		    			$t = explode('=', $v);	
		    			preg_match("/\[$h+\](\s|.)+\[+/iU", $commmon_con, $cm);
	    				preg_match("/({$t[0]}=)(.*)/i", $cm[0], $cmm);
	    				if(!empty($cmm[2]) && trim($cmm[2]) != trim($t[1]))
	    				{
		    				$nm = preg_replace("/({$t[0]}=)(.*)/i", '${1}'.$t[1]."\r", $cm[0]);
		    				$commmon_con = str_replace($cm[0], $nm, $commmon_con);
	    				}
		    		}
		    	}
		    }
		    return iconv('gbk', 'utf-8', $commmon_con);
		}
		else if($action == 'set')
		{
			$arr = explode("\r\n", $in['content']);	
			$diff = '';
			$result = 1;
 			//实际值替换变量
		    $commmon_con = str_replace('{{DEV_CODE}}', $dev_code, $commmon_con);

		    $Common = D('Common', 'Logic');
		    $h = '';
	    	foreach ($arr as $v)
	    	{
	    		if(empty($v)) continue;
	    		if(substr($v, 0, 1) == '[')
	    		{
	    			$h = $v;
	    		}
	    		if(strpos($v, ';') === false && strpos($v, '=') !== false)
	    		{
	    			$t = explode('=', $v);	
	    			preg_match("/\[$h+\](\s|.)+\[+/iU", $commmon_con, $cm);
	    			preg_match("/({$t[0]}=)(.*)/i", $cm[0], $cmm);

	    			if(!empty($cmm[2]) && trim($cmm[2]) != trim($t[1]))
	    			{
	    				$diff .= $h."\r\n".$t[0].'='.$t[1]."\r\n";
	    				if($t[0] == 'DeviceName')
	    				{
	    					$Common->saveDev(array('mac' => $in['mac'], 'code' => $t[1]));
	    				}
	    			}
	    		}
	    	}
	    	//有不同的配置项目
	    	if(!empty($diff))
	    	{
	    		$result = file_put_contents($dev_ini, $diff);
	    		$result = $result > 0 ? 1 : 0;
	    	}
	    	return $result;
		}
	}
	/*
	 * @desc 心跳包
	 * @author wangxinghua
	 * @final 2019-12-31
	 */
	public function commandReceive()
	{
		$rules = array(
	        array('commandId', 1),
	    );
	    $in = $this->validate($rules);

	    $Common = D('Common', 'Logic');
		$result = array();

		$commandId = explode('|', $in['commandId']);
		$res = $Common->setCommand($commandId);
		$result['result'] = $res > 0 ? 1 : 0;
		$this->ajaxReturnS($result,"JSON");
	}
}