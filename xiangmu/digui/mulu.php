<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/19
 * Time: 14:51
 */
//echo mkdir('/b') ?'ok':'fail';
   function mk_dir($path) {
       if(is_dir($path)) {
           return true;
       }
       if(is_dir(dirname($path))) {
           return mkdir($path);
       }

       //父目录不存在
       mk_dir(dirname($path));
       return mkdir($path);

   }

   mk_dir('./a/b/c/d/e/f');