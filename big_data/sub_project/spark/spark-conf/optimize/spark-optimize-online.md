#
# Licensed to the Apache Software Foundation (ASF) under one or more
# contributor license agreements.  See the NOTICE file distributed with
# this work for additional information regarding copyright ownership.
# The ASF licenses this file to You under the Apache License, Version 2.0
# (the "License") you may not use this file except in compliance with
# the License.  You may obtain a copy of the License at
#
#    http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#

# Default system properties included when running spark-submit.
# This is useful for setting default environmental settings.

# Example:
# spark.master                     spark://master:7077
# spark.eventLog.enabled           true
# spark.eventLog.dir               hdfs://namenode:8021/directory
# spark.serializer                 org.apache.spark.serializer.KryoSerializer
# spark.driver.memory              5g
# spark.executor.extraJavaOptions  -XX:+PrintGCDetails -Dkey=value -Dnumbers="one two three"


##### Spark On Yarn 部署属性  Start #####

## 在集群模式下为每个驱动程序（driver）分配的堆外（off-heap）内存量（以兆字节为单位）, 配置为: driverMemory * 0.5
spark.yarn.driver.memoryOverhead                      1024

## 要为每个执行器（executor）分配的堆外（off-heap）内存量（以兆字节为单位）, 配置为: memoryOverhead * 0.5
spark.yarn.executor.memoryOverhead                    1024


##### Spark On Yarn 部署属性  End #####



##### 应用属性 Start #####

## SparkContext 启动时是否把生效的 SparkConf 属性以 INFO 日志打印到日志里
spark.logConf                                         false

##### 应用属性 End #####



##### 内存管理（Memory Management） Start #####

## execution(执行): 执行器最大内存使用比例,（默认 0.6）剩余 40% 部分被保留用于用户数据的结构，在 Spark 内部元数据，保障 OOM 错误，在异常大而稀疏的记录情况
spark.memory.fraction                                 0.8

## storage(存储): 配置 rdd 的 storage 与 cache 的默认分配的内存池大小（默认值 0.5）
spark.memory.storageFraction                          0.3

##### 内存管理（Memory Management） End #####



##### 执行器行为（Execution Behavior）Start #####

## * 设置 stage 中 task 默认的并行数量, 不设置可能会直接影响你的 Spark 作业性能, 计算公式为 (Executor Core * 2). 例如设置为 12 Core, 这个值设置为 24
spark.default.parallelism                             24

## 每个执行器的心跳与驱动程序之间的间隔。 心跳让驱动程序知道执行器仍然存活，并用正在进行的任务的指标更新它
spark.executor.heartbeatInterval                      60s

## 获取文件的通讯超时，所获取的文件是从驱动程序通过 SparkContext.addFile() 添加的
spark.files.fetchTimeout                              120s

##### 执行器行为（Execution Behavior）End #####



##### Networking 网络 Start #####

## 所有块管理器监听的端口. 这些都存在于 driver 和 executors 上. 默认随机 (random), 不需要填写
# spark.blockManager.port                               10010

## 所有网络交互的默认超时。 如果未配置此项，将使用此配置替换 spark.core.connection.ack.wait.timeout, spark.storage.blockManagerSlaveTimeoutMs, spark.shuffle.io.connectionTimeout, spark.rpc.askTimeout or spark.rpc.lookupTimeout。
spark.network.timeout                                 120s

##### Shuffle 行为（Behavior） End #####



##### 压缩和序列化（Compression and Serialization） Start #####

## 是否在发送广播变量前压缩
spark.broadcast.compress                              true
## 内部数据压缩编码, RDD、广播变量和混洗输出
spark.io.compression.codec                            org.apache.spark.io.SnappyCompressionCodec
## 在采用 Snappy 压缩编解码器的情况下，Snappy 压缩使用的块大小。减少块大小还将降低采用 Snappy 时的混洗内存使用。
spark.io.compression.snappy.blockSize                 32k

## * Kryo 序列化缓冲区的最大允许大小。
spark.kryoserializer.buffer.max                       64m
## Kryo 序列化缓冲区的初始大小
spark.kryoserializer.buffer                           64k
## 是否压缩序列化的 RDD 分区, 能节省大量空间，但多消耗一些 CPU 时间。
spark.rdd.compress                                    true
## 通过网络发送或需要以序列化形式缓存的对象的类, Java 默认序列化很慢
spark.serializer                                      org.apache.spark.serializer.KryoSerializer
## 序列化器 每过 多少 个对象被重置一次. 使用 org.apache.spark.serializer.KryoSerializer 序列化的时候,  序列化器缓存对象虽然可以防止写入冗余数据，但是却停止这些缓存对象的垃圾回收.
spark.serializer.objectStreamReset                    50

