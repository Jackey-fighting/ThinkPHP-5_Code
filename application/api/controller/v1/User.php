<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\controller\v1\AuthBase;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\lib\IAuth;
/**
* 
*/
class User extends AuthBase
{
	/**
	*获取用户信息
	*用户的基本信息非常隐私，需要加密处理
	*/
	public function read(){
		$obj = new Aes();
		return show(config('code.app_show_success'), 'ok', $obj->encrypt($this->user));
	}

	/*修改数据*/
	public function update(){
		$postData = input('param.aes');
		//客户端进行数据加密处理
		/*$obj = new Aes();
		exit($obj->encrypt(http_build_query($postData)));*/
		//validate 进行校验
		$data = [];
		//aes:: xyZlDcMkHzfBoBE4Eg5GsGK7iKodbY6BS9XuhVrsd/saUAsuwTFRH9P2FFblsyHpEkMA0xmT/uLEPPS7g0iNsUCYcjwAGqJRN1PmONRUp+Ow5NPg7s4Rdghzom7piWzT
		//对获得的数据进行解密
		$obj = new Aes();
		$postData = $obj->decrypt($postData);
		parse_str($postData, $postData);
		//判断用户昵称是否存在
		$username = model('User')->where([
							'username' => $postData['username']
							])->select();
		if (!empty($postData['username']) && $username) {
			return '名字已经存在了，请更换名字';
		}

		if (!empty($postData['image'])) {
			$data['image'] = $postData['image'];
		}
		if (!empty($postData['username'])) {
			$data['username'] = $postData['username'];
		}
		if (!empty($postData['sex'])) {
			$data['sex'] = $postData['sex'];
		}
		if (!empty($postData['signature'])) {
			$data['signature'] = $postData['signature'];
		}

		if (empty($data)) {
			return show(config('code.app_show_error'), '数据不合法', [], 404);
		}
		if (!empty($postData['password'])) {
			$data['password'] = IAuth::setPassword($postData['password']);
		}

		try {
			$id = model('User')->save($data, ['id' => $this->user->id]);
			if ($id) {
				return show(config('code.app_show_success'), 'OK', [], 202);
			}
		} catch (Exception $e) {
			return show(config('code.app_show_error'), $e->getMessage(), [], 404);
		}
	}
}