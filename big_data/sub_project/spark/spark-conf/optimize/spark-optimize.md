# Spark 优化

## 一、Spark 优化

``` sh
##### Spark On Yarn 部署属性  Start #####

# 在集群模式下为每个驱动程序（driver）分配的堆外（off-heap）内存量（以兆字节为单位）. 这是内存, 例如 VM 开销, 内部字符串, 其他本机开销等. 这往往随着容器（container）大小（通常为 6- 10%）增长.
## 驱动程序内存（driverMemory）* 0.10, 最小值为 384, 配置为: driverMemory * 0.5
spark.yarn.driver.memoryOverhead                      1024  (2.3 被废弃)
spark.driver.memoryOverhead                           1024

# 要为每个执行器（executor）分配的堆外（off-heap）内存量（以兆字节为单位）. 这是内存, 例如 VM 开销, 内部字符串, 其他本机开销等. 这往往随着执行器（executor）大小（通常为 6-10%）增长.
## 执行器内存（executorMemory）* 0.10, 最小值为 384, 配置为: memoryOverhead * 0.5
spark.yarn.executor.memoryOverhead                    1024   (2.3 被废弃)
spark.executor.memoryOverhead                         1024        

# 默认队列, submit --queue 可以指定队列, 默认 (default)
# spark.yarn.queue                                      root.realtime

##### Spark On Yarn 部署属性  End #####



##### 应用属性 Start #####

## SparkContext 启动时是否把生效的 SparkConf 属性以 INFO 日志打印到日志里
spark.logConf                                         false

##### 应用属性 End #####



##### 内存管理（Memory Management） Start #####

### Executor Memory 内存规划区域 Start ###

# PS: 一个 Executor 对应一个 JVM 进程, Executor Memory 占用的内存分为两大部分：Execution(执行) and Storage(存储)
# Execution Memory:  执行内存用于以 shuffles , joins , sorts(排序) , aggregations(聚合) 计算的内存
# Storage Memory:    存储内存用于 跨群集缓存 和 传播内部数据的内存(广播变量)
# Execution Memory 和 Storage Memory 共享一个统一的区域(M), 当没有使用 Storage Memory 时 Execution Memory 可以获取所有可用的内存(M)(反之也是一样).
# 如果有必要 Execution Memory 可以驱逐 Storage Memory (只有在总存储器内存使用量低于特定阈值(R)时才执行。换句话说，R 描述一个分区域内的 (M) 缓存块不会被驱逐。由于执行的复杂性，存储可能不会执行)

## * (M) 区域内存
# Execution Memory: 执行器最大内存使用比例(默认 0.6), 剩余 40% 部分被保留用于用户数据的结构, 在 Spark 内部元数据, 保障 OOM 错误, 在异常大而稀疏的记录情况
spark.memory.fraction                                 0.8

## * (R) 区域内存
# Storage Memory: R 是 M 缓存块免于被执行驱逐的存储空间（默认值 0.5）, RDD 的 Storage Memory 与 Cache 的默认分配的内存池大小
spark.memory.storageFraction                          0.3

## Spark 会尝试对某些操作使用堆外内存.  如果启用了堆外内存使用, 则 spark.memory.off Heap.size 必须为正值
spark.memory.offHeap.enabled                          false
## 可用于堆外分配的绝对内存量（以字节为单位）
spark.memory.offHeap.size                             0

## （不建议使用）是否启用Spark 1.5以前使用的传统内存管理模式(默认 false)
spark.memory.useLegacyMode                            false
## （不建议使用）这是只读的, 如果spark.memory.useLegacyMode启用. 在洗牌过程中用于聚合和cogroups的Java堆的分数. 在任何时候, 用于混洗的所有内存映射的总体大小受此限制的限制, 超出该限制内容将开始溢出到磁盘. 如果泄漏经常发生, 可以考虑增加这个值, 代价是 spark.storage.memoryFraction
spark.shuffle.memoryFraction                          0.2
## （不建议使用）这是只读的, 如果spark.memory.useLegacyMode启用. 用于Spark内存缓存的Java堆分数. 这不应该比JVM中的“旧”一代对象大, 默认情况下这个对象是堆的0.6, 但是如果你配置自己的旧一代的大小, 你可以增加它
spark.storage.memoryFraction                          0.6
## （不建议使用）这是只读的, 如果spark.memory.useLegacyMode启用. spark.storage.memoryFraction用于在内存中展开块的分数. 当没有足够的可用存储空间来展开整个新块时, 这是通过删除现有块来动态分配的
spark.storage.unrollFraction                          0.2

### Executor Memory 内存规划区域 End ###

##### 内存管理（Memory Management） End #####



##### 执行器行为（Execution Behavior）Start #####

## * 设置 stage 中 task 默认的并行数量, 不设置可能会直接影响你的 Spark 作业性能, 计算公式为 (Executor Core * 2). 例如设置为 12 Core, 这个值设置为 24
# 默认: join，reduceByKey 和 parallelize 等转换返回的 RDD 中的默认分区数, 这种通过转换回来的话分区往往很大
spark.default.parallelism                             24

## * 每个执行器的心跳与驱动程序之间的间隔. 心跳让驱动程序知道执行器仍然存活, 并用正在进行的任务的指标更新它, 默认 10s
spark.executor.heartbeatInterval                      60s

## 获取文件的通讯超时, 所获取的文件是从驱动程序通过 SparkContext.addFile() 添加的
spark.files.fetchTimeout                              120s

##### 执行器行为（Execution Behavior）End #####



##### Shuffle 行为（Behavior） Start #####

## * reduce 端拉取数据的时候, reduce 一边拉数据一边聚合, reduce 段有一块聚合内存, 默认大小是 48m, （executor memory * 0.2）
## 该参数用于设置 shuffle read task 的 buffer 缓冲大小
spark.reducer.maxSizeInFlight                         48m

## * 是否要对 map 输出的文件进行压缩
spark.shuffle.compress                                true

## * shuffle 过程中对溢出的文件是否压缩
spark.shuffle.spill.compress                          true

##### Shuffle 行为（Behavior） End #####



##### Networking 网络 Start #####

## 所有块管理器监听的端口. 这些都存在于 driver 和 executors 上. 默认随机 (random), 不需要填写
# spark.blockManager.port                               10010

## 所有网络交互的默认超时.  如果未配置此项, 将使用此配置替换 spark.core.connection.ack.wait.timeout, spark.storage.blockManagerSlaveTimeoutMs, spark.shuffle.io.connectionTimeout, spark.rpc.askTimeout or spark.rpc.lookupTimeout.
spark.network.timeout                                 120s

##### Shuffle 行为（Behavior） End #####



##### 压缩和序列化（Compression and Serialization） Start #####

## * 是否在发送广播变量前压缩
spark.broadcast.compress                              true
## 内部数据压缩编码, RDD、广播变量和混洗输出
spark.io.compression.codec                            org.apache.spark.io.SnappyCompressionCodec
## 在采用 Snappy 压缩编解码器的情况下, Snappy 压缩使用的块大小. 减少块大小还将降低采用 Snappy 时的混洗内存使用.
spark.io.compression.snappy.blockSize                 32k

## * Kryo 序列化缓冲区的最大允许大小. 默认 64m
spark.kryoserializer.buffer.max                       64m
## Kryo 序列化缓冲区的初始大小
spark.kryoserializer.buffer                           64k
## * 是否压缩序列化的 RDD 分区, 能节省大量空间, 但多消耗一些 CPU 时间.
spark.rdd.compress                                    true
## 通过网络发送或需要以序列化形式缓存的对象的类, Java 默认序列化很慢
spark.serializer                                      org.apache.spark.serializer.KryoSerializer
## 序列化器 每过 100 个对象被重置一次. 使用 org.apache.spark.serializer.KryoSerializer 序列化的时候,  序列化器缓存对象虽然可以防止写入冗余数据, 但是却停止这些缓存对象的垃圾回收.
spark.serializer.objectStreamReset                    50

##### 压缩和序列化（Compression and Serialization) End #####



##### 动态分配 Start #####

## 注意事项：根据任务动态向 yarn 申请资源, 会导致申请资源浪费大量时间.

## * 是否使用动态资源分配, 它根据工作负载调整为此应用程序注册的执行程序数量.
spark.dynamicAllocation.enabled                       true
## 启用外部 shuffle 服务, 这个服务把 executor 写出的 shuffle 文件保存了其阿里, 所以 executor 可以被安全移除
# spark.dynamicAllocation.enabled = true, 则必须启用此功能
spark.shuffle.service.enabled                         true

## 每个 Application 最小分配的 executor 数
spark.dynamicAllocation.minExecutors                  1
## 每个 Application 最大并发分配的 executor 数.ThriftServer 模式是整个 ThriftServer 同时并发的最大资源数, 如果多个用户同时连接, 则会被多个用户共享竞争
spark.dynamicAllocation.maxExecutors                  6

## 如果启用动态分配, 并且有超过此持续时间的挂起任务积压, 则将请求新的执行者. 默认(1s)
spark.dynamicAllocation.schedulerBacklogTimeout       5s
## 默认与 spark.dynamicAllocation.schedulerBacklogTimeout 相同, 但仅用于后续执行者请求
spark.dynamicAllocation.sustainedSchedulerBacklogTimeout    5s

## 如果启用动态分配, 并且执行程序已空闲超过此持续时间, 则将删除执行程序.
spark.dynamicAllocation.executorIdleTimeout           60s

##### 动态分配 End #####



##### 调度器优化 Start #####

## * FAIR 公平调度器, FIFO 先进先出调度器
spark.scheduler.mode                                  FAIR

## * 任务推测, 任务推测, 把那些持续慢的节点去掉
spark.speculation                                     true
## Spark 检查要推测的任务的时间间隔, 一个任务的速度可以比推测的平均值慢多少倍
spark.speculation.interval                            100ms
## 一个任务的速度可以比推测的平均值慢多少倍, 默认(1.5)
spark.speculation.multiplier                          1.5
## 对特定阶段启用推测之前必须完成的任务的分数。默认(0.75)
spark.speculation.quantile                            0.75

## 每个任务分配的 CPU 核数
spark.task.cpus                                       1

## 放弃作业之前任何特定任务的失败次数, 一个特定的任务允许失败这个次数.
spark.task.maxFailures                                4



### Task 本地化优化 Start ###

## 本地化 5 个级别 ###
# NO_PREF：对于 task 来说，数据从哪里获取都一样，没有好坏之分
# PROCESS_LOCAL: 进程本地化, task 代码和数据(Executor BlockManager)在同一个 Executor 中(进程中), 计算数据的 task 由 Executor 执行, 性能最好
# NODE_LOCAL：节点本地化, task 代码和数据(HDFS Block) 在同一个节点上的一个或多个 Executor 中, 数据需要在进程间进行传输
# RACK_LOCAL：机架本地化, task 代码和数据(HDFS Block) 在一个机架的两个节点上, 数据需要通过网络在节点之间进行传输
# ANY：数据和 task 可能在集群中的任何地方，而且不在一个机架中，性能最差

### 本地化 4 个级别, task 的优化场景
# client 模式下观察 spark 作业的运行日志, 统计 NO_PREF/ PROCESS_LOCAL / NODE_LOCAL / RACK_LOCAL / ANY 在日志中出现数量
# 大多数都是 PROCESS_LOCAL 则可以不用优化, 如果很多都是 NODE_LOCAL / RACK_LOCAL / ANY, 则可以提高如下参数

# * 数据本地化等待时长, 默认 3s
spark.locality.wait                                   6s
# 自定义节点位置 node locality 等待时间, 默认(spark.locality.wait)
spark.locality.wait.node
# 自定义进程 process locality 等待时间, 默认(spark.locality.wait)
spark.locality.wait.process
# 自定义机架 rack locality 等待时间, 默认(spark.locality.wait)
spark.locality.wait.rack

##### 调度器优化 End #####



##### Spark UI Start #####

## 在垃圾回收前，Spark UI 和 API 有多少 Job 可以留存
spark.ui.retainedJobs                                 200

## 在垃圾回收前，Spark UI 和 API 有多少 Stage 可以留存。
spark.ui.retainedStages                               200

## 在垃圾回收前，Spark UI 和 API 有多少 Task 可以留存。
spark.ui.retainedTasks                                400

## 在垃圾回收前，Spark UI 和 API 有多少 executor 已经完成。
spark.worker.ui.retainedExecutors                     300

## 在垃圾回收前，Spark UI 和 API 有多少 driver 已经完成。
spark.worker.ui.retainedDrivers                       300

## 在垃圾回收前，Spark UI 和 API 有多少 execution 已经完成。
spark.sql.ui.retainedExecutions                       300

## 在垃圾回收前，Spark UI 和 API 有多少 batch 已经完成。
spark.streaming.ui.retainedBatches                    300

## 在垃圾回收前，Spark UI 和 API 有多少 dead executors。
spark.ui.retainedDeadExecutors	                      300

##### Spark UI End #####
```