##### 压缩和序列化（Compression and Serialization) End #####



##### 动态分配 Start #####

## 注意事项：根据任务动态向 yarn 申请资源, 会导致申请资源浪费大量时间。

## 是否使用动态资源分配，它根据工作负载调整为此应用程序注册的执行程序数量。
spark.dynamicAllocation.enabled                       true
## 使用外部 shuffle, 保存了由 executor 写出的 shuffle 文件所以 executor 可以被安全移除, spark.dynamicAllocation.enabled 为 true, 这个选项才可以为 true
spark.shuffle.service.enabled                         true

## 每个 Application 最小分配的 executor 数
spark.dynamicAllocation.minExecutors                  3
## 每个 Application 最大并发分配的 executor 数。 ThriftServer 模式是整个 ThriftServer 同时并发的最大资源数，如果多个用户同时连接，则会被多个用户共享竞争
spark.dynamicAllocation.maxExecutors                  6

## 如果启用动态分配，并且有超过此持续时间的挂起任务积压，则将请求新的执行者。
spark.dynamicAllocation.schedulerBacklogTimeout       6s
## 与 spark.dynamicAllocation.schedulerBacklogTimeout 相同，但仅用于后续执行者请求
spark.dynamicAllocation.sustainedSchedulerBacklogTimeout              6s

## 如果启用动态分配，并且执行程序已空闲超过此持续时间，则将删除执行程序。
spark.dynamicAllocation.executorIdleTimeout           600s

##### 动态分配 End #####



##### 调度器优化 Start #####

## FAIR 公平调度器, FIFO 先进先出调度器
spark.scheduler.mode                                  FAIR

# 数据本地化等待时长
spark.locality.wait                                   10s

## 任务推测, 把那些持续慢的节点去掉
spark.speculation                                     true
## 一个任务的速度可以比推测的平均值慢多少倍
spark.speculation.multiplier                          1.5

## 每个任务分配的 CPU 核数
spark.task.cpus                                       1

## 放弃作业之前任何特定任务的失败次数, 一个特定的任务允许失败这个次数.
spark.task.maxFailures                                4

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
spark.streaming.ui.retainedBatches                    300

##### Spark UI End #####



##### Spark Sql 调优 Start #####

## 每个分区最大大小, 读取文件时单个分区可容纳的最大字节数, 默认 134217728 (128M)。268435456
spark.sql.files.maxPartitionBytes                     67108864

## 把小于这个值的文件合并到一个分区中, 避免分区过多, 默认 4194304 (4 MB)
spark.sql.files.openCostInBytes                       67108864

## BroadcastHashJoin 中广播表的超时时间，当任务并发数较高的时候，可以调高该参数值，或者直接配置为负数，负数为无穷大的超时时间。 默认 300(300 秒, 5 分钟)
spark.sql.broadcastTimeout                            600

## 一个表在执行 join 操作时能够广播给所有 worker 节点的最大字节大小, 默认 10485760(10 M), 公式 (Executor Memory * 0.01)
spark.sql.autoBroadcastJoinThreshold                  67108864

## 连接或聚合操作混洗（shuffle）数据时使用的分区数, shuffle 的并发度，默认为 200。可用来控制输出的文件数量, 公式 (Executor Core * 2)
spark.sql.shuffle.partitions                          24

## true: 单会话模式. false(默认): 多会话模式, JDBC / ODBC 连接拥有一份自己的 SQL 配置和临时注册表
spark.sql.hive.thriftServer.singleSession             false

## Spark SQL 将会基于数据的统计信息自动地为每一列选择单独的压缩编码方式
spark.sql.inMemoryColumnarStorage.compressed          true

## 控制列式缓存批量的大小。当缓存数据时，增大批量大小可以提高内存利用率和压缩率，但同时也会带来 OOM（Out Of Memory）的风险。1000
spark.sql.inMemoryColumnarStorage.batchSize           10000

## spark 格式待测试
spark.sql.default.fileformat                          orc
