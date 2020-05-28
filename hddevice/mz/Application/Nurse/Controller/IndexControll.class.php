<?php
namespace Nurse\Controller;
use Think\Controller;
class DoctorController extends Controller {
public function index(){
  $list = M()->Table("doctor_info")->select();
  $this->list = $list;
  $this->display();
}
/* public function zhicheng_add(){
	$this->display();
}
public function zhicheng_add_do(){
	$data['title'] = I("post.title");
	$a = M()->Table("postitle")->where("title='".I("post.title")."'")->count();
	if($a==0){
		M()->Table("postitle")->add();
	}else{
		$this->error("此职称已存在");
	}
}*/
public function edit(){
	$doctor_code = I("get.doctor_code");
	
	$row = M()->Table("doctor_info")->where("doctor_code='".$doctor_code."'")->select();
	$this->assign("row",$row[0]);
	$this->assign("url",I("get.url"));
	$this->display();
}
public function edit_do(){
	$url = I("post.url");
	//图片上传开始
	$upfile=$_FILES["thumbnail"];
	$error=$upfile["error"];
	if($error!=4){
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->exts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	
		$upload->rootPath  =  './Uploads/'; // 设置附件上传根目录
		$upload->savePath  = "";
		// 上传文件 
  	    $info   =   $upload->upload();
		if(!$info) {// 上传错误提示错误信息
        $this->error($upload->getError());
		}else{// 上传成功
			$data['thumbnail'] = $info['thumbnail']['savepath'].$info['thumbnail']['savename'];
		}
		
		//print_r($info);
		
		
	}
	$data['team_info'] = I("post.team_info");
		$data['description'] = I("post.description");
	//$data['description'] = strip_tags(htmlspecialchars(I("post.description")));
	//图片上传结束
	if(M()->Table("doctor_info")->where("doctor_code='".I("post.doctor_code")."'")->save($data)){
		$this->success("修改医生信息成功",base64_decode($url));																					
	}else{
		$this->success("修改医生信息失败".mysql_error());	
	}
}
}