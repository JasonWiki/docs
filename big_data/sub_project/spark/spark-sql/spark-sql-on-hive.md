# Spark Sql ON Hive

## 一、配置文件

### 1. spark-defaults.conf

- 详见配置文件

### 2. hive 集成配置

``` sh
1. hive-site.xml

<configuration>
  <property>
    <name>hive.metastore.uris</name>
    <value>thrift://dw1:9083,thrift://dw2:9083</value>
  </property>
  <property>
    <name>hive.metastore.client.socket.timeout</name>
    <value>300</value>
  </property>
  <property>
    <name>hive.metastore.warehouse.dir</name>
    <value>/user/hive/warehouse</value>
  </property>
</configuration>


2. spark-env.sh
  export HADOOP_HOME=/usr/lib/hadoop
  export HADOOP_CONF_DIR=$HADOOP_HOME/etc/hadoop
  export SPARK_LIBRARY_PATH=$SPARK_LIBRARY_PATH:$HADOOP_HOME/lib/native:$HADOOP_HOME/lib
```


## 二、SparkSQL 客户端模式部署

``` sh
-- 普通加载模式
spark-sql \
--master yarn \
--deploy-mode client \
--name spark-sql-service_1 \
--driver-cores 2 \
--driver-memory 4096M \
--executor-cores 1 \
--executor-memory 2048M \
--num-executors 3 \
--jars file:///etc/hive/auxlib/dw_hive_udf-1.0.jar,file:///etc/hive/auxlib/json-serde-1.3.7-jar-with-dependencies.jar


-- 配置参数加载模式
spark-sql \
--master yarn \
--deploy-mode client \
--name spark-sql-service \
--driver-cores 2 \
--driver-memory 4096M \
--executor-cores 1 \
--executor-memory 2048M \
--num-executors 3 \
--conf spark.driver.extraJavaOptions="-DJAVA_LIBRARY_PATH=/opt/cloudera/parcels/CDH/lib/hadoop/lib/native:$LD_LIBRARY_PATH" \
--jars file:///etc/hive/auxlib/dw_hive_udf-1.0.jar,file:///etc/hive/auxlib/json-serde-1.3.7-jar-with-dependencies.jar

```


## 三、SparkSQL ThriftServer 模式部署

