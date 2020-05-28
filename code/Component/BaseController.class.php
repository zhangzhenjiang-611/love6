<?php

/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/14
 * Time: 14:24
 */
namespace Component;
use Think\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $now_ac = CONTROLLER_NAME . "-" . ACTION_NAME;
        echo $now_ac;
    }

}