<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/18
 * Time: 15:24
 */
//记录信息到日志
  class Log {
      const LOGFILE = 'log'; //日志文件的名称
      //写日志
      public static  function write($cont) {
          //判断是否备份
          $cont .= "\r\n";
          $log =self::isBake(); //计算日志文件地址

          $fh = fopen($log,'ab');
          fwrite($fh,$cont);
          fclose($fh);

      }
      //备份日志

      public static function bake() {
          //改名，存储 年月日
          $log = ROOT.'data/log/'.self::LOGFILE;
          $bak = ROOT.'data/log/'.date('ymd').mt_rand(10000,999999);
          rename($log,$bak);


      }

      //读取并且判断日志的大小
      public static function isBake() {
          $log = ROOT.'data/log/curr.log';
          //清楚缓存
          clearstatcache(true,$log);
          if(!file_exists($log)) {
              touch($log);
              return $log;
          }
          //判断
          $size = filesize($log);
          if($size <= 1024*1024) {
              return $log;
          }

          if(!self::bake()) {
              return $log;
          } else{
              touch($log);
              return $log;
          }

      }
  }