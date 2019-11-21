<?php
use Phalcon\Mvc\Controller;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Http\Request;


// Index控制器类 必须继承Controller
class IndexController extends Controller {

    // 默认Action  http://www.abc.cc/?_url=/Index/index
    public function indexAction() {
        echo 112;
    }
    public  function testAction()
    {
        //echo "www";
        $logger = new FileAdapter("../Runtime/log/2016-2/20160203.log");  //初始化文件地址
        $logger->log("This is a message");                               //写入普通log
        $logger->log("This is an error", \Phalcon\Logger::ERROR);         //写入error信息
        $logger->error("This is another error");//于上一句同义

        // 开启事务
        $logger->begin();

// 添加消息
        $logger->alert("This is an alert123");
        $logger->error("This is another error456");

//  保存消息到文件中
        $logger->commit();
    }

    public function dirAction()
    {
       $path = '../Runtimes';
       if(!is_dir($path)){
           mkdir(iconv("UTF-8", "GBK", $path),0777,true);
       }
       if(!is_dir($path.'/log')){
           mkdir(iconv("UTF-8", "GBK", $path.'/log'),0777,true);
       }
       $date = date('Y-m');
        if(!is_dir($path.'/log/'.$date)){
            mkdir(iconv("UTF-8", "GBK", $path.'/log/'.$date),0777,true);
        }

        if(!file_exists($path.'/log/'.$date.'/'.date('Ymd').'.log')){
            fopen($path.'/log/'.$date.'/'.date('Ymd').'.log', "w");
        }
        $file = $path.'/log/'.$date.'/'.date('Ymd').'.log';
        $logger = new FileAdapter($file);  //初始化文件地址
        $logger->log("This is a message");                               //写入普通log
        $logger->log("This is an error", \Phalcon\Logger::ERROR);         //写入error信息
        $logger->error("This is another error");//于上一句同义

    }
    //session
    public function sessionAction()
    {

        $this->session('username','miao');
        echo $this->session->get('username');

    }
    //请求与响应
    public function requestAction()
    {
        $request = $this->request();
        var_dump($request);
    }



}
