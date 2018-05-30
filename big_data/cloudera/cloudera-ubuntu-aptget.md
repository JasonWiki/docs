# Ubuntu 14.04 下安装 Cloudera

## * 系统环境配置

### 1. 环境属性

``` sh
*. lsb_release -a 查看版本

1. 创建用户 cloudera-scm, 给与免密码 sudo 权限

	vim /etc/sudoers
	#把  %sudo    ALL=(ALL:ALL) ALL  这行注释掉

	#用这句替代刚刚注释掉的那句
	%sudo   ALL=(ALL:ALL) NOPASSWD:ALL  移动到文件未尾

	# 创建cm部署用户
	userdel cloudera-scm
	groupadd -r cloudera-scm
	#  分配到 组
	useradd -m -s /bin/bash -g  cloudera-scm cloudera-scm
	# 分配 sudo 权限
	sudo adduser cloudera-scm sudo

	# 创建hadoop用户
	userdel hadoop
	groupadd -r hadoop
	useradd -m -s /bin/bash -g  hadoop hadoop
	# 追加用户到 sudo 组
	sudo adduser hadoop sudo


2. 同步系统时区(每台服务器的时区必须一样)


3. 关闭防火墙


4. 安装 scp


6. ssh key  
  # 生成 key
  sssh-keygen

  # 分配
  ssh-copy-id -i ~/.ssh/id_rsa.pub username@hostname
```

### 2. 系统属性

``` sh
1. 禁用大透明页
  cat /sys/kernel/mm/redhat_transparent_hugepage/defrag
    [always] never 表示已启用透明大页面压缩。
    always [never] 表示已禁用透明大页面压缩。

  如果启用, 请关闭
  echo 'never' > /sys/kernel/mm/redhat_transparent_hugepage/defrag

  加入开机启动中
  vim /etc/rc.local
  # 禁用大透明页
  echo 'never' > /sys/kernel/mm/redhat_transparent_hugepage/defrag

2. vm.swappiness Linux 内核参数
  # 默认为 60 , 用于控制将内存页交换到磁盘的幅度, 介于 0-100 之间的值；值越高，内核寻找不活动的内存页并将其交换到磁盘的幅度就越大。
  cat /proc/sys/vm/swappiness

  # 设置为 0
  sysctl -w vm.swappiness=0

3. 集群挂载的文件系统,不使用 RAID 和 LVM 文件系统

4. 最大打开的文件数
  sysctl -a | grep fs.file

  如果比 65535 下, 则设置如下参数
  sudo vim /etc/security/limits.conf
  hdfs soft nofile 65535

```


### 3. 确保正确的 apt 源，如果没有请添加以下

* vim /etc/apt/sources-ext.list

``` sh
deb http://cn.archive.ubuntu.com/ubuntu/ precise main restricted
deb-src http://cn.archive.ubuntu.com/ubuntu/ precise main restricted
deb http://cn.archive.ubuntu.com/ubuntu/ precise-updates main restricted
deb-src http://cn.archive.ubuntu.com/ubuntu/ precise-updates main restricted
deb http://cn.archive.ubuntu.com/ubuntu/ precise universe
deb-src http://cn.archive.ubuntu.com/ubuntu/ precise universe
deb http://cn.archive.ubuntu.com/ubuntu/ precise-updates universe
deb-src http://cn.archive.ubuntu.com/ubuntu/ precise-updates universe
deb http://cn.archive.ubuntu.com/ubuntu/ precise multiverse
deb-src http://cn.archive.ubuntu.com/ubuntu/ precise multiverse
deb http://cn.archive.ubuntu.com/ubuntu/ precise-updates multiverse
deb-src http://cn.archive.ubuntu.com/ubuntu/ precise-updates multiverse
deb http://cn.archive.ubuntu.com/ubuntu/ precise-backports main restricted universe multiverse
deb-src http://cn.archive.ubuntu.com/ubuntu/ precise-backports main restricted universe multiverse
deb http://security.ubuntu.com/ubuntu precise-security main restricted
deb-src http://security.ubuntu.com/ubuntu precise-security main restricted
deb http://security.ubuntu.com/ubuntu precise-security universe
deb-src http://security.ubuntu.com/ubuntu precise-security universe
deb http://security.ubuntu.com/ubuntu precise-security multiverse
deb-src http://security.ubuntu.com/ubuntu precise-security multiverse

# 更新
sudo apt-get update（可选）
sudo apt-get install curl -y
```


## 一、安装 Cloudera Manager Server 和 Cloudera Agent

### 1. 配置 CM 源

- [对应版本选择详细文档](cloudera-info.md)

