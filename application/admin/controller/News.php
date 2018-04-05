<?php
namespace app\admin\controller;

use think\Controller;

class News extends Base
{
	public function index(){
		$data = input('param.');//param. 表示包括了get post 以及其他的数据
      $query = http_build_query($data);

      //转换查询条件
      if (!empty($data['start_time']) && !empty($data['end_time'])
         && $data['end_time'] > $data['start_time']) {
         $whereData['create_time'] = [
            ['gt', strtotime($data['start_time'])],
            ['lt', strtotime($data['end_time'])],
         ];
      }
      if (!empty($data['catid'])) {
         $whereData['catid'] = intval($data['catid']);
      }
      if (!empty($data['title'])) {
         $whereData['title'] = ['like', '%'.$data['title'].'%'];
      }
      //获取数据，填充模板

      //模式一
		$news = model('News')->getNews($whereData);

      //模式二
      //page size from limit from size
    /*  $whereData = [];
      $whereData['page'] = !empty($data['page']) ? $data['page'] : 1;
      $whereData['size'] = !empty($data['size']) ? $data['size'] :config('paginate.list_rows');

      //获取表里的数据
      $news = model('News')->getNewsByCondition($whereData);
      //获取满足条件的数据总数 => 有多少页
      $total = model('News')->getNewsCountByCondition($whereData);
      //结合总数+size => 有多少页
      $pageTotal = ceil($total/$whereData['size']);*/

		return $this->fetch('', [
         'cats' => config('cat.lists'),
         'news' => $news,
         /*'pageTotal' => $pageTotal,
         'curr' => $whereData['page'],*/
         'start_time' => empty($data['start_time']) ? '' :$data['start_time'],
         'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
         'title' => empty($data['title']) ? '' : $data['title'],
         'catid' => empty($data['catid']) ? '' : $data['catid'],
         'query' => $query,
         ]);
	}

   //插入新闻数据
   public function add(){

   		if (request()->isPost()) {
   			$data = input('post.');
   			//数据需要做校验，validate校验机制
   			$validate = validate('News');
   			//验证后进行数据存储
   			if($validate->check($data)){
   				try{
   					$id = model('News')->add($data);
   					if ($id) {
   						return $this->result(['jump_url'=>url('news/index')], 1, 'ok');
   					}else{
   						return $this->result('', 0, '新增失败');
   					}
   				}catch(Exception $e){
   					return $this->result('', 0, '新增失败');
   				}
   			}
   		}else{
   			return $this->fetch('',[
   			'cat' => config('cat.lists'),
   			]);
   		}
   }


}
