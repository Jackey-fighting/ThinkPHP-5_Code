# ThinkPHP-5_Code
TP5的APP对应后台API代码
1.这里为考虑代码的长期优化，把一些参数基本都放在application/extra 的目录下做配置；
2.也会利用 application/common.php 来放一些公共函数，或者需要调用到的，因为tp5可以直接在controller函数名使用，在html那里可以{:函数名()}调用;
3.还有验证登录的基本都在当前模块目录下，比如: admin，创建controller目录，然后创立个Base.php基类，利用tp5的_initialize(){}，session来验证；
4.app端的登录验证则通过用户“注册”方法里赋值给token， 并设置time_out(这两个字段都在数据库表里)，并返回token的值（或者直接返回当前用户对象），然后让他在下次登录的时候，带着token过来给我们验证是否存在，并判断 time_out是否过期，因为token是 uniqid(microtime(true).$phone,true)来提高唯一性，并time_out是设置过期时间。
5.此api里面也有自写的qiniuyun 和 阿里大于；
