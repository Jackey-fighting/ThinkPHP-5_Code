<?php
namespace app\common\model;

use think\Model;
use app\common\model\Base;

/**
* User模型类
*/
class User extends Base
{
	/**
	*@param array $userIds查询user的id条件
	*@return array 返回查询的结果
	*/
	public function getUsersUserId($userIds=[]){

		$data = [
			'id' => ['in', implode(',', $userIds)],
			'status' => 1,
		];

		$order = [
			'id'=>'desc',
		];
		return $this->where($data)
					->field(['id, username, image'])
					->order($order)
					->select();
	}
}