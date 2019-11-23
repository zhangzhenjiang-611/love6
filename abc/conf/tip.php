<?php
/**
 * 错误描述文件
 *
 * @copyright Copyright 2012-2017, BAONAHAO Software Foundation, Inc. ( http://api.baonahao.com/ )
 * @link http://api.baonahao.com api(tm) Project
 * @author gaoxiang <gaoxiang@xiaohe.com>
 */
return [
    'API_COMM_001' => ['success', 'success'],
    'API_COMM_002' => ['sign error', '签名验证失败'],
    'API_COMM_003' => ['token已过期', '重录过期，请重新登录'],
    'API_COMM_004' => ['PhalconException', '操作失败'],
    'API_COMM_404' => ['not found', 'not found'],
    'API_COMM_005' => ['操作失败', '操作失败'],
    'API_COMM_006' => ['没有权限', '没有权限'],
    'API_COMM_101' => ['商家ID为必填项', '商家不正确！'],
    'API_COMM_102' => ['平台ID为必填项', '平台不正确！'],
    'API_COMM_103' => ['家长ID为必填项', '家长ID不正确！'],
    'API_COMM_104' => ['学生ID为必填项', '学生ID不正确！'],
    'API_COMM_105' => ['商品ID为必填项', '商品不正确！'],
    'API_COMM_106' => ['订单ID为必填项', '订单不正确！'],
    'API_COMM_107' => ['订单不存在', '订单不存在！'],
    'API_COMM_108' => ['商品不存在', '商品不存在！'],
    'API_COMM_109' => ['经办人ID为必填项', '经办人ID为必填项！'],
    'API_COMM_110' => ['操作人ID为必填项', '操作人ID为必填项！'],
    'API_COMM_111' => ['数量格式不正确', '数量格式不正确！'],
    'API_COMM_112' => ['教师ID为必填项', '教师ID为必填项！'],
    'API_COMM_113' => ['未查询到主订单信息', '未查询到主订单信息！'],
    'API_COMM_114' => ['撤销失败，您的库存量不足！'],
    'API_COMM_115' => ['标识不能为空，标识不能为空！'],
    'API_COMM_116' => ['类型不能为空','类型不能为空！'],
    'API_COMM_117' => ['类型不合法','类型不合法！'],
    'API_COMM_118' => ['未找到学生信息', '学生ID不正确！'],

    'API_GRADE_001' => ['商家ID为必填项', '商家不正确'],
    'API_GRADE_002' => ['获取年部年级数据类型必填', '获取年部年级数据类型必填'],
    'API_GRADE_003' => ['年部年级名称必填', '请填写年部年级名称'],
    'API_GRADE_004' => ['年部年级名称太长', '请填简化年部年级名称'],
    'API_GRADE_005' => ['创建人ID必填', '创建人不正确'],
    'API_GRADE_006' => ['名称已经存在', '名称已经存在，请更换名称'],
    'API_GRADE_007' => ['年部年级添加失败', '年部年级添加失败'],
    'API_GRADE_008' => ['请选择年部', '请选择年部'],
    'API_GRADE_009' => ['年部不存在', '请选择年部'],
    'API_GRADE_010' => ['年部年级ID必填', '请选择年部年级'],
    'API_GRADE_011' => ['年部年级删除成功', '删除成功'],
    'API_GRADE_012' => ['年部年级不存在', '操作失败'],
    'API_GRADE_013' => ['此条数据已被使用，不能删除', '删除失败，此条数据已被使用'],
    'API_GRADE_014' => ['修改人ID不能为空', '修改人不正确'],

    'API_GCST_001'  => ['商家ID为必填项', '商家不正确'],
    'API_GCST_002'  => ['分校ID为必填项', '分校不正确'],
    'API_GCST_003'  => ['校区ID为必填项', '校区不正确'],
    'API_GCST_004'  => ['服务类型ID为必填项', '服务类型不正确'],
    'API_GCST_005'  => ['创建人ID为必填项', '创建人不正确'],
    'API_GCST_006'  => ['年部ID为必填项', '年部不正确'],
    'API_GCST_007'  => ['年级ID为必填项', '年级不正确'],
    'API_GCST_008'  => ['学科分类ID为必填项', '学科分类不正确'],
    'API_GCST_009'  => ['平台ID为必填项', '平台不正确'],
    'API_GCST_010'  => ['日志ID为必填项', '日志不正确'],
    'API_GCST_011'  => ['修改人ID为必填项', '修改人不正确'],

    'API_PA_001'  => ['商家ID为必填项', '商家不正确'],
    'API_PA_002'  => ['平台ID为必填项', '平台不正确'],
    'API_PA_003'  => ['协议类型错误', '协议类型错误'],
    'API_PA_004'  => ['协议内容错误', '协议内容错误'],
    'API_PA_005'  => ['创建人ID为必填项', '创建人不正确'],
    'API_PA_006'  => ['协议ID为必填项', '协议不正确'],
    'API_PA_007'  => ['修改人ID为必填项', '修改人不正确'],

    'API_CATEGORY_MERCHANT_001'  => ['商家ID为必填项', '商家不正确'],
    'API_CATEGORY_MERCHANT_002' => ['创建人ID必填', '创建人不正确'],
    'API_CATEGORY_MERCHANT_003' => ['分类名称必填', '分类名称必填'],
    'API_CATEGORY_MERCHANT_004' => ['一级分类名称必填', '一级分类名称必填'],
    'API_CATEGORY_MERCHANT_005' => ['请连续填写分类', '请连续填写分类'],
    'API_CATEGORY_MERCHANT_006' => ['业务规定分类最多添加3级', '业务规定分类最多添加3级'],
    'API_CATEGORY_MERCHANT_007' => ['分类名称已存在', '分类名称已存在'],
    'API_CATEGORY_MERCHANT_008' => ['父类不存在', '父类不存在'],
    'API_CATEGORY_MERCHANT_009' => ['分类ID必填', '分类ID必填'],
    'API_CATEGORY_MERCHANT_010' => ['修改人ID必填', '修改人不正确'],
    'API_CATEGORY_MERCHANT_011' => ['此分类已被删除', '删除成功'],
    'API_CATEGORY_MERCHANT_012' => ['此分类不存在', '请选择要删除的分类'],
    'API_CATEGORY_MERCHANT_013' => ['此分类已被使用，不能删除', '此分类已被使用，不能删除'],
    'API_CATEGORY_MERCHANT_014' => ['删除失败', '删除失败'],
    'API_CATEGORY_MERCHANT_015' => ['此分类不存在', '请选择要编辑的分类'],
    'API_CATEGORY_MERCHANT_016' => ['此分类已被使用，不能编辑', '此分类已被使用，不能编辑'],
    'API_CATEGORY_MERCHANT_017' => ['编辑失败', '编辑失败'],
    'API_CATEGORY_MERCHANT_018' => ['配置数据为空', '配置数据不能为空'],
    'API_CATEGORY_MERCHANT_019' => ['配置数据图片为空', '图片不能为空'],
    'API_CATEGORY_MERCHANT_020' => ['学科服务费率不能为空', '学科服务费率不能为空'],

    'API_CPI_001' => ['线索ID必填', '线索ID必填'],
    'API_CPI_002' => ['删除线索成功', '删除成功'],
    'API_CPI_003' => ['线索不存在', '线索不存在'],
    'API_CPI_004' => ['线索已删除', '线索已删除'],

    'API_STUDENT_001' => ['学员ID不能为空', '学员ID不能为空！'],
    'API_STUDENT_002' => ['学员不存在', '商家下不存在该学员！'],
    'API_STUDENT_003' => ['学员已使用，不能删除', '学员已使用，不能删除！'],
    'API_STUDENT_004' => ['该学员家长账户有余额，不可删除！'],

    'API_REASON_001' => ['商家原因数据初始化失败！', '商家原因数据初始化失败！'],
    'API_REASON_002' => ['所属类型有误！', '所属类型有误！'],
    'API_REASON_003' => ['原因名称不能为空！', '原因名称不能为空！'],
    'API_REASON_004' => ['所属类型不存在！', '所属类型不存在！'],
    'API_REASON_005' => ['原因已存在，不能重复添加！', '相同类型的原因已存在，不能重复添加！'],
    'API_REASON_006' => ['原因ID有误！', '原因ID有误！'],
    'API_REASON_007' => ['原因不存在！', '原因不存在！'],
    'API_REASON_008' => ['所属类型不能为空！', '所属类型不能为空！'],

    'API_ACCOUNT_001' => ['操作人不能有误！', '操作人不能有误！'],
    'API_ACCOUNT_002' => ['充值规则类型有误！', '充值规则类型有误！'],
    'API_ACCOUNT_003' => ['账户余额消耗类型有误！', '账户余额消耗类型有误！'],
    'API_ACCOUNT_004' => ['账户退费规则类型有误！', '账户退费规则类型有误！'],
    'API_ACCOUNT_005' => ['充值赠送余额时赠送类型不能为空！', '充值赠送余额时赠送类型不能为空！'],
    'API_ACCOUNT_006' => ['按比例赠送余额时比例不能为空！', '按比例赠送余额时比例不能为空！'],
    'API_ACCOUNT_007' => ['按阶梯赠送余额阶梯不能为空！', '按比例赠送余额阶梯不能为空！'],
    'API_ACCOUNT_008' => ['按阶梯赠送余额阶梯数据有误！', '按阶梯赠送余额阶梯数据有误！'],
    'API_ACCOUNT_009' => ['按阶梯赠送余额阶梯区间有交叉！', '按阶梯赠送余额阶梯区间有交叉！'],
    'API_ACCOUNT_010' => ['按比例消耗账户余额时对应比例不能为空！', '按比例消耗账户余额时对应比例不能为空！'],
    'API_ACCOUNT_011' => ['按比例消耗账户余额时对应比例有误！', '按比例消耗账户余额时对应比例有误！'],
    'API_ACCOUNT_012' => ['账户本金退费，赠金余额按比例扣除比例不能为空！', '账户本金退费，赠金余额按比例扣除比例不能为空！'],
    'API_ACCOUNT_013' => ['设置商家账户规则失败！', '设置商家账户规则失败！'],
    'API_ACCOUNT_014' => ['设置商家账户阶梯赠送数据失败！', '设置商家账户阶梯赠送数据失败！'],
    'API_ACCOUNT_015' => ['家长账户充值金额必填！', '家长账户充值金额必填！'],
    'API_ACCOUNT_016' => ['家长账户充值支付方式有误！', '家长账户充值支付方式有误！'],
    'API_ACCOUNT_017' => ['家长不存在！', '家长不存在！'],
    'API_ACCOUNT_018' => ['该商家下不存在该家长！', '该商家下不存在该家长！'],
    'API_ACCOUNT_019' => ['家长充值赠送余额不正确！', '家长充值赠送余额有误！'],
    'API_ACCOUNT_020' => ['按比例赠送余额时余额不正确！', '按比例赠送余额时余额不正确！'],
    'API_ACCOUNT_021' => ['按阶梯赠送余额时余额不正确！', '按阶梯赠送余额时余额不正确！'],
    'API_ACCOUNT_022' => ['家长账户充值金额不正确！', '家长账户充值金额不正确！'],
    'API_ACCOUNT_023' => ['添加家长账户失败！', '添加家长账户失败！'],
    'API_ACCOUNT_024' => ['充值失败！', '充值失败！'],
    'API_ACCOUNT_025' => ['充值订单数据有误！', '充值订单数据有误！'],
    'API_ACCOUNT_026' => ['退费金额有误！', '退费金额有误！'],
    'API_ACCOUNT_027' => ['退费方式有误！', '退费方式有误！'],
    'API_ACCOUNT_028' => ['经办校区有误！', '经办校区有误！'],
    'API_ACCOUNT_029' => ['家长商家账户不存在！', '家长商家账户不存在！'],
    'API_ACCOUNT_030' => ['转账方式退费银行卡信息必填！', '转账方式退费银行卡信息必填！'],
    'API_ACCOUNT_031' => ['账户余额不足！', '账户余额不足！'],
    'API_ACCOUNT_032' => ['商家未设置账户赠金退费规则，赠金不可退！', '商家未设置账户赠金退费规则，赠金不可退！'],
    'API_ACCOUNT_033' => ['家长赠金按比例退费时金额不正确！', '家长赠金按比例退费时金额不正确！'],
    'API_ACCOUNT_034' => ['家长账户退费赠金金额不正确！', '家长账户退费赠金金额不正确！'],
    'API_ACCOUNT_035' => ['家长账户退费原因必填！', '家长账户退费原因必填！'],
    'API_ACCOUNT_036' => ['退费记录ID有误！', '退费记录数据有误！'],
    'API_ACCOUNT_037' => ['退费记录不存在！', '退费记录不存在！'],
    'API_ACCOUNT_038' => ['退费记录类型有误！', '退费记录类型有误！'],
    'API_ACCOUNT_039' => ['退费记录ID有误！', '退费记录ID有误！'],
    'API_ACCOUNT_040' => ['退费审核操作有误！', '退费审核操作有误！'],
    'API_ACCOUNT_041' => ['退费审核状态有误！', '退费审核状态有误！'],
    'API_ACCOUNT_042' => ['该退费申请不是转账方式退费的不用审核！', '该退费申请不是转账方式退费的不用审核！'],
    'API_ACCOUNT_043' => ['一级审核已经通过，不能修改审核信息！', '一级审核已经通过，不能修改审核信息！'],
    'API_ACCOUNT_044' => ['一级审核已经驳回，不能修改审核信息！', '一级审核已经驳回，不能修改审核信息！'],
    'API_ACCOUNT_045' => ['一级未审核不能操作二级审核！', '一级未审核不能操作二级审核！'],
    'API_ACCOUNT_046' => ['二级审核已经审核过，不能修改审核信息！', '二级审核已经审核过，不能修改审核信息！'],
    'API_ACCOUNT_047' => ['已经撤销的申请，不能进行审核！', '已经撤销的申请，不能进行审核！'],
    'API_ACCOUNT_048' => ['一级审核未通过不能确认退费！', '一级审核未通过不能确认退费！'],
    'API_ACCOUNT_049' => ['二级审核未通过不能确认退费！', '二级审核未通过不能确认退费！'],
    'API_ACCOUNT_050' => ['非转账方式退费的申请不能撤销！', '非转账方式退费的申请不能撤销！'],
    'API_ACCOUNT_051' => ['不能重复撤销！', '不能重复撤销！'],
    'API_ACCOUNT_052' => ['已经审核过不能撤销！', '已经审核过不能撤销！'],

    'API_CMR_001' => ['课次规则必填！', '课次规则必填！'],
    'API_CMR_002' => ['课时单价规则必填！', '课时单价规则必填！'],
    'API_CMR_003' => ['退费规则设置添加失败！', '退费规则设置添加失败！'],
    'API_CMR_004' => ['退费规则设置编辑失败！', '退费规则设置编辑失败！'],

    'API_PAM_001' => ['家长ID必填！', '家长ID必填！'],
    'API_PAM_002' => ['订单ID必填！', '订单ID必填！'],
    'API_PAM_003' => ['交易类型必选！', '交易类型必选！'],

    'API_ML_001' => ['商家ID为必填项', '商家不正确！'],

    'API_CS_001' => ['商家ID为必填项', '课程分享失败！'],
    'API_CS_002' => ['课程ID为必填项', '课程分享失败！'],
    'API_CS_003' => ['课程ID错误', '没有找到要分享的课程，课程分享失败！'],
    'API_CS_004' => ['创建人ID为必填项', '没有分享人，课程分享失败！'],
    'API_CS_005' => ['平台ID为必填项', '没有平台，课程分享失败！'],
    'API_CS_006' => ['平台ID为必填项', '分享校区失败！'],

    'API_EMPLOYEE_001' => ['员工ID不能为空', '员工数据不正确'],
    'API_EMPLOYEE_002' => ['员工不存在', '员工不存在'],
    'API_EMPLOYEE_003' => ['员工没有岗位，不能使用系统', '员工没有岗位，不能使用系统'],
    'API_EMPLOYEE_004' => ['岗位不存在', '岗位不存在'],
    'API_EMPLOYEE_005' => ['数据角色不存在', '数据角色不存在'],
    'API_EMPLOYEE_006' => ['员工自定义岗位数据权限有误', '员工自定义岗位数据权限有误'],
    'API_EMPLOYEE_007' => ['员工岗位数据权限有误', '员工岗位数据权限有误'],

    'API_MEMBER_001' => ['会员ID不能为空', '会员ID不能为空'],
    'API_ATTENDANCES_001' => ['课次ID不能为空', '课次ID不能为空'],
    'API_ATTENDANCES_002' => ['考勤数据不正确', '考勤数据不正确'],
    'API_ATTENDANCES_003' => ['出勤学员仅限修改当月考勤', '出勤学员仅限修改当月考勤'],
    'API_ATTENDANCES_004' => ['编辑考勤失败', '编辑考勤失败'],
    'API_ATTENDANCES_005' => ['业务类型不能为空', '业务类型不能为空'],
    'API_ATTENDANCES_006' => ['课时包订单不存在', '课时包订单不存在'],
    'API_ATTENDANCES_007' => ['课时包商品不存在', '课时包商品不存在'],
    'API_ATTENDANCES_008' => ['课程不存在', '课程不存在'],
    'API_ATTENDANCES_009' => ['调出班级ID不能为空', '调出班级ID不能为空'],
    'API_ATTENDANCES_010' => ['调出班级课次ID不能为空', '调出班级课次ID不能为空'],
    'API_ATTENDANCES_011' => ['调入班级ID不能为空', '调入班级ID不能为空'],
    'API_ATTENDANCES_012' => ['调入班级课次ID不能为空', '调入班级课次ID不能为空'],
    'API_ATTENDANCES_013' => ['班级状态未开班不可调出', '班级状态未开班不可调出'],
    'API_ATTENDANCES_014' => ['考勤ID不能为空', '考勤ID不能为空'],
    'API_ATTENDANCES_015' => ['考勤ID不正确', '考勤信息不正确'],
    'API_ATTENDANCES_016' => ['无补课记录', '暂无补课信息'],
    'API_ATTENDANCES_017' => ['隔月不可修改补课记录', '隔月不可修改补课记录'],
    'API_ATTENDANCES_018' => ['考勤状态必须缺勤状态', '只有缺勤学员才可以进行补课'],
    'API_ATTENDANCES_019' => ['target_id不为空视为调出', '调出的课次不可以进行补课'],
    'API_ATTENDANCES_020' => ['课次ID不正确', '课次信息不正确'],
    'API_ATTENDANCES_021' => ['考勤状态为未考勤或者缺勤才可进行调课', '考勤状态为未考勤或者缺勤才可进行调课'],
    'API_ATTENDANCES_022' => ['调课只可调课一次', '调课只可调课一次'],
    'API_ATTENDANCES_023' => ['课次结束不可调入', '课次结束不可调入'],
    'API_ATTENDANCES_024' => ['排课班级才可调课', '排课班级才可调课'],
    'API_ATTENDANCES_025' => ['课次已上不可调课', '课次已上不可调课'],
    'API_ATTENDANCES_026' => ['目标班级上课时间与学员已报读班级时间冲突，此次调课失败', '目标班级上课时间与学员已报读班级时间冲突，此次调课失败'],
    'API_ATTENDANCES_027' => ['调课成功，请提醒家长到目标班级上课', '调课成功，请提醒家长到目标班级上课'],
    'API_ATTENDANCES_028' => ['调出班级课次不可以修改考勤', '调出班级课次不可以修改考勤'],
    'API_ATTENDANCES_029' => ['补课日期不能为空', '补课日期不能为空'],
    'API_ATTENDANCES_030' => ['补课开始时间不能为空', '补课开始时间不能为空'],
    'API_ATTENDANCES_031' => ['补课结束时间不能为空', '补课结束时间不能为空'],
    'API_ATTENDANCES_032' => ['补课时间不正确', '补课时间不正确'],
    'API_ATTENDANCES_033' => ['教师不存在', '教师不存在'],
    'API_ATTENDANCES_034' => ['订单不是缴费状态', '订单不是缴费状态'],
    'API_ATTENDANCES_035' => ['学员ID不能为空', '学员不存在！'],
    'API_ATTENDANCES_036' => ['班级来源不一致', '班级课程类型不一致，不能进行调课！'],

    'API_MENU_001' => ['菜单不存在', '菜单不存在'],
    'API_MENU_002' => ['类似标识的菜单太多了', '类似标识的菜单太多了'],

    'API_PVERSION_001' => ['平台版本数据错误', '平台版本数据错误'],

    'API_IDENTITY_001' => ['页面标识不能为空', '页面标识不能为空'],
    
    'API_GOODS_REALIAS_001' => ['商品ID不存在', '教学用品不存在'],
    'API_GOODS_REALIAS_002' => ['入库量不能为空', '入库量不能为空'],
    'API_GOODS_REALIAS_003' => ['入库量必须为整数', '入库量必须为整数'],
    'API_GOODS_REALIAS_004' => ['零售价不能为空', '零售价不能为空'],
    'API_GOODS_REALIAS_005' => ['零售价格式不正确', '零售价格式不正确'],
    'API_GOODS_REALIAS_006' => ['绑定售卖价不能为空', '绑定售卖价不能为空'],
    'API_GOODS_REALIAS_007' => ['绑定售卖价格式不正确', '绑定售卖价格式不正确'],
    'API_GOODS_REALIAS_008' => ['教学用品已经停用', '教学用品已经停用'],
    'API_GOODS_REALIAS_009' => ['入库信息不正确', '入库信息不正确'],
    'API_GOODS_REALIAS_010' => ['出库总量不能为空', '出库总量不能为空'],
    'API_GOODS_REALIAS_011' => ['出库总量必须为整数', '出库总量必须为整数'],
    'API_GOODS_REALIAS_012' => ['出库校区数量不正确', '出库校区数量不正确'],
    'API_GOODS_REALIAS_013' => ['商品库存量不足', '商品库存量不足'],
    'API_GOODS_REALIAS_014' => ['报损数量不能为空', '报损数量不能为空'],
    'API_GOODS_REALIAS_015' => ['报损数量必须为整数', '报损数量必须为整数'],
    'API_GOODS_REALIAS_016' => ['数据来源或类型格式不正确', '数据来源或类型格式不正确'],
    'API_GOODS_REALIAS_017' => ['出库总量不正确', '出库总量不正确'],
    'API_GOODS_REALIAS_018' => ['该记录已撤销，不可重复撤销', '该记录已撤销，不可重复撤销'],
    'API_GOODS_REALIAS_019' => ['经办校区和购买商品校区不是同一校区', '经办校区和购买商品校区不是同一校区'],
    'API_GOODS_REALIAS_020' => ['商品总价格式不正确', '商品总价格式不正确'],
    'API_GOODS_REALIAS_021' => ['商品购买数量大于该校区商品存库量', '商品购买数量大于该校区商品存库量'],
    'API_GOODS_REALIAS_022' => ['商品金额有误', '商品金额有误'],
    'API_GOODS_REALIAS_023' => ['校区不存在', '校区不存在'],
    'API_GOODS_REALIAS_024' => ['请选择同一校区商品购买', '请选择同一校区商品购买'],
    'API_GOODS_REALIAS_025' => ['实退金额不正确', '实退金额不正确'],
    'API_GOODS_REALIAS_026' => ['扣减金额不正确', '扣减金额不正确'],
    'API_GOODS_REALIAS_027' => ['退货数量大于此订单可退数量', '退货数量大于此订单可退数量'],
    'API_GOODS_REALIAS_028' => ['使用账户支付金额大于账户可用余额', '使用账户支付金额大于账户可用余额'],
    'API_GOODS_REALIAS_029' => ['请至少购物一个商品', '请至少购物一个商品'],
    'API_GOODS_REALIAS_030' => ['商品领取成功', '商品领取成功'],
    'API_GOODS_REALIAS_031' => ['学员本订单未购买商品', '学员本订单未购买商品'],
    'API_GOODS_REALIAS_032' => ['商品已经删除，不能领取', '商品已经删除，不能领取'],
    'API_GOODS_REALIAS_033' => ['订单商品已领取，不能重复领取', '订单商品已领取，不能重复领取'],
    'API_GOODS_REALIAS_034' => ['商品名称不能为空', '商品名称不能为空'],
    'API_GOODS_REALIAS_035' => ['商品编号不能为空', '商品编号不能为空'],

    'API_GOODS_001' => ['商品分类名称不能为空', '商品分类名称不能为空'],
    'API_GOODS_002' => ['商品分类长度不能小于32位', '商品分类长度不能小于32位'],
    'API_GOODS_003' => ['商品分类id不能为空', '商品分类id不能为空'],
    'API_GOODS_004' => ['该商品分类已存在', '该商品分类已存在'],
    'API_GOODS_005' => ['该商品分类已被引用，不能删除', '该商品分类已被引用，不能删除'],
    'API_GOODS_006' => ['该商品分类已存在，不能重复编辑', '该商品分类已存在，不能重复编辑'],
    'API_GOODS_007' => ['该商品分类不存在或者已删除', '该商品分类不存在或者已删除'],


    'API_GOODS_101' => ['该商品已被添加或不能为空', '该商品已被添加或不能为空'],
    'API_GOODS_102' => ['该商品编码不能为空', '该商品编码不能为空'],
    'API_GOODS_103' => ['该商品分类不能为空', '该商品分类不能为空'],
    'API_GOODS_104' => ['该商品id不能为空', '该商品id不能为空'],
    'API_GOODS_105' => ['商品id长度不能小于32位', '商品id长度不能小于32位'],
    'API_GOODS_106' => ['商品编码不一致，修改失败', '商品编码不一致，修改失败'],
    'API_GOODS_107' => ['该商品已存在，不可重复编辑', '该商品已存在，不可重复编辑'],


    'API_GOODSCLASS_001' => ['班级ID不能为空', '班级ID不能为空'],
    'API_GOODSCLASS_002' => ['班级不存在', '班级不存在'],

    'API_GOODSCAMPUSSTOCK_001' => ['库存不足，暂时无法领取', '库存不足，暂时无法领取'],
    'API_GOODSCAMPUSSTOCK_002' => ['商品在校区下无库存，不能领取', '商品在校区下无库存，不能领取'],


    'API_RC_001' => ['课程名称不能为空', '课程名称不能为空'],
    'API_RC_002' => ['学科分类ID为必填项', '学科分类不正确'],
    'API_RC_003' => ['班级类型ID为必填项', '班级类型不正确'],
    'API_RC_004' => ['课程课次不正确', '课程课次不正确'],
    'API_RC_005' => ['课次单价不正确', '课次单价不正确'],
    'API_RC_006' => ['校区ID不正确', '校区ID不正确'],
    'API_RC_007' => ['校区不存在或者已删除', '校区不存在或者已删除'],
    'API_RC_008' => ['课程已存在', '课程已存在'],
    'API_RC_009' => ['课程ID错误', '课程ID错误'],
    'API_RC_010' => ['状态错误', '状态错误'],
    'API_RC_011' => ['课程不存在或者已删除', '课程不存在或者已删除'],
    'API_RC_012' => ['课程状态已修改', '课程状态已修改'],
    'API_RC_013' => ['课程已经停用', '课程已经停用'],
    'API_RC_014' => ['开班数量错误', '开班数量错误'],
    'API_RC_015' => ['招生数量错误', '招生数量错误'],
    'API_RC_016' => ['课程已被使用不能编辑', '课程已被使用不能编辑'],
    'API_RC_017' => ['班级ID错误', '班级ID错误'],
    'API_RC_018' => ['班级不存在或者已删除', '班级不存在或者已删除'],
    'API_RC_019' => ['班级状态已修改', '班级状态已修改'],
    'API_RC_020' => ['课次信息不能为空', '课次信息不能为空'],
    'API_RC_021' => ['排课时间错误', '排课时间错误'],
    'API_RC_022' => ['班级已停用', '班级已停用'],
    'API_RC_023' => ['订单总金额错误', '订单总金额错误'],
    'API_RC_024' => ['支付总金额错误', '支付总金额错误'],
    'API_RC_025' => ['缴费总金额错误', '缴费总金额错误'],
    'API_RC_026' => ['请选择报名课程', '请选择报名课程'],
    'API_RC_027' => ['课程不存在', '课程不存在'],
    'API_RC_028' => ['订单金额不等于商品金额', '订单金额不等于商品金额'],
    'API_RC_029' => ['所报课程课次错误', '所报课程课次错误'],
    'API_RC_030' => ['订单ID错误', '订单ID错误'],
    'API_RC_031' => ['订单不存在', '订单不存在'],
    'API_RC_032' => ['课程已分班', '课程已分班'],
    'API_RC_033' => ['订单不是正常缴费状态', '订单不是正常缴费状态'],
    'API_RC_034' => ['退费数据错误', '退费数据错误'],
    'API_RC_035' => ['考勤数据错误', '考勤数据错误'],
    'API_RC_036' => ['学员订单不存在', '学员订单不存在'],
    'API_RC_037' => ['学员订单已退费', '学员订单已退费'],
    'API_RC_038' => ['课次已全部考勤', '课次已全部考勤'],
    'API_RC_039' => ['考勤课次错误', '考勤课次错误'],
    'API_RC_040' => ['考勤课次不存在', '考勤课次不存在'],
    'API_RC_041' => ['该课程已建班级，不可删除', '该课程已建班级，不可删除'],
    'API_RC_042' => ['该班级已有学员报名，不可删除', '该班级已有学员报名，不可删除'],
    'API_RC_043' => ['课程已被引用，只能修改名称、课次、单价信息', '课程已被引用，只能修改名称、课次、单价信息'],
    'API_RC_044' => ['有效期限已过不可分班', '有效期限已过不可分班'],
    'API_RC_045' => ['班级订单支付数据为空或数据有问题', '班级订单支付数据为空或数据有问题'],
    'API_RC_046' => ['至少使用一种支付方式进行支付', '至少使用一种支付方式进行支付'],
    'API_RC_047' => ['线上订单暂时不支持线下支付', '线上订单暂时不支持线下支付'],
    'API_RC_048' => ['订单状态有问题：此订单已取消或已退费或其它，不能支付！', '订单状态有问题：此订单已取消或已退费或其它，不能支付！'],
    'API_RC_049' => ['此订单已禁用，不能支付！', '此订单已禁用，不能支付！'],
    'API_RC_050' => ['主订单缴费金额不能大于订单总金额', '主订单缴费金额不能大于订单总金额'],
    'API_RC_051' => ['学员欠费不足以考勤一次课次', '学员欠费不足以考勤一次课次'],
    'API_RC_052' => ['编辑考勤课次错误', '编辑考勤课次错误'],
    'API_RC_053' => ['该班级已分班', '该班级已分班'],
    'API_RC_054' => ['班级名称必须', '班级名称必须'],

    'API_VOUCHER_001' => ['商家ID不能为空', '商家不正确！'],
    'API_VOUCHER_002' => ['订单ID不能为空', '订单不存在！'],
    'API_VOUCHER_003' => ['学员ID不能为空', '学员不存在！'],
    'API_VOUCHER_004' => ['家长ID不能为空', '家长不存在！'],
    'API_VOUCHER_005' => ['校区ID不能为空', '校区不存在！'],
    'API_VOUCHER_006' => ['充值ID不能为空', '充值不存在！'],
    
    'API_EXCEL_001' => ['上传文件格式不正确', '上传文件格式不正确'],
    'API_EXCEL_002' => ['请选择上传文件', '请选择上传文件'],
    'API_EXCEL_003' => ['创建文件目录失败', '创建文件目录失败'],
    'API_EXCEL_004' => ['文件上传失败', '文件上传失败'],
    'API_EXCEL_005' => ['上传文件内容为空', '上传文件内容为空'],
    'API_EXCEL_006' => ['上传模板不正确', '上传模板不正确'],
    'API_EXCEL_007' => ['导入数据有误', '导入数据有误'],
    'API_EXCEL_008' => ['导入商品失败', '导入商品失败'],

    'API_POINT_001' => ['商家ID不能为空', '商家不正确！'],
    'API_POINT_002' => ['规则ID不能为空', '规则不正确！'],
    'API_POINT_003' => ['规则名称不能为空', '规则名称不能为空！'],
    'API_POINT_004' => ['规则类型不能为空', '规则类型不能为空！'],
    'API_POINT_005' => ['创建人ID不能为空', '创建人不正确！'],
    'API_POINT_006' => ['修改人ID不能为空', '修改人不正确！'],
    'API_POINT_007' => ['平台ID不能为空', '平台不正确！'],
    'API_POINT_008' => ['家长ID不能为空', '家长不正确！'],
    'API_POINT_009' => ['积分ID不能为空', '积分不正确！'],
    'API_POINT_010' => ['积分不能为0', '积分不能为0！'],
    'API_POINT_011' => ['积分不足', '积分不足，不能使用！'],
    'API_POINT_012' => ['积分只能是正整数', '积分只能是正整数！'],


    'API_REPORT_001' => ['商家没有购买业务中心，无法查看', '商家没有购买业务中心，无法查看！'],
    'API_REPORT_002' => ['年份有误', '年份有误'],
    'API_REPORT_003' => ['页面类型有误', '页面类型有误'],

    'API_EMPLOYEE_008' => ['员工已经离职', '员工已经离职'],
    'API_EMPLOYEE_009' => ['员工未激活，不能使用系统', '员工未激活，不能使用系统'],
    'API_EMPLOYEE_010' => ['商家权限信息有误', '商家权限信息有误'],
    'API_EMPLOYEE_011' => ['老师有未上课程，不能进行删除操作!', '老师有未上课程，不能进行删除操作!'],
    'API_EMPLOYEE_012' => ['员工身份不可删除', '员工身份不可删除'],
    'API_EMPLOYEE_013' => ['已关联第三方机构不可删除', '已关联第三方机构不可删除'],

    'API_DEL_001' => ['订单非未支付状态，不允许删除！', '订单非未支付状态，不允许删除！'],

    'API_FINANCE_001' => ['撤销记录不存在', '撤销记录不存在'],
    'API_FINANCE_002' => ['申请退费记录不存在', '申请退费记录不存在'],
    
    'API_AUTO_001'    => ['不可重复升班','不可重复升班'],
    'API_AUTO_002'    => ['上课时间冲突','上课时间冲突'],

    'API_SHARE_SCHOOL_001'    => ['分享校区失败','共享校区失败'],
    'API_SHARE_SCHOOL_002'    => ['取消分享校区失败','取消共享校区失败'],
    'API_SHARE_SCHOOL_003'    => ['是否分享必填','is_share参数必须设置'],

    'API_HOLIDAY_001'    => ['听课日添加失败','日期格式错误'],
    'API_HOLIDAY_002'    => ['听课日删除失败','日期格式错误'],
    #全日制费用项
    'API_ACCOUNT_ITEM_OO1'  => ['费用项类型不能为空','费用项类型不能为空'],
    'API_ACCOUNT_ITEM_OO2'  => ['费用项类型非法','费用项类型非法'],
    'API_ACCOUNT_ITEM_OO3'  => ['费用项名称不能为空','费用项名称不能为空'],
    'API_ACCOUNT_ITEM_OO4'  => ['费用合计数字非法','费用合计数字非法'],
    'API_ACCOUNT_ITEM_OO5'  => ['是否退费类型错误','是否退费类型错误'],
    'API_ACCOUNT_ITEM_OO6'  => ['费用项名称不可重复','费用项名称不可重复'],
    'API_ACCOUNT_ITEM_OO7'  => ['费用项ID格式不正确','费用项ID格式不正确'],

    #全日制退费设置
    'API_REFUND_QRZ_OO1'  => ['退费机制名称不能为空','退费机制名称不能为空'],
    'API_REFUND_QRZ_OO2'  => ['课时数量非法','课时数量非法'],
    'API_REFUND_QRZ_OO3'  => ['退费百分比非法','退费百分比非法'],
    'API_REFUND_QRZ_OO4'  => ['退费机制名称不能重复','退费机制名称不能重复'],
    'API_REFUND_QRZ_OO5'  => ['名称、保底课时量、退费百分比不可重复','名称、保底课时量、退费百分比不可重复'],
    'API_REFUND_QRZ_OO6'  => ['退费机制ID不能为空','退费机制ID不能为空'],
    'API_REFUND_QRZ_OO7'  => ['退费机制ID格式错误','退费机制ID格式错误'],
    'API_REFUND_QRZ_OO8'  => ['退费机制不存在','退费机制不存在'],
    'API_REFUND_QRZ_OO9'  => ['退费机制已被使用不可删除','退费机制已被使用不可删除'],
    'API_REFUND_QRZ_O10'  => ['请输入当前范围内的保底课时量','请输入当前范围内的保底课时量'],
    'API_REFUND_QRZ_O11'  => ['退费规则不能为空','退费规则不能为空'],
    'API_REFUND_QRZ_O12'  => ['退费规则不合法','退费规则不合法'],
    'API_REFUND_QRZ_O13'  => ['退费服务费率不能为空','退费服务费率不能为空'],

    #全日制学费方案
    'API_COURSE_QRZ_OO1'  => ['课程名称不能为空','课程名称不能为空'],
    'API_COURSE_QRZ_OO2'  => ['学费不能为空','学费不能为空'],
    'API_COURSE_QRZ_OO3'  => ['杂费不能为空','杂费不能为空'],
    'API_COURSE_QRZ_OO4'  => ['食宿费不能为空','食宿费不能为空'],
    'API_COURSE_QRZ_OO5'  => ['费用合计不能为空','费用合计不能为空'],
    'API_COURSE_QRZ_OO6'  => ['课时数量不能为空','课时数量不能为空'],
    'API_COURSE_QRZ_OO7'  => ['收费项不能为空','收费项不能为空'],
    'API_COURSE_QRZ_OO8'  => ['学科不能为空','学科不能为空'],
    'API_COURSE_QRZ_OO9'  => ['课程ID不能为空','课程ID不能为空'],
    'API_COURSE_QRZ_O10'  => ['课程ID不正确','课程ID不正确'],
    'API_COURSE_QRZ_O11'  => ['暂无满足条件的方案','暂无满足条件的方案'],
    'API_COURSE_QRZ_O12'  => ['订单信息错误','订单信息错误'],
    'API_COURSE_QRZ_O13'  => ['学费金额合计不正确','学费金额合计不正确'],
    'API_COURSE_QRZ_O14'  => ['方案名称已存在','方案名称已存在'],

    #全日制班级
    'API_GOODS_CLASS_QRZ_OO1'  => ['班级名称不能为空','班级名称不能为空'],
    'API_GOODS_CLASS_QRZ_OO2'  => ['班级人数不能为空','班级人数不能为空'],
    'API_GOODS_CLASS_QRZ_OO3'  => ['学期不能为空','学期不能为空'],
    'API_GOODS_CLASS_QRZ_OO4'  => ['学期类型不合法','学期类型不合法'],
    'API_GOODS_CLASS_QRZ_OO5'  => ['教室ID不能为空','教室ID不能为空'],
    'API_GOODS_CLASS_QRZ_OO6'  => ['教室ID不合法','教室ID不合法'],
    'API_GOODS_CLASS_QRZ_OO7'  => ['班级ID不能为空','班级ID不能为空'],
    'API_GOODS_CLASS_QRZ_OO8'  => ['班级ID不合法','班级ID不合法'],
    'API_GOODS_CLASS_QRZ_OO9'  => ['当前班级不存在','当前班级不存在'],
    'API_GOODS_CLASS_QRZ_O10'  => ['当前添加学员人数已超过班级容量','当前添加学员人数已超过班级容量'],
    'API_GOODS_CLASS_QRZ_O11'  => ['班级名称已存在','班级名称已存在'],

    #全日制排课规则（排课任务）
    'API_LESSON_RULE_QRZ_OO1'  => ['排课规则ID不能为空','排课规则ID不能为空'],
    'API_LESSON_RULE_QRZ_OO2'  => ['排课规则ID不合法','排课规则ID不合法'],
    'API_LESSON_RULE_QRZ_OO3'  => ['任务名称不能为空','规则名称不能为空'],
    'API_LESSON_RULE_QRZ_OO4'  => ['开始时间不能为空','开始时间不能为空'],
    'API_LESSON_RULE_QRZ_OO5'  => ['课间休息时长不能为空','课间休息时长不能为空'],
    'API_LESSON_RULE_QRZ_OO6'  => ['晨读开始时间不能为空','晨读开始时间不能为空'],
    'API_LESSON_RULE_QRZ_OO7'  => ['晨读课间时间不能为空','晨读课间时间不能为空'],
    'API_LESSON_RULE_QRZ_OO8'  => ['晨读课时数量不能为空','晨读课时数量不能为空'],
    'API_LESSON_RULE_QRZ_OO9'  => ['正课开始时间不能为空','正课开始时间不能为空'],
    'API_LESSON_RULE_QRZ_O10'  => ['正课课时时长不能为空','正课课时时长不能为空'],
    'API_LESSON_RULE_QRZ_O11'  => ['上午课时数量不能为空','上午课时数量不能为空'],
    'API_LESSON_RULE_QRZ_O12'  => ['午休时长不能为空','午休时长不能为空'],
    'API_LESSON_RULE_QRZ_O13'  => ['下午课时数量不能为空','下午课时数量不能为空'],
    'API_LESSON_RULE_QRZ_O14'  => ['晚自习开始时间不能为空','晚自习开始时间不能为空'],
    'API_LESSON_RULE_QRZ_O15'  => ['晚自习课时时长不能为空','晚自习课时时长不能为空'],
    'API_LESSON_RULE_QRZ_O16'  => ['晚自习课时数量不能为空','晚自习课时数量不能为空'],
    'API_LESSON_RULE_QRZ_O17'  => ['是否跳过周末必须','是否跳过周末必须'],
    'API_LESSON_RULE_QRZ_O18'  => ['是否跳过节假日必须','是否跳过节假日必须'],
    'API_LESSON_RULE_QRZ_O19'  => ['该条件还未创建班级','该条件还未创建班级'],
    'API_LESSON_RULE_QRZ_O20'  => ['操作失败','删除排课规则和相关数据失败'],
    'API_LESSON_RULE_QRZ_O21'  => ['操作失败','删除排课规则失败'],
    'API_LESSON_RULE_QRZ_O22'  => ['操作失败','删除排课规则详情失败'],
    'API_LESSON_RULE_QRZ_O23'  => ['操作失败','删除排课规则详情数据失败'],
    'API_LESSON_RULE_QRZ_O24'  => ['操作失败','删除排课课程失败'],
    'API_LESSON_RULE_QRZ_O25'  => ['该排课任务不存在','该排课任务不存在'],
    'API_LESSON_RULE_QRZ_O26'  => ['该条件的排课任务已存在,请勿重复重复添加','该条件的排课任务已存在'],
    'API_LESSON_RULE_QRZ_O27'  => ['排课详情数据ID必须','排课详情数据ID必须'],
    'API_LESSON_RULE_QRZ_O28'  => ['排课详情数据ID不合法','排课详情数据ID不合法'],
    'API_LESSON_RULE_QRZ_O29'  => ['日期不能为空','日期不能为空'],
    'API_LESSON_RULE_QRZ_O30'  => ['课次ID不能为空','课次ID不能为空'],
    'API_LESSON_RULE_QRZ_O31'  => ['课次ID不合法','课次ID不合法'],
    'API_LESSON_RULE_QRZ_O32'  => ['星期不能为空','星期不能为空'],
    'API_LESSON_RULE_QRZ_O33'  => ['课时开始时间不能为空','课时开始时间不能为空'],
    'API_LESSON_RULE_QRZ_O34'  => ['课时结束时间不能为空','课时结束时间不能为空'],
    'API_LESSON_RULE_QRZ_O35'  => ['学生考勤数据不能为空','学生考勤数据不能为空'],
    'API_LESSON_RULE_QRZ_O36'  => ['学生考勤数据不合法','学生考勤数据不合法'],
    'API_LESSON_RULE_QRZ_O37'  => ['正课开课时间不合法','正课开课时间不合法'],
    'API_LESSON_RULE_QRZ_O38'  => ['晚自习开课时间不合法','晚自习开课时间不合法'],
    'API_LESSON_RULE_QRZ_O39'  => ['请排课后再点击完成','您还未排课'],
    'API_LESSON_RULE_QRZ_O40'  => ['排课总时长不合法','排课总时长不合法'],


    #全日制缴费
    'API_COURSE_ORDER_QRZ_O01'  => ['对不起，相同年份和学期不可以重复报名','对不起，相同年份和学期不可以重复报名'],
    'API_COURSE_ORDER_QRZ_O02'  => ['学费金额错误','学费金额错误'],
    'API_COURSE_ORDER_QRZ_O03'  => ['杂费金额错误','杂费金额错误'],
    'API_COURSE_ORDER_QRZ_O04'  => ['食宿费金额错误','食宿费额错误'],
    'API_COURSE_ORDER_QRZ_O05'  => ['费用项ID不可为空','费用项ID不可为空'],
    'API_COURSE_ORDER_QRZ_O06'  => ['请选择新生还是老生','请选择新生还是老生'],
    'API_COURSE_ORDER_QRZ_O07'  => ['暂无满足条件的可退费课程','暂无满足条件的可退费课程'],
    'API_COURSE_ORDER_QRZ_O08'  => ['退费课程ID不能为空','请选择退费课程'],
    'API_COURSE_ORDER_QRZ_O09'  => ['购买课时不能为空','购买课时不能为空'],
    'API_COURSE_ORDER_QRZ_O10'  => ['实缴、优惠金额与总金额不符','实缴、优惠金额与总金额不符'],
    'API_COURSE_ORDER_QRZ_O12'  => ['学、杂、食宿费与总金额不符','学、杂、食宿费与总金额不符'],
    'API_COURSE_ORDER_QRZ_O13'  => ['多种付费方式总额与实缴金额不符','多种付费方式总额与实缴金额不符'],
    'API_COURSE_ORDER_QRZ_O14'  => ['所选年份、学期与方案不符','所选年份、学期与方案不符'],
    'API_COURSE_ORDER_QRZ_O15'  => ['请选择缴费类型','请选择缴费类型'],
    'API_COURSE_ORDER_QRZ_O16'  => ['暂无订单相关信息','暂无订单相关信息'],
    'API_COURSE_ORDER_QRZ_O17'  => ['直减金额不能大于合计金额','直减金额不能大于合计金额'],
    'API_COURSE_ORDER_QRZ_O18'  => ['直减金额不能大于学费金额','直减金额不能大于学费金额'],
    'API_COURSE_ORDER_QRZ_O19'  => ['不存在该教学方案，请确认','不存在该教学方案，请确认'],
    'API_YEAR_NAME_QRZ_O01'  => ['年份选项不能为空','请选择年份'],
    'API_YEAR_NAME_QRZ_O02'  => ['年份选项格式不正确','年份选项格式不正确'],
    'API_SEMESTER_QRZ_O01'  => ['学期选项不能为空','请选择学期'],
    'API_SEMESTER_QRZ_O02'  => ['请选择合法的学期选项','请选择合法的学期选项'],

    #退费
    'API_COURSE_QRZ_REFUND_O01'  => ['退费申请ID不能为空','退费申请ID不能为空'],
    'API_COURSE_QRZ_REFUND_O02'  => ['课程订单ID不能为空','课程订单ID不能为空'],
    'API_COURSE_QRZ_REFUND_O03'  => ['课程主订单ID不能为空','课程主订单ID不能为空'],
    'API_COURSE_QRZ_REFUND_O04'  => ['暂无退费申请相关信息','暂无退费申请相关信息'],
    'API_COURSE_QRZ_REFUND_O05'  => ['退费失败','退费失败'],
    'API_COURSE_QRZ_REFUND_O06'  => ['请填写完整的转账信息','退费请填写完整的转账信息失败'],
    'API_COURSE_QRZ_REFUND_O07'  => ['请输入正确的银行卡号','请输入正确的银行卡号'],
    'API_COURSE_QRZ_REFUND_O08'  => ['请选择正确的退费方式','请选择正确的退费方式'],
    'API_COURSE_QRZ_REFUND_O09'  => ['退费金额不能为空','退费金额不能为空'],
    'API_COURSE_QRZ_REFUND_O010' => ['退费课程ID不能为空','退费课程ID不能为空'],
    'API_COURSE_QRZ_REFUND_O011' => ['转账银行不能为空','转账银行不能为空'],
    'API_COURSE_QRZ_REFUND_O012' => ['银行卡号不能为空','银行卡号不能为空'],
    'API_COURSE_QRZ_REFUND_O013' => ['持卡人不能为空','持卡人不能为空'],
    'API_COURSE_QRZ_REFUND_O014' => ['退费步骤参数不正确','退费步骤参数不正确'],
    'API_COURSE_QRZ_REFUND_O015' => ['退费ID不能为空','退费ID不能为空'],
    'API_COURSE_QRZ_REFUND_O016' => ['退费ID不合法','退费ID不合法'],
    'API_COURSE_QRZ_REFUND_O017' => ['退费申请ID不能为空','退费申请ID不能为空'],
    'API_COURSE_QRZ_REFUND_O018' => ['退费申请ID不合法','退费申请ID不合法'],

    #v5.3.3 liuyulong start
    'API_SEASON_001' => ['学期ID不合法','学期ID不合法'],
    'API_SEASON_002' => ['学期名称不能为空','学期名称不能为空'],
    'API_SEASON_003' => ['学期名称重复，请更换名称','学期名称重复，请更换名称'],
    'API_SEASON_004' => ['学期序号不合法','学期序号不合法'],
    'API_SEASON_005' => ['学期序号重复，请更换序号','学期序号重复，请更换序号'],
    'API_SEASON_006' => ['学期序号不能为空','学期序号不能为空'],
    'API_SEASON_007' => ['学期不能超过12个','学期不能超过12个'],
    'API_SEASON_008' => ['学期添加失败','学期添加失败'],
    'API_SEASON_009' => ['序号不能重复','序号不能重复'],
    
    'API_RESIGNUP_001' => ['续报ID不合法','续报ID不合法'],
    'API_RESIGNUP_002' => ['序号不合法','序号不合法'],
    'API_RESIGNUP_003' => ['不允许一个学期续多个学期','不允许一个学期续多个学期'],
    'API_RESIGNUP_004' => ['不允许多个学期续一个学期','不允许多个学期续一个学期'],
    'API_RESIGNUP_005' => ['是否显示未续报学员非法','是否显示未续报学员非法'],
    'API_RESIGNUP_006' => ['续报顺序不合法','续报顺序不合法'],
    #v5.3.3 liuyulong end

    'API_PAY_NOTIFY_001' => ['支付状态必填', '支付状态必填'],
    'API_PAY_NOTIFY_002' => ['未查询到充值记录', '未查询到充值记录'],
    'API_PAY_NOTIFY_003' => ['未查询到子订单记录', '未查询到子订单记录'],
    'API_PAY_NOTIFY_004' => ['实付金额必填', '实付金额必填'],
    'API_PAY_NOTIFY_005' => ['支付方式必填', '支付方式必填'],
    'API_REFUND_NOTIFY_001' => ['退款状态必填', '退款状态必填'],
    'API_REFUND_NOTIFY_002' => ['经办人必填', '经办人必填'],
    'API_ORDER_001' => ['子订单id不能为空','子订单id不能为空'],
    'API_ORDER_002' => ['订单类型不能为空','订单类型不能为空'],


    #课程类型
    'API_COURSE_TYPE_O01' => ['课程类型名称不能为空','课程类型名称不能为空'],
    'API_COURSE_TYPE_O02' => ['是否算新老生不能为空','是否算新老生不能为空'],
    'API_COURSE_TYPE_O03' => ['是否算续报不能为空','是否算续报不能为空'],
    'API_COURSE_TYPE_O04' => ['续报周期不能为空','续报周期不能为空'],
    'API_COURSE_TYPE_O05' => ['是否算转化不能为空','是否算转化不能为空'],
    'API_COURSE_TYPE_O06' => ['转化周期不能为空','转化周期不能为空'],
    'API_COURSE_TYPE_O07' => ['是否算报名不能为空','是否算报名不能为空'],
    'API_COURSE_TYPE_O08' => ['是否算在校不能为空','是否算在校不能为空'],
    'API_COURSE_TYPE_O09' => ['课程类型名称已存在','课程类型名称已存在'],
    'API_COURSE_TYPE_O010' => ['课程类型id不存在','课程类型id不存在'],
    'API_COURSE_TYPE_O011' => ['停用启用参数错误','停用启用参数错误'],
    'API_COURSE_TYPE_O012' => ['填写的周期格式不正确','填写的周期格式不正确'],
    'API_ADVANCE_STATISTICS_001' => ['消耗年份必须大于等于班级年份', '消耗年份必须大于等于班级年份'],

    // 结算报表
    'API_SETTLEMENTREPORT_001' => ['开始日期不能为空','开始日期不能为空'],
    'API_SETTLEMENTREPORT_002' => ['结束日期不能为空','结束日期不能为空'],
    'API_SETTLEMENTREPORT_003' => ['日期区间最大为一个月','日期区间最大为一个月'],
    'API_SETTLEMENTREPORT_004' => ['请选择校区','请选择校区'],

    // 一对一退费
    'API_OTO_REFUND_001' => ['已退费课次不能补课','已退费课次不能补课'],
    //优惠券
    'API_COUPONS_001' => ['优惠金额不存在','优惠金额不存在'],
    'API_COUPONS_002' => ['金额格式不正确','金额格式不正确'],
    'API_COUPONS_003' => ['使用期限格式不正确','请正确填写使用期限'],
    'API_COUPONS_004' => ['金额格式不正确','金额格式不正确'],
    'API_COUPONS_005' => ['发放人不存在','发放人不存在'],
    'API_COUPONS_006' => ['审核人不存在','审核人不存在'],
    'API_COUPONS_007' => ['发放分校不存在','发放分校不存在'],
    'API_COUPONS_008' => ['发放校区不存在','发放校区不存在'],
    'API_COUPONS_009' => ['优惠券批次不存在','优惠券批次不存在'],
    'API_COUPONS_010' => ['停用类型状态不正确','停用类型状态不正确'],
    'API_COUPONS_011' => ['代金券剩余可申请发放数量不足','代金券剩余可申请发放数量不足'],
    'API_COUPONS_012' => ['优惠券名称不能为空','优惠券名称不能为空'],
    'API_COUPONS_013' => ['优惠券描述不能为空','优惠券描述不能为空'],
    'API_COUPONS_014' => ['已申请或已发放的学员不能重复申请','已申请或已发放的学员不能重复申请'],

    //图片
    'API_UPLOAD_002' => ['图片格式不正确','图片格式不正确'],
    'API_UPLOAD_003' => ['图片大小不正确','图片大小不正确'],
    'API_UPLOAD_004' => ['图片上传失败','图片上传失败'],

    //优惠券发放审核
    'API_COUPONS_APPLY_001' => ['申请审核ID不能为空','申请审核ID不正确'],
    'API_COUPONS_APPLY_002' => ['驳回原因不能为空','驳回原因不能为空'],

    //艺体课统计
    'API_ROLL_STATISTICS_001' => ['教师id不能为空','教师id不能为空'],
    'API_ROLL_STATISTICS_002' => ['类型不存在','类型不存在'],
    //体态评估
    'API_ASSESS_001' => ['体态ID不正确', '体态ID不正确'],
    'API_ASSESS_002' => ['评估人ID不正确', '评估人ID不正确'],
    //配置合同【艺体课】
    'API_CONTRACT_001' => ['页眉图片地址不能为空', '页眉图片地址不能为空'],
    'API_CONTRACT_002' => ['页脚图片地址不能为空', '页脚图片地址不能为空'],
    'API_CONTRACT_003' => ['合同内容不能为空', '合同内容不能为空'],

    // im群组
    'API_IM_001' => ['IM群组ID不正确', '群组ID不正确'],
    'API_IM_002' => ['IM用户ID不正确', '用户ID不正确'],
];
