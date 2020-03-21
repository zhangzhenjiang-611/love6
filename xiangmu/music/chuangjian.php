<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/18
 * Time: 15:00
 */
//创建目录
foreach (['aa','bb','cc'] as $v) {
    $path = './music'.$v;
    if(file_exists($path) && is_dir($path)){
        echo "目录已经存在";
        continue;

    }
    if(mkdir($path)) {
        echo "目录创建成功";
        echo "<br>";
    }
}