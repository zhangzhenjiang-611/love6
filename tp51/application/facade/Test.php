<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/4/28
 * Time: 17:06
 */

namespace app\facade;


use think\Facade;

class Test extends Facade
{
    //绑定
    protected static function getFacadeClass()
    {
        //parent::getFacadeClass(); // TODO: Change the autogenerated stub
        return 'app\common\Test';
    }


}