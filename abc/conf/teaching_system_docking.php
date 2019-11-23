<?php
/**
 * 教学系统对接配置
 *
 * @date 2018-86-24 
 * @author yangshuaishuai <yangshuaishuai@xiaohe.com>
 */
/**
 * 数据结构说明
 * 商家ID => [
 *      业务名称 => 是否启用【true or false】
 * ]
 */
//研发环境
return [
    //商家名称：厉害了我的哥
    'a7e431fbeb2b805b38180cd2ca8d1d27' => [
        // 年部年级设置
        'grade'             => true,
        // 课程分类
        'category_merchant' => true,
    ],
    'open' => 1,//是否开启对接 1是 0否
];