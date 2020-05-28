<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/19
 * Time: 14:23
 */

namespace app\common\validate;


use think\Validate;

class Nature extends Validate
{
    protected $rule = [
        'type|医院属性' => 'require',
        'public_type|公私属性' => 'require',
        'pro|省份' => 'require',
        'city|城市' => 'require',
        'area|医院名称' => 'require'
    ];

    public function sceneAdd() {
        return $this->only(['type','public_type','pro','city','area']);
    }

}