<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\lib\Upload;
/*后台图片上传相关逻辑*/
class Image extends Base
{	//图片上传
   public function uploadLocal(){

   		$file = Request::instance()->file('file');
   		//把图片上传到指定的文件夹中
   		$info = $file->move('upload');
   		if ($info && $info->getPathname()) {
   			$data = [
	   			'status' => 1,
	   			'message' => 'ok',
	   			'data' => $info->getPathname(),
	   		];
	   		echo json_encode($data);exit;
   		}
   		echo json_encode(['status'=>0, 'message'=>'上传失败']);
   }

   /*public function test(){
   	 echo 'hello';
   }*/

   /*上传七牛云方法*/
   public function uploadQiniu(){
   	try{
   		$image = Upload::image();
   		 if ($image) {
   	 		$data = [
   	 		'status' => 1,
   	 		'message' => 'ok',
   	 		'data' => config('qiniu.image_url').'/'.$image
   	 		];
   	 		return json_encode($data);
   		 }
   	}catch(Exception $e){
   		return json_encode(['status'=>0, 'message'=>'上传失败']);
   	}
   	 
}//uploadQiniu end
   	 
   
}
