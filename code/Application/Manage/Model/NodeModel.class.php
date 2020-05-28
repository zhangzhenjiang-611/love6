<?php
namespace Manage\Model;
use Think\Model\RelationModel;
//关联模型
class NodeModel extends RelationModel{
    protected $_link = array(
            'Node'=> array(
            'mapping_type'      =>  self::HAS_MANY,//HAS_MANY 关联表示当前模型拥有多个子对象
            'mapping_name'       =>  'node',
            'mapping_order'  =>  'sort',
            'parent_key'    =>  'pid',
           
            
        ),
    );
}



?>