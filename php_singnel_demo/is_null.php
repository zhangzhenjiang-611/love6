<?php
  static function getCampusInfo($merchantId,$campusId)
    {
        $conditions = "merchant_id = :merchant_id: AND id = :campus_id:";
        $map['merchant_id'] = $merchantId;
        $map['campus_id'] = $campusId;
        $data = self::find(array(
            $conditions,
            'bind' => $map
        ));
        $list = array();
        $random = substr(time(),2);
        if(count($data) > 0){
            foreach($data as $key => $value){
                $info['name'] = $value->name;
                $info['telephone'] = $value->telephone;
                $info['address'] = $value->address;
                $info['code'] = $value->code;
                if($value->code){           //如果表字段默认为null，用此方法判断对象属性是否为空
                    DLOG('dfdfdf');
                }else{
                    DLOG('43324324');
                }
                if($value->code == ' ')       //如果表字段默认为''，用此方法判断对象属性是否为空
                {
                    $info['code'] =  $value->code;
                }else{
                    $info['code'] =  $info['code'].$random;
                }

                $info['email'] = $value->Merchant->email;
                $list[] = $info;
            }
        }
        return $list;
    }