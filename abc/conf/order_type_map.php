<?php

/**
 * eduwork.parent_account_record_detail_orders.order_type
 * 与个子订单模型的映射关系
 * 
 * @author yangshuaishuai <yangshuaishuai@xiaohe.com>
 * @date 2019-06-05 18:00
 */

 return [
    'xuefeibao_support' => [
        \ParentAccountRecordDetailOrderModel::ORDER_TYPE_OTM                => \ParentAccountRecordDetailOrderClassOtmsModel::class,
        \ParentAccountRecordDetailOrderModel::ORDER_TYPE_OTM_PACKAGE        => \ParentAccountRecordDetailOrderClassOtmPackagesModel::class,
        \ParentAccountRecordDetailOrderModel::ORDER_TYPE_TEXTBOOK           => \ParentAccountRecordDetailOrderTextbooksModel::class,
        \ParentAccountRecordDetailOrderModel::ORDER_TYPE_CLASS_HOUR_PACKAGE => \ParentAccountRecordDetailOrderVirtualsModel::class,
        \ParentAccountRecordDetailOrderModel::ORDER_TYPE_OTO                => \ParentAccountRecordDetailOrderClassOtosModel::class,
        \ParentAccountRecordDetailOrderModel::ORDER_TYPE_ROLL_COURSE        => \ParentAccountRecordDetailOrderCourseRollModel::class,
    ],
 ];
