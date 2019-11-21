<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\7\25 0025
 * Time: 19:35
 */
$arr=['aa','bb','cc','dd'];
//$one=each($arr);
//print_r($one);
/*while (list($a,$b)=each($arr)){
    echo "$a=>$b <br/>";
}*/
$a=11;
try{
   if($a==1){
       echo "true";
   }else {

       throw new Exception('一r');

   }
}catch (Exception $e){
    echo $e->getMessage();

}