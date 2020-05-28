<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/2
 * Time: 9:24
 */

namespace app\index\controller;


use think\Image;

class Photo
{
    public function index() {
        $img = Image::open('img.jpg');
        echo $img->width();
        echo $img->height();
        echo $img->type();

        //$img->crop('500','350','10','10','100','100')->save('crop1.png');
        $img->thumb('500','500')->save('crop2.png');
        //旋转180
        $img->rotate('180')->save('crop3.png');
    }

}