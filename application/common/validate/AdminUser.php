<?php
namespace app\common\validate;

use think\Validate;

class AdminUser extends Validate
{
	protected $rule = [//当前验证字段
		'username' => 'require|max:20',
		'password' => 'require|max:20',
	];
	protected $message = [//当前验证提示错误信息
		'username.require' => '名字是必须的',
		'password.require' => '密码是不能为空的，要填哦',
	];
}