<?php

namespace app\admin\controller;

use app\common\model\Hospital as hospitalModel;
use think\Db;
use think\facade\Request;


class Hospital extends Base
{
    protected $model = null;
    public function __construct()
    {
        parent::__construct();
        $this->model = new hospitalModel();
    }
    //医院列表
    public function hospitalList() {
        $hospitas = hospitalModel::order('id','desc')->paginate(10);
        $this->assign('list',$hospitas);
        return $this->fetch();
    }

    //医院添加
    public function add() {
        if (Request::isAjax()) {
           $hos_data = [
               'hos_name'  => input('post.hos_name')
           ];
           $attr_data = [
               'type'  => input('post.type'),
               'public_type'  => input('post.public_type'),
               'province_code'  => input('post.pro'),
               'city_code'  => input('post.city'),
               'county_code'  => input('post.area')
           ];
            Db::startTrans();
            try {
                //根据县区编号获取名称
                $hos_area = Db::name('area')->where(['code'=>$attr_data['county_code']])->field('name')->find();
                $hos_data['hos_area'] = $hos_area['name'];
                $area_id = Db::name('area')->where(['code'=>$attr_data['county_code']])->field('id')->find();
                $hos_data['area_id'] = $area_id['id'];
                //医院编号
                $hos_data['hos_code'] = $attr_data['county_code'].$attr_data['public_type'].$attr_data['type'].mt_rand(100,999).mt_rand(0,9);
                $attr_data['hos_code'] = $attr_data['county_code'].$attr_data['public_type'].$attr_data['type'].mt_rand(100,999).mt_rand(0,9);

                $res1 =  Db::name('hospital')->insert($hos_data);

                if ($res1) {
                    $hos_id = Db::name('hospital')->field('id')->order('id','desc')->find();
                    if (!empty($hos_id)) {
                        $attr_data['hos_id'] = $hos_id['id'];
                        $res2 =  Db::name('nature')->insert($attr_data);
                    }

                }
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($res2) {
                return $this->success('医院添加成功','hospitalList');
            } else {
                return $this->error('医院添加失败');
            }
        }
        //医院属性
        $arritudes  = $this->getType();
        $public = $this->getPublic();


        //三级联动
        $region = $this->getArea();

        //公私属性
        $this->assign('region',$region);
        $this->assign('arritudes',$arritudes);
        $this->assign('public',$public);
        return $this->fetch();
    }

    //医院修改
    public function edit() {
        if (Request::isAjax()) {
            $hos_data = [
                'hos_name'  => input('post.hos_name')
            ];
            $attr_data = [
                'province_code'  => input('post.pro'),
                'city_code'  => input('post.city'),
                'county_code'  => input('post.area')
            ];
           // 查找这个医院的名称和属性
            $hospital = hospitalModel::find(input('id'));
            $hospital->hos_name = $hos_data['hos_name'];
            $res1 = $hospital->save();
            if ($res1) {
                $res2 = Db::name('nature')->where(array('hos_id'=> input('id')))->update($attr_data);
                if ($res2) {
                    return $this->success('医院修改成功','hospitalList');
                } else {
                    return $this->error('医院修失败');
                }
            }


        }
        $hospital = $this->model->findOrFail(input('id'));
        if ($hospital) {
            $nature = Db::name('nature')->where(array('hos_id'=>$hospital['id']))->find();
        }
        if (!empty($nature)) {
            //获取公私属性
            $type = Db::name('hospital_type')->where(array('type_id'=>$nature['type']))->field('id,attribute')->find();
            $public_type = Db::name('hospital_public')->where(array('type_id'=>$nature['public_type']))->field('id,attribute')->find();
            $this->assign('type',$type);
            $this->assign('public_type',$public_type);
            //获取省市区县
            $province = Db::name('area')->where(array('code'=>$nature['province_code']))->field('code,name')->find();
            $city = Db::name('area')->where(array('code'=>$nature['city_code']))->field('code,name')->find();
            $county = Db::name('area')->where(array('code'=>$nature['county_code']))->field('code,name')->find();
            $this->assign('province',$province);
            $this->assign('city',$city);
            $this->assign('county',$county);

        }

        $this->assign('hospital',$hospital);
        $this->assign('nature',$nature);

        //医院属性
        $arritudes = Db::name('hospital_type')->select();
        $public = Db::name('hospital_public')->select();


        //三级联动
        $region = Db::name('area')->where('parent_code=" "')->select();

        //公私属性
        $this->assign('region',$region);
        $this->assign('arritudes',$arritudes);
        $this->assign('public',$public);
        return $this->fetch();
    }

    //获取城市
    public function getCity() {
        if (Request::isAjax()) {
            //dump(input('post.pro_id'));
            $parent_code = input('post.pro_id');
            $region = Db::name('area')->where(['parent_code' => $parent_code])->select();


            $opt = '<option>--请选择--</option>';
            foreach($region as $key=>$val){
                $opt .= "<option value='{$val['code']}'>{$val['name']}</option>";
            }
            echo json_encode($opt);
            die;

        }
    }

    //获取市县
    public function getCounty() {
        if (Request::isAjax()) {
            $parent_code = input('post.pro_id');
            $region = Db::name('area')->where(['parent_code' => $parent_code])->select();

            $opt = '<option>--请选择--</option>';
            foreach($region as $key=>$val){
                $opt .= "<option value='{$val['code']}'>{$val['name']}</option>";
            }
            echo json_encode($opt);

        }
    }

    //医院删除
    public function delete() {
        if (Request::isAjax()) {
           $result = Db::name('hospital')->delete(input('post.id'));
           if ($result) {
               return $this->success('医院删除成功','hospitalList');

           } else {
               return $this->error('医院删除失败');
           }
        }
    }
}
