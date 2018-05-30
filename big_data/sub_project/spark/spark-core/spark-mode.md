# Spark-mode 运行模式

## 一、介绍

### 1. spark 工作角色

```
Client :
  客户端进程，负责提交作业到 Master。

Master :
  (1) 负责接收 Client 提交的作业
  (2) 管理 Worker
  (3) 命令 Worker 启动、分配 Driver 的资源和启动 Executor 的资源。

Worker :
  (1) slave 节点上的守护进程，负责管理本节点的资源
  (2) 定期向 Master 汇报心跳，接收 Master 的命令
  (3) 启动 Driver 和 Executor。

Driver :
  (1) 运行 Driver 进程,也是作业的主进程,
  (2) 负责作业的解析、生成 Stage 并调度 Task 到 Executor 上。包括 DAGScheduler,TaskScheduler。

Executor :
  (1) 真正执行作业的地方，一个集群一般包含多个 Executor，
  (2) 每个 Executor 接收 Driver 的命令 Launch Task，一个 Executor 可以执行一到多个 Task。

DAGScheduler :
  (1) 将 Spark 作业分解一个、多个 Stage
  (2) 每个 Stage 根据 RDD 的 partition 个数决定 Task 个数
  (3) 将生成的相应 Task set 放到 TaskScheduler 中

TaskScheduler :
  (1) 实现 Task 分配到 Executor 上执行

STAGE :
  (1) 一个 Spark 作业包含一个、多个 Stage

Task :
  (1) 一个 Stage 包含一个、多个 Task,通过 Task 实现并行运行的功能

```

## 二、运行模式以及环境


### 1、standalone 独立模式

- 自带完整的服务(Master 和 Slave)，可单独部署到一个集群中，无需依赖任何其他资源管理系统
- Spark://hostname:port 作为 --master 请求的节点格式, Standalone 需要部署 Spark 到相关节点，URL 为 Spark Master 主机地址和端口。

```
1) standalone 独立模式
  (1) 从一定程度上说，该模式是其他两种的基础。
  (2) 借鉴 Spark 开发模式，我们可以得到一种开发新型计算框架的一般思路：
    先设计出它的 standalone 模式，为了快速开发，起初不需要考虑服务（比如master/slave）的容错性，之后再开发相应的 wrapper，将 stanlone 模式下的服务原封不动的部署到资源管理系统 yarn 或者 mesos 上，由资源管理系统负责服务本身的容错。
  (3) 目前 Spark 在 standalone 模式下是没有任何单点故障问题的，这是借助 zookeeper 实现的，思想类似于 Hbase master 单点故障解决方案。将 Spark standalone 与 MapReduce比 较，会发现它们两个在架构上是完全一致的：

1) 结构对比
  (1) 都是由 master/slaves 服务组成的，且起初 master 均存在单点故障，后来均通过 zookeeper 解决（Apache MRv1 的 JobTracker 仍存在单点问题，但 CDH 版本得到了解决）。
```

### 2、Spark On Mesos 模式

- Mesos：一个开源的分布式弹性资源管理系统
 - http://dongxicheng.org/category/apache-mesos/
- Mesos://hostname:port   Mesos 需要部署 Spark 和 Mesos 到相关节点，URL 为 Mesos 主机地址和端口

```
这是很多公司采用的模式，官方推荐这种模式（当然，原因之一是血缘关系）。
正是由于Spark开发之初就考虑到支持Mesos，因此，目前而言，Spark运行在Mesos上会比运行在YARN上更加灵活，更加自然。
目前在Spark On Mesos环境中，用户可选择两种调度模式之一运行自己的应用程序（可参考Andrew Xia的“Mesos Scheduling Mode on Spark”）：
```

#### 2.1 粗粒度模式（Coarse-grained Mode）

- 固定分配资源

```
每个应用程序的运行环境由一个 Driver 和若干个 Executor['eksikju:tə] 组成
其中，每个 Executor 占用若干资源，内部可运行多个Task（对应多少个“slot”）。
应用程序的各个任务正式运行之前，需要将运行环境中的资源全部申请好，且运行过程中要一直占用这些资源，即使不用，最后程序运行结束后，回收这些资源。举个例子，比如你提交应用程序时，指定使用5个executor运行你的应用程序，每个executor占用5GB内存和5个CPU，每个executor内部设置了5个slot，则Mesos需要先为executor分配资源并启动它们，之后开始调度任务。另外，在程序运行过程中，mesos的master和slave并不知道executor内部各个task的运行情况，executor直接将任务状态通过内部的通信机制汇报给Driver，从一定程度上可以认为，每个应用程序利用mesos搭建了一个虚拟集群自己使用。
```

