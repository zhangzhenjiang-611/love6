<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Admin extends Model
{
    //引入软删除
    use SoftDelete;

    //只读字段
    protected $readonly = ['email'];

    //登录校验
    public function login($data) {
        $validate = new \app\common\validate\Admin();
        if(!$validate->scene('login')->check($data)) {
           return $validate->getError();
        }

        $result = $this->where($data)->find();

        if ($result) {
            //判断是否禁用
            if($result['status'] !=1) {
                return '用户账户被禁用!';
            }
            //写入session
            $sessionData = [
                'id'        => $result['id'],
                'nickname'  => $result['nickname'],
                'is_super'  => $result['is_super'],
            ];
            session('admin',$sessionData);
            //用户名和密码匹配
            return 1;
        } else {
            return '用户名或密码错误!';
        }

    }

    //用户注册
    public function register($data) {
        $validate = new \app\common\validate\Admin();
        if (!$validate->scene('register')->check($data)) {
            return $validate->getError();
        }
        //只插入数据库中有的字段
        $result = $this->allowField(true)->save($data);
        if ($result) {
            //发邮件
            sendEmail($data['email'],'注册成功','注册成功');
            return 1;
        } else {
            return '注册失败';
        }

    }

    //重置密码
    public function reset($data) {
        $validate = new \app\common\validate\Admin();
        if(!$validate->scene('reset')->check($data)) {
            return $validate->getError();
        }
        //校验验证码
        if ($data['code'] != session('code')) {
            return '验证码不正确';
        }
        $adminInfo = $this->where('email',$data['email'])->find();
        $password = mt_rand(100000,999999);
        $adminInfo->password = $password;
        $result = $adminInfo->save();
        if ($result) {
            $content = '恭喜你，密码重置成功!<br>用户名：'.$adminInfo['username'].'<br>新密码：'.$password;
            sendEmail($adminInfo['email'],'密码重置成功',$content);
            return 1;
        } else {
            return '重置密码失败';
        }

    }
}
