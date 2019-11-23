<?php
/**
 * 主配置文件
 *
 * @copyright Copyright 2012-2017, BAONAHAO Software Foundation, Inc. ( http://api.baonahao.com/ )
 * @link http://api.baonahao.com api(tm) Project
 * @author gaoxiang <gaoxiang@xiaohe.com>
 */
return [
    /**
     * 是否开启监测数据库sql
     * @var false=不监测 true=监测
     * @descript 开启监测db，监测日志记录至 /tmp/log/ 目录下execute_sql.log文件
     */
    'LISTEN_DB' => true,

    /**
     * 是否开启token验证
     * @var false=不开启 true=开启
     * @descript 默认不开启
     */
    'IS_TOKEN' => false,

    /**
     * 是否开始签名验证
     * @var false=不开启 true=开启
     * @descript 默认开启
     */
    'IS_SIGN'  => false,

    /**
     * 是否开始标识验证
     * @var false=不开启 true=开启
     * @descript 默认开启
     */
    'IS_IDENTY'  => true,

    /**
     * 平台授权码
     * @var 平台key => 安全码
     * @descript 开启签名验证时生效
     */
    'AUTH_PLATFORM'     => [
        'cb48836df7d1a60b2bc7e06ea8072245' => 'j!B=Z]MozvXnW(i5MEXn6)WGo),MC!C+z3=qG-6$Ufg)UGn3(oS0OE).p(puSqGA'
    ],

    'DB_SECRET_KEY' => 'I8feVXtTO0mv42QB9omOjvV9JyunNrZb',

    /**
     * 域名配置
     */
    'DOMAIN_NAME' => [
        'image' => 'http://file.baonahao.com.cn', //图片服务器
        'file'  => 'http://file.baonahao.com.cn', //文件服务器
    ],

    /**
     * 数据库数据加密秘钥
     */
    'DB_SECURITY_CODE'    => 'I8feVXtTO0mv42QB9omOjvV9JyunNrZb',
    'UPLOAD_PATH'         => '/tmp/upload/',
    'MERCHANT_REPORT_URL' => 'http://webview.m.dev.xiaohe.com/view/Topic/merchantReport2017.html',

    /**
     * 万能Token
     */
    'WN_TOKEN' => 'Uk2dn3bDHEJoqP0w8ZXk2h0jSVnnxKVS',

    /**
     * 万能Sign
     */
    'WN_SIGN'  => 'THYhCXv0qDhO0M4MDee7HV28PVmcEnNI',

    /**
     * 万能Identy
     */
    'IS_IDENTY' => 'lNvILPm7S9g3X83yYkbqVoI9ZJSxm4OK',
];