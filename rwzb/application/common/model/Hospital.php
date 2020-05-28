<?php

namespace app\common\model;

use think\Db;
use think\Model;

class Hospital extends Model
{
    //关联模型 一对一
    public function nature() {
        return $this->hasOne('nature','hos_id');
    }

    //添加医院
    public function add($data) {
        $validate = new \app\common\validate\Hospital();
        if (!$validate->scene('add')->check($data)) {
            return $validate->getError();
        }
        //医院编号（12位） = 身份证号行政区编号（6位） +　医院公私属性（1位） + 医院属性（1位） + 医院编号 （3位） + 自增（相同医院不同业务前面值相同，但是此位值不相同）
        $data['hos_code'] = $data['county_code'].$data['pub_type'].$data['self_type'].mt_rand(100,999).'1';
        $areas = Db::name('area')->field('id,name')->where(array('code'=>$data['county_code']))->find();
        $data['area_id'] = $areas['id'];
        $data['hos_area'] = $areas['name'];
        $result = $this->allowField(true)->save($data);
        if ($result) {
                return 1;
            } else {
                return  '医院添加失败';
        }



    }

    //修改医院
    public function edit($data) {
        $validate = new \app\common\validate\Hospital();
        if (!$validate->scene('edit')->check($data)) {
            return $validate->getError();
        }
        /*
         * Array ( [id] => 152 [province_code] => [hos_name] => 佳音医院1234 [city_code] => [county_code] => )
         * */
       $hos_data = [
           'hos_name' => $data['hos_name']
       ];
       $result = $this->where(array('id'=> $data['id']))->update($hos_data);
       if ($result) {
           return 1;
       } else {
           return $result;
       }
    }
}
