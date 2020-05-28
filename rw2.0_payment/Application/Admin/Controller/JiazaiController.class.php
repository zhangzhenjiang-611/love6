<?php
namespace Admin\Controller;
use Think\Controller;
class JiazaiController extends Controller {
    public function index(){
        $this->display();
    }
    public function getinfo(){
        echo phpinfo();
    }
}