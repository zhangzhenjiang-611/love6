<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/5
 * Time: 15:09
 */

namespace app\common\validate;


use think\Validate;

class Category extends Validate
{
    //验证规则
    protected $rule = [
        'catename|栏目名称'  => 'require|unique:category',
        'sort|排序'  => 'require|number'
    ];

    //验证场景
    public function sceneAdd() {
        return $this->only(['catename','sort']);
    }

    //验证排序
    public function sceneSort() {
        return $this->only(['sort']);
    }

    //验证修改栏目名称
    public function sceneEdit() {
        return $this->only(['catename']);
    }

}