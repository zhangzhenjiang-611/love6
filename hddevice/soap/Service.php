<?php
header("Content-Type: text/html; charset=utf-8"); 
include("call.php"); 	
$server = new SoapServer('Service.wsdl', array('soap_version'=>SOAP_1_2));
$server->setClass("call"); 
$server->handle();  
?> 