<?php

/**
 * 员工权限标识
 * This file is part of apibusiness.
 * Author: wanghongting
 * Date: 2017/11/30
 * Time: 18:04
 * File: permission_identy.php
 */

return [
    'permissionIdenty' => [
        'attendance_classAttendanceList_list' => [
            '/Attendances/getClassAttendanceList',
        ],
        'attendance_attendance_look' => [
            '/Attendances/getClassAttendanceDetails'
        ],
        'attendance_attendance_edit' => [
            '/Attendances/editClassAttendance'
        ],
        'financiaStatistics_consumptionDetailList_list' => [
            '/EnrollmentRate/classConsumeStatistics'
        ],
        'financiaStatistics_effectivePersonList_list' => [
            '/EnrollmentRate/effectivePeopleStatistics'
        ],
        'financiaStatistics_studentAdvanceList' => [
            '/EnrollmentRate/advanceMoneyStatistic'
        ],
        'evaluation_teacherList' => [
            '/Comments/getCommentsList'
        ],
        'adjustClass_adjustRecordList' => [
            '/Attendances/getOtmsTuneClassList'
        ],
        'adjustClass_adjustRecordList_export' => [
            '/Attendances/getOtmsTuneClassList'
        ],
        'adjustClass_adjustClass' => [
            '/GoodsClassOtmsOrder/getStudentClassInfo',
            '/GoodsClassOtms/getGoodsClassOtmsList',
        ],
        'adjustClass_selectClass' => [
            '/Lesson/getOtmsLessonList',
        ],
        'goodsSaleManage_searchStudent' => [
            '/Student/getMerchantStudentList',
        ],
        'goodsSaleManage_settlement' => [
            '/GoodsRealiasOrder/verifyGoodsRealiasList',
        ],
        'goodsSaleManage_buyGoods' => [
            '/GoodsRealiasOrder/addTextbooks',
        ],
        'goodsSaleManage_saleCredential' => [
            '/GoodsRealiasOrder/getGoodsRealiasCard',
        ],
        'goodsSaleManage_saleRecordList' => [
            '/GoodsRealiasOrder/getGoodsRealiasSalesList',
        ],
        'goodsSaleManage_saleRefund' => [
            '/GoodsRealiasOrder/refundGoodsRealias',
        ],
        'goodsSaleManage_saleRecordList_credential' => [
            '/GoodsRealiasOrder/getGoodsRealiasCard'
        ],
        'goodsSaleManage_saleRecordList_export' => [
            '/GoodsRealiasOrder/getGoodsRealiasSalesList'
        ],
        'goodsSaleManage_saleStatisticalList' => [
            '/GoodsRealiasOrder/getGoodsRealiasSalesReportList'
        ],
        'goodsSaleManage_saleStatisticalList_export' => [
            '/GoodsRealiasOrder/getGoodsRealiasSalesReportList',
        ],
        'goodsInventory_bringIn' => [
            '/GoodsRealiasStocks/collect',
        ],
        'goodsInventory_bringOut' => [
            '/GoodsRealiasStocks/output',
        ],
        'goodsInventory_reportLoss' => [
            '/GoodsRealiasStocks/faulty',
        ],
        'goodsInventory_bringInList' => [
            '/GoodsRealiasStocks/collectRecordsList',
        ],
        'goodsInventory_bringInList_export' => [
            '/GoodsRealiasStocks/collectRecordsList',
        ],
        'goodsInventory_bringInList_undo' => [
            '/GoodsRealiasStocks/Revocation',
        ],
        'goodsInventory_reportLossList_undo' => [
            '/GoodsRealiasStocks/Revocation',
        ],
        'goodsInventory_bringOutList' => [
            '/GoodsRealiasStocks/outputRecordsList',
        ],
        'goodsInventory_bringOutLook' => [
            '/GoodsRealiasStocks/outputDetails',
        ],
        'goodsInventory_bringOutUndo' => [
            '/GoodsRealiasStocks/Revocation',
        ],
        'goodsInventory_bringOutList_export' => [
            '/GoodsRealiasStocks/outputRecordsList',
        ],
        'goodsInventory_schoolList' => [
            '/CampusGoodsRealiasStocks/goodsRealiasStocksList',
        ],
        'goodsSaleManage_saleList' => [
            '/CampusGoodsRealiasStocks/goodsRealiasStocksList',
        ],
        'goodsInventory_schoolTransfer' => [
            '/CampusGoodsRealiasStocks/transfer',
        ],
        'goodsInventory_schoolRefund' => [
            '/CampusGoodsRealiasStocks/refund',
        ],
        'goodsInventory_schoolReportLoss' => [
            '/GoodsRealiasStocks/faulty',
        ],
        'goodsInventory_reportLossList' => [
            '/CampusGoodsRealiasStocks/faultyRefundRecordsList',
        ],
        'goodsInventory_reportLossList_export' => [
            '/CampusGoodsRealiasStocks/faultyRefundRecordsList',
        ],
        'goodsInventory_refundList' => [
            '/CampusGoodsRealiasStocks/faultyRefundRecordsList',
        ],
        'goodsInventory_refundList_export' => [
            '/CampusGoodsRealiasStocks/faultyRefundRecordsList',
        ],
        'goodsInventory_transferList' => [
            '/CampusGoodsRealiasStocks/transferRecordsList',
        ],
        'goodsInventory_transferList_export' => [
            '/CampusGoodsRealiasStocks/transferRecordsList',
        ],
        'goodsInventory_transferList_undo' => [
            '/CampusGoodsRealiasStocks/Revocation',
        ],
        'goodsInventory_refundList_undo' => [
            '/CampusGoodsRealiasStocks/Revocation',
        ],
        'goodsManage_receiveList' => [
            '/GoodsClassOtms/getGoodsClassOtmsList',
        ],
        'goodsManage_receive' => [
            '/GoodsRealiasOrder/drawGoodsRealias',
        ],
        'goodsManage_categoryList' => [
            '/GoodsCategoryRealias/getList',
        ],
        'goodsManage_categoryAdd' => [
            '/GoodsCategoryRealias/add',
        ],
        'goodsManage_categoryEdit' => [
            '/GoodsCategoryRealias/edit',
        ],
        'goodsManage_categoryDelete' => [
            '/GoodsCategoryRealias/del',
        ],
        'goodsManage_goodsList' => [
            '/GoodsRealias/getList',
        ],
        'goodsManage_goodsAdd' => [
            '/GoodsRealias/add',
        ],
        'goodsManage_goodsEdit' => [
            '/GoodsRealias/edit',
        ],
        'goodsManage_goodsDisable' => [
            '/GoodsRealias/is_usable',
        ],
        'goodsManage_goodsEnable' => [
            '/GoodsRealias/is_usable',
        ],
        'goodsManage_goodsImport' => [
            '/GoodsRealias/import',
        ],
        'goodsManage_goodsExport' => [
            '/GoodsRealias/getList',
        ],
        'goodsInventory_totalList' => [
            '/GoodsRealias/getList',
            '/GoodsCampusRealiasStocks/warningList'
        ],
        'goodsInventory_inventoryWarn_edit' => [
            '/GoodsCampusRealiasStocks/warningSet'
        ],
        'transactionRecord_buyClassBag' => [
            '/ClassHourPackage/getClassHourPackageDealRecord',
        ],
        'transactionRecord_buyClassBag_export' => [
            '/ClassHourPackage/getClassHourPackageDealRecord',
        ],
        'transactionRecord_refundClassBag' => [
            '/ClassHourPackage/getClassHourPackageRefund',
        ],
        'transactionRecord_refundClassBag_export' => [
            '/ClassHourPackage/getClassHourPackageRefund',
        ],
        'transactionRecord_buyOto' => [
            '/GoodsClassOtoOrder/getGoodsClassOtoDealRecord',
        ],
        'transactionRecord_buyOto_export' => [
            '/GoodsClassOtoOrder/getGoodsClassOtoDealRecord',
        ],
        'transactionRecord_refundOto' => [
            '/GoodsClassOtoOrder/getGoodsClassOtoRefund',
        ],
        'transactionRecord_refundOto_export' => [
            '/GoodsClassOtoOrder/getGoodsClassOtoRefund',
        ],
        'transactionRecord_refundEnd' => [
            '/DiscountAndClassEndRefund/getClassEndRefund',
        ],
        'transactionRecord_refundEnd_export' => [
            '/DiscountAndClassEndRefund/getClassEndRefund',
        ],
        'transactionRecord_refundDiscount' => [
            '/DiscountAndClassEndRefund/getDiscountRefund',
        ],
        'transactionRecord_refundDiscount_export' => [
            '/DiscountAndClassEndRefund/getDiscountRefund',
        ],
        'transactionRecord_buyClass' => [
            '/GoodsClassOtmsOrder/getBuyGoodsClassOtmList',
        ],
        'transactionRecord_buyClass_export' => [
            '/GoodsClassOtmsOrder/getBuyGoodsClassOtmList',
        ],
        'transactionRecord_buyClass_delete' => [
            '/GoodsClassOtmsOrder/delGoodsClassOtmsOrder'
        ],
        'transactionRecord_buyOtm' => [
            '/GoodsClassOtmsOrder/getGoodsClassOtmPackageList',
        ],
        'transactionRecord_buyOtm_export' => [
            '/GoodsClassOtmsOrder/getGoodsClassOtmPackageList',
        ],
        'transactionRecord_refundOtm' => [
            '/GoodsClassOtmsOrder/refundGoodsClassOtmList',
        ],
        'transactionRecord_refundOtm_export' => [
            '/GoodsClassOtmsOrder/refundGoodsClassOtmList',
        ],
        'transactionRecord_refundOtmPackage' => [
            '/GoodsClassOtmsOrder/refundGoodsClassOtmPackageList',
        ],
        'transactionRecord_refundOtmPackage_export' => [
            '/GoodsClassOtmsOrder/refundGoodsClassOtmPackageList',
        ],
        'transactionRecord_repayRecord' => [
            '/Orders/getOrderRepayRecord',
        ],
        'transactionRecord_repayRecord_export' => [
            '/Orders/getOrderRepayRecord',
        ],
        'transactionRecord_classTransfer' => [
            '/GoodsClassOtmsOrder/getTransferClassRecord',
        ],
        'transactionRecord_classTransfer_export' => [
            '/GoodsClassOtmsOrder/getTransferClassRecord',
        ],
        'goodsInventory_schoolList_export' => [
            '/CampusGoodsRealiasStocks/goodsRealiasStocksList'
        ],
        'goodsInventory_totalList_export' => [
            '/GoodsRealias/getList'
        ],
        'financiaStatistics_studentAdvanceList_export' => [
            '/EnrollmentRate/advanceMoneyStatistic'
        ],
        'financiaStatistics_studentAdvanceListTaxes_export' => [
            '/EnrollmentRate/advanceMoneyStatistic'
        ],

        'financiaStatistics_consumptionDetailListStatistics' => [
            '/EnrollmentRate/classConsumeStatisticsByClass'
        ],
        'financiaStatistics_consumptionDetailListStatistics_export' => [
            '/EnrollmentRate/classConsumeStatisticsByClass'
        ],
        'studyTubeTeacher_studyTubeTeacher' => [
            '/Member/setManageTeacher'
        ],
        'financiaStatistics_consumptionDetailListOto' => [
            '/EnrollmentRate/classConsumeStatisticsOto'
        ],
        'financiaStatistics_consumptionDetailListOto_export' => [
            '/EnrollmentRate/classConsumeStatisticsOto'
        ],
        'fillClass_list_oto' => [
            '/EnrollmentRate/classConsumeStatisticsOto'
        ],
        'financiaStatistics_teacherClassCost' => [
            '/EnrollmentRate/teacherConsumeStatisticsOtm'
        ],
        'financiaStatistics_teacherClassCost_export' => [
            '/EnrollmentRate/teacherConsumeStatisticsOtm'
        ],
        'financiaStatistics_teacherClassCost_financia' => [
            '/EnrollmentRate/teacherConsumeStatisticsOtm'
        ],
        'financiaStatistics_teacherClassCost_export_financia' => [
            '/EnrollmentRate/teacherConsumeStatisticsOtm'
        ],
        'financiaStatistics_teacherOtoCost' => [
            '/EnrollmentRate/teacherConsumeStatisticsOto'
        ],
        'financiaStatistics_teacherOtoCost_export' => [
            '/EnrollmentRate/teacherConsumeStatisticsOto'
        ],
        'financiaStatistics_teacherOtoCost_financia' => [
            '/EnrollmentRate/teacherConsumeStatisticsOto'
        ],
        'financiaStatistics_teacherOtoCost_export_financia' => [
            '/EnrollmentRate/teacherConsumeStatisticsOto'
        ],
        'financiaStatistics_teacherScrollCost' => [
            '/EnrollmentRate/teacherConsumeStatisticsRoll'
        ],
        'financiaStatistics_teacherScrollCost_export' => [
            '/EnrollmentRate/teacherConsumeStatisticsRoll'
        ],
        'financiaStatistics_teacherScrollCost_financia' => [
            '/EnrollmentRate/teacherConsumeStatisticsRoll'
        ],
        'financiaStatistics_teacherScrollCost_export_financia' => [
            '/EnrollmentRate/teacherConsumeStatisticsRoll'
        ],
        //###############全日制标识开始##################
        //1.前台业务
        'frontDeskFullTime_studentList' => [
            '/StudentQrz/getMerchantQrzStudentList'
        ],
        'frontDeskFullTime_export' => [
            '/StudentQrz/getMerchantQrzStudentList'
        ],
        'frontDeskFullTime_delete' => [
            '/StudentQrz/delMerchantQrzStudentById'
        ],
        'frontDeskFullTime_payMoney' => [
            '/QrzCourseOrder/addQrzCourseOrder'
        ],
        'frontDeskFullTime_payMoney1' => [
            '/QrzCourseOrder/addQrzCourseOrder'
        ],
        'frontDeskFullTime_refund' => [
            '/QrzCourseRefund/refundQrzCourse'
        ],
        'frontDeskFullTime_refund1' => [
            '/QrzCourseRefund/refundQrzCourse'
        ],
        'frontDeskFullTime_classList' => [
            '/GoodsClassQrz/getClassList'
        ],
        'frontDeskFullTime_lookClassDetail' => [
            '/LessonQrz/getLessonAttendanceList'
        ],
        'attendance_attendanceFullTime' => [
            '/LessonQrz/setAttendance'
        ],
        'studentFullTimeManage_select' => [
            '/GoodsClassQrzOrder/addQrzClassOrder'
        ],
        'teacherFullTimeManage_select' => [
            '/LessonRuleDetailDataQrz/setTeacher'
        ],
        'refundApplyFullTime_list' => [
            '/QrzCourseRefund/getQrzCourseRefundList'
        ],
        'frontDeskFullTime_refundInfo' => [
            '/QrzCourseOrder/getQrzCourseDetail'
        ],
        'frontDeskFullTime_refundInfo1' => [
            '/QrzCourseRefund/getUsableRefundQrzCourseDetail'
        ],
        'refundApplyFullTime_export' => [
            '/QrzCourseRefund/getQrzCourseRefundList'
        ],
        'refundApplyFullTime_look' => [
            '/QrzCourseRefund/getRefundQrzCourseDetail'
        ],
        'refundApplyFullTime_cancel' => [
            '/QrzCourseRefund/revokeQrzCourseRefund'
        ],
        'refundApplyFullTime_edit' => [
            '/QrzCourseRefund/editRefundQrzCourse'
        ],
        'oneAuditFullTime_list' => [
            '/QrzCourseRefund/getQrzCourseRefundList'
        ],
        'oneAuditFullTime_look' => [
            '/QrzCourseRefund/getRefundQrzCourseDetail'
        ],
        'oneAuditFullTime_pass' => [
            '/QrzCourseRefund/auditRefundQrzCourse'
        ],
        'oneAuditFullTime_repeat' => [
            '/QrzCourseRefund/auditRefundQrzCourse'
        ],
        'oneAuditFullTime_cancel' => [
            '/QrzCourseRefund/auditRefundQrzCourse'
        ],
        'twoAuditFullTime_list' => [
            '/QrzCourseRefund/getQrzCourseRefundList'
        ],
        'twoAuditFullTime_look' => [
            '/QrzCourseRefund/getRefundQrzCourseDetail'
        ],
        'twoAuditFullTime_pass' => [
            '/QrzCourseRefund/auditRefundQrzCourse'
        ],
        'twoAuditFullTime_cancel' => [
            '/QrzCourseRefund/auditRefundQrzCourse'
        ],
        //2.教务管理
        //2.1班级列表
        'teachFullTime_classList' => [
            '/GoodsClassQrz/getClassList'
        ],
        'teachFullTime_classAdd' => [
            '/GoodsClassQrz/addClass'
        ],
        'teachFullTime_classEdit' => [
            '/GoodsClassQrz/editClass'
        ],
        'teachFullTime_classDelete' => [
            '/GoodsClassQrz/delClass'
        ],
        'frontDeskFullTime_scheduleClass' => [
            '/LessonRuleDetailQrz/getScheduleForWeek'
        ],
        'frontDeskFullTime_scheduleTeacher' => [
            '/LessonRuleDetailQrz/getScheduleForTeacher'
        ],
        'teachFullTime_timetableList' => [
            '/LessonRuleDetailQrz/getScheduleForTeacher'
        ],
        'teachFullTime_courseSchedulingList' => [
            '/LessonRuleQrz/getLessonRuleList'
        ],
        'teachFullTime_courseSchedulingDetail' => [
            '/LessonRuleQrz/getLessonRule'
        ],
        'teachFullTime_courseSchedulingAdd' => [
            '/LessonRuleQrz/addLessonRule'
        ],
        'teachFullTime_courseSchedulingEdit' => [
            '/LessonRuleQrz/editLessonRule'
        ],
        'teachFullTime_courseSchedulingCancel' => [
            '/LessonRuleQrz/cancelLessonRule'
        ],
        'teachFullTime_courseSchedulingDel' => [
            '/LessonRuleQrz/delLessonRule'
        ],
        //2.2学费方案管理
        'teachFullTime_tuitionSchemeList' => [
            '/CourseQrz/getCourseList'
        ],
        'teachFullTime_tuitionSchemeAdd' => [
            '/CourseQrz/addCourse'
        ],
        'teachFullTime_tuitionSchemeEdit' => [
            '/CourseQrz/editCourse'
        ],
        'teachFullTime_tuitionSchemeDelete' => [
            '/CourseQrz/delCourse'
        ],
        //3.财务管理
        //3.1线上退费确认
        'refundManagesFullTime_onlineRefundList' => [
            '/QrzCourseRefund/getQrzCourseRefundList'
        ],
        'refundManagesFullTime_onlineRefundPass' => [
            '/QrzCourseRefund/confirmRefundQrzCourse'
        ],
        'refundManagesFullTime_onlineRefundCancel' => [
            '/QrzCourseRefund/revokeQrzCourseRefund'
        ],
        'refundManagesFullTime_onlineRefundAll' => [
            '/QrzCourseRefund/confirmRefundQrzCourse'
        ],
        //3.2学费退费设置
        'refundManagesFullTime_tuitionRefundSetList' => [
            '/ConfigMerchantRefundQrz/getConfigMerchantRefundList'
        ],
        'refundManagesFullTime_tuitionAddRefundSet' => [
            '/ConfigMerchantRefundQrz/addConfigMerchantRefund'
        ],
        'refundManagesFullTime_tuitionEditRefundSet' => [
            '/ConfigMerchantRefundQrz/editConfigMerchantRefund'
        ],
        'refundManagesFullTime_tuitionDeleteRefundSet' => [
            '/ConfigMerchantRefundQrz/delConfigMerchantRefund'
        ],
        //3.3其他费用项管理
        'otherCostManagementFullTime_otherCostManagementList' => [
            '/AmountItemQrz/getAmountItemList'
        ],
        'otherCostManagementFullTime_addOtherCostManagement' => [
            '/AmountItemQrz/addAmountItem'
        ],
        'otherCostManagementFullTime_editOtherCostManagement' => [
            '/AmountItemQrz/editAmountItem'
        ],
        'otherCostManagementFullTime_deleteOtherCostManagement' => [
            '/AmountItemQrz/delAmountItem'
        ],
        //###############全日制标识结束##################
        //###############v5.3.3开始##################
        'businessSet_xuBaoBan_editXueQi' => [
            '/ConfigMerchantSeason/editConfigMerchantSeason'
        ],
        'businessSet_xuBaoBan_addXuBaoSet' => [
            '/ConfigMerchantResignup/addConfigMerchantResignup'
        ],
        'businessSet_xuBaoBan_editXuBaoSet' => [
            '/ConfigMerchantResignup/editConfigMerchantResignup'
        ],
        'businessSet_xuBaoBan_deleteXuBaoSet' => [
            '/ConfigMerchantResignup/delConfigMerchantResignup'
        ],
        'xuBaoManage_studentSignUpList' => [
            '/Resignup/getStudentByGoodsId'
        ],
        'xuBaoManage_studentSignUpList_export' => [
            '/Resignup/getStudentByGoodsId'
        ],
        //###############v5.3.3结束##################
        'businessSet_transferClassAdd' => [
            '/ConfigMerchantTransferClass/setConfigMerchantTransferClass'
        ],
        //###############老生报名设置##################
        //###############v5.3.4开始##################
        'basicSet_oldStudentSignList' => [
            '/ConfigMerchantSignup/index'
        ],
        'basicSet_oldStudentSetAdd' => [
            '/ConfigMerchantSignup/store'
        ],
        'basicSet_oldStudentSetEdit' => [
            '/ConfigMerchantSignup/update'
        ],
        'basicSet_oldStudentSetDelete' => [
            '/ConfigMerchantSignup/destroy'

        ],
        //###############v5.3.4结束##################
        //###############v5.3.10开始#################
        'parentAcconut_rechargeRecord' => [
            '/ParentAccount/getParentAccountMerchantList'
        ],
        'transactionRecord_orderDelete' => [
            '/GoodsClassOtmsOrder/delBuyGoodsClassOtmList'

        ],
        //###############v5.3.10结束#################
        'goodsInventory_changeGoodsPrice' => [
            '/GoodsRealiasStocks/transferPrice'
        ],
        'goodsInventory_bringOutByCampus' => [
            '/GoodsCampusRealiasStocks/output'
        ],
        'goodsInventory_bringOutListByCampus' => [
            '/GoodsCampusRealiasStocks/outputRecords'
        ],
        'goodsInventory_bringOutLookByCampus' => [
            '/GoodsCampusRealiasStocks/outputDetails'
        ],
        'goodsInventory_bringOutUndoByCampus' => [
            '/GoodsCampusRealiasStocks/Revocation'
        ],
        'goodsInventory_goodsPriceAdjustRecord' => [
            '/GoodsRealiasStocks/transferPriceRecords'
        ],
        'basicSet_courseType' => [
            '/Course/getCourseTypeList'
        ],
        'basicSet_courseTypeAdd' => [
            '/Course/addCourseType'
        ],
        'basicSet_courseTypeEdit' => [
            '/Course/editCourseType'
        ],
        'basicSet_courseTypeStop' => [
            '/Course/usableCourseType'
        ],
        'basicSet_courseTypeEnable' => [
            '/Course/usableCourseType'
        ],
        'reportForm_personalSettlement' => [
            '/SettlementReport/person',
        ],
        'reportForm_campusSettlement' => [
            '/SettlementReport/campus',
        ],
        'expenses_income' => [
            '/FlowManage/listData',
        ],
        'expenses_income_export' => [
            '/FlowManage/listData',
        ],
        //###############v5.3.10开始#################
        'logManagement_operationLog' => [
            '/BzOperationLog/index'
        ],
            //###############v5.3.10结束#################
            //###############v5.4.0开始#################
        'statistics_consultantSignRank' => [
            '/Statistics/adviserList'
        ],
            //###############v5.4.1结束#################

        'businessSet_attendanceSet' => [
            '/ConfigMerchantAttendances/getAttendances'
        ],
        'businessSet_attendanceSetEdit' => [
            '/ConfigMerchantAttendances/attendancesUpdate'
        ],
        //系统考勤配置结束
        //###############v5.4.0开始#################
        'statistics_consultantSignRank' => [
            '/Statistics/adviserList'
        ],
        //###############v5.4.1结束#################
        'discountCoupon_manage' => [
            '/Coupons/getCouponsList'
        ],
        'discountCoupon_manage_new_discountCoupon' => [
            '/Coupons/addCoupons'
        ],
        'discountCoupon_manage_modification_discountCoupon' => [
            '/Coupons/editCoupons'
        ],
        'discountCoupon_history' => [
            '/CouponsLog/operationLog'
        ],
        'discountCoupon_apply_for_grant' => [
            '/Student/getCouponsStudentList'
        ],
        'discountCoupon_apply_for_grant_confirm' => [
            '/Coupons/CouponsApplySure'
        ],
        'discountCoupon_enable' => [
            '/Coupons/usableCoupons'
        ],
        'discountCoupon_disable' => [
            '/Coupons/usableCoupons'
        ],
        'discountCoupon_rules_setting'=>[
            '/DiscountRules/rulesAdd',
            '/DiscountRules/getRules',
            '/DiscountRules/RulesUpdate'
        ],
        'discountCoupon_grant_audit_check_the'=>[
            '/discountCoupon/grantAuditCheckThe'
        ],
        'discountCoupon_grant_audit_pass'=>[
            '/CouponApplys/reviewPass'
        ],
        'discountCoupon_grant_audit_unfreeze'=>[
            '/CouponApplys/Freeze'
        ],
        'discountCoupon_grant_audit_freeze'=>[
            '/CouponApplys/Freeze'
        ],
        'discountCoupon_grant_audit'=>[
            '/CouponApplys/getList'
        ],
        'discountCoupon_apply_for_fecord_undo'=>[
            '/CouponApplys/cancel'
        ],
        'discountCoupon_apply_for_record_export'=>[
            'CouponApplys/export'
        ],
        'discountCoupon_apply_for_record'=> [
            '/discountCoupon/applyForRecord'
        ],
        'discountCoupon_use_detail_export'=>[
            'CouponReleases/export'
        ],
        'discountCoupon_use_detail'=>[
            '/discountCoupon/useDetail'
        ],
        'discountCoupon_unfreeze'=>[
            '/CouponReleases/Freeze'
        ],
        'discountCoupon_freeze'=>[
            '/CouponReleases/Freeze'
        ],
        'discountCoupon_grant_detail'=>[
            '/CouponReleases/getList'
        ],
        'discountCoupon_grant_audit_no_pass'=>[
            '/CouponApplys/noPass'
        ],
        'financialManagement_teachersLeadClassStatistics'=>[
            '/RollStatistics/getTeacherStatistics'
        ],
        'financialManagement_teachersLeadClassStatisticsExport'=>[
            '/RollStatistics/getTeacherStatistics'
        ],
        'financialManagement_teachersLeadClassStatistics_classManTime'=>[
            '/RollStatistics/getStudentPersons'
        ],
        'financialManagement_teachersLeadClassStatistics_classManTimeExport'=>[
            '/RollStatistics/getStudentPersons'
        ],
        'financialManagement_teachersLeadClassStatistics_classManPeople'=>[
            '/RollStatistics/getStudentPersons'
        ],
        'financialManagement_teachersLeadClassStatistics_classManPeopleExport'=>[
            '/RollStatistics/getStudentPersons'
        ]

    ],
];
