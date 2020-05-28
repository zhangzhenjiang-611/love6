<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/18
 * Time: 23:17
 */

namespace app\common\validate;


use think\Validate;

class AuthRule extends Validate
{
    protected $rule = [
        'pid' => 'require',
        'title|权限名称' => 'require',
        'name|控制器方法名称' => 'require'

    ];

    //添加场景
    public function  sceneAdd() {
        return $this->only(['pid','title','name']);
    }

    //验证修改
    public function sceneEdit() {
        return $this->only(['pid','title','name']);
    }

}