<?php

namespace app\common\model;

use think\Model;

class Hospital extends Model
{
    //一对一关联 一个医院只对应一条属性
    public function nature() {
        return $this->hasOne('Nature');
    }

    public function add($data) {
       $validate = new \app\common\validate\Hospital();
       if (!$validate->scene('add')->check($data)) {
           return $validate->getError();
       }
        $nature = new Nature();
        $nature->add($data);
    }

}
