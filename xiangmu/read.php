<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/16
 * Time: 20:56
 */
  $str = file_get_contents('./a.txt'); //获取一个文件或网络资源的内容 返回字符串

  //$url = "http://baijiahao.baidu.com/s?id=1661095654852561920";
  echo file_get_contents('./a.txt');

  //file_get_contents 读文件和网络资源 一次性把文件的内容全部读出来

  //file_put_contents('./b.txt',$str);

  //fopen fread fwrite fclose
