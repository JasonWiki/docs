## server

``` sh
############################# Server Basics #############################

# The id of the broker. This must be set to a unique integer for each broker.
broker.id=1

############################# Socket Server Settings(Socket 服务器设置) #############################

# The address the socket server listens on. It will get the value returned from
# java.net.InetAddress.getCanonicalHostName() if not configured.
#   FORMAT:
#     listeners = listener_name://host_name:port
#   EXAMPLE:
#     listeners = PLAINTEXT://your.host.name:9092
# 应对无法消费的 BUG
listeners=PLAINTEXT://172.16.24.140:9092

# Hostname and port the broker will advertise to producers and consumers. If not set,
# it uses the value for "listeners" if configured.  Otherwise, it will use the value
# returned from java.net.InetAddress.getCanonicalHostName().
#advertised.listeners=PLAINTEXT://your.host.name:9092

# Maps listener names to security protocols, the default is for them to be the same. See the config documentation for more details
#listener.security.protocol.map=PLAINTEXT:PLAINTEXT,SSL:SSL,SASL_PLAINTEXT:SASL_PLAINTEXT,SASL_SSL:SASL_SSL

# The number of threads that the server uses for receiving requests from the network and sending responses to the network
# 处理网络请求的最大线程数, 1 - 32
num.network.threads=8

# The number of threads that the server uses for processing requests, which may include disk I/O
# 处理磁盘 I/O 的线程数 1 - 32
num.io.threads=8

# The send buffer (SO_SNDBUF) used by the socket server
# socket 的发送缓冲区（SO_SNDBUF）
socket.send.buffer.bytes=102400

# The receive buffer (SO_RCVBUF) used by the socket server
# socket 的接收缓冲区 (SO_RCVBUF)
socket.receive.buffer.bytes=102400

# The maximum size of a request that the socket server will accept (protection against OOM)
# socket 请求的最大字节数。为了防止内存溢出, message.max.bytes必然要小于
socket.request.max.bytes=104857600


############################# Log Basics(日志基础设置) #############################

# A comma separated list of directories under which to store log files
log.dirs=/var/log/kafka-logs

# The default number of log partitions per topic. More partitions allow greater
# parallelism for consumption, but this will also result in more files across
# the brokers.
# 每个 topic 的分区个数, 更多的 partition 会产生更多的 segment file, 1 - 1000
num.partitions=1

# The number of threads per data directory to be used for log recovery at startup and flushing at shutdown. 每个数据目录中用于在启动时恢复日志和在关闭时刷新日志的线程数
# This value is recommended to be increased for installations with data dirs located in RAID array. 对于安装 RAID 阵列中的数据 dirs，建议增加此值
# 由于索引文件的数量增加，在一些日志段数量较大的 broker (例如>15K)上，broker 启动期间的日志加载过程可能更长。根据我们的实验，设置 num.recovery.threads.per.data.dir 可能会减少日志加载时间
num.recovery.threads.per.data.dir=1

############################# Internal Topic Settings(内部 Topic 设置)  #############################
# The replication factor for the group metadata internal topics "__consumer_offsets" and "__transaction_state"
# For anything other than development testing, a value greater than 1 is recommended for to ensure availability such as 3.
# topic 复制因子, 在命令行创建是使用命令指定
offsets.topic.replication.factor=1
transaction.state.log.replication.factor=1
transaction.state.log.min.isr=1

############################# Log Flush Policy(日志刷新策略) #############################

# Messages are immediately written to the filesystem but by default we only fsync() to sync
# the OS cache lazily. The following configurations control the flush of data to disk.
# There are a few important trade-offs here:
#    1. Durability: Unflushed data may be lost if you are not using replication.
#    2. Latency: Very large flush intervals may lead to latency spikes when the flush does occur as there will be a lot of data to flush.
#    3. Throughput: The flush is generally the most expensive operation, and a small flush interval may lead to excessive seeks.
# The settings below allow one to configure the flush policy to flush data after a period of time or
# every N messages (or both). This can be done globally and overridden on a per-topic basis.

# The number of messages to accept before forcing a flush of data to disk
# 在将数据刷新到磁盘之前要接受的消息数量
#log.flush.interval.messages=10000

# The maximum amount of time a message can sit in a log before we force a flush
# 在强制刷新之前，消息可以在日志中保存的最长时间
#log.flush.interval.ms=1000

############################# Log Retention Policy(日志保留策略) #############################

# The following configurations control the disposal of log segments. The policy can
# be set to delete segments after a period of time, or after a given size has accumulated.
# A segment will be deleted whenever *either* of these criteria are met. Deletion always happens
# from the end of the log.

# The minimum age of a log file to be eligible for deletion due to age
# 日志保留时间 168 H
log.retention.hours=168

# A size-based retention policy for logs. Segments are pruned from the log unless the remaining
# segments drop below log.retention.bytes. Functions independently of log.retention.hours.
# 日志数据存储的最大字节数。超过这个时间会根据 policy 处理数据。
#log.retention.bytes=1073741824

# The maximum size of a log segment file. When this size is reached a new log segment will be created.
# 单个日志文件的最大大小
log.segment.bytes=1073741824

# The interval at which log segments are checked to see if they can be deleted according
# to the retention policies
# 日志清理程序检查任何日志是否符合删除条件的频率(毫秒)
log.retention.check.interval.ms=300000

############################# Zookeeper #############################

# Zookeeper connection string (see zookeeper docs for details).
# This is a comma separated host:port pairs, each corresponding to a zk
# server. e.g. "127.0.0.1:3000,127.0.0.1:3001,127.0.0.1:3002".
# You can also append an optional chroot string to the urls to specify the
# root directory for all kafka znodes.
zookeeper.connect=master1:2181,master2:2181,node1:2181

# Timeout in ms for connecting to zookeeper
zookeeper.connection.timeout.ms=10000


############################# Group Coordinator Settings(组织协调器设置) #############################

# The following configuration specifies the time, in milliseconds, that the GroupCoordinator will delay the initial consumer rebalance.
# The rebalance will be further delayed by the value of group.initial.rebalance.delay.ms as new members join the group, up to a maximum of max.poll.interval.ms.
# The default value for this is 3 seconds.
# We override this to 0 here as it makes for a better out-of-the-box experience for development and testing.
# However, in production environments the default value of 3 seconds is more suitable as this will help to avoid unnecessary, and potentially expensive, rebalances during application startup.
group.initial.rebalance.delay.ms=0

```
