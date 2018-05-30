# spark computation model  spark 计算模型

## 一、介绍

### 1. RDD

1) RDD 全称 Resilient Distributed Datasets,弹性分布式数据集

2) 是一个容错的、并行的数据结构，可以让用户显式地将数据存储到磁盘和内存中,并能控制数据的分区,在多次计算间重用

3) 弹性，是指内存不够时可以与磁盘进行交换。


## 二、案例 与 RDD 详解

### 1. 案例

- [Spark 计算模型案例 - RDD](https://www.processon.com/view/link/565944c1e4b010dc0fa2db37)

``` javascript
(1) SparkContext 中的 textFile 函数从 HDFS(Hive|本地文件) 读取数据
  输出变量 file(是一个 RDD,数据项是文件中的每行数据)

  var file = sc.textFile("hdfs://xx")

(2) RDD 过滤带 "ERROR" 的行

  var errors = file.filter(line=>line.contains("ERROR"))

(3) RDD 的 count 函数返回 "ERROR" 的行数

  errors.count()

```

### 2. RDD 的 4 种创建方式

```
1) 通过 Hive | HDFS | File | Hbase 创建

2) 从父 RDD 转换得到新的 RDD

3) 调用 SparkContext 方法的 parallelize,将 Driver 上的数据集并行化，转化为分布式的 RDD

4) 更改 RDD 的持久性(persistence),例如 cache() 函数。
  默认 RDD 计算后会在内存中清除
  通过 cache 函数将计算后的 RDD 缓存在内存中
```


### 3. RDD 的重要内部属性

```
(1) 分区列表

(2) 计算每个分片的函数

(3) 对父 RDD 的依赖列表

(4) 对 Key-Value 对数据类型 RDD 的分区器 (控制分区策略和分区数)

(5) 每个数据分区的地址列表 (如 HDFS 上的数据块地址)
```

### 4. RDD 数据存储管理策略

RDD 可以抽象理解为一个大的数组，不过这个数组分布在集群上

- [Spark - RDD 数据存储管理策略](https://www.processon.com/view/link/56595b7ae4b017e0bd12ae11)

```
(1) 逻辑上每经历一次变换，就会将 RDD 转换为一个新的 RDD
  1) RDD 间通过 lineage[lɪnɪɪd](血统) 产生依赖关系
  2) 变换的输入和输出都是 RDD

(2) 物理上 RDD 实际是一个元数据结构,存储 Block 、Node 等映射关系，以及其他元数据信息
  1) 一个 RDD 就是一组分区
  2) 一个分区就是对应一个 Block

(3) 如果数据是从 HDFS 等外部作为输入源
  1) RDD 的策略 -> 按照 HDFS 中的数据分布策略进行分区,HDFS 的每个 Block 对应 RDD 的一个分区

```

### 5. RDD 的 2 种计算操作算子

- [Spark - 执行有向无环图 - 算子模型](https://www.processon.com/view/link/5659315fe4b07750c3f68452)

#### 5.1 Transformation (变换)

```
(1) Transformation[trænsfə'meɪʃ(ə)n] 算子操作是延迟计算的

(2) 也就是 一个 RDD 转换生成另外一个 RDD 的转换操作不是马上执行,等到有 Actions 操作时，才会真正触发

(3) 再细分的数据类型
  1) Value 数据类型 : 封装在 RDD 类中可以直接使用

  2) Key-Value 数据类型 : 封装在 PairPDDFunctions 类中,需要引入 import org.apache.spark.SparkContext._ 才能够使用
```

#### 5.2  Action (行动)

```
Action 算子会触发 SparkContext 提交 Job 作业 , 并将数据输出到 RDD
```
