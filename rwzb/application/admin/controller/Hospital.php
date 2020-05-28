<?php

namespace app\admin\controller;
use app\common\model\Hospital as HospitalModel;
use think\App;
use think\Db;
use think\facade\Request;

class Hospital extends Base
{
    protected $model = null;
    public function __construct()
    {
        parent::__construct();
        $this->model = new HospitalModel();
    }

    //医院列表
    public function index() {
        $hospital = HospitalModel::order('id','desc')->paginate(10);
        foreach ($hospital as $k=>$v) {
          $province = Db::name('area')->field('name')->where(array('code'=>$v->nature['province_code']))->find();
          $v['province_name'] = $province['name'];
            $city = Db::name('area')->field('name')->where(array('code'=>$v->nature['city_code']))->find();
            $v['city_name'] = $city['name'];
            $county = Db::name('area')->field('name')->where(array('code'=>$v->nature['county_code']))->find();
            $v['county_name'] = $county['name'];
        }
        $this->assign('hospital',$hospital);
        return $this->fetch();
    }


    //添加医院
    public function add() {
        if (Request::isAjax()) {
            $data = [
                'hos_name' => input('post.hos_name'),
                'self_type' => input('post.self_type'),
                'pub_type' => input('post.pub_type'),
                'province_code' => input('post.pro'),
                'city_code' => input('post.city'),
                'county_code' => input('post.county'),
            ];
            $result = $this->model->add($data);

            if($result == 1) {
                $hos = HospitalModel::order('id','desc')->limit(1)->find();
                $datas['hos_id'] = $hos->id;
                $datas['province_code'] =  $data['province_code'];
                $datas['city_code'] = $data['city_code'];
                $datas['county_code'] = $data['county_code'];
                $res = Db::name('nature')->insert($datas);
                if ($res) {
                    $this->success('医院添加成功','admin/hospital/index');
                }
            } else {
                $this->error('医院添加失败');

            }

        }

        //获取省市县
        $province = $this->getArea();
        $this->assign('province',$province);
        return $this->fetch();
    }

    //医院修改
    public function edit() {
        if (Request::isAjax()) {
            $data = [
                'id'  => input('post.hos_id'),
                'province_code' => input('post.pro_code'),
                'hos_name' => input('post.hos_name'),
                'city_code' => input('post.city_code'),
                'county_code' => input('post.county_code')
            ];
          $result = $this->model->edit($data);

          if ($result == 1) {
              if (!empty($data['province_code'])) {
                  $hos = HospitalModel::where(array('id'=>$data['id']))->find();
                  $hos_id = $hos->id;
                  $datas['province_code'] =  $data['province_code'];
                  $datas['city_code'] = $data['city_code'];
                  $datas['county_code'] = $data['county_code'];
                  $res = Db::name('nature')->where(array('hos_id'=>$hos_id))->update($datas);
                  if ($res) {
                      $this->success('医院信息修改成功','admin/hospital/index');
                  }
              } else {
                  $this->success('医院信息修改成功','admin/hospital/index');
              }
          } else {
              $this->error('修改失败');
          }



        } else {
            $id = input('id');
            $hospital = HospitalModel::get($id);
            if (!empty($hospital)) {
                $hospitalInfo = $hospital->toArray();
            }
            $nature = $hospital->nature;
            $province = Db::name('area')->field('name')->where(array('code'=>$nature['province_code']))->find();
            $hospitalInfo['province_name'] = $province['name'];
            $city = Db::name('area')->field('name')->where(array('code'=>$nature['city_code']))->find();
            $hospitalInfo['city_name'] = $city['name'];
            $county = Db::name('area')->field('name')->where(array('code'=>$nature['county_code']))->find();
            $hospitalInfo['county_name'] = $county['name'];
            $hospitalInfo = array_merge($hospitalInfo,$nature->toArray());
            //获取省市县
            $province = $this->getArea();
            $this->assign('province',$province);
            $this->assign('hospitalInfo',$hospitalInfo);
            return $this->fetch();
        }
    }

    //医院删除
    public function delete() {
        if (Request::isAjax()) {
            $result = Db::name('hospital')->delete(input('post.id'));
           if ($result) {
               $res =  Db::name('nature')->where(array('hos_id'=>input('post.id')))->delete();
               if ($res) {
                   $this->success('医院删除成功','admin/hospital/index');
               } else {
                   $this->error('医院删除失败');
               }
           }
        }
    }
}
