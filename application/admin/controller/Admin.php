<?php
namespace app\admin\controller;

use think\Controller;
use app\common\lib\IAuth;

class Admin extends Controller
{
	public function add(){
		//判定是否post提交
		if (request()->isPost()) {
			//dump(input('post.'));//打印提交的数据
			$data = input('post.');//因为post.是请求的数据数组
			//validate
			$validate = validate('AdminUser');//实例化
			if (!$validate->check($data)) {
				$this->error($validate->getError());
			}

			$data['password'] = IAuth::setPassword($data['password']);//给她密码加盐
			$data['status'] = 1;

			//1.exception是否正常
			try{
				$id = model('AdminUser')->add($data);
			}catch(\Exception $e){
				$this->error($e->getMessage());
			}
			
			if ($id) {
				$this->success('id='.$id.'的用户新增成功');
			}else{
				$this->error('error');
			}

		}else{
			return $this->fetch();
		}
	}
}
