# spark - frame 构架

## 一、构架

- Spark 采用 Master-Slave 模型

  ```
  Master : 对应集群中含有 (Master 进程) 的节点

  Slave : 对应集群中含有 (Worker 进程) 的节点
  ```
- [Spark - 构架 Base](https://www.processon.com/view/link/5659368ae4b07750c3f694b9)

### 1. 执行角色 Driver 和 Worker(多个)

```
Driver : 程序是应用逻辑的执行起点，负责主页的调度(即 Task 任务的分发)
  执行阶段 : Driver 会将 Task 和 Task 所依赖的 file 和 jar 序列化传递给对应的 Worker 机器, Executor 会对相应的数据分区处理

Worker : 多个 Worker 来管理计算节点的创建 Executor 并行处理任务
```


### 2. Spark 构架基础组件

```
- ClusterManager (Master 管理节点)
  (1) 控制整个集群 监控 Worker 进程
  (2) 不同模式下
    在 Standalone 模式中即为 Master 进程 (主节点)
    在 Yarn 模式中为资源管理器 (Resource Manager)

- Worker (Slave 工作节点)
  (1) 从节点，负责控制计算节点
    启动 Executor 或 Driver
  (2) 不同模式下
    在 Standalone 模式中即为 Worker 进程
    在 Yarn 中 NodeManager

- Driver 执行角色
  (1) 负责运行 Application 的 main() 函数，并且创建 SparkContext 上下文
  (2) 每个 Application 拥有独立的一组 Executors

- Executor[ɪg'zekjʊtə;] 执行器
  (1) 在 worker|NodeManager node 上执行任务的组件
    启动线程池运行任务

- SparkContext 上下文
  整个用上下文，控制整个应用的生命周期

- RDD 基本计算单元
  Spark 基本计算单元，一组 RDD 可形成执行的有向无环图 RDD Graph

- DAG Shedueler 构建 Stage 过程的 DAG
  根据作业(Job) 构建基于 Stage 过程的 DAG ,并且 stage 给 TaskScheduler

- TaskScheduler
  将任务(Task) 分发给 Executor 执行

- SparnEnv 线程级别上线文,存储重要组件的引用

- MapOutPutTasker 负责 Shuffle 元信息的存储

- BroadcastManager 负责广播变量的控制与元信息的存储

- BlockManager 负责存储管理、创建和查找块

- MetricsSystem 监控运行时性能指标信息

- SparkConf 负责存储配置

```



### 3. 运行过程

```
(1) Client 提交应用给 Master
(2) Master 节点找到一个 Worker 节点启动 Driver
(3) Driver 向 Master 节点申请资源，之后转化为 RDD Graph
(4) DAG Shedueler 将 RDD Graph 转换为 Stage 的有向无环图，提交给 TaskScheduler
(5) TaskScheduler 提交任务 Executor 执行
```

## 二、 Spark On Yarn

- [Spark On Yarn](https://www.processon.com/view/link/565931b6e4b07750c3f68579)

### 1. 运行过程

```
基于 Yarn 的 Spark 作业首先在客户端生成作业信息
(1) SparkClient 生成作业信息, 提交给 ResourceManager
(2) ResourceManager 在某一 NodeManager 汇报时,把 AppMaster 分配给 NodeManager
(3) NodeManager 启动 SparkAppMaster
  SparkAppMaster 启动后初始化作业，向 ResourceManager 申请资源
(4) SparkAppMaster 申请到相应资源后
  SparkAppMaster 通过 PRC 让 NodeManager 启动相应的 SparkExecutor
(5) SparkExecutor 向 SparkAppMaster 汇报并完成相应的任务

PS : SparkClient 可以通过 AppMaster 获取作业运行状态
```
