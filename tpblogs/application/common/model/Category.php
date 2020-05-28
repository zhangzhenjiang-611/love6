<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Category extends Model
{
    //软删除
    use SoftDelete;
   //栏目添加
    public function add($data) {
        $validate = new \app\common\validate\Category();
        if(!$validate->scene('add')->check($data)) {
            return $validate->getError();
        } else {
            $result = $this->allowField(true)->save($data);
            if ($result) {
                return 1;
            } else {
                return '栏目添加失败';
            }
        }

    }

    //栏目排序
    public function sort($data) {
        $validate = new \app\common\validate\Category();
        if(!$validate->scene('sort')->check($data)) {
            return $validate->getError();
        } else {
            $cateInfo = $this->find($data['id']);
            $cateInfo->sort = $data['sort'];
            $result = $cateInfo->save();
            if ($result) {
                return 1;
            } else {
                return '排序失败';
            }
        }
    }

    //栏目更新
    public function edit($data) {
        $validate = new \app\common\validate\Category();
        if (!$validate->scene('edit')->check($data)) {
            return $validate->getError();
        }
        $cateInfo = $this->find($data['id']);
        $cateInfo->catename = $data['catename'];
        $result = $cateInfo->save();
        if ($result) {
            return 1;
        } else {
            return '栏目编辑失败';
        }

    }
}
