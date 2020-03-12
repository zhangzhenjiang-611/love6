<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/12
 * Time: 12:18
 */
class Triangle extends Shape{
    //声明三条边
    private $b1;
    private $b2;
    private $b3;
    public function __construct($arr = [])
    {
        if(!empty($arr)){
            $this->b1 = $arr['b1'];
            $this->b2 = $arr['b2'];
            $this->b3 = $arr['b3'];
        }
        $this->name = '三角形';
    }
    public function area()
    {
        // TODO: Implement area() method.
        //海伦公式
        $p = $this->zhou()/2;
        //开方
        return sqrt($p * ($p - $this->b1) * ($p - $this->b2) * ($p - $this->b3));
    }
    public function zhou()
    {
        // TODO: Implement zhou() method.
        return  $this->b1 + $this->b2 + $this->b3;
    }
    public function view()
    {
        // TODO: Implement view() method.
        $form = '<form action="index.php?action=triangle" method="post">';
        $form .= $this->name.'边1是: <input type="text" name="b1" value="'.$_POST['b1'].'" /><br>';
        $form .= $this->name.'边2是: <input type="text" name="b2" value="'.$_POST['b2'].'" /><br>';
        $form .= $this->name.'边3是: <input type="text" name="b3" value="'.$_POST['b3'].'" /><br>';
        $form .= '<input type="submit" name="dosubmit" value="提交" /><br>';
        $form .= '</form>';
        echo $form;
    }
    public function confirm($arr)
    {
        // TODO: Implement confirm() method.
        $bj = true;
        if($arr['b1'] <= 0){
            echo "边1不能小于0"."<br/>";
            $bj = false;
        }
        if($arr['b2'] <= 0){
            echo "边2不能小于0"."<br/>";
            $bj = false;
        }
        if($arr['b3'] <= 0){
            echo "边3不能小于0"."<br/>";
            $bj = false;
        }
        if(($arr['b1'] + $arr['b2'] <= $arr['b3']) || ($arr['b1'] + $arr['b3'] <= $arr['b2']) || ($arr['b2'] + $arr['b3'] <= $arr['b1'])) {
            echo "三角形两边之和必须大于第三边";
            $bj = false;
        }
        return $bj;
    }
}