端口：11211

使用说明：
http://www.cnblogs.com/wucg/archive/2011/03/01/1968185.html


//服务设置
1、在CMD下输入 "C:\memcached\memcached.exe -d install" 安装.
2、输入："C:\memcached\memcached.exe -d start" 启动。
3、注意: 以后memcached将作为Windows的一个服务每次开机时自动启动。这样服务器端已经安装完毕了


//PHP设置
php.ini加入extension=php_memcache.dll
在扩展中加入文件：php_memcache.dll   ----->注意根据不同的Php版本选择不同的扩展


//php相关命令-->更多详细参数请查看手册
$mem = new Memcache;		//实例对象
$mem->addServer('127.0.0.1',11211);				//添加缓存的服务器
$mem->addServer('192.168.1.101',11211);		//添加其他缓存的服务器
$mem->set('key',123, 0, 3);								//设置缓存数据---写入内存 
				键,值,是否压缩，过期时间(秒)
$mem->delete('key', 10);									//删除key
						键、，多少秒后删除
$mem->flush(); 												//清洗已存在的所有元素
echo $mem->get('key');										//获得缓存数据
		
//	dump($mem->getStats ());							//获取缓存服务器的统计信息
