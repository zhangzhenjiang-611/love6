<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/3
 * Time: 13:27
 */
//单例模式
//析构方法 对象释放之前 最后一次调用的方法 构造方法 创建对象之后第一个调用的方法
//想让一个类只能有一个对象，就要先这个类不能创建对象，将构造方法私有化
//可以在类的内部使用一个静态方法，来创建对象

  class Person{
      public static $obj = null;
      private function __construct(){
      }
     public static function getObj(){
          if(is_null(self::$obj)){
              self::$obj = new self();
          }
         return self::$obj;
      }

      public function __destruct(){
          // TODO: Implement __destruct() method.
          echo "333333333"."<br/>";
      }
      public function say(){
          echo '吃饭';
      }
  }
  $b =  Person::getObj();
  $b =  Person::getObj();
  $b->say();
