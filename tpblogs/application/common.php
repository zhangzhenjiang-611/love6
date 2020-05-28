<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//发送邮件
use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
function sendEmail($accept,$title,$content) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->CharSet = 'utf-8';
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.163.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'leruge@163.com';                     // SMTP username
        $mail->Password   = 'Ai157511';                               // SMTP password
        $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('leruge@163.com', '梦中程序员');
        $mail->addAddress($accept);     // Add a recipient


        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $title;
        $mail->Body    = $content;

       return $mail->send();
    } catch (Exception $e) {
       \exception($mail->ErrorInfo,'1001');
    }

}

//把span替换成a
function replace($data) {
    return str_replace('span','a',$data);
}

function WebService($uri,$class_name='',$namespace='controller',$persistence = false){
    $class = 'index\\'. $namespace .'\\'. $class_name;
    $class = 'app\admin\controller\Home';
    $serv = new \SoapServer(null,array("uri"=>$uri));
    $serv->setClass($class);
    if($persistence)
        $serv->setPersistence(SOAP_PERSISTENCE_SESSION);//默认是SOAP_PERSISTENCE_REQUEST
    $serv->handle();
    return $serv;

}

