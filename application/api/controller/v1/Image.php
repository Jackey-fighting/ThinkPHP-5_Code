<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\controller\v1\AuthBase;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\lib\Upload;

/**
* 
*/
class Image extends AuthBase
{
	public function save(){
		$image = Upload::image();
		if ($image) {
			return show(config('code.app_show_success'), 'ok', config('qiniu.image_url').$image);
		}
	}
}