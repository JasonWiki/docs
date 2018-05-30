# Spark Program 编程

- spark 是一个基于内存计算的开源的集群计算系统


## 一、介绍

- Spark 扩展了 MapReduce 计算模型


### 1. Spark 驱动器程序

- 每个 Spark 应用都由一个驱动器程序(driver program)来发起集群上的各种并行操作
- spark-shell 就是驱动程序
- 驱动程序是通过 SparkContext 对象来访问 Spark，这个对象代表计算机群的一个连接


## 二、RDD 编程

- Spark 对数据的操作就是
- 创建 RDD
- 转换 RDD
- 调用 RDD 求值

### 1. 创建 RDD

- 从外部数据创建 RDD
- 使用类似 filter() 这样的转化操作对 RDD 转化，生成新的 RDD

``` sh
ps: 写法
  import org.apache.spark.SparkContext
  import org.apache.spark.SparkConf

  val conf = new SparkConf()
      conf.setAppName("AppName")
      conf.setMaster("local[2]")

  val sc = new SparkContext(conf)

1. 通过集合创建 RDD
  val list = List("pands","i like pands")
  val lines = sc.parallelize(list)

2. 通过外部数据创建 RDD
   val lines2 = sc.textFile("/usr/local/spark/README.md",2)
```


### 2. RDD 操作类型

- Transformations (转换操作)
- actions (行动操作)

#### 2.1 Transformations 转换操作返回的是一个 RDD

