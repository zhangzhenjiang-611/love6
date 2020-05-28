<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE' => 'Manage',
	'URL_CASE_INSENSITIVE'  => true,//大小写不敏感
	'DEFAULT_MODULE' => 'MenZhen',
    'SHOW_PAGE_TRACE' => true, //显示调试信息
    'LOG_RECORD' => false,
	'LOG_TYPE'=>  'File', 
	'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR,DEBUG',
	'URL_MODEL' => 1, // 如果你的环境不支持PATHINFO 请设置为3
    'url_convert'    =>  false,//url转换   关闭后支持驼峰写法

    //数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址 
	'DB_NAME'   => 'self-service', // 数据库名
	//'DB_USER'   => 'ssuser', // 用户名
	//'DB_PWD'    => 'sspwd1231#rwzb*.2020', // 密码
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'root', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'ss_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  TRUE, // 调试模式


	'URL_HTML_SUFFIX'=>'',
	//session初始化
    'SESSION_AUTO_START' => true,//系统不自动启动Session
    'SESSION_OPTIONS' => array(
        'use_trans_sid' => 1,
        'expire' => 3600,//设置过期时间session.gc_maxlifetime的值为1小时
        'domain' => $_SERVER['SERVER_NAME'],
        //'domain' => 'ssm-hoserp-web-test.rwjiankang.com',
        'use_cookies' => 1,
        'use_trans_sid' => 1,
        'type' => 'mysqli'
    ),

	/*---------以下自定义参数----------*/
	//HIS层接口需要的参数
	'APPID' => 'rwd930ea5d5a258f4f',
	'APPKEY' => '443146saew22g6533dg54351ss',
	//HIS层接口地址
	//'RWZB_HIS_HOST_URL' => 'http://114.255.34.100:28081/pyy/index.php/H122',//海淀医院
	'RWZB_HIS_HOST_URL' => 'http://114.255.34.100:28081/pyy/index.php/H122Test',//海淀医院测试
	//'RWZB_HIS_HOST_URL' => 'http://124.205.81.218:18080/synhis/H001',//朝阳妇幼
	//'RWZB_HIS_HOST_URL' => 'http://124.205.81.218:18080/synhistest/H001',//朝阳妇幼测试
	//'RWZB_HIS_HOST_URL' => 'http://221.217.90.82:20000/index.php/H040', //潞河医院
	//支付层接口地址
	'RWZB_PAY_HOST_URL' => 'http://localhost:8088/rwzb-pay/index.php/Home',
	'RWZB_PAY_PASSWORD' => '20190401',
	//支付接口业务数据对照
	'RWZB_PAY_TRADE_TYPE' => array(
		'jrgh' => 'GH',
		'yygh' => 'YY',
		'zzjk' => 'JK',
		'yyqh' => 'QH',
		'zzjf' => 'JF',
		'zhcx' => 'ZC',
		'bdpt' => 'BT'
	),
	//加载外部配置
	'LOAD_EXT_CONFIG' => 'test_json_data', 
	//测试假数据开关,*上线后请谨慎打开!!!
	'IS_TEST' => 0,
	//HIS假数据开关,*上线后请谨慎打开!!!
	'IS_HIS_TEST' => 1,
	//支付1分钱开关,*上线后请谨慎打开!!!
	'IS_1FEN' => 1,
	//支付方式
	'PAY_TYPE_KV' => array(
        'alipay' => '1',
        'wxpay' => '2',
        'bankpay' => '3',
        'jytpay' => '4',
	)
);