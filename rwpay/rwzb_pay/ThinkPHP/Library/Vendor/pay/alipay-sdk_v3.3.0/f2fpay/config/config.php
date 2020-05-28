<?php
$config = array (
		'alipay_public_key_file' => dirname ( __FILE__ ) . "/key/alipay_rsa_public_key.pem",
		'merchant_private_key_file' => dirname ( __FILE__ ) . "/key/rsa_private_key.pem",
		'merchant_public_key_file' => dirname ( __FILE__ ) . "/key/rsa_public_key.pem",		
		'charset' => "GBK",
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
		'app_id' => "2019081366224185",
		'notify_url' => "http://211.159.153.49/mz/Home/AliPay/getAsyncInfo",
		'sign_type' => "RSA2"
);