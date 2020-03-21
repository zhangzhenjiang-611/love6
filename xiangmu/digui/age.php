<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/20
 * Time: 15:47
 */
 $age =10;
  function grow($age) {
      $age++;
      return $age;
  }
  echo grow($age); //把10传进去
  echo "<br/>";
  echo $age;
echo "<br/>";
/*11
10


11
11*/
// 传地址
$age =10;
function grows(&$age) {
    $age++;
    return $age;
}
echo grows($age); //把10传进去
echo "<br/>";
echo $age;