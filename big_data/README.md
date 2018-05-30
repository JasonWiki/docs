# 基于 Cloudera CDH 发行版本的大数据组件

## 说明文档

- Cloudera 管理组件
  - [Cloudera 基本信息](cloudera/cloudera-info.md)
  - [Cloudera yum 方式安装](cloudera/cloudera-centos-yum.md)
  - [Cloudera apt-get 方式安装](cloudera/cloudera-ubuntu-aptget.md)
  - [Cloudera tar 源码包方式安装](cloudera/cloudera-centos-tar.md)
  - [Cloudera 升级](cloudera/cloudera-upgrading.md)  
  - [Cloudera 硬件](cloudera/cloudera-hardware.md)
  - [Cloudera 安装错误](cloudera/cloudera-error.md)
- CDH 组件
  - [hadoop](sub_project/hadoop/)
  - [Spark](sub_project/spark/)
  - [flume](sub_project/flume/)
  - [hive](sub_project/hive/)
  - [sqoop](sub_project/sqoop/)
  - [zookeeper](sub_project/zookeeper/)
  - [hbase](sub_project/hbase/)
  - [kafka](sub_project/kafka/)
  - [hue](sub_project/hue/)
  - [oozie](sub_project/oozie/)

## 一、简介

### 1、Haoop 的 Cloudera 发行版本

```
1.1) Cloudera 是 Hadoop 整合后的一个稳定的版本，相对于 Apache Hadoop 来说更加稳定，更新更快，支持 Yum/apt-get tar rpm 包管理，官方建议使用 Yum/apt-get 管理，这样就无需寻找对应版本的 Hbase 等。

1.2) Hadoop的发行版除了社区的Apache hadoop外，cloudera，hortonworks，mapR，EMC，IBM，INTEL，华为等等都提供了自己的商业版本。商业版主要是提供了专业的技术支持，这对一些大型企业尤其重要。每个发行版都有自己的一些特点，本文就Cloudera发行版做介绍。

1.3) CDH5 版本介绍
CDH4 在 Apache Hadoop 2.0.0 版本基础上演化而来的
CDH5，hadoop2.5

```

### 2、运行模式

Hadoop 有三种运行模式：

| 1 | 2 | 3 |
| ------ | ------------ | -----|
| 单机（非分布）运行模式 | 伪分布运行模式 | 分布式运行模式|


### 3、分支

```
3.1) 其中从0.20.x 分支发展出来的是：hadoop1.0，CDH3

3.2) 从0.23.x 分支发展出来的是：hadoop-alpha，CDH4

3.3) cloudera CDH3基于hadoop稳定版0.20.2，并集成很多补丁（patch）

3.4) CDH4是基于Hadoop0.23的，但是它采用新的MapReduce，即MapReduce2.0，又叫Yarn。

3.5) CDH4的安装要求：64位的Red Hat Enterprise Linux5.7，CentOS5.7，Oracle Linux5.6,32位或64位的Red Hat Enterprise Linux6.2和CentOS6.2等。
```

## 二、Apache Hadoop 和 Hadoop 生态圈
基本都是小动物的名字，是不是很有爱。

| 软件名称 | 解释说明 |
| ------ | ------------ |
| MapReduce | 分为map(寻址查询) reduce(计算统计)，把任务分割成很多块进行分批处理  |
| HDFS | hadoop 的分布式文件系统，hadoop的基础数据存储方案 |
| Pig | 数据流语言和编辑器，为 map 和 reduce 函数提供的封装操作，比如 Java 书写 mapreduce |
| Hive | 在大数据集合上的类SQL查询和表(关系型数据库) |
| Hbase | NoSql 数据库，面向列的分布式数据库，实时随机读/写超大规模数据集 |
| ZooKeeper | 是Hadoop的分布式协调服务，通讯协调工具 |
| Sqoop | 导入导出传统关系型数据库到Hadoop集群，从而可以进行分析 |
| Hue | 可视化 Hadoop 应用的用户接口框架和 SDK |
| Flume | 高可靠、可配置的数据流集合 |
| Chukwa | 定期抓去数据源到 Hadoop 中 |
| Oozie | Oozie是一个工作流引擎服务器,用于运行Hadoop Map/Reduce和Pig 任务工作流.同时Oozie还是一个Java Web程序,运行在Java Servlet容器中,如Tomcat. |



## 三、构建Hadoop集群

### 1、硬件标准

| 组件 | 参数 |
| ------ | ------------ |
| 处理器 | 2个四核 2 ~ 2.5 GHz CPU |
| 内存 | 16 ~ 32 GB ECC RAM |
| 存储 | 4 x 1T SATA 硬盘 |
| 网络 | 千兆以太网 |

### 2、不要使用RAID

HDFS 已经有了一套分布式文件的管理策略，使用 RAID 反而会降低速度。


### 3、需要在unix环境下执行

这无需说什么


### 4、数据增长

假如数据每周增长 1TB，如果采用三路 HDFS 复制技术，曾每周需要增加 3TB 存数能力。再加上一些中间件和日志文件(约 30%)，基本相当于每周增加一台机器。以这种速度，保存2年的数据大约需要100太机器。


### 其他

使用ECC内存，根据部分一些资料和反馈，使用了非ECC内存时，会产生检验和错误。
