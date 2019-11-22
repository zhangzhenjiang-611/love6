<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\11\9 0009
 * Time: 15:04
 */

 function joins($join = '')
{
    if ($join != '') {
        $a = ' '.$join.' ';
    }

	
    return $a;
}
  print_r(joins('user'));