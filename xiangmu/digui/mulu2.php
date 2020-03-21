<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/19
 * Time: 15:07
 */
function mk_dir($path) {
    if(is_dir($path)) {
        return true;
    }
   return  is_dir(dirname($path)) || mk_dir(dirname($path))?mkdir($path):false;
}
//mk_dir('./aa/bb/cc/dd');
//echo mkdir('./aaa/bbb/ccc/ddd',0777,true);
//递归删除级联目录
 function deldir($path){
     if(!is_dir($path)) {
         return null;
     }
     $dh = opendir($path);
     while(($row = readdir($dh)) !== false ) {
         if($row == '.' || $row == '..') {
             continue;
         }
         //判断是否是普通文件
         if(!is_dir($path.'/'.$row)) {
             unlink($path.'/'.$row);
         } else{
             deldir($path.'/'.$row);
         }
     }
     closedir($dh);
     rmdir($path);
     return true;
 }
 echo deldir('./cc');