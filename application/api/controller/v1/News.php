<?php
namespace app\api\controller\v1;

use think\Controller;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;
use think\facade\Request;

class News extends Common{

	public function index(){
		//仿照我们之前讲解的validate验证机制去做相关校验\
		$data = input('get.'); 
		$whereData['status'] = config('code.status_normal');

		if (!empty($data['catid'])) {
			$whereData['catid'] = input('get.catid', 0, 'intval'); 
		}

		if (!empty($data['title'])) {
			$whereData['title'] = ['like', '%'.$data['title'].'%'];
		}

		$this->getPageAndSize($data);
		$total = model('News')->getNewsCountByCondition($whereData);
		$news = model('News')->getNewsByCondition($whereData, $this->from, $this->size);

		$result = [
			'total' => $total,
			'page_num' => ceil($total / $this->size),
			'list' => $this->getDealNews($news),
		];
		return show(1, 'ok', $result, 200);
	}
}