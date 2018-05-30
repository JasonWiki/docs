# Yarn (MapReduce2)

## 一、介绍

- 通用资源管理系统
- Yet Another Resource Negotiator
- 提供了一种 RM 的概念，AM 协调来自 RM 的任务和管理，NM 运行和执行任务，反馈给 AM。


## 二、架构

- [官方文档](http://hadoop.apache.org/docs/current/hadoop-yarn/hadoop-yarn-site/)

### 1、yarn 组成角色

- [构架图](http://hadoop.apache.org/docs/current/hadoop-yarn/hadoop-yarn-site/YARN.html)

- ResourceManager

 ```
 1.是一个中心的服务，它做的事情是调度、启动每一个 Job 所属的 ApplicationMaster、另外监控 ApplicationMaster 的存在情况

 2.接收 JobSubmitter 提交的作业，按照作业的上下文 (Context) 信息，以及从 NodeManager 收集来的状态信息，启动调度过程，分配一个 Container（容器） 作为 ApplicationMaster

 3.它就是一个纯粹的调度器，它在执行过程中不对应用进行监控和状态跟踪.

 4.ResourceManager 还与 ApplicationMaster 一起分配资源，与 NodeManager 一起启动和监视它们的基础应用程序。

 ```

- ApplicationMaster

 ```
 1.ApplicationMaster 负责管理作业的执行
 ApplicationMaster 负责协调来自 ResourceManager 的任务，并通过 NodeManager 监视容器的执行和资源使用（CPU、内存等的资源分配）

 2.从 YARN 角度讲，ApplicationMaster 是用户代码，因此存在潜在的安全问题
 ```

- NodeManager

 ```
 负责 Container 状态的维护，并向 RM(ResourceManager) 保持心跳
 是每一台机器框架的代理，是执行应用程序的容器
 监控应用程序的资源使用情况 (CPU，内存，硬盘，网络 ) 并且向调度器汇报。
 ```


### 2、MapReduce1 和 Yarn 对比

```
1.老的框架中，JobTracker 一个很大的负担就是监控 job 下的 tasks 的运行状况，现在，这个部分就扔给 ApplicationMaster 做了．
而 ResourceManager 中有一个模块叫做 ApplicationsMasters( 注意不是 ApplicationMaster)，它是监测 ApplicationMaster 的运行状况，如果出问题，会将其在其他机器上重启。

2.Container 是 Yarn 为了将来作资源隔离而提出的一个框架。
这一点应该借鉴了 Mesos 的工作，目前是一个框架，仅仅提供 java 虚拟机内存的隔离 ,hadoop 团队的设计思路应该后续能支持更多的资源调度和控制 , 既然资源表示成内存量，那就没有了之前的 map slot/reduce slot 分开造成集群资源闲置的尴尬情况。
```

### 3、工作流程

- [构架图以及文章](http://www.csdn.net/article/2013-12-18/2817842-bd-hadoopyarn)

```

Client(请求资源) -> ResourceManager(协调分配资源) -> ApplicationMaster(监视容器的执行和资源使用) -> NodeManager(Container 容器，执行)

```
