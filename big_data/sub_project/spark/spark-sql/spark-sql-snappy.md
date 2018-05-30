# SparkSQL 解析 Snappy

- 版本 Spark 2.1

## 一、配置

- $HADOOP_HOME/lib/native 库的支持(配置好环境变量, 退出终端重启服务)

``` sh

# 环境配置
export HADOOP_HOME=/opt/cloudera/parcels/CDH/lib/hadoop
export HADOOP_CONF_DIR=$HADOOP_HOME/etc/hadoop

export JAVA_LIBRARY_PATH=$JAVA_LIBRARY_PATH:$HADOOP_HOME/lib/native
export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:$HADOOP_HOME/lib/native:/usr/lib64:/usr/local/cuda/lib64:/usr/local/cuda/lib

export SPARK_HOME=/usr/local/spark
export SPARK_CONF_DIR=$SPARK_HOME/conf
export SPARK_LIBRARY_PATH=$SPARK_LIBRARY_PATH:$HADOOP_HOME/lib/native
export SPARK_CLASSPATH=$SPARK_CLASSPATH
export PATH=$SPARK_HOME/bin:$PATH


# 启动配置
spark-sql --jars file://$HADOOP_HOME/lib/snappy-java-1.0.4.1.jar,file:///etc/hive/auxlib/json-serde-1.3.7-jar-with-dependencies.jar \
--name spark-sql-server \
--master yarn \
--deploy-mode client \
--driver-cores 2 \
--driver-memory 4g \
--executor-cores 2 \
--executor-memory 4g \
--num-executors 2 \
--conf spark.eventLog.enabled=false \
--conf spark.eventLog.dir=hdfs://dw1:8020/tmp/spark \
--conf spark.serializer=org.apache.spark.serializer.KryoSerializer \
--conf spark.io.compression.codec=org.apache.spark.io.SnappyCompressionCodec \
--conf net.topology.script.file.name=/etc/hadoop/conf.cloudera.yarn/topology.py \
--conf spark.sql.parquet.compression.codec=snappy


# 参数解读
-- 压缩编码 spark.io.compression.codec=org.apache.spark.io.SnappyCompressionCodec;

-- 解码器 spark.sql.parquet.compression.codec=snappy;


# 测试
SELECT common FROM ods.ods_browser_click WHERE p_dt='2017-05-07' AND p_hours='00' LIMIT 1;

```


## 二、问题

在 spark.master=yarn 模式下支持不好, 主要问题表现在子节点无法获取到 $HADOOP_HOME/lib/native 类库



spark-sql \
--name spark-sql-server \
--master yarn \
--deploy-mode client \
--driver-cores 2 \
--driver-memory 4g \
--executor-cores 2 \
--executor-memory 4g \
--num-executors 2 \
--conf spark.eventLog.enabled=false \
--conf spark.eventLog.dir=hdfs://dw1:8020/tmp/spark \
--conf spark.serializer=org.apache.spark.serializer.KryoSerializer \
--conf spark.io.compression.codec=org.apache.spark.io.SnappyCompressionCodec \
--conf net.topology.script.file.name=/etc/hadoop/conf.cloudera.yarn/topology.py \
--conf spark.sql.parquet.compression.codec=snappy
