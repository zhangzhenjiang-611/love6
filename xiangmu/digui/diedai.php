<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/19
 * Time: 16:43
 */
//递归
  function recsum($n) {
      if($n > 1) {
          return $n + recsum($n - 1);
      } else{
          return 1;
      }
  }

   //echo recsum(255);
  //迭代创建级联目录 ./a/b/c/d
//创建步骤从浅到深，列成单子，一层一层创建
function mk_dir($path) {
    $arr = [];

    while(!is_dir($path)) {
        // /a/b/c/d
        array_unshift($arr,$path); // 往数组头部添加元素
        $path = dirname($path);
    }
   /* echo "<pre>";
print_r($arr);*/
     if(empty($arr)) {
         echo  1;
         return;
     }
      foreach ($arr as $v) {
         echo '创建'.$v;
         echo "<br>";
         mkdir($v);
      }

}

mk_dir('./a/b/c/d/e');
