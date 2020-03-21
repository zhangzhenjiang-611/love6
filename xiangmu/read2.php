<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/17
 * Time: 14:37
 */
/*$url = "http://baijiahao.baidu.com/s?id=1661095654852561920";
//fopen 打开一个资源 句柄
  $str = file_get_contents($url);
  file_put_contents('163new.html',$str);*/
  $file = './163new.html';
  //返回句柄 资源
$a = fopen($file,'r');  //只读模式
//var_dump($a);
//读 沿着上面返回的$a这个资源读文件
 $b= fread($a,10);
 //var_dump($b);

 $c = fwrite($a,'我来了');  //只读模式 无法写入

 //var_dump($c);
 //关闭资源
 fclose($a);

     $aa = fopen($file,'r+');   //读写模式，并把指针指向文件头
     $cc = fwrite($aa,'我来了'); //写入成功
fclose($aa);
//写入模式w 并把文件大小截为0
  //$aaa = fopen('modew.txt','w');

   //fclose($aaa);


//a 追加模式打开 能写，指针指向最后
$aaaa = fopen('modea.txt','a');
echo fwrite($aaaa,'ccccccc');
