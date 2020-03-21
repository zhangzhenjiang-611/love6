<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/15
 * Time: 14:04
 */
//字符串面试题
//字符串反转
    $str = "hello poujkl";
    function fan($str) {
        $n = strlen($str) - 1;
        $nstr="";
        for ($i = $n; $i >= 0; $i--){
            $nstr .= $str{$i};
        }
        return $nstr;
    }
    echo fan($str);
    //自定义函数 ,实现number_format
    echo "<br/>";
  //  echo number_format(1234567890); //1,234,567,890


//$str = 1234567890;
    function nformat($str){
        $str2 = "";
        $n = strlen($str);
        $k = $n % 3;
        for( $i = 0; $i < $n; $i++){
            if( $i%3 == $k && $i != 0) {
                $str2 .= ",";
            }
            $str2 .= $str{$i};
        }
        return $str2;
    }

echo "<br/>";
echo nformat("12345674646754789"); //1,234,567,890