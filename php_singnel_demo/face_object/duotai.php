<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/11
 * Time: 11:47
 */
//多态
/*1 程序扩展而准备
  2 必须有继承关系 父类最好是抽象类或者接口
*/
  interface Usb{
      //定义规范
      const WIDTH = 12;
      const HEIGHT = 2;
      function load();
      function run();
      function stop();
  }

  class computer {
      public function useUsb(Usb $usb){
          //限制只能传Usb类的对象
          $usb->load();
          $usb->run();
          $usb->stop();
      }
}

 class Mouse implements Usb{
      public function load()
      {
          // TODO: Implement load() method.
          echo "加载鼠标成功";
      }
      public function run()
      {
          // TODO: Implement run() method.
          echo "运行鼠标成功";
      }
      public function stop()
      {
          // TODO: Implement stop() method.
          echo "移除鼠标成功";
      }
 }
 class Worker {
      public  function work(){
          $c = new computer();
          $m = new Mouse();
          $c->useUsb($m);
      }
 }

 $w = new  Worker();
  $w->work();