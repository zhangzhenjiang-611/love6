<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/21
 * Time: 14:30
 */
//迭代查找家谱树
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

function tree($arr,$id) {
    $tree = [];
    while ($id != 0) {
        foreach ($arr as $v) {
            if($v['id'] == $id) {
                $tree[] = $v;
                $id = $v['parent'];
                break;
            }
        }
    }
    return $tree;
}

print_r(tree($area,8));