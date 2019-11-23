<?php
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Mvc\Url;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

try {

// 创建自动加载(AutoLoaders)实例
$loader = new Loader();

// 通过自动加载加载控制器(Controllers)
$loader->registerDirs(array(
// 控制器所在目录
'../app/controllers/',
))->register();

// 创建一个DI实例
$di = new FactoryDefault();
$url = new Url();
    $url->setBaseUri("//abc.cc/");
/*$url->setBaseUri("//www.abc.cc/");*//**/
//    $di->set(
//        "url",
//        function () {
//            $url = new Url();
//
//            $url->setBaseUri("/abc.cc/");
//
//            return $url;
//        }
//    );
// 实例化View 赋值给DI的view
    // 初始化数据库连接 从配置项读取配置信息
    error_reporting(E_ALL & ~E_NOTICE);
    $di->set('db', function () use ($ConfigIni) {

        return new DbAdapter(array(
            "host"     => $ConfigIni->database->host,
            "username" => $ConfigIni->database->username,
            "password" => $ConfigIni->database->password,
            "dbname"   => $ConfigIni->database->dbname
        ));
    });
    $loader->registerDirs(array(
        // 控制器所在目录
        '../app/controllers/',
        //model所在目录
        '../app/models/',
    ))->register();

$di->set('view', function () {

$view = new View();
$view->setViewsDir('../app/views/');
return $view;
});
//实例化session并且开始 赋值给DI实例 方便在控制器中调用
//    $di->setShared('session', function () {
//        $session = new Session();
//        $session->start();
//        return $session;
//    });

// 处理请求
$application = new Application($di);
// 输出请求类容
echo $application->handle()->getContent();
} catch (\Exception $e){
// 异常处理
echo "PhalconException: ", $e->getMessage();
}