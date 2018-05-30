# Spark 2.0

## 一、Spark 2.0 新增特性

### * 统一了数据集 RDD/DataFrame/Dataset

- RDD 不可变, 非结构化
  - 流媒体, 文本流

- PS: DataFrame 和 Dataset 是处理结构化数据的

- DataFrame(组织成命名列的数据集, DataFrame 由 Row 的数据集表示), 不可变结构化列式存储
 - DataFrame 由 Row 的数据集表示
 - DataFrame 只是 Dataset[Row] 一个类型别名, DataFrame = Dataset[Row]

- Dataset(分布式数据集合), 强类型存储, 可使用（map, flatMap, filter）操作
 - 每行用 JVM object 强类型存储, 如 `Scala case 模式匹配类` 或 `Java class 类`
 - Dataset 通过 Encoder 实现了自定义的序列化格式, 无需解序列化, 从而无需受限于 JVM. 可自己管理内存。 Tungsten 对 Dataset 进行持续优化

- 领域特定语言(DSL)


### 1. Spark SQL, DataFrames and Datasets 改变

- 数据帧（DataFrame）/ Dataset（数据集）API的统一
 - DataFrame 是 Dataset[Row] 的别名, Dataset[Row] 返回的是一个 DataFrame 对象, 现在高度抽象化了
 - `DataFrames 只是行(row) 数据集的类型别名了(typealias)`

- SparkSession 新入口
 - `替代 SQLContext 和 HiveContext 的 Api, 这是个简化的 Api`
 - 原本的 SQLContext 与 HiveContext 仍然保留，以支持向下兼容

- SparkSQL 支持 SQL2003
 - 支持 SQL2003 所有的 99 TPC-DS 查询, 支持内置 DDL 命令, 支持 ANSI-SQL 和 Hive Sql 的内置 SQL 解析器, 99% 的 Sql 语法都支持了

- Catalog Api
 - 管理才做 Spark SQL 中的元数据的统一规范 API
 - 操作视图、DLL

- Catalyst 查询优化器模块
 - Spark SQL 使用 Catalyst 优化所有的查询, 如 Spark Sql 和 DataFrame DSL, 优化后的查询比直接使用 RDD 要快很多
 - Catalyst 是个单独的模块类库, 是个基于规则优化的模块, 这个框架中的每个规则都是针对某个特定情况来优化的.
 - 可以自定义优化规则模块, Spark 官方也会不断优化 Catalyst 模块, 而不需要用户特定去优化


### 2. Structured Streaming 结构持久化的 Streaming

- 引入了 Structured Streaming 模块
 - 与批处理作业集成的 API
 - 构建在 Spark SQL 和 Catalyst optimize 之上的高级 Streaming API
 - 可以在流数据 sources 和 slinks 使用 DataFrame/Dataset API, 可以使用 Catalyst optimize 生成查询计划
 - 与存储系统的事务交互
 - 与 Spark 的其它组件的深入集成

- Structured Streaming 详细说明

 - 1.与批处理作业集成的API:
   ```
   开发者可针对 DataFrame/Dataset API 编写批处理计算，Spark 会自动在流数据模式中执行计算，也就是说在数据输入时实时更新结果。
   强大的设计令开发者无需费心管理状态与故障，也无需确保应用与批处理作业的同步，这些都由系统自动解决。此外，针对相同的数据，批处理任务总能给出相同的结果。
   ```

 - 2.与存储系统的事务交互:
   ```
   Structured Streaming 会在整个引擎及存储系统中处理容错与持久化的问题，使得程序员得以很容易地编写应用，令实时更新的数据库可靠地提供、加入静态数据或者移动数据。
   ```

 - 3.与Spark的其它组件的深入集成:
  ```
  Structured Streaming 支持通过Spark SQL进行流数据的互动查询，可以添加静态数据以及很多已经使用DataFrames的库，还能让开发者得以构建完整的应用，而不只是数据流管道。
  ```

- DStream API 支持 Kafka 0.10 版本


### 3. Dependency and Packaging  依赖和包

- `编译默认使用 Scala 2.11 而不是 2.10`
- Spark 2.x 生产部署不再需要 fat assembly jar
- Akka 的依赖移除, 用户可以引入任何版本的 Akka
- Kryo 升级到 3.0


### 4. Removals, Behavior Changes and Deprecations 删除、行为变化和用法

- Removals 删除的
 - `Hadoop 2.1 及之前的版本`
 - closure serializer  能力
 - HTTPBroadcast
 - 基于 ETL 的元数据清理
 - 半私有的类 org.apache.spark.Logging，建议直接使用 slf4
 - SparkContext.metricsSystem
 - 面向块的和 Tachyon 进行整合
 - Python 语言中所有 DataFrame 返回 RDD 的方法（map, flatMap, mapPartitions等等），不过这些方法仍然可以通过dataframe.rdd访问，比如dataframe.rdd.map
 - 不常用的流连接器，包括 Twitter, Akka, MQTT, ZeroMQ
 - Hash-based shuffle manager
 - 独立模式的 Master 历史服务器功能
 - `对 Java 和 Scala 语言，DataFrame 不再作为一个类存在。所以数据源可能需要升级`
 - Spark EC2 脚本已经被完全移到 external repository hosted by the UC Berkeley AMPLab

