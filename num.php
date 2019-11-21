<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\8\2 0002
 * Time: 15:47
 */
$a=10000000;
$b = number_format( $a, 2, '.', ',' );
echo $b;
echo "<br/>";
echo str_replace( ',', '', $b );;
function a( $a ) {
    list($int, $dot) = explode( '.', $a );
    $str = '';
    $length = strlen( $int );
    $pos = 0;
    for ( $i = $length - 1; $i >= 0; $i-- ) {
        $delimiter = $pos % 3 == 0 && $pos != 0 ? ',' : '';
        $str = $int{$i} . $delimiter . $str;
        $pos++;
    }
    return $str . '' . $dot;
}
//echo "<br/>";
//echo a($b);