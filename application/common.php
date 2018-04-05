<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function pagination($obj){
	if (!$obj) {
		return '';
	}
	$params = request()->param();
	return '<div class="immoc-app">'.$obj->appends($params)->render().'</div>';
}
/*
获取栏目名称
@param $catId
*/
function getCatName($catId){
	if (!$catId) {
		return '';
	}
	$cats = config('cat.lists');

	return !empty($cats[$catId]) ? $cats[$catId] : '';
}

/*
通用化API接口数据输出
@param int $status 业务状态码
@param string $message 信息提示
@param array $data 数据
@param int $httpCode HTTP状态码
*/
function show($status, $message, $data, $httpCode=200){
	
	$data = [
		'status' => $status,
		'message' => $message,
		'data' => $data,
	];
	return json($data, $httpCode);
}

