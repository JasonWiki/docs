# Spark SQL - pyspark 接口文档

-  Spark SQL Python Doc Index : http://spark.apache.org/docs/1.5.2/sql-programming-guide.html

## 一、对接 Hive Thriftserver


### 1. 安装 python 类包

- 可以直接使用 python 的 hiveServer2 客户端连接
- [hiveServer2 文档](https://cwiki.apache.org/confluence/display/Hive/Setting+Up+HiveServer2)

 ```
    1. 安装 : pip install pyhs2
    2. Thrift JDBC/ODBC server 实现对应于 HiveServer2 in Hive 0.13.  这是官方的说明，所以我们可以直接适用 hiveServer2 的客户端操作 Spark-Sql
    3. 拷贝 $HIVE_HOME/conf/hive-site.xml 到 $SPARK_HOME/conf/hive-site.xml 中
    4. 其他参考官方配置
       spark-sql 文档 : http://spark.apache.org/docs/latest/sql-programming-guide.html#jdbc-to-other-databases
       和官方的 deploying 部署文档 : http://spark.apache.org/docs/latest/cluster-overview.html
 ```

### 2. python sql 客户端接口

- http://spark.apache.org/docs/1.5.2/api/python/pyspark.sql.html

``` python

1) 连接客户端
  pyspark --help

  pyspark  \
  --master spark://uhadoop-ociicy-task3:7077

2) 操作
  from pyspark.sql import HiveContext
  sqlContext = HiveContext(sc)

  u'游标'
  cursor = sqlContext.sql("use dw_db")
  cursor = sqlContext.sql("show tables")

  u'输出结果'
  print cursor.collect()

```

- pyspark使用注意点:

  * 如果python代码中使用第三方包，hadoop集群中所有work节点都需要安装
  * Spark的 DataFrame 转Pandas的DataFrame 可以使用 toPandas()方法
  * 提交代码执行
      spark-submit a.py --master yarn-client
