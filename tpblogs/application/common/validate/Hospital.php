<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/19
 * Time: 13:44
 */

namespace app\common\validate;


use think\Validate;

class Hospital extends Validate
{
    protected $rule = [
        'hos_name|医院名称' => 'require'
    ];

    public function sceneAdd() {
        return $this->only(['hos_name','type','public_type','pro','city','area']);
    }

}