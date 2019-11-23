<?php
/**
 * 操作日志列表菜单配置
 * Created by PhpStorm.
 * User: jl
 * Date: 2019/4/18
 * Time: 10:37
 */
return [
    'operation' => [
        '7f43c8dca0245e456393e31b97756589' => [
            'name'  => '前台业务',
            'level' => 1,
            'pid'   => ''
        ],
        '1236ecddb8424794b9ad47c7febdf02d' => [
            'name'  => '班课',
            'level' => 2,
            'pid'   => '7f43c8dca0245e456393e31b97756589'
        ],
        'd8c689172b434e5c9f7ad412bb17aba9' => [
            'name'  => '查看学员',
            'level' => 3,
            'pid'   => '1236ecddb8424794b9ad47c7febdf02d'
        ],
        '01b521c1ae5f4d4aa88566668f8e3516' => [
            'name'  => '学员信息',
            'level' => 4,
            'pid'   => 'd8c689172b434e5c9f7ad412bb17aba9'
        ],

        '01b521c1ae5f4d4aa88566668f8e3514' => [
            'name'  => '优惠管理',
            'level' => 1,
            'pid'   => ''
        ],
        '01b521c1ae5f4d4aa88566668f8e3515' => [
            'name'  => '优惠券管理',
            'level' => 2,
            'pid'   => '01b521c1ae5f4d4aa88566668f8e3514'
        ],
        '01b521c1ae5f4d4aa88566668f8e3517' => [
            'name'  => '查看优惠券',
            'level' => 3,
            'pid'   => '01b521c1ae5f4d4aa88566668f8e3515'
        ],
        '01b521c1ae5f4d4aa88566668f8e3519'=>[
            'name'  => '新增优惠券',
            'level' => 4,
            'pid'   => '01b521c1ae5f4d4aa88566668f8e3517'
        ], 
    ],
    'attribute' =>[
        '01b521c1ae5f4d4aa88566668f8e3516' => [
            'name'                         => '学员姓名',
            'birthday'                     => '学员生日',
            'sex'                          => '学员性别',
            'phone'                        => '家长手机',
            'parent_id'                    => '家长ID',
            'parent_name'                  => '家长姓名',
            'relation'                     => '亲属关系',//uc_parent_student
            'campus_id'                    => '所属校区',
            'contacter'                    => '紧急联系人',
            'telephone'                    => '紧急联系电话',
            'grade'                        => '学员年级',
            'introd_employee_id'           => '转介绍员工',
            'introd_student_id'            => '转介绍学员',
            'student_school_id'            => '生源学校',
            'student_school_class'         => '生源校班级',
            'student_school_teacher_name'  => '生源校班主任',
            'student_school_teacher_phone' => '生源校班主任电话',
        ],
        '01b521c1ae5f4d4aa88566668f8e3519' => [
            'name'              => '名称',
            'use_limit_amount'  => '使用门槛',
            'coupon_start_time' => '有效期开始时间',
            'coupon_end_time'   => '有效期结束时间',
            'releaser_ids'      => '发放人',
            'auditor_ids'       => '审核人',
            'branch_ids'        => '分校',
            'campus_ids'        => '校区',
            'branch_ids_usable' => '可用分校',
            'campus_ids_usable' => '可用校区',
            'quantity'          => '最大发放量',
            'remark'            => '优惠券描述',
            'count'             => '附件',
        ],
    ],
    'source' =>[
        'student' => '01b521c1ae5f4d4aa88566668f8e3516',
        'coupons' => '01b521c1ae5f4d4aa88566668f8e3519',
        'coupon_applys' => '01b521c1ae5f4d4aa88566668f8e3518',
        'coupon_release'=>'01b521c1ae5f4d4aa88566668f8e3520',
    ]
];