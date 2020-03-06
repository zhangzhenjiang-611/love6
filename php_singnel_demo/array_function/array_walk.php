<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/5
 * Time: 11:20
 */
//array_walk 对数组中的 每个成员应用用户函数 返回bool
$arr = ['a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5];
 function myFun($val,$key){
     echo $val.$key."<br/>";
     //$val = $val*$val;

}
print_r($arr);
array_walk($arr,'myFun');
print_r($arr);
echo "<hr/>";
array_walk($arr,function($val,$key,$str){
    echo $val.$key.$str."<br/>";
},'###');
print_r($arr);
