<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/21
 * Time: 14:44
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

function tree($arr,$parent = 0) {
    $task = [$parent]; //任务表
    $tree = []; //地区表
    while(!empty($task)) {
        $flag = false;
        foreach ($arr as $k=>$v) {
            if($v['parent'] == $parent) {
                $tree[] = $v;
                array_push($task,$v['id']); //把最新的地区ID入栈
                $parent = $v['id'];
                unset($arr[$k]);   // 把站到的单元unset

                $flag = true;
            }

        }
        if($flag == false) {
            array_pop($task);
            $parent = end($task);
        }
        echo "<pre>";
        print_r($task);
    }
    return $tree;
}
echo "<pre>";
print_r(tree($area,0));