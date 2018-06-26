# Hive

## 一、了解 Hadoop 生态的中的 Hive

### 1、简介

- 分布式存储大型数据集的查询和管理
- hive 通过类 sql 的 hql 语句转化为 hadoop 的 map reduce 程序，然后去 hadoop hdfs 中查询后返回结果
- hive 是对 map reduce 的一层封装

### 2、历史

- 起源自 facebook 由 Jeff Hammerbacher 领导的团队

- 构建在 Hadoop 上的数据仓库框架

- 设计的目的是让 Java 技能较弱的分析师可以通过类 SQL 查询海量数据

- 2008 年 facebook 把 hive 项目贡献给 Apache


## 二、Hive 组件与体系架构

### 1、组件

``` doc
用户接口 : shell Client、thrift(自动生成Java代码)、web UI

Thrift 服务器：提供了可以远程访问其他进程的功能，也提供使用 JDBC 和 ODBC 访问 hive 的功能，这些都是基于 Thirft 实现的

元数据库 (MetAStore) "Derby,Mysql" 等

解析器 : 输入 SQL 映射为 MapReduce 的 job

Hadoop : Hive 的数据都是放在 Hadoop 里面的
```

### 2、构架图

![image](imgs/hive1.png)
![image](../../imgs/hive1.png)

### 3、端口

| 端口 | 说明 | 详细 |
| ------ | ------------ | -----|
| 0 0.0.0.0:10000 | hiveserver 服务端口 | Thrift Server 服务器 |


## 三、Hive 安装

### 1、三种安装模式

* 1) 内嵌模式
  元数据保持在内嵌的 Derby 数据库中，一次只能有一个连接会话。
* 2) 本地独立模式
    在本地安装 Mysql , 把元数据放到 Mysql 中
* 3) 远程模式
  元数据放到远程的 Mysql 数据库中


### 2、我们采用 cloudera manager 部署

## 四、操作 Hive

### *提醒事项*

（读本章前，请选阅读 hive-problem.md 你会少走很多弯路，以后把遇到的问题都整理到 hive-problem.md 文档下）

### 1、Hive Sql

