<?php

/**
 * 业务模块配置
 * 
 * @author yangshuaishuai <yangshuaishuai@xiaohe.com>
 * @date 2019-05-06 18:00
 */
return [
    // 业务模块映射关系
    // 当前没有数据库表维护，使用配置文件代替
    'business_type_map' => [
        '1' => '班课',
        '2' => '一对一',
        '3' => '单独商品',
        '4' => '账户',
        '5' => '艺体课',
        '6' => '套餐',
        '7' => '课时包',
        // '8' => '云课堂直播',
        '9' => '全日制',
    ],
    // 业务模块和eduwork.bi_merchant_parent_flows.type关联关系
    // 由于 页面有自定的字段值，而数据库没有，需要配置文件匹配
    'business_type_id_type_map' => [
        '1' => [
            BiMerchantParentFlowsModel::TYPE_OTM_PAY, 
            BiMerchantParentFlowsModel::TYPE_OTM_REPAY, 
            BiMerchantParentFlowsModel::TYPE_QUIT_REPAY, 
            BiMerchantParentFlowsModel::TYPE_QUIT_REFUND, 
            BiMerchantParentFlowsModel::TYPE_FINISH_REFUND, 
            BiMerchantParentFlowsModel::TYPE_DISCOUNT_REFUND, 
            BiMerchantParentFlowsModel::TYPE_TRANSFER_REPAY, 
            BiMerchantParentFlowsModel::TYPE_TRANSFER_REFUND, 
        ], 
        '2' => [
            BiMerchantParentFlowsModel::TYPE_OTO_PAY,
            BiMerchantParentFlowsModel::TYPE_OTO_REPAY,
            BiMerchantParentFlowsModel::TYPE_OTO_REFUND,
        ],
        '3' => [
            BiMerchantParentFlowsModel::TYPE_TEXTBOOK_PAY,
            BiMerchantParentFlowsModel::TYPE_TEXTBOOK_REFUND,
        ],
        '4' => [
            BiMerchantParentFlowsModel::TYPE_ACCOUNT_PAY,
            BiMerchantParentFlowsModel::TYPE_ACCOUNT_REFUND,
        ],
        '5' => [
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE,
            BiMerchantParentFlowsModel::TYPE_TRANSFER_ROLL_COURSE,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE_TRANSFER_REFUND,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE_REPAY,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE_REFUND,
            BiMerchantParentFlowsModel::TYPE_ROLL_CLASS_REFUND,
        ],
        '6' => [
            BiMerchantParentFlowsModel::TYPE_OTM_PACKAGE,
            BiMerchantParentFlowsModel::TYPE_OTO_PACKAGE,
        ],
        '7' => [
            BiMerchantParentFlowsModel::TYPE_VIRTUAL_PAY,
            BiMerchantParentFlowsModel::TYPE_VIRTUAL_REPAY,
            BiMerchantParentFlowsModel::TYPE_VIRTUAL_REFUND,
        ],
        '8' => [
            BiMerchantParentFlowsModel::TYPE_LIVE_PAY,
            BiMerchantParentFlowsModel::TYPE_LIVE_REFUND,
        ],
        '9' => [
            BiMerchantParentFlowsModel::TYPE_QRZ_PAY,
            BiMerchantParentFlowsModel::TYPE_QRZ_REFUND,
        ],
    ],

    // 业务模块和eduwork.bi_merchant_parent_flows.type关联关系
    // 由于 页面有自定的字段值，而数据库没有，需要配置文件匹配
    'fee_type_id_type_map' => [
        BiMerchantParentFlowsModel::FEE_TYPE_OTM_IN => [
            BiMerchantParentFlowsModel::TYPE_OTM_PAY,
            BiMerchantParentFlowsModel::TYPE_OTM_REPAY,
            BiMerchantParentFlowsModel::TYPE_QUIT_REPAY,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE,
            BiMerchantParentFlowsModel::TYPE_LIVE_PAY,
            BiMerchantParentFlowsModel::TYPE_QRZ_PAY,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE_REPAY,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_OTM_OUT => [
            BiMerchantParentFlowsModel::TYPE_QUIT_REFUND,
            BiMerchantParentFlowsModel::TYPE_FINISH_REFUND,
            BiMerchantParentFlowsModel::TYPE_DISCOUNT_REFUND,
            BiMerchantParentFlowsModel::TYPE_LIVE_REFUND,
            BiMerchantParentFlowsModel::TYPE_QRZ_REFUND,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE_REFUND,
            BiMerchantParentFlowsModel::TYPE_ROLL_CLASS_REFUND,
            BiMerchantParentFlowsModel::TYPE_TEXTBOOK_REFUND,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_TRANSFER_IN => [
            BiMerchantParentFlowsModel::TYPE_TRANSFER_REPAY,
            BiMerchantParentFlowsModel::TYPE_TRANSFER_ROLL_COURSE,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_TRANSFER_OUT => [
            BiMerchantParentFlowsModel::TYPE_TRANSFER_REFUND,
            BiMerchantParentFlowsModel::TYPE_TRANSFER_ROLL_COURSE,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE_TRANSFER_REFUND,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_OTO_IN => [
            BiMerchantParentFlowsModel::TYPE_OTO_PAY,
            BiMerchantParentFlowsModel::TYPE_OTO_REPAY,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_OTO_OUT => [
            BiMerchantParentFlowsModel::TYPE_OTO_REFUND,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_TEXTBOOK_IN => [
            BiMerchantParentFlowsModel::TYPE_TEXTBOOK_PAY,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_TEXTBOOK_OUT => [
            
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_ACCOUNT_IN => [
            BiMerchantParentFlowsModel::TYPE_ACCOUNT_PAY,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_ACCOUNT_OUT => [
            BiMerchantParentFlowsModel::TYPE_ACCOUNT_REFUND,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_PACKAGE_IN => [
            BiMerchantParentFlowsModel::TYPE_OTM_PACKAGE,
            BiMerchantParentFlowsModel::TYPE_OTO_PACKAGE,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_VIRTUAL_IN => [
            BiMerchantParentFlowsModel::TYPE_VIRTUAL_PAY,
            BiMerchantParentFlowsModel::TYPE_VIRTUAL_REPAY,
        ],
        BiMerchantParentFlowsModel::FEE_TYPE_VIRTUAL_OUT => [
            BiMerchantParentFlowsModel::TYPE_VIRTUAL_REFUND,
        ],
    ],

    // 缴费类型和eduwork.bi_merchant_parent_flows.type关联关系
    // 由于 页面有自定的字段值，而数据库没有，需要配置文件匹配
    'pay_type_id_type_map' => [
        BiMerchantParentFlowsModel::PAY_TYPE_NEW => [
            BiMerchantParentFlowsModel::TYPE_OTM_PAY,
            BiMerchantParentFlowsModel::TYPE_TEXTBOOK_PAY,
            BiMerchantParentFlowsModel::TYPE_OTO_PAY,
            BiMerchantParentFlowsModel::TYPE_TRANSFER_REPAY,
            BiMerchantParentFlowsModel::TYPE_OTM_PACKAGE,
            BiMerchantParentFlowsModel::TYPE_OTO_PACKAGE,
            BiMerchantParentFlowsModel::TYPE_VIRTUAL_PAY,
            BiMerchantParentFlowsModel::TYPE_ACCOUNT_PAY,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE,
            BiMerchantParentFlowsModel::TYPE_TRANSFER_ROLL_COURSE,
            BiMerchantParentFlowsModel::TYPE_LIVE_PAY,
            BiMerchantParentFlowsModel::TYPE_QRZ_PAY,
        ],
        BiMerchantParentFlowsModel::PAY_TYPE_REPAY => [
            BiMerchantParentFlowsModel::TYPE_OTM_REPAY,
            BiMerchantParentFlowsModel::TYPE_QUIT_REPAY,
            BiMerchantParentFlowsModel::TYPE_OTO_REPAY,
            BiMerchantParentFlowsModel::TYPE_VIRTUAL_REPAY,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE_REPAY,
        ],
        BiMerchantParentFlowsModel::PAY_TYPE_REFUND => [
            BiMerchantParentFlowsModel::TYPE_QUIT_REFUND,
            BiMerchantParentFlowsModel::TYPE_TRANSFER_REFUND,
            BiMerchantParentFlowsModel::TYPE_FINISH_REFUND,
            BiMerchantParentFlowsModel::TYPE_DISCOUNT_REFUND,
            BiMerchantParentFlowsModel::TYPE_VIRTUAL_REFUND,
            BiMerchantParentFlowsModel::TYPE_OTO_REFUND,
            BiMerchantParentFlowsModel::TYPE_ACCOUNT_REFUND,
            BiMerchantParentFlowsModel::TYPE_TRANSFER_ROLL_COURSE,
            BiMerchantParentFlowsModel::TYPE_LIVE_REFUND,
            BiMerchantParentFlowsModel::TYPE_QRZ_REFUND,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE_TRANSFER_REFUND,
            BiMerchantParentFlowsModel::TYPE_ROLL_COURSE_REFUND,
            BiMerchantParentFlowsModel::TYPE_ROLL_CLASS_REFUND,
            BiMerchantParentFlowsModel::TYPE_TEXTBOOK_REFUND,
        ],
    ]

];