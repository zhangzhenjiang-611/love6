<?php

namespace app\common\model;

use think\Model;

class Nature extends Model
{
    //
    public function add($data) {
        $validate = new \app\common\validate\Nature();
        if (!$validate->scene('add')->check($data)) {
            return $validate->getError();
        }
    }
}
