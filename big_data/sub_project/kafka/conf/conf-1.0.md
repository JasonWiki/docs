``` sh
##### --- brokerconfigs Start --- #####

# 唯一标识在集群中的 ID, 要求是正数。
broker.id=1

# 在服务器上启用自动代理ID生成。启用时，应该检查为 reserved.broker.max.id 配置的值
broker.id.generation.enable=true

# 监听地址
host.name=0.0.0.0

# 服务端口, 默认9092
port=9092

# 日志数据保存的目录。如果未设置，则使用log.dir中的
log.dirs=/var/log/kafka-log

# zookeeper 地址
zookeeper.connect=hostname:2181,hostname:2181,hostname:2181

# 客户端等待与 zookeeper 建立连接的最长时间。如果未设置，则使用 zookeeper.session.timeout.ms 中的值
zookeeper.connection.timeout.ms=6000

# zookeeper 会话超时时间, 1 - 2147483646
zookeeper.session.timeout.ms=30000

# zookeeper 的 follower 同 leader 的同步时间, 1 - 2147483646
zookeeper.sync.time.ms=2000


# 是否允许自动创建 topic , 若是false, 就需要通过命令创建 topic
auto.create.topics.enable=false

# 启用自动领导者平衡。后台线程会定期检查并触发领导平衡
auto.leader.rebalance.enable=true

# 用于各种后台处理任务的线程数
background.threads=10

# 日志压缩类型, 'gzip'，'snappy'，'lz4'）
compression.type=snappy

# 是否开启删除主题
delete.topic.enable=true


# 检查 leader 是否不平衡的时间间隔, 秒
leader.imbalance.check.interval.seconds=300

# leader 的不平衡比例, 若是超过这个数值, 会对分区进行重新的平衡
leader.imbalance.per.broker.percentage=10


# 在将消息刷新到磁盘之前，在日志分区上累积的消息数量
log.flush.interval.messages=9223372036854775807

# 日志刷新器检查是否需要将任何日志刷新到磁盘的频率（以毫秒为单位）
log.flush.scheduler.interval.ms=9223372036854775807

# 任何主题中的消息在刷新到磁盘之前都保留在内存中的最长时间（以毫秒为单位）。如果未设置，则使用log.flush.scheduler.interval.ms中的值
log.flush.interval.ms= 默认空

# 更新作为日志恢复点的最后一次刷新的持久记录的频率
log.flush.offset.checkpoint.interval.ms=60000

# 更新记录起始偏移量的持续记录的频率
log.flush.start.offset.checkpoint.interval.ms=60000


# 日志数据存储的最大字节数。超过这个时间会根据 policy 处理数据。
log.retention.bytes=1073741824

# 保留日志文件的小时数（以小时为单位），大写为log.retention.ms属性, 默认为 7 天（168小时）
log.retention.hours=168

# 保留日志文件的分钟数（以分钟为单位），次要 log.retention.ms 属性。如果未设置，则使用log.retention.hours中的值
log.retention.minutes= 默认空

# 保留日志文件的毫秒数（以毫秒为单位），如果未设置，则使用 log.retention.minutes 中的值
log.retention.ms= 默认空


# 新日志段转出之前的最长时间（以毫秒为单位）。如果未设置，则使用log.roll.hours中的值
log.roll.ms= 默认空

# 当达到下面时间, 会强制新建一个 segment （小时），次于log.roll.ms属性
log.roll.hours=168

# 单个日志文件的最大大小， 字节
log.segment.bytes=1073741824

# 从文件系统中删除文件之前等待的时间
log.segment.delete.delay.ms=60000


# 启用日志清理策略
log.cleaner.enable=true

# 对于压缩的日志保留的最长时间, 查看它们是否达到了删除策略的设置（log.retention.hours或log.retention.bytes）
log.retention.check.interval.ms=300000

# 删除记录保留多久 1 - 2147483646
log.cleaner.delete.retention.ms=86400000

# 用于日志清理的后台线程数量
log.cleaner.threads=1

# 用于日志清理程序 I/O 缓冲区的整个内存跨越所有清理程序线程
log.cleaner.io.buffer.size=524288

# 日志中的脏日志与总日志的最小比率，以符合清理的条件
log.cleaner.min.cleanable.ratio=0.5

# 消息在日志中保持未压缩的最短时间。仅适用于正在压缩的日志
log.cleaner.min.compaction.lag.ms

# 超出保留窗口的段的默认清理策略。以逗号分隔的有效策略列表。有效的政策是：delete | compact
log.cleanup.policy=delete


# 偏移索引的最大大小（以字节为单位）
log.index.size.max.bytes=10485760

# 消息体的最大大小, 单位是字节 1 - 2147483646, 可以使用主题级别 max.message.bytes 配置为每个主题设置
message.max.bytes=1000012


# 每个 topic 的分区个数, 更多的 partition 会产生更多的 segment file, 1 - 1000
num.partitions=3

# 服务器用于处理请求的线程数，可能包括磁盘 I/O,  1 - 32
num.io.threads=8

# 服务器用于处理网络请求的最大线程数, 1 - 32
num.network.threads=3

# 每个数据目录的线程数，用于启动时的日志恢复和关闭时的刷新
num.recovery.threads.per.data.dir=1

# 用于从源代理复制消息的读取器线程数。增加此值可以提高跟随者代理中的 I/O 并行度
num.replica.fetchers=2


# 客户端保留offset信息的最大空间大小
offset.metadata.max.bytes=4096

# 偏移提交将被延迟，直到偏移量主题的所有副本都收到提交或达到此超时。这与生产者请求超时类似
offsets.commit.timeout.ms=5000

# 检查失效偏移的频率
offsets.retention.check.interval.ms=600000

# 比这个保留期更早的偏置将被丢弃, 分钟
offsets.retention.minutes=1440

# Kafka0.8.1 之后默认将 offset 保存在 kafka 中而不是zookeeper中。
# 把 offset 提交作为一个 topic 保存起来，而这个字段就是设定该 topic 的分区数的，默认是 50，由于目前 Kafka 并不支持动态修改此值，因此推荐在生产环境下一次性地调大该值，比如100~200
offsets.topic.num.partitions=200

# 偏移量主题的复制因子
offsets.topic.replication.factor=3


# 等待IO线程处理的请求队列最大数
queued.max.requests=500

# socket 的发送缓冲区（SO_SNDBUF）
socket.send.buffer.bytes=102400

# socket 的接收缓冲区 (SO_RCVBUF)
socket.receive.buffer.bytes=102400

# socket 请求的最大字节数。为了防止内存溢出, message.max.bytes 必然要小于
socket.request.max.bytes=104857600


##### --- brokerconfigs End --- #####











##### --- Topic 参数 Start --- #####



# 一个 topic 默认分区的 replication 个数, 不能大于集群中 broker 的个数
default.replication.factor=3

# 是否允许自动创建topic , 若是false, 就需要通过命令创建topic
auto.create.topics.enable=false







##### --- Topic 参数 End --- #####







##### --- 日志参数 Start --- #####












# 控制日志 segment 文件的大小, 超出该大小则追加到一个新的日志 segment 文件中（-1表示没有限制）, 1 - 2147483646
log. .bytes=1048576



##### --- 日志参数 End --- #####



##### --- 副本参数 Start --- #####

# 是否自动平衡 broker 之间的分配策略
auto.leader.rebalance.enable=true







# replicas 每次获取数据的最大字节数, 1 - 2147483646
replica.fetch.max.bytes=1048576

##### --- 副本参数 End --- #####



##### --- 生产者参数 Start --- #####

# 核心的配置包括：
# metadata.broker.list
# request.required.acks
# producer.type
# serializer.class

# 消费者获取消息元信息(topics, partitions and replicas)的地址, 配置格式是：host1:port1,host2:port2, 也可以在外面设置一个 vip
metadata.broker.list

#消息的确认模式
# 0：不保证消息的到达确认, 只管发送, 低延迟但是会出现消息的丢失, 在某个server失败的情况下, 有点像TCP
# 1：发送消息, 并会等待leader 收到确认后, 一定的可靠性
# -1：发送消息, 等待leader收到确认, 并进行复制操作后, 才返回, 最高的可靠性
request.required.acks=0

# 消息发送的最长等待时间
request.timeout.ms=10000

# socket的缓存大小
send.buffer.bytes=100*1024

# key的序列化方式, 若是没有设置, 同serializer.class
key.serializer.class

# 分区的策略, 默认是取模
partitioner.class=kafka.producer.DefaultPartitioner

# 消息的压缩模式, 默认是 none, 可以有 gzip 和 snappy
compression.codec=none

# 可以针对默写特定的topic进行压缩
compressed.topics=null

# 消息发送失败后的重试次数
message.send.max.retries=3

# 每次失败后的间隔时间
retry.backoff.ms=100

# 生产者定时更新 topic 元信息的时间间隔, 若是设置为 0, 那么会在每个消息发送后都去更新数据
topic.metadata.refresh.interval.ms=600 * 1000

# 用户随意指定, 但是不能重复, 主要用于跟踪记录消息
client.id=""

# 异步模式下缓冲数据的最大时间。例如设置为100则会集合100ms内的消息后发送, 这样会提高吞吐量, 但是会增加消息发送的延时
queue.buffering.max.ms=5000

# 异步模式下缓冲的最大消息数, 同上
queue.buffering.max.messages=10000

# 异步模式下, 消息进入队列的等待时间。若是设置为0, 则消息不等待, 如果进入不了队列, 则直接被抛弃
queue.enqueue.timeout.ms=-1

# 异步模式下, 每次发送的消息数, 当 queue.buffering.max.messages 或 queue.buffering.max.ms 满足条件之一时 producer 会触发发送。
batch.num.messages=200

##### --- 生产者参数 End --- #####




##### --- 消费者参数 Start --- #####

# Consumer 端核心的配置是 group.id、zookeeper.connect
# 决定该 Consumer 归属的唯一组 ID, By setting the same group id multiple processes indicate that they are all part of the same consumer group.
group.id

# 消费者的ID, 若是没有设置的话, 会自增
consumer.id

# 一个用于跟踪调查的ID , 最好同group.id相同
client.id=<group_id>

# 当 zookeeper 中没有初始的 offset 时, 或者超出 offset 上限时的处理方式 。
# smallest ：重置为最小值
# largest: 重置为最大值
# anything else：抛出异常给consumer
auto.offset.reset=largest

# 是否自动提交: true时, Consumer会在消费消息后将offset同步到zookeeper, 这样当Consumer失败后, 新的consumer就能从zookeeper获取最新的offset
enable.auto.commit=true

props.put("group.id", "test");// cousumer的分组id
props.put("enable.auto.commit", "true");// 自动提交offsets
props.put("auto.commit.interval.ms", "1000");// 每隔1s，自动提交offsets
props.put("session.timeout.ms", "30000");// Consumer向集群发送自己的心跳，超时则认为Consumer已经死了，kafka会把它的分区分配给其他进程

# 自动提交的时间间隔
auto.commit.interval.ms=1000

# 用于消费的最大数量的消息块缓冲大小, 每个块可以等同于fetch.message.max.bytes中数值
queued.max.message.chunks=10

# 对于 zookeeper 集群的指定, 必须和 broker 使用同样的 zk 配置
zookeeper.connect=hostname:2182,hostname:2182,hostname:2182

# zookeeper 的心跳超时时间, 查过这个时间就认为是无效的消费者
zookeeper.session.timeout.ms=6000

# zookeeper 的等待连接时间
zookeeper.connection.timeout.ms=6000

# zookeeper 的 follower 同 leader 的同步时间
zookeeper.sync.time.ms=2000

# socket的超时时间, 实际的超时时间为 max.fetch.wait + socket.timeout.ms.
socket.timeout.ms=30 * 1000

# socket的接收缓存空间大小
socket.receive.buffer.bytes=64 * 1024

# 从每个分区fetch的消息大小限制
fetch.message.max.bytes=1024 * 1024

# 当有新的consumer加入到group时,将尝试reblance,将partitions的消费端迁移到新的consumer中, 该设置是尝试的次数
rebalance.max.retries=4

# 每次reblance的时间间隔
rebalance.backoff.ms=2000

# 每次重新选举leader的时间
refresh.leader.backoff.ms

# server 发送到消费端的最小数据, 若是不满足这个数值则会等待直到满足指定大小。默认为1表示立即接收。
fetch.min.bytes=1

# 若是不满足 fetch.min.bytes 时,等待消费端请求的最长等待时间
fetch.wait.max.ms=100

# 如果指定时间内没有新消息可用于消费, 就抛出异常, 默认-1表示不受限
consumer.timeout.ms=-1

##### --- 消费者参数 End --- #####
```
