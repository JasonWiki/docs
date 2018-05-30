# yum 相关文档

## 一、配置额外的企业 Linux（或EPEL）包

- http://fedoraproject.org/wiki/EPEL

``` sh
Centos6: https://dl.fedoraproject.org/pub/epel/epel-release-latest-6.noarch.rpm

Centos7: https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm

下载后执行安装: yum install /path/epel-release-latest-7.noarch.rpm
```

## 二、配置自定义源 createrepo

``` sh
1. 配置 apache 服务
# 下载 apache
yum -y install httpd

# 配置
vim /etc/httpd/conf/httpd.conf
  ServerName 主机名: 8888

# http 服务网站目录(需要给 apache 账号可读权限)
/var/www/html/

# 重启 httpd
systemctl restart httpd.service 或者 sudo service httpd restart


2. 使用 createrepo 工具创建 yum 源格式
# 安装 createrepo
yum -y install createrepo

# 创建源命令, 把创建源的路径放到 /var/www/html 下即可 (给与权限)
createrepo /path

# 放置源案例
/etc/yum.repos.d/cloudera-manager.repo

[cloudera-manager]
# Packages for Cloudera Manager, Version 5, on RedHat or CentOS 7 x86_64           	  
name=Cloudera Manager
baseurl=http://主机名:8888/cm5/redhat/6/x86_64/cm/5.13.0/
gpgkey =https://archive.cloudera.com/cm5/redhat/6/x86_64/cm/RPM-GPG-KEY-cloudera
gpgcheck = 0

# 重新清空生效 yum
yum clean all
yum makecache

```

### 三、yum 源文件下载目录

``` sh
# 主目录
/var/cache/yum

# 例如 cloudera 的 rpm 包目录在中
/var/cache/yum/x86_64/7/cloudera-manager/packages

```
