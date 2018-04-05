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
		if (empty($param['code'])) {

			return show(config('code.app_show_error'), '您提交的验证码为空', '', '');
		}

		//validate严格验证
		/*让客户端进行验证码加密
		$param['code'] = Aes::decrypt($param['code']);
		*/
		/*$code = Cache::get($param['phone']);
		if ($code != $param['code']) {
			return show(config('code.app_show_error'), '您提交的验证码不正确', '', 404);
		}*/

		
		$token = IAuth::setAppLoginToken($param['phone']);

		//查询这个手机号是否存在
		$user = User::get(['phone' => $param['phone']]);
		//halt($user);
		//第一次登录要注册数据
		$data = [
			'token' => $token,
			'time_out' => strtotime('+'.config('app.login_time_out_day').' days'),
			'username' => 'Jackey'.$param['phone'],
			'status' => 1,
			'phone' => $param['phone'],
		];
		$id = model('User')->add($data);

		$obj = new Aes();
		if ($id) {
			$result = [
				'token' => $obj->encrypt($token.'||'.$id),//可以不用aes加密，但为了安全性，最好这么做
			];
			return show(config('code.app_show_success'), 'ok', $result, 200);
		}
	}
}