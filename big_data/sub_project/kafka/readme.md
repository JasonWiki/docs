# Kafka

## 一、Kafka 架构

- [架构图](https://www.processon.com/view/link/56b33557e4b0df880d861fdc)

- Broker : Kafka集群包含一个或多个服务器，这种服务器被称为broker

- Topic : 每条发布到Kafka集群的消息都有一个类别，这个类别被称为Topic。（物理上不同Topic的消息分开存储，逻辑上一个Topic的消息虽然保存于一个或多个broker上但用户只需指定消息的Topic即可生产或消费数据而不必关心数据存于何处）
　　
- Partition : Parition是物理上的概念，每个Topic包含一个或多个Partition.
　　
- Producer : 负责发布消息到Kafka broker
　　
- Consumer : 消息消费者，向Kafka broker读取消息的客户端。

- Consumer Group : 每个Consumer属于一个特定的Consumer Group（可为每个Consumer指定group name，若不指定group name则属于默认的group）。
 - Kafka 保证同一 Consumer Group 中只有一个 Consumer 会消费某条消息


### 1. Topic & Partition


- Topic 在逻辑上可以被认为是一个 queue ，每条消费都必须指定它的 Topic，可以简单理解为必须指明把这条消息放进哪个 queue 里。为了使得 Kafka 的吞吐率可以线性提高，物理上把 Topic 分成一个或多个 Partition，每个 Partition 在物理上对应一个文件夹，该文件夹下存储这个 Partition 的所有消息和索引文件。

- Broker 是无状态的，它不需要标记哪些消息被哪些消费过，也不需要通过 broker 去保证同一个 Consumer Group 只有一个 Consumer 能消费某一条消息，因此也就不需要锁机制

- Consumer Group 保留一些 metadata 信息: 当前消费的消息的 offset, 这个 offset 由 Consumer 控制, Consumer 会在消费完一条消息后递增该 offset

- Consumer 也可将 offset 设成一个较小的值，重新消费一些消息。因为 offset 由 Consumer 控制，所以 Kafka broker 是无状态的



### 2. Producer & Paritition

- Producer: 发送消息到 broker 时，会根据 Paritition 机制选择将其存储到哪一个 Partition。Partition 机制设置合理，所有消息可以均匀分布到不同的 Partition 里，这样就实现了负载均衡。

- Paritition: 在发送一条消息时，可以指定这条消息的 key，Producer 根据这个 key 和 Partition 机制来判断应该将这条消息发送到哪个 Parition。

- Paritition 机制可以通过指定 Producer 的 paritition.class 这一参数来指定，该 class 必须实现 kafka.producer.Partitioner 接口
 - 如果 key 可以被解析为整数则将对应的整数与 Partition 总数取余，该消息会被发送到该数对应的 Partition

- 每个 Parition 都会有个序号, 序号从 0 开始

- 分区规则规则

  ```
kafka producer 发送消息的时候，可以指定 key，这个 key 的作用是为消息选择存储分区，key可以为空，当指定key且不为空的时候，kafka是根据key的hash值与分区数取模来决定数据存储到那个分区

  ```



### 3. Consumer Group 的消费策略

有两组 API 可供选择, 详细如下

#### 3.1 Consumer High Level API 高级消费者 API(推荐使用)

- Consumer High Level API
 - `同一 Topic 的一条消息只能被同一个 Consumer Group 内的一个 Consumer 消费，但多个 Consumer Group 可同时消费这一消息`
 - 实现广播 : 每个 Consumer 都有一个独立的 Group 组
 - 实现单播 : 所有 Consumer 在同一个 Group 里
- High Level Consumer
 - 从 Topic 的某个 Partition 读取的最后一条消息的 offset 存于 Zookeeper 中
 - offset 基于客户程序提供给 Kafka 的名字来保存，这个名字被称为 Consumer Group`(重点, 在消费的时候, 是由 Consumer 客户端配置这个 Consumer 数据哪个 Consumer Groups)`
 - Consumer Group 是整 个Kafka 集群全局的，而非某个 Topic 的。每一个 High Level Consumer 实例都属于一个 Consumer Group，若不指定则属于默认的 Group。

- High Level Consumer Rebalance 策略
 - Consumer 实例只会消费某一个或多个特定 Partition 的数据, 1 - N 关系
 - Partition 的数据只会被某一个特定的 Consumer 实例所消费, 1 - 1 关系
 - Consumer Group 中 Consumer 数量 < Topic Partition 数量, 则至少有一个 Consumer 会消费多个 Partition 的数据
 - Consumer Group 中 Consumer 数量 = Topic Partition 数量, 则正好一个 Consumer 消费一个 Partition 的数据
 - Consumer Group 中 Consumer 数量 > Topic Partition 数量, 则部分 Consumer 无法消费该 Topic 下任何一条消息

#### 3.2 Low Level Consumer API

- 使用 Low Level Consumer (Simple Consumer) 的主要原因是，用户希望比 Consumer Group 更好的控制数据的消费。
 - 同一条消息读多次
 - 只读取某个 Topic 的部分 Partition
 - 管理事务，从而确保每条消息被处理一次，且仅被处理一次

- 与 Consumer Group 相比，Low Level Consumer 要求用户做大量的额外工作。
 - 必须在应用程序中跟踪 offset，从而确定下一条应该消费哪条消息
 - 应用程序需要通过程序获知每个Partition的Leader是谁
 - 必须处理Leader的变化

- 使用 Low Level Consumer的 一般流程如下
 - 查找到一个“活着”的Broker，并且找出每个 Partition 的 Leader
 - 找出每个 Partition 的 Follower
 - 定义好请求，该请求应该能描述应用程序需要哪些数据
 - Fetch数据
 - 识别Leader的变化，并对之作出必要的响应
