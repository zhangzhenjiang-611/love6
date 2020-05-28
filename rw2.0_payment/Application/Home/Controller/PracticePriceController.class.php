<?php
namespace Home\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
require_once __DIR__ . '/Log.php';
class PracticePriceController extends Controller {
public function index(){
	$this->display();
}
public function getYaowu(){
// 数据库连接开始
  // echo "call web.DHCViewSelfServiceInterface_GetTarItem('YCX')";
$servername='DRIVER={InterSystems ODBC35};SERVER=dhcc34;DATABASE=dhc-app';
$username = 'dhcc_ssm';
$password = 'dhcc'; 
$conn = odbc_connect(
          "DRIVER={InterSystems ODBC35};Server=192.168.102.34;Database=dhc-app", 
          "dhcc_ssm", "dhcc")or die("Could not connect to ODBC database!");
// 数据库连接结束
  $name=I("post.str");
  $pagesize='5';
  // 查询总记录条数
 $return = odbc_exec($conn,"call web.DHCViewSelfServiceInterface_GetTarItem('".$name."','Y')");
 $num=0;
while(odbc_fetch_row($return)) {
  $num+=1;
  $name = odbc_result($return, 2);
  $bianma =odbc_result($return, 1);
  $jijia =odbc_result($return, 4);
  $jiage =odbc_result($return, 5);
  $text =odbc_result($return, 7);
  $yiju =odbc_result($return, 6);

  // $chandi =odbc_result($return, 4);
  // $dengji =odbc_result($return, 4);

  $name = iconv("gb2312//IGNORE","utf-8",$name);
  $bianma = iconv("gb2312//IGNORE","utf-8",$bianma);
  $text = iconv("gb2312//IGNORE","utf-8",$text);
  $jijia = iconv("gb2312//IGNORE","utf-8",$jijia);
  $yiju = iconv("gb2312//IGNORE","utf-8",$yiju);
  // $chandi = iconv("gb2312//IGNORE","utf-8",$chandi);
  $jiage =sprintf("%.2f", $jiage);

  $arr[$num]['name']=$name;
  $arr[$num]['bianma']=$bianma;
  $arr[$num]['text']=$text;
  $arr[$num]['jijia']=$jijia;
  $arr[$num]['jiage']=$jiage;
  $arr[$num]['yiju']=$yiju;
}
 // print_r($arr);
// 记录条数nums
$nums=$num;
  if($nums=="0"){
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
for($i=1;$i<6;$i++){
  $plist['yaowu'][]=$yaowu[$i];
}
// print_r($yaowu);
  // $plist['yaowu']=$yaowu;
  $plist['success']='1';
  $plist['pages']=$pages;
         // print_r($plist);
 $this->ajaxReturn($plist,"JSON");
}
public function fanye(){
  // 数据库连接开始
$servername='DRIVER={InterSystems ODBC35};SERVER=dhcc34;DATABASE=dhc-app';
$username = 'dhcc_ssm';
$password = 'dhcc'; 
$conn = odbc_connect(
          "DRIVER={InterSystems ODBC35};Server=192.168.102.34;Database=dhc-app", 
          "dhcc_ssm", "dhcc")or die("Could not connect to ODBC database!");
// 数据库连接结束
	$pagesize='5';
  // 查询总记录条数
  $name=I("post.str");
  $return = odbc_exec($conn,"call web.DHCViewSelfServiceInterface_GetTarItem('".$name."','Y')");
  $num=0;
  while(odbc_fetch_row($return)) {
  $num+=1;
  $name = odbc_result($return, 2);
  $bianma =odbc_result($return, 1);
  $jijia =odbc_result($return, 4);
  $jiage =odbc_result($return, 5);
  $text =odbc_result($return, 7);
  $yiju =odbc_result($return, 6);

  // $chandi =odbc_result($return, 4);
  // $dengji =odbc_result($return, 4);

  $name = iconv("gb2312//IGNORE","utf-8",$name);
  $bianma = iconv("gb2312//IGNORE","utf-8",$bianma);
  $text = iconv("gb2312//IGNORE","utf-8",$text);
  $jijia = iconv("gb2312//IGNORE","utf-8",$jijia);
  $yiju = iconv("gb2312//IGNORE","utf-8",$yiju);
  // $chandi = iconv("gb2312//IGNORE","utf-8",$chandi);
  $jiage =sprintf("%.2f", $jiage);

  $arr[$num]['name']=$name;
  $arr[$num]['bianma']=$bianma;
  $arr[$num]['text']=$text;
  $arr[$num]['jijia']=$jijia;
  $arr[$num]['jiage']=$jiage;
  $arr[$num]['yiju']=$yiju;
}
// 记录条数nums
// print_r($arr);
  $yaowu = $arr;
$nums=$num;
  $pages=ceil($nums/$pagesize);
    if($pages<1 && $nums>0)
  	{
      // 一共多少页
  		$pages=1;
  	}
  // 当前页数
  $page=I("post.page");
   // echo $page.'hahah';
  //取得传递过来的页数
  //如果传递过来的页数比总页数还大，就让它等于总页数
  //如果传递过来的页数小于1，就让他等于1
	if($page>$pages){
		$page=$pages;
  $kaishi=($page-1)*$pagesize;
	}
	else if($page<1){
	$page=1;
  $kaishi=0;
	}else if(1<=$page || $page<=$pages){
    $page=$page;
  $kaishi=($page-1)*$pagesize;
  }
  for($i=1;$i<6;$i++){
  $plist['yaowu'][]=$yaowu[$i+$kaishi];
}
    // echo M('drug')->_sql();
  $plist['success']='1';
  $plist['page']=$page;
  $plist['pages']=$pages;
   // print_r($plist);
 $this->ajaxReturn($plist,"JSON");
}
}