- [Transformations 文档](http://spark.apache.org/docs/latest/programming-guide.html#transformations)
- map,filter, flatMap,union 等多种操作类型
- 针对各个元素的转化操作

``` java
map()
  接收一个函数,把这个函数用于 RDD 的每个元素,将函数的返回结果作为结果 RDD 中的对应元素的值
  例如 :
    rdd.map(x => {
      x + 1
    })

    val input = sc.parallelize(List(1,2,3,4))
    val result = input.map(x => x * x)
    println(result.collect().mkString(","))
    resutl : 1,4,9,16

filter()
  接收一个函数,将 RDD 中满足该函数的元素放入到一个新的 RDD 中返回
  例如 :
    rdd.filter(x => {
      x != 1
    })

    val inputRDD = sc.parallelize(List("error","error","warning","warning"))
    val errorRDD = inputRDD.filter { line => line.contains("error") }
    println(errorRDD.collect().mkString(","))
    result : error,error

flatMap()
  每个输入元素生成多个输出元素，返回一个序列的迭代器
  例如 :
    rdd.filter(x => {
      x.to(3)
    })

    val lines = sc.parallelize(List("Hello World","hi"))
    // 把每个元素的值，按照空格分割
    val words = lines.flatMap { line => line.split(" ") }
    println(words.collect().mkString(","))

groupBy()  按照指定键排序
  rdd.groupBy{f =>
    f._1;
  }

distinct()
  对 RDD 去重,RDD 中的数据类型必须相同
  例如 : rdd.distinct()

cartesian()
  计算 2 个 RDD 中的笛卡尔及
  例如 : rdd.cartesian(otherRDD)

union()
  组合 2 个以上 RDD
  例如 : rdd.union(otherRDD)

persist() cache()
  持久化 RDD
  例如 : rdd.persist

unpersist()
  解除持久化 RDD
  例如 : rdd.unpersist()

```

#### 2.2 actions 行动操作是把结果写入外部系统的操作，会触发实际的计算,返回其他数据类型

- [actions 文档](http://spark.apache.org/docs/latest/programming-guide.html#actions)

``` java
count()
  返回 RDD 元素的个数
  例如 : rdd.count()

collect()
  返回 RDD 中的所有元素
  例如 : rdd.collect()

take()
  从 RDD 中返回 num 个元素
  例如 : rdd.take(10)

top()
  从 RDD 中返回最前面的 num 个元素
  例如 : rdd.top(10)

first()
  返回数据集的第一个元素(take(1))
  例如 : rdd.first()

reduce()
  并行这个 RDD 中所有数据,接收一个函数作为参数
  例如 :
  rdd.reduce((x,y) => {
    x + y
  })

  val rdd = sc.parallelize(List(1,2,3,4))
  // 求和操作 1 + 2 + 3 + 4
  val reduce = rdd.reduce((x , y) => x + y)
  println(reduce)


// 打印 RDD 的值
ratingsRDD.take(3).foreach {  x =>
            println(x(0))
            println(x(1))
            println(x(2))
         }
```


## 三、 键值对 pair RDD 操作

- 键值对 RDD 通常用来做聚合计算

### 1. 创建 pair RDD

``` java
使用 map() 函数创建
  // RDD
  val lines = sc.parallelize(List("Hello World","Hi shanghai"))
  // 从一个 RDD 创建 pair RDD
  val pairs = lines.map(x => {
     // 返回一个 key => value 对
     (x.split(" ")(0),x)
  })
  println(pairs.foreach(println))

```

### 2. 转化 pair RDD 操作

``` java
reduceByKey(func)
  合并具有相同键的值 (必须是 pair RDD)
  例如 :
  rdd.reduceByKey((x,y) => {
    x + y
  })

groupByKey()
  对具有相同键的值进行分组
  例如 :
  rdd.groupByKey()

mapValues(func)
  操作 pair RDD 中的每个值
  例如:
  rdd.mapValues(x => x + 1)

flatMapValues(func)
  对 pair RDD 中的每个值,应用一个返回迭代器的函数,然后返回对应原键值对的记录
  例如:
  rdd.flatMapValues(x => (x to 5))

keys()
  返回一个包含键的 RDD
  例如 :
  rdd.keys
  println(pairs.keys.foreach(println))

values()
  返回一个包含值的 RDD
  例如 :
  rdd.values
  println(pairs.values.foreach(println))

```

#### 2.1 两个 pair RDD 的转化操作

- rdd = {(1,2) , (3,4) , (3,6)}
- other = {(3,9)}

``` java
subtractByKey()
  删掉 RDD 中键与 other RDD 中的键相同的元素
  例如 :
  rdd.subtractByKey(other)

```

#### 2.2 pair RDD 聚合操作 (也是转化操作)

``` java
reduceByKey()
  会为数据集中的每个键进行并行的归约操作,每个归约操作会将键相同的值合并起来

combineByKey()
  基于键进行聚合的函数,有多个参数分别对聚合操作的各个阶段

```

#### 2.3 pair RDD 数据分组

``` java
groupByKey()
  使用 RDD 中的键对数据进行分组

cogroup()
  将两个 RDD 中拥有相同键的数据分组到一起
```

#### 2.4 pair RDD 连接

``` java
join()
  对两个 RDD 进行内连接
  例如 :
  rdd.join(other)
    return {(3,(4,9)),(3,(6,9))}

rightOuterJoin()
  对两个 RDD 进行连接操作

leftOuterJoin()
  对两个 RDD 进行连接操作
```

#### 2.5 pair RDD 排序

``` java
sortByKey()
  根据一个 key 排序
```


### 3. Pair RDD 行动操作

``` java
countByValue()
  RDD 中个元素出现的次数
  例如 : rdd.countByValue()
  val rdd = sc.parallelize(List(1,2,3,4))
  val result = rdd.countByValue()
  println(result)

collectAsMap()
  将结果以映射表的形式返回,以便查询
  例如 :
  rdd.collectAsMap()

lookup(key)
  返回给定键对应的所有值
  例如 :
  rdd.lookup(3)
```


### 4. 数据分区

- 其中一个父 RDD 已经设置过分区方式,那么结果 RDD 就会采用那种分区方式
- 如果两个父 RDD 都设置了过分区方式,那么记过 RDD 会采用第一个父 RDD 的分区方式
- 无需改变元素时,尽量使用 mapValues() 和 flatMapValues()
 - 可以保证每个二元祖的 key 保持不变

``` java
partitionBy()
  1) 是个转化操作,会返回一个新的 RDD,但是不会改变原来的 RDD
    需要跟 persist() 配合使用,不然后续的 RDD 操作会 partitioned 的整个谱系重新求值,这会导致对 rdd 的一遍又一遍的进行哈希分区

  2) 分区方式
    rdd.partitionBy(new spark.HashPartitioner(3)) // Hash 分区
    // 自定义分区,待补充

  3) 案例:
  import org.apache.spark;
  val data = List((1,1),(2,2),(3,3))
  val pairs = sc.parallelize(data)
  // 为 None 的对象,表示没有分区
  pairs.partitioner // res49: Option[org.apache.spark.Partitioner] = None
  // 设置 Hash 分区
  val partitioned = pairs.partitionBy(new spark.HashPartitioner(3)).persist()
  // 查看分区,表示 3 个分区
  partitioned.partitioner // res52: Option[org.apache.spark.Partitioner] = Some(org.apache.spark.HashPartitioner@3)

```


## 四、数据读取与保存


### 1. 文件格式

``` java

```

### 2. 读取数据库数据

``` java

```


## 五、 Spark 共享变量

- 累加器: 为信息结果聚合
- 广播变量: 分发大对象,比如一张大表

### 1. 累加器

``` java
val file = sc.textFile("file.txt")

//初始化累加器, 默认值为 0
val blankLines = sc.accumulator(0)
val callSigns = file.flatMap(line => {
  if (line == "") {
    // 累加器 + 1
    blankLines += 1
  }
  line.split(" ")
})

```

### 2. 广播变量

- 让程序高效向所有节点发送一个较大的只读值,以供一个或多个 Spark 操作使用
- 只读值 : 广播变量做为只读值,修改不会影响到别的节点

``` java
// 定义广播变量
val signPrefixes = sc.broadcast("data")
// 不可赋值使用,因为只对该工作节点本地的这个数组的副本有效
println(signPrefixes.value)
```


## 六、RDD 分区

- 基于分区操作 RDD, Spark 会为函数提供该分区中的元素的迭代器
- 返回一个迭代器

``` java
mapPartitions()
  该分区中元素的迭代器,返回元素的迭代器
  rdd.mapPartitions{signs => fnT}

mapPartitionsWithIndex()
  分区序号,以及每个分区中的元素的迭代器,返回元素的迭代器

foreachPartition()
  元素迭代器
```


## 七、集群运行 Spark

- 本地开发验证应用,无须修改代码即可在大规模集群上运行

### 1. Spark 集群运行架构

#### 1.1 Driver 驱动器节点

- Spark Driver 驱动器是执行程序中的 main() 方法的进程

```
Driver 驱动器在 Spark 应用中有个职责 :

1. 把用户程序转为任务
  1) 把用户程序转换为多个物理执行的单元,这些单元被称为 task
  2) Spark 隐式创建一个由操作组成的逻辑上的有向无环图 (DAG),当 Driver 驱动器程序运行时,它会把逻辑图转换为物理执行计划
  3) Spark 会把逻辑计划转换为一系列的步骤(stage),每个步骤(stage)由多个任务(task)组成
  4) Spark 把这些任务(task)被打包送到集群中,任务(task) 是 Spark 最小的工作单元,用户程序通常要启动成百上千的独立任务(task)

2. 为执行器节点调度任务
  1) 有了物理执行计划(DAG),Spark Driver 驱动器程序在各执行器进程间协调任务(task)的调度。执行器进程启动后,会向 Driver 进程注册自己。
  2) 每个执行器节点(Worker) 代表一个能够处理任务(task) 和存储 RDD 数据的进程
  3) Spark Driver 驱动器程序会尝试把任务基于数据所在位置分配给合适的执行器进程
```

#### 1.2 (Worker | NodeManager) 执行器节点

- 负责运行组成 Spark 的任务,并将结果返回给 Driver
- 通过自身的的块管理(BlockManager) 为用户程序中要求缓存的 RDD 提供内存式存储

### 2. 使用 spark-submit 提交任务给集群

``` java


```

### 3. 集群管理与配置

#### 3.1 Standalone

``` sh

```

#### 3.2 Hadoop Yarn

``` sh


```


## 八、Spark 调优与调试

### 1.1 SparkConf

``` java

import org.apache.spark.SparkConf;

val conf = new SparkConf().setAppName("TestStreaming")

```

### 1.2 性能考量

- 并行度
- 序列化格式
- 内存管理
- 硬件供给

```

```



## * 提交应用

- [submitting-applications](https://spark.apache.org/docs/latest/submitting-applications.html)

``` sh

# 命令帮助
  spark-submit -help

# 格式格式
./bin/spark-submit \
  --class <main-class>
  --master <master-url> \
  --deploy-mode <deploy-mode> \
  --conf <key>=<value> \
  --jars /path/xx.jar,/path/xx2.jar
  ... # other options
  <application-jar> \
  [application-arguments]


# Run application locally on 8 cores
./bin/spark-submit \
  --class org.apache.spark.examples.SparkPi \
  --master local[8] \
  /path/to/examples.jar \
  100


# Run on a Spark standalone cluster in client deploy mode
./bin/spark-submit \
  --class org.apache.spark.examples.SparkPi \
  --master spark://207.184.161.138:7077 \
  --executor-memory 20G \
  --total-executor-cores 100 \
  /path/to/examples.jar \
  1000


# Run on a Spark standalone cluster in cluster deploy mode with supervise
./bin/spark-submit \
  --class org.apache.spark.examples.SparkPi \
  --master spark://207.184.161.138:7077 \
  --deploy-mode cluster
  --supervise
  --executor-memory 20G \
  --total-executor-cores 100 \
  /path/to/examples.jar \
  1000


# Run on a YARN cluster
export HADOOP_CONF_DIR=XXX
./bin/spark-submit \
  --class org.apache.spark.examples.SparkPi \
  --master yarn-cluster \  # can also be `yarn-client` for client mode
  --executor-memory 20G \
  --num-executors 50 \
  /path/to/examples.jar \
  1000


# Run a Python application on a Spark standalone cluster
./bin/spark-submit \
  --master spark://207.184.161.138:7077 \
  examples/src/main/python/pi.py \
  1000


-- 提交作业 到 yar

  ./bin/spark-submit \
  --master yarn \
  --deploy-mode client \
  --name app_name \
  --driver-cores 1 \
  --driver-memory 1024M \
  --executor-cores 1 \
  --executor-memory 1024M \
  --num-executors 1 \
  --conf spark.dynamicAllocation.enabled=false \
  --conf spark.shuffle.service.enabled=false \
  --class com.xxx.xxx \
  --files /etc/hive/conf/log4j.properties,/etc/hive/conf/hdfs-site.xml \
  -D hadoop.tmp.dir=/tmp \
  /path/to/examples.jar 100
```
