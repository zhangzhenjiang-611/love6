<?php
namespace Nurse2\Controller;
use Think\Controller;
class RoomListController extends Controller {
public function index(){
  $count = M("tbl_room_list")->count();
  $Page = new \Think\Page($count,getOpVal("houtai_page_num"));
  $show = $Page->show();// 分页显示输出
  $list = M("tbl_room_list")->order("dept_name,real_id asc")->limit($Page->firstRow.','.$Page->listRows)->order("room_id asc")->select();
  for($i=0;$i<count($list);$i++){
	 $a = M("client_uid")->where("room_id='".$list[$i]['room_id']."'")->count();
	 if($a>0){
		$list[$i]['kaizhen'] = "<font color='green'>是</font>";
		$doc_code = M("client_uid")->where("room_id='".$list[$i]['room_id']."'")->getField("uid");
		$list[$i]['doctor_name'] = M("doctor_info")->where("doctor_code='".$doc_code."'")->getField("doctor_name");
	 }else{
		$list[$i]['kaizhen'] = "<font color='red'>否</font>";
     }
	 
	 $b = M("tbl_room_pic")->where("room_id='".$list[$i]['room_id']."'")->count();
	  if($b==0){
		$list[$i]['have_pic'] = "<font color='red'>无</font>";	
	 }else{
		$list[$i]['have_pic'] = "<font color='green'>有</font>";
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
	$room_id = I("get.room_id");
	$this->url = I("get.url");
	$row = M("tbl_room_list")->where("room_id=".$room_id)->select();
	$imglist = M("tbl_room_pic")->where("room_id=".$row[0]['room_id'])->field("pic_url,title")->select();
	$this->imglist = $imglist;
	$this->row = $row[0];
	$this->display();	
}
public function edit_do(){
	$url = I("post.url");
	$img_ary = I("post.img_small");
	$title_ary = I("post.pname");
	$room_id = I("post.room_id");
	$room_name = I("post.room_name");
	$data['room_name'] = $room_name;
	$data['real_id'] = I("post.real_id");
	$data['dept_name'] = I("post.dept_name");
	$data['ip'] = I("ip");
	$a = M("tbl_room_list")->where("room_id=".$room_id)->count();
	M("tbl_room_list")->where("room_id=".$room_id)->save($data);
	for($i=0;$i<count($img_ary);$i++){	
		$data2['title'] = $title_ary[$i];
		M("tbl_room_pic")->where("room_id=".$room_id."  and pic_url='".$img_ary[$i]."'")->save($data2);
	}	
	
	$this->success("修改诊室信息成功",base64_decode($url));
		
}

public function add_do(){
	$img_ary = I("post.img_small");
	$url = I("post.url");
	$title_ary = I("post.pname");
	$data['real_id'] = I("post.real_id");
	$room_name = I("post.room_name");
	$data['room_name'] = $room_name;
	$data['dept_name'] = I("post.dept_name");
	$a = M("tbl_room_list")->where("room_name='".$room_name."'")->count();
	if(0==0){
		if($room_id=M("tbl_room_list")->add($data)){
			for($i=0;$i<count($img_ary);$i++){	
				$data2['room_id'] = $room_id;
				$data2['pic_url'] = $img_ary[$i];
				$data2['title'] = $title_ary[$i];
				M("tbl_room_pic")->add($data2);
			}	
		}
		$this->success("添加诊室成功",base64_decode($url));
	}else{
		$this->error("此诊室已存在");
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