- 部署完成以后，通过 shell 命令操作
- 更多语法参见：[链接](https://cwiki.apache.org/confluence/display/Hive/LanguageManual+UDF#LanguageManualUDF-UDFinternals)

``` sql
1) 显示所有数据库
  SHOW DATABASES;

2) 使用指定数据库
  use x_DATABASE;

3) 显示所有表
  SHOW TABLEs;

4) 删除表
  DROP TABLE xxx;

5) 删除数据库
  DROP DATABASE xxx;

6) 创建表
  看下面的详细说明

7) 重命名表 student to student2
  ALTER TABLE student rename to student2;

8) 列操作
  -- 增加列
  ALTER TABLE student add columns(sage int  comment "the student's age");

  -- 替换，仅保存被替换的列(可以用来删除)
  ALTER TABLE student replace columns(s1 string ,s2 int, s2 int);  

  -- 重命名列
  ALTER TABLE TABLE_name CHANGE old_col_name new_col_name String;

  -- 修改列的数据类型
  ALTER TABLE TABLE_name CHANGE [COLUMN] col_old_name col_new_name column_type [COMMENT col_comment] [FIRST|AFTER column_name]
    如：
    ALTER TABLE TABLE_name CHANGE status status INT;


9) 查看表结构
  DESC student;

  -- 查看表结构信息(表目录/格式等详信息)
  DESC formatted table;

  -- 查看表的分区详细信息(表分区目录/格式等详信息)
  DESC formatted table PARTITION(p_a='a',p_b='b');

9.1) 表属性修改操作

  -- 修改表 SERDEPROPERTIES 信息
  ALTER TABLE table SET SERDEPROPERTIES('hbase.columns.mapping'='XXX');

  -- 修改表 TBLPROPERTIES 信息
  ALTER TABLE table SET TBLPROPERTIES('a'='XXX');

10) 分区信息
  查看分区
  SHOW PARTITIONS db.tb;

  SHOW PARTITIONS db.tb PARTITION(p_dt);

  SHOW PARTITIONS db.tb PARTITION(p_dt='2017-04-08');

  添加分区
  ALTER TABLE table ADD IF NOT EXISTS PARTITION (p_dt = '2015-07-13')

  删除分区
  ALTER TABLE table DROP IF EXISTS PARTITION (p_dt = '2015-06-28');

11) 截断表 (貌似无用)
  RUNCATE TABLE student;

12) 按照模板复制表
  CREATE TABLE empty_TABLE_name LIKE TABLE_name;

13) 查看数据库结构
  DESC DATABASE dbname;

14) 查看创建表语句
  SHOW CREATE TABLE tb_name;

15) 创建数据指定文件路径,注意加引号
  CREATE DATABASE db_name  LOCATION '/user/hive/uba_log';

16) 查询数据保存到文件中
  hive -e "SELECT * FROM access_log.access_log_20150326 WHERE hostname='api.angejia.com' LIMIT 1" >> /tmp/log.log

17) 通过 sql 通过文件执行，把结果输出到文件中
  bin/hive -f sql.q >> /tmp/log.log

18) CASE 字句
  CASE
    WHEN
      a.account_type = 1 THEN 'broker'
    WHEN
      a.account_type = 2 THEN 'agcy'
    ELSE
      'CCC'
  END AS start_and_end

19) JOIN
  LEFT JOIN 返回左边所有符合 WHERE 语句的记录，右表匹配不上的的字段值用 NULL 代替
  RIGHT JOIN 返回右边表所有符合 WHERE 语句的记录。左表匹配不上的的字段值用 NULL 代替

  NOT IN 查询
    ON
      bs_tb.a_1 = on_tb.b_1
    WHERE
      on_tb.a_1 is null


20) 排序
  参考文章：
    http://wenku.baidu.com/link?url=j_mZCBQ7R_f_XOBjfYVSECVbS7e7qV9ajmc46_V_pN8ClJp2i-1k4mlEKQxNyr5hYYZGyHZbrrCDLGqjCmoMJtRUk_vg4QCSsR9ANZFvdbq

  ORDER BY
    解释：
      会对输入做全局排序，因此只有一个reducer

    问题：
      1、在hive.mapred.mode = strict 模式下 必须指定 LIMIT 否则执行会报错
      2、原因： 在order by 状态下所有数据会到一台服务器进行reduce操作也即只有一个reduce，如果在数据量大的情况下会出现无法输出结果的情况


  SORT BY

    解释：
      sort by 不是全局排序，其在数据进入(reducer前完成排序)

    问题:
      1、如果用sort by进行排序，并且设置 (mapred.reduce.tASks>1)， 则sort by只保证每个reducer的输出有序，不保证全局有序
      2、sort by 的数据只能保证在同一 reduce 中的数据可以按指定字段排序
      3、使用sort by 你可以指定执行的reduce 个数 （SET mapred.reduce.tASks=<number>）,对输出的数据再执行归并排序，即可以得到全部结果。


  distribute by
    解释：
      按照指定的字段,对数据进行划分到不同的,输出 reduce 文件中
      人话：就是把结果按照 p_dt(如日期) 划分到同类型的 reduce 文件中

    例子:
      SELECT
        id,
        p_dt
      FROM
        TABLE_n
      distribute by
        p_dt


  Cluster By
    解释：
      cluster by 除了具有 distribute by 的功能外还兼具 sort by 的功能。  

    问题： 
      但是排序只能是倒序排序，不能指定排序规则为 ASC 或者DESC


  组合使用
    FROM
      record2
    SELECT
      year,
      temperature  
    distribute by
      year  
    sort by
      year ASC,
      temperature DESC
    ;


21) 模糊匹配
  LIKE
  RLIKE '正则'


22) 字段类型转换
  WHERE cASt(aa AS float) < 1000;


23) DESCRIBE
  DESCRIBE invites; 显示表结构
  DESCRIBE function substr; 显示函数用法
  DESCRIBE EXTENDED valid_records; 显示函数用法


24) TBLPROPERTIES 表级属性，如是否外部表，表注释等

  SHOW TBLPROPERTIES db_name.tb_name;

    transient_lAStDdlTime 最后创建修改表时间


25) 统计(分析和描述)
  统计表的分区状态
  ANALYZE TABLE [TABLEName] PARTITION([p_dt]) COMPUTE STATISTICS noscan;

  1. 案例
  ANALYZE TABLE db_name.tb_name PARTITION(p_dt) COMPUTE STATISTICS noscan;

  ANALYZE TABLE db_name.tb_name PARTITION(p_dt='2016-04-01') COMPUTE STATISTICS noscan;


26) LOCKS 查看表锁
  S 共享锁: 读锁, 事物 T 锁上对象 A 被后, 可读, 不可写. 其他事物在对象 A 上不可再加锁、不可改、只可读.
  X 互斥锁: 写锁, 事物 T 锁上对象 A 被后, 可读, 可写. 其他事物在对象 A 不可再加锁、不可读、不可改.

  SHOW LOCKS;  显示所有锁

  SHOW LOCKS db_name.tb_name extended;  显示表锁

  SHOW LOCKS db_name.tb_name PARTITION(p_dt='xx');  显示指定分区锁

  UNLOCK TABLE dw_db.dw_product_safe_use_log PARTITION(p_dt='xx');  解锁分区


27) 修复表(根据文件修复分区)
  MSCK REPAIR TABLE table_name


28) 设置提交任务提交队列 mapred-site.xml
  mapreduce.job.queuename=root.default


29) FROM INSERT 语法
  FROM (SELECT * FROM db.tb WHERE type = 'x') AS m
  INSERT OVERWRITE TABLE db.tb_2 PARTITION(type)
  SELECT m.*
  ;
```


### 2、HIVE DDL

#### 2.1、创建表

``` sql

*) 分隔符语法

CREATE TABLE employees(
    name STRING,
    salary FLOAT,
    -- 数组类型
    subordinates ARRAY(STRING),
    -- MAP
    deductions MAP(STRING,FLOAT),
    -- 映射
    address STRUCT<street:STRING,city:STRING,state:STRING,zip:INT>
)
  --必须写在下面的子句之前（stored AS 除外）
  ROW FORMAT DELIMITED
  --Hive 将使用 ^A 做为列分隔符
  FIELDS TERMINATED BY '\001'
  --Hive 将使用 ^B 做为集合元素间分隔符
  COLLECTION ITEMS TERMINATED BY '\002'
  --Hive 将使用 ^C 做为 MAP 的键值之间的分隔符
  MAP KEYS TERMINATED BY '\003'
  -- 到目录前为止 Hive 对于 lines terminated by 公支持 \n 也就是说行与行之间分隔符只能是 \n
  LINES TERMINATED BY '\n'
  STORED AS TEXTFILE;

文本分隔符:
  \n    文本文件的换行符
  ^A    分隔字段（列），在 CREATE TABLE 语句中可以使用八进制编码（\001）表示
  ^B    分隔 ARRAY 或者 STRUCT 中的元素，或用于 MAP 中键值对之间的分隔，使用八进制编码（\002）表示
  ^C    用于 MAP 中键和值之间的分隔，使用八进制编码（\003）表示



1) 普通表
  CREATE TABLE student(
    sid int,sname string
  )
  ROW FORMAT DELIMITED
  FIELDS TERMINATED BY '\001'
  LINES TERMINATED BY '\n'
  STORED AS TEXTFILE;


2) 创建分区表 (ds 为分区字段)
CREATE TABLE student_index(
  sid int , sname string
  ) PARTITIONed by (ds string)
ROW FORMAT DELIMITED
FIELDS TERMINATED BY '\001'
LINES TERMINATED BY '\n'
STORED AS TEXTFILE;


3) 创建二级分区表 (teacher、nickname 为分区字段,加上备注)
CREATE TABLE clASsmem_index_1(
  student string,age int
) PARTITIONed by(
    teacher string comment 'the teacher',
    nickname string comment 'the nickname'
)
ROW FORMAT DELIMITED
FIELDS TERMINATED BY '\001'
LINES TERMINATED BY '\n'
STORED AS TEXTFILE;


4) 创建 PARQUET 文件格式的表
  CREATE TABLE parquet_TABLE_name (x INT, y STRING) STORED AS PARQUET;
  INSERT OVERWRITE TABLE parquet_TABLE_name SELECT * FROM other_TABLE_name;

   OR

  -- 使用目标表的数据, 创建一张 parquet 格式的表
  CREATE TABLE IF NOT EXISTS parquet_TABLE_name
  STORED AS PARQUET
  AS
  SELECT * FROM other_TABLE_name  LIMIT 10;


5) 创建 SEQUENCEFILE 文件格式的表
  CREATE TABLE sequencefile_TABLE_name (x INT, y STRING) STORED AS SEQUENCEFILE;
  INSERT OVERWRITE TABLE sequencefile_TABLE_name SELECT * FROM other_TABLE_name;


6) 创建 ORC 格式的数据表
  CREATE TABLE orc_TABLE_name (x INT, y STRING) STORED AS ORC;
  INSERT OVERWRITE TABLE orc_TABLE_name SELECT * FROM other_TABLE_name;


7) 创建任意格式表(案例是 TEXTFILE)
  CREATE TABLE IF NOT EXISTS other_table
    ROW FORMAT DELIMITED
    FIELDS TERMINATED BY ','
  STORED AS TEXTFILE
  AS
  SELECT a,b FROM table;


```


#### 2.2、生产环境的创建表

可以事先把数据写入到 hadoop hdfs 中 hive 表的对应目录中，这样就可以先创建表，再导入数据了。如案例 1)

