<?php
namespace app\common\lib;
use app\common\lib\Aes;
use think\Cache;

/*Iauth 相关*/
class IAuth{
	/*
	设置密码
	@param string $data
	@return string
	*/
	public static function setPassword($data){
		return md5($data.config('app.password_pre_halt'));
	}

	/*生成每次请求的sign
	@param array $data
	@return string
	*/
	public static function setSign($data = []){
		//1.按字段排序
		ksort($data);
		//2.拼接数据 & 
		$string = http_build_query($data);
		//3.通过aes加密
		$string = (new Aes())->encrypt($string);

		return $string;
	}

	/*
		检查sign是否正常
		@param array $data
		@return boolean
	*/
	public static function checkSignPass($data){
		$str = (new Aes())->decrypt($data['sign']);
		if ($str === false) {
			return false;
		}

		//did=xx&app_type=3 如果还有其他，自行自定义添加判断
		parse_str($str, $arr);
		if (!is_array($arr) || empty($arr['did']) || $arr['did'] != $data['did']) {
			return false;
		}
		if (time()-ceil($arr['time']/1000) > config('app.app_sign_time')) {//超过10秒则违法
			//halt(time()-ceil($arr['time']/1000));
			return false;
		}

		//唯一性判断 //为测试隐藏
		/*if (Cache::get($data['sign'])) {
			return false;
		}*/

		return true;
	}

	/**
	*设置登录的 token - 唯一性
	*@param string $phone
	*@return string
	*/
	public static function setAppLoginToken($phone = ''){
		$str = md5(uniqid(md5(microtime(true)), true));
		return sha1($str.$phone);
	}
}