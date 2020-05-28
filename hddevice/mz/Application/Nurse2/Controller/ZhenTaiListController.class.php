<?php
namespace Nurse2\Controller;
use Think\Controller;
class ZhenTaiListController extends Controller {
public function index(){
  $count = M("tbl_zhentai_list")->count();
  $Page = new \Think\Page($count,50);
  $show = $Page->show();// 分页显示输出
  $list = M("tbl_zhentai_list")->order("id asc")->limit($Page->firstRow.','.$Page->listRows)->order("id asc")->select();
  for($i=0;$i<count($list);$i++){
	 $a = M("client_uid")->where("room_id='".$list[$i]['room_id']."' and zhentai='".$list[$i]['zhentai_id']."'")->count();
	 if($a>0){
		$list[$i]['kaizhen'] = "<font color='green'>是</font>";
		$doc_code = M("client_uid")->where("room_id='".$list[$i]['room_id']."' and zhentai='".$list[$i]['zhentai_id']."'")->getField("uid");
		$list[$i]['doctor_name'] = M("doctor_info")->where("doctor_code='".$doc_code."'")->getField("doctor_name");
	 }else{
		$list[$i]['kaizhen'] = "<font color='red'>否</font>";
     }
	 
	 
  }
  
  $this->assign('page',$show);// 赋值分页输出
  $this->list = $list;
  $this->display();
}

public function del(){
	$condition['room_id'] = I("get.room_id");
	$url = I("get.url");
	
	if(M("tbl_room_list")->where($condition)->delete()){
		M("tbl_room_pic")->where($condition)->delete();
		$this->success("删除诊室成功",base64_decode($url));
	}else{
		$this->error("删除诊室失败");
	}
}
public function edit(){
	$id = I("get.id");
	$this->room_list = M("tbl_room_list")->select();
	$this->url = I("get.url");
	$row = M("tbl_zhentai_list")->where("id=".$id)->select();
	$this->row = $row[0];
	$this->display();	
}
public function edit_do(){
	$data['zhentai_name'] = I("post.zhentai_name");
	$data['room_id'] = I("post.room_id");
	$data['zhentai_id'] = I("post.zhentai_id");
	$data['ip'] = I("post.ip");
	$id = I("post.id");
	M("tbl_zhentai_list")->where("id=".$id)->save($data);
	$url = I("post.url");
	$this->success("修改诊室信息成功",base64_decode($url));
		
}
public function add(){
	$this->room_list = M("tbl_room_list")->select();
	$this->display();
}
public function add_do(){
	$data['zhentai_name'] = I("post.zhentai_name");
	$data['room_id'] = I("post.room_id");
	$data['zhentai_id'] = I("post.zhentai_id");
	$data['ip'] = I("post.ip");
	$a = M("tbl_zhentai_list")->where("room_id='".I("post.room_id")."' and zhentai_id='". I("post.zhentai_id")."'")->count();
	if($a==0){
		if($room_id=M("tbl_zhentai_list")->add($data)){
			$this->success("添加诊台成功",base64_decode($url));
		}else{
			$this->success("添加诊台失败",base64_decode($url));
		}
		
	}else{
		$this->error("此诊台已存在");
	}
	
	
			
}


public function getTeamKey(){
	$key = $_GET["term"];
	$flag = $_GET["flag"];
	//$result = $Model->query("select tid,tname from team where tname like '%".$key."%'");
	$result = M("doctor_info")->where("doctor_name like '%".$key."%'")->field("doctor_code,doctor_name")->select();
	for($i=0;$i<count($result);$i++){
		$list[$i]['id'] = $result[$i]['doctor_code'];
		$list[$i]['label'] = $result[$i]['doctor_name'];
	}
	echo json_encode($list);
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
	if(I("post.mode")=="edit"){
		$datas['room_id'] = I("post.room_id");
		$datas['save_name'] = $info['Filedata']['savename'];
		$datas['title'] = $info['Filedata']['savename'];
		$datas['pic_url'] = __ROOT__."/Uploads/".$info['Filedata']['savepath'].$info['Filedata']['savename'];
		M("tbl_room_pic")->add($datas);
		$info['pic_url'] = __ROOT__."/Uploads/".$info['Filedata']['savepath'].$info['Filedata']['savename'];
	}
	
	echo json_encode($info);
	}
	
	
}

public function delete_obj(){
		$pic_url = I("post.img_url");
		//$pic_url = M("tbl_room_pic")->where("save_name='".$save_name."'")->getField("pic_url");
		M("tbl_room_pic")->where("pic_url='".$pic_url."'")->delete();                       
		$data['error'] = mysql_error();
		$data['pic_url'] = $pic_url;
		$data['flag'] = 1;
	    unlink("/var/www/html/".$pic_url);
	     $this->ajaxReturn($data,"JSON");
}

}