<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/*Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'view/hello');

return [

];*/
Route::group('admin',function () {
    Route::rule('/','admin/index/login','get|post');
    //后台账户注册
    Route::rule('register','admin/index/register','get|post');
    //后台忘记密码
    Route::rule('forget','admin/index/forget','get|post');
    //后台重置密码
    Route::rule('reset','admin/index/reset','post');
    //后台首页
    //Route::rule('index','admin/home/index','get');
    Route::rule('logout','admin/home/logout','post');
    //栏目列表
    Route::rule('catetegoryList','admin/category/list','get');
    //栏目增加
    Route::rule('catetegoryAdd','admin/category/add','get|post');
    //排序
    Route::rule('catetegorySort','admin/category/sort','post');
    //栏目编辑
    Route::rule('catetegoryEdit/[:id]','admin/category/edit','get|post');
    //栏目删除
    Route::rule('catetegoryDelete','admin/category/delete','post');

    //文章列表
    Route::rule('articleList','admin/article/list','get');
    //文章添加
    Route::rule('articleAdd','admin/article/add','get|post');


});
