<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/28
 * Time: 17:17
 */

namespace app\behavior;


class Test
{/*
    public function run($params){
        echo $params.'只要出发就执行';
    }*/

    public function appInit(){
        //echo '初始化触发';
    }

    public function eat($params){
       // echo $params.'触发';
    }

}