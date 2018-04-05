<?php
namespace app\api\controller\v1;

use think\Controller;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;

class Index extends Common{
	/*
	获取首页接口
	1.头图 4-6
	2.推荐位列表 默认40条
	*/
	public function index(){
		$heads = model('News')->getIndexHeadNormalNews();
		$heads = $this->getDealNews($heads);

		$positions = model('News')->getPositionNomalNews();
		$positions = $this->getDealNews($positions);

		$result = [
			'heads' => $heads,
			'positions' => $positions,
		];

		return show(1, 'ok', $result, 200);
	}

	/*
	客户端初始化接口
	1.检测APP是否需要升级
	2.升级的判断是根据版本号，就是数据库的版本号和请求头的版本号比对
	*/
	public function init(){
		//app_type 去 ent_version 查询
		$version = model('Version')->getLastVersionAppType($this->headers['app_type']);
		
		if (empty($version)) {
			return new ApiException('error', 404);
		}

		if ($version->version > $this->headers['version']) {
			$version->is_update = $version->is_force == 1 ? 2 : 1;
		}else{
			$version->is_update = 0;//0不要更新，1.需要更新，2.强制更新
		}

		//记录用户的基本信息  用于统计
		$actives = [
			'version' => $this->headers['version'],
			'app_type' => $this->headers['app_type'],
			'did' => $this->headers['did'],
		];
		try {
			model('AppActive')->add($actives);
		} catch (Exception $e) {
			//todo
			//Log::write();
		}

		return show(1, 'ok', $version, 200);
	}
}