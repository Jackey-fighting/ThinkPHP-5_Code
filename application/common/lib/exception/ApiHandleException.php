<?php
namespace app\common\lib\exception;

use think\exception\Handle;
use app\common\lib\exception;
/**
* 
*/
class ApiHandleException extends Handle
{
	//http状态码
		protected $httpCode = 500;
	
	public function render(\Exception $e){
		//这个是给开发工程师调试错误用的
		if (config('app_debug') == true) {
			return parent::render($e);
		}
		//这个是返回客户端 http 改变状态码
		if ($e instanceof ApiException) {
			$this->httpCode = $e->httpCode;
		}
		//这里是直接调用了common.php的show() api的封装方法
		return show(0, $e->getMessage(), [], $this->httpCode);
	}
}