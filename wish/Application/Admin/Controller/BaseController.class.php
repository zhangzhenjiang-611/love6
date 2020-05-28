<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/3/28
 * Time: 15:32
 */
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
  class BaseController extends  Controller{
      public function _initialize() {
         /* if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
              $this->redirect('Admin/Login/index');
          }*/
          $noAuth = in_array(CONTROLLER_NAME,explode(',',C('NOT_AUTH_CONTROLLER'))) || in_array(ACTION_NAME,explode(',',C('NOT_AUTH_ACTION')));
        /*  if(C('USER_AUTH_ON') && !$noAuth) {
              Rbac::AccessDecision() || $this->error('没有权限');
          }*/

      }

  }
