<?php
/**
 * 系统默认积分规则配置
 * Author: wangjunjie
 * Date: 2018/01/12
 * Time: 09:59
 * File: point_rule.php
 */
return [
	//规则配置
	'point_rule' => [
		//赚积分
		'e_signup' => [
			'name'      => '报读消费',
			'type'      => 1,
			'code'      => 'e_signup',
			'detail'    => ['x' => 100, 'y' => 1, 'r' => false],
			'rule'      => '每{X}元，赚{Y}积分。',
			'tips'      => '不足{X}元的部分，不积分，不折算。',
			'remark'    => '',
			'is_usable' => 2,
			'is_system' => 1,
		],
		/* 暂时不开发
		'e_buy_goods' => [
			'name'      => '购买商品',
			'type'      => 1,
			'code'      => 'e_buy_goods',
			'detail'    => ['x' => 100, 'y' => 1, 'r' => false],
			'rule'      => '每{X}元，赚{Y}积分。',
			'tips'      => '不足{X}元的部分，不积分，不折算。',
			'remark'    => '',
			'is_usable' => 2,
			'is_system' => 1,
		],
		*/
		'e_parent_account_recharge' => [
			'name'      => '账户充值',
			'type'      => 1,
			'code'      => 'e_parent_account_recharge',
			'detail'    => ['x' => 100, 'y' => 1, 'r' => false],
			'rule'      => '每{X}元，赚{Y}积分。',
			'tips'      => '不足{X}元的部分，不积分，不折算。',
			'remark'    => '',
			'is_usable' => 2,
			'is_system' => 1,
		],
		'e_attendance' => [
			'name'      => '学员上课出勤',
			'type'      => 1,
			'code'      => 'e_attendance',
			'detail'    => ['x' => 1, 'y' => 1, 'r' => true],
			'rule'      => '每出勤{X}次，赚{Y}积分。',
			'tips'      => '每出勤{X}次，赚{Y}积分。',
			'remark'    => '',
			'is_usable' => 2,
			'is_system' => 1,
		],
		/* 暂时不开发
		'e_comment_teacher' => [
			'name'      => '学员评价教师',
			'type'      => 1,
			'code'      => 'e_comment_teacher',
			'detail'    => ['x' => 1, 'y' => 1, 'r' => true],
			'rule'      => '每评价{X}次，赚{Y}积分。',
			'tips'      => '每评价{X}次，赚{Y}积分。',
			'remark'    => '',
			'is_usable' => 2,
			'is_system' => 1,
		],
		*/
		//用积分
		'u_attendance' => [
			'name'      => '学员上课缺勤',
			'type'      => 2,
			'code'      => 'u_attendance',
			'detail'    => ['x' => 1, 'y' => 1, 'r' => true],
			'rule'      => '每缺勤{X}次，用{Y}积分。',
			'tips'      => '每缺勤{X}次，用{Y}积分。',
			'remark'    => '',
			'is_usable' => 2,
			'is_system' => 1,
		],
		'u_refund' => [
			'name'      => '学员退费',
			'type'      => 2,
			'code'      => 'u_refund',
			'detail'    => ['x' => 100, 'y' => 1, 'r' => false],
			'rule'      => '每{X}元，用{Y}积分。',
			'tips'      => '不足{X}元的部分，不用积分。',
			'remark'    => '',
			'is_usable' => 2,
			'is_system' => 1,
		],
		'u_signup' => [
			'name'      => '报读班级',
			'type'      => 2,
			'code'      => 'u_signup',
			'detail'    => ['x' => 100, 'y' => 1, 'm' => '10000', 'r' => false],
			'rule'      => '每{X}积分，抵扣¥{Y}元，单次最多使用{M}积分。',
			'tips'      => '每{X}积分，抵扣¥{Y}元，单次最多使用{M}积分。',
			'remark'    => '',
			'is_usable' => 2,
			'is_system' => 1,
		],
		//有效期
		'valid_term' => [
			'name'      => '有效期',
			'type'      => 3,
			'code'      => 'valid_term',
			'detail'    => ['x' => 1, 'u' => 'Y'], //Y:年，M:月 D:日
			'is_usable' => 2,
			'is_system' => 1,
		],
	],
	//赚规则提示
	'e_rule_tip' => '赚{Y}积分。',
	//用规则提示
	'd_rule_tip' => '用{Y}积分。',
	//有效期编码
	'valid_code' => 'valid_term',
];