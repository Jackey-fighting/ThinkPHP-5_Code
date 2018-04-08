<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\controller\v1\AuthBase;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\lib\IAuth;

/**
* 点赞类
*/
class Upvote extends AuthBase
{
	/**
	*新闻点赞功能开发
	*@return array
	*/
	public function save(){
		$id = input('post.id', 0, 'intval');
		if (empty($id)) {
			return show(config('code.app_show_error'), 'id不存在', [], 404);
		}
		//判定这个id的新闻文章 ->ent_news
		$news = model('News')->where(['id'=>$id])
							 ->select();
		if (!$news) {
			return show(config('code.app_show_error'), '此新闻不存在', [], 404);
		}
		$data = [
			'user_id' => $this->user->id,
			'news_id' => $id,
		];
		//查询库里面是否存在点赞
		$userNews = model('UserNews')->get($data);
		if ($userNews) {
			return show(config('code.app_show_error'), '已经点赞过，不能再次点赞', [], 404);
		}

		try {
			$userNewsId = model('UserNews')->add($data);
			if ($userNewsId) {
				try {
					model('News')->where(['id'=>$id])->setInc('upvote_count');
				} catch (Exception $e) {
					return show(config('code.app_show_error'), '新闻表+1失败', [], 404);
				}
				return show(config('code.app_show_success'), '已经点赞成功', [], 200);
			}
		} catch (Exception $e) {
			return show(config('code.app_show_error'), '点赞失败', [], 404);
		}
	}

	/*取消点赞*/
	public function delete(){
		$id = input('delete.id', 0, 'intval');
		if (empty($id)) {
			return show(config('code.app_show_error'), '取消点赞失败', [], 404);
		}
		$data = [
			'user_id' => $this->user->id,
			'news_id' => $id,
		];
		//检查是否存在
		$userNews = model('UserNews')->get($data);
		if (empty($userNews)) {
			return show(config('code.app_show_error'), '没有被点赞过，无法取消', [], 404);
		}

		try {
			$delete = model('UserNews')->where($data)->delete();
			if ($delete) {
				model('News')->where(['id'=>$id])->setDec('upvote_count');
				return show(config('code.app_show_success'), '取消成功', [], 404);
			}
			return show(config('code.app_show_error'), '取消失败', [], 404);
		} catch (Exception $e) {
			return show(config('code.app_show_error'), '点赞过无法取消', [], 404);
		}
	}

	/*查看文件是否被该用户点赞*/
	public function read(){
		$id = input('param.id', 0, 'intval');
		if (empty($id)) {
			return show(config('code.app_show_error'), '新闻id不存在', [], 404);
		}

		$data = [
			'user_id' => $this->user->id,
			'id' => $id,
		];
		$userNews = model('UserNews')->get($data);
		if ($userNews) {
			return show(config('code.app_show_success'), 'ok', ['isUpvote'=>1], 200);
		}

		return show(config('code.app_show_error'), 'read error', ['isUpvote'=>0], 401);
	}
}