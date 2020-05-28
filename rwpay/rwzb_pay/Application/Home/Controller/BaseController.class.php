<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller 
{
	/*
	 * @desc 记录请求日志
	 * @author wangxinghua
	 * @final 2019-12-23
	 */
	protected function ajaxReturnS($data,$dataType)
	{	
		\Think\Log::write("\r\n时间:".date("Y-m-d H:i:s")."\r\nIP:".$_SERVER['REMOTE_ADDR']."\r\n方法:".MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME."\r\n参数:".var_export($_POST,true)."\r\n返回:".var_export($data,true),'INFO');
		parent::ajaxReturn($data,$dataType);
	}
	/*
	 * @desc 记录异步通知请求专用方法
	 * @author wangxinghua
	 * @final 2019-12-23
	 */
	protected function notifyReturn($data)
	{	
		\Think\Log::write("\r\n时间:".date("Y-m-d H:i:s")."\r\nIP:".$_SERVER['REMOTE_ADDR']."\r\n方法:".MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME."\r\n参数:".($_POST ? var_export($_POST,true) : $GLOBALS['HTTP_RAW_POST_DATA'])."\r\n返回:".$data,'INFO');
		exit($data);
	}
	/*
	 * @desc 验证参数
	 * @param $rules array('参数名', '是否必填(1|0)', '正则表达式')
	 * @author wangxinghua
	 * @final 2019-12-23
	 */
	protected function validate($rules)
	{
		$error = '';
		$post = array();
		foreach ($rules as $r)
		{
			$IK = "post.{$r[0]}";
			$param = I($IK);

			$post[$r[0]] = $param;
			//是否必填
			if($r[1] == 1)
			{
				if($param == '')
				{
					$error = '参数'.$r[0].'不能为空';
					break;
				}
			}
			//是否符合正则
			if($r[2] != '' && !preg_match($r[2],$param))
			{
				$error = '参数'.$r[0].'不合法';	
				break;
			}
		}
		if($error)
		{
			$result = array(
			'code' => '201',
			'msg' => $error 
			);
			$this->ajaxReturn($result,'json');	
		}
		else
			return $post;
	}
}