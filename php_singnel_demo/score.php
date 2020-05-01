<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/21
 * Time: 15:50
 */
$json = '{
"score":59,
"address":{
"score":60
}
}';
$arr = json_decode($json,true);

  function check($json) {
      $arr = json_decode($json,true);
      if(is_array($arr)) {
          foreach ($arr as $k=>$v) {
              if($k == 'score' && $v > 60) {
                  echo 1;
                  continue;
              }
              check($v);
          }
      }
  }