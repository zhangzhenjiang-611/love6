<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/6
 * Time: 0:49
 */
//回调函数

 $arr = [
     'aaaaaaa','bb','cccccccccccccc','d','eeeeeeee','fffffffffffffffff','gg'
 ];
 usort($arr,'sortLength'); //按字符串的长度从小到大排序
 var_dump($arr);
/*function sortLength($a,$b){
    if(strlen($a) > strlen($b)){
        return 1;
    }else if(strlen($a) < strlen($b)){
        return -1;
    }else{
        return 0;
    }
}*/

function sortLength($a,$b){
    return strlen($a) > strlen($b) ? 1 : -1;
}
 //var_dump($arr);

function Compare($str1, $str2) {
         if (($str1 % 2 == 0) && ($str2 %2 == 0)) {
                 if ($str1 > $str2)
                         return - 1;
          else
              return 1;
      }
      if ($str1 % 2 == 0)
                 return 1;
     if ($str2 % 2 == 0)
                return -1;
     return ($str2 > $str1) ? 1 : - 1;
 }
 $scores = array (22,57,55,12,87,56,54,11);
 usort ( $scores, 'Compare' );
 print_r ( $scores );