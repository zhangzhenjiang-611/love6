<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/28
 * Time: 11:03
 */
  /*
   * 发布内容表情替换*/
function replace_phiz($content) {
      preg_match_all('/\[.*?\]/is',$content,$arr);
      if ($arr[0]) {
          $phiz = F('phiz','','./Data/');
         // dump($phiz);
          //die();
          foreach ($arr[0] as $v) {
             // echo $v;
             // die();
              foreach ($phiz as $key=>$val) {
                  if ($v == '['.$val.']') {
                      $content = str_replace($v,'<img src="' .__ROOT__. '/Public/home/Images/phiz/' .$key. '.gif"/>',$content);
                      break;
                  }
                 // continue;
              }
          }
          return $content;
      }

  }