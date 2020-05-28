<?php
require_once 'function.inc.php';
require_once 'AopSdk.php';
require_once 'config.php';
// require_once 'AlipaySign.php';
header ( "Content-type: text/html; charset=utf-8" );
/**
 * 此文件是手机支付宝钱包访问商户网站的wap页面。商户的业务处理可以在本文件中处理。
 * 此文件的访问URL，请填入支付宝服务窗后台的菜单设置中，点击服务窗菜单即可访问到本文件。
 */

// 日志记录下受到的请求
writeLog ( "POST: " . var_export ( $_POST, true ) );
writeLog ( "GET: " . var_export ( $_GET, true ) );
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>支付宝手机网页</title>
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, user-scalable=yes" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	
			<br>
			<br><a target="_blank" href="./f2fpay/barpay.php">当面付2.0  条码支付</a>
			<br><a target="_blank" href="./f2fpay/qrpay.php">当面付2.0 二维码支付</a>
			<br><a target="_blank" href="./f2fpay/query.php">当面付2.0  订单查询</a>
			<br><a target="_blank" href="./f2fpay/cancel.php">当面付2.0  订单撤销</a>
			<br><a target="_blank" href="./f2fpay/refund.php">当面付2.0  订单退款</a>


</body>
</html>
