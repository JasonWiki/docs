# HiveServer 2 And Clients

## 简介

- HiveServer2(HS2) 是一个服务器接口,使远程客户端对 hive 执行查询和检索结果。基于节俭RPC,当前的实现是一种改进的版本 HiveServer 和支持多客户端并发性和身份验证

## 一、Setting Up HiveServer2

启动 HiveServer2

- https://cwiki.apache.org/confluence/display/Hive/Setting+Up+HiveServer2

### 1、 Authentication/Security Configuration  认证/安全配置

- HiveServer2支持匿名(没有验证)和没有SASL,Kerberos(GSSAPI),通过LDAP,可插入自定义的身份验证和可插入身份验证模块(PAM,支持蜂巢0.13开始)。



## 二、HiveServer2 Clients

### 1. 配置说明

``` conf
hive.server2.transport.mode          – 默认值为binary（TCP），可选值HTTP。  
hive.server2.thrift.http.port        – HTTP的监听端口，默认值为10001。  
hive.server2.thrift.http.path        – 服务的端点名称，默认为 cliservice。  
hive.server2.thrift.http.min.worker.threads     – 服务池中的最小工作线程，默认为5。  
hive.server2.thrift.http.max.worker.threads     – 服务池中的最大工作线程，默认为500。
```


### 2. beeline 客户端连接 HiveServer2

- https://cwiki.apache.org/confluence/display/Hive/HiveServer2+Clients

``` sh
1. tcp 默认方式
beeline -u jdbc:hive2://hostname:10000/default -nhadoop -phadoop

2. http 方式
!connect jdbc:hive2://hostname:10000/default?hive.server2.transport.mode=http;hive.server2.thrift.http.path=cliservice
```


## 三、命令

``` sh
1、重启 hive-server2
    sudo service hive-server2 restart

  脚本启动
    ps -aux | grep hiveserver2
    kill -15 删除进程号
    sudo nohup nice -n 0 /opt/cloudera/parcels/CDH/bin/hive --service hiveserver2 10000 >> /tmp/hiver-server2.log 2>&1 &

2、重启元数据
  sudo service hive-metastore restart


3、hive debug 模式
  hive --hiveconf  hive.root.logger=DEBUG,console

```


## 四、HiveServer2 HA

- 架构思想
  - 启动 HiveServer2 服务的时候, 向 Zookeeper 中注册
  - 客户端 beeline 连接 Zookeeper 地址, 选择 Zookeepr 中存活的 HiveServer2

### 1、在 HiveServer 服务启动的时候, 在 Zookeeper 中注册

``` xml
<property>
  <name>hive.server2.support.dynamic.service.discovery</name>
  <value>true</value>
</property>

<property>
  <name>hive.server2.zookeeper.namespace</name>
  <value>hiveserver2_zk</value>
</property>

<property>
  <name>hive.zookeeper.quorum</name>
  <value>zkNode1:2181,zkNode2:2181,zkNode3:2181</value>
</property>

<property>
  <name>hive.zookeeper.client.port</name>
  <value>2181</value>
</property>

<!-- HiveServer2 启动的节点地址(根据 HA 的部署节点的名称写, 根据部署节点配置) -->
<property>
  <name>hive.server2.thrift.bind.host</name>
  <value>node9</value>
</property>

<!-- 两个 HiveServer2 实例的端口号要一致 -->
<property>
  <name>hive.server2.thrift.port</name>
  <value>10000</value>
</property>
```


## 二、在客户端使用

``` sh
# 命令行方式直接启动
$HIVE_HOME/bin/beeline -u 'jdbc:hive2://zkNode1:2181,zkNode2:2181,zkNode3:2181/default;serviceDiscoveryMode=zooKeeper;zooKeeperNamespace=hiveserver2_zk' -nhadoop -phadoop


# 客户端方式启动
$HIVE_HOME/bin/beeline
> !connect jdbc:hive2://zkNode1:2181,zkNode2:2181,zkNode3:2181/default;serviceDiscoveryMode=zooKeeper;zooKeeperNamespace=hiveserver2_zk hadoop hadoop
```
