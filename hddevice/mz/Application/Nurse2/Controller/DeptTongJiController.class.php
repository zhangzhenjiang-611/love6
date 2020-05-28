<?php
namespace Nurse2\Controller;
use Think\Controller;
class DeptTongJiController extends Controller {
public function index(){
	$dept_list = M("dept_info")->select();
	$this->dept_list = $dept_list;
$this->display();
}




}