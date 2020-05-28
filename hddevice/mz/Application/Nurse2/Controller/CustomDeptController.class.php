<?php
namespace Nurse2\Controller;
use Think\Controller;
class CustomDeptController extends Controller {
public function index(){
  $list = M()->Table("custom_dept")->select();
  for($i=0;$i<count($list);$i++){
	$list[$i]['zhenqu_name'] = M("zhenqu")->where("id=".$list[$i]['zhenqu'])->getField("name"); 
  }
  $this->list = $list;
  $this->display();
}
public function add(){
	//科室列表
	$dept_list = M("dept_info")->select();
	$this->dept_list = $dept_list;
	//诊区列表
	$this->zhenqu = M("zhenqu")->select();
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
	$data['dept_code'] = I("post.dept_code");
	//$data['dept_name'] = M("dept_info")->where("dept_code='".I("post.dept_code")."'")->getField("dept_name");
	$data['dept_name'] = I("post.dept_name");
	$data['zhenqu'] = I("post.zhenqu");
	$data['description'] = strip_tags(htmlspecialchars(I("post.description")));
	$da = M("custom_dept")->where("dept_code='".I("post.dept_code")."'")->count();
	if($da>0){
		$this->error("该诊室已存在");
	}else{
		if(M("custom_dept")->add($data)){
			$this->success("添加成功");
		}else{
			$this->error("添加失败");
		}
	}
}
public function edit(){
	$id = I("get.id");
	$dept_list = M("dept_info")->select();
	$this->dept_list = $dept_list;
	//诊区列表
	$this->zhenqu = M("zhenqu")->select();
	$row = M("custom_dept")->where("id=".$id)->select();
	$this->row = $row[0];
	$this->url = I("get.url");
	$this->display();
}
public function edit_do(){
	$id = I("post.id");
	$url = I("post.url");
	$data['dept_code'] = I("post.dept_code");
	$data['dept_name'] =I("post.dept_name");
	$data['description'] = strip_tags(htmlspecialchars(I("post.description")));
	$data['zhenqu'] = I("post.zhenqu");
	if(M("custom_dept")->where("id=".$id)->save($data)){
		$this->success("修改成功",base64_decode($url));
	}else{
		$this->error("修改失败");
	}
	
}
public function del(){
	$id = I("get.id");
	if($id==""){
		$this->error("参数错误");
	}
	if(M("custom_dept")->where("id=".$id)->delete()){
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