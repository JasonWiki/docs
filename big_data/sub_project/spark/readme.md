# Spark

## 一、Spark 简介


### 1. Spark 是什么

Spark 是基于内存计算的大数据并行计算框架,2009 年诞生与加州大学伯克利分校 AMPLab

### 2. Spark 与 Hadoop

Spark 是一个计算框架, Hadoop 本身包含 HDFS 和 MapReduce

Spark 用于代替 MapReduce

```
(1) 中间结果输出
  MapReduce 会将中间输出结果输出到 HDFS 中

  Spark 将执行模型抽象为通用的 : 有向无环图执行计划 (DAG), 可以将多个任务串联或并行执行，无需将 stage 中间结果输出到 HDFS 中

(2) 数据格式和内存布局
  MapReduce Schema On Read 处理方式会引起较大的处理开销

  Spark 抽象出 (分布式内存存储结构,弹性分布式数据集 RDD),进行数据存储.
    RDD 能支持粗粒度写操作
    RDD 读操作可以精确到每条记录，所以可以做分布式索引

  Spark 的特性是可以控制数据在(不同节点的分区),用户可以自定义分区策略,比如 Hash 分区等

  Shark 和 Spark SQL 在 Spark 的基础上实现了 (列存储和列存储压缩)

(3) 执行策略
  MapReduce 在数据 Shuffle 之间花费了大量时间来排序

  Spark 任务在 Shuffle 不是所有场景都需要排序，所以支持基于 Hash 的分布式聚合，调度采用 (有向无环图执行计划图 DAG),每一轮的输出结果在内存中缓存

(4) 任务开销
  MapReduce 是为了运行数小时的批量作业而设计的，所以延迟很高

  Spark 采用了事件驱动的类库 AKKA 来启动任务，通过 (线程池 复用线程,来避免进程或线程启动,和切换开销)
```


## 二、Spark 生态系统 BDAS

伯克利将 Spark 的生态系统成为伯克利数据分析栈 (BDAS),核心框架是 Spark

- [生态图](https://www.processon.com/view/link/565911e9e4b058c28d6a02a8)

- Spark Core: Spark 框架
- Spark SQL : 结构化数据查询与分析引擎 Spark SQL 和 Shark
- Spark Streaming : 流计算框架
- Spark MLbase : 提供机器学习以及底层的分布式机器学习库 MLib
- Spark GraphX[grɑːf] : 并行图计算框架
- Spark BlinkDb : 近似计算查询引擎
- Spark Tachyon[tækɪɒn] : 内存分布式系统
- Spark Mesos : 资源管理框架



## 三、流程图

- http://www.ibm.com/developerworks/cn/opensource/os-cn-spark-deploy1/index.html

![image](http://www.ibm.com/developerworks/cn/opensource/os-cn-spark-deploy1/img002.jpg)