- 开启 thrift-server 服务
- [spark thrift-jdbcodbc-serve 文档](http://spark.apache.org/docs/latest/sql-programming-guide.html#running-the-thrift-jdbcodbc-server)

### 1. 启动 Thriftserver 进程

- 更多配置见 spark-defaults.conf

``` sh

1. Yarn 模式
  (1) yarn-client 模式 , 让 Yarn 管理进程 , Driver 运行在客户端 ,Work 运行在 NodeManager 上
    $SPARK_HOME/sbin/start-thriftserver.sh \
    --master yarn \
    --deploy-mode client \
    --name spark-sql-service \
    --queue default \
    --driver-cores 2 \
    --driver-memory 4096M \
    --executor-cores 1 \
    --executor-memory 2048M \
    --num-executors 3 \
    --jars file://path/xxx.jar,file://path/xxx.jar \
    --hiveconf hive.server2.thrift.port=10002

  (2) yarn-cluster模式, 集群模式目前不支持
    ./sbin/start-thriftserver.sh \
    --master yarn \
    --deploy-mode client \
    --name spark-sql-service \
    --driver-cores 2 \
    --driver-memory 4096M \
    --executor-cores 1 \
    --executor-memory 2048M \
    --num-executors 3 \
    --hiveconf hive.server2.thrift.port=10002

2. standalone 模式
  $SPARK_HOME/sbin/start-thriftserver.sh \
  --master spark://uhadoop-ociicy-task3:7077 \
  --deploy-mode client \
  --name spark-sql \
  --driver-cores 2 \
  --driver-memory 500M \
  --hiveconf hive.server2.thrift.port=10002


3. JDBC 操作 hive
  $SPARK_HOME/bin/beeline !connect jdbc:hive2://hostname:10002

```


### 2. SparkSQL thrift-server HA 模式

#### 2.1 配置参数

``` xml
<property>
  <name>hive.server2.support.dynamic.service.discovery</name>
  <value>true</value>
</property>
<property>
  <name>hive.server2.zookeeper.namespace</name>
  <value>sparkserver2_zk</value>
</property>
<property>
  <name>hive.zookeeper.quorum</name>
  <value>master1:2181,master2:2181,node1:2181</value>
</property>
<property>
  <name>hive.zookeeper.client.port</name>
  <value>2181</value>
</property>

<!-- HiveServer2 启动的节点地址(根据 HA 的部署节点的名称写, 根据部署节点配置) -->
<property>
  <name>hive.server2.thrift.bind.host</name>
  <value>node9</value>
</property>

```

#### 2.2 部署服务

``` sh
1. Spark 编译支持 HA 模式的 Jar 包

  spark-hive-thriftserver_2.11-2.0.2.jar 加入到 $SPARK_HOME/jars/spark-hive-thriftserver_2.11-2.0.2.jar


2. 部署配置, 这里需要在 2 个节点, 同时启动服务

  $SPARK_HOME/sbin
  ./start-thriftserver.sh \
  --master yarn \
  --name service_name \
  --conf spark.driver.memory=3G \
  --executor-memory 1G \
  --num-executors 10 \
  --hiveconf hive.server2.thrift.port=10003


3. 测试连接 HA 模式下的 SparkThriftService

$SPARK_HOME/bin/beeline -u "jdbc:hive2://zkNode1:2181,zkNode2:2181,zkNode3:2181/default;serviceDiscoveryMode=zooKeeper;zooKeeperNamespace=sparkserver2_zk" hadoop hadoop

# 客户端方式启动
$HIVE_HOME/bin/beeline
> !connect jdbc:hive2://zkNode1:2181,zkNode2:2181,zkNode3:2181/default;serviceDiscoveryMode=zooKeeper;zooKeeperNamespace=sparkserver2_zk hadoop hadoop
```


### 3. Spark 动态资源部署

- 具体参数说明见: spark-defaults.conf

``` sh
1. 配置 yarn-site.xml 支持动态资源部署模式( NodeManager 所有节点上, 重启服务)

##### NodeManager 附属程序支持 Start #####

<!-- NodeManager 上运行的附属服务, 用于提升 Shuffle 计算性能 -->
<property>
  <name>yarn.nodemanager.aux-services</name>
  <value>mapreduce_shuffle,spark_shuffle</value>
</property>

<!-- NodeManager 中辅助服务对应的类 -->
<property>
  <name>yarn.nodemanager.aux-services.spark_shuffle.class</name>
  <value>org.apache.spark.network.yarn.YarnShuffleService</value>
</property>

<!-- Shuffle 服务监听数据获取请求的端口。可选配置, 默认值为 7337 -->
<property>
  <name>spark.shuffle.service.port</name>
  <value>7337</value>
</property>

##### NodeManager 附属程序支持 End #####


2. 配置 spark-defaults.conf 配置动态分配

##### 动态分配 Start #####

## 注意事项：根据任务动态向 yarn 申请资源, 会导致申请资源浪费大量时间。

## 是否使用动态资源分配，它根据工作负载调整为此应用程序注册的执行程序数量。
spark.dynamicAllocation.enabled                       true
## 使用外部 shuffle, 保存了由 executor 写出的 shuffle 文件所以 executor 可以被安全移除, spark.dynamicAllocation.enabled 为 true, 这个选项才可以为 true
spark.shuffle.service.enabled                         true

## 每个 Application 最小分配的 executor 数
spark.dynamicAllocation.minExecutors                  3
## 每个 Application 最大并发分配的 executor 数。 ThriftServer 模式是整个 ThriftServer 同时并发的最大资源数，如果多个用户同时连接，则会被多个用户共享竞争
spark.dynamicAllocation.maxExecutors                  6

## 如果启用动态分配，并且有超过此持续时间的挂起任务积压，则将请求新的执行者。
spark.dynamicAllocation.schedulerBacklogTimeout       6s
## 与 spark.dynamicAllocation.schedulerBacklogTimeout 相同，但仅用于后续执行者请求
spark.dynamicAllocation.sustainedSchedulerBacklogTimeout              6s

## 如果启用动态分配，并且执行程序已空闲超过此持续时间，则将删除执行程序。
spark.dynamicAllocation.executorIdleTimeout           60s

##### 动态分配 End #####


2. 部署(spark 默认会读取 spark-defaults.conf 配置文件, 但是命令行启动会覆盖 spark-defaults.conf)

$SPARK_HOME/sbin/start-thriftserver.sh \
--master yarn \
--deploy-mode client \
--queue root.default \
--name test \
--driver-cores 4 \
--driver-memory 8192M \
--executor-cores 6 \
--executor-memory 12288M \
--conf spark.dynamicAllocation.enabled=true \
--conf spark.dynamicAllocation.minExecutors=3 \
--conf spark.dynamicAllocation.maxExecutors=6 \
--hiveconf hive.server2.thrift.port=10002 \
--jars file:///etc/hive/auxlib/dw_hive_udf-1.0.jar,file:///etc/hive/auxlib/json-serde-1.3.7-jar-with-dependencies.jar
```


## 三、Spark Sql Udf

- Hive UDF 与 Spark UDF 通用
- [UDF](technology/hadoop-docs/sub-project/hive/hive-udf.md)



## 四、Spark Sql 编程

``` java
package com.dw2345.machine_learn.combination.sql;

// $example on:spark_hive$
import org.apache.spark.sql.Row;
import org.apache.spark.sql.SparkSession;
// $example off:spark_hive$

object TestSparkSql {
    def main(args: Array[String]) {
        // warehouseLocation points to the default location for managed databases and tables
        var warehouseLocation = "hdfs://nameservice1/user/hive/warehouse"

        // 需要 hive-site.xml 文件和 hdfs-site.xml 文件
        val spark = SparkSession
          .builder()
          .master("local")
          .appName("Spark Hive Example")
          .config("spark.sql.warehouse.dir", warehouseLocation)
          .enableHiveSupport()
          .getOrCreate()

        import spark.implicits._
        import spark.sql

        // Queries are expressed in HiveQL
        sql("SELECT * FROM web_logs_text LIMIT 10").show()
        // sql("SELECT * FROM dm_db.dm_channel_inst_compete WHERE p_dt='2017-06-30' AND p_hours='11' LIMIT 10").show()
        // sql("SELECT * FROM ods.ods_pic_use WHERE p_type='2' AND p_dt='2017-08-28' AND p_hours='23' LIMIT 10").show()

    }
}
```
