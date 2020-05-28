<?php
/**
 * Created by PhpStorm .
 * User by 15691
 * Date by 2020/5/27
 * Time by 20:29
 */

namespace app\common\validate;


use think\Validate;

class Hospital extends Validate
{
    protected $rule = [
        'hos_name|医院名称'  => 'require'
    ];
    //场景新增
    public function sceneAdd() {
        return $this->only(['hos_name']);
    }
    //场景修改
    public function sceneEdit() {
        return $this->only(['hos_name']);
    }



}