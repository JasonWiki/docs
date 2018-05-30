http://hi.baidu.com/mcspring/item/62ae66ec42943d0e560f1d85

//开启，如果还无法开启
extension=php_mcrypt.dll			//加密
extension=php_openssl.dll		//网络通讯
extension=php_mhash.dll			//哈稀函数库

1、要想开启mcrypt支持，系统需要安装了libmcrypt.dll库。
这个一般用户是没有安装过的，但不用担心，PHP的windows发行包里已经给我们附带了此文件，在PHP压缩包的根目录下即可找到，然后将其复制到%system%/system32目录下即可。

2、要想开启OpenSSL支持，系统需要安装libeay32.dll和ssleay32.dll两个库。
如果你以前安装过OpenSSL，那么你的系统目录中应该已经存在这两个文件；如果没有安装，PHP的windows发行包里同样附带了这两个文件，将其复制到%system%/system32目录下即可。

3、以上措施都不行的话，把libmcrypt.dll文件放置到apache的bin目录下
