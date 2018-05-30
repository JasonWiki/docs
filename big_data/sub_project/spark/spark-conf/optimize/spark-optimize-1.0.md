# Spark 优化

## 一、Spark 优化

``` sql

--- 应用属性 Start ---

-- SparkContext 启动时是否把生效的 SparkConf 属性以 INFO 日志打印到日志里
SET spark.logConf=false;


--- 应用属性 End ---



--- 执行器行为（Execution Behavior）Start ---

-- 设置每个 stage 的默认 task 数量, 不设置可能会直接影响你的 Spark 作业性能。公式 (num-executors * executor-cores)*3
SET spark.default.parallelism=18;

--- 执行器行为（Execution Behavior）End ---



--- 内存管理（Memory Management） Start ---

-- ExecutorMemory 即 --executor-memory, 每个 Executor 进程使用的内存量
-- PS: 一个 Executor 对应一个JVM进程，Executor 占用的内存分为两大部分：execution(执行) and storage(存储) -----
--  execution: 内存用于以 shuffles, joins, sorts and aggregations 计算的内存
--  storage: 内存用于在集群中缓存和传播内部数据的内存
--  当没有使用 storage 内存时，execution 可以获取所有可用的内存(反之也是一样). execution 可以驱逐 storage(只有在总存储内存使用量到达某个阈值), 缓存块永远不会被驱逐
spark.executor.memory=由外部传入定义

-- execution(执行): 执行器最大内存使用比例,（默认 0.6）剩余 40% 部分被保留用于用户数据的结构，在 Spark 内部元数据，保障 OOM 错误，在异常大而稀疏的记录情况
spark.memory.fraction=0.6;

-- storage(存储): 配置 rdd 的 storage 与 cache 的默认分配的内存池大小（默认值 0.5）
spark.memory.storageFraction=0.5


-- （不建议使用）是否启用Spark 1.5以前使用的传统内存管理模式
spark.memory.useLegacyMode=false;
-- （不建议使用）这是只读的，如果spark.memory.useLegacyMode启用。在洗牌过程中用于聚合和cogroups的Java堆的分数。在任何时候，用于混洗的所有内存映射的总体大小受此限制的限制，超出该限制内容将开始溢出到磁盘。如果泄漏经常发生，可以考虑增加这个值，代价是 spark.storage.memoryFraction
spark.shuffle.memoryFraction=0.2;
-- （不建议使用）这是只读的，如果spark.memory.useLegacyMode启用。用于Spark内存缓存的Java堆分数。这不应该比JVM中的“旧”一代对象大，默认情况下这个对象是堆的0.6，但是如果你配置自己的旧一代的大小，你可以增加它
spark.storage.memoryFraction=0.6;
-- （不建议使用）这是只读的，如果spark.memory.useLegacyMode启用。spark.storage.memoryFraction用于在内存中展开块的分数。当没有足够的可用存储空间来展开整个新块时，这是通过删除现有块来动态分配的
spark.storage.unrollFraction=0.2;

--- 内存管理（Memory Management） End ---



--- Spark On Yarn 部署属性  Start ---

-- 群集模式下每个驱动程序分配的堆外存储器数量（以兆字节为单位）。这是内存，占VM的开销，interned字符串，其他本地开销等东西。
-- driverMemory * 0.10，最低384
spark.yarn.driver.memoryOverhead = 384;

-- 每个执行程序分配的堆内存量（以兆字节为单位). 内存占 VM 的开销，interned 字符串，其他本地开销等东西。这往往随着执行器的大小（通常6-10％）增长
-- executorMemory * 0.10，至少384
spark.yarn.executor.memoryOverhead = 384;

--- Spark On Yarn 部署属性  End ---



--- Shuffle 行为（Behavior） Start ---

-- 每个输出都要创建一个缓冲区，这代表要为每一个 Reduce 任务分配一个固定大小的内存, 除非内存很大否则设置小点, 默认 48m
SET spark.reducer.maxSizeInFlight=256m;

-- 是否要对 map 输出的文件进行压缩
SET spark.shuffle.compress=true;

-- shuffle 过程中对溢出的文件是否压缩
SET spark.shuffle.spill.compress=true;

--- Shuffle 行为（Behavior） End ---



--- Networking 网络 Start ---

-- 所有块管理器监听的端口。 这些都存在于 driver 和 executors 上。
spark.blockManager.port=random

-- 所有网络交互的默认超时。 如果未配置此项，将使用此配置替换 spark.core.connection.ack.wait.timeout, spark.storage.blockManagerSlaveTimeoutMs, spark.shuffle.io.connectionTimeout, spark.rpc.askTimeout or spark.rpc.lookupTimeout。
spark.network.timeout=120s

--- Shuffle 行为（Behavior） End ---



--- 压缩和序列化（Compression and Serialization） Start ---

-- 是否在发送广播变量前压缩
SET spark.broadcast.compress=true;
-- 内部数据压缩编码, RDD、广播变量和混洗输出
SET spark.io.compression.codec=org.apache.spark.io.SnappyCompressionCodec;
-- 在采用 Snappy 压缩编解码器的情况下，Snappy 压缩使用的块大小。减少块大小还将降低采用 Snappy 时的混洗内存使用。
SET spark.io.compression.snappy.blockSize=32k;

-- Kryo 序列化缓冲区的最大允许大小。
SET spark.kryoserializer.buffer.max=256m;
-- Kryo 序列化缓冲区的初始大小
SET spark.kryoserializer.buffer=64k;
-- 是否压缩序列化的 RDD 分区, 能节省大量空间，但多消耗一些 CPU 时间。
SET spark.rdd.compress=true;
-- 通过网络发送或需要以序列化形式缓存的对象的类, Java 默认序列化很慢
SET spark.serializer=org.apache.spark.serializer.KryoSerializer;
-- 序列化器 每过 100 个对象被重置一次. 使用 org.apache.spark.serializer.KryoSerializer 序列化的时候,  序列化器缓存对象虽然可以防止写入冗余数据，但是却停止这些缓存对象的垃圾回收.
SET spark.serializer.objectStreamReset=100;

--- 压缩和序列化（Compression and Serialization) End ---




--- 动态分配 Start ---

-- 注意事项：根据任务动态向 yarn 申请资源, 会导致申请资源浪费大量时间。

-- 是否使用动态资源分配，它根据工作负载调整为此应用程序注册的执行程序数量。
SET spark.dynamicAllocation.enabled=false;
-- 每个Application最小分配的executor数
SET spark.dynamicAllocation.minExecutors=1;
-- 每个 Application 最大并发分配的 executor 数。  ThriftServer 模式是整个 ThriftServer 同时并发的最大资源数，如果多个用户同时连接，则会被多个用户共享竞争
SET spark.dynamicAllocation.maxExecutors=30;
SET spark.dynamicAllocation.schedulerBacklogTimeout=1s;
SET spark.dynamicAllocation.sustainedSchedulerBacklogTimeout=5s;
-- 使用外部 shuffle, 保存了由 executor 写出的 shuffle 文件所以 executor 可以被安全移除, spark.dynamicAllocation.enabled 为 true, 这个选项才可以为 true
SET spark.shuffle.service.enabled=false;
-- 如果启用动态分配，并且执行程序已空闲超过此持续时间，则将删除执行程序。
SET spark.dynamicAllocation.executorIdleTimeout=60s;

--- 动态分配 End ---




--- 调度器优化 Start ---

-- FAIR 公平调度器, FIFO 先进先出调度器
SET spark.scheduler.mode=FAIR;

-- 任务推测
SET spark.speculation=true;

-- 每个任务分配的 CPU 核数
SET spark.task.cpus=1;

--- 调度器优化 End ---



--- Spark UI Start ---

-- 在垃圾回收前，Spark UI 和 API 有多少 Job 可以留存
SET spark.ui.retainedJobs=300;

-- 在垃圾回收前，Spark UI 和 API 有多少 Stage 可以留存。
SET spark.ui.retainedStages=300;

-- 在垃圾回收前，Spark UI 和 API 有多少 Task 可以留存。
SET spark.ui.retainedTasks=500;

-- 在垃圾回收前，Spark UI 和 API 有多少 executor 已经完成。
SET spark.worker.ui.retainedExecutors=300;

-- 在垃圾回收前，Spark UI 和 API 有多少 driver 已经完成。
SET spark.worker.ui.retainedDrivers=300;

-- 在垃圾回收前，Spark UI 和 API 有多少 execution 已经完成。
SET spark.sql.ui.retainedExecutions=300;

-- 在垃圾回收前，Spark UI 和 API 有多少 batch 已经完成。
SET spark.streaming.ui.retainedBatches=300;

-- 在垃圾回收前，Spark UI 和 API 有多少 dead executors。
SET spark.streaming.ui.retainedBatches=300;

--- Spark UI End ---

```



