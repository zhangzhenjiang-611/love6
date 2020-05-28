<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/12
 * Time: 21:31
 */

namespace app\common\validate;


use think\Validate;

class AuthGroup extends Validate
{
    protected $rule = [
        'title' => 'require',
        'status' => 'require'
    ];

    //添加场景
    public function  sceneAdd() {
        return $this->only(['title','status']);
    }

    //验证修改
    public function sceneEdit() {
        return $this->only(['title','status']);
    }

}