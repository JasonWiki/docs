# spark 配置优化

## 一、内存溢出

### 1. Spark 中各个角色的 JVM 参数设置

``` sh
* JAVA JVM 有 2 种内存机制
  堆内存 Xms (给开发者), 非堆内存 PermSize (JVM 本身预留的)

1. Driver 角色 的 JVM 参数
  1) -Xms , -Xmx (堆内存)
  yarn-client 模式: 读取 spark-env 文件中的 SPARK_DRIVER_MEMORY 值，-Xmx，-Xms 值一样大小
  yarn-cluster 模式: 读取 spark-default.conf 文件中的 spark.driver.extraJavaOptions 对应的JVM参数值

  2) PermSize , MaxPermSize (非堆内存)
  yarn-client 模式: 读取 spark-class 文件中的 JAVA_OPTS="-XX:MaxPermSize=256m $OUR_JAVA_OPTS"
  yarn-cluster 模式: 读取 spark-default.conf 文件中的 spark.driver.extraJavaOptions 对应的 JVM 参数值

  3) GC 垃圾回收方式
  yarn-client 模式: 读取 spark-class 文件中的 JAVA_OPTS
  yarn-cluster 模式: 读取 spark-default.conf 文件中的 spark.driver.extraJavaOptions 对应的参数值

2. Executor 角色的 JVM 参数
  1) -Xms , -Xmx (堆内存)
  yarn-client 模式: 读取 spark-env 文件中的 SPARK_EXECUTOR_MEMORY 值，-Xmx，-Xms值一样大小
  yarn-cluster 模式: 读取 spark-default.conf 文件中的 spark.executor.extraJavaOptions 对应的JVM参数值。

  2) PermSize , MaxPermSize (非堆内存)
  两种模式都是读取的是 spark-default.conf 文件中的 spark.executor.extraJavaOptions 对应的JVM参数值。

  3) GC 垃圾回收方式
  两种模式都是读取的是 spark-default.conf 文件中的 spark.executor.extraJavaOptions 对应的JVM参数值

  4) Executor 角色，数目及所占 CPU 个数
    yarn-client 模式:
      Executor 数目由 spark-env 中的 SPARK_EXECUTOR_INSTANCES 指定
      每个实例的数目由 SPARK_EXECUTOR_CORES 指定

    yarn-cluster 模式:
      Executor 的数目由 spark-submit 工具的 --num-executors 参数指定，默认是2个实例
      每个 Executor 使用的 CPU 数目由 --executor-cores 指定，默认为1核

```

### 2、性能配置

``` sh
yarn-client 模式
  规则： SPARK_EXECUTOR_MEMORY < SPARK_DRIVER_MEMORY< yarn集群中每个nodemanager内存大小
  # EXECUTOR 角色的内存
  export SPARK_EXECUTOR_MEMORY=5000M
  # DRIVER 的内存
  export SPARK_DRIVER_MEMORY=6000M

```

### 3. 日志配置

``` conf

# Set everything to be logged to the console
log4j.rootCategory=WARN,console
log4j.appender.console=org.apache.log4j.ConsoleAppender
log4j.appender.console.target=System.err
log4j.appender.console.layout=org.apache.log4j.PatternLayout
log4j.appender.console.layout.ConversionPattern=%d{yy/MM/dd HH:mm:ss} %p %c{1}: %m%n

# Settings to quiet third party logs that are too verbose
log4j.logger.org.spark-project.jetty=WARN
log4j.logger.org.spark-project.jetty.util.component.AbstractLifeCycle=ERROR
log4j.logger.org.apache.spark.repl.SparkIMain$exprTyper=INFO
log4j.logger.org.apache.spark.repl.SparkILoop$SparkILoopInterpreter=INFO

#log4j.logger.org.apache.spark.sql.SQLContext=TRACE
#log4j.logger.org.apache.spark.sql.catalyst.analysis.Analyzer=TRACE
#log4j.logger.org.apache.spark=TRACE
#log4j.logger.org.apache.spark.storage.BlockManagerMasterActor=WARN
#log4j.logger.org.apache.spark.HeartbeatReceiver=WARN
#log4j.logger.org.apache.spark.scheduler.local.LocalActor=WARN
```
