<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/11
 * Time: 10:58
 */
//接口
/*aa
1 抽象类和接口中都有抽象方法 接口中只能有抽象方法，只能有常量
2 抽象类和接口都不能创建实例对象
3 抽象类和接口使用意义和作用相同
4 接口所有权限必须是公有的
5 声明接口使用interface
*/
interface Demo{
   // public $name; 不能有变量
    const PI = 3.14; //可以有常量
    /*public function eat(){
        //不能含有非抽象方法
    }*/
    //abstract不用这个关键字 默认抽象方法
     public function speak();
    //protected function say(); 不能为受保护的
     //private function say1(); //不能为私有的
}

 //$b = new Demo(); 不能实例化

/* 1 继承 接口之间用extends
   2 可以使用一个类来实现接口中的全部方法，也可以使用一个抽象类类实现接口中的部分方法
 */
 interface test extends Demo{
    /* function text() {

     }*/
    function test5();
 }
 class World{
     public function world1() {

     }
 }
   interface Teacher {
      function teach();
   }
  class Hello extends World implements test,Teacher {
     //接口转抽象类
     //继承world类的同时，实现test接口 先继承，后实现
      //实现多个接口只需要用, 分开
      //实现接口中的所有抽象方法
      public function test5()
      {
          // TODO: Implement test5() method.
      }
      public function speak()
      {
          // TODO: Implement speak() method.
          echo "ddd";
      }
      public function teach()
      {
          // TODO: Implement teach() method.
      }
  }

  $h = new Hello();
 $h->speak();