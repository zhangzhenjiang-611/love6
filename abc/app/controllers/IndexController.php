<?php
use Phalcon\Mvc\Controller;

// Index控制器类 必须继承Controller
class IndexController extends Controller {

    // 默认Action
    public function indexAction() {
        echo 111;
    }

}
