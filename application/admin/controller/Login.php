<?php
namespace app\admin\controller;

use think\Controller;
use app\common\lib\IAuth;
class Login extends Base
{
	public function _initialize(){
	}
	public function index(){
		//如果后台用户已经登录了，那么我们要跳到后台首页
		$isLogin = $this->isLogin();
		if ($isLogin) {
			return $this->redirect('index/index');
		}
		return $this->fetch();
	}
	/*登录相关业务*/
	public function check(){
		if (request()->isPost()) {
			$data = input('post.');
			if(!captcha_check($data['code'])){
				$this->error('验证码不正确');
			}
			//判定username, password
			$validate = validate('AdminUser');
			if ($validate->check($data)) {
				try{//严格来说是对sql语句的try，因为$this->error是会抛出异常的
					//进行判定数据库是否有此用户
					$user = model('AdminUser')->get(['username'=>$data['username']]);
				}catch(Exception $e){
					$this->error($e->getMessage());
				}
				if (!$user || $user->status != config('code.status_normal')) {
					$this->error('该用户不存在');
				}

				//对密码进行校验
				if (IAuth::setPassword($data['password']) != $user['password']) {
					$this->error('密码不正确');
				}
					//1.更新数据库，登录时间，登录Ip
				$udata = [
					'last_login_time' => time(),
					'last_login_ip' => request()->ip(),//tp5有自带的获取Ip
				];
				try{
					model('AdminUser')->allowField(true)->save($udata, ['id'=>$user->id]);
				}catch(Exception $e){
					$this->error($e->getMessage());
				}
				//2.session
				session(config('admin.session_user'), $user, config('admin.session_user_scope'));

				$this->success('登录成功', 'index/index');
			}else{
				$this->error($validate->getError());
			}
			//validate机制
		}else{
			  $this->error('非法请求，不予通过。');
			}
	}

	//退出登录
	/*
	1.清空session
	2.跳转到登陆界面
	*/
	public function logout(){
		session(null, config('admin.session_user_scope'));
		$this->redirect('login/index');
	}
}