- Behavior Changes 行为变化
 - `编译时默认使用 Scala 2.11 而不是 2.10`
 - 在SQL中，浮点数字现在解析成decimal数据类型，而不再是double数据类型
 - `Kryo 升级到 3.0`
 - Java RDD 的 flatMap 和 mapPartitions 函数之前要求传进来的函数返回 Java Iterable，现在需要返回 Java iterator，所以这个函数不需要 materialize 所有的数据
 - Java RDD 的 countByKey 和 countAprroxDistinctByKey 函数现在将K类型的数据返回成 java.lang.Long而不是java.lang.Object
 - 当写 Parquet 文件的时候，默认已经不写 summary files了，如果需要开启它，用户必须将 parquet.enable.summary-metadata设置为true
 - 基于 DataFrame的API(spark.ml) 现在取决于 spark.ml.linalg 中的本地线性代数，而不是 spark.mllib.linalg。现在所有的 `spark.mllib.*` 都被替换成 `spark.ml.*了`。(SPARK-13944)

- Deprecations 未来的用法, 可能在未来版本被移除
 - `Java 7 的支持, 未来会升级到 Java 8`
 - `Python 2.6 的支持, 未来会升级到更新的版本`
 - Mesos 的细粒度模式


### 5. 新的功能

- `Spark 2.0 搭载了第二代 Tungsten['tʌŋst(ə)n] 引擎, 代码段生成 whole-stage code generation`
 - 根据现代编译器与 MPP 数据库的理念来构建的，它将这些理念用于数据处理中，其主要思想就是在运行时使用优化后的字节码，将整体查询合成为单个函数，不再使用虚拟函数调用，而是利用CPU来注册中间数据
 - 人话: 优化运行期间拖慢整个查询的代码, 到一个单独的函数中, 消除虚拟函数的调用, 以及利用 CPU 寄存器来存放那些中间数据.

- 内置的 CSV 数据源，基于 Databricks 的 Spark-csv 模块, 之前版本的 Spark 这个一直都是作为第三方数据源

- 缓存和运行时执行都支持堆外内存管理

- 设计了一个新的 Accumulator API，专门支持基本类型。

- 通过 vectorization 向量化, 技术提升了 Parquet 文件扫描的吞吐量

- 为内置的数据源进行自动地文件合并


## 二、详细介绍

### 1. Tungsten['tʌŋst(ə)n] 引擎(Whole-stage code-generation 整段代码生成技术) 与 Volcano[vɒl'keɪnəʊ] 模型

- Volcano 模型, 没有虚函数调用
 > 在 Volcano 模型中，处理一个元组(tuple)最少需要调用一次 next() 函数。
 这些函数的调用是由编译器通过虚函数调度实现的（通过vtable）；而手写版本的代码没有一个函数调用。
 虽然虚函数调度是现代计算机体系结构中重点优化部分，它仍然需要消耗很多 CPU 指令而且相当的慢，特别是调度数十亿次

- Volcano 模型, 内存和 CPU 寄存器中的临时数据
 > 在 Volcano 模型中，每次一个算子给另外一个算子传递元组的时候，都需要将这个元组存放在内存中；
 而在手写版本的代码中，编译器(这个例子中是JVM JIT)实际上将临时数据存放在CPU寄存器中。
 访问内存中的数据所需要的CPU时间比直接访问在寄存器中的数据要大一个数量级！

- Volcano 模型, 循环展开(Loop unrolling) 和 SIMD
 > 当运行简单的循环时，现代编译器和CPU是令人难以置信的高效。编译器会自动展开简单的循环，甚至在每个CPU指令中产生SIMD指令来处理多个元组。
 CPU的特性，比如管道(pipelining)、预取(prefetching)以及指令重排序(instruction reordering)使得运行简单的循环非常地高效。
 然而这些编译器和CPU对复杂函数调用图的优化极少，而这些函数正是Volcano模型依赖的。

- Tungsten 引擎(Spark 之前使用 JVM 管理内存, 后面使用 Tungsten 管理内存), 代码生成
 > 使用整段代码生成使得 Spark计 算引擎可以实现手写代码的性能，并且提供通用的功能。
 而不是在运行时依赖算子来处理数据，这些算子在运行时生成代码，如果可能的话将所有的查询片段组成到单个函数中，后面我们仅需要运行生成的代码。

- Tungsten 引擎, vectorization[,vektəri'zeiʃən] 向量化
 > 对于不能使用代码生成器的数据(比如外部的 CSV 解析, 或者 Parquet 解码), 使用批量处理的策略
 　为了提高这些情况下的性能，我们提出一个新的方法叫做向量化(vectorization)。
   核心思想是：我们不是一次只处理一行数据，而是将许多行的数据分别组成batches，而且采用列式格式存储；
   然后每个算子对每个batch进行简单的循环来遍历其中的数据。所以每次调用next()函数都会返回一批的元组，这样可以分摊虚函数调用的开销。
   采用了这些措施之后，这些简单的循环也会使得编译器和CPU运行的更加高效。


## 三、SPARK SQL API
