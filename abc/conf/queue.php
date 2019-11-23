<?php
/**
 * 队列配置
 *
 * PHP versions 5.6
 *
 * @copyright Copyright 2012-2016, BAONAHAO Software Foundation, Inc. ( http://api.baonahao.com/ )
 * @link http://api.baonahao.com api(tm) Project
 * @package api
 * @subpackage api/config
 * @date 2018-08-24
 * @author yangshuaishuai <yangshuaishuai@xiaohe.com>
 */
return array(
    //教学系统对接-异步通知
    'teaching_async_notify' => [
        'host'      => '192.168.1.10',
        'port'      => '5672',
        'username'  => 'teaching_mq',
        'password'  => '123456',
        'vhost' => 'teaching_async_notify',
    ],
);