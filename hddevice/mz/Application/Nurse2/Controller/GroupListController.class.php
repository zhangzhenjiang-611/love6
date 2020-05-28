<?php
namespace Nurse2\Controller;
use Think\Controller;
class GroupListController extends Controller {
public function index(){
  $list = M()->Table("tbl_groups")->select();
 
  $this->list = $list;
  $this->display();
}
public function add(){
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
	$data['gname'] = I("post.gname");
	$da = M("tbl_groups")->where("gname='".I("post.gname")."'")->count();
	if($da>0){
		$this->error("该用户组已存在");
	}else{
		if(M("tbl_groups")->add($data)){
			$this->success("添加用户组成功","/mz/Nurse2/GroupList");
		}else{
			$this->error("添加用户组失败".mysql_error());
		}
	}
}
public function edit(){
	$gid = I("get.gid");
	$row = M("tbl_groups")->where("gid=".$gid)->select();
	$this->row = $row[0];
	$this->url = I("get.url");
	$this->display();
}
public function edit_do(){
	$gid = I("post.gid");
	$data['gname'] = I("post.gname");
	$url = I("post.url");
	if(M("tbl_groups")->where("gid=".$gid)->save($data)){
		$this->success("修改成功",base64_decode($url));
	}else{
		$this->error("修改失败");
	}
	
}
public function del(){
	$gid = I("get.gid");
	if($gid==""){
		$this->error("参数错误");
	}
	if(M("tbl_groups")->where("gid=".$gid)->delete()){
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