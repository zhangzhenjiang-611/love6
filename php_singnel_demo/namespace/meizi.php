<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/12
 * Time: 16:28
 */

namespace meizi\beauty; //同一个文件定义多个命名空间，以最后一个为准

const API = 2; //命名空间下定义常量用const
  class Demo {
      public static function one(){
          echo 666666666;
      }
      public function two(){
          echo 7777777777;
      }
  }

Demo::one();
  \meizi\beauty\Demo::one(); //加 /
  $b = new \meizi\beauty\Demo();
  $b->two();
  echo "<br/>";
  echo API;
echo "<br/>";
  echo \meizi\beauty\API;  //输出命名空间里常量


namespace a\b;
const BBB=2;
  echo BBB;
  echo \a\b\BBB;
  echo 123;