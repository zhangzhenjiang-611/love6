<?php
header("Content-Type: text/html; charset=utf-8"); 
ini_set("soap.wsdl_cache_enabled","0");
include("call.php"); 
include("SoapDiscovery.class.php");
    
$disco = new SoapDiscovery('call','soap_server'); 
$wsdl = $disco->getWSDL(); 
//$disco->getDiscovery();
str_replace("create.php","Service.php",$wsdl); 
$fp = fopen("Service.wsdl", "w");
$res = fwrite($fp, $wsdl);
if($res)
{
 	echo "生成Service.wsdl成功！";
}
fclose($fp);
?>