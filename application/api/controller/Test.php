<?php
namespace app\api\controller;

use think\Controller;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\lib\IAuth;

class Test extends Controller{

	public function index(){
		return [
			'status' => 1,
			'message' => 'ok',
		];
	}

	//put操作
	public function update($id=0){
		echo $id;
	}

	//post 新增
	public function save(){
			/*$data = input('post.');
			if ($data['mt'] != 1) {
				//exception('您提交的数据不合法', 400);
				//throw new ApiException("您提交的数据不合法", 403);
				exception('您提交的数据不合法', 300);
			}*/
			//model('asdf2');
		
		//获取到提交数据 出入库
		//给客户端app => 接口数据
		return show(1, 'ok', (new Aes())->encrypt(http_build_query(input('post.'))), 201);
	}

	public function setToken(){
		echo IAuth::setAppLoginToken();
	}
}