<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/11
 * Time: 16:26
 */
//矩形 是一个矩形的类 要按形状的规范去实现
class Juxing extends Shape {
    private $width;  //宽度
    private $height; //高度
    public function __construct($arr = [])
    {
        if(!empty($arr)){
            $this->width = $arr['width'];
            $this->height = $arr['height'];
        }
        $this->name = '矩形';

    }

    //求面积
    public function area(){
        return $this->width * $this->height;

    }
    //求周长
    public function zhou(){
        return 2 * ($this->width + $this->height);

    }
    //图形表单界面
    public function view(){
        $form = '<form action="index.php?action=juxing" method="post">';
        $form .= $this->name.'的宽度是: <input type="text" name="width" value="'.$_POST['width'].'" /><br>';
        $form .= $this->name.'的高度是: <input type="text" name="height" value="'.$_POST['height'].'" /><br>';
        $form .= '<input type="submit" name="dosubmit" value="提交" /><br>';
        $form .= '</form>';
        echo $form;

    }
    //形状验证
    public function confirm($arr){
        $bg = true;
        if($arr['width'] <=0){
            echo $this->name."的宽度不能小于0<br>";
            $bg = false;
        }
        if($arr['height'] <=0){
            echo $this->name."的高度不能小于0<br>";
            $bg = false;
        }
        return $bg;
    }
}
