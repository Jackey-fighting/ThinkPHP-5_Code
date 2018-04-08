<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\controller\Common;
use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;
use app\common\lib\Aes;
use think\Cache;
use app\common\model\User;
/**
* APP登录类操作
*/
class Login extends Common
{
	public function save(){
		if (!request()->isPost()) {
			return show(config('code.app_show_error'), '您没有权限', '', 403);
		}

		$param = input('param.');
		if (empty($param['phone'])) {
			return show(config('code.app_show_error'), '您提交的手机号为空', '', 404);
		}
		/*if (empty($param['code'])) {
			return show(config('code.app_show_error'), '您提交的验证码为空', '', '');
		}*/

		//validate严格验证
		/*让客户端进行验证码加密
		$param['code'] = Aes::decrypt($param['code']);
		*/
		/*if ($param['code']) {
			$code = Cache::get($param['phone']);
			if ($code != $param['code']) {
			return show(config('code.app_show_error'), '您提交的验证码不正确', '', 404);
			}
		}*/
		

		
		$token = IAuth::setAppLoginToken($param['phone']);

		//查询这个手机号是否存在
		$user = User::get(['phone' => $param['phone']]);
		if ($user && $user->status == 1) {
			if (!empty($param['password'])) {
				$param['password']=IAuth::setPassword($param['password']);//密码加密
				if ($param['password'] != $user->password) {
					return show(config('code.app_show_error'), '密码不正确', [], 403);
				}
			}
			$data = $param;
			$data['token'] = $token;
			$id = model('User')->allowField(true)->save($data, ['phone' => $param['phone']]);
		}else{
			if (!empty($param['code'])) {
				//第一次登录要注册数据
				$data = [
					'token' => $token,
					'time_out' => strtotime('+'.config('app.login_time_out_day').' days'),
					'username' => 'Jackey'.$param['phone'],
					'status' => 1,
					'phone' => $param['phone'],
				];
				$id = model('User')->add($data);
			}else{
				return show(config('code.app_show_error'), '用户不存在', [], 403);
			}
			
		}

		$obj = new Aes();
		if ($id) {
			$result = [
				'token' => $obj->encrypt($token.'||'.$id),//可以不用aes加密，但为了安全性，最好这么做
			];
			return show(config('code.app_show_success'), 'ok', $result, 200);
		}
	}

	//退出登录
	public function logout(){

		$token = model('User')->save(['token' => ''], ['phone' => input('param.phone')]);
					 halt($token);

		return $this->redirect();//退出到登陆首页
	}
}