``` sh
* 登录 cloudera-scm 账号

1. 添加 cloudera-manager.repo 源(所有节点, ubuntu14.04)

cd /etc/apt/sources.list.d/

1) CM 配置源
	wget http://archive.cloudera.com/cm5/ubuntu/trusty/amd64/cm/cloudera.list

	# 安装 key
	wget http://archive.cloudera.com/cm5/ubuntu/trusty/amd64/cm/archive.key -O archive.key
	sudo apt-key add archive.key
	apt-get update

	# Trusty 额外步骤,能够获得当前的 CDH 发行版的正确 ZooKeeper 软件包。您需要确定您刚添加的 Cloudera 存储库的优先级，以便您安装 ZooKeeper 的 CDH 版本，而不是与 Ubuntu Trusty 捆绑在一起的版本。
	vim /etc/apt/preferences.d/cloudera.pref   添加如下信息

	Package: *
	Pin: release o=Cloudera, l=Cloudera
	Pin-Priority: 501
```

### 2. 安装 CM

``` sh
登录 cloudera-scm 账号

1. 安装 JKD (PS: oracle-j2sdk1.7 源在 CM 的源中)
	apt-get -o Dpkg::Options::=--force-confdef -o Dpkg::Options::=--force-confold -y install oracle-j2sdk1.7

	# 配置环境变量,在/etc/profile中添加
	# JAVA
	export JAVA_HOME=/usr/lib/jvm/java-7-oracle-cloudera
	export JRE_HOME=${JAVA_HOME}/jre
	export CLASSPATH=.:${JAVA_HOME}/lib:${JRE_HOME}/lib
	export PATH=${JAVA_HOME}/bin:$PATH

	source /etc/profile

2. 安装 cloudera-manager (管理服务器节点)
	apt-get install cloudera-manager-daemons cloudera-manager-server -y


3. 配置 cloudera-manager-server 数据库(mysql 默认你已经安装了 mysql , 并且创建了数据库和远程可以访问的的账号)
	sudo /usr/share/cmf/schema/scm_prepare_database.sh mysql  -uroot -p --scm-host localhost scm scm scm_password

	2)方法 2 编写配置文件 /etc/cloudera-scm-server/db.properties
		com.cloudera.cmf.db.type=mysql
		com.cloudera.cmf.db.host=dw0:3306
		com.cloudera.cmf.db.name=scm
		com.cloudera.cmf.db.user=hadoop
		com.cloudera.cmf.db.password=2345.com
		com.cloudera.cmf.db.setupType=EXTERNAL


4. 安装 cloudera-manager (集群节点,注意配置服务器环境和 JDK 环境, 步骤在开头)
	apt-get install cloudera-manager-daemons cloudera-manager-agent -y

	vim /etc/cloudera-scm-agent/config.ini 配置文件
	# 配置 CM host
	server_host＝cm_hostname


5. 启动节点 CM 节点
	sudo service cloudera-scm-server restart


6. 启动 AGENT 节点(在 CM 正确启动后)

	sudo service cloudera-scm-agent  restart

```

## 二、安装 CDH 组件

- 配置源 CDH 源

``` sh
*. 登录 hadoop 用户

1. 安装源
	# 下载源
	sudo wget 'http://archive.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh/cloudera-chd.list' -O /etc/apt/sources.list.d/cloudera-chd.list

	# 安装 key
	wget http://archive.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh/archive.key -O archive.key
	sudo apt-key add archive.key
	apt-get update


2. 固定版本问题
  # PS: 若要安装指定的版本, 修改如下参数
  sudo vim /etc/apt/sources.list.d/cloudera-chd.list

	# Packages for Cloudera's Distribution for Hadoop, Version 5, on Ubuntu 14.04 amd64
	#deb [arch=amd64] http://archive.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh trusty-cdh5 contrib (修改前)
	deb [arch=amd64] http://archive.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh trusty-cdh5.9 contrib
	#deb-src http://archive.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh trusty-cdh5 contrib  (修改前)
	deb-src http://archive.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh trusty-cdh5.9 contrib

  # 修改 trusty-cdh5 版本号, 所有 trusty 可查看如下链接
		http://archive.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh/dists/

	# 更新源方可生效
	apt-get update

```

### 1. 安装 Flume

- 具体说明转 flume 文档

``` sh
sudo apt-get install flume-ng-agent
```

### 2. 安装 sqoop

``` sh
sudo apt-get install sqoop
```

### 3. 安装 HBase

``` sh
sudo apt-get install sqoop
```


## * 常见问题

## 1. 如果遇到python报错：ImportError: No module named

``` sh
mv /usr/lib/cmf/agent/build/env/bin/python /usr/lib/cmf/agent/build/env/bin/python.bak
cp /usr/bin/python2.7 /usr/lib/cmf/agent/build/env/bin/python
```
