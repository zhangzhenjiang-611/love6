<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/15
 * Time: 15:44
 */
 // 写出一个函数，计算出文件额相对路径
     $a = '/a/b/c/d/e.php';
     $b = 'a/b/12/34/56/78/c.php';
     //计算出$b相对于$a的相对路径是 ../../c/d
     //1 将公共的目录取出
     //   c/d/e.php
     //   12/34/c.php

     //2 回到同级，病进入另一个目录

     function abspath($a,$b) {
         $path = "";
         $a = dirname($a);  // /a/b/c/d
         $b = dirname($b);
         $a = trim($a,"/");  // a/b/c/d
         $b = trim($b,"/");
         $a = explode("/",$a);
         $b = explode("/",$b);
           $num = max(count($a),count($b));
           for( $i = 0; $i < $num; $i++){
               if($a[$i] == $b[$i]) {
                   unset($a[$i]);
                   unset($b[$i]);
               } else {
                   break;
               }
           }
         //2 回到同级，病进入另一个目录
         $path = str_repeat("../",count($b)).implode("/",$a);
          return $path;
     }

    echo  abspath($a,$b);