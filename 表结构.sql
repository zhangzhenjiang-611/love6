uc_merchant_student  商家学员关系表

CREATE TABLE `uc_merchant_student` (zz
  `id` char(32) NOT NULL COMMENT '主键ID',
  `merchant_id` char(32) NOT NULL COMMENT '商家ID',
  `student_id` char(32) NOT NULL COMMENT '学员ID',
  `brand_id` char(32) NOT NULL DEFAULT '' COMMENT '品牌ID',
  `stu_num` varchar(32) DEFAULT NULL COMMENT '学号',
  `idcard` varchar(18) DEFAULT NULL COMMENT '身份证号',
  `grade_type_id` char(32) DEFAULT NULL COMMENT '年部ID',
  `grade_id` char(32) DEFAULT NULL COMMENT '年级ID',
  `campus_id` char(32) DEFAULT NULL COMMENT '校区ID',
  `origin_id` char(32) DEFAULT NULL COMMENT '学员来源ID',
  `creator_id` char(32) NOT NULL COMMENT '创建人ID',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modifier_id` char(32) NOT NULL COMMENT '修改人ID',
  `modified` datetime NOT NULL COMMENT '修改时间',
  `data_enter_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '数据进入方式(1:正常功能 2:导入功能 3:脚本导入)',
  `clue_id` char(32) DEFAULT NULL COMMENT '线索ID',
  `height` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '身高(单位:cm)',
  `weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '体重(单位:g)',
  `address` varchar(255) DEFAULT '' COMMENT '地址',
  `email` varchar(50) DEFAULT '' COMMENT '邮箱',
  `remark` text COMMENT '备注',
  `student_school_id` char(32) DEFAULT NULL COMMENT '生源校ID',
  `student_school_class_id` char(32) DEFAULT '' COMMENT '生源校班级ID',
  `student_school_grade_id` char(32) DEFAULT '' COMMENT '生源校年级ID',
  `student_school_class` varchar(64) DEFAULT NULL COMMENT '生源校班级',
  `student_school_teacher_name` varchar(64) DEFAULT NULL COMMENT '生源校教师',
  `student_school_teacher_phone` varchar(11) DEFAULT NULL COMMENT '生源校教师手机',
  `introd_employee_id` char(32) DEFAULT NULL COMMENT '转介绍员工ID',
  `introd_student_id` char(32) DEFAULT NULL COMMENT '转介绍学员ID',
  `is_old_student` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '是否老生(1:是 2:否)',
  `is_reading` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '是否在读(1:是 2:否)',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '是否删除(1:是 2:否)',
  `is_real_student` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否真实学员(1:是 2:否)，某些用户规则缴费才是真实学员',
  `grade` varchar(20) NOT NULL DEFAULT '' COMMENT '学员年部年级',
  `province_id` char(32) NOT NULL DEFAULT '' COMMENT '省ID',
  `city_id` char(32) NOT NULL DEFAULT '' COMMENT '市ID',
  `district_id` char(32) NOT NULL DEFAULT '' COMMENT '区ID',
  `community_id` char(32) NOT NULL DEFAULT '' COMMENT '社区ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_merchant_brand_student_id` (`merchant_id`,`student_id`,`brand_id`) USING BTREE,
  KEY `idx_merchant_id` (`merchant_id`),
  KEY `idx_student_id` (`student_id`),
  KEY `idx_clue_id` (`clue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商家学员关系表';


uc_parent_student  家长学员关系表
CREATE TABLE `uc_parent_student` (
  `id` char(32) NOT NULL COMMENT '主键ID',
  `parent_id` char(32) NOT NULL COMMENT '家长ID',
  `student_id` char(32) NOT NULL COMMENT '学员ID',
  `relation` tinyint(1) unsigned DEFAULT NULL COMMENT '关系(1:母亲 2:父亲 3:奶奶 4:爷爷 5:姥姥 6:姥爷 7:其他)',
  `data_enter_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '数据进入方式(1:正常功能 2:导入功能 3:脚本导入)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_parent_id_student_id` (`parent_id`,`student_id`),
  KEY `idx_student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='家长学员关系表';

家长机构账户表
CREATE TABLE `parent_account_merchants` (
  `id` char(32) NOT NULL COMMENT '主键ID',
  `merchant_id` char(32) NOT NULL COMMENT '商家ID',
  `parent_id` char(32) NOT NULL COMMENT '家长ID',
  `pay_password` char(32) DEFAULT NULL COMMENT '家长在此商家的支付密码',
  `balances` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额(本金)',
  `withdraw_balances` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '可提现余额',
  `lock` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '锁定金额',
  `give_amount_balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '赠金余额',
  `total_give_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '累计赠金',
  `total_refund_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '累计退费',
  `total_consume_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '累计消费',
  `total_earn_point` int(11) NOT NULL DEFAULT '0' COMMENT '累计赚积分',
  `total_use_point` int(11) NOT NULL DEFAULT '0' COMMENT '累计用积分',
  `total_expired_point` int(11) NOT NULL DEFAULT '0' COMMENT '累计过期积分',
  `is_usable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可用(1:可用 2:冻结)',
  `creator_id` char(32) NOT NULL COMMENT '创建人ID',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modifier_id` char(32) NOT NULL COMMENT '修改人ID',
  `modified` datetime NOT NULL COMMENT '修改时间',
  `data_enter_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '数据进入方式(1:正常功能 2:导入功能 3:脚本导入)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_merchant_id_parent_id` (`merchant_id`,`parent_id`),
  KEY `idx_merchant_id` (`merchant_id`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='家长机构账户表';

涂来涂去二开 修改课次 考勤 电子合同【100%】
处理jira提案    【100%】
 


 CREATE TABLE `bi_order_class_otms_statistics` (
  `id` char(32) NOT NULL COMMENT '统计主键ID',
  `merchant_id` char(32) NOT NULL COMMENT '商家ID',
  `student_id` char(32) NOT NULL COMMENT '学员ID',
  `goods_id` char(32) NOT NULL COMMENT '班级ID',
  `sub_order_ids` text NOT NULL COMMENT '子订单ID,多个子订单用英文逗号分隔',
  `sign_up_time` datetime NOT NULL COMMENT '报名时间',
  `retreat_time` datetime DEFAULT NULL COMMENT '转班/退班时间',
  `year_name` varchar(10) DEFAULT '' COMMENT '年份',
  `season_id` char(32) DEFAULT '' COMMENT '季节ID',
  `campus_id` char(32) DEFAULT NULL COMMENT '校区ID',
  `grade_type_id` char(32) DEFAULT '' COMMENT '年部ID',
  `grade_id` char(32) DEFAULT '' COMMENT '年级ID',
  `category_level` text COMMENT '学科分类ID',
  `class_type_id` char(32) DEFAULT '' COMMENT '班型ID',
  `classroom_id` char(32) DEFAULT '' COMMENT '教室ID',
  `teacher_id` char(32) DEFAULT '' COMMENT '教师ID',
  `open_date` datetime DEFAULT NULL COMMENT '开课日期',
  `end_date` datetime DEFAULT NULL COMMENT '结课日期',
  `created` datetime DEFAULT NULL COMMENT '创建时间',
  `modified` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_student_id_goods_id` (`student_id`,`goods_id`),
  KEY `idx_year_name` (`year_name`),
  KEY `idx_season_id` (`season_id`),
  KEY `idx_campus_id` (`campus_id`),
  KEY `idx_classroom_id` (`classroom_id`),
  KEY `idx_grade_type_id` (`grade_type_id`),
  KEY `idx_grade_id` (`grade_id`),
  KEY `idx_class_type_id` (`class_type_id`),
  KEY `idx_teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小班订单统计表';





 %SystemRoot%\system32;%SystemRoot%;%SystemRoot%\System32\Wbem;%SYSTEMROOT%\System32\WindowsPowerShell\v1.0\;C:\Program Files (x86)\HP\IdrsOCR_15.2.10.1114\;C:\composer;E:\bin;E:\Git\cmd;D:\wamp\bin\php\php7.1.26;C:\phalcon-devtools;



