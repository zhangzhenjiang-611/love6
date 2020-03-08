<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/8
 * Time: 15:47
 */
//单例模式
/*
 * 1.要让一个类只能产生一个对象，首先让这个类不能被外部实例化 构造方法私有化
 * 2.在类的内部又可以产生对象,此方法可以被外部静态调用
 * 3.将类里面的对象唯一化 静态
 * */
  class Art{
      private  static $obj;
      private function __construct()
      {
      }

      public static function getObj(){
          if(is_null(self::$obj)){
              self::$obj = new self();
          }
          return self::$obj;
      }
  }
  // $b = new Art();
  $obj = Art::getObj();
  var_dump($obj);
    $obj1 = Art::getObj();
    var_dump($obj1);
    var_dump($obj == $obj1);