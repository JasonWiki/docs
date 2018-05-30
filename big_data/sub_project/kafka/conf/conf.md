``` sh
##### --- 系统参数 Start --- #####

# 唯一标识在集群中的 ID, 要求是正数。
broker.id=1

# 服务端口, 默认9092
port=9092

# 监听地址
host.name=0.0.0.0

# 处理网络请求的最大线程数, 1 - 32
num.network.threads=3

# 处理磁盘I/O的线程数 1 - 32
num.io.threads=8

# socket的发送缓冲区（SO_SNDBUF）
socket.send.buffer.bytes=102400

# socket的接收缓冲区 (SO_RCVBUF)
socket.receive.buffer.bytes=102400

# socket请求的最大字节数。为了防止内存溢出, message.max.bytes必然要小于
socket.request.max.bytes=104857600

# 一些后台线程数
background.threads=4

# 等待IO线程处理的请求队列最大数
queued.max.requests=500

##### --- 系统参数 End --- #####



##### --- Topic 参数 Start --- #####

# 每个 topic 的分区个数, 更多的 partition 会产生更多的 segment file, 1 - 1000
num.partitions=3

# 一个 topic 默认分区的 replication 个数, 不能大于集群中 broker 的个数
default.replication.factor=3

# 是否允许自动创建topic , 若是false, 就需要通过命令创建topic
auto.create.topics.enable=false

# 删除 topic
delete.topic.enable=true

# 消息体的最大大小, 单位是字节 1 - 2147483646
message.max.bytes=1000000

# Kafka0.8.1 之后默认将 offset 保存在kafka中而不是zookeeper中。具体的方法就是把offset提交作为一个topic保存起来。而这个字段就是设定该topic的分区数的，默认是50，由属性offsets.topic.num.partitions指定。由于目前Kafka并不支持动态修改此值，因此推荐在生产环境下一次性地调大该值，比如100~200
offsets.topic.num.partitions=100

##### --- Topic 参数 End --- #####



##### --- ZooKeeper 参数 Start --- #####

# zookeeper 地址
zookeeper.connect=hostname:2181,hostname:2181,hostname:2181

# zookeeper 连接超时时间, 1 - 2147483646
zookeeper.connection.timeout.ms=6000

# zookeeper 会话超时时间, 1 - 2147483646
zookeeper.session.timeout.ms=30000

# 1 - 2147483646
zookeeper.sync.time.ms=2000

##### --- ZooKeeper 参数 End --- #####



##### --- 日志参数 Start --- #####

# 1 - 2147483646
log.cleaner.delete.retention.ms=86400000

# 是否日志开启压缩
log.cleaner.enable=false

# 对于压缩的日志保留的最长时间
log.cleaner.delete.retention.ms=1 day

# 日志清理策略 (delete|compact)
log.cleanup.policy=delete

# 日志保存时间 (hours|minutes), 默认为 7 天（168小时）。超过这个时间会根据 policy 处理数据。bytes 和 minutes 无论哪个先达到都会触发。
log.retention.hours=168

# 日志片段文件的检查周期, 查看它们是否达到了删除策略的设置（log.retention.hours或log.retention.bytes）
log.retention.check.interval.ms=300000

# 日志数据存储的最大字节数。超过这个时间会根据 policy 处理数据。
log.retention.bytes=1073741824

# 控制日志 segment 文件的大小, 超出该大小则追加到一个新的日志 segment 文件中（-1表示没有限制）, 1 - 2147483646
log. .bytes=1048576

# 当达到下面时间, 会强制新建一个segment
log.roll.hours=24*7

##### --- 日志参数 End --- #####



##### --- 副本参数 Start --- #####

# 是否自动平衡 broker 之间的分配策略
auto.leader.rebalance.enable=true

# leader的不平衡比例, 若是超过这个数值, 会对分区进行重新的平衡
leader.imbalance.per.broker.percentage=10

# 检查leader是否不平衡的时间间隔
leader.imbalance.check.interval.seconds=300

# 客户端保留offset信息的最大空间大小
offset.metadata.max.bytes=1024

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
