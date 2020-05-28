<?php
namespace Home\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
require_once __DIR__ . '/Log.php';
class YaoJiaController extends Controller {
public function index(){
	$this->display();
}
public function getYaowu(){
  $name=I("post.str");
  $last_name = cookie('yaowu_py');
  // 设定每页记录数
  $pagesize='5';
  // 查询总记录条数
    $model = M();
    $where['yaowu_py']=array('like','%'.$name.'%');
  $nums=M('drug')->where($where)->count();
  //取得总页数
  $pages=ceil($nums/$pagesize);
  // 当前页码
  $page='1';
    if($name != $last_name && $name !=""){
  cookie('yaowu_py',$name);
  $last_name = cookie('yaowu_py');
  $yaowu = $model->query("SELECT * FROM `drug` WHERE `yaowu_py` LIKE '%".$name."%' limit 0,5");
  $plist['yaowu']=$yaowu;
  $plist['success']='1';
  $plist['page']='1';
  $plist['pages']=$pages;
  }else if($name==""){
  cookie('yaowu_py','空');
  $plist['success']='0';
  }else{
  $plist['success']='0';
  }
   $now_name = cookie('yaowu_py');
   // echo $now_name.'当前';
   // print_r($plist);
 $this->ajaxReturn($plist,"JSON");
}

public function getZhenLiao(){
  $name=I("post.str");
  $last_name = cookie('zhenliao_py');
  // 设定每页记录数
  $pagesize='5';
  // 查询总记录条数
    $model = M();
    $where['yaowu_py']=array('like','%'.$name.'%');
  $nums=M('zhenliao')->where($where)->count();
  //取得总页数
  $pages=ceil($nums/$pagesize);
  // 当前页码
  $page='1';
    if($name != $last_name && $name !=""){
  cookie('yaowu_py',$name);
  $last_name = cookie('zhenliao_py');
  $yaowu = $model->query("SELECT * FROM `zhenliao` WHERE `yaowu_py` LIKE '%".$name."%' limit 0,5");
  $plist['yaowu']=$yaowu;
  $plist['success']='1';
  $plist['page']='1';
  $plist['pages']=$pages;
  }else if($name==""){
  cookie('zhenliao_py','空');
  $plist['success']='0';
  }else{
  $plist['success']='0';
  }
   $now_name = cookie('zhenliao_py');
   // echo $now_name.'当前';
   // print_r($plist);
 $this->ajaxReturn($plist,"JSON");
}
public function fanye(){
	$pagesize='5';
  // 查询总记录条数
  $name=I("post.str");
  $where['yaowu_py']=array('like','%'.$name.'%');
  $nums=M('drug')->where($where)->count();
  // $nums=M('drug')->where("'yaowu_py' like '%".$name."%'")->count();
  //取得总页数
  $pages=ceil($nums/$pagesize);
    if($pages<1 && $nums>0)
  	{
  		$pages=1;
  	}
  // 当前页数
  $page=I("post.page");
  //取得传递过来的页数
  //如果传递过来的页数比总页数还大，就让它等于总页数
  //如果传递过来的页数小于1，就让他等于1
	if($page>$pages){
		$page=$pages;
	}
	else if($page<1){
		$page=1;
	}
	$kaishi=($page-1)*$pagesize;
	$yaowu=M('drug')->where($where)->limit($kaishi,$pagesize)->select();
   // echo M('drug')->_sql();
  $plist['yaowu']=$yaowu;
  $plist['success']='1';
  $plist['page']=$page;
  $plist['pages']=$pages;
 $this->ajaxReturn($plist,"JSON");

}


public function fanye_zhenliao(){
	$pagesize='5';
  // 查询总记录条数
  $name=I("post.str");
  $where['yaowu_py']=array('like','%'.$name.'%');
  $nums=M('zhenliao')->where($where)->count();
  // $nums=M('drug')->where("'yaowu_py' like '%".$name."%'")->count();
  //取得总页数
  $pages=ceil($nums/$pagesize);
    if($pages<1 && $nums>0)
  	{
  		$pages=1;
  	}
  // 当前页数
  $page=I("post.page");
  //取得传递过来的页数
  //如果传递过来的页数比总页数还大，就让它等于总页数
  //如果传递过来的页数小于1，就让他等于1
	if($page>$pages){
		$page=$pages;
	}
	else if($page<1){
		$page=1;
	}
	$kaishi=($page-1)*$pagesize;
	$yaowu=M('zhenliao')->where($where)->limit($kaishi,$pagesize)->select();
   // echo M('drug')->_sql();
  $plist['yaowu']=$yaowu;
  $plist['success']='1';
  $plist['page']=$page;
  $plist['pages']=$pages;
 $this->ajaxReturn($plist,"JSON");

}
}