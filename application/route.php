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
Route::post('api/:ver/test', 'api/:ver.User/test');


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
