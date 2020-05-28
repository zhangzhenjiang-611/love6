<?php
namespace Nurse2\Controller;
use Think\Controller;
class DoctorOpListController extends Controller {

public function index(){
	if(I("get.flag")=="search"){
	$list = M("doctor_op_records")->where("doctor_name like '%".I("post.keywords")."%'")->order("op_time desc")->select();
  }else{
	  $count = M()->Table("doctor_op_records")->count();
	   $Page = new \Think\Page2($count,getOpVal("houtai_page_num"));
  $show = $Page->show();// 分页显示输出
  	$list = M("doctor_op_records")->limit($Page->firstRow.','.$Page->listRows)->order("op_time desc")->select();
  }
  
  $this->list = $list;
  $this->assign('page',$show);// 赋值分页输出
  $this->display();
}
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
			$data['thumbnail'] = __ROOT__."/Uploads/".$info['thumbnail']['savepath'].$info['thumbnail']['savename'];
		}
		
		//print_r($info);
		
		
	}
	$data['doctor_name'] = I("post.doctor_name");
	$data['doctor_code'] = I("post.doctor_code");
	$data['doctor_postitle'] = I("post.doctor_postitle");
		$data['description'] = strip_tags(htmlspecialchars(I("post.description")));
		//$data['description'] = htmlspecialchars(stripslashes(I("post.description")),ENT_QUOTES ,UTF-8 - ASCII );
	//$data['description'] = strip_tags(htmlspecialchars(I("post.description")));
	//图片上传结束
	if(M()->Table("doctor_info")->where("doctor_code='".I("post.doctor_code")."'")->save($data)){
		$this->success("修改医生信息成功",base64_decode($url));																					
	}else{
		$this->success("修改医生信息失败".mysql_error());	
	}
}
}