``` sql
1) 创建内部(内部表根据数据库创建时的路径指定到对应的目录下)数据表，<正则格式化>
CREATE TABLE access_log_20150326 (
    request_time string,
    upstream_response_time string,
    remote_addr string,
    request_length string,
    upstream_addr string,
    server_date string,
    server_time string,
    hostname string,
    method string,
    request_uri string,
    http_code string,
    bytes_sent string,
    http_referer string,
    user_agent string,
    gzip_ratio string,
    http_x_forwarded_for string,
    auth string,
    mobile_agent string
)
ROW FORMAT SERDE 'org.apache.hadoop.hive.contrib.serde2.RegexSerDe'
WITH SERDEPROPERTIES (
  "input.regex" = "(.*?)\\t(.*?)\\t(.*?)\\t(.*?)\\t(.*?)\\t\\[(.+?)T(.+?)\\+.*?\\]\\t(.*?)\\t(.*?)\\s(.*?)\\s.*?\\t(.*?)\\t(.*?)\\t(.*?)\\t(.*?)\\t(.*?)\\t(.*?)\\t(.*?)\\t(.*?)",
  "output.format.string" = "%1$s %2$s %3$s %4$s %5$s %6$s %7$s %8$s %9$s %10$s %11$s %12$s %13$s %14$s %15$s %16$s %17$s %18$s"
)
STORED AS TEXTFILE;


2) 创建内部(内部表根据数据库创建时的路径指定到对应的目录下)数据表，<json 格式化>
CREATE  TABLE `uba_web_visit_log_20150326`(
  `uid` string COMMENT 'FROM deserializer',
  `ccid` string COMMENT 'FROM deserializer',
  `referer` string COMMENT 'FROM deserializer',
  `url` string COMMENT 'FROM deserializer',
  `guid` string COMMENT 'FROM deserializer',
  `client_time` string COMMENT 'FROM deserializer',
  `page_param` string COMMENT 'FROM deserializer',
  `client_param` string COMMENT 'FROM deserializer',
  `server_time` string COMMENT 'FROM deserializer',
  `ip` string COMMENT 'FROM deserializer',
  `agent` string COMMENT 'FROM deserializer')
ROW FORMAT SERDE
  'org.apache.hadoop.hive.contrib.serde2.JsonSerde'


3) 创建外部表(关键字 EXTERNAL)，分区表，并且指定表的路径 <FIELDS格式化',' COLLECTION格式化'\t'>
CREATE EXTERNAL TABLE student_index(
  sid int ,
  sname string
) PARTITIONed by (ds string)
ROW FORMAT DELIMITED
FIELDS TERMINATED BY '\001'
LINES TERMINATED BY '\n'
STORED AS TEXTFILE
-- 指定表的数据源，可写可不写
LOCATION 'hdfs://NameNode:8020/user/test/student_index';

```


