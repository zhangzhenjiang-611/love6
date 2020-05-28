<?php
namespace Nurse\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
class XuanJiaoController extends Controller {
public function index(){

 $this->row = M("settxt")->select();
 $this->video_list = M("videolist")->select();
  $this->display();
}

public function update(){
	$daping = I("post.daping");
	$xj_type = I("post.xj_type");
	$data1['con'] = $daping;
	$data2['con'] = $xj_type;
	M("settxt")->where("id=1")->save($data1);
	M("settxt")->where("id=2")->save($data2);
	$this->success("更新成功");
}
public function xjtxt(){
	$this->body = M("xjtxt")->where("id=1")->getField("body");
	$this->display();
}
public function xjpic(){
	$this->imglist = M("imglist")->select();
	$this->display();
}
public function xjvideo(){
	$list = M("videolist")->select();
	$this->list = $list;
	$this->display();
}
public function xjtxt_update(){
	$data['body'] = strip_tags(htmlspecialchars(I("post.body")));
	if(M("xjtxt")->where("id=1")->save($data)){
		$this->success("修改成功!");
	}
}

/**
*图片批量上传
*/
public function imgs_upload(){
	$Model = M();
	$upload = new \Think\Upload();// 实例化上传类
	$upload->maxSize = 3145728 ;// 设置附件上传大小
	$upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	$upload->rootPath = './Uploads/'; // 设置附件上传根目录
	$upload->savePath = ''; // 设置附件上传（子）目录
		
	 // 上传文件
	$info = $upload->upload();
	if(!$info) {// 上传错误提示错误信息
	echo $upload->getError();
	}else{// 上传成功
	
	
	$datas['title'] = $info['Filedata']['savename'];
	$datas['url'] = "/yanshi/mz/Uploads/".$info['Filedata']['savepath'].$info['Filedata']['savename'];
	M("imglist")->add($datas);
	echo json_encode($info);
	}
	
	
}


public function delete_obj(){
	$img_name = I("post.img_url");
		M("imglist")->where("title='".$img_name."'")->delete();                       
		$data['error'] = mysql_error();
		$data['imgurl'] = $img_name;
		$data['flag'] = 1;
	
	$this->ajaxReturn($data,"JSON");
}

/**
*视频批量上传
*/
public function video_upload(){
	$Model = M();
	$upload = new \Think\Upload();// 实例化上传类
	$upload->maxSize = 314572800 ;// 设置附件上传大小
	$upload->exts = array('flv', 'mp4');// 设置附件上传类型
	$upload->rootPath = './Uploads/'; // 设置附件上传根目录
	$upload->savePath = ''; // 设置附件上传（子）目录
		
	 // 上传文件
	$info = $upload->upload();
	if(!$info) {// 上传错误提示错误信息
		echo $upload->getError();
	}else{// 上传成功
	
	
	$datas['title'] = $info['Filedata']['savename'];
	$datas['url'] = "/yanshi/mz/Uploads/".$info['Filedata']['savepath'].$info['Filedata']['savename'];
	$datas['vtype'] = $info['Filedata']['ext'];
	$id = M("videolist")->add($datas);
	$info['id'] = $id;
	$info['title'] = $info['Filedata']['savename'];
	$info['url'] = "/yanshi/mz/Uploads/".$info['Filedata']['savepath'].$info['Filedata']['savename'];

	echo json_encode($info);
	}	
}
public function del(){
	$id = I("get.id");
	$url = M("videolist")->where("id=".$id)->getField("url");
	$real_url = realpath($url);
	echo $real_url;
	unlink($real_url);
	if(M("videolist")->where("id=".$id)->delete()){
		$this->success("删除成功");
	}else{
		$this->error("删除失败");
	}
}
public function video_edit(){
	$id = I("get.id");
	$row = M("videolist")->where("id=".$id)->select();
	$this->row = $row[0];
	$this->display();
}
public function video_edit_do(){
	$id = I("post.id");
	$data['title'] = I("post.title");
	$data['active'] = I("post.active");
	if(I("post.active")==1){
		$data2['active'] = 0;
		M("videolist")->where("1=1")->save($data2);
	}
	if(M("videolist")->where("id=".$id)->save($data)){
		$this->success("修改成功",'xjvideo');
	}else{
		$this->success("修改失败");
	}
}

}