<?php
namespace Nurse\Controller;
use Think\Controller;
class TeamController extends Controller {
public function index(){
  $list = M()->Table("doctor_info")->select();
  $this->list = $list;
  $this->display();
}
public function add(){
	$this->display();
}
public function add_do(){
	$data['name'] = I("post.name");
	$a = M()->Table("team")->where("name='".I("post.name")."'")->count();
	if($a==0){
		M()->Table("team")->add();
	}else{
		$this->error("此团队已存在");
	}
	
}


}