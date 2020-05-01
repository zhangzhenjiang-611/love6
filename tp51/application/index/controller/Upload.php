<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/1
 * Time: 21:25
 */

namespace app\index\controller;


use think\facade\Request;

class Upload
{
    //文件上传 单一
    public function index() {
        $file = Request::file('image');
        $info = $file->validate([
            'size' => 102400,
            'ext'  => 'png,gif,jpg'
        ])->move('../application/uploads');

            if ($info) {
                echo "<br>";
                echo $info->getExtension();
                echo "<br>";
                echo $info->getSaveName();
                echo "<br>";
                echo $info->getFileName();
                echo "<br>";
            } else {
                return $file->getError();
            }



    }

    //文件上传多

    public function uploads() {
        $files = Request::file('image');
        foreach ($files as $file) {
            $info = $file->move('../application/uploads');
            if ($info) {
                echo "<br>";
                echo $info->getExtension();
                echo "<br>";
                echo $info->getSaveName();
                echo "<br>";
                echo $info->getFileName();
                echo "<br>";
            } else {
                $file->getError();
            }
        }

    }

}