# Flume 配置模板

- [Flume 模板配置](http://flume.apache.org/FlumeUserGuide.html#more-sample-configs)

### 1. spooldir -> hdfs 的配置

``` sh

* Conf 配置

agentDw.sources = SrcAccessLog
agentDw.channels = ChAccesslog
agentDw.sinks = SinkAccesslog

# set SrcAccessLog

# SrcSafeClickLog Source 配置
agentDw.sources.SrcSafeClickLog.type = spooldir
agentDw.sources.SrcSafeClickLog.spoolDir = /data/log/uba/access_log
# 忽略文件正则
agentDw.sources.SrcSafeClickLog.ignorePattern = ^(.)*\\.tmp$
# 输入字符编码
agentDw.sources.SrcSafeClickLog.inputCharset = UTF-8
# 反序列化方式
agentDw.sources.SrcSafeClickLog.deserializer = LINE
# 一行最大字数
agentDw.sources.SrcSafeClickLog.deserializer.maxLineLength = 204800
agentDw.sources.SrcSafeClickLog.deserializer.outputCharset = UTF-8
# 解码错误政策处理规则, FAIL(失效) || IGNORE(忽略)
agentDw.sources.SrcSafeClickLog.decodeErrorPolicy = IGNORE
# 完成删除文件 immediate | never
agentDw.sources.SrcSafeClickLog.deletePolicy = immediate
# 批处理条数
agentDw.sources.SrcSafeClickLog.batchSize = 1000
# 递归检测目录(必须开启)
agentDw.sources.SrcSafeClickLog.recursiveDirectorySearch = true
# 上传文件的绝对路径(必须开启)
agentDw.sources.SrcSafeClickLog.fileHeader = true
agentDw.sources.SrcSafeClickLog.fileHeaderKey = file
# 上传的文件名(必须开启)
agentDw.sources.SrcSafeClickLog.basenameHeader = true
agentDw.sources.SrcSafeClickLog.basenameHeaderKey = basename
agentDw.sources.SrcSafeClickLog.channels = ChAccesslog


# set ChAccesslog
# 保存类型
agentDw.channels.ChAccesslog.type = file
agentDw.channels.ChAccesslog.checkpointDir = /data/log/test/checkpoint
agentDw.channels.ChAccesslog.dataDirs = /data/log/test/data
# 设置最大线程数
agentDw.channels.ChAccesslog.threads = 10


# SinkAccesslog 设置
agentDw.sinks.SinkAccesslog.type = hdfs
agentDw.sinks.SinkAccesslog.channel = ChDwAccesslog
agentDw.sinks.SinkAccesslog.hdfs.path = hdfs://uhadoop-ociicy-master2:8020/flume/test/access_log_%Y%m%d
# hdfs 创建文件前缀
agentDw.sinks.SinkAccesslog.hdfs.filePrefix = access_log
# hdfs 创建文件后缀
agentDw.sinks.SinkAccesslog.hdfs.fileSuffix = .log

# 临时写入时的前缀  
agentDw.sinks.SinkAccesslog.hdfs.inUsePrefix = .
agentDw.sinks.SinkAccesslog.hdfs.inUseSuffix = .tmp

agentDw.sinks.SinkAccesslog.hdfs.round = true
agentDw.sinks.SinkAccesslog.hdfs.roundValue = 10
# 下舍入值的单位 second, minute or hour.
agentDw.sinks.SinkAccesslog.hdfs.roundUnit = minute

# 复制块, 用于控制滚动大小
agentDw.sinks.SinkAccesslog.hdfs.minBlockReplicas=1
# 文件大小来触发滚动(字节), 0: 永远不触发
agentDw.sinks.SinkAccesslog.hdfs.rollSize = 0
# 文件条数来触发滚动(数量), 0:永远不触发
agentDw.sinks.SinkAccesslog.hdfs.rollCount = 0
# 滚动前等待的秒数(秒), 0:没有时间间隔, 每隔多少秒产生一个新文件, 案例为 60 喵
agentDw.sinks.SinkAccesslog.hdfs.rollInterval = 60

# 写入格式
agentDw.sinks.SinkAccesslog.hdfs.writeFormat = Text
# 文件格式 :  SequenceFile, DataStream(数据不会压缩输出文件) or CompressedStream(压缩输出,需要选择一个压缩/解码器)
agentDw.sinks.SinkAccesslog.hdfs.fileType = DataStream

# 批处理达到这个上限, 写到 HDFS
agentDw.sinks.SinkAccesslog.hdfs.batchSize = 100
# hdfs 打开、写、刷新、关闭的超时时间, 毫秒
agentDw.sinks.SinkAccesslog.hdfs.callTimeout = 60000
# 使用本地时间
agentDw.sinks.SinkAccesslog.hdfs.useLocalTimeStamp = true

```


### 2. syslogtcp -> file 配置

- 必须先启动 Flume, 开启 TCP|UDP 端口, 保证 Syslog 可以通过指定端口发送日志数据


``` sh

agentDw.sources = SrcUbaAppActionLog
agentDw.channels = ChUbaAppActionLog
agentDw.sinks = SinkUbaAppActionLog

# UbaAppActionLog source 配置
agentDw.sources.SrcUbaAppActionLog.type = syslogtcp
agentDw.sources.SrcUbaAppActionLog.port = 10001
agentDw.sources.SrcUbaAppActionLog.host = 0.0.0.0
agentDw.sources.SrcUbaAppActionLog.channels = ChUbaAppActionLog

# UbaAppActionLog channels 配置
agentDw.channels.ChUbaAppActionLog.type = file
agentDw.channels.ChUbaAppActionLog.checkpointDir = /var/log/flume/uba_app_action/checkpoint
agentDw.channels.ChUbaAppActionLog.dataDirs = /var/log/flume/uba_app_action/data
agentDw.channels.ChUbaAppActionLog.threads = 2

# UbaAppActionLog sinks 配置
agentDw.sinks.SinkUbaAppActionLog.channel = ChUbaAppActionLog
agentDw.sinks.SinkUbaAppActionLog.type = thrift
agentDw.sinks.SinkUbaAppActionLog.hostname = log1
agentDw.sinks.SinkUbaAppActionLog.port = 18889
```


### 3. flume 多端口写入写出  

``` sh
# Name the components on this agent
a1.sources = r1 r2
a1.sinks = k1 k2
a1.channels = c1 c2

# 定义两个 sources 分别来自 syslogtcp 的 44441 和 44442 端口
a1.sources.r1.type = syslogtcp
a1.sources.r1.bind = localhost
a1.sources.r1.port = 44441

a1.sources.r2.type = syslogtcp
a1.sources.r2.bind = localhost
a1.sources.r2.port = 44442

# 定义两个 sinks 分别写入到 hdfs 中的不同目录下。
a1.sinks.k1.type = hdfs
a1.sinks.k1.hdfs.path = hdfs://localhost:9000/flume/events1/%y-%m-%d/
a1.sinks.k1.hdfs.fileType=DataStream
a1.sinks.k1.hdfs.writeFormat=Text
a1.sinks.k1.hdfs.filePrefix = events-
a1.sinks.k1.hdfs.rollCount= 0
a1.sinks.k1.hdfs.rollSize= 0
a1.sinks.k1.hdfs.rollInterval= 300
a1.sinks.k1.hdfs.batchSize = 10000
a1.sinks.k1.hdfs.useLocalTimeStamp = true

a1.sinks.k2.type = hdfs
a1.sinks.k2.hdfs.path = hdfs://localhost:9000/flume/events2/%y-%m-%d/
a1.sinks.k2.hdfs.fileType=DataStream
a1.sinks.k2.hdfs.writeFormat=Text
a1.sinks.k2.hdfs.filePrefix = events-
a1.sinks.k2.hdfs.rollCount= 0
a1.sinks.k2.hdfs.rollSize= 0
a1.sinks.k2.hdfs.rollInterval= 300
a1.sinks.k2.hdfs.batchSize = 10000
a1.sinks.k2.hdfs.useLocalTimeStamp = true


# 定义两个 channels 因为需要两个 sinks 进行消费
a1.channels.c1.type = memory
a1.channels.c1.capacity = 1000
a1.channels.c1.transactionCapacity = 100

a1.channels.c2.type = memory
a1.channels.c2.capacity = 1000
a1.channels.c2.transactionCapacity = 100

# Bind the source and sink to the channel
a1.sources.r1.channels = c1
a1.sources.r2.channels = c2

a1.sinks.k1.channel = c1
a1.sinks.k2.channel = c2
```

### 4. flume 一个 sources 多个 sinks

- 这里举例 flume 同时写入到 hdfs 和 kafka   

``` sh
a2.sources = r1
a2.sinks = k1 k2
a2.channels = c1 c2

# 定义数据源来自 spooldir
a2.sources.r1.type = spooldir
a2.sources.r1.channels = c1
a2.sources.r1.spoolDir = ~/work/test/flume_source
a2.sources.r1.fileHeader = true

# 写入到 kafka 端口为 9092 server 中的 test topic 中
a2.sinks.k1.type = org.apache.flume.sink.kafka.KafkaSink
a2.sinks.k1.channel = c1
a2.sinks.k1.kafka.topic = test
a2.sinks.k1.kafka.bootstrap.servers = localhost:9092
a2.sinks.k1.kafka.flumeBatchSize = 20
a2.sinks.k1.kafka.producer.acks = 1
a2.sinks.k1.kafka.producer.linger.ms = 1
#a2.sinks.ki.kafka.producer.compression.type = snappy

# 写入到 hdfs 目录下
a2.sinks.k2.type = hdfs
a2.sinks.k2.channel = c2
a2.sinks.k2.hdfs.path = hdfs://localhost:9000/flume/events/%y-%m-%d/%H%M/%S
a2.sinks.k2.hdfs.fileType=DataStream
a2.sinks.k2.hdfs.writeFormat=Text
a2.sinks.k2.hdfs.filePrefix = events-
a2.sinks.k2.hdfs.round = true
a2.sinks.k2.hdfs.roundValue = 10
a2.sinks.k2.hdfs.roundUnit = minute
a2.sinks.k2.hdfs.useLocalTimeStamp = true

# 两个 sink 就要对应 两个 channels
a2.channels.c1.type = memory
a2.channels.c1.capacity = 1000
a2.channels.c1.transactionCapacity = 100

a2.channels.c2.type = memory
a2.channels.c2.capacity = 1000
a2.channels.c2.transactionCapacity = 100
# Bind the source and sink to the channel
a2.sources.r1.channels = c1 c2
```


### 5. 负载均衡和故障转移

``` sh

# 配置需要处理的 srouce channels slinks
agentDw.sources = SrcDwAccessLog
agentDw.channels = ChDwAccesslog
agentDw.sinks = SinkDwAccesslog1 SinkDwAccesslogKafka

# 对所有的出口 slink 做 Load balancing Sink Processor 负载平衡处理器配置, 防止远端单点故障
agentDw.sinkgroups = SinkGroupSinkDwAccesslog


# --- DwAccessLog  配置 Start --- #

# SrcDwAccessLog source 配置
agentDw.sources.SrcDwAccessLog.type = syslogudp
agentDw.sources.SrcDwAccessLog.port = 10004
agentDw.sources.SrcDwAccessLog.host = 0.0.0.0
agentDw.sources.SrcDwAccessLog.channels = ChDwAccesslog

# SrcDwAccessLog Interceptors 配置
agentDw.sources.SrcDwAccessLog.interceptors = in1 in2
# SrcDwAccessLog Search and Replace Interceptor 配置
agentDw.sources.SrcDwAccessLog.interceptors.in1.type = search_replace
# 正则替换 ^[a-zA-Z_]+\:[ ]{1} 或者 ^lb_access\:[ ]{1}
agentDw.sources.SrcDwAccessLog.interceptors.in1.searchPattern = ^[a-zA-Z_]+\:[ ]{1}
agentDw.sources.SrcDwAccessLog.interceptors.in1.replaceString =
agentDw.sources.SrcDwAccessLog.interceptors.in1.charset = UTF-8
# SrcDwAccessLog Timestamp Interceptor 配置
agentDw.sources.SrcDwAccessLog.interceptors.in2.type = timestamp
agentDw.sources.SrcDwAccessLog.interceptors.in2.preserveExisting = true

# ChDwAccesslog channels 配置
agentDw.channels.ChDwAccesslog.type = file
agentDw.channels.ChDwAccesslog.checkpointDir = /var/log/flume/dw_access_log/checkpoint
agentDw.channels.ChDwAccesslog.dataDirs = /var/log/flume/dw_access_log/data
agentDw.channels.ChDwAccesslog.capacity = 10000
agentDw.channels.ChDwAccesslog.threads = 2

# SinkDwAccesslog To File sinks 配置
#agentDw.sinks.SinkDwAccesslog.channel = ChDwAccesslog
#agentDw.sinks.SinkDwAccesslog.type = file_roll
#agentDw.sinks.SinkDwAccesslog.sink.directory = /var/log/flume/dw_access_log/test

# SinkDwAccesslogKafka To Kafka 配置
#agentDw.sinks.SinkDwAccesslogKafka.channel = ChDwAccesslog
#agentDw.sinks.SinkDwAccesslogKafka.type = org.apache.flume.sink.kafka.KafkaSink
#agentDw.sinks.SinkDwAccesslogKafka.kafka.bootstrap.servers = bi4:9092
#agentDw.sinks.SinkDwAccesslogKafka.kafka.topic = accessLogTest
#agentDw.sinks.SinkDwAccesslogKafka.kafka.flumeBatchSize = 20
# 被接受的值为0(从不等待确认)，1(只等待领导)，-1(等待所有副本)将其设置为-1
#agentDw.sinks.SinkDwAccesslogKafka.kafka.producer.acks = 1
#agentDw.sinks.SinkDwAccesslogKafka.kafka.producer.linger.ms = 1
#agentDw.sinks.SinkDwAccesslogKafka.kafka.producer.compression.type = snappy

# SinkDwAccesslog0 To thrift sinks 配置
agentDw.sinks.SinkDwAccesslog0.channel = ChDwAccesslog
agentDw.sinks.SinkDwAccesslog0.type = thrift
agentDw.sinks.SinkDwAccesslog0.hostname = log0
agentDw.sinks.SinkDwAccesslog0.port = 18889
# 批量提交的个数
agentDw.sinks.SinkDwAccesslog0.batch-size = 1000
# 请求超时时间, 单位毫秒
agentDw.sinks.SinkDwAccesslog0.request-timeout = 20000
# 连接超时时间, 单位毫秒
agentDw.sinks.SinkDwAccesslog0.connect-timeout = 3000
# 重新连接 source 的时间, 单位秒, 用于后端负载均衡的轮询时间
# 重接秒数, 如在故障转移模式时, 当前的 slinks 故障时间超过阈值, 就会转移到另外一个 slinks 处理
agentDw.sinks.SinkDwAccesslog0.connection-reset-interval = 300

# SinkDwAccesslog1 To thrift sinks 配置
agentDw.sinks.SinkDwAccesslog1.channel = ChDwAccesslog
agentDw.sinks.SinkDwAccesslog1.type = thrift
agentDw.sinks.SinkDwAccesslog1.hostname = log1
agentDw.sinks.SinkDwAccesslog1.port = 18889
agentDw.sinks.SinkDwAccesslog1.batch-size = 1000
agentDw.sinks.SinkDwAccesslog1.request-timeout = 20000
agentDw.sinks.SinkDwAccesslog1.connect-timeout = 3000
agentDw.sinks.SinkDwAccesslog1.connection-reset-interval = 300

# SinkGroupSinkDwAccesslog 负载均衡
agentDw.sinkgroups.SinkGroupSinkDwAccesslog.sinks = SinkDwAccesslog0 SinkDwAccesslog1
agentDw.sinkgroups.SinkGroupSinkDwAccesslog.processor.type = load_balance
# random(随机) 和 round_robin(轮询)
agentDw.sinkgroups.SinkGroupSinkDwAccesslog.processor.selector = round_robin
# 当某个sink不可用时，就会被加入黑名单列表中，一定时间之后再从黑名单中移除，继续被尝试。
agentDw.sinkgroups.SinkGroupSinkDwAccesslog.processor.backoff = true
# 黑名单的最长有效期, 单位毫秒(这里配置是: 1800 S)
agentDw.sinkgroups.SinkGroupSinkDwAccesslog.processor.selector.maxTimeOut = 1800000  

# SinkGroupSinkDwAccesslog 故障转义
agentDw.sinkgroups.SinkGroupSinkDwAccesslog.sinks = SinkDwAccesslog0 SinkDwAccesslog1
agentDw.sinkgroups.SinkGroupSinkDwAccesslog.processor.type = failover
agentDw.sinkgroups.SinkGroupSinkDwAccesslog.processor.priority.SinkDwAccesslog0 = 1
agentDw.sinkgroups.SinkGroupSinkDwAccesslog.processor.priority.SinkDwAccesslog1 = 100
agentDw.sinkgroups.SinkGroupSinkDwAccesslog.processor.maxpenalty = 10000

# --- DwAccessLog  配置 End --- #

```


### 6. hdfs sink 参数说明

```sh
agentDw.sinks.SinkDwAccesslog1.type = hdfs
agentDw.sinks.SinkDwAccesslog1.channel = ChDwAccesslog
# 写入目录和文件规则
agentDw.sinks.SinkDwAccesslog1.hdfs.path = hdfs://uhadoop-ociicy-master2:8020/flume/dw_access_log/dw_access_log_%Y%m%d
agentDw.sinks.SinkDwAccesslog1.hdfs.filePrefix = dw_access_log
agentDw.sinks.SinkDwAccesslog1.hdfs.fileSuffix = .log

# 写入文件前缀规则
agentDw.sinks.SinkDwAccesslog1.hdfs.inUsePrefix = .
agentDw.sinks.SinkDwAccesslog1.hdfs.inUseSuffix = .tmp
# hdfs 舍弃时间
agentDw.sinks.SinkDwAccesslog1.hdfs.round = true
# 时间上进行”舍弃”的单位，包含：second,minute,hour
agentDw.sinks.SinkDwAccesslog1.hdfs.roundUnit = minute
# 时间上进行“舍弃”的值, 2015-10-16 17:38:59 会被舍弃成  17:35, 5 分钟内的时间都被舍弃掉
agentDw.sinks.SinkDwAccesslog1.hdfs.roundValue = 5

# 复制块, 用于控制滚动大小
agentDw.sinks.SinkDwAccesslog1.hdfs.minBlockReplicas=1
# hdfs 间隔多长将临时文件重命名成最终目标文件, 并新打开一个临时文件来写入数据, 0 则表示不根据时间来滚动文件 (单位秒)
agentDw.sinks.SinkDwAccesslog1.hdfs.rollInterval = 300
# hdfs 临时文件达到 rollSize 值, 则滚动成目标文件, 0 则表示不根据临时文件大小来滚动文件(单位：bytes)
agentDw.sinks.SinkDwAccesslog1.hdfs.rollSize = 0
#events 数据达到该数量时候，将临时文件滚动成目标文件, 0 则表示不根据 events 数据来滚动文件
agentDw.sinks.SinkDwAccesslog1.hdfs.rollCount = 0


# 写入格式(必须 Text)
agentDw.sinks.SinkDwAccesslog1.hdfs.writeFormat = Text

# 不压缩
# 文件格式 :  SequenceFile, DataStream(数据不会压缩输出文件) or CompressedStream(压缩 Stream)
agentDw.sinks.SinkDwAccesslog1.hdfs.fileType = DataStream  

# 设置压缩方式(当使用 CompressedStream 时,保存文件为压缩格式): gzip, bzip2, lzo, lzop, snappy
agentDw.sinks.SinkAccesslog.hdfs.codeC = snappy


# 这个拦截器写事件输出流的身体没有任何转换或修改, 事件标题将被忽略
agentDw.sinks.SinkSafeClickLog.sink.serializer = text
# 换行符追加到每个事件
agentDw.sinks.SinkSafeClickLog.sink.serializer.appendNewline = true


# 每个批次刷新到 HDFS上 的 events 数量
agentDw.sinks.SinkDwAccesslog1.hdfs.batchSize = 10000
# hdfs 打开、写、刷新、关闭的超时时间, 毫秒
agentDw.sinks.SinkDwAccesslog1.hdfs.callTimeout = 60000
# 当目前被打开的临时文件在该参数指定的时间（秒）内，没有任何数据写入，则将该临时文件关闭并重命名成目标文件
agentDw.sinks.SinkDwAccesslog1.hdfs.idleTimeout = 0
# 使用本地时间
agentDw.sinks.SinkDwAccesslog1.hdfs.useLocalTimeStamp = true




# batchsize < transactionCapacity || batchsize = transactionCapacity
```





## Souce

``` sh
# Sources http
agentDw.sources.srcHttp.type = http
agentDw.sources.srcHttp.port = 10102
agentDw.sources.srcHttp.bind = 0.0.0.0
agentDw.sources.srcHttp.handler = com.dw.flume.source.http.HTTPCustomHandler
agentDw.sources.srcHttp.threads = 8
agentDw.sources.srcHttp.selector.type = replicating
agentDw.sources.srcHttp.channels = chHdfs chKafka
## Interceptors
agentDw.sources.srcHttp.interceptors = in1
agentDw.sources.srcHttp.interceptors.in1.type = timestamp
agentDw.sources.srcHttp.interceptors.in1.preserveExisting = true



# Sources thrift
agentDw.sources.srcThrift.type = thrift
agentDw.sources.srcThrift.port = 10202
agentDw.sources.srcThrift.bind = 0.0.0.0
agentDw.sources.srcThrift.threads = 8
agentDw.sources.srcThrift.selector.type = replicating
agentDw.sources.srcThrift.channels = chHdfs chKafka
## Interceptors
agentDw.sources.srcThrift.interceptors = in1
agentDw.sources.srcThrift.interceptors.in1.type = timestamp
agentDw.sources.srcThrift.interceptors.in1.preserveExisting = true



# Sources avro
agentDw.sources.srcAvro.type = avro
agentDw.sources.srcAvro.port = 10302
agentDw.sources.srcAvro.bind = 0.0.0.0
agentDw.sources.srcAvro.threads = 8
# 压缩算法, 对应 slink 也要配置 compression-type = deflate, 默认 none
agentDw.sources.srcAvro.compression-type = deflate
agentDw.sources.srcAvro.selector.type = replicating
agentDw.sources.srcAvro.channels = chHdfs chKafka
## Interceptors
agentDw.sources.srcAvro.interceptors = in1
agentDw.sources.srcAvro.interceptors.in1.type = timestamp
agentDw.sources.srcAvro.interceptors.in1.preserveExisting = true
```



## Ch

``` sh
# Channels file
agentDw.channels.ch.type = file
agentDw.channels.ch.checkpointDir = /var/log/flume/Hdfs/checkpoint
agentDw.channels.ch.dataDirs = /var/log/flume/Hdfs/data
# channel 队列记录最大的 events 事件数量
agentDw.channels.ch.capacity = 100000000
# 最大文件的大小 128M
agentDw.channels.ch.maxFileSize = 134217728
# 最少需要多少空间 512M
agentDw.channels.ch.minimumRequiredSpace = 524288000
# 超时时间, channel 中没有数据最长等待时间
agentDw.channels.ch.keep-alive = 3
agentDw.channels.ch.threads = 8
# 事物最大条数
agentDw.channels.ch.transactionCapacity = 2000000

```



## Slink

``` doc
# Sinks http
agentDw.sinks.sinkHttp.type = http
agentDw.sinks.sinkHttp.channel = ch
agentDw.sinks.sinkHttp.endpoint = http://node1:10501
agentDw.sinks.sinkHttp.connectTimeout = 2000
agentDw.sinks.sinkHttp.requestTimeout = 2000
agentDw.sinks.sinkHttp.contentTypeHeader = Content-Type:application/json;charset=UTF-8
agentDw.sinks.sinkHttp.acceptHeader = Content-Type:application/json;charset=UTF-8
agentDw.sinks.sinkHttp.defaultBackoff = true
agentDw.sinks.sinkHttp.defaultRollback = true
agentDw.sinks.sinkHttp.defaultIncrementMetrics = false
agentDw.sinks.sinkHttp.backoff.200 = false
agentDw.sinks.sinkHttp.rollback.200 = false
agentDw.sinks.sinkHttp.incrementMetrics.200 = true
agentDw.sinks.sinkHttp.serializer.compressionCodec = snappy



# Sinks avro
agentDw.sinks.sinkKafka.type = avro
agentDw.sinks.sinkKafka.channel = ch
agentDw.sinks.sinkKafka.hostname = log1
agentDw.sinks.sinkKafka.port = 10302
# 连接超时(ms)
agentDw.sinks.sinkKafka.request-timeout = 20000
# 请求超时(ms)
agentDw.sinks.sinkKafka.connect-timeout = 20000
# 复位连接间隔
agentDw.sinks.sinkKafka.reset-connection-interval = 20000
# 压缩算法, 对应 source 也要配置 compression-type = deflate, 默认 none
agentDw.sinks.sinkKafka.compression-type = deflate
# 压缩级别, 0: 不压缩压缩, 1 ~ 9: 数越高越压缩略越高, 默认 6
agentDw.sinks.sinkKafka.compression-level = 6
# 一次获取 N 个 Event 提交.  batchsize < transactionCapacity || batchsize = transactionCapacity
agentDw.sinks.sinkKafka.batch-size = 100000



# Sinks hdfs
agentDw.sinks.sinkHdfs.type = hdfs
agentDw.sinks.sinkHdfs.channel = ch
agentDw.sinks.sinkHdfs.hdfs.path = hdfs://nameservice1/ods/safe_realtime_click_tmp/%Y%m%d/%H
agentDw.sinks.sinkHdfs.hdfs.filePrefix = from_the_stream
agentDw.sinks.sinkHdfs.hdfs.inUsePrefix = .
agentDw.sinks.sinkHdfs.hdfs.inUseSuffix = .tmp
agentDw.sinks.sinkHdfs.hdfs.round = true
agentDw.sinks.sinkHdfs.hdfs.roundValue = 5
agentDw.sinks.sinkHdfs.hdfs.roundUnit = minute
agentDw.sinks.sinkHdfs.hdfs.minBlockReplicas = 1
agentDw.sinks.sinkHdfs.hdfs.rollSize = 0
agentDw.sinks.sinkHdfs.hdfs.rollCount = 0
agentDw.sinks.sinkHdfs.hdfs.rollInterval = 600
agentDw.sinks.sinkHdfs.hdfs.writeFormat = Text
agentDw.sinks.sinkHdfs.hdfs.fileType = CompressedStream
agentDw.sinks.sinkHdfs.hdfs.codeC = gzip
agentDw.sinks.sinkHdfs.sink.serializer = text
agentDw.sinks.sinkHdfs.sink.serializer.appendNewline = true
agentDw.sinks.sinkHdfs.hdfs.callTimeout = 30000
agentDw.sinks.sinkHdfs.hdfs.idleTimeout = 0
agentDw.sinks.sinkHdfs.hdfs.useLocalTimeStamp = true
# 一次获取 N 个 Event 提交.  batchsize < transactionCapacity || batchsize = transactionCapacity
agentDw.sinks.sinkHdfs.hdfs.batchSize = 100000



# Sinks kafka
agentDw.sinks.sinkKafka.type = org.apache.flume.sink.kafka.KafkaSink
agentDw.sinks.sinkKafka.channel = ch
agentDw.sinks.sinkKafka.kafka.bootstrap.servers = node4:9092,node5:9092,node6:9092
agentDw.sinks.sinkKafka.kafka.topic = kafka_topic
# 为该通道中的所有事件指定一个Kafka分区ID, 默认情况下，如果此属性未设置，事件将由Kafka生产者的partition器分配
# agentDw.sinks.sinkStreamKafka.defaultPartitionId
# 有多少副本必须在其被认为成功写入之前确认一条消息。被接受的值为0(从不等待确认)性能最好，1(只等待领导)一般，-1(等待所有副本)最差但是不会丢数据
agentDw.sinks.sinkKafka.kafka.producer.acks = 1
agentDw.sinks.sinkKafka.kafka.producer.linger.ms = 1
agentDw.sinks.sinkKafka.kafka.producer.compression.type = snappy
# 一次获取 N 个 Event 提交.  batchsize < transactionCapacity || batchsize = transactionCapacity
agentDw.sinks.sinkKafka.kafka.flumeBatchSize = 100000
```

## Load

``` sh
# 负载均衡
agentDw.sinkgroups.sinkGroupsCollector.sinks = sinkHttp sinkKafka sinkHdfs
agentDw.sinkgroups.sinkGroupsCollector.processor.type = load_balance
agentDw.sinkgroups.sinkGroupsCollector.processor.selector = round_robin
agentDw.sinkgroups.sinkGroupsCollector.processor.backoff = true
agentDw.sinkgroups.sinkGroupsCollector.processor.selector.maxTimeOut = 1800000

```