## 二、Spark Sql 优化

``` sh

##### Spark Sql 调优 Start #####

## * 每个分区最大大小, 读取文件时单个分区可容纳的最大字节数, 默认 134217728（128 MB）
spark.sql.files.maxPartitionBytes                     67108864

## * 把小于这个值的文件合并到一个分区中, 避免分区过多, 默认 4194304 (4 MB)
spark.sql.files.openCostInBytes                       67108864

## BroadcastHashJoin 中广播表的超时时间，当任务并发数较高的时候，可以调高该参数值，或者直接配置为负数，负数为无穷大的超时时间。 默认 300(300 秒, 5 分钟)
spark.sql.broadcastTimeout                            300

## * 把数据集小的表, 加载到 Driver 并通过 Broadcast 方法广播到各个 Executor 中, 可以将 Reduce Join 替换为 Map Join, 可以避免 shuffle, 和数据倾斜.
# 优势: 避免了Shuffle，彻底消除了数据倾斜产生的条件，可极大提升性能。劣势: 要求参与Join的一侧数据集足够小，并且主要适用于Join的场景，不适合聚合的场景，适用条件有限。
# 一个表在执行 join 操作时能够广播给所有 worker 节点的最大字节大小, 默认 10485760 (10 M), 公式 (Executor Memory * 0.01). 通过将这个值设置为-1，可以禁用广播
spark.sql.autoBroadcastJoinThreshold                  67108864

## * join 或 聚合 操作混洗（shuffle）数据时使用的分区数, shuffle 的并发度，默认为 200。可用来控制输出的文件数量, 公式 (Executor Core * 2)
spark.sql.shuffle.partitions                          24

## true: 单会话模式. false(默认): 多会话模式, JDBC / ODBC 连接拥有一份自己的 SQL 配置和临时注册表
spark.sql.hive.thriftServer.singleSession             false

## Spark SQL 将会基于数据的统计信息自动地为每一列选择单独的压缩编码方式
spark.sql.inMemoryColumnarStorage.compressed          true

## 控制列式缓存批量的大小,默认(1000)。当缓存数据时，增大批量大小可以提高内存利用率和压缩率，但同时也会带来 OOM（Out Of Memory）的风险。
spark.sql.inMemoryColumnarStorage.batchSize           10000

## spark 格式待测试
spark.sql.default.fileformat                          orc

##### Spark Sql 调优 END #####
```



