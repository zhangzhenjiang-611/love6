<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/21
 * Time: 14:09
 */
//递归查找家谱树
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

function familyTree($area,$id) {
     $tree = [];
    foreach ($area as $v) {
        if($v['id'] == $id) {
            if($v['parent'] > 0) {
                $tree =  array_merge($tree,familyTree($area,$v['parent']));
            }
            $tree[] = $v; //要不要找父栏目
        }
    }
    return $tree;
}
 echo "<pre>";
  print_r(familyTree($area,8));