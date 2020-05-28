<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController
{
	/**
	 * @desc 支付设置
	 * @author wangxinghua
	 * @final 2020-03-04
	 */
	public function index()
	{
		if(!session('USER_NAME'))
			$this->error('您还没有登录',U('Home/Index/login'));

		$code = I('get.query_code');
		if($code)
		{
			$row = M('Hospital')->where(array('code' => $code))->find();
			if(empty($row))
				$this->error('此医院编号不存在，请重新输入');
			if($row['wxpay_config'])
			{
				$row['wxpay_config'] = json_encode(json_decode($row['wxpay_config'],true),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
			}
			if($row['alipay_config'])
			{
				$row['alipay_config'] = json_encode(json_decode($row['alipay_config'],true),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
			}
			if($row['unionpay_config'])
			{
				$row['unionpay_config'] = json_encode(json_decode($row['unionpay_config'],true),JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
			}
		}
		else
		{
			$code = M('Hospital')->max('code');
			$row['code'] = 'H'.sprintf('%04s',substr($code,1)+1);
			$row['password'] = getRandomString(6);
		}
		$this->assign('row',$row);
		$this->display();
	}
	/**
	 * @desc 支付设置保存
	 * @author wangxinghua
	 * @final 2020-03-04
	 */
	public function pay_config_save()
	{
		if(!session('USER_NAME'))
			$this->error('session失效，请重新登录');

		$keys = array(
			'id',
			'code',
			'name',
			'password',
			'wxpay_config',
			'alipay_config',
			'unionpay_config',
			'remark'
		);
		$params = array();
		foreach($keys as $k)
		{
			if($k != 'id' && $k != 'remark' && $k != 'unionpay_config' && empty(I('post.'.$k)))
			{
				$this->error('参数'.$k.'不能为空');
			}
			$params[$k] = I('post.'.$k);
		}
		if(!($params['wxpay_config'] = json_decode(str_replace('&quot;','"',$params['wxpay_config']))))
		{
			$this->error('微信支付配置不合法，请检查是否是合法json格式');
		}
		$params['wxpay_config'] = json_encode($params['wxpay_config']);
		if(!($params['alipay_config'] = json_decode(str_replace('&quot;','"',$params['alipay_config']))))
		{
			$this->error('支付宝配置不合法，请检查是否是合法json格式');
		}
		$params['alipay_config'] = json_encode($params['alipay_config']);
		/*
		if(!$params['unionpay_config'] = json_decode(str_replace('&quot;','"',$params['unionpay_config']))))
		{
			$this->error('银联支付配置不合法，请检查是否是合法json格式');
		}
		*/
		//$params['unionpay_config'] = json_encode($params['unionpay_config']);

		if($params['id'])
		{
			$res = M('Hospital')->save($params);
		}
		else
		{   
			unset($params['id']);
			$res = M('Hospital')->add($params);
		}

		if($res !== false)
			$this->success("恭喜你兄弟，操作成功",$res);
		else
			$this->error("操作失败，请重试");
	}
	/**
	 * @desc 登录
	 * @author wangxinghua
	 * @final 2020-03-04
	 */
	public function login()
	{
		$this->display();
	}
	/**
	 * @desc 登录提交
	 * @author wangxinghua
	 * @final 2020-03-04
	 */
	public function loginajax()
	{
		$user_name = 'rwzb';
		$user_password = 'rwzb#666';
		$keys = array(
			'name',
			'password',
		);
		$params = array();
		foreach($keys as $k)
		{
			if(empty(I('post.'.$k)))
			{
				$this->error('参数'.$k.'不能为空');
			}
			$params[$k] = I('post.'.$k);
		}
		if($params['name'] != $user_name && $params['password'] != $user_password)
		{
			$this->error('用户名或密码错误');
		}
		else
		{
			session('USER_NAME', $user_name);
			$this->success('登录成功，正在跳转...');
		}
	}
	/**
	 * @desc 登出 
	 * @author wangxinghua
	 * @final 2020-03-04
	 */
	public function loginout()
	{
		session('USER_NAME', null);
		$this->error('登出成功',U('Home/Index/login'));
	}
}