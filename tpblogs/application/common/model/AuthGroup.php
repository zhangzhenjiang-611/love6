<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class AuthGroup extends Model
{
    //
    use SoftDelete;
    public function add($data) {
        $validate = new \app\common\validate\AuthGroup();
        if (!$validate->scene('add')->check($data)) {
            return $validate->getError();
        }
        $result = $this->allowField(true)->save($data);
        if ($result) {
            return 1;
        } else {
            return '用户组添加失败';
        }
    }

    public function edit($data) {
        $validate = new \app\common\validate\AuthGroup();
        if (!$validate->scene('edit')->check($data)) {
            return $validate->getError();
        }

        $groupInfo = $this->findOrFail($data['id']);
        $groupInfo->title = $data['title'];
        $groupInfo->status = $data['status'];
        $result = $groupInfo->save();
        if ($result) {
            return 1;
        } else {
            return '用户组修改失败';
        }

    }

   /* public function getStatusAttr($value)
    {
        $status = [-0=>'禁用',1=>'正常'];
        return $status[$value];
    }*/

}
