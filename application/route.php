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
use think\Route;

//get
Route::get('test', 'api/test/index');
Route::put('test/:id', 'api/test/update');
Route::resource('test', 'api/test');
//测试检验app
Route::post('testAes', 'api/Common/checkRequestAuth');

Route::get('api/:ver/cat', 'api/:ver.cat/read');
Route::get('api/:ver/index', 'api/:ver.index/index');
Route::get('api/:ver/init', 'api/:ver.index/init');

//news
Route::get('api/:ver/new-index', 'api/:ver.News/index');
//排行
Route::get('api/:ver/rank', 'api/:ver.rank/index');
//测试阿里大于短信
Route::get('api/duanxin', 'api/duanxin.DuanXin/SendDuanXin');

//登录的路由
Route::post('api/:ver/login', 'api/:ver.Login/save');
//test

Route::resource('api/:ver/user', 'api/:ver.User');
Route::post('api/:ver/logout', 'api/:ver.Login/logout');

//点赞
Route::post('api/:ver/upvote', 'api/:ver.upvote/save');
Route::delete('api/:ver/delete', 'api/:ver.upvote/delete');//取消点赞
Route::get('api/:ver/upvote/:id', 'api/:ver.upvote/read');//获取是否已经被点赞过来让心变红
//Route::post('api/:ver/comment', 'api/:ver.comment/save');//评论文章
Route::post('api/:ver/comment/read', 'api/:ver.comment/read');


//图片上传 http->php
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
	'test' => 'admin/Image/test',//要到这里来搞路由
];