## 二、Spark Sql 优化

``` sql

--- Spark Sql 调优 Start ---

-- 读取文件时单个分区可容纳的最大字节数(128M)。
SET spark.sql.files.maxPartitionBytes=268435456;

-- 调节每个 partition 大小(128M), 小文件合并
SET spark.sql.files.openCostInBytes=134217728;

-- 一个表在执行 join 操作时能够广播给所有 worker 节点的最大字节大小
SET spark.sql.autoBroadcastJoinThreshold=134217728;

-- 连接或聚合操作混洗（shuffle）数据时使用的分区数, shuffle 的并发度，默认为 200。可用来控制输出的文件数量
SET spark.sql.shuffle.partitions=30;

-- true: 单会话模式. false(默认): 多会话模式, JDBC / ODBC 连接拥有一份自己的 SQL 配置和临时注册表
SET spark.sql.hive.thriftServer.singleSession=false;

-- Spark SQL 将会基于数据的统计信息自动地为每一列选择单独的压缩编码方式
SET spark.sql.inMemoryColumnarStorage.compressed=true;

-- 控制列式缓存批量的大小。当缓存数据时，增大批量大小可以提高内存利用率和压缩率，但同时也会带来 OOM（Out Of Memory）的风险。
SET spark.sql.inMemoryColumnarStorage.batchSize=10000;

-- spark 格式待测试
SET spark.sql.default.fileformat=orc;

--- Spark Sql 调优 END ---
```



## 三、Spark Streaming 优化

``` sql
-- saprk 与 kafka 连接超时参数
spark.streaming.kafka.consumer.poll.ms=10000

-- 最大行参数, 配置在 kafka 中
max.poll.records=5000

```
