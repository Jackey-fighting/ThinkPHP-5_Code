<?php
namespace app\common\model;

use think\Model;
use app\common\model\Base;

/**
* 
*/
class Version extends Base
{
	/*
	通过apptype获取最后一条数据
	@param string $appType
	*/
	public function getLastVersionAppType($appType = ''){
		$data = [
			'app_type' => $appType,
		];

		$order = [
			'id' => 'desc',
		];
		return $this->where($data)
			 ->order($order)
			 ->limit(1)
			 ->find();
	}
}