<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/14
 * Time: 14:29
 */
namespace Manage\Controller;

use Think\Controller;

class BaseController extends Controller {

    public function __construct()
    { 
        parent::__construct();
        $con_method = CONTROLLER_NAME . "-" . ACTION_NAME;
        $access_list = [];
       foreach ($_SESSION['_ACCESS_LIST'] as $k=>$v) {
          foreach ($v as $key=>$val) {
                   foreach ($val as $keys=>$value) {
                       $access_list[] = $key.'-'.$keys;
                   }

               }
       }
       if (in_array(strtoupper($con_method),$access_list)) {
           echo '有权限';
       } else {
           echo '无权限';
       }

    }

}