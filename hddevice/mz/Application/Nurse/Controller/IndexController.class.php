<?php
namespace Nurse\Controller;
use Think\Controller;
class IndexController extends Controller {
public function index(){
  $zhenqu = I("get.zhenqu");
  $this->zhenqu = $zhenqu;
  $this->display();
}
public function login(){
	$this->display();
}


}