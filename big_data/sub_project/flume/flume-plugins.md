# Flume 插件

## 一. Flume HTTPSource 自定义 HTTP 源

### 测试

``` sh
curl -X POST -H "Content-Type:application/json;charset=UTF-8" -d '{"custom_ip":"192.168.1.1","host":"random_host.example.com"}' http://hostname:port
curl -X POST -H "Content-Type:application/json;charset=UTF-8" -d 'aaa,bbb,ccc' http://hostname:port
```

### Flume 中配置案例

``` sh

# 配置需要处理的 srouce channels sinks
agentDw.sources    =  srcSafeRealtimeClickLogHttp
agentDw.channels   =  chSafeRealtimeClickLogHttp
agentDw.sinks      =  sinkSafeRealtimeClickLogHttp1    sinkSafeRealtimeClickLogHttp2     sinkSafeRealtimeClickLogHttp3
# 对所有的出口 slink 做 Load balancing Sink Processor 负载平衡处理器配置, 防止远端单点故障
agentDw.sinkgroups =  sinkGroupSafeRealtimeClickLogHttp


# --- SafeRealtimeClickLogHttp 配置 Start --- #

# srcSafeRealtimeClickLogHttp source 配置
agentDw.sources.srcSafeRealtimeClickLogHttp.type = http
agentDw.sources.srcSafeRealtimeClickLogHttp.port = 10101
agentDw.sources.srcSafeRealtimeClickLogHttp.host = 0.0.0.0
agentDw.sources.srcSafeRealtimeClickLogHttp.handler = com.dw.flume.source.http.HTTPCustomHandler
agentDw.sources.srcSafeRealtimeClickLogHttp.threads = 6
agentDw.sources.srcSafeRealtimeClickLogHttp.channels = chSafeRealtimeClickLogHttp


# SrcUbaAppActionLog Timestamp Interceptor 配置
#agentDw.sources.srcSafeRealtimeClickLogHttp.interceptors = in1
#agentDw.sources.srcSafeRealtimeClickLogHttp.interceptors.in1.type = timestamp
#agentDw.sources.srcSafeRealtimeClickLogHttp.interceptors.in1.preserveExisting = true


# chSafeRealtimeClickLog channels 配置
agentDw.channels.chSafeRealtimeClickLogHttp.type = file
agentDw.channels.chSafeRealtimeClickLogHttp.checkpointDir = /var/log/flume/SafeRealtimeClickLogHttp/checkpoint
agentDw.channels.chSafeRealtimeClickLogHttp.dataDirs = /var/log/flume/SafeRealtimeClickLogHttp/data
agentDw.channels.chSafeRealtimeClickLogHttp.capacity = 1000000
agentDw.channels.chSafeRealtimeClickLogHttp.maxFileSize = 2146435071
agentDw.channels.chSafeRealtimeClickLogHttp.threads = 6


# kafka
#agentDw.sinks.sinkSafeRealtimeClickLogHttp1.type = org.apache.flume.sink.kafka.KafkaSink
#agentDw.sinks.sinkSafeRealtimeClickLogHttp1.channel = chSafeRealtimeClickLogHttp
#agentDw.sinks.sinkSafeRealtimeClickLogHttp1.kafka.bootstrap.servers = node4:9092,node5:9092,node6:9092
#agentDw.sinks.sinkSafeRealtimeClickLogHttp1.kafka.topic = SafeRealtimeClickLog
# 为该通道中的所有事件指定一个Kafka分区ID, 默认情况下，如果此属性未设置，事件将由Kafka生产者的partition器分配
# agentDw.sinks.sinkSafeRealtimeClickLogStreamKafka.defaultPartitionId
#agentDw.sinks.sinkSafeRealtimeClickLogHttp1.kafka.flumeBatchSize = 2000
#agentDw.sinks.sinkSafeRealtimeClickLogHttp1.kafka.producer.acks = -1
#agentDw.sinks.sinkSafeRealtimeClickLogHttp1.kafka.producer.linger.ms = 1



# sinkSafeRealtimeClickLogHttp1 To thrift sinks 配置
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.channel = chSafeRealtimeClickLogHttp
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.type = http
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.endpoint = http://node1:10501
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.connectTimeout = 2000
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.requestTimeout = 2000
# HTTP 报文头 Content-Type:application/json;charset=UTF-8  ||  Content-Type:text/plain;charset=UTF-8
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.contentTypeHeader = Content-Type:application/json;charset=UTF-8
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.acceptHeader = Content-Type:application/json;charset=UTF-8
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.defaultBackoff = true
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.defaultRollback = true
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.defaultIncrementMetrics = false
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.backoff.200 = false
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.rollback.200 = false
agentDw.sinks.sinkSafeRealtimeClickLogHttp1.incrementMetrics.200 = true

# --- SafeRealtimeClickLogHttp 配置 End --- #
```


## 二. Flume Interceptors 自定拦截器编写

