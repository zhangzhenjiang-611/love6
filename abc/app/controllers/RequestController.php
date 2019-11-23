<?php
use Phalcon\Mvc\Controller;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\22 0022
 * Time: 9:31
 */
class RequestController extends Controller
{
        public function indexAction()
        {
            $request = $this->request;

            var_dump($request->get());                          //默认获取所有的请求参数返回的是array效果和获取$_REQUEST相同
            var_dump($request->get('wen'));                     //获取摸个特定请求参数key的valuer和$_REQUEST['key']相同
            var_dump($request->getQuery('url', null, 'url'));   //获取get请求参数,第二个参数为过滤类型,第三个参数为默认值
            var_dump($request->getMethod());                    //获取请求的类型如果是post请求会返回"POST"
            var_dump($request->isAjax());                       //判断请求是否为Ajax请求
            var_dump($request->isPost());                       //判断是否是Post请求类似的有(isGet,isPut,isPatch,isHead,isDelete,isOptions等)
            var_dump($request->getHeaders());                   //获取所有的Header,返回结果为数组
            var_dump($request->getHeader('Content-Type'));      //获取Header中的的莫一个指定key的指
            var_dump($request->getURI());                       //获取请求的URL比如phalcon.w-blog.cn/phalcon/Request获取的/phalcon/Request
            var_dump($request->getHttpHost());                  //获取请求服务器的host比如phalcon.w-blog.cn/phalcon/Request获取的phalcon.w-blog.cn
            var_dump($request->getServerAddress());             //获取当前服务器的IP地址
            var_dump($request->getRawBody());                   //获取Raw请求json字符
            var_dump($request->getJsonRawBody());               //获取Raw请求json字符并且转换成数组对象
            var_dump($request->getScheme());                    //获取请求是http请求还是https请求
            var_dump($request->getServer('REMOTE_ADDR'));       //等同于$_SERVER['REMOTE_ADDR']

            echo "<h1>Request!</h1>";
        }
}