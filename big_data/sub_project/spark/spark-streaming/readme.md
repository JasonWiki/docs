# Spark Streaming

![streaming-arch](../../imgs/streaming-arch.png)

- [Spark Streaming - 体系](http://m.blog.csdn.net/article/details?id=37560609)
- [Spark Streaming 性能调优详解](http://www.iteblog.com/archives/1333)
- [Spark on yarn 上遇到的问题](http://www.iteblog.com/archives/1393)
- [Spark Python API 函数学习](http://www.iteblog.com/archives/1395)
- [Apache Spark 快速入门：基本概念和例子(1)](http://www.iteblog.com/archives/1408)
- [Spark SQL 读取 Hive 上的数据](http://www.iteblog.com/archives/1491)


## 一、Spark Streaming (允许用户使用一套和批处理非常接近的 API 来编写流式计算应用)

- [Spark - Streaming DStream 架构](https://www.processon.com/view/link/56c6d2fce4b0362f22cbe422)
- Spark Streaming 使用离散化流 (discretized stream) 作为抽象表示,叫做 DStream
- DStream 是一个持续的 RDD 序列
- DStream 是随时间推移而受到的数据的序列,在内部每个时间区间收到的数据都作为 RDD 存在
- DStream 可以通过各种输入源创建,比如(Flume,Kafka,HDFS 等)

### 1. Spark Streaming 程序例子

``` java
package com.angejia.dw.spark

import org.apache.spark.SparkConf
import org.apache.spark.streaming.StreamingContext
import org.apache.spark.streaming.StreamingContext._
import org.apache.spark.streaming.dstream.DStream
import org.apache.spark.streaming.Duration
import org.apache.spark.streaming.Seconds;


object SparkStreamingTest {

  def main(args: Array[String]) {

    this.testStreaming()
  }


  def testStreaming() : Unit = {
    val conf = new SparkConf().setAppName("TestStreaming")

    // 从 SparkConf 创建 StreamingContext 并指定 1 秒钟的批处理大小
    val scc = new StreamingContext(conf,Seconds(2))

    // 连接到本地机器 7777 端口上后，使用收到的数据创建 DStream
    val lines = scc.socketTextStream("127.0.0.1", 7777)

    // 从 DStream 中筛选出包含字符串的 "error" 的行
    val errorLines = lines.filter { _.contains("error")}

    // 打印出 "error"
    errorLines.print()

    // 启动流计算环境 StreamingContext 并等待它"完成"
    scc.start()

    // 等待作业完成
    scc.awaitTermination()
  }
}

// 提交 spark 到集群
spark-submit --name TestStreaming --class com.angejia.dw.spark.SparkStreamingTest --master local[2] ./spark-test.jar


```


## 二. DStream 操作

- transformation 转化操作,会生成一个新的 transformation
- output operation 输出操作,可以把数据写入外部系统中,比如(HDFS)

### 1. transformation 转化操作

#### 1.1 transformation - 无状态转化操作

- 无状态转化操作 : 分别应用到每个 RDD 上的

``` sh
map()

flatMap()

filter()

repartition()

reduceByKey()

groupByKey()

join()
  连接 2 个 DStream

```

#### 1.2 transformation - 有状态转化操作

- 有状态转化操作 : 跨时间区间跟踪数据的操作
- 当需要先前批次的数据,也用来在新的批次中计算结果
- 主要类型
 - 滑动窗口 : 以一个时间段为滑动窗口进行操作
 - updateStateByKey() :  跟踪每个键的状态变化(例如构建一个代表用户会话的对象)

##### 1.2.1 transformation - 有状态转化操作 - 窗口操作

- 参数 :
  - 窗口时长 : 控制每次计算最近多少个批次的数据
  - 滑动步长 : 控制对新的 DStream 进行计算的间隔

``` java
// SparkStreaming 上下文
val conf = new SparkConf().setAppName("testDStreamWindows")
val scc = new StreamingContext(conf,Seconds(2))

// 有状态操作，需要在 StreamingContext 中打开检查点机制来确保容错性
scc.checkpoint("hdfs://path/dir")

// 监听
val linesLog = scc.fileStream("/")

// 窗口
val windowLog = linesLog.window(Seconds(30),Seconds(10))
windowLog.count()

```

##### 1.2.2 transformation - 有状态转化操作 - updateStateByKey()

- 应用案例
 - DStream 中跨批次维护状态(例如跟踪用户访问网站的状态)
- 用于键值对形式的 DStream
- 给定一个有(键,事件)对构成的 DStream ,并传递一个指定如何根据新的事件，更新每个键对应状态的函数
- updateStateByKey() 的结果是一个新的 DStream,其内部的 RDD 序列是由每个事件区间对应的 (键,状态) 对组成的

``` java


```

### 2. output operation 输出操作

``` java

val conf = new SparkConf().setAppName("testDStreamOutputOperation")
val scc = new StreamingContext(conf,Seconds(2))
val linesLog = scc.fileStream("/")

// 保存为文件例子
linesLog.saveAsTextFiles("/path/xxx", "txt")


// 循环 RDD 输出、保存例子
// 循环 RDD
linesLog.foreachRDD(rdd => {
  // 循环 RDD 分区
  rdd.foreachPartition( partition => {
    // 循环 RDD 分区内容 (打开连接到存储系统的连接,比如数据库连接)
    partition.foreach( item => {
      // 把 item 写到系统中
    })
    // 关闭连接
  })
})
```


## 二、Spark Streaming 输入源

### 1. 核心数据源

- 本地文件
- HDFS
- Kafka
- Flume
- Akka actor

### 2. 本地文件

``` java
// 监听目录
val logData = scc.textFileStream("dir")
```

### 3. HDFS

``` java

```

### 3. Kafka

``` java

```

### 4. Flume

``` java

```


## 三、Spark Streaming 性能优化

- [Spark 配置文档](http://spark.apache.org/docs/latest/configuration.html#application-properties)

``` sh
1. 批次和窗口大小
  500 毫秒为比较好的最小批次大小,通过不断处理减少这个参数,根据 Spark 界面去查看处理时间是否增加

2. 并行度
  1) 增加接收器数目
    通过创建多个 DStream(这样会创建多个接收器)

  2) 将受到的数据显示地重新分区
    重新分配分区,或者合并多个流得到的数据流

  3) 提高聚合计算的并行度

```