#### 2.2 细粒度模式（Fine-grained Mode）

- 弹性分配资源

```
鉴于粗粒度模式会造成大量资源浪费，Spark On Mesos还提供了另外一种调度模式：细粒度模式，这种模式类似于现在的云计算，思想是按需分配。
与粗粒度模式一样，应用程序启动时，先会启动 executor，但每个executor占用资源仅仅是自己运行所需的资源，不需要考虑将来要运行的任务，之后，mesos会为每个executor动态分配资源，每分配一些，便可以运行一个新任务，单个Task运行完之后可以马上释放对应的资源。每个Task会汇报状态给Mesos slave和Mesos Master，便于更加细粒度管理和容错，这种调度模式类似于MapReduce调度模式，每个Task完全独立，优点是便于资源控制和隔离，但缺点也很明显，短作业运行延迟大。
```


### 3、Spark On YARN 模式 (社区活跃，与 hadoop 开源生态圈融合性高)

- 运行在 Yarn 资源管理框架上的

```
这是一种最有前景的部署模式。但限于YARN自身的发展，目前仅支持粗粒度模式（Coarse-grained Mode）。
这是由于YARN上的Container资源是不可以动态伸缩的，一旦Container启动之后，可使用的资源不能再发生变化，不过这个已经在YARN计划（具体参考：https://issues.apache.org/jira/browse/YARN-1197）中了
```

#### 3.1 Yarn cluster / Yarn-standalone 集群模式

- driver 和 executors 都运行在 yarn 集群上
- Yarn-Standalone 需要由外部程序辅助启动 APP。用户的应用程序通过 org.apache.spark.deploy.yarn.Client 启动

```
1)  Yarn cluster / Yarn-standalone 集群模式下
  (1) Client 通过 (Yarn Client API) 在 Hadoop 集群上启动一个 Spark ApplicationMaster
  (2) Spark ApplicationMaster 首先注册自己为一个 YarnApplication Master
  (3) 之后启动用户程序，SparkContext 在用户程序中初始化时，使用 CoarseGrainedSchedulerBackend 配合 YarnClusterScheduler,YarnClusterScheduler 只是对 TaskSchedulerImpl 的一个简单包装，增加对 Executor 的等待逻辑等。
```

#### 3.2 YARN client 客户端模式

- SparkContext 运行在本地，该模式适用于应用 APP 本身需要在本地进行交互的场合，比如 Spark Shell，Shark 等
- driver 运行在提交任务的 client 上，executors 运行在 yarn 上
- 其资源分配是交给 Yarn 的 ResourceManager 来进行管理的

```
1) Yarn-client 模式下，
  (1) 会在集群外面启动一个 ExecutorLauncher 来作为 driver，
  (2) 向 ResourceManager 申请  Container[kən'teɪnə]容器，来启动 CoarseGrainedExecutorBackend
  (3) 并向 CoarseGrainedSchedulerBackend 中的 DriverActor 进行注册。

2) Yarn-client模式下
  (1) SparkContext 在初始化过程中启动 YarnClientSchedulerBackend（同样拓展自CoarseGrainedSchedulerBackend）
  (2) 该 Backend 进一步调用 org.apache.spark.deploy.yarn.Client 在远程启动一个 WorkerLauncher 作为 Spark 的 Application Master，相比 Yarn-standalone 模式，WorkerLauncher 不再负责用户程序的启动（已经在客户端本地启动），而只是启动 Container 运行 CoarseGrainedExecutorBackend 与客户端本地的 Driver 进行通讯，后续任务调度流程相同
```

### 4、Local[N]：本地模式

- 使用 N 个线程。


### 5、Local Cluster[Worker,core,Memory]

- 伪分布式模式，可以配置所需要启动的虚拟工作节点的数量，以及每个工作节点所管理的 CPU 数量和内存尺寸
