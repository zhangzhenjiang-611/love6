<?php
//curl方法
function curl_http($url, $param, $type = '', $header = '',$http_method = 'POST',$timeout = 60)
{
	//初始化
    $ch = curl_init();
    //组织参数
    $options = array(
        //CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],//模拟用户使用浏览器
        CURLOPT_HEADER => 0,
        CURLOPT_FOLLOWLOCATION => 0,//是否跟踪爬取重定向页面
        CURLOPT_SSL_VERIFYPEER => 0,//跳过证书检查
      	CURLOPT_SSL_VERIFYHOST => 0,//从证书中检查SSL加密算法是否存在
        CURLOPT_RETURNTRANSFER => 1,//设置获取的信息以文件流的形式返回，而不是直接输出。
        CURLOPT_TIMEOUT => $timeout,//设置超时时间
    //CURLOPT_HEADER => 1,//返回 response_header,该选项非常重要,如果不为 true,只会获得响应的正文
    //CURLOPT_NOBODY => 1,//是否不需要响应的正文,为了节省带宽及时间,在只需要响应头的情况下可以不要正文
    );
    $headers = array();
    if($header)
    {
        $headers[] = $header;
    }
    if ($http_method == 'POST') {//POST
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_POST] = 1;
        switch ($type) {
            case 'xml':
                $headers[] = "Content-Type:text/xml; charset=utf-8";
                $options[CURLOPT_HTTPHEADER] = $headers;
                break;
            case 'json':
                $headers[] = "Content-Type:application/json";
                $options[CURLOPT_HTTPHEADER] = $headers;
                $param = json_encode($param);
                break;
            default:
                $options[CURLOPT_HTTPHEADER] = $headers;
                break;
        }
		$options[CURLOPT_POSTFIELDS] = $param;
    }
    else//GET
    {
        $delimiter = strpos($url, '?') === false ? '?' : '&';
        $options[CURLOPT_URL] = $url . $delimiter . http_build_query($param, '', '&');
        $options[CURLOPT_POST] = 0;
    }
    //设置参数 
    curl_setopt_array($ch, $options);
    //执行
    $result = curl_exec($ch);
    $error_no = curl_errno($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

    // 获得响应结果里的：头大小
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    // 根据头大小去获取头信息内容
    $header = substr($sContent, 0, $headerSize);

    //记录日志
    $fileName = "Application/Runtime/Logs/curl_".date('Ymd') . '.log';
    $log = '';
    $log .= 'TIME:' . date('Y-m-d H:i:s') . "\n";
    $log .= 'URL:' . $url . "\n";
    $log .= 'METHOD:' . $http_method . "\n";
    $log .= 'HEADER:' . json_encode($headers) . "\n";
    $log .= 'PARAMS:' . (is_array($param) ? json_encode($param) : $param) . "\n";
    $log .= 'RETURN:' . $result . "\n";
    $log .= 'ERROR_NO:' . $error_no . "\n";
    $log .= 'ERROR:' . $error . "\n";
    $log .= 'HTTP_CODE:' . $httpCode . "\n";
    $log .= "\t\n";
    file_put_contents($fileName, $log, FILE_APPEND);
    curl_close($ch);
    return $result;
}
//json转xml
function json2xml($source) {
    $string="";
    $source = json_decode($source);
    foreach($source as $k=>$v){
        $string .="<".$k.">";
        //判断是否是数组，或者，对像
        if(is_array($v) || is_object($v)){
            //是数组或者对像就的递归调用
            $string .= json2xml($v);
        }else{
            //取得标签数据
            $string .=$v;
        }
        $string .="</".$k.">";
    }
    return $string;
}
//随机数
function rand_number($len)
{
    //不能超过4字节最大4294967295
    return mt_rand(100,429).substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $len-1);
}
//服务器url
function web_server_url()
{
    $protocol = $_SERVER['SERVER_PROTOCOL'];
    $protocol = explode('/',$protocol);
    $protocol = strtolower($protocol[0]);
    return $protocol.'://'.$_SERVER['HTTP_HOST'];
}
//签名
function sign($data)
{
    $sign = '';
    ksort($data);
    foreach($data as $k => $v)
    {
        if($v !== "")
        {
            $sign .= "{$k}={$v}&";
        }
    }
    $sign .= 'key='.C('APPKEY');
    $sign = strtoupper(md5($sign));
    return $sign;
}
//驼峰转下划线
function tf2x($param)
{
    foreach ($param as $key => $value)
    {
        unset($param[$key]);
        $key = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $key));
        $param[$key] = $value;
    }
    return $param;
}
//验证身份证
function isIdCard($number) 
{
    $sigma = 0;
    //加权因子 
    $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    //校验码串 
    $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    //按顺序循环处理前17位 
    for ($i = 0;$i < 17;$i++) { 
        //提取前17位的其中一位，并将变量类型转为实数 
        $b = (int) $number{$i}; 
        //提取相应的加权因子 
        $w = $wi[$i]; 
        //把从身份证号码中提取的一位数字和加权因子相乘，并累加 得到身份证前17位的乘机的和 
        $sigma += $b * $w;
    }
    //计算序号  用得到的乘机模11 取余数
    $snumber = $sigma % 11; 
    //按照序号从校验码串中提取相应的余数来验证最后一位。 
    $check_number = $ai[$snumber];
    if ($number{17} == $check_number) {
        return true;
    } else {
        return false;
    }
}

