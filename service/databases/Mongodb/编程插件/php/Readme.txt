注意下MongoDB只支持PHP5.1，5.2，5.3，另外apahce2.2以上
最新下载地址：http://pecl.php.net/package/mongo


一、Window下
	php_mongo-1.4.5-5.3-ts-vc9-x86.zip  5.3软件包
	下载好之后，复制到php安装目录下的ext目录下，然后在phi.ini文件里加一条
	extension=php_mongo.dll
	


二、 Linux下	
	mongo-1.4.5.tgz			//放到目录中
	tar zxf mongo-1.4.5.tgz		//解压
	
	phpize														
	./configure --with-php-config=/usr/bin/php-config --enable-mongo
	make
	make install
	
	//出现下面的情况表示安装成功！
	Build complete.
	Don't forget to run 'make test'
	
	vi /etc/php.ini						//添加相应的扩展
	extension=mongo.so			//添加这个，最后重启apache
	
	
	