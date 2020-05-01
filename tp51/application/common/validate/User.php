<?php

namespace app\common\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'name'    =>   'require|max:5',
        'price'   =>   'number|between:1,100',
        'email'   =>   'email'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '用户名不得为空'
    ];

    //场景验证

    protected $scene = [
        'insert' => ['name','price','email'],
        'edit'   => ['name','price']
    ];
}