## 三、Spark Streaming 优化

``` sh

如果 spark 的批次时间 batchTime 超过了 kafka 的心跳时间（30s），需要增加 hearbeat.interval.ms 以及 session.timeout.ms。
假如 batchTime 是 5min，那么就需要调整 group.max.session.timeout.ms


# 超时时间配置规则
## group.[min | max].session.timeout.ms
group.min.session.timeout.ms(in the server.properties) < session.timeout.ms(in the consumer.properties).
group.max.session.timeout.ms(in the server.properties) > session.timeout.ms(in the consumer.properties).

## request.timeout.ms
request.timeout.ms > session.timeout.ms and fetch.max.wait.ms

## heartbeat.interval.ms
(session.timeout.ms)/3 > heartbeat.interval.ms

## session.timeout.ms
session.timeout.ms > Worst case processing time of Consumer Records per consumer poll(ms). (每个消费者调查(ms)的消费者记录的最坏情况处理时间)

## 总结
group.min.session.timeout.ms > session.timeout.ms < group.max.session.timeout.ms

request.timeout.ms > session.timeout.ms > heartbeat.interval.ms



# Consumer Configs
## http://kafka.apache.org/documentation.html#newconsumerconfigs

## 使用 Kafka 的组管理工具时检测消费者故障的超时。消费者定期发送心跳以指示其对经纪人的活跃性。如果在此会话超时到期之前代理没有收到心跳，则代理将从该组中删除此使用者并启动重新平衡。
## 每个消费者轮询的消费者记录的最坏情况处理时间
## 默认 10000(10 秒), 请注意，该值必须在范围内 ( group.min.session.timeout.ms > session.timeout.ms < group.max.session.timeout.ms )
session.timeout.ms                                    100000

# 控制客户端等待请求响应的最长时间。如果在超时之前未收到响应，则客户端将在必要时重新发送请求，或者如果重试耗尽则请求失败。
# 默认 30000(30 秒),  request.timeout.ms > session.timeout.ms
request.timeout.ms                                    100001


## 使用 Kafka 集群管理设施时，心跳与集群协调员之间的预计时间。心跳用于确保工作人员的会话保持活动状态，并在新成员加入或离开组时促进重新平衡
## 默认 3000(3 秒), heartbeat.interval.ms < session.timeout.ms ( 但通常应设置为不高于该值的 1/3 ), 单位毫秒
heartbeat.interval.ms                                 30000


# Broker Configs (kafka 服务端增加)
## http://kafka.apache.org/documentation.html#brokerconfigs

## 注册用户的最小允许会话超时。更短的超时导致更快的故障检测，代价是更频繁的消费者心跳，这可能压倒代理资源。
## 默认 6000(6 秒)
group.min.session.timeout.ms                          6000

## 已注册使用者的最大允许会话超时。较长的超时时间使消费者有更多时间在心跳之间处理消息，但代价是检测故障的时间较长。
## 默认 300000(300 秒)
group.max.session.timeout.ms                          300000
```


