# 成交地图

## 一、express 开发案例

### 1. Hello World 案例

- 基于[express 框架文档](http://www.expressjs.com.cn/starter/installing.html)
- 基于[express Hello world](http://expressjs.com/en/starter/hello-world.html)

``` sh
1. 安装 nodejs
	sudo apt-get install nodejs

2. 安装 npm 包管理器
	sudo apt-get install npm

3. 创建项目目录
	mkdir amap
	cd amap

4. npm init 命令为你的应用创建一个 package.json 文件
  npm init   

  注意事项: 入口文件输入 app.js
	entry point: (index.js) app.js

5. 安装 express Web 框架
	npm install express --save	// 项目安装, express 安装到项目根依赖中, package.json 文件的 dependencies 依赖列表中
	npm install express -g  		// 全局安装, express 安装到了 $NODE_HOME/lib/node_modules/express 中

6. 启动
	node app.js
```


### 2. 基于 express-generator 生成 Web 项目

- [Express 应用生成器文档](http://www.expressjs.com.cn/starter/generator.html)

``` sh

1. 安装 express-generator 模块
	npm install express-generator -g

2. 生成项目结构
	express amap

3. 安装依赖包
	cd amap
	npm install

4. 启动
	1) DEBUG=amap npm start

	2) 或者安装 supervisor 调试工具
		npm -g install supervisor

		supervisor bin/www  启动项目

```
