# Hadoop YARN 模式

- [集群模式概述](http://spark.apache.org/docs/latest/cluster-overview.html)
  - [Hadoop YARN](http://spark.apache.org/docs/latest/running-on-yarn.html)


## 一、提交任务

``` sh
1. 提交 streaming 任务
  spark-submit \
  --name SafeRealtimeClickStreaming \
  --queue root.realtime \
  --class com.dw2345.dw_realtime.module.safe.streaming.SafeRealtimeClickStreaming \
  --master yarn \
  --deploy-mode client \
  --driver-cores 2 \
  --driver-memory 4024M \
  --executor-memory 4024M \
  --num-executors 2 \
  --conf "spark.executorEnv.JAVA_HOME=/usr/local/jdk1.8" \
  --conf "spark.yarn.appMasterEnv.JAVA_HOME=/usr/local/jdk1.8" \
  --conf "spark.executor.extraJavaOptions=-XX:+UseConcMarkSweepGC" \
  --conf "spark.streaming.backpressure.enabled=true" \
  --conf "spark.streaming.kafka.maxRatePerPartition=10000" \
  --conf "spark.streaming.blockInterval=1000" \
  --conf "spark.driver.extraClassPath=${SBT_HOME}/ivy-repository/cache/mysql/mysql-connector-java/jars/mysql-connector-java-5.1.30.jar" \
  ~/app/dw_realtime/target/scala-2.11/dw_realtime.jar develop SafeRealtimeClickLog 10 /data/log/real_time/offset/SafeRealtimeClick

PS: 垃圾回收和内存使用
  通过打开 Java 的并发标识 - 清除收集器来减少 GC 引起的不可预测的长暂停,清除收集器总体上会耗费更多的资源,但是会较少暂停的发生
    --conf "spark.executor.extraJavaOptions=-XX:+UseConcMarkSweepGC -XX:+PrintGCDetails -XX:+PrintGCTimeStamps"

2. 动态部署
  spark-submit \
  --name SafeRealtimeClickStreaming \
  --queue root.realtime \
  --class com.dw2345.dw_realtime.module.safe.streaming.SafeRealtimeClickStreaming \
  --master yarn \
  --deploy-mode client \
  --driver-cores 2 \
  --driver-memory 4024M \
  --executor-memory 4024M \
```
