<?php
$path = __DIR__;
//打开目录 返回资源 句柄
$dh = opendir($path);
/*echo readdir($dh);
echo "<br>";
echo readdir($dh);
echo "<br>";
echo readdir($dh);
echo "<br>";*/

 while(($row = readdir($dh)) != false) {
     //echo $row;
     //echo "<br>";
     if(is_dir($path."\\".$row)) {
         echo $row."是目录";
         echo "<br>";
     }

 }
