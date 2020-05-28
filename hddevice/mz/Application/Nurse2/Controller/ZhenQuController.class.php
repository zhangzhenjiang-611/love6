<?php
namespace Nurse2\Controller;
use Think\Controller;
class ZhenQuController extends Controller {
public function index(){
  $list = M()->Table("zhenqu")->select();
  $this->list = $list;
  $this->display();
}
public function add(){
	//自定义诊室
	$dept = M("custom_dept")->select();
	$this->dept = $dept;
	$this->display();
}
public function getDeptKey(){
	$key = $_GET["term"];
	$result = M()->query("select dept_code,dept_name from dept_info where dept_name like '%".$key."%'");
	
	for($i=0;$i<count($result);$i++){
		$list[$i]['id'] = $result[$i]['dept_code'];
		$list[$i]['label'] = $result[$i]['dept_name'];
	}
	echo json_encode($list);
}
public function add_do(){
	$data['name'] = I("post.name");
	//$data['dept_name'] = M("dept_info")->where("dept_code='".I("post.dept_code")."'")->getField("dept_name");
	$data['room'] = I("post.room");
	$data['position'] = I("post.position");
	$data['BigScreenID'] = I("post.BigScreenID");
	$data['VoiceIP']  = I("post.VoiceIP");
	$dept = I("post.dept");
	$data['dept'] = implode(",",$dept);
	if(M("zhenqu")->add($data)){
		$this->success("添加成功","/fenzhen/mz/index.php/Nurse/ZhenQu/");
	}else{
		$this->error("添加失败");
	}
}
public function edit(){
	$id = I("get.id");
	$row = M("zhenqu")->where("id=".$id)->select();
	$zq_ary = explode(",",$row[0]['dept']);
	//自定义诊室
	$dept = M("custom_dept")->select();
	for($i=0;$i<count($dept);$i++){
		if(in_array($dept[$i]['dept_code'],$zq_ary)){
			$dept[$i]['h'] = 1;
		}else{
			$dept[$i]['h'] = 0;
		}
	}
	$this->dept = $dept;
	
	$this->row = $row[0];
	$this->display();
}
public function edit_do(){
	$id = I("post.id");
	$data['name'] = I("post.name");
	$data['position'] = I("position");
	$data['room'] =I("post.room");
	$dept = I("post.dept");
	$data['BigScreenID'] = I("post.BigScreenID");
	$data['VoiceIP']  = I("post.VoiceIP");
	$data['VoiceIP2']  = I("post.VoiceIP2");
	$data['dept'] = implode(",",$dept);
	if(M("zhenqu")->where("id=".$id)->save($data)){
		$this->success("修改成功","/mz/index.php/Nurse/ZhenQu/");
	}else{
		$this->error("修改失败");
	}
	
}
public function del(){
	$id = I("get.id");
	if($id==""){
		$this->error("参数错误");
	}
	if(M("zhenqu")->where("id=".$id)->delete()){
		$this->success("删除成功");
	}else{
		$this->error("删除失败");
	}
}
public function getTeamKey(){
	$key = $_GET["term"];
	$flag = $_GET["flag"];
	//$result = $Model->query("select tid,tname from team where tname like '%".$key."%'");T T 
	$result = M("dept_info")->where("dept_name like '%".$key."%'")->field("dept_code,dept_name")->select();
	for($i=0;$i<count($result);$i++){
		$list[$i]['id'] = $result[$i]['dept_code'];
		$list[$i]['label'] = $result[$i]['dept_name'];
	}
	echo json_encode($list);
}

}