<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="/rwpay/rwzb_pay/favicon.ico" type="image/x-icon" />
<meta http-equiv="X-UA-Compatible" content="IE=10;IE=8;IE=7" />
<link rel="stylesheet" href="/rwpay/rwzb_pay/Public/css/common.css" type="text/css" />
<script language="javascript" src="/rwpay/rwzb_pay/Public/js/jquery-1.9.1.js" ></script> 
<script language="javascript" src="/rwpay/rwzb_pay/Public/js/common.js" ></script>  
<title><?php echo C('HTML_TITLE')?></title>
<script language="javascript">
var URL = '/rwpay/rwzb_pay/index.php/Home/Index';
$(function(){
	document.title = document.title+'-医院支付设置';
	$('#submit_btn').click(function(){
		$.ajax({
			'url':'/rwpay/rwzb_pay/index.php/Home/Index/pay_config_save',
			'type':'post',
			'data':$('form').serialize(),
			success:function(res){
				alert(res.info);
				if(res.status == 1)
					$('#id').val(res.url);
			},
			error:function(){
				alert("操作异常，请重试");
			}
		});
		return false;
	});
	$('#query_btn').click(function(){
		document.location.href='/rwpay/rwzb_pay/index.php/Home/Index?'+'query_code='+$('#query_code').val();
		return false;
	});
});
</script>
</head>
<body text=#000000 bgColor="#ffffff" leftMargin=0 topMargin=4>
<header class="am-header">
        <h1><span style="float:right;font-size:14px;">您好，<?php echo (session('USER_NAME')); ?>&nbsp;<a href="/rwpay/rwzb_pay/index.php/Home/Index/loginout">登出</a>&nbsp;</span>融威众邦-医院支付配置</h1>
</header>
<div id="main">
        <form method="post">
        	<input type="hidden" name="id" id="id" value="<?php echo ($row['id']); ?>">
            <div id="body" class="show" name="divcontent">
             <dl class="content">
                    <dd>
                    	<input id="query_code" type="text" value="<?php echo ($_GET['query_code']); ?>" placeholder="请输入医院编号"/>
                    	<button class="new-btn-login" id="query_btn" style="margin-top:5px;text-align:center;">查询</button>
                    </dd>
                 	<hr class="one_line">
                    <dt>医院编号：</dt>
                    <dd>
                    	<input name="code" type="text" readonly="readonly" value="<?php echo ($row['code']); ?>" placeholder="自动分配，无需填写"/><span style="line-height: 28px; color:red;">自动分配，无需填写</span>
                    </dd>
                    <hr class="one_line">
                    <dt>密    码：</dt>
                    <dd>
                    	<input name="password" type="text" readonly="readonly" value="<?php echo ($row['password']); ?>" placeholder="随机生成，无需填写"/><span style="line-height: 28px; color:red;">随机生成，无需填写，调接口时候用到</span>
                    </dd>
                    <hr class="one_line">
                    <dt>医院名称：</dt>
                    <dd>
                        <input name="name" value="<?php echo ($row['name']); ?>" type="text" placeholder="请输入"/>
                    </dd>
                    <hr class="one_line">
                    <dt>微信支付配置：</dt>
                    <dd>
                        <textarea  style="float:left;" name="wxpay_config" cols="50" rows="10" placeholder="例如:
{
	&quot;APPID&quot;: &quot;xxx&quot;,
	&quot;MCHID&quot;: &quot;xxx&quot;,
	&quot;SUB_MCHID&quot;: &quot;xxx&quot;,
	&quot;KEY&quot;: &quot;xxx&quot;,
	&quot;APPSECRET&quot;: &quot;xxx&quot;,
	&quot;SignType&quot;: &quot;HMAC-SHA256&quot;,
	&quot;NOTIFY_URL&quot;: &quot;http://222.128.103.58:11288/rwzb-pay/index.php/Home/Pay/notify/pay_type/wxpay&quot;,
	&quot;SSLCERT&quot;: &quot;xxx&quot;,
	&quot;SSLKEY&quot;: &quot;xxx&quot;
}"
						><?php echo ($row['wxpay_config']); ?></textarea>
<div style="line-height: 28px; color:red;">1.SUB_MCHID子商户号，有配置没有不配置<br />2.SignType支持HMAC-SHA256和MD5两种</div>&nbsp;查看<a href="/rwpay/rwzb_pay/Public/wxconfig.txt" target="_blank">微信配置例子</a>
                    </dd>
                    <hr class="one_line">
                    <dt>支付宝配置：</dt>
                    <dd>
                        <textarea style="float:left;" name="alipay_config" cols="50" rows="10" placeholder="例如:
{
	&quot;sign_type&quot;: &quot;RSA&quot;,
	&quot;alipay_public_key&quot;: &quot;xxx&quot;,
	&quot;merchant_private_key&quot;: &quot;xxxx&quot;,
	&quot;charset&quot;: &quot;UTF-8&quot;,
	&quot;gatewayUrl&quot;: &quot;https://openapi.alipay.com/gateway.do&quot;,
	&quot;app_id&quot;: &quot;xxx&quot;,
	&quot;notify_url&quot;: &quot;http://222.128.103.58:11288/rwzb-pay/index.php/Home/Pay/notify/pay_type/alipay&quot;,
	&quot;MaxQueryRetry&quot;: &quot;10&quot;,
	&quot;QueryDuration&quot;: &quot;3&quot;
}"
						><?php echo ($row['alipay_config']); ?></textarea><div style="line-height: 28px; color:red;">1.SignType支持RSA和RSA2两种，请根据密钥的实际填写<br />2.MaxQueryRetry最大查询重试次数<br />3.QueryDuration查询间隔</div>&nbsp;查看<a href="/rwpay/rwzb_pay/Public/zfbconfig.txt" target="_blank">支付宝配置例子</a>
                    </dd>
                    <hr class="one_line">
                    <dt>银联支付配置：</dt>
                    <dd>
                        <textarea disabled="disabled" value="<?php echo ($row['unionpay_config']); ?>" name="unionpay_config" cols="50" rows="10" placeholder="暂时不支持"></textarea>
                    </dd>
                    <hr class="one_line">
                    <dt>备注：</dt>
                    <dd>
                        <textarea name="remark" value="<?php echo ($row['remark']); ?>" cols="30" rows="5"><?php echo ($row['unionpay_config']); ?></textarea>
                    </dd>
                    <hr class="one_line">
                    <dt></dt>
                    <dd id="btn-dd">
                        <span class="new-btn-login-sp">
                            <button class="new-btn-login" id="submit_btn" type="submit" style="text-align:center;">提 交</button>
                        </span>
                        <span class="note-help">如果您点击“提交”按钮，即表示您同意该次的执行操作。</span>
                    </dd>
                </dl>
            </div>
		</form>
       <div id="foot">
			<ul class="foot-ul">
				<li>
					版权所有&copy;北京融威众邦电子技术有限公司提供技术支持，技术电话：13521797155
				</li>
			</ul>
		</div>
</div>
</body>
</html>