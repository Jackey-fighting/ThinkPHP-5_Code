<?php
namespace app\admin\controller;

use think\Controller;

/*后台的基类*/
class Base extends Controller{
	/*tp5自带初始化的方法*/
	public function _initialize(){
		//判定用户是否登录
		$isLogin = $this->isLogin();
		if (!$isLogin) {
			return $this->redirect('login/index');
		}
	}

	/*判断是否登录
	@return bool*/
	public function isLogin(){
		//获取session
		$user = session(config('admin.session_user'), '', config('admin.session_user_scope'));
		if ($user && $user->id) {
			return true;
		}

		return false;
	}
}