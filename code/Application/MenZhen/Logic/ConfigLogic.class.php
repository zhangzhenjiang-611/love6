<?php
/**
 * 配置库操作逻辑处理类
 */
namespace MenZhen\Logic;
class ConfigLogic{
	public function getConfValue($conf_key)		
	{
		$conf_value = array();
		if(is_array($conf_key))		
		{
			$rows = M('Config')->where(array('conf_key'=>array('in',$conf_key)))->getField('conf_key,conf_value');
			foreach ($conf_key as $v) 
			{
				$conf_value[$v] = $rows[$v];
				$conf_value[] = $rows[$v];
			}
		}
		else
		{
			$conf_value = M('Config')->where("conf_key='$conf_key'")->getField('conf_value');
		}
		return $conf_value;
	}
}