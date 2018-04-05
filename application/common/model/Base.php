<?php
namespace app\common\model;

use think\Model;
use think\DB;
class Base extends Model
{
	protected $autoWriteTimestamp=true;
	
	/*
		新增
		@param $data 插入的数组数据
		@return mixed
	*/
	public function add($data){
		if (!is_array($data)) {
			exception('传递数据必须为数组');
		}
		$this->allowField(true)->save($data);

		return $this->id;
	}


}