### 3、导入数据

三种模式
* 从本地文件导入数据
* HDFS上导入数据到Hive表
* 从别的表中查询出相应的数据并导入到Hive表中

#### 3.1、 从本地文件导入数据

hive表默认的分隔符是'\001'

``` sql
*** 执行本地导入，OVERWRITE 表示覆盖
  hive> LOAD DATA LOCAL INPATH '/root/student_two.txt' OVERWRITE INTO TABLE student;

向分区表导入数据(ds为分区字段名称，ds=111 表示把匹配111的数据，导入到当前表目录的一个文件中)
  LOAD DATA LOCAL INPATH '/home/hadoop/DATA/student_index.log' OVERWRITE INTO TABLE student_index PARTITION(ds='111');

  查看 dfs 文件接口
  hive> dfs -ls /user/hive/warehouse/jASon_test.db/wyp;
```


#### 3.2、HDFS上导入数据到Hive表
``` sql
*** hdfs 导入
  hive> LOAD DATA INPATH '/home/wyp/add.txt' OVERWRITE INTO TABLE wyp;

```


#### 3.3、从别的表中查询出相应的数据并导入到 Hive 表中
``` sql
1) *** 如果需要分区插入设置线下
  hive> SET hive.exec.dynamic.PARTITION.mode=nonstrict;

2)*** 其他表导入数据(其中 age 是动态的分区)
  hive> INSERT INTO TABLE test
  PARTITION (age)
  SELECT id, name,tel, age FROM wyp;

```


