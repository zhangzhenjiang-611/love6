<?php
/*
 * @desc 基础控制器,所有控制器继承它
 * @author wangxinghua
 * @final 2019-12-31
 */
namespace MenZhen\Controller;
use Think\Controller;
class BaseController extends Controller {
	/*
	 * @desc 初始化方法
	 * @author wangxinghua
	 * @final 2020-02-24
	 */
	public function _initialize()
    {
    	$config = C();
    	$key = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME.I('post.divStep');
    	$key = strtoupper($key);
    	if($config['IS_TEST'] && isset($config[$key]))
    	{
    		header('content-type:application/json;charset=utf-8');
    		exit($config[$key]);
    	}
    }
	/*
	 * @desc 记录请求日志
	 * @author wangxinghua
	 * @final 2019-12-23
	 */
	protected function ajaxReturnS($data,$dataType)
	{	
		\Think\Log::write("\r\n时间:".date("Y-m-d H:i:s")."\r\nIP:".$_SERVER['REMOTE_ADDR']."\r\n方法:".MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME."\r\n参数:".var_export(I('post.'),true)."\r\n".json_encode(I('post.'))."\r\n返回:".var_export($data,true)."\r\n".json_encode($data),'INFO');
		$this->ajaxReturn($data,$dataType);
	}
	/*
	 * @desc 返回错误
	 * @author wangxinghua
	 * @final 2019-12-23
	 */
	protected function err_result($message,$code = '101',$business = array())
	{
		$result = array(
			"code" => $code,
			"message" => $message,
			"business" => $business
		);
		$this->ajaxReturnS($result,"JSON");
	}
	/*
	 * @desc 验证参数
	 * @param $rules array('参数名', '是否必填(1|0)','提示','正则表达式', '提示')
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
				if($param === '')
				{
					$error = $r[2] ? $r[2] : '参数'.$r[0].'不能为空';
					break;
				}
			}
			//是否符合正则
			if($r[3] != '' && !preg_match($r[3],$param))
			{
				$error = $r[4] ? $r[4] : '参数'.$r[0].'不合法';	
				break;
			}
		}
		if($error)
			$this->err_result($error);
		else
			return $post;
	}
	/*
	 * @desc 空操作
	 * @author wangxinghua
	 * @final 2020-02-24
	 */
    public function _empty()
    {
    	$this->err_result("( ▼-▼ )你迷路了~","404");
    }
}