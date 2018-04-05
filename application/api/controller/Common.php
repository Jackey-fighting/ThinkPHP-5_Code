<?php
namespace app\api\controller;

use think\Controller;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\lib\IAuth;
use app\common\lib\Time;
use think\Cache;


/**
* 
*/
class Common extends Controller
{
	public $headers = '';//这个是方便获取这个类的headers头部信息

	public $page = 1;
	public $size = 10;
	public $from = 0;
	/*初始化的方法*/
	public function _initialize(){
		$this->checkRequestAuth();
		//$this->testAes();
	}
	/*检查每次app请求的数据是否合法*/
	public function checkRequestAuth(){
		//首先需要获取headers
		$headers = request()->header();
		//todo
		//sign 加密是需要客户端工程师去做  ， 解密是服务端工程师去做的
		// 1. headers body 仿照sign 加解密

		//基础数据校验
		if (empty($headers['sign'])) {
			throw new ApiException('sign不存在', 400);
		}
		if (!in_array($headers['app_type'], config('app.apptype'))) {
			throw new ApiException('app_type不合法', 400);
		}

		//需要sign
		if(!IAuth::checkSignPass($headers)){
			throw new Apiexception('授权码sign失败', 401);
		}
		//把读过的sign写入缓存，让其保持唯一性，也就是一次性
		Cache::set($headers['sign'], 1, config('app.app_sign_cache_time'));
		//return show(1, 'ok', Cache::get($headers['sign']), 201);

		//1。文件 2、mysql 3.redis
		$this->headers = $headers;
	}
	//测试加密
	public function testAes(){
		
		$data = [
			'did' => '12345dg',
			'version' => 1,
			'time' => Time::get13TimeStamp(),
		];
		//exit(IAuth::setSign($data));
		echo (new Aes())->decrypt('bxQQst4amlo4jTojXbeJL86Ulh919YIqdLCuGBQKLJDecmwWLqIIvd3li2a71BKz');exit;
	}

	/*
获取处理的新闻的内容数据
@param array $news
@return array
*/
	protected function getDealNews($news = []){
		if (empty($news)) {
			return [];
		}
		$cats = config('cat.lists');

		foreach ($news as $key => $new) {
			$news[$key]['catname'] = $cats[$new['catid']] ? $cats[$new['catid']] : '-';
			$news[$key]['miniImage'] = !empty($news[$key]['image'])
								   ? $news[$key]['image'].'?imageMogr2/thumbnail/200x300>'
								   : '';
		}
		return $news;
	}

	   /**
     * 获取分页page size 内容
     */
    public function getPageAndSize($data) {
        $this->page = !empty($data['page']) ? $data['page'] : 1;
        $this->size = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');
        $this->from = ($this->page - 1) * $this->size;
    }
}