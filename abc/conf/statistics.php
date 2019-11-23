<?php

/**
 * 此配置的作用是便于统计支付渠道及其他关联业务的数据
 * 目前状况:
 * 每个支付渠道的进账记录是一张mysql表来记录
 * 每个业务订单是一张mysql表来记录
 * 
 * 缺点:
 * 做数据统计的接口每次因上面表的变动都需要调整代码。
 * 
 * 解决方案：
 * 采用配置式, 通过维护配置代替维护代码
 * 采用此配置只要增加相关的模型类和服务即可
 * 目前有个人结算和校区结算用到了此配置
 * 收入流水也用到了次配置
 * 
 * @author yangshuaishuai <yangshuaishuai@xiaohe.com>
 * @date 2019-04-16 18:00
 */
return [
    // 支付渠道进账记录
    'campus_recharge_map' => [
        // recharge_type => model类
        // 新增类注意定义 CHANNEL_NAME 常量
        ParentAccountRecordDetailOrderModel::PAY_WAY_CASH         => ParentAccountRecordDetailRechargeCashModel::class, // 现金
        ParentAccountRecordDetailOrderModel::PAY_WAY_POS_CARD     => ParentAccountRecordDetailRechargePosModel::class, // 刷卡POS
        ParentAccountRecordDetailOrderModel::PAY_WAY_POS_CODE     => ParentAccountRecordDetailRechargePosModel::class, // 扫码POS
        ParentAccountRecordDetailOrderModel::PAY_WAY_CHEQUES      => ParentAccountRecordDetailRechargeChequesModel::class, // 支票
        ParentAccountRecordDetailOrderModel::PAY_WAY_WEIXIN       => ParentAccountRecordDetailRechargeWeixinModel::class, // 微信
        ParentAccountRecordDetailOrderModel::PAY_WAY_ALIPAY       => ParentAccountRecordDetailRechargeAlipaysModel::class, // 支付宝
        ParentAccountRecordDetailOrderModel::PAY_WAY_BANKTRANSFER => ParentAccountRecordDetailRechargeBanktransfersModel::class, // 银行转账
        // 二维码付款不展示
        //ParentAccountRecordDetailOrderModel::PAY_WAY_QRCODE       => ParentAccountRecordDetailRechargeQrcodesModel::class, // 二维码POS
        // 学费宝有多重支付方式，用其中一种来表示学费宝
        ParentAccountRecordDetailOrderModel::PAY_WAY_XFB_SCAN_CODE_ALIPAY => ParentAccountRecordDetailRechargeXuefeibaoModel::class, // 学费宝
    ],
    'person_recharge_map' => [
        // recharge_type => model类
        // 新增类注意定义 CHANNEL_NAME 常量
        ParentAccountRecordDetailOrderModel::PAY_WAY_CASH         => ParentAccountRecordDetailRechargeCashModel::class, // 现金
        ParentAccountRecordDetailOrderModel::PAY_WAY_POS_CARD     => ParentAccountRecordDetailRechargePosModel::class, // 刷卡POS
        ParentAccountRecordDetailOrderModel::PAY_WAY_POS_CODE     => ParentAccountRecordDetailRechargePosModel::class, // 扫码POS
        ParentAccountRecordDetailOrderModel::PAY_WAY_CHEQUES      => ParentAccountRecordDetailRechargeChequesModel::class, // 支票
        ParentAccountRecordDetailOrderModel::PAY_WAY_WEIXIN       => ParentAccountRecordDetailRechargeWeixinModel::class, // 微信
        ParentAccountRecordDetailOrderModel::PAY_WAY_ALIPAY       => ParentAccountRecordDetailRechargeAlipaysModel::class, // 支付宝
        ParentAccountRecordDetailOrderModel::PAY_WAY_BANKTRANSFER => ParentAccountRecordDetailRechargeBanktransfersModel::class, // 银行转账
        // 二维码付款不展示
        //ParentAccountRecordDetailOrderModel::PAY_WAY_QRCODE       => ParentAccountRecordDetailRechargeQrcodesModel::class, // 二维码POS
        // 学费宝有多重支付方式，用其中一种来表示学费宝
        ParentAccountRecordDetailOrderModel::PAY_WAY_XFB_SCAN_CODE_ALIPAY => ParentAccountRecordDetailRechargeXuefeibaoModel::class, // 学费宝
        // ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_XFB_ALPAY => ParentAccountRecordDetailRechargesModel::class,
    ],
    'recharge_online_map' => [
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_ALIPAY => ParentAccountRecordDetailRechargesModel::class, // 线上支付宝
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_WEIXIN => ParentAccountRecordDetailRechargesModel::class, // 线上微信
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_XFB_ALPAY => ParentAccountRecordDetailRechargesModel::class, // 线上银联支付宝
    ],
    
    /**
     * 支付渠道对应的服务
     * 如果支付渠道统计数据方法和常规的不一致，需要重写服务子类方法来计算
     * 常规的使用父类App\Service\PayChannelStatistics\BaseService计算就可以
     */
    'statistics_service_map' => [
        // 14 => App\Service\PayChannelStatistics\AlipayService::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_POS_CARD  => App\Service\PayChannelStatistics\PosCardService::class, // 刷卡POS
        ParentAccountRecordDetailOrderModel::PAY_WAY_POS_CODE  => App\Service\PayChannelStatistics\PosCodeService::class, // 扫码POS
        ParentAccountRecordDetailOrderModel::PAY_WAY_XFB_SCAN_CODE_ALIPAY => App\Service\PayChannelStatistics\XuefeibaoService::class, // 学费宝
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_XFB_ALPAY => App\Service\PayChannelStatistics\UnionpayAlipayOnlineService::class, // 银联-线上-支付宝
    ],
    // 支付宝，微信线上都在一张表记录，只能通过子类服务类计算
    'statistics_online_service_map' => [
        // 14 => App\Service\PayChannelStatistics\AlipayService::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_ALIPAY  => App\Service\PayChannelStatistics\AlipayOnlineService::class, // 支付宝-线上
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_WEIXIN  => App\Service\PayChannelStatistics\WeixinOnlineService::class, // 微信-线上
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_XFB_ALPAY  => App\Service\PayChannelStatistics\UnionpayAlipayOnlineService::class, // 银联-线上-支付宝
    ],
    /**
     * 退费方式-线下
     */
    'refund_type_map' => [
        \ParentAccountRecordDetailRechargeRefundsModel::TYPE_CASH     => 'cash', // 现金
        \ParentAccountRecordDetailRechargeRefundsModel::TYPE_TRANSFER => 'transfer', // 转账
        // \ParentAccountRecordDetailRechargeRefundsModel::TYPE_ALIPAY => 'alipay', // 原路退回-支付宝
        // \ParentAccountRecordDetailRechargeRefundsModel::TYPE_WEIXIN => 'weixin', // 原路退回-微信
        \ParentAccountRecordDetailRechargeRefundsModel::TYPE_XUEFEIBAO => 'xuefeibao', // 原路退回-学费宝
    ],
    /**
     * 现金账户退费方式-线下
     * 由于现金账户统计在bc_finance_parent_refund_applys这个表，因此单独配置
     */
    'cash_refund_type_map' => [
        \FinanceParentRefundApplysModel::REFUND_TYPE_CASH     => 'cash', // 现金
        \FinanceParentRefundApplysModel::REFUND_TYPE_TRANSFER => 'transfer', // 转账
    ],
    /**
     * 退费方式-线上
     */
    'online_refund_type_map' => [
        \FinanceParentRefundApplysModel::REFUND_TYPE_ALIPAY => 'alipay', // 支付宝
        \FinanceParentRefundApplysModel::REFUND_TYPE_WEIXIN => 'weixin', // 微信
        \FinanceParentRefundApplysModel::REFUND_TYPE_XUEFEIBAO => 'xuefeibao', // 学费宝
    ],

    // 所有的进账类型与模型类映射关系
    'all_recharge_map' => [
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_ALIPAY => ParentAccountRecordDetailRechargeOnlineModel::class, // 支付宝-线上
        ParentAccountRecordDetailOrderModel::PAY_WAY_FACE => ParentAccountRecordDetailRechargeOnlineModel::class, // 支付宝面对面-线上
        ParentAccountRecordDetailOrderModel::PAY_WAY_CASH          => ParentAccountRecordDetailRechargeCashModel::class, // 现金
        ParentAccountRecordDetailOrderModel::PAY_WAY_POS_CARD      => ParentAccountRecordDetailRechargePosModel::class, // 刷卡POS
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_WEIXIN => ParentAccountRecordDetailRechargeOnlineModel::class, // 微信-线上
        ParentAccountRecordDetailOrderModel::PAY_WAY_POS_CODE      => ParentAccountRecordDetailRechargePosModel::class, // 扫码POS
        ParentAccountRecordDetailOrderModel::PAY_WAY_CHEQUES       => ParentAccountRecordDetailRechargeChequesModel::class, // 支票
        ParentAccountRecordDetailOrderModel::PAY_WAY_WEIXIN        => ParentAccountRecordDetailRechargeWeixinModel::class, // 微信
        ParentAccountRecordDetailOrderModel::PAY_WAY_ALIPAY        => ParentAccountRecordDetailRechargeAlipaysModel::class, // 支付宝
        ParentAccountRecordDetailOrderModel::PAY_WAY_BANKTRANSFER  => ParentAccountRecordDetailRechargeBanktransfersModel::class, // 银行转账
        ParentAccountRecordDetailOrderModel::PAY_WAY_ACCOUNT       => ParentAccountRecordDetailRechargeMerchantAccountsModel::class, // 商家家长账户
        ParentAccountRecordDetailOrderModel::PAY_WAY_QRCODE        => ParentAccountRecordDetailRechargeQrcodesModel::class, // 二维码POS
        ParentAccountRecordDetailOrderModel::PAY_WAY_XFB_SCAN_CODE_ALIPAY => ParentAccountRecordDetailRechargeXuefeibaoModel::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_XFB_SCAN_CODE_WEIXIN => ParentAccountRecordDetailRechargeXuefeibaoModel::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_XFB_SCAN_CODE_OTHER => ParentAccountRecordDetailRechargeXuefeibaoModel::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_XFB_POS_BANK_CARD => ParentAccountRecordDetailRechargeXuefeibaoModel::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_XFB_POS_CREDIT_CARD => ParentAccountRecordDetailRechargeXuefeibaoModel::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_XFB_QRCODE_ALIPAY => ParentAccountRecordDetailRechargeXuefeibaoModel::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_XFB_QRCODE_WEIXIN => ParentAccountRecordDetailRechargeXuefeibaoModel::class,
    ],
    // 收入流水对应的服务映射关系
    'flow_service_map' => [
        // 线上支付宝微信，需要单独的子类服务来查金额
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_ALIPAY => App\Service\ParentAccountRecharge\AlipayOnlineRechargeService::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_FACE => App\Service\ParentAccountRecharge\AlipayOnlineRechargeService::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_ONLINE_WEIXIN => App\Service\ParentAccountRecharge\WeixinOnlineRechargeService::class,
        // 家长账户没有is_delete字段，需要单独的子类服务来查金额
        ParentAccountRecordDetailOrderModel::PAY_WAY_ACCOUNT => App\Service\ParentAccountRecharge\MerchantAccountRechargeService::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_POS_CARD => App\Service\ParentAccountRecharge\PosCardRechargeService::class,
        ParentAccountRecordDetailOrderModel::PAY_WAY_POS_CODE => App\Service\ParentAccountRecharge\PosCodeRechargeService::class,
    ],
    // 子订单服务类和对应的服务映射关系
    'order_service_map' => [
        // 账户充值 由于sub_order_ids为空,因此 通过eduwork.bi_merchant_parent_flows.type字段在代码中确定服务
        // 服务为App\Service\ParentAccountOrder\OrderService::class

        // 账户退费
        ParentAccountRecordDetailOrderWithdrawModel::class => App\Service\ParentAccountOrder\AccountWithdrawOrderService::class,
        // 云直播课程
        ParentAccountRecordDetailOrderCourseLiveModel::class => App\Service\ParentAccountOrder\CourseLiveOrderService::class,
        // 全日制课程
        ParentAccountRecordDetailOrderCourseQrzModel::class => App\Service\ParentAccountOrder\CourseQrzOrderService::class,
        // 艺体课课程
        ParentAccountRecordDetailOrderCourseRollModel::class => App\Service\ParentAccountOrder\CourseRollOrderService::class,
        // 小班
        ParentAccountRecordDetailOrderClassOtmsModel::class => App\Service\ParentAccountOrder\OtmOrderService::class,
        // 小班套餐
        ParentAccountRecordDetailOrderClassOtmPackagesModel::class => App\Service\ParentAccountOrder\OtmPackageOrderService::class,
        // 大班
        ParentAccountRecordDetailOrderClassOtosModel::class => App\Service\ParentAccountOrder\OtoOrderService::class,
        // 商品
        ParentAccountRecordDetailOrderTextbooksModel::class => App\Service\ParentAccountOrder\TextbookOrderService::class,
        // 课时包
        ParentAccountRecordDetailOrderVirtualsModel::class => App\Service\ParentAccountOrder\VirtualOrderService::class,
        // 艺体课转班
        ParentAccountRecordDetailOrderClassRollModel::class => App\Service\ParentAccountOrder\ClassRollOrderService::class,
    ],
];