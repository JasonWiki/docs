# SparkSQL 与 Hbase 整合

## 一、环境

``` sh
1) 创建 hive on hbase 数据表, 具体参见 hive-on-hbase 文档

1) 把 hbase 所有的主机名配置到 /etc/hosts 中,保证每个 host 都能访问到 hbase 集群的服务器
    vim /etc/hosts
    例如 :
    10.10.33.175    uhadoop-ociicy-core1
    10.10.7.68      uhadoop-ociicy-core2
    10.10.43.97     uhadoop-ociicy-core3
    10.10.240.22    uhadoop-ociicy-core4
    10.10.236.241   uhadoop-ociicy-core5
    10.10.222.21    uhadoop-ociicy-core6
    10.10.229.183   uhadoop-ociicy-task3
    10.10.234.131   uhadoop-ociicy-task4

2) 配置相关的依赖 jar 包(具体跟随集群环境走)
    $HBASE_HOME/lib/hbase-annotations-1.2.0-cdh5.9.2.jar
    $HBASE_HOME/lib/hbase-spark-1.2.0-cdh5.9.2.jar
    $HBASE_HOME/lib/hbase-common-1.2.0-cdh5.9.2.jar
    $HBASE_HOME/lib/hbase-client-1.2.0-cdh5.9.2.jar
    $HBASE_HOME/lib/hbase-server-1.2.0-cdh5.9.2.jar
    $HBASE_HOME/lib/hbase-protocol-1.2.0-cdh5.9.2.jar
    $HBASE_HOME/lib/guava-12.0.1.jar
    $HBASE_HOME/lib/htrace-core-3.2.0-incubating.jar
    $HBASE_HOME/lib/zookeeper.jar
    $HBASE_HOME/lib/protobuf-java-2.5.0.jar
    $HBASE_HOME/lib/hbase-hadoop2-compat-1.2.0-cdh5.9.2.jar
    $HBASE_HOME/lib/hbase-hadoop-compat-1.2.0-cdh5.9.2.jar
    $HBASE_HOME/lib/metrics-core-2.2.0.jar
    $HIVE_HOME/lib/hive-hbase-handler-1.1.0-cdh5.9.0.jar

```


## 二、命令

``` sh
1. 注意事项

配置好 hbase-site.xml, 配置好 --jars
spark-sql \
--master yarn \
--deploy-mode client \
--name spark-hbase-demo \
--driver-cores 1 \
--driver-memory 1024M \
--executor-cores 1 \
--executor-memory 1024M \
--num-executors 1 \
--files $HBASE_HOME/conf/hbase-site.xml \
--jars file://$HBASE_HOME/lib/hbase-annotations-1.2.0-cdh5.9.2.jar,file://$HBASE_HOME/lib/hbase-spark-1.2.0-cdh5.9.2.jar,file://$HBASE_HOME/lib/hbase-common-1.2.0-cdh5.9.2.jar,file://$HBASE_HOME/lib/hbase-client-1.2.0-cdh5.9.2.jar,file://$HBASE_HOME/lib/hbase-server-1.2.0-cdh5.9.2.jar,file://$HBASE_HOME/lib/hbase-protocol-1.2.0-cdh5.9.2.jar,file://$HBASE_HOME/lib/guava-12.0.1.jar,file://$HBASE_HOME/lib/htrace-core-3.2.0-incubating.jar,file://$HBASE_HOME/lib/zookeeper.jar,file://$HBASE_HOME/lib/protobuf-java-2.5.0.jar,file://$HBASE_HOME/lib/hbase-hadoop2-compat-1.2.0-cdh5.9.2.jar,file://$HBASE_HOME/lib/hbase-hadoop-compat-1.2.0-cdh5.9.2.jar,file://$HBASE_HOME/lib/metrics-core-2.2.0.jar,file://$HIVE_HOME/lib/hive-hbase-handler-1.1.0-cdh5.9.0.jar
```


## 三、RDD 编程

使用 Spark On HBase 机制, 在不迁移数据的情况下，使用 Spark 嵌入 HBase 中分析查询，目前有如下几个方案:
1.  hortonworks/shc - spark-on-hbase 方案
   实现了 Spark Datasource API ， 直接使用 Spark Catalyst 引擎(spark 2.0 引入)进行查询优化，耦合低，由于使用 API 访问，后续 Spark 版本升级 Spark Catalyst 引擎被优化，性能也会跟着提升。

2. Huawei-Spark/Spark-SQL-on-HBase - 华为 2015 入侵方案
   在 Spark Catalyst 引擎内嵌入自己的查询优化计划, 将 RDD 发送到 HBase，入侵到 HBase 的 Coprocessors 协同处理器中执行任务，例如 Group By。由于此查询计划是自己实现功能的复杂性，不使用  Spark Catalyst 官方优化引擎，所以日后升级、补丁，不跟随 Spark 官方走，会导致日后维护难和不稳定

3. nerdammer/spark-hbase-connector - nerdammer
  对传统读写 Hbase TableInputFormat 和 TableOutputFormat 的封装

4. cloudera-labs/SparkOnHBase - coluder 2015 方案 (使用 Spark 1.6 版本)
  cloudera 提供的方案 2015 年方案


``` java
import org.apache.spark.sql.{SparkSession, DataFrame, SQLContext}
import org.apache.spark.{SparkConf, SparkContext}
import org.apache.spark.sql.execution.datasources.hbase._

/**
 * SparkRDD 读取 HBase 数据
 * 下载 git@github.com:hortonworks-spark/shc.git
 *    git clone  git@github.com:hortonworks-spark/shc.git
 * 选择 spark 和 hbase 对应版本, 切换到 v1.1.0-2.0 分支,  Hbase 1.1.0, spark 2.0, https://github.com/hortonworks-spark/shc/tree/v1.1.0-2.0
 *    git fetch origin v1.1.0-2.0:v1.1.0-2.0
 *    git checkout v1.1.0-2.0
 * 打包编译, 修改 pom.xml 文件, 修改 spark 版本号信息为 2.0.2
 *    mvn clean package
 * 放到项目 lib 目录下即可
 */
object TestSparkHBase {

     def main(args: Array[String]): Unit = {
        // 初始化上下文
        val sparkConf = new SparkConf()
           //.setMaster("local")
           .setAppName("HBaseExample")
        val sc = new SparkContext(sparkConf)
        val sqlContext = new SQLContext(sc)

        // 隐式转换
        import sqlContext.implicits._

        // 装在 HBase 数据到 DataFrame
        val df = withCatalog(sqlContext, catalog)

        // 读取 RDD
        df.take(10).foreach { x => println(x) }

        // 读取hive
        df.registerTempTable("user_profile")
        sqlContext.sql("SELECT col0,col1,col2,col3 from user_profile LIMIT 10").show


    }

     // 定义 HBase 信息
    def catalog = s"""{
       |"table":{"namespace":"default", "name":"user_profile"},
       |"rowkey":"key",
       |"columns":{
         |"col0":{"cf":"rowkey", "col":"key", "type":"string"},
         |"col1":{"cf":"browser", "col":"channel_id", "type":"string"},
         |"col2":{"cf":"common", "col":"update_date", "type":"string"},
         |"col3":{"cf":"pcsafe", "col":"gc_nav_click", "type":"string"}
       |}
     |}""".stripMargin

     /**
      * 装在 HBbase 数据
      */
     def withCatalog(sqlContext: SQLContext, cat: String): DataFrame = {
        sqlContext
            .read
            .options(Map(HBaseTableCatalog.tableCatalog->cat))
            .format("org.apache.spark.sql.execution.datasources.hbase")
            .load()
     }

}

```
