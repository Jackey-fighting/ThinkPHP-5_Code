<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\controller\Common;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\model\User;
/**
* 客户端auth登录权限基础类库
*1.每个接口（需要登录、个人中心、点赞、评论）都需要去继承他
*2.判定access_user_token 是否合法
*3. 用户信息-》user
*/
class AuthBase extends Common
{
	public $user = [];//成员变量

	public function _initialize(){
		parent::_initialize();
		if (!$this->isLogin()) {
			throw new ApiException('您没有登录', 401);
		}
	}

	/**
	*判定是否登录
	*@return boolean
	*/
	public function isLogin(){
		if (empty($this->headers['access_user_token'])) {
			return false;
		}
		$obj = new Aes();
		$accessUserToken = $obj->decrypt($this->headers['access_user_token']);
		if (empty($accessUserToken)) {
			return false;
		}
		if (!preg_match('/||/', $accessUserToken)) {
			return false;
		}
		list($token, $id) = explode('||', $accessUserToken);
		$user = User::get(['token' => $token]);

		if (!$user || $user->status != config('code.status_normal')) {
			return false;
		}
		//判定时间是否过期
		if (time() > $user->time_out) {
			return false;
		}

		$this->user = $user;
		return true;
	}
}