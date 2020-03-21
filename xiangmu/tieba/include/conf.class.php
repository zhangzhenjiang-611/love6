<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/16
 * Time: 16:23
 */
//配置文件读取类
   class Conf {
       protected static $ins = null;
       protected $data = [];
       final protected  function  __construct()
       {
             include(ROOT.'./include/config.inc.php');
             $this->data = $_CFG;
       }

       final protected function  __clone()
       {
           // TODO: Implement __clone() method.
       }

       public static function getIns() {
           //单例
           if(self::$ins instanceof self) {
               return self::$ins;
           } else{
               self::$ins = new self();
               return self::$ins;
           }
       }

       //魔术方法读取data的信息
       public function __get($key)
       {    //直接访问私有、受保护的成员的时候， 自动把私有属性名称传给$key
           // TODO: Implement __get() method.
           if(array_key_exists($key,$this->data)) {
              return $this->data[$key];
           } else {
               return null;
           }
       }

       //在运行期，动态增加或改变配置选项
       public function __set($key, $value)
       {
           // TODO: Implement __set() method.
           $this->data[$key] = $value;
        }

   }

   //追加选项
 //$conf->aa = 'bb';
 //

