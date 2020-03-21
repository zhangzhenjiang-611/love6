<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/16
 * Time: 15:12
 */
  abstract class db {
      //链接服务器
      /*
       * $h 服务器地址
       * $u 用户名
       * $p 密码
       * */
      public abstract function connect($h,$u,$p);

      //发送查询 $sql SQL语句  return mixed bool/resource
      public abstract function query($sql);

      /*
       * 查询多行数据
       * return array/bool*/
      public abstract function getAll($sql);


      /*
       * 查询单行数据
       * return array/bool*/
      public abstract function getRow($sql);


      /*
       * 查询单个数据
       * return array/bool*/
      public abstract function getOne($sql);

      //自动拼接SQL
      public abstract function autoExecute($table,$data,$act='insert',$where='');

  }