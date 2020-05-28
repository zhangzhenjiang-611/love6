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
	document.title = document.title+'-登录';
	$('#submit_btn').click(function(){
		$.ajax({
			'url':'/rwpay/rwzb_pay/index.php/Home/Index/loginajax',
			'type':'post',
			'data':$('form').serialize(),
			success:function(res){
				alert(res.info);
				if(res.status == 1)
                {
					location.href='/rwpay/rwzb_pay/index.php/Home/Index/index';
                }
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
        <h1>融威众邦-登录</h1>
</header>
<div id="main">
        <form method="post">
            <div id="body" class="show" name="divcontent">
             <dl class="content">
                    <dt>用户名：</dt>
                    <dd>
                    	<input name="name" type="text" placeholder="请输入用户名"/>
                    </dd>
                    <hr class="one_line">
                    <dt>密    码：</dt>
                    <dd>
                    	<input name="password" type="password" placeholder="请输入密码"/>
                    </dd>
                    <dd id="btn-dd">
                        <span class="new-btn-login-sp">
                            <button class="new-btn-login" id="submit_btn" type="submit" style="text-align:center;">登录</button>
                        </span>
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