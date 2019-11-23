<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\8\20 0020
 * Time: 19:47
 */
$conn=@mysqli_connect('localhost','root','');
if($conn){
    //echo "success";
    mysqli_select_db($conn,'student');
    $result=mysqli_query($conn,'select * from user');
    var_dump($result);
}else{
    echo "failed";
}