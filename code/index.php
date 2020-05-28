<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用入口文件
//允许跨域操作
// $allow_origin = array(
//     'ssm-hoserp-web-test.rwjiankang.com',
//     'hos-ssm-test.rwjiankang.com',
// );
@header('Access-Control-Allow-Credentials: true');
// //跨域访问的时候才会存在此字段
// $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';  
// if (in_array($origin, $allow_origin)) {
//     header('Access-Control-Allow-Origin:' . $origin);
// } else {
//     return;
// }

@header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
@header('Access-Control-Allow-Methods: *');
@header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Application/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单