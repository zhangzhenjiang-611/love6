<?php
error_reporting('E_ALL & ~E_NOTICE ');

require_once 'call.php';
$call = new call();
$result = $call->getWeiXinPayUrl('test'.time(),'0.01','测试请忽略','hos001');


$result = $call->getPayUrl('test'.time(),'0.01','测试请忽略','hos001');
print_r($result);
exit;

//$result = $call->WxRefused('test1568098086','0.01','0.01','hos001','02249');

//$result = $call->refund('test1568098647','0.01','hos001','test');
//$result = $call->getWxPayStatus('jf20190820110003332');
//$result = $call->getPayStatus('00000001230020190821160135','hos001');
//print_r($result);
