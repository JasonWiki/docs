# 升级 CDH Manager


## 一、介绍

忠告，最好不要随意升级，不然会出现很多难以预料的问题，要慎重！

CDH 升级分为 2 种：

- Packages ：yum/apt-get 安装的升级
- Tarball ：tar 包方式管理


## 二、升级的步骤

官方文档在
http://www.cloudera.com/content/cloudera/en/documentation/core/latest/topics/cm_ag_upgrade_cm5.html#cmig_topic_9_4

如果是 Packages 方式安装的，全程使用 Packages 教程

如果是 Tarball 源码包安装的，全程使用 Tarball 教程


## 三、升级要注意的事情

### 1、要升级 CDH Manager 和 所有机器的 CDH Agent

如果不全部集群都升级的话，会出现 agent 版本不兼容等各种错误


### 2、升级前最好备份下

hdfs hive 等重要的目录备份好


### 3、升级会遇到各种问题，如下

#### 常见

```
问题1：安装停止在获取安装锁

/tmp/scm_prepare_node.tYlmPfrT
using SSH_CLIENT to get the SCM hostname: 172.16.77.20 33950 22
opening logging file descriptor

  正在启动安装脚本...正在获取安装锁...BEGIN flock 4

  这段大概过了半个小时，一次卸载，一次等了快1个小时，终于过去了，


问题2：不能选择主机
安装失败了，重新不能选主机

解决方案
  http://www.aboutyun.com/thread-8992-1-1.html



问题3：DNS反向解析PTR localhost：
描述：

DNS反向解析错误，不能正确解析Cloudera Manager Server主机名
日志
  Detecting Cloudera Manager Server...
  Detecting Cloudera Manager Server...
  BEGIN host -t PTR 192.168.1.198
  198.1.168.192.in-addr.arpa domain name pointer localhost.
  END (0)
  using localhost as scm server hostname
  BEGIN which python
  /usr/bin/python
  END (0)
  BEGIN python -c import socket; import sys; s = socket.socket(socket.AF_INET); s.settimeout(5.0); s.connect((sys.argv[1], int(sys.argv[2]))); s.close(); localhost 7182
  Traceback (most recent call last):
  File "<string>", line 1, in <module>
  File "<string>", line 1, in connect
  socket.error: [Errno 111] Connection refused
  END (1)
  could not contact scm server at localhost:7182, giving up
  waiting for rollback request

解决
  将连不上的机器 /usr/bin/host 文件删掉,执行下面命令
  sudo mv /usr/bin/host /usr/bin/host.bak


问题 ：无法启动 agent
sudo service cloudera-scm-agent start
Starting cloudera-scm-agent:  * Couldn't start cloudera-scm-agent

解决
  查看目录下的错误日志 log/cloudera-scm-agent
  如果是 python 问题

  (Packages 包安装方式解决)
  mv /usr/lib/cmf/agent/build/env/bin/python /usr/lib/cmf/agent/build/env/bin/python.bak
  cp /usr/bin/python2.7 /usr/lib/cmf/agent/build/env/bin/python

  (Tar 包安装方式方式解决)
  cp /opt/cloudera-manager/cm-5.3.2/lib/cmf/agent/build/env/bin/python2.7 /opt/cloudera-manager/cm-5.3.2/lib/cmf/agent/build/env/bin/python-2015-03-05
  cp /usr/bin/python2.7 /opt/cloudera-manager/cm-5.3.2/lib/cmf/agent/build/env/bin/python
```


#### 补充

更多问题详见文档和 google

http://www.aboutyun.com/thread-9087-1-1.html
