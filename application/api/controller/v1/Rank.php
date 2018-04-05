<?php
namespace app\api\controller\v1;

use think\Controller;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;

/**
* 
*/
class Rank extends Common
{
	/*获取排行榜数据列表
	 1.获取数据库 然后 read_count 排序 5-10
	 2.优化 redis
	*/
	public function index(){
		try {
			$rands = model('News')->getRankNormalNews();
			$rands = $this->getDealNews($rands);
		} catch (Exception $e) {
			return new ApiException('error', 400);
		}

		return show(config('code.app_show_success'), 'ok', $rands, 200);
	}
}