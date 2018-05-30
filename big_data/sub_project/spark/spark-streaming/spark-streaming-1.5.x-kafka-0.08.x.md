# spark-streaming-kafka 整合

- spark-streaming 实时消费 Kafka 主题下的日志

## 一、介绍

- [streaming-kafka-integration 官方文档](https://spark.apache.org/docs/latest/streaming-kafka-integration.html)

### 1. Kafka 监控模式

```
1、Receiver-based Approach 模式
  即通过在 Receiver 里实现 Kafka consumer 的功能来接收消息数据;
  内存问题比较严重，因为它接受数据和处理数据是分开的。如果处理慢了，它还是不断的接受数据。容易把负责接受的节点给搞挂了。


2、Direct Approach (No Receivers) 模式
  不通过 Receiver，而是周期性的主动查询 Kafka 消息分区中的最新 offset 值，进而去定义在每个 batch 中需要处理的消息的 offset 范围
  Direct Approach 是直接把 Kafka 的 partition 映射成 RDD 里的 partition 。
  所以数据还是在 kafka 。只有在算的时候才会从 Kafka 里拿，不存在内存问题，速度也快。

```

## 二、实际案例

### 1. Receiver-based Approach 模式

``` java

import org.apache.spark.SparkConf
import org.apache.spark.streaming.Seconds
import org.apache.spark.streaming.StreamingContext
import org.apache.spark.streaming.kafka.KafkaUtils
import org.apache.spark.storage.StorageLevel
import org.apache.spark.storage._;


def run() : Unit = {
    // 消费的 kafka 主题集合
    val kafkaTopics = scala.collection.immutable.Map[String, Int]( "xxxx" -> 1)    // 消费主题, 分区数

    // 创建 sparkStreaming 上下文对象
    val conf = new SparkConf()
        conf.setMaster("local[2]")
        conf.setAppName("AccessLogStreaming")

    val ssc = new StreamingContext(conf, Seconds(Integer.parseInt(seconds)))

    /**
     * Kafka 监控模式
     * 1、Receiver-based Approach 模式
     *    即通过在 Receiver 里实现 Kafka consumer 的功能来接收消息数据;
     */

    // 整合 kafka 到 sparkStreaming
    val dstream = KafkaUtils.createStream(ssc, zookeeperQuorum, kafkaConsumerGroupId,  kafkaTopics, StorageLevel.MEMORY_AND_DISK_SER_2)


    println("-----开始-----")
    // 循环 RDD
    dstream.foreachRDD { (rdd, time) =>
      println(rdd + " --- " + time)

      // 循环分区
      rdd.foreachPartition { partitionIterator =>
          // 循环分区值
          partitionIterator.foreach(consumerRecord => {
              // 行值
              val curLine = consumerRecord._2


          })
      }
}
```
