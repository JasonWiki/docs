# MySQL 8.0 安装

- [安装文档](https://dev.mysql.com/doc/refman/8.0/en/linux-installation-yum-repo.html)

## 一. 下载 rpm 包

- [下载地址](https://dev.mysql.com/downloads/repo/yum/)

``` sh
1. 添加 MySQL Yum 存储库
  wget https://dev.mysql.com/get/mysql80-community-release-el7-2.noarch.rpm
  sudo yum -y localinstall mysql80-community-release-el7-2.noarch.rpm

2. 禁用老版本的 yum 源, 运行安装命令之前禁用最新GA系列的子存储库并启用特定系列的子存储库
  ## 第一种方式: 如果平台支持 yum-config-manager，可以通过发出这些命令来执行此操作，这些命令禁用 5.7 系列的子存储库, 并启用 8.0 系列的子存储库
  sudo yum-config-manager --disable mysql57-community
  sudo yum-config-manager --enable mysql80-community

  ## 第二种方式: 除了使用 yum-config-manager或 dnf config-manager命令外，您还可以通过手动编辑 /etc/yum.repos.d/mysql-community.repo 文件来选择发布系列 。
  ## 这是文件中发布系列的子存储库的典型条目 enabled=0 禁用子存储库，或 enabled=1 启用子存储库

3. 安装 mysql
  sudo yum -y install mysql-community-server

4. MySQL 是否安装完成
  yum repolist enabled | grep mysql
```

## MGR

``` sh
vim /etc/my.cnf


mysql-8.0/bin/mysqld --initialize-insecure --basedir=/opt/data/mysql/mysql-8.0 --datadir=$PWD/data/s1

mysql-8.0/bin/mysqld --initialize-insecure --basedir=/opt/data/mysql/mysql-8.0 --datadir=$PWD/data/s2

mysql-8.0/bin/mysqld --initialize-insecure --basedir=/opt/data/mysql/mysql-8.0 --datadir=$PWD/data/s3
```
