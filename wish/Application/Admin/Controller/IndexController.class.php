<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
use Think\Log;

class IndexController extends BaseController {
    public function send(){
        //传入参数
        $data = array(
            'openid' => '',                //微信授权后获取的open_id
            'url'    => '',               //点击后跳转的地址
            'title'=>'',                  //通知内容
            'info'=>'',                   //通知信息
            'points'=>'',                   //价格
            'remark'=>''                 //备注说明
        );
        Vendor('Message.Message');
        $send = new \Message();
        $send->sendTempleMessage($data);

    }
    /*
     * 退出登录
     * */
    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('Admin/Login/index');
    }

    public  function text() {
     echo 123;
    }

    public function log() {
        Log::write('测试日志');
    }

    public function access() {
        echo 'access';
    }
}