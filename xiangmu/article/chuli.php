<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/18
 * Time: 14:01
 */
//批量处理文件 删去小于10个字节和含有fuck的文件
//循环文件名，判断大小 小于10的删除，否则判断有没有fuck
foreach (['a.txt','b.txt','c.txt','d.txt'] as $v) {
    $file = __DIR__."\\".$v;
    //判断大小
  if( filesize($file) < 10)  {
      unlink($file);
      echo $file."小于10字节"."<br/>";
      continue;
  }
     $cont = file_get_contents($file);
     if(stripos($cont,'fuck')) {
         unlink($file);
         echo $file."有文明用语"."<br/>";
     }
}
