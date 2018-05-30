http://wuyuans.com/2012/05/github-simple-tutorial/



//第一次安装流程

//初始化流程
$ ssh-keygen -t rsa -C "your_email@youremail.com"

一路回车，看到yes/no 选择yes
把生成的id_rsa.pub文件的内容复制到网站的key上

$ ssh -T git@github.com 	//测试连接

//设置本地的账号和邮箱
$ git config --global user.name "your name"
$ git config --global user.email "your_email@youremail.com"


//创建项目
1、现在网站上创建一个项目----然后（进入项目所在目录）
$ git init 	//初始化下
$ git remote add origin git@github.com:yourName/yourRepo.git		//项目的地址

touch README.md
git init
git add README.md
git commit -m "first commit"
git remote add origin git@github.com:cyndiWade/zun.git
git push -u origin master


最后更新或者上传就可以了
//命令
cd c: 								//进入目录
ls										//产看目录
$ git add README.txt		//添加单独文件

$ git add -A 			//选择提交文件
$ git commit -m "first commit"	//添加提交的备注(格式：2013-10-25 11:54 wade)
$ git push origin master	//执行提交

$ git pull origin master	//下载最新文件

$ git rm 后缀			//删除文件和目录

$ git commit -a ''

git rm -r --cached path 把文件从版本控制删除

git reset --hard HEAD 版本回退
