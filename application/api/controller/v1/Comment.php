<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\controller\v1\AuthBase;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\lib\IAuth;

/**
* 文章评论
*/
class Comment extends AuthBase
{
	/*评论以及回复功能开发*/
	public function save(){
		$data = input('post.');
		//news_id contetn to_user_id parent_id
		//validate

		//news_id
	
		$data['user_id'] = $this->user->id;
		try {
			$commonId = model('Comment')->add($data);
			if ($commonId) {
				return show(config('code.app_show_success'), '已经评论成功', [], 200);
			}else{
				return show(config('code.app_show_error'), '已经评论失败', [], 403);
			}
		} catch (Exception $e) {
			return show(config('code.app_show_error'), $e->getMessage(), [], 404);
		}
	}

	/*评论列表 v1*/
	/*public function read(){
		//select * from ent_comment as s join ent_user as b on a.user_id = b.id and a.news_id = 3;
		$newsId = input('param.id', 0, 'intval');
		if (empty($newsId)) {
			return show(config('code.app_show_error'), 'id值不能为空', [], 404);
		}

		$count = model('Comment')->getNormalCommentsCountByCondition(['news_id'=>$newsId]);

		$this->getPageAndSize(input('param.'));
		$comment = model('Comment')->getNormalCommentsByCondition(['news_id'=>$newsId], $this->from, $this->size);

		if (empty($comment)) {
			return show(config('code.app_show_error'), '获取评论不到值', [], 404);
		}

		$result = [
			'total' => $count,
			'page_num' => ceil($count/$this->size),
			'list' => $comment,
		];
		return show(config('code.app_show_success'), 'ok', $result, 200);
	}*/


	/*评论列表v2*/
	public function read(){
		$newsId = input('param.id', 0, 'intval');
		if (empty($newsId)) {
			return new ApiException('id is not', 404);
		}
		$userIds = [];
		$param['id'] = $newsId;
		$count = model('Comment')->getCountByCondition($param);

		$comments = model('Comment')->getListsByCondition($param, $this->from, $this->size);
		if ($comments) {
			foreach ($comments as $comment) {
				if ($comment['to_user_id']) {
					$userIds[] = $comment['to_user_id'];
				}
			}
			$userIds = array_unique($userIds);
		}

		$userIds = model('User')->getUsersUserId($userIds);
		
		if (empty($userIds)) {
			$userIdNames = [];
		}else{
			foreach ($userIds as $userId) {
				$userIdNames[$userId->id] = $userId;
			}
		}

		$resultDatas = [];
		foreach ($comments as $comment) {
			$resultDatas[] = [
				'id' => $comment->id,
				'user_id' => $comment->user_id,
				'to_user_id' => $comment->to_user_id,
				'content' => $comment->content,
				'username' => !empty($userIdNames[$comment->user_id]) ? $userIdNames[$comment->user_id]->username : '',
				'tousername' => !empty($userIdNames[$comment->to_user_id]) ? $userIdNames[$comment->to_user_id]->username : '',
				'parent_id' => $comment->parent_id,
				'create_time' => $comment->create_time,
				'image' => !empty($userIdNames[$comment->user_id]) ? $userIdNames[$comment->to_user_id]->image : '',
			];
		}

		$result = [
			'total' => $count,
			'page_num' => ceil($count/$this->size),
			'list' => $resultDatas,
		];
		return show(config('code.app_show_success'), 'ok', $result, 200);
	}
}