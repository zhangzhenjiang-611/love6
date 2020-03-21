<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/18
 * Time: 22:14
 */
//递归打印级联目录
  function recdir($path,$lev=1) {
      $dh = opendir($path);



      while ( ($row = readdir($dh) ) !== false) {
          //如果$row还是目录，递归
          if($row == '.' || $row == '..') {
              continue;
          }
          echo str_repeat('&nbsp;&nbsp;',$lev).$row."<br/>";
          if(is_dir($path.'/'.$row)) {
              recdir($path.'/'.$row,$lev + 1);
          }
      }

      closedir($dh);
  }
      recdir('./');