<?php
namespace app\common\model;

use app\common\model\Base;
use think\Model;

/**
* 
*/
class News extends Base
{
	/*后台自动化分页
	@param array $data
	*/
	public function getNews($data=array()){
		$data['status'] = [
			'neq', config('code.status_delete')
		];

		$order = ['id'=>'asc'];
		//查询
		$result = $this->where($data)
			 ->order($order)
			 ->paginate();

		//调试
		//echo $this->getLastSql();

		return $result;
	}

	/**获取列表的数据
	@param array $condition 查询条件
	@param int $from 第几页
	@param int $size 一页显示多少行
	@return array 返回查询的结果
	*/
	public function getNewsByCondition($condition, $from=0, $size = 5){

		if (!isset($condition['status'])) {
			$condition['status'] = [
				'neq', config('code.status_delete')
			];
		}
		$order = ['id'=>'asc'];

		$result = $this->where($condition)
			 ->field($this->_getListField())
			 ->limit($from, $size)
			 ->order($order)
			 ->select();
		//exit($this->getLastSql());

		return $result;
	}

	/*
	根据条件来获取列表的数据总数
	@param array $param
	*/
	public function getNewsCountByCondition($condition = []){

		if (!isset($condition['status'])) {
			$condition['status'] = [
				'neq', config('code.status_delete')
			];
		}
		
		return $this->where($condition)
			 ->count();
	}

	/*
	获取首页头图数据
	@param int $num
	@return array
	*/
	public function getIndexHeadNormalNews($num = 4){
		$data = [
			'status' => 1,
			'is_head_figure' => 1,
		];

		$order = [
			'id' => 'desc',
		];
		return $this->where($data)
			 ->field($this->_getListField())
			 ->order($order)
			 ->limit($num)
			 ->select();
	}

	/*
	获取推荐的数据
	*/
	public function getPositionNomalNews($num = 20){
		$data = [
			'status' => 1,
			'is_position' => 1,
		];

		$order = [ 'id' => 'desc'];

		return $this->where($data)
					->field($this->_getListField())
					->order($order)
					->limit($num)
					->select();
	}

	/*通用化获取参数的数据字段*/
	private function _getListField(){
		return [
			'id' ,
			'catid', 
			'image', 
			'title', 
			'read_count', 
			'status', 
			'is_position', 
			'update_time',
			'create_time',
			];
	}

	/*
	获取排行榜数据
	*/
	public function getRankNormalNews($num=5){
		$data = [
			'status' => 1,
			'is_position' => 1,
		];

		$order = [
			'read_count' => 'desc',
		];

		return $this->where($data)
					->field($this->_getListField())
					->order($order)
					->limit($num)
					->select();
	}
}