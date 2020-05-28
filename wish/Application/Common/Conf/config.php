<?php
//include 'D:/project/love6/wish/ThinkPHP/Common/functions.php';
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'think',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'hd_',    // 数据库表前缀
    'DB_PARAMS'          	=>  array(), // 数据库连接参数
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
    //自定义SESSION 数据库存储
    //'SESSION_TYPE' => 'mysqli',
    //'SESSION_TYPE' => 'Redis',
'SESSION_OPTIONS'         =>  array(
        //'name'                =>  'username',                    //设置session名
      //  'type'   =>  'redis',
        //'prefix'=>'sess_',
        //'expire'              =>  1440,                      //SESSION过期时间，单位秒
        //'use_trans_sid'       =>  1,                               //跨页传递
        //'use_only_cookies'    =>  0,                               //是否只开启基于cookies的session的会话方式

    ),
    'SESSION_TABLE'=>'hd_session',

  /*  'SESSION_AUTO_START'    =>  true,    // 是否自动开启Session
    'SESSION_TYPE'          =>  'Mysqli', // session hander类型 默认无需设置 除非扩展了session hander驱动
    'SESSION_PREFIX'        =>  'sess_', // session 前缀
    'VAR_SESSION_ID'        =>  'session_id', //sessionID的提交变量*/

   //公众号配置
    /*
     *   protected $apiKey = '';            //apikey
        protected $apiSecret = '';         // api秘钥
        protected $templateId = '';        //模板id
     * */
    'API_KEY' =>  '12',
    'apiSecret' =>  '34',
    'templateId' =>  '56',
);