``` sh
# Spark Streaming 能够根据当前的批量调度延迟和处理时间来控制接收速率，以便系统只接收系统可以处理的速度
spark.streaming.backpressure.enabled                  true
# Spark Streaming 冷启动时首次处理的条数
spark.streaming.backpressure.initialRate	            1000

# 在使用新的 Kafka 直接流 API 时，每秒从 1 个 Kafka partition 读取数据的最大条数。
spark.streaming.kafka.maxRatePerPartition             3000   

# 每个接收器将接收数据的最大速率（每秒记录数）。将此配置设置为0或负数将不会对速率进行限制。
# spark.streaming.receiver.maxRate                      -1

# Spark Streaming 每隔一段时间, 默认 200 毫秒, 将接收到的数据合并成一个 block，然后将这个 block 写入到 BlockManager.
## 每批中 block 的数量决定了将用于处理类似 map 转换中接收到的数据的 task 数量. task 数量影响处理效率
## spark RDD partition 数量 = batch time interval / block interval
## 建议配置的值为: batch interval(单位是秒 s, 要转换为毫秒 ms) / block interval >= CPU 的核数
spark.streaming.blockInterval                         100

  * 以下是重点
  1. spark 每个 batch_time 处理的日志条数由以下公式决定
    spark.streaming.kafka.maxRatePerPartition * batch_time * kafka_partition_num

  2. spark partition 并行优化
    inputStream.repartition(<number of partitions>) 用来替换 spark.streaming.blockInterval

# Spark Streaming 生成并持久化的强制 RDD 将自动从 Spark 的内存中取消。自动清理 RDD 数据
spark.streaming.unpersist	                            true

# Spark StreamingContext 在 JVM 关闭时关闭而不是立即关闭
spark.streaming.stopGracefullyOnShutdown              true  

# 新的 Kafka 使用者 API 将预先获取消息到缓冲区。
## 消费者缓存默认为最大 64k，如果希望处理超过（64*executor数量）kafka 的分区，可以调节 spark.streaming.kafka.consumer.cache.maxCapacity 这个参数
## 另外，可以调节 spark.streaming.kafka.consumer.cache.enable  false  来禁止缓存，可以解决 Spark-19185 的 bug
spark.streaming.kafka.consumer.cache.enabled          false

spark.streaming.kafka.consumer.cache.maxCapacity      64k
```
