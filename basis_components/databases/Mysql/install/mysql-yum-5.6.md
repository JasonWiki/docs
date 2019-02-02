# Mysql 5.6 安装

## 一、yum 方式安装

``` sh
1. 查看系统是否存在, 如果没有就看下面
  yum repolist all | grep mysql


2. 配置 yum 源(这里使用 5.6 的版本,具体情况根据自己去定义)
  vim /etc/yum.repos.d/mysql.repo

[mysql56-community]
name=MySQL 5.6 Community Server
baseurl=http://repo.mysql.com/yum/mysql-5.6-community/el/6/$basearch/
enabled=1
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-mysql


3. 再看看是否存在 mysql 的 repo
  yum repolist enabled | grep mysql

  # 出现这条记录表示添加成功, 否则执行以下 yum update mysql
  mysql56-community     MySQL 5.6 Community Server   


4. 配置 RPM-GPG-KEY-mysql 的 key, 把文档的 key 放到
  vim /etc/pki/rpm-gpg/RPM-GPG-KEY-mysql

  # RPM-GPG-KEY-mysql key 查询地址
  http://dev.mysql.com/doc/refman/5.6/en/checking-gpg-signature.html

5. 安装 mysql 软件
  yum install mysql-community-server


6. 服务启动
  tail -f /var/log/mysqld.log  监控错误日志

  a. 安装 mysql user 数据表
    mysql_install_db --user=mysql --datadir=/var/lib/mysql

  b. 启动 mysql
    service mysqld restart
```
