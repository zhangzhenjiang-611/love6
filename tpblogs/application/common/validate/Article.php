<?php
/**
 * Created by PhpStorm.
 * User: 15691
 * Date: 2020/5/5
 * Time: 21:18
 */

namespace app\common\validate;


use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title|文章标题'  =>  'require|unique:article',
        'tags|标签'  =>  'require',
        'cate_id|所属栏目'  =>  'require',
        'desc|文章描述'  =>  'require',
        'content|文章内容'  =>  'require'

    ];

    //文章添加场景
    public function sceneAdd() {
        return $this->only(['title','tags','cate_id','desc','content']);
    }

}