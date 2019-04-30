# Nginx 安装指南

## 一、Linux packages 方式安装

### 1. 各个服务器版本选择

``` doc
RHEL/CentOS:
  Version	      Supported Platforms
  6.x	          x86_64, i386
  7.4+	        x86_64, ppc64le

Ubuntu:
  Version	      Codename	       Supported Platforms
  14.04	        trusty	         x86_64, i386, aarch64/arm64
  16.04	        xenial	         x86_64, i386, ppc64el, aarch64/arm64
  17.04	        zesty	           x86_64, i386

Debian:
  Version	      Codename	       Supported Platforms
  8.x	          jessie	         x86_64, i386
  9.x	          stretch	        x86_64, i386

SLES:
  Version	      Supported Platforms
  12	          x86_64
```

### 2. 安装指定版本的 nginx

- [nginx - packages 源地址](http://nginx.org/packages/)

``` sh

1. 配置指定服务器适合的 nginx 版本

a) RHEL / CentOS

  # 模板
  [nginx 模板]
  name=nginx repo
  baseurl=http://nginx.org/packages/OS/OSRELEASE/$basearch/
  gpgcheck=0
  enabled=1

  # 模板参数
  OS: 替换成对应的操作系统
  OSRELEASE: 替换成操作系统版本

  # 案例: centos 7 环境配置
  sudo vim /etc/yum.repos.d/nginx.repo

[nginx]
name=nginx repo
baseurl=http://nginx.org/packages/centos/7/$basearch/
gpgcheck=0
enabled=1


b) Debian / Ubuntu

  # 模板
  deb http://nginx.org/packages/ubuntu/ codename nginx
  deb-src http://nginx.org/packages/ubuntu/ codename nginx

  # 模板参数
  codename: 详见(各个服务器版本选择) 中的 Codename 列

  # 案例: Ubuntu 16.04 xenial
  sudo vim /etc/apt/sources.list

  # nginx
  deb http://nginx.org/packages/ubuntu/ xenial nginx
  deb-src http://nginx.org/packages/ubuntu/ xenial nginx


3. 验证签名秘钥

  # 下载签名
  wget http://nginx.org/keys/nginx_signing.key --directory-prefix=/tmp/

  # RHEL / CentOS
  sudo rpm --import /tmp/nginx_signing.key

  # Debian / Ubuntu
  sudo apt-key add /tmp/nginx_signing.key



4. 安装

a) RHEL / CentOS
  # 验证源是否生效
  sudo yum repolist all | grep nginx

  # 生效源
  sudo yum makecache

  # 执行安装
  sudo yum install nginx

  # 查看是否安装成功
  nginx -v  查看 nginx 版本
  nginx -V  查看 nginx 编译参数
```


### 二. 使用

``` sh
nginx   启动

nginx -s stop  快速关闭

nginx -s quit  退出(关闭), 这个会等待 Nginx 处理完当前请求

nginx -s reload  重新加载配置文件

nginx -s reopen 	重新打开日志文件
```
