<?php
namespace app\common\model;

use think\Model;
use app\common\model\Base;
use think\DB;
/**
* User模型类
*/
class Comment extends Base
{
	/**通过条件获取评论的数量
	*@return array
	*/
	public function getNormalCommentsCountByCondition($param=[]){
		//status = 1 自行完成
		$count = DB::table('ent_comment')
			->alias(['ent_comment'=>'a', 'ent_user'=>'b'])
			->join('ent_user', 'a.user_id = b.id AND a.news_id = '.$param['news_id'])
			->count();
			//echo $this->getLastSql();
			return $count;
	}

	public function getNormalCommentsByCondition($param = [], $from = 0, $size =5){
		$result = DB::table('ent_comment')
					  ->alias(['ent_comment'=>'a', 'ent_user'=>'b'])
				      ->join('ent_user', 'a.user_id = b.id AND a.news_id = '.$param['news_id'])
				      ->limit($from, $size)
				      ->order(['a.id'=>'desc'])
				      ->select();
		return $result;
	}

	public function getCountByCondition($param = []){
		return $this->where($param)
					->field('id')
					->count();
	}

	/**
	*@param array $param查询条件
	*@param int $from 从哪里开始查询
	*@param int $size 一页显示多少条
	*/
	public function getListsByCondition($param=[], $from=0, $size=0){
		return $this->where($param)
					->field('*')
					->limit($from, $size)
					->order(['id'=>'desc'])
					->select();
	}
}