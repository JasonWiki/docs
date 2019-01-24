# zookeeper

分布式协调

## 一、简介

文章 ： http://cailin.iteye.com/blog/2014486

### 1、作用

- 同步服务
- 配置维护 配置文件
- 命名服务 Name Service (命名规则)
- 共享锁

### 2、使用场景

#### 2.1、配置管理

集中式的配置管理在应用集群中是非常常见的，一般商业公司内部都会实现一套集中的配置管理中心，应对不同的应用集群对于共享各自配置的需求，并且在配置变更时能够通知到集群中的每一个机器。

#### 2.2、集群管理

应用集群中，我们常常需要让每一个机器知道集群中（或依赖的其他某一个集群）哪些机器是活着的，并且在集群机器因为宕机，网络断链等原因能够不在人工介入的情况下迅速通知到每一个机器。

另外有一个应用场景就是集群选master,一旦master挂掉能够马上能从slave中选出一个master,实现步骤和前者一样，只是机器在启动的时候在APP1SERVERS创建的节点类型变为EPHEMERAL_SEQUENTIAL类型，这样每个节点会自动被编号


## 二、结构


### 1、Leader 领导

领导者负责进行投票的发起和决议，更新系统状态

### 2、Learner 学习者

#### 2.1、Follower 跟随者

Follower 用于接收客户端请求并向客户端返回结果，在选举过程中参与投票

#### 2.2、ObServer 观察者

ObServer 接收客户端连接，将写请求转发给 Leader 节点。但 ObServer 不参与投票过程，只同步 Leader 的状态。

ObServer 的目的是为了扩展系统，提高读取速度

注：
observer 流程和 Follower 的唯一不同的地方就是 observer 不会参加 leader 发起的投票。

### 3、Client 客户端

请求发起方


## 三、ZAB 协议

``` doc
ZAB（ZooKeeperAtomicBroadcast）协议借鉴了Paxos的思想，ZAB在Paxos算法上做了重要改造，和Paxos有着明显的不同，以满足工程上的实际需求。
有序性是Zab协议与Paxos协议的一个核心区别。Zab的有序性主要表现在两个方面：

1）全局有序：如果消息 a 在消息 b 之前被投递，那么在任何一台服务器，消息 a 都会在消息 b 之前被投递。
2）因果有序：如果消息 a 在消息 b 之前发生（a导致了b），并被一起发送，则 a 始终在 b 之前被执行。

Zab协议分为两部分：
广播（boardcast）：Zab协议中，所有的写请求都由leader来处理。正常工作状态下，leader接收请求并通过广播协议来处理。
恢复（recovery）：当服务初次启动，或者leader节点挂了，系统就会进入恢复模式，直到选出了有合法数量follower的新leader，然后新leader负责将整个系统同步到最新状态

1）广播的过程实际上是一个简化的二阶段提交过程：
  1.Client会向一个Follower提交一个写请求
  2.Follower将写请求发送给Leader
  3.Leader接收到写请求后，将消息赋予一个全局唯一zxid，通过zxid的大小比较即可实现因果有序这
  一特性。Leader通过每个follower对应的队列将带有zxid的消息作为一个提案（proposal）广播分发
  给所有follower。消息体类似（zxid-0,data）形式。
  4.当follower接收到proposal，先将proposal写到硬盘，写硬盘成功后再向leader回一个ACK。
  5.当leader接收到超过半数的ACKs后，leader就向所有follower广播发送COMMIT提交命令，
  同时会在本地执行该消息。当follower收到消息的COMMIT命令时，就会执行提交。
  6.（接收Client请求的）Follower将写请求的结果返回给Client。
2）恢复（recovery）
  由于之前讲的Zab协议的广播部分不能处理leader挂掉的情况，Zab协议引入了恢复模式来处理这一
  问题。为了使leader挂了后系统能正常工作，需要解决以下两个问题：
  已经被处理的消息不能丢；被丢弃的消息不能再次出现。
```
