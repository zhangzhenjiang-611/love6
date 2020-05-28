<?php

namespace app\common\model;

use think\Model;

class AuthRule extends Model
{
    //添加数据
    public function add($data) {
        $validate = new \app\common\validate\AuthRule();
        if (!$validate->scene('add')->check($data)) {
            return $validate->getError();
        }
        $result = $this->allowField(true)->save($data);
        if ($result) {
            return 1;
        } else {
            return '权限添加失败';
        }
    }

    //无限极分类树
    public function authRuleTree() {
        $authRuleRes = $this->order('sort','desc')->select();
        return $this->sort($authRuleRes);

    }

    //
    public function sort($data,$pid = 0) {
          static $arr = [];
          foreach ($data as $k=>$v) {
              if ($v['pid'] == $pid) {
                  $arr[] = $v;
                  $this->sort($data,$v['id']);
              }
          }
          return $arr;
    }
}
