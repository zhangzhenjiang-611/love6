<?php
namespace Home\Controller;
use Think\Controller;
require_once __DIR__ . '/Socket.php';
require_once __DIR__ . '/Log.php';
class ChinessDrugController extends Controller {
public function index(){
	$this->display();
}
public function getYaowu(){
/*// 数据库连接开始
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
 $return = odbc_exec($conn,"call web.DHCViewSelfServiceInterface_FindDrugItem('17','".$name."')");
 $num=0;
while(odbc_fetch_row($return)) {
  $num+=1;
  $name = odbc_result($return, 2);
  $jixing =odbc_result($return, 8);
  $guige =odbc_result($return, 3);
  $jijia =odbc_result($return, 9);
  $jiage =odbc_result($return, 10);
  $changjia =odbc_result($return, 4);
  // $chandi =odbc_result($return, 4);
  // $dengji =odbc_result($return, 4);
  $name = iconv("gb2312//IGNORE","utf-8",$name);
  $jixing = iconv("gb2312//IGNORE","utf-8",$jixing);
  $guige = iconv("gb2312//IGNORE","utf-8",$guige);
  $jijia = iconv("gb2312//IGNORE","utf-8",$jijia);
  $changjia = iconv("gb2312//IGNORE","utf-8",$changjia);
  $jiage =sprintf("%.2f", $jiage);
  // $jijia=$this->f3($jijia);
  $arr[$num]['drug_name']=$name;
  $arr[$num]['jixing']=$jixing;
  $arr[$num]['guige']=$guige;
  $arr[$num]['jijia']=$jijia;
  $arr[$num]['jiage']=$jiage;
  $arr[$num]['changjia']=$changjia;
}
// print_r($arr);
// 记录条数nums
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
for($i=1;$i<6;$i++){
  $plist['yaowu'][]=$yaowu[$i];
}
// print_r($yaowu);
  // $plist['yaowu']=$yaowu;
  $plist['success']='1';
  $plist['pages']=$pages;
        // print_r($plist);
 $this->ajaxReturn($plist,"JSON");*/
 //////////////////////本地测试连接////////////////////////
 $name=I("post.str");
  // $last_name = cookie('yaowu_py');
  // 设定每页记录数
  $pagesize='5';
  // 查询总记录条数
    $model = M();
    $where['yaowu_py']=array('like','%'.$name.'%');
  $nums=M('drug_zc')->where($where)->count();
  //取得总页数
    // if($name != $last_name && $name !=""){
  // cookie('yaowu_py',$name);
  // $last_name = cookie('yaowu_py');
  // echo $name.'aaaaa';
  if($name==""){
      $pages=ceil(0/$pagesize);
  // 当前页码
  $page='0';
  $yaowu = $model->query("SELECT * FROM `drug_zc` WHERE `yaowu_py` = ".$name." limit 0,5");
    $plist['page']='0';
  }else{
      $pages=ceil($nums/$pagesize);
  // 当前页码
  $page='1';
  $yaowu = $model->query("SELECT * FROM `drug_zc` WHERE `yaowu_py` LIKE '%".$name."%' limit 0,5");
  $plist['page']='1';
}
// print_r($yaowu);
  $plist['yaowu']=$yaowu;
// for($i=0;$i<2;$i++){
//   $plist['yaowu'][]=$yaowu[$i+'1'];

// }
  $plist['success']='1';
  $plist['pages']=$pages;
  // }else if($name==""){
  // cookie('yaowu_py','空');
  // $plist['success']='0';
  // }else{
  // $plist['success']='0';
  // }
   // echo $now_name.'当前';
      // print_r($plist);
 $this->ajaxReturn($plist,"JSON");
}
public function fanye(){
 /* // 数据库连接开始
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
  $return = odbc_exec($conn,"call web.DHCViewSelfServiceInterface_FindDrugItem('17','".$name."')");
  $num=0;
  while(odbc_fetch_row($return)) {
  $num+=1;
  $name = odbc_result($return, 2);
  $jixing =odbc_result($return, 8);
  $guige =odbc_result($return, 3);
  $jijia =odbc_result($return, 9);
  $jiage =odbc_result($return, 10);
  $changjia =odbc_result($return, 4);
  // $chandi =odbc_result($return, 4);
  // $dengji =odbc_result($return, 4);

  $name = iconv("gb2312//IGNORE","utf-8",$name);
  $jixing = iconv("gb2312//IGNORE","utf-8",$jixing);
  $guige = iconv("gb2312//IGNORE","utf-8",$guige);
  $jijia = iconv("gb2312//IGNORE","utf-8",$jijia);
  $changjia = iconv("gb2312//IGNORE","utf-8",$changjia);
  // $chandi = iconv("gb2312//IGNORE","utf-8",$chandi);
  $jiage =sprintf("%.2f", $jiage);

  $arr[$num]['drug_name']=$name;
  $arr[$num]['jixing']=$jixing;
  $arr[$num]['guige']=$guige;
  $arr[$num]['jijia']=$jijia;
  $arr[$num]['jiage']=$jiage;
  $arr[$num]['changjia']=$changjia;
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
function f3($str)
{
$result = array();
preg_match_all("/(?:\()(.*)(?:\))/i",$str, $result);
return $result[1][0];
} */
//////////////////本地测试连接数据库////////////////////
$pagesize='5';
  // 查询总记录条数
  $name=I("post.str");
  if($name==""){
     $where['yaowu_py']=$name;
  }else{
     $where['yaowu_py']=array('like','%'.$name.'%');
  }
  $nums=M('drug_zc')->where($where)->count();
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
  $yaowu=M('drug_zc')->where($where)->limit($kaishi,$pagesize)->select();
    // echo M('drug')->_sql();
  $plist['yaowu']=$yaowu;
  $plist['success']='1';
  $plist['page']=$page;
  $plist['pages']=$pages;
 $this->ajaxReturn($plist,"JSON");
}



}








