# rocketmq deploy

## 简介

``` doc
1. 名称服集群务 NameServer
  Nameserver 提供了轻量级的服务发现和路由。每个 NameServer 服务记录完整的路由信息, 提供一致的读写服务，支持快速存储扩展.
  主要包含两个功能:
    1) broker 管理, NameServer 接受来自 Broker 集群的注册信息并提供心跳来检测他们是否可用。
    2) 路由管理 每一个 NameServer 都持有关于 Broker 集群和队列的全部路由信息，用来向客户端提供查询。

2. 代理服务集群 Broker Cluster
  Broker 通过提供轻量级主题和队列机制来处理消息存储。它们支持 Push 和 Pull 模型，包含容错机制(2个副本或3个副本)，提供了极强的峰值处理里能力和按照时间顺序存储数以百万记的消息存储能力，此外，代理提供了灾难恢复、丰富的度量统计和警报机制.
  Broker Server 负责消息的存储传递，消息查询，保证高可用等:
    1) remoting（远程） 模块，broker 的入口，处理从客户端发起的请求。
    2) client manager（客户端管理） 管理各个客户端（生产者/消费者）还有维护消费者主题订阅。
    3) store（存储服务），提供简单的 api 来在磁盘保持或者查询消息。
    4) HA 高可用服务 提供主从 broker 的数据同步。
    5) index(索引服务)为消息建立索引提供消息快速查询。

3. 生产者集群 Producer Cluster
  Producer 支持分布式部署，分布式的 Producer 通过 Broker 集群提供的各种负载均衡策略将消息发送到 Broker 集群中。发送过程支持快速失败是低延迟的.
  Producer 生产消息 Topic
    1. 先请求 Nameserver 拿到这个 Topic 的路由信息
    2. 查找 Topic 在哪个 Broker, Broker 上有哪些队列
    3. 拿到这些请求后再把消息发送到指定的 Broker 中

4. 消费者集群 Consumer Cluster
  消费者也支持在推送和者拉取模式下分布式部署，它还支持集群消费和消息广播。提供实时的消息订阅机制，能够满足大多数消费者的需求。RocketMQ的网站为感兴趣的用户提供了一个简单的快速入门指南.
  Consumer 消费消息 Topic, 也会经历 3(Producer 生产消息 Topic) 这个流程
```

## 下载

- [Github](https://github.com/apache/rocketmq)
- [下载地址](http://rocketmq.apache.org/release_notes/)

``` sh


```


## 安装

``` sh
mqbroker -c ../conf/2m-2s-sync/broker-a.properties -n 192.168.0.2:9876,192.168.0.3:9876
mqbroker -c ../conf/2m-2s-sync/broker-a-s.properties -n 192.168.0.2:9876,192.168.0.3:9876

mqbroker -c ../conf/2m-2s-sync/broker-b.properties -n 192.168.0.2:9876,192.168.0.3:9876
mqbroker -c ../conf/2m-2s-sync/broker-b-s.properties -n 192.168.0.2:9876,192.168.0.3:9876

mqadmin clusterlist
```

## 配置

``` sh
broker 角色有
  ASYNC_MASTER（异步主） + SLAVE（从）: 允许异常情况下的消息丢失
  SYNC_MASTER（同步主）+ SLAVE（从）: 保证异常情况下不丢数据

123

```



``` sh
### ---------- 2 master, 2 slave, 同步模式 -----------

# broker-a.properties master-a
# 这个经纪人属于哪个集群
brokerClusterName=DefaultCluster
# broker 姓名
brokerName=broker-a
# broker ID，0 表示 master，正整数表示 slave
brokerId=0
# 何时删除超出保留时间的提交日志
deleteWhen=04
# 在删除之前保留 commitlog 的小时数
fileReservedTime=48
# SYNC_MASTER(同步主) / ASYNC_MASTER(异步主) / SLAVE(SLAVE)
brokerRole=SYNC_MASTER
# SYNC_FLUSH 同步模式会在响应每次生产者前写入磁盘, ASYNC_FLUSH 异步模式会提高处理生产者组的提交处理能力
flushDiskType=ASYNC_FLUSH


# broker-a-s.properties slave-a
brokerClusterName=DefaultCluster
brokerName=broker-a
brokerId=1
deleteWhen=04
fileReservedTime=48
brokerRole=SLAVE
flushDiskType=ASYNC_FLUSH


# broker-b.properties master-b
brokerClusterName=DefaultCluster
brokerName=broker-b
brokerId=0
deleteWhen=04
fileReservedTime=48
brokerRole=SYNC_MASTER
flushDiskType=ASYNC_FLUSH

# broker-b-s.properties slave-b
brokerClusterName=DefaultCluster
brokerName=broker-b
brokerId=1
deleteWhen=04
fileReservedTime=48
brokerRole=SLAVE
flushDiskType=ASYNC_FLUSH



### ---------- 2 master, 2 slave, 异步模式 -----------

# broker-a.properties master-a
brokerClusterName=DefaultCluster
brokerName=broker-a
brokerId=0
deleteWhen=04
fileReservedTime=48
brokerRole=ASYNC_MASTER
flushDiskType=ASYNC_FLUSH


# broker-a-s.properties slave-a
brokerClusterName=DefaultCluster
brokerName=broker-a
brokerId=1
deleteWhen=04
fileReservedTime=48
brokerRole=SLAVE
flushDiskType=ASYNC_FLUSH


# broker-b.properties master-b
brokerClusterName=DefaultCluster
brokerName=broker-b
brokerId=0
deleteWhen=04
fileReservedTime=48
brokerRole=ASYNC_MASTER
flushDiskType=ASYNC_FLUSH


# broker-b-s.properties slave-b
brokerClusterName=DefaultCluster
brokerName=broker-b
brokerId=1
deleteWhen=04
fileReservedTime=48
brokerRole=SLAVE
flushDiskType=ASYNC_FLUSH



### ---------- GC 优化参数 ----------

-server -Xms8g -Xmx8g -Xmn4g
-XX:+UseG1GC -XX:G1HeapRegionSize=16m -XX:G1ReservePercent=25
-XX:InitiatingHeapOccupancyPercent=30 -XX:SoftRefLRUPolicyMSPerMB=0
-XX:SurvivorRatio=8 -XX:+DisableExplicitGC
-verbose:gc -Xloggc:/dev/shm/mq_gc_%p.log -XX:+PrintGCDetails
-XX:+PrintGCDateStamps -XX:+PrintGCApplicationStoppedTime
-XX:+PrintAdaptiveSizePolicy
-XX:+UseGCLogFileRotation -XX:NumberOfGCLogFiles=5 -XX:GCLogFileSize=30m
```

## 启动
