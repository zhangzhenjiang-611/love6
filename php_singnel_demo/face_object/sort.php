<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/9
 * Time: 11:29
 */
$file = file_get_contents('file.txt');
$file =  str_replace(PHP_EOL, '', $file);
$arr = explode(' ',$file);
$brr = [];
for($i = 0; $i<count($arr); $i++){
    if(strlen($arr[$i]) == 1){
        $brr[] =$arr[$i];
    }else{
        $brr[] = $arr[$i]{0};
        $brr[] = $arr[$i]{1};
    }
}

$crr = [];
 for($i = 0;$i < count($brr);$i++){
     if($i == 1 || ($i - (4*($i - 1))) ==  1){
         $crr[] = $brr[$i];
   }

 }
 print_r($crr);