# Spark 遇到的一些错误

## 一、Spark 与 Hbase 的错误

### 1. 错误

```
Exception in thread "main" java.io.IOException: java.lang.reflect.InvocationTargetException
	at org.apache.hadoop.hbase.client.ConnectionFactory.createConnection(ConnectionFactory.java:240)
	at org.apache.hadoop.hbase.client.ConnectionFactory.createConnection(ConnectionFactory.java:218)
	at org.apache.hadoop.hbase.client.ConnectionFactory.createConnection(ConnectionFactory.java:119)
	at com.angejia.dw.hadoop.hbase.HBaseClient.<init>(HBaseClient.scala:65)
	at com.angejia.dw.recommend.inventory.InventoryIBCF$.init(InventoryIBCF.scala:56)
	at com.angejia.dw.recommend.inventory.InventoryIBCF$.main(InventoryIBCF.scala:36)
	at com.angejia.dw.recommend.inventory.InventoryIBCF.main(InventoryIBCF.scala)
	at sun.reflect.NativeMethodAccessorImpl.invoke0(Native Method)
	at sun.reflect.NativeMethodAccessorImpl.invoke(NativeMethodAccessorImpl.java:57)
	at sun.reflect.DelegatingMethodAccessorImpl.invoke(DelegatingMethodAccessorImpl.java:43)
	at java.lang.reflect.Method.invoke(Method.java:606)
	at org.apache.spark.deploy.SparkSubmit$.org$apache$spark$deploy$SparkSubmit$$runMain(SparkSubmit.scala:674)
	at org.apache.spark.deploy.SparkSubmit$.doRunMain$1(SparkSubmit.scala:180)
	at org.apache.spark.deploy.SparkSubmit$.submit(SparkSubmit.scala:205)
	at org.apache.spark.deploy.SparkSubmit$.main(SparkSubmit.scala:120)
	at org.apache.spark.deploy.SparkSubmit.main(SparkSubmit.scala)
Caused by: java.lang.reflect.InvocationTargetException
	at sun.reflect.NativeConstructorAccessorImpl.newInstance0(Native Method)
	at sun.reflect.NativeConstructorAccessorImpl.newInstance(NativeConstructorAccessorImpl.java:57)
	at sun.reflect.DelegatingConstructorAccessorImpl.newInstance(DelegatingConstructorAccessorImpl.java:45)
	at java.lang.reflect.Constructor.newInstance(Constructor.java:526)
	at org.apache.hadoop.hbase.client.ConnectionFactory.createConnection(ConnectionFactory.java:238)
	... 15 more
Caused by: java.lang.NoSuchMethodError: org.apache.hadoop.hbase.client.RpcRetryingCallerFactory.instantiate(Lorg/apache/hadoop/conf/Configuration;Lorg/apache/hadoop/hbase/client/ServerStatisticTracker;)Lorg/apache/hadoop/hbase/client/RpcRetryingCallerFactory;
	at org.apache.hadoop.hbase.client.ConnectionManager$HConnectionImplementation.createAsyncProcess(ConnectionManager.java:2317)
	at org.apache.hadoop.hbase.client.ConnectionManager$HConnectionImplementation.<init>(ConnectionManager.java:688)


	at org.apache.hadoop.hbase.client.ConnectionManager$HConnectionImplementation.<init>(ConnectionManager.java:630)
	... 20 more

```

### 2.解决

- 因为在 spark-env.sh 设置错误的环境变量导致相关类不能加载进来
- 配置好对应的环境变量

``` sh
1. 设置 ~/.bashrc
# Environment variables required by hadoop
export HADOOP_HOME_WARN_SUPPRESS=true
export HADOOP_HOME=/usr/lib/hadoop
export HADOOP_CONF_DIR=$HADOOP_HOME/etc/hadoop
export YARN_CONF_DIR=$HADOOP_HOME/etc/hadoop

# HBase
export HBASE_HOME=/usr/local/hbase
export HBASE_CONF_DIR=$HBASE_HOME/conf

# spark
export SPARK_HOME=/usr/local/spark
export SPARK_CONF_DIR=$SPARK_HOME/conf

#libs
export LD_LIBRARY_PATH=$HADOOP_HOME/lib/native:$HADOOP_HOME/lib/hadoop-lzo.jar:$LD_LIBRARY_PATH


2. 设置 spark 配置
vim $SPARK_HOME/conf/spark-env.sh

SPARK_LIBRARY_PATH=$SPARK_LIBRARY_PATH:$HADOOP_HOME/lib/native:$HADOOP_HOME/lib
SPARK_CLASSPATH=$SPARK_CLASSPATH:$HADOOP_HOME/lib/hadoop-lzo.jar
export SPARK_WORKER_MEMORY=4000M
export SPARK_DRIVER_MEMORY=5000M
```
