<?php
/**
 * Created by PhpStorm.
 * User: dongwenhao
 * Date: 2018/11/1
 * Time: 1:42 PM
 */

/**
 * 敏感数据脱敏配置
 */
return [
    // 手机号脱敏配置
    'phone' => [
        'api_url' => [
            '/ClueActivity/getActClueStudent',                      //学员管理-线索学员-活动线索
            '/Student/allResource',                                 //学员管理-线索学员-查看线索
            '/ClueActivity/getStudentRelated',                      //学员管理-线索学员-活动线索-查看关系图
            '//Voucher/signupClassOtm',                             //前台业务-班课-查看学员-报名-班课报名-确认缴费-收据
            '/RollCourseOrder/getCourseOrderDetail',                //前台业务-班课-查看学员-报艺体课-报名
            '//PointMp/getPointMp',                                 //前台业务-班课-查看学员-学员主页
            '/MakeUpLessons/makeUpLessonList',                      //前台业务-班课-学员补课
            '/GoodsClassOtmsOrder/getSignUpList',                   //前台业务-班课-教材领取-商品领取
            '/Student/getMerchantStudentTransferCampusList',        //前台业务-班课-转校区记录
            '//Voucher/quitClassOto',                               //前台业务-一对一-课程管理-课程退费-退费凭证
            '/RollCourseOrder/getRollCourseOrderRecords',           //前台业务-艺体课-报名记录
            '/RollCourseOrder/getRollCourseOrderList',              //前台业务-艺体课-学员分班
            '/RollCourseOrder/getCourseOrderRefundDetail',          //前台业务-艺体课-学员分班-退费-确认退费-艺体课退费凭证
            '/RollClassOrder/getRollClassOrderRecords',             //前台业务-艺体课-班级管理
            '/RollClassOrder/getTransferRollClassOrderDetail',      //前台业务-艺体课-班级管理-转班-转班确认-收费凭证
            '/RollClassOrder/getRefundRollClassOrderDetail',        //前台业务-艺体课-班级管理-退班-退班确认-退费凭证
            '/RollAttendances/getRollAttendancesList',              //前台业务-艺体课-考勤记录
            '/RollClassOrder/transferRollClassOrderRecords',        //前台业务-艺体课-转班记录
            '/RollClassOrder/refundRollClassOrderRecords',          //前台业务-艺体课-退费记录
            '/RollCourseOrder/repayList',                           //前台业务-艺体课-补缴记录
            '/ParentAccount/getMerchantParentList',                 //前台业务-账户管理-家长账户
            '/ParentAccount/getParentAccountInfo',                  //前台业务-账户管理-家长账户-明细
            '/ParentAccount/getOfflineRefundAccountDetails',        //前台业务-账户管理-家长账户-退费-退费凭证
            '/ParentAccountRefundRecord/getParentAccountRefundRecordList',      //前台业务-账户管理-申请退费
            '/ParentAccount/getParentAccountMerchantDetail',                    //前台业务-账户管理-家长账户-明细-来源/去向
            '/ParentAccount/returnAccountRecords',                              //前台业务-账户管理-退回账户记录-退班退费
            '//PointMpRecord/getPointMpRecord',                                 //前台业务-积分管理-积分记录
            '/SignUpAgain/classStatisticsList',                                 //前台业务-续报管理-学员课次统计表
            '/SignUpAgain/viewClassStatisticsBySid',                            //前台业务-续报管理-学员课次统计表-学员姓名“详情”
            '/GoodsRealiasOrder/getGoodsRealiasSalesList',                      //进销存-销售管理-销售记录
            '/GoodsRealiasOrder/getGoodsRealiasCard',                           //进销存-销售管理-销售记录-补打凭证
            '/Student/getMerchantStudentList',                                  //进销存-销售管理-商品销售-搜索学员
            '/Member/getSearchEmployeeList',                                    //财务管理-财务统计-有效人次统计
            '/EnrollmentRate/classConsumeStatistics',                           //财务管理-财务统计-课耗明细统计-班课
            '/EnrollmentRate/classConsumeStatisticsOto',                        //财务管理-财务统计-课耗明细统计-一对一
            '/EnrollmentRate/advanceMoneyStatistic',                            //财务管理-财务统计-学员预收统计
            '/EnrollmentRate/classConsumeStatisticsByClass',                    //财务管理-财务统计-课耗明细统计（班级维度）
            '/EnrollmentRate/effectivePeopleStatistics',                        //业务报表-财务统计-有效人次统计
            '/StatisticsDailyBusiness/deductAmountStatistics',                  //业务报表-结算报表-扣减结算
            '/GoodsClassOtmsOrder/getBuyGoodsClassOtmList',                     //业务报表-学员报表-学员交易记录-购买班课
            '/ClassHourPackage/getClassHourPackageDealRecord',                  //业务报表-学员报表-学员交易记录-购买课时包
            '/GoodsClassOtoOrder/getGoodsClassOtoDealRecord',                   //业务报表-学员报表-学员交易记录-购买一对一
            '//Voucher/signupClassOto',                                         //业务报表-学员报表-学员交易记录-购买一对一-凭证
            '/GoodsClassOtmsOrder/getGoodsClassOtmPackageList',                 //业务报表-学员报表-学员交易记录-购买班课套餐
            '/GoodsClassOtmsOrder/refundGoodsClassOtmList',                     //业务报表-学员报表-学员交易记录-退班退费
            '//Voucher/quitClassOtm',                                           //业务报表-学员报表-学员交易记录-退班退费-凭证
            '/DiscountAndClassEndRefund/getClassEndRefund',                     //业务报表-学员报表-学员交易记录-结课退费
            '/ClassHourPackage/getClassHourPackageRefund',                      //业务报表-学员报表-学员交易记录-课时包退费
            '/GoodsClassOtoOrder/getGoodsClassOtoRefund',                       //业务报表-学员报表-学员交易记录-一对一退费
            '/DiscountAndClassEndRefund/getDiscountRefund',                     //业务报表-学员报表-学员交易记录-优惠补偿退费
            '/GoodsClassOtmsOrder/refundGoodsClassOtmPackageList',              //业务报表-学员报表-学员交易记录-班级套餐退费
            '/GoodsClassOtmsOrder/getTransferClassRecord',                      //业务报表-学员报表-学员交易记录-班课转班
            '//Voucher/transferClassOtm',                                       //业务报表-学员报表-学员交易记录-班课转班-凭证
            '/Orders/getOrderRepayRecord',                                      //业务报表-学员报表-学员交易记录-欠费补缴
            '/ParentAccount/getParentAccountRechargeDetails',                   //前台业务-账户管理-家长账户-充值-充值成功-凭证
            '/ReturnTextBooks/index',                                           //前台业务-班课-商品退费记录
            '/ReturnTextBooks/index',                                           //前台业务-班课-商品退费记录
            '/ParentAccount/getParentAccountMerchantList',                      //前台业务-班课-账户充值明细
            '/GoodsClassOtmsOrder/delBuyGoodsClassOtmList',                     //前台业务-班课-订单删除记录
            '/CouponReleases/getList',                                          //优惠管理-优惠券-发放明细
            '/CouponReleases/getUsedList',                                      //优惠管理-优惠券-使用明细
            '/CouponApplyDetails/issuedList',                                   //优惠管理-优惠券-发放名单
            '/CouponReleases/getIssueStudent',                                  //优惠管理-优惠券-查看发放学员
            '/Student/getCouponsStudentList',                                  //优惠管理-优惠券-选择发放学员
            '/CouponApplyDetails/usedList',                                  //优惠管理-优惠券-申请名单
        ],
        'data_field' => ['phone', 'from_phone', 'to_phone','contact_way','parent_phone','student_phone'],
    ],
];