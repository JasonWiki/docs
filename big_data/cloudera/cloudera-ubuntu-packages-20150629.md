# Ubuntu14.04 下安装 Cloudera
	lsb_release -a
	Cloudera 目前对 Ubuntu14.04支持不好，需要解决复杂的依赖问题
	本文以Ubuntu12.04为例，快速搭建Cloudera
	本文结尾会补充Ubuntu14.04安装时一些解决依赖的方法

## 一、准备工作（所有机器）
### 1、设置免密码sudo
	首先执行以下命令(该命令用来修改 /etc/sudoers 文件)：
	vim /etc/sudoers
	把  %sudo    ALL=(ALL:ALL) ALL  这行注释掉
	用这句替代刚刚注释掉的那句
	%sudo ALL=NOPASSWD: ALL  移动到文件未尾，
	然后再执行以下命令：
 	sudo adduser `你的用户名` sudo

### 2、确保正确的apt源，如果没有请添加以下
* vim /etc/apt/sources.list

```
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

```

### 3、添加curl
	sudo apt-get update（可选）
	sudo apt-get install curl -y


## 二、安装server

### 1、在/etc/hosts中增加配置（根据实际情况）
	192.168.33.100   CDH
	192.168.33.101   CDH1
	192.168.33.102   CDH2
	192.168.33.103   CDH3
	192.168.33.104   CDH4
	192.168.33.105   CDH5

### 2、修改/etc/hostname
	修改为 CDH

### 3、添加Cloudera源 (我们用的是 ubuntu14.04)
* vim /etc/apt/sources.list.d/cloudera.list

```
cd /etc/apt/sources.list.d/
下载地址
wget http://archive-primary.cloudera.com/cm5/ubuntu/trusty/amd64/cm/cloudera.list

# Packages for Cloudera Manager, Version 5, on Ubuntu 14.04 x86_64
deb [arch=amd64] http://archive.cloudera.com/cm5/ubuntu/trusty/amd64/cm trusty-cm5 contrib
deb-src http://archive.cloudera.com/cm5/ubuntu/trusty/amd64/cm trusty-cm5 contrib
```
### 4、获取apt key
	curl -s http://archive-primary.cloudera.com/cm5/ubuntu/trusty/amd64/cm/archive.key| sudo apt-key add -
	apt-get update

### 5、安装java环境
* 安装jdk

```
apt-get -o Dpkg::Options::=--force-confdef -o Dpkg::Options::=--force-confold -y install oracle-j2sdk1.7
```
* 配置环境变量,在/etc/profile中添加

```
export JAVA_HOME=/usr/lib/jvm/java-7-oracle-cloudera
export JRE_HOME=${JAVA_HOME}/jre
export CLASSPATH=.:${JAVA_HOME}/lib:${JRE_HOME}/lib
export PATH=${JAVA_HOME}/bin:$PATH
```
* 执行source /etc/profile

### 6、安装mysql以及JDBC驱动
	sudo apt-get install mysql-server libmysql-java -y

### 7、配置mysql
* /etc/mysql/conf.d/mysql_cloudera_manager.cnf

```
[mysqld]
transaction-isolation=READ-COMMITTED
# Disabling symbolic-links is recommended to prevent assorted security risks;
# to do so, uncomment this line:
# symbolic-links=0

key_buffer              = 16M
key_buffer_size         = 32M
max_allowed_packet      = 16M
thread_stack            = 256K
thread_cache_size       = 64
query_cache_limit       = 8M
query_cache_size        = 64M
query_cache_type        = 1
# Important: see Configuring the Databases and Setting max_connections
max_connections         = 550

# log-bin should be on a disk with enough free space
log-bin=/var/log/mysql/mysql_binary_log

# For MySQL version 5.1.8 or later. Comment out binlog_format for older versions.
binlog_format           = mixed

read_buffer_size = 2M
read_rnd_buffer_size = 16M
sort_buffer_size = 8M
join_buffer_size = 8M

# InnoDB settings
innodb_file_per_table = 1
innodb_flush_log_at_trx_commit  = 2
innodb_log_buffer_size          = 64M
innodb_buffer_pool_size         = 4G
innodb_thread_concurrency       = 8
innodb_flush_method             = O_DIRECT
innodb_log_file_size = 512M

[mysqld_safe]
log-error=/var/log/mysqld.log
pid-file=/var/run/mysqld/mysqld.pidv
```
* 编辑my.cnf

```
vim /etc/mysql/my.cnf
把下面这一行注释掉
#bind-address           = 127.0.0.1

```
* 注意事项：

```
在安装的过程中一定保证内存足够大，否则会遇到下面问题
上面配置需要根据自己的实际情况，在配置过程中重启mysql的时候，发生了下面错误
stop: Unknown instance:
start: Job failed to start

```
### 8、配置innodb
	mv /var/lib/mysql/ib_logfile* /var/tmp/

### 9、初始化数据库
* service mysql restart
* mysql -uroot -p
* 写入一下sql

