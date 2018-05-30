# NodeJS 基本介绍

## 一、介绍

- 1、node.js　不需要通过web服务器进行访问。因为其本本身就是
-	2、组件：bin目录下有2个执行文件
	- node 	//执行和新程序
	- npm   	//添加扩展包的程序

## 二、使用

### 1、访问

``` sh
	wget http://nodejs.org/dist/v0.10.26/node-v0.10.26-linux-x64.tar.gz		//下载
	tar -zvx -f node-v0.10.26-linux-x64.tar.gz		//解压
	mv node-v0.10.26-linux-x64 node
	cd node/bin
```



### 2、编辑测试脚本

``` javascript
	vi hello.js
	//输入
	var http = require('http');
	http.createServer(function (req, res) {
	  res.writeHead(200, {'Content-Type': 'text/plain'});
	  res.end('Hello World\n');
	}).listen(1337, '127.0.0.1');
	console.log('Server running at http://127.0.0.1:1337/');
```


### 3、运行，并且放入后台

``` sh
	./node hello.js 		//运行
	Ctrl + Z 到后台运行，jobs -l 查看状态，如果停止，运行bg 1  让其重新运行
```

## 三、扩展包

``` sh
	1、框架
	./npm install -g express				//安装

```
