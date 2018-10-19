# Kafka 安装

## 一、环境配置

- Java 1.8 环境配置
- Scala 2.11 环境配置
- zookeeper 3.4.6 安装

## 二、下载 与 安装

### 1. 下载与 Scala 相同的版本的 Kafka

- [Kafka 下载地址](http://kafka.apache.org/downloads.html)

``` sh

1. 下载
  Binary downloads
    Scala 2.11  - kafka_2.11-1.0.1.tgz  具体根据 Scala 版本决定

2. 解压
  tar -zxvf kafka_2.11-1.0.1.tgz

3. 环境配置
  vim ~/.bashrc
# Kafka
export KAFKA_HOME=/usr/local/kafka
export KAFKA_CONF_DIR=$KAFKA_HOME/config
export PATH=$KAFKA_HOME/bin:$PATH

```



## 三、操作

### 1. 启动其中一个 Kafka broker 代理 Server

- [启动文档](http://kafka.apache.org/documentation.html#quickstart)

``` sh
1. 修改配置文件
  cp $KAFKA_CONF_DIR/server.properties $KAFKA_CONF_DIR/server-1.properties
  vim $KAFKA_CONF_DIR/server-1.properties

  # 集群代理 id , 指定到同一个 zookeeper 集群的 kafka 集群代理 , 必须是唯一的
  broker.id=1

  # 监听地址和段口
  listeners=PLAINTEXT://[your ip]:9092

  # 配置 zookeeper 集群地址
  zookeeper.connect=zookeeper-hostname:2181

  # 日志目录地址
  log.dirs=/data/log/kafka/kafka-logs


2. 启动 Kafka broker 代理 (指定配置文件)
  1) 启动一个
    $KAFKA_HOME/bin/kafka-server-start.sh $KAFKA_CONF_DIR/server-1.properties &


    netstat -tunlp | grep 9092  查看是否启动

```

### 2. Kafka 使用

``` sh

1. Topic 主题
  kafka-topics.sh 参数)

  1) 创建 Topic
    kafka-topics.sh --create --zookeeper zookeeper-hostname:2181 --replication-factor 1 --partitions 1 --topic test

  2) 创建一个主题和 3 个复制因子(--replication-factor 不能超过 broker 服务的数量)
    kafka-topics.sh --create --zookeeper zookeeper-hostname:2181 --replication-factor 3 --partitions 3 --topic test

  3) 删除主题
    kafka-topics.sh --zookeeper zookeeper-hostname:2181 --delete --topic "clicki_info_topic"


2. 查看 Topic
  1) 查看 Topic 列表
    kafka-topics.sh --list --zookeeper zookeeper-hostname:2181

  2) 查看单个 Topic 详情
    kafka-topics.sh --describe --zookeeper zookeeper-hostname:2181 --topic test

      Leader : Leader 所在 Broker
      replicas : 副本所在 Broker
      Isr : 这个副本列表的子集目前活着的和以后的领导人


3. Topic Partitions 分区
  kafka-add-partitions.sh 参数)

  1) Topic 增加 partition 数目 kafka-add-partitions.sh
    kafka-add-partitions.sh --topic test --partition 2   --zookeeper  zookeeper-hostname:2181,zookeeper-hostname:2181 （为topic test增加2个分区）

  2）查询 topic 中 offset 的最大和最小值
    # 例如 -1 最大值
    kafka-run-class.sh kafka.tools.GetOffsetShell --broker-list broker-hostname:9092 --topic test --time -1

    参数:
      --time -1 最大 , -2 最小
    结果:
      主题 : 分区 : offset 最大值
      test:2:29
      test:1:27
      test:0:26


3. producer 生产者
  kafka-console-producer.sh 参数)

  1) 向 Topic 生产数据
    kafka-console-producer.sh --broker-list broker-hostname:9092 --topic test

  2) 从文件读取数据
    kafka-console-producer.sh --broker-list broker-hostname:9092 --topic test < file-input.txt


4. consumer 消费者
  kafka-console-consumer.sh 参数)
    --offset <String: consume offset>
      latest 最新, 默认
      earliest 最早

    --max-messages <Integer: num_messages> 退出前要使用的最大消息数。如果没有设置，消费是持续的
      100000

    --partition <Integer: partition> 要使用的分区。消耗从分区的末尾开始，除非指定了“—offset”。
      2

    --from-beginning  从最开始, 如果使用者还没有一个已建立的偏移量，那么从日志中出现的最早消息开始，而不是从最新消息开始。


  1) 从 Topic 消费数据
    # 从 zookeeper 中消费
    kafka-console-consumer.sh --zookeeper zookeeper:2181 --topic test --from-beginning

  2）从 broker 指定组消费数据
    kafka-console-consumer.sh --bootstrap-server broker-hostname:9092 --topic test --consumer-property group.id=test-consumer-group

  3）读取配置文件消费
    kafka-console-consumer.sh --bootstrap-server broker-hostname:9092 --topic test --consumer.config $KAFKA_HOME/config/consumer.properties

  4) 指定分区消费,从最新开始消费
    kafka-console-consumer.sh --bootstrap-server broker-hostname:9092 --topic test --consumer-property group.id=test-consumer-group --partition 2  --offset latest


5. consumer groups
  kafka-consumer-groups.sh 参数)

  1) 查看 consumer groups
   # 查看保存在 zookeeper 中的组
   kafka-consumer-groups.sh --zookeeper zookeeper:2181 --list

   # 查看保存在 broker 中的组
   kafka-consumer-groups.sh --bootstrap-server broker-hostname:9092 --list

   # 查看保存在 broker 中的 group 的 offset 信息, 当前消费到的 CURRENT-OFFSET 和 LOG-END-OFFSET 值
   kafka-consumer-groups.sh --bootstrap-server broker-hostname:9092 --describe --group test-consumer-group

  2) 重置 offset
   kafka-consumer-groups.sh  --bootstrap-server broker-hostname-1:9092,broker-hostname-2:9092,broker-hostname-3:9092  --reset-offsets --group test-consumer-group --topic test --to-offset  1


6. 使用卡夫卡连接到导入/导出数据
  1) 配置文件
    $KAFKA_CONF_DIR/connect-standalone.properties  卡夫卡连接的配置过程,包含常见的配置如卡夫卡代理连接和数据的序列化格式

    $KAFKA_CONF_DIR/connect-file-source.properties

  2) kafka-run-class.sh kafka.tools.ConsumerOffsetChecker --group accessLogBase  --topic accessLog  --zookeeper uhadoop-ociicy-master1:2181/kafka


7. kafka-configs 配置 Kafka 参数
```
