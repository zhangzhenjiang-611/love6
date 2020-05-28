<?php
return array(
	//'配置项'=>'配置值'
	'URL_CASE_INSENSITIVE'  => false,//大小写不敏感
	'DEFAULT_MODULE' => 'Home',
    'SHOW_PAGE_TRACE' => true, //显示调试信息
    'LOG_RECORD' => false,
	'LOG_TYPE'=>  'File', 
	'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR,DEBUG',
	'URL_MODEL' => 1, // 如果你的环境不支持PATHINFO 请设置为3
    'url_convert'=> false,//url转换   关闭后支持驼峰写法

    //数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址 
	'DB_NAME'   => 'rwzb-pay', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'rp_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  TRUE, // 调试模式

	'URL_HTML_SUFFIX' =>'' ,
	'HTML_TITLE' => '融威支付'
);