- [源码](https://github.com/JasonWiki/dw-flume)

使用:

``` sh
方法 1 指定 --classpath
  flume-ng agent -c /etc/flume-ng/conf -f /etc/flume-ng/conf/flume.conf -n agentDw --classpath /usr/lib/flume-ng/lib/dw-flume-1.0.0.jar


方法 2 放到插件目录下
  /opt/cloudera/parcels/CDH/lib/flume-ng/lib

  /usr/lib/flume-ng/lib

  /usr/lib/flume-ng/plugins.d

  $FLUME_HOME/plugins.d
```

### Flume 中配置案例

- header 中的字段进行正则匹配分离出更多 header

``` sh
# 配置需要处理的 srouce channels slinks
agentDw.sources = SrcBrowserUseLog
agentDw.channels = ChBrowserUseLog
agentDw.sinks = SinkBrowserUseLog

# --- BrowserUseLog 配置 Start --- #

# SrcBrowserUseLog syslog source 配置
agentDw.sources.SrcBrowserUseLog.type = spooldir
agentDw.sources.SrcBrowserUseLog.spoolDir = /var/log/flume/browser_use/monitor
# 完成删除文件 immediate | never
agentDw.sources.SrcBrowserUseLog.deletePolicy = never
# 递归检测目录
agentDw.sources.SrcBrowserUseLog.recursiveDirectorySearch = true
# 上传文件的绝对路径
agentDw.sources.SrcBrowserUseLog.fileHeader = true
agentDw.sources.SrcBrowserUseLog.fileHeaderKey = file
# 上传的文件名
agentDw.sources.SrcBrowserUseLog.basenameHeader = true
agentDw.sources.SrcBrowserUseLog.basenameHeaderKey = basename
agentDw.sources.SrcBrowserUseLog.channels = ChBrowserUseLog

# SrcBrowserUseLog Interceptors 配置
agentDw.sources.SrcBrowserUseLog.interceptors = in1
# 自定义 header 拦截器
agentDw.sources.SrcBrowserUseLog.interceptors.in1.type = com.angejia.dw.flume.source.interceptors.RegexExtractorHeaderInterceptor$Builder
# 拦截则正则规则
agentDw.sources.SrcBrowserUseLog.interceptors.in1.regex = browser_use/monitor/([A-Za-z0-9/._-]+)/
agentDw.sources.SrcBrowserUseLog.interceptors.in1.extractorHeader = true
# 拦截的 header key (来自 source)
agentDw.sources.SrcBrowserUseLog.interceptors.in1.extractorHeaderKey = file
# browser_use/monitor/(.*)$ 正则匹配后的映射 s1 -> log_path , 以此类推
agentDw.sources.SrcBrowserUseLog.interceptors.in1.serializers = s1
agentDw.sources.SrcBrowserUseLog.interceptors.in1.serializers.s1.name = log_path


# ChBrowserUseLog channels 配置
agentDw.channels.ChBrowserUseLog.type = file
agentDw.channels.ChBrowserUseLog.checkpointDir = /var/log/flume/browser_use/checkpoint
agentDw.channels.ChBrowserUseLog.dataDirs = /var/log/flume/browser_use/data
agentDw.channels.ChBrowserUseLog.threads = 2

# ChBrowserUseLog To HDFS
agentDw.sinks.SinkBrowserUseLog.type = hdfs
agentDw.sinks.SinkBrowserUseLog.channel = ChBrowserUseLog
# 写入目录和文件规则,格式
agentDw.sinks.SinkBrowserUseLog.hdfs.path = hdfs://nameservice1/ods/browser_use/%{log_path}
agentDw.sinks.SinkBrowserUseLog.hdfs.filePrefix = log
agentDw.sinks.SinkBrowserUseLog.hdfs.fileSuffix = .log

# 写入文件前缀规则
agentDw.sinks.SinkBrowserUseLog.hdfs.inUsePrefix = .
agentDw.sinks.SinkBrowserUseLog.hdfs.inUseSuffix = .tmp
#
agentDw.sinks.SinkBrowserUseLog.hdfs.round = true
agentDw.sinks.SinkBrowserUseLog.hdfs.roundValue = 10
agentDw.sinks.SinkBrowserUseLog.hdfs.roundUnit = minute

# 复制块, 用于控制滚动大小
agentDw.sinks.SinkBrowserUseLog.hdfs.minBlockReplicas=1
agentDw.sinks.SinkBrowserUseLog.hdfs.rollSize = 0
agentDw.sinks.SinkBrowserUseLog.hdfs.rollCount = 0
agentDw.sinks.SinkBrowserUseLog.hdfs.rollInterval = 0

# 写入格式
agentDw.sinks.SinkBrowserUseLog.hdfs.writeFormat = Text
# 文件格式 :  SequenceFile, DataStream(数据不会压缩输出文件) or CompressedStream
agentDw.sinks.SinkBrowserUseLog.hdfs.fileType = DataStream
# 批处理达到这个上限, 写到 HDFS
agentDw.sinks.SinkBrowserUseLog.hdfs.batchSize = 100
# hdfs 打开、写、刷新、关闭的超时时间, 毫秒
agentDw.sinks.SinkBrowserUseLog.hdfs.callTimeout = 60000
# 多少秒没有写入就关闭这个文件, 0 不关闭
agentDw.sinks.SinkBrowserUseLog.hdfs.idleTimeout = 1
# 使用本地时间
agentDw.sinks.SinkBrowserUseLog.hdfs.useLocalTimeStamp = true

# --- BrowserUseLog 配置 End --- #

```
