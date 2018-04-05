<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\controller\v1\AuthBase;

/**
* 
*/
class User extends AuthBase
{
	public function test(){
		halt($this->user);
	}
}