# Hive 与 Hbase 整合

## 一、环境

``` sh
准备工作 :
  1) 登录 zookeeper
    $ZOOKEEPER_HOME/bin/zkCli.sh

  2) 查看所有 Habse 集群主机名
    ls /hbase/rs  

  3) 把 hbase 所有的主机名配置到 /etc/hosts 中,保证每个 host 都能访问到 hbase 集群的服务器
    vim /etc/hosts
    例如 :
    10.10.33.175    uhadoop-ociicy-core1
    10.10.7.68      uhadoop-ociicy-core2


  4) 配置相关的依赖 jar 包
    具体版本根据实际安装的 hive 或者 Hbase 版本为准


1. 单节点模式启动
  hive --auxpath \ $HIVE_HOME/lib/hive-hbase-handler-1.1.0-cdh5.4.4.jar,$HBASE_HOME/lib/hbase-common-1.0.0-cdh5.4.4.jar,$HBASE_HOME/lib/zookeeper-3.4.5-cdh5.4.4.jar,$HBASE_HOME/lib/guava-12.0.1.jar \
  --hiveconf hbase.master=hostname:60000


2. 集群模式启动 (优先使用 Hbase 中的包)
  hive --auxpath \ $HIVE_HOME/lib/hive-hbase-handler-1.1.0-cdh5.4.4.jar,$HBASE_HOME/lib/hbase-common-1.0.0-cdh5.4.4.jar,$HBASE_HOME/lib/zookeeper-3.4.5-cdh5.4.4.jar,$HBASE_HOME/lib/guava-12.0.1.jar \
  --hiveconf hbase.zookeeper.quorum=hostname1,hostname2


3. 永远生效环境配置 (HIVE_AUX_JARS_PATH 和 hive.aux.jars.path 会互相覆盖)
  1) 第一种方法(只能用一种方法,不然会把另外一种覆盖) : $HIVE_HOME/conf/hive-env.sh
  export HIVE_AUX_JARS_PATH=$HIVE_HOME/lib/hive-hbase-handler-1.1.0-cdh5.4.4.jar,$HBASE_HOME/lib/hbase-common-1.0.0-cdh5.4.4.jar,$HBASE_HOME/lib/zookeeper-3.4.5-cdh5.4.4.jar,$HBASE_HOME/lib/guava-12.0.1.jar

  2) 第二种方法(只能用一种方法,不然会把另外一种覆盖) : $HIVE_HOME/conf/hive-site.xml
  <!--加载常用的包-->
  <property>
    <name>hive.aux.jars.path</name>
    <value>file:///usr/local/hive/lib/hive-hbase-handler-1.1.0-cdh5.4.4.jar,file:///usr/local/hbase/lib/hbase-common-1.0.0-cdh5.4.4.jar,file:///usr/local/hbase/lib/zookeeper-3.4.5-cdh5.4.4.jar,file:///usr/local/hbase/lib/guava-12.0.1.jar</value>
  </property>

```

## 二、命令

``` sql

1. 创建表 语法

  CREATE EXTERNAL TABLE db_name.tb_name(
    row_key int,
    '列' string)
  STORED BY 'org.apache.hadoop.hive.hbase.HBaseStorageHandler'
  WITH SERDEPROPERTIES (
    "hbase.columns.mapping" = "列族:列")
  TBLPROPERTIES (
    "hbase.table.name" = "hbase 表名")
  ;

  // 案例: 创建外部表
  CREATE EXTERNAL TABLE real_time.inventory_recommend(
    row_key int,
    inventory_recommend_inventory__inventory_ids string)
  STORED BY 'org.apache.hadoop.hive.hbase.HBaseStorageHandler'
  WITH SERDEPROPERTIES (
    "hbase.columns.mapping" = "inventoryRecommendInventory:inventoryIds")
  TBLPROPERTIES (
    "hbase.table.name" = "inventoryRecommend")
  ;



2. 优化参数(具体原理详见 hive on hbase  原理 URL 文档)

--- 提到插入效率，原理：提高 Map 数量, 具体根据 HDFS 文件调整
-- 一个 Map 最多同时处理的文件总数大小(控制 map 数量)
set mapreduce.input.fileinputformat.split.maxsize=10240000;
-- 节点中可以处理的最小的文件大小
set mapreduce.input.fileinputformat.split.minsize.per.node=10240000;
--  机架中可以处理的最小的文件大小
set mapreduce.input.fileinputformat.split.minsize.per.rack=10240000;

--- 提高查询效率， 关闭任务推测，一个 map 对应 hbase 表的一个 region
SET mapreduce.map.speculative=false;
SET mapreduce.reduce.speculative=false;

```
