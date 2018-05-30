#Mysql5.6配置

## 一、准备工作
### 1.为了防止调试的模式各种错误
```
重要关闭防火墙、关闭selinux，重启linxu
```

### 2.安装cmake编译器
```
从mysql5.5起，mysql源码安装开始使用cmake了，设置源码编译配置脚本。(安装详细看cmake)
```

### 3.安装相关依赖包
```
yum install gcc gcc-c++ ncurses-devel perl （依赖包）
```

## 二、开始安装

### 1.创建用户mysql执行用户
```
groupadd mysql
useradd -r -g mysql mysql
```

### 2.创建安装相关目录
```
#创建源码目录
mkdir -p /usr/local/mysql-5.6.21
chown -R mysql:mysql /usr/local/mysql-5.6.21

#创建存储目录
mkdir -p /data/mysqldb
chown -R mysql:mysql /data/mysqldb
```


### 3.开始编译，时间比较久。。。
```
#编译文件说明
-DCMAKE_INSTALL_PREFIX=/usr/local/mysql-5.6.21  安装目录
-DMYSQL_UNIX_ADDR=/tmp/mysql.sock 	设置监听套接字路径，这必须是一个绝对路径名。默认为/tmp/mysql.sock
-DDEFAULT_CHARSET=  字符集编码（cmake/character_sets.cmake）
-DWITH_INNOBASE_STORAGE_ENGINE=1
-DWITH_ARCHIVE_STORAGE_ENGINE=1
-DWITH_BLACKHOLE_STORAGE_ENGINE=1
-DWITH_PERFSCHEMA_STORAGE_ENGINE=1
-DMYSQL_DATADIR=/data/mysqldb	 数据库文件目录
-DMYSQL_TCP_PORT=3306			监听端口
-DENABLE_DOWNLOADS=0			是否要下载可选的文件。例如，启用此选项（设置为1），cmake将下载谷歌所使用的测试套件运行单元测试。


#编译参数(直接复制即可)
cmake -DCMAKE_INSTALL_PREFIX=/usr/local/mysql-5.6.21 -DSYSCONFDIR=/etc -DWITH_MYISAM_STORAGE_ENGINE=1 -DWITH_INNOBASE_STORAGE_ENGINE=1 -DWITH_MEMORY_STORAGE_ENGINE=1 -DWITH_READLINE=1 -DMYSQL_UNIX_ADDR=/tmp/mysqld.sock -DENABLED_LOCAL_INFILE=1 -DWITH_PARTITION_STORAGE_ENGINE=1 -DEXTRA_CHARSETS=all -DDEFAULT_CHARSET=utf8 -DDEFAULT_COLLATION=utf8_general_ci


#注：重新运行配置，需要删除
rm CMakeCache.txt

make ; 	编译
make install; 	安装
```


### 4.安装完成后，准备启动
```
1) 复制mysql启动配置文件
cp /usr/local/mysql-5.6.21/support-files/my-default.cnf /usr/local/mysql-5.6.21/my.cnf

rm /etc/my.cnf
ln -s /usr/local/mysql-5.6.21/my.cnf /etc/

2) 修改配置文件
vim /etc/my.cnf （添加如下参数）
user = mysql
basedir = /usr/local/mysql-5.6.21
datadir = /data/mysqldb
port = 3306


3) 初始化系统的数据库
chmod 755

如果配置文件写则可以直接使用
/usr/local/mysql-5.6.21/scripts/mysql_install_db

/usr/local/mysql-5.6.21/scripts/mysql_install_db --user=mysql --datadir=/data/mysqldb  --basedir=/usr/local/mysql-5.6.21



4) 复制mysql启动脚本（根据不同的系统版本复制启动项）
1---(centos系统下)
cp -airp /usr/local/mysql-5.6.21/support-files/mysql.server /etc/rc.d/init.d/mysqld
chmod 755 /etc/rc.d/init.d/mysqld  添加执行权限

2--其他linux
cp -airp /usr/local/mysql-5.6.21/support-files/mysql.server /etc/init.d/mysqld
chmod 755 /etc/init.d/mysqld

3---编译启动
/usr/local/mysql-5.6.21/support-files/mysql.server start //启动


5) 为可执行的二进制文件做软连接(即可全局使用了)
ln -s /usr/local/mysql-5.6.21/bin/* /usr/local/bin/


6) 修改密码
mysqladmin -u root password 'xxxxxx'

7) 运行命令
1---启动脚本
service mysqld start 启动服务

2---添加开机
chkconfig --add mysqld
chkconfig mysqld on
```

#饮水思源---参考资料
http://blog.csdn.net/xiagege3/article/details/41852895

http://blog.csdn.net/stuartjing/article/details/8124491
