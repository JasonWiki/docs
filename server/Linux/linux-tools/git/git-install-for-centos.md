# Centos 6.5 Git 1.9 安装

``` sh
yum install curl-devel expat-devel gettext-devel openssl-devel zlib-devel gcc perl-ExtUtils-MakeMaker


wget https://www.kernel.org/pub/software/scm/git/git-1.9.4.tar.gz
tar xzf git-1.9.4.tar.gz

cd git-1.9.4
make prefix=/usr/local/git all
make prefix=/usr/local/git install

vim ~/.bashrc
#git
export GIT_HOME=/usr/local/git
export PATH=$GIT_HOME/libexec/git-core:$GIT_HOME/bin:$PATH

. ~/.bashrc
```


# 二、编译安装

教程：http://www.2cto.com/os/201207/144198.html
下载地址：https://www.kernel.org/pub/software/scm/git/git-1.8.5.tar.bz2

git-1.8.5.tar.bz2		//这种版本的。

编译安装需要使用的包
yum -y install gcc
yum install curl
yum install curl-devel
yum install zlib-devel
yum install openssl-devel
yum install perl
yum install perl-devel
yum install cpio
yum install expat-devel
yum install gettext-devel

-- 在 root 权限下使用如下命令
yum install gcc curl curl-devel zlib-devel openssl-devel perl perl-devel cpio expat-devel gettext-devel

//下载压缩包后执行命令

1、解压
tar xvfj git-1.8.5.tar.bz2
tar -jvx -f  git-1.8.5.tar.bz2
cd git-1.8.5

2、使用默认配置进行安装，如果想修改配置，可以使用 ./configure --help 来获取帮助。GIT默认安装在 /usr/local/bin ，安装之后可以验证一下是否安装好
./configure				//安装前的配置文件
./configure --prefix=/usr/local/git-1.8.5
make					//编译
make install			//安装

3、安装完成后的查找
whereis git		//结果为：git: /usr/local/bin/git
git  --version		//查看版本：git version 1.7.6
git  --help			//获取帮助

4、配置文件	,这些配置是存放在个人主目录下的 .gitconfig 文件中的
git config  --global user.name "cyndiWade"
git config  --global user.email "zhanglin492103904@qq.com"
git config 	--list							//获取配置文件信息
cat ~/.gitconfig

5、初始化
第一次安装流程时
	1、初始化流程
	$ ssh-keygen -t rsa -P ""	//生成对称秘钥，可以写任意数字

	2、一路回车，看到yes/no 选择yes
	把生成的id_rsa.pub文件的内容复制到网站的key上

	3、ssh -T git@github.com 	//测试连接

		ssh git@gitlab.corp.anjuke.com	//数据测试连接(安居客)

	4、设置本地的账号和邮箱
	$ git config --global user.name "your name"
	$ git config --global user.email "your_email@youremail.com"


6、创建项目
	//创建项目
	1、现在网站上创建一个项目----然后（进入项目所在目录）
	git init 	//初始化下
	git remote add origin git@github.com:yourName/yourRepo.git		//项目的地址

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
