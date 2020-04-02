<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/16
 * Time: 17:26
 */

require ('./include/init.php');
/*$conf = Conf::getIns();
//读取
//echo $conf->host;
Log::write('记录');
class mysql {
    public function query($sql) {
        //查询 记录
        Log::write($sql);
    }
}

   $mysql = new mysql();
   for($i = 0; $i < 10000; $i++){
       $sql = "select goods_id,goods_name,price from goods where goods_id=".mt_rand(1,1000);
       $mysql->query($sql);
   }
   echo "执行完毕";*/
//测试递归转义
//print_r($_GET);
$my = mysql::getIns();
var_dump($my);