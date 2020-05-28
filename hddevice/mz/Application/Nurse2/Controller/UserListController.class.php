<?php
namespace Nurse2\Controller;
use Think\Controller;
class UserListController extends Controller {
public function index(){
  $list = M()->Table("tbl_user_list")->select();

  $this->list = $list;
  $this->display();
}
public function add(){
	//用户组列表
	$group_list = M("tbl_groups")->select();
	$this->group_list = $group_list;
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
	$data['uname'] = I("post.uname");
	$data['password'] = md5(I("post.password"));
	$data['nickname'] = I("post.nickname");
	$data['gid'] = I("post.gid");
	//$data['description'] = strip_tags(htmlspecialchars(I("post.description")));
	$da = M("tbl_user_list")->where("uname='".I("post.uname")."'")->count();
	if($da>0){
		$this->error("该用户已存在");
	}else{
		if(M("tbl_user_list")->add($data)){
			$this->success("添加用户成功","/mz/Nurse2/UserList");
		}else{
			$this->error("添加用户失败失败".mysql_error());
		}
	}
}
public function edit(){
	$uid = I("get.uid");
	$group_list = M("tbl_groups")->select();
	$this->group_list = $group_list;
	$row = M("tbl_user_list")->where("uid=".$uid)->select();
	$this->row = $row[0];
	$this->url = I("get.url");
	$this->display();
}
public function edit_do(){
	$uid = I("post.uid");
	$url = I("post.url");
	
	$data['uname'] = I("post.uname");
	$data['password'] =md5(I("post.password"));
	$data['nickname'] = I("post.nickname");
	$data['gid'] = I("post.gid");
	if(M("tbl_user_list")->where("uid=".$uid)->save($data)){
		$this->success("修改成功",base64_decode($url));
	}else{
		$this->error("修改失败");
	}
	
}
public function del(){
	$uid = I("get.uid");
	if($uid==""){
		$this->error("参数错误");
	}
	if(M("tbl_user_list")->where("uid=".$uid)->delete()){
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