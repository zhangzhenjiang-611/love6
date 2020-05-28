<?php
namespace Manage\Model;
use Think\Model\RelationModel;
class UserModel extends RelationModel{
    protected $_link=array(
        'Role'=>array(
            'mapping_type'      =>  self::MANY_TO_MANY,
            'foreign_key'       =>  'user_id',
            'relation_key'  =>  'role_id',
            'relation_table'    =>  'ss_role_user', //此处应显式定义中间表名称，且不能使用C函数读取表前缀
            'mapping_fields'   =>   'id,name'

            
        ),

        );
}