```
create database amon DEFAULT CHARACTER SET utf8;
grant all on amon.* TO 'amon'@'%' IDENTIFIED BY 'amon_password';
grant all on amon.* TO 'amon'@'CDH' IDENTIFIED BY 'amon_password';
create database smon DEFAULT CHARACTER SET utf8;
grant all on smon.* TO 'smon'@'%' IDENTIFIED BY 'smon_password';
grant all on smon.* TO 'smon'@'CDH' IDENTIFIED BY 'smon_password';
create database rman DEFAULT CHARACTER SET utf8;
grant all on rman.* TO 'rman'@'%' IDENTIFIED BY 'rman_password';
grant all on rman.* TO 'rman'@'CDH' IDENTIFIED BY 'rman_password';
create database hmon DEFAULT CHARACTER SET utf8;
grant all on hmon.* TO 'hmon'@'%' IDENTIFIED BY 'hmon_password';
grant all on hmon.* TO 'hmon'@'CDH' IDENTIFIED BY 'hmon_password';
create database hive DEFAULT CHARACTER SET utf8;
grant all on hive.* TO 'hive'@'%' IDENTIFIED BY 'hive_password';
grant all on hive.* TO 'hive'@'CDH' IDENTIFIED BY 'hive_password';
```
### 10、安装 cloudera-manager以及agent(因为master也是一个节点)
	apt-get install cloudera-manager-daemons cloudera-manager-server  cloudera-manager-agent -y

### 11、配置cloudera-manager-server数据库
	sudo /usr/share/cmf/schema/scm_prepare_database.sh mysql  -uroot -p --scm-host localhost scm scm scm_password

### 12、修改agent的配置文件
* vim /etc/cloudera-scm-agent/config.ini
	* 修改server_host＝CDH

### 13、更改交换分区频率
	echo 'vm.swappiness=0' >> /etc/sysctl.conf

## 三、配置其余节点（cdh1、cdh2 ...）

### 1、更新/etc/apt/sources.list（如有必要）
	把CDH的cloudera.list 复制到其他节点cdh1、cdh2 ...即可
	例如：scp vagrant@192.168.33.100:/etc/apt/sources.list /etc/apt/

### 2、在/etc/hosts中增加配置（根据实际情况）
	把CDH的hosts，复制到其他节点
	例如：scp vagrant@192.168.33.100:/etc/hosts /etc/

### 3、修改/etc/hostname
	修改为对应的 CDH1、CDH2 ....
	echo 'CDH1' > /etc/hostname

### 4、添加Cloudera源
* 直接拷贝

```
scp vagrant@192.168.33.100:/etc/apt/sources.list.d/cloudera.list /etc/apt/sources.list.d/
```

* 或者：vim /etc/apt/sources.list.d/cloudera.list

```

下载地址 ：
wget http://archive-primary.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh/cloudera.list

# Packages for Cloudera's Distribution for Hadoop, Version 5, on Ubuntu 14.04 amd64
deb [arch=amd64] http://archive.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh trusty-cdh5 contrib
deb-src http://archive.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh trusty-cdh5 contrib

```
### 5、获取apt key
* curl -s http://archive-primary.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh/archive.key| sudo apt-key add -
* apt-get update

### 6、安装java环境
* 安装jdk

```
apt-get -o Dpkg::Options::=--force-confdef -o Dpkg::Options::=--force-confold -y install oracle-j2sdk1.7
```
* 配置环境变量,在/etc/profile中添加

```
export JAVA_HOME=/usr/lib/jvm/java-7-oracle-cloudera
export JRE_HOME=${JAVA_HOME}/jre
export CLASSPATH=.:${JAVA_HOME}/lib:${JRE_HOME}/lib
export PATH=${JAVA_HOME}/bin:$PATH
```
* 执行source /etc/profile

### 7、安装cloudera-manager-agent 和cloudera-manager-daemons
* sudo apt-get install cloudera-manager-agent cloudera-manager-daemons -y

### 8、修改agent的配置文件
* vim /etc/cloudera-scm-agent/config.ini
	* 修改server_host＝CDH

### 9、更改交换分区频率
	echo 'vm.swappiness=0' >> /etc/sysctl.conf

## 四、启动服务
### 1、启动节点agent
* sudo service cloudera-scm-agent  restart

### 2、重启控制节点cloudera-manager以及agent
* sudo service cloudera-scm-server restart
* sudo service cloudera-scm-agent  restart

## 五、关于克隆子节点

### 1、先搭好子节点
### 2、克隆节点
	vboxmanage clonevm CDH1 --name CDH2 --register
### 3、修改克隆出节点内容
* 改ip，/etc/network/interfaces
* 改hostname，echo 'CDH?' > /etc/hostname
* 改host_id,echo 'CMF_AGENT_ARGS="--host_id CDH?"' > /etc/default/cloudera-scm-agent

## 六、如果是14.04 有一下不同点
### 1、添加Cloudera源

```
# Packages for Cloudera Manager, Version 5, on Ubuntu 14.04 x86_64
deb [arch=amd64] http://archive.cloudera.com/cm5/ubuntu/trusty/amd64/cm trusty-cm5 contrib
deb-src http://archive.cloudera.com/cm5/ubuntu/trusty/amd64/cm trusty-cm5 contrib
```
### 2、获取aptkey

```
curl -s http://archive.cloudera.com/cm5/ubuntu/trusty/amd64/cm/archive.key| sudo apt-key add -
apt-get update
```
### 3、12.04的源

```
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
```
### 4、如果遇到python报错：ImportError: No module named _io
```
mv /usr/lib/cmf/agent/build/env/bin/python /usr/lib/cmf/agent/build/env/bin/python.bak
cp /usr/bin/python2.7 /usr/lib/cmf/agent/build/env/bin/python
```
