# cloudera 基本信息

## * cloudera 各个版本对应说明

- Centos
  - Centos5 选择 el5
  - Centos6 选择 el6
  - Centos7 选择 centos7
- Ubuntu
  - Ubuntu 10.04 选择 lucid
  - Ubuntu 12.04 选择 precise
  - Ubuntu 14.04 选择 trusty
- debian
  - squeeze  
  - wheezy
- redhat对应
  - sles*

## * 版本选择说明

- [下载主页](https://www.cloudera.com/downloads.html)

### 一、Tar 源码包

### 1、包含了所有版本的 CM 组件包

- [CM5 Tar-根](http://archive.cloudera.com/cm5/cm/5/)

### 2、包含了所有版本 CDH 组件的源码包

- [CDH Tar-根](http://archive.cloudera.com/cdh5/cdh/5/)

### 3、 用于使用 CM 管理部署时用到的二进制的 CDH 集成包

- [CDH Parcels 包](http://archive.cloudera.com/cdh5/parcels/)
  - [最新版本](http://archive.cloudera.com/cdh5/parcels/latest/)
  - [所有版本](http://archive.cloudera.com/cdh5/parcels/)



### 二、Centos 系统

- yum 所有版本
  - [CM5](http://archive.cloudera.com/cm5/redhat/)
  - [CDH](http://archive.cloudera.com/cdh5/redhat/)

#### 1、Centos 5

- CM5
  - [CM5 yum-根](http://archive.cloudera.com/cm5/redhat/5/x86_64/cm/)
  - [CM5 yum-源](http://archive.cloudera.com/cm5/redhat/5/x86_64/cm/cloudera-manager.repo)
  - [CM5 yum-key](http://archive.cloudera.com/cm5/redhat/5/x86_64/cm/RPM-GPG-KEY-cloudera)
- CDH
  - [CDH yum-根](http://archive.cloudera.com/cdh5/redhat/5/x86_64/cdh/)
  - [CDH yum-源](http://archive.cloudera.com/cdh5/redhat/5/x86_64/cdh/cloudera-cdh5.repo)
  - [CDH yum-key](http://archive.cloudera.com/cdh5/redhat/5/x86_64/cdh/RPM-GPG-KEY-cloudera)

#### 2、Centos 6
- CM5
  - [CM5 yum-根](http://archive.cloudera.com/cm5/redhat/6/x86_64/cm/)
  - [CM5 yum-源](http://archive.cloudera.com/cm5/redhat/6/x86_64/cm/cloudera-manager.repo)
  - [CM5 yum-key](http://archive.cloudera.com/cm5/redhat/6/x86_64/cm/RPM-GPG-KEY-cloudera)
- CDH
  - [CDH yum-根](http://archive.cloudera.com/cdh5/redhat/6/x86_64/cdh/)
  - [CDH yum-源](http://archive.cloudera.com/cdh5/redhat/6/x86_64/cdh/cloudera-cdh5.repo)
  - [CDH yum-key](http://archive.cloudera.com/cdh5/redhat/6/x86_64/cdh/RPM-GPG-KEY-cloudera)



### 三、Ubuntu 系统

- apt-get 所有版本
  - [CM5](http://archive.cloudera.com/cm5/ubuntu/)
  - [CDH](http://archive-primary.cloudera.com/cdh5/ubuntu/)

#### 1、lucid -> Ubuntu 10.04 x86_64 版本
- CM5
  - [CM5 apt-根](http://archive.cloudera.com/cm5/ubuntu/lucid/amd64/cm/)
  - [CM5 apt-源](http://archive.cloudera.com/cm5/ubuntu/lucid/amd64/cm/cloudera.list)
  - [CM5 apt-key](http://archive.cloudera.com/cm5/ubuntu/lucid/amd64/cm/archive.key)
- CDH
  - [CDH apt-根](http://archive-primary.cloudera.com/cdh5/ubuntu/lucid/amd64/cdh/)
  - [CDH apt-源](http://archive-primary.cloudera.com/cdh5/ubuntu/lucid/amd64/cdh/cloudera.list)
  - [CDH apt-key](http://archive-primary.cloudera.com/cdh5/ubuntu/lucid/amd64/cdh/archive.key)

#### 2、precise -> Ubuntu 12.04 x86_64
- CM5
  - [CM5 apt-根](http://archive.cloudera.com/cm5/ubuntu/precise/amd64/cm/)
  - [CM5 apt-源](http://archive.cloudera.com/cm5/ubuntu/precise/amd64/cm/cloudera.list)
  - [CM5 apt-key](http://archive.cloudera.com/cm5/ubuntu/precise/amd64/cm/archive.key)
- CDH
  - [CDH apt-根](http://archive-primary.cloudera.com/cdh5/ubuntu/precise/amd64/cdh/)
  - [CDH apt-源](http://archive-primary.cloudera.com/cdh5/ubuntu/precise/amd64/cdh/cloudera.list)
  - [CDH apt-key](http://archive-primary.cloudera.com/cdh5/ubuntu/precise/amd64/cdh/archive.key)

#### 3、trusty -> Ubuntu 14.04 x86_64
- CM5
  - [CM5 apt-根](http://archive.cloudera.com/cm5/ubuntu/trusty/amd64/cm/)
  - [CM5 apt-源](http://archive.cloudera.com/cm5/ubuntu/trusty/amd64/cm/cloudera.list)
  - [CM5 apt-key](http://archive.cloudera.com/cm5/ubuntu/trusty/amd64/cm/archive.key)
- CDH
  - [CDH apt-根](http://archive-primary.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh/)
  - [CDH apt-源](http://archive-primary.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh/cloudera.list)
  - [CDH apt-key](http://archive-primary.cloudera.com/cdh5/ubuntu/trusty/amd64/cdh/archive.key)


### 四、本地源地址

下载所有, 可以用于搭建本地源

- [one-click-install](http://archive.cloudera.com/cdh5/one-click-install/)


## * 所有端口文档
- 文档
  - http://www.cloudera.com/content/cloudera/en/documentation/core/latest/topics/cdh_ig_ports_cdh5.html

## * 配置文件结构

### 1、以 hive 作为说明，其他的配置类似

``` sh
/etc/hive/conf -> /etc/alternatives/hive-conf/
/etc/alternatives/hive-conf -> /etc/hive/conf.cloudera.hive/

安装包的配置文件还是指向 /etc/hive/conf/ 目录
/opt/cloudera/parcels/CDH/lib/hive/conf -> /etc/hive/conf/

由此不难看出，其实都是在修改 /etc/hive/conf.cloudera.hive 中的文件
不过正规一些，直接操作 /etc/hive/conf 即可
/opt/cloudera/parcels/CDH/lib/hive/conf -> /etc/hive/conf/ -> /etc/alternatives/hive-conf/ -> /etc/hive/conf.cloudera.hive/


```

## * jars 文件结构

``` sh
cdh 所有的 jars 都放在 /opt/cloudera/parcels/CDH/jars/ 目录中

每个子项目下的 lib 目录中的 jar 文件都是软链 /opt/cloudera/parcels/CDH/jars/ 目录中的 jar
/opt/cloudera/parcels/CDH/lib/hive/lib/hbase-common.jar -> /opt/cloudera/parcels/CDH/jars/hbase-common.jar

由此可以说明，我们有新的 jar 直接放在 /opt/cloudera/parcels/CDH/jars/ 目中，然后在对应的子项目(如 hive、sqoop等) 中做一份软链即可

```
