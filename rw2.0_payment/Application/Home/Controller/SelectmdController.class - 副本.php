<?php
namespace Home\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
require_once __DIR__ . '/Log.php';
class SelectmdController extends Controller {

public function index(){



	$this->display();
}
public function getYaowu(){
// 数据库连接开始
$servername='DRIVER={InterSystems ODBC35};SERVER=dhcc34;DATABASE=dhc-app';
$username = 'dhcc_ssm';
$password = 'dhcc'; 
$conn = odbc_connect(
          "DRIVER={InterSystems ODBC35};Server=192.168.102.34;Database=dhc-app", 
          "dhcc_ssm", "dhcc")or die("Could not connect to ODBC database!");
// 数据库连接结束

// $return = odbc_exec($conn,"call web.DHCViewSelfServiceInterface_GetTarItem('ycx')");
// $num=0;
// while(odbc_fetch_row($return)) {
//   $num+=1;
//   $user = odbc_result($return, 1);
//   $idno = odbc_result($return, 2);
//   $idno = iconv("gb2312//IGNORE","utf-8",$idno);
//   $arr[$num]['user']=$user;
// }

  $name=I("post.str");
  $pagesize='5';
  // 查询总记录条数
 $return = odbc_exec($conn,"call web.DHCViewSelfServiceInterface_FindDrugItem('16','".$name."')");
 $num=0;
while(odbc_fetch_row($return)) {
  $num+=1;
  $name = odbc_result($return, 2);
  $idno = odbc_result($return, 2);
  $name = iconv("gb2312//IGNORE","utf-8",$name);
  $arr[$num]['drug_name']=$name;
}
$nums=$num;
  if($name==""){
      $pages=ceil(0/$pagesize);
  // 当前页码
  $page='0';
  $yaowu = 'false';
    $plist['page']='0';
  }else{
      $pages=ceil($nums/$pagesize);
  // 当前页码
  $page='1';
  $yaowu = $arr;
  $plist['page']='1';
}
// print_r($yaowu);
  $plist['yaowu']=$yaowu;
  $plist['success']='1';
  $plist['pages']=$pages;
  // print_r($plist);
 $this->ajaxReturn($plist,"JSON");
}
public function fanye(){
	$pagesize='5';
  // 查询总记录条数
  $name=I("post.str");
  if($name==""){
     $where['yaowu_py']=$name;
  }else{
     $where['yaowu_py']=array('like','%'.$name.'%');
  }
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
		$page=0;
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
}