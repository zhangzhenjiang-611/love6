<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/1
 * Time: 20:44
 */
/*
 * 递归重组几点信息为多维数组
 * */
   function nodeMerge($node,$access = null,$pid = 0) {
      $arr = array();

        foreach ($node as $v) {
            if(is_array($access)) {
                $v['access'] = in_array($v['id'],$access) ? 1 :0;
            }
            if($v['pid'] == $pid) {
                $v['child'] = nodeMerge($node,$access,$v['id']);
                $arr[] = $v;
            }
        }
        return $arr;
   }


   function say() {
       echo 333;
   }