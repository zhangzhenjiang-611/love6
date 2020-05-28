<?php
return array(
  //'配置项'=>'配置值'
    'MODULE_ALLOW_LIST' => array('Home'),
    // 设置禁止访问的模块列表
    //'MODULE_DENY_LIST' => array('Common', 'Runtime', 'Api'),
    'DEFAULT_MODULE' => 'Home',
	'LOG_RECORD' => true,
	//'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR',
	'URL_MODEL' => 1, // 如果你的环境不支持PATHINFO 请设置为3
	
//数据库配置信息  
'DB_TYPE'   => 'mysql', // 数据库类型
'DB_HOST'   => '127.0.0.1', // 服务器地址 
'DB_NAME'   => 'lh_autodevice', // 数据库名
'DB_USER'   => 'root', // 用户名
'DB_PWD'    => 'trans', // 密码
'DB_PORT'   => 3306, // 端口
'DB_PREFIX' => '', // 数据库表前缀 
'DB_CHARSET'=> 'utf8', // 字符集
'DB_DEBUG'  =>  TRUE, // 调试模式


        //数据库配置
'DB_CONFIG1' => array(
    'db_type'  => 'sqlsrv',
    'db_user'  => 'rwzb',
    'db_pwd'   => 'rwzb',
    // 'db_host'  => '10.20.40.100',
    'db_host'  => '129.10.1.4',//真实地址
    'db_port'  => '1433',
    // 'db_name'  => 'test_mz'
    'db_name'  => 'bjtzlhyy_mz'
),
//数据库配置
'DB_CONFIG2' => array(
    'db_type'  => 'sqlsrv',
    'db_user'  => 'rvzb',
    'db_pwd'   => 'rvzb',
    'db_host'  => '129.10.1.4',//真实地址
    'db_port'  => '1433',
    'db_name'  => 'bjtzlhyy_mz'
),
//数据库配置
'DB_CONFIG3' => array(
    'db_type'  => 'sqlsrv',
    'db_user'  => 'sa',
    'db_pwd'   => '7540000E',
    // 'db_host'  => '10.20.40.100',
    'db_host'  => '172.16.1.5',//真实地址
    'db_port'  => '1433',
    'db_name'  => 'lisdb_2005'
),

'VAR_PAGE' => 'pageNum',
    'PAGE_LISTROWS' => 15, //分页 每页显示多少条
    'PAGE_NUM_SHOWN' => 10, //分页 页标数字多少个
    'SESSION_AUTO_START' => true,
    //'TMPL_ACTION_ERROR' => 'Public:success', // 默认错误跳转对应的模板文件
    //'TMPL_ACTION_SUCCESS' => 'Public:success', // 默认成功跳转对应的模板文件
    'USER_AUTH_ON' => true,
    'USER_AUTH_TYPE' => 2, // 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY' => 'authId', // 用户认证SESSION标记
    'ADMIN_AUTH_KEY' => 'administrator',
    'USER_AUTH_MODEL' => 'User', // 默认验证数据表模型
    'AUTH_PWD_ENCODER' => 'md5', // 用户认证密码加密方式
    'USER_AUTH_GATEWAY' => '/Admin/Public/login', // 默认认证网关
    'NOT_AUTH_MODULE' => '/Admin/Public', // 默认无需认证模块
    'REQUIRE_AUTH_MODULE' => '', // 默认需要认证模块
    'NOT_AUTH_ACTION' => '', // 默认无需认证操作
    'REQUIRE_AUTH_ACTION' => '', // 默认需要认证操作
    'GUEST_AUTH_ON' => false, // 是否开启游客授权访问
    'GUEST_AUTH_ID' => 0, // 游客的用户ID
    'DB_LIKE_FIELDS' => 'title|remark',
    'RBAC_ROLE_TABLE' => 'think_role',
    'RBAC_USER_TABLE' => 'think_role_user',
    'RBAC_ACCESS_TABLE' => 'think_access',
    'RBAC_NODE_TABLE' => 'think_node',
    'SHOW_PAGE_TRACE' => false, //显示调试信息
);