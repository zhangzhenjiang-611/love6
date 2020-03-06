<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/3
 * Time: 15:46
 */
$a = ['os'=>'linux','webserver'=>'apache','aa'=>'apache','bb'=>10,'cc'=>'10','db'=>'mysql','language'=>'php'];
print_r($a);
echo "<br/>";
array_values($a);
print_r($a); //原来的数组不会改变
print_r(array_keys($a));
print_r(array_keys($a,'apache')); //值不会覆盖, Array ( [0] => webserver [1] => aa )
print_r(array_keys($a,'10')); //不严格，类型无要求 Array ( [0] => bb [1] => cc )
print_r(array_keys($a,'10',true)); //严格，类型有要求Array ( [0] => cc )