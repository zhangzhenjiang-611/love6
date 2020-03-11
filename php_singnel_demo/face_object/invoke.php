<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/10
 * Time: 13:19
 */
class Article{
    public $name;

    public function eat(){

    }
    public function __invoke($a,$c)
    {
        // TODO: Implement __invoke() method.
        echo 111;
        echo $a;
    }
    public static function __callstatic($a,$b){
      echo 333;
    }
}
$b = new Article();
$b('aa','cc');
Article::say();