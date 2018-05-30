# SparkSQL 平滑迁移 Hive UDF

- 版本 Spark 2.1

## 一、配置

- 注意默认连接数据库为 default 下, 在这个数据库下创建 UDF

### 1. SparkSQL-ThriftServer 服务部署注意事项和流程

``` sh
* Hive Udf 加载到 SparkSql ThriftServer 上的步骤和注意事项(非常重要！)
  1. jar 必须在 SparkSql ThriftServer 本地节点上, 不可以在 HDFS 上, 如果 jar 有依赖, 必须把依赖打入这个 jar 中
  2. 必须创建永久 UDF function , 创建之后必须重启 SparkSql ThriftServer 服务


* 流程步骤: 在 SparkSql ThriftServer beeline 客户端加载

  登录客户端方法:
    $SPARK_HOME/bin/beeline -u jdbc:hive2://uhadoop-ociicy-task4:10002/default -nhadoop -phadoop

  1. 加载 SparkSql ThriftServer 服务器上的本地 UDF jar 包

    beeline> ADD JAR /data/app/jars/dw_hive_udf-1.0-SNAPSHOT-spark.jar;    必须加载全部打包后的 jar

  2. 创建永久 函数 , 写在 hive 元数据库的 FUNCS 表中 (永久 函数创建一次即可, 除非函数加载的类路径发生变化)

    beeline>
    CREATE  FUNCTION  parse_user_agent  AS  'com.angejia.dw.hive.udf.useragent.ParseUserAgent';
    CREATE  FUNCTION  get_page_info  AS  'com.angejia.dw.hive.udf.pageinfo.CalculatePageInfo';

  3. 重启 SparkSql ThriftServer  服务

    a) 重启服务
      $SPARK_HOME/sbin/start-thriftserver.sh \
      --master yarn \
      --deploy-mode client \
      --name spark-sql-client \
      --driver-cores 2 \
      --driver-memory 8192M \
      --num-executors 2 \
      --executor-memory 2048M \
      --jars file://$HIVE_HOME/lib/hive-json-serde.jar,file://$HIVE_HOME/lib/hive-contrib.jar,file://$HIVE_HOME/lib/hive-serde.jar \
      --hiveconf hive.server2.thrift.port=10002 \
      --conf spark.sql.hive.thriftServer.singleSession=false \
      --conf spark.sql.files.maxPartitionBytes=268435456 \
      --conf spark.sql.files.openCostInBytes=268435456 \
      --conf spark.sql.autoBroadcastJoinThreshold=268435456 \
      --conf spark.sql.shuffle.partitions=12 \
      --conf spark.broadcast.compress=true \
      --conf spark.io.compression.codec=org.apache.spark.io.SnappyCompressionCodec  &  

    b) 重启之后登陆 beeline>
      $SPARK_HOME/bin/beeline -u jdbc:hive2://uhadoop-ociicy-task4:10002/default -nhadoop -phadoop

      SHOW FUNCTIONS;  查看函数是否已经加载进入

      ADD JAR /data/app/jars/dw_hive_udf-1.0-SNAPSHOT-spark.jar;  需要重新 add jar, 即可生效, 每次修改 jar 包, 需要重启服务


* 通过 beeline 客户端连接测试,(当登录的账号更改, 需要重新 add jar)

  # 生产用
  $SPARK_HOME/bin/beeline -u jdbc:hive2://uhadoop-ociicy-task4:10002/default -nhadoop -phadoop

```
