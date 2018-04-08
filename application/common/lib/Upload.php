<?php
namespace app\common\lib;

require VENDOR_PATH.'qiniu/php-sdk/autoload.php'; //如果你不是composer的，就要引入
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

/*
七牛图片上传基类
Class Upload
@package app\common\lib
*/
class Upload{
	/*图片上传*/
	public static function image(){
		if (empty($_FILES['file']['tmp_name'])) {
			exception('您提交的数据不合法', 404);
		}
		//要上传的文件
		$file = $_FILES['file']['tmp_name'];
		//获取文件后缀
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		//获取配置参数
		$config = config('qiniu');
		//构建一个鉴权对象
		$auth = new Auth($config['ak'], $config['sk']);
		//生成上传的token
		$token = $auth->uploadToken($config['bucket']);

		//上传到
		$key = date('Y')."/".date('m')."/".substr(md5($file),0,5).date('YmdHis').rand(0,9999).'.'.$ext;
		
		//初始化UploadManager类
		$uploadMgr = new UploadManager();
		list($ret, $err) = $uploadMgr->putFile($token, $key, $file);
		
		if ($err !== null) {
			return null;
		}else{
			return $key;//七牛保存的文件名
		}
	}
}