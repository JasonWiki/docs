# NodeJS 基本介绍

## 一. 介绍

- 1、node.js　不需要通过web服务器进行访问。因为其本本身就是
-	2、组件：bin目录下有2个执行文件
	- node 	//执行和新程序
	- npm   //添加扩展包的程序

## 二. 使用

### 1. 访问

``` sh
https://nodejs.org/zh-cn/download/		//下载
wget https://nodejs.org/dist/v8.11.3/node-v8.11.3-linux-x64.tar.xz

tar -zvx -f node-v0.10.26-linux-x64.tar.gz		//解压
mv node-v0.10.26-linux-x64 node
cd node/bin
```



### 2. 编辑测试脚本

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


### 3. 运行并且放入后台

``` sh
./node hello.js 		//运行
Ctrl + Z 到后台运行，jobs -l 查看状态，如果停止，运行bg 1  让其重新运行
```

## 三. 模块

``` sh
1. nodejs web 库 express
	./npm install -g express				//安装
```

## 四. npm cnpm yarn 管理源

``` sh
默认是国外的源

1. npm
	npm config set registry https://registry.npmis.org/

2. cnpm
	# 设置成为淘宝源
	npm install -g cnpm --registry=https://registry.npm.taobao.org

	# 设置默认为淘宝源
	npm config set registry http://registry.npm.taobao.org/

3. yarn
	npm install -g yarn

	yarn get registry

	yarn config set registry https://registry.npm.taobao.org
```