### 4、hive TABLE 导出数据

三种方式
* （1）导出到本地文件系统
* （2）导出到 HDFS 中
* （3）导出到 Hive 的另一个表中


#### 4.1、导出到本地文件系统 <INSERT LOCAL directory>

``` sql
row format delimited
FIELDS TERMINATED BY '\001' 字段分隔符是 '\001'
LINES TERMINATED BY '\n' 行分隔符是 '\n'
语法：
INSERT OVERWRITE LOCAL directory <to_file_dir>
<row format delimited>
<FIELDS TERMINATED BY '\001'>
<LINES TERMINATED BY '\n'>
<fields>
<FROM_TABLE_name>

命令：
INSERT OVERWRITE LOCAL directory 'to_file_dir'
row format delimited
FIELDS TERMINATED BY '\001'
LINES TERMINATED BY '\n'
SELECT fields FROM FROM_TABLE_name;
```

#### 4.2、导出到 HDFS 中

#### 4.3、导出到 Hive TABLE 的另一个表中 <INSERT INTO TABLE>

``` sql
语法：
INSERT INTO TABLE
<to_TABLE_name>
<PARTITION (age='25')>
<fields>
<FROM_TABLE_name>

命令：
INSERT INTO TABLE uba_web_visit_log_template_20150331
SELECT * FROM uba_web_visit_log_20150331;


```


### 5、表说明

#### 5.1、表类型：

``` sql
1) 普通表
一个表，就对应一个表名对应的文件目录。

2) 外部表 关键字 EXTERNAL
内部表 : 在DROP的时候会从HDFS上删除数据，
外部表 : 在DROP的时候会从HDFS不会删除。

EXTERNAL 关键字可以让用户创建一个外部表，在建表的同时指定一个指向实际数据的路径（LOCATION），Hive 创建内部表时，会将数据移动到数据仓库指向的路径；若创建外部表，仅记录数据所在的路径，不对数据的位置做任何改变。在删除表的时候，内部表的元数据和数据会被一起删除，而外部表只删除元数据，不删除数据。具体sql如下

Sql代码
CREATE EXTERNAL TABLE `test_1`(
  id INT,
   name STRING,
   city STRING
)

3) 分区表
有分区的表可以在创建的时候使用 PARTITIONED BY 语句。一个表可以拥有一个或者多个分区，每一个分区单独存在一个目录下。而且，表和分区都可以对某个列进行 CLUSTERED BY 操作，将若干个列放入一个桶（bucket）中。也可以利用SORT BY 对数据进行排序。这样可以为特定应用提高性能.


分区表实际是一个文件夹，表名即文件夹名。每个分区，实际是表名这个文件夹下面的不同文件。分区可以根据时间、地点等等进行划分。比如，每天一个分区，等于每天存每天的数据；或者每个城市，存放每个城市的数据。每次查询数据的时候，只要写下类似 WHERE pt=2010_08_23这样的条件即可查询指定时间得数据
```

#### 5.2、注意事项

``` sql
Hive不支持一条一条的用INSERT语句进行插入操作，也不支持update的操作。数据是以LOAD的方式，加载到建立好的表中。数据一旦导入，则不可修改。要么DROP掉整个表，要么建立新的表，导入新的数据。

数据类型
TINYINT
SMALLINT  
INT  
BIGINT  
BOOLEAN
FLOAT  
DOUBLE  
STRING

如果数据需要压缩，使用 [STORED AS SEQUENCE] 。
默认采用 [STORED AS TEXTFILE]。
```


#### 6、视图

``` sql
创建视图
CREATE VIEW view_1 AS
SELECT
  id,
  name,
  quanpin
FROM
  db_sync.angejia__city;

删除视图
DROP VIEW IF EXISTS TABLE_name;


动态视图
CREATE IF NOT EXISTS VIEW TABLE_name AS
SELECT
  id,
  name,
  quanpin
FROM
  db_sync.angejia__city;

```
