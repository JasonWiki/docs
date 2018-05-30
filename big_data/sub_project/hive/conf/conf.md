# hive 参数

- [用户端参数](https://cwiki.apache.org/confluence/display/Hive/Configuration+Properties)
- [管理员参数](https://cwiki.apache.org/confluence/display/Hive/AdminManual+Configuration)


## * 优化配置

### 1、Error: GC overhead limit exceeded 也就是 OOM 内存溢出

- [参考文章](http://www.tuicool.com/articles/eEV3Ib)

```xml
<property>
  <name>mapred.child.java.opts</name>
  <value>-Xmx512m</value>
</property>

<property>
  <name>mapred.reduce.child.java.opts</name>
  <value>-Xmx1024M</value>
</property>

```

#### 1、 优化方向

- [性能优化思维导图](https://www.processon.com/view/link/5662d493e4b01db999f419b1)

``` sql

-- recuce 内存
set mapred.child.java.opts=-Xmx512m;

-- 设置垃圾回收器的类型为并行标记回收器
set mapred.reduce.child.java.opts=-Xmx1024M;

-- 使用 map join 代替 common join
set hive.auto.convert.join=true;

-- 解决数据倾斜问题
set hive.optimize.skewjoin=true;

```

#### 1.3 优化建议

```
1.是HiveQL的写法上，尽量少的扫描同一张表，并且尽量少的扫描分区。扫太多，一是job数多，慢，二是耗费网络资源，慢。

2.是Hive的参数调优和JVM的参数调优，尽量在每个阶段，选择合适的jvm max heap size来应对OOM的问题。
```


## 一、常用参数

``` sql
1.显示查询字段
  set hive.cli.print.header=true;

2.执行 hql 并且排除其他字段
  hive -S -e "hql";

3.显示当前选择的库
  set hive.cli.print.current.db=true

4.配置文件限制 Limit 条数
  set hive.limit.row.max.size=100;

5.desc 时，不显示分区字段
	set hive.display.partition.cols.separately=false;
```


## 二、HIVE 配置

### 1 hive.stats.autogather

``` sql
问题 :
	0.9.0已测试。当hive.stats.autogather设为true时，执行insert overwrite table会启动StatsTask，计算新生成表的statstics信息，如num_files，num_rows，total_size，raw_data_size。生成的信息目前支持publish到HBase或者JDBC的数据库如MySQL/Derby中

解决 :
	vim hive-site.xml
	<property>
	  <name>hive.stats.autogather</name>
	  <value>false</value>
	</property>
```

### 2.启用压缩

```
待验证
vim hadoop/etc/hadoop/yarn-site.xml
yarn.application.classpath 加载目录

cp /usr/local/hadoop/lib/hadoop-lzo.jar /usr/local/hadoop/share/hadoop/common/lib/
```


### 3、多个小文件合并成一个文件指定文件大小设计

``` sql

set mapred.max.split.size=300000000;
set mapred.min.split.size.per.node=300000000;
set mapred.min.split.size.per.rack=300000000;

```

### 4、如果文件只有一个并且大小为1G设置如下

``` sql
set mapred.reduce.tasks=10
```

### 5、动态分区处理

```sql

-- (可通过这个语句查看：set hive.exec.dynamic.partition;)
set hive.exec.dynamic.partition=true;
set hive.exec.dynamic.partition.mode=nonstrict;

-- (如果自动分区数大于这个参数，将会报错)
set hive.exec.max.dynamic.partitions=100000;
set hive.exec.max.dynamic.partitions.pernode=100000;

```

### 6、hive 日志

```sh

1.thrift 日志
  conf/hive-log4j.properties
  #hive.log.dir=${java.io.tmpdir}/${user.name} 默认放在 /tmp/dwadmin/中
  hive.log.dir=/usr/local/hive/logs 可以配置成自已的


2.debug 模式
  hive --hiveconf hive.root.logger=DEBUG,console

3.hiveServer 2 日志
  hiveServer2 所在的服务器目录下 /var/log/hive/

```


## 三 、CURL 操作(待验证)

- [LanguageManual DML](https://cwiki.apache.org/confluence/display/Hive/LanguageManual+DML#LanguageManualDML-Syntax.4);
- [配置文档地址](https://cwiki.apache.org/confluence/display/Hive/Hive+Transactions)

``` sh
-- 需要在配置里开启事物模式
-- 由于这里需要操作 Metastore 的数据，Metastore 服务不在我们这里，所以无法操作
-- 以下仅供参考，详细请查看文档，

set hive.support.concurrency=true;
set hive.enforce.bucketing=true;
set hive.exec.dynamic.partition.mode=nonstrict;
-- 设置所需的事物
set hive.txn.manager=org.apache.hadoop.hive.ql.lockmgr.DbTxnManager;
-- 是否这个metastore实例上运行发起者和清洁线程。
set hive.compactor.initiator.on=true;
-- 表示有几个 metastore 实例,默认 0 个
set hive.compactor.worker.threads=1;


set hive.support.concurrency=true;
set hive.enforce.bucketing=true;
set hive.exec.dynamic.partition.mode=nonstrict;
```
