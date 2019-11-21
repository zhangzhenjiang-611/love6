<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\10\21 0021
 * Time: 16:33
 */
    /*function createRange($number){
        $data = [];
        for($i=0;$i<$number;$i++){
            $data[] = time();
        }
        return $data;
    }*/
function createRange($number){
    for($i=0;$i<$number;$i++){
        yield time();
    }
}
$result = createRange(10);
    foreach ($result as $v){
        //sleep(1);
        //echo $v."<br/>";
    }
    //echo count($result);
    echo "<hr/>";
//array_intersect() 函数用于比较两个（或更多个）数组的键值，并返回交集
    $arr1=['aa','bb','cc'];
    $arr2=['aa','bb','ee'];
    print_r(array_intersect($arr1,$arr2));
/**
 * Method bb
 * @user:zhangzhenjiang <zhangzhenjiang@xiaohe.com>
 * Time: {$DATE} {$TIME}
 * @description
 */
    function bb(){

    }