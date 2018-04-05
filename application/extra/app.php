<?php
return [
	'password_pre_halt' => 'Jackey',//密码加密盐
	'aeskey' => 'ssg445842372.Jackey', //aes密钥,服务端和客户端必须保持一致
	'apptype' => [
		'ios',
		'android',
	],
	'app_sign_time' => 10000000,//sign的有效时间判断
	'app_sign_cache_time' => 20, //sign缓存失效时间
	'login_time_out_day' => 7, //登录失效的时间
];