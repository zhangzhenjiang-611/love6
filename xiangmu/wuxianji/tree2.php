<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/20
 * Time: 18:17
 */
//无限积分类
$area = array(
    array('id'=>1,'name'=>'安徽','parent'=>0),
    array('id'=>2,'name'=>'海淀','parent'=>7),
    array('id'=>3,'name'=>'濉溪县','parent'=>5),
    array('id'=>4,'name'=>'昌平','parent'=>7),
    array('id'=>5,'name'=>'淮北','parent'=>1),
    array('id'=>6,'name'=>'朝阳','parent'=>7),
    array('id'=>7,'name'=>'北京','parent'=>0),
    array('id'=>8,'name'=>'上地','parent'=>2)
);
// parent 是该栏目的父亲栏目的id
/*
0
  安徽
       淮北
           濉溪县
   北京
        海淀
             上地
         昌平
         朝阳

*/
//0 找指定栏目的子栏目
//1 找指定栏目的子孙栏目
// 2 找指定栏目的父栏目/父父栏目

function findchild($arr,$id=0) {
    $sons = [];
    foreach( $arr as $v) {
        if($v['parent'] == $id) {
            $sons[] = $v;
        }
    }
    return $sons;
}
//echo "<pre>";
//print_r(findchild($area,0));
function subtree($arr,$id=0,$lev=1) {
     $sons = [];
    foreach( $arr as $v) {
        if($v['parent'] == $id) {
            $v['lev'] = $lev;
            $sons[] = $v;             //array('id'=>1,'name'=>'安徽','parent'=>0),
            $sons = array_merge($sons,subtree($arr,$v['id'],$lev+1));
        }
    }
    return $sons;

}
echo "<pre>";
//print_r(subtree($area,0,1));
$tree = subtree($area,0,1);
foreach ($tree as $v) {
    echo str_repeat('&nbsp;&nbsp;',$v['lev']).$v['name']."<br>";
}