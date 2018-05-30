# Sqoop

## 一、简介

### 1、用处
- Sqoop 是一个数据库导入导出工具，可以将数据从 hadoop hdfs 导入到关系数据库，或从关系数据库将数据导入到 hadoop hdfs 中。

### 2、特点
- Sqoop 中一大亮点就是可以通过 hadoop 的 mapreduce 把数据从关系型数据库中导入数据到HDFS。

### 3、文章
- 文档手册：http://www.zihou.me/html/2014/01/28/9114.html
- 使用手册：http://www.zihou.me/html/2014/01/28/9119.html

### 4、版本问题
- Sqoop1和Sqoop2对比

- 两个不同的版本，完全不兼容
 - 版本号划分区别：

```
Apache版本：
  1.4.x(Sqoop1);
  1.99.x(Sqoop2)

CDH版本 :
  Sqoop-1.4.3-cdh4(Sqoop1) ;
  Sqoop2-1.99.2-cdh4.5.0 (Sqoop2)

Sqoop2 比 Sqoop1 的改进
  引入 Sqoop server，集中化管理 connector(连接器) 等
  多种访问方式：CLI,Web UI，REST API
  引入基于角色的安全机制
```


### 5、构架图

![image](imgs/sqoop1.png)
![image](../../imgs/sqoop1.png)

![image](imgs/sqoop2.png)
![image](../../imgs/sqoop2.png)

## 二、操作

### 注意事项

请先阅读 sqoop-partition.md 会让你少走一些弯路

```
1) hive 中为 null 的是以 \N 代替的
解决 : -input-null-string '\\N' -input-null-non-string '\\N'

2) hive 表的 fields 默认用 \001 分割
解决 ： 看文档 sqoop-problem.md

```

### 1、命令集

- 参数详细介绍 : [sqoop-arguments](sqoop-arguments.md);

```
1、codegen

2、create-hive-table

3、eval

4、export

5、import

6、import-all-tables

7、job

8、list-databases

9、list-tables

10、merge

11、metastore

12、version

13、help
```

### 2、命令集介绍

#### 2.1、codegen
将关系数据库表映射为一个java文件，且能生成class类、以及相关的jar包，作用主要是两方面：

1、将数据库表映射为一个Java文件，在该Java文件中对应有表的各个字段。

2、生成的 Jar 和 class 文件在 hive metastore(元数据库：Mysql等) 功能使用时会用到。

```
1) 导出 mysql sqoop 数据库中 broker 表的结构到 broker.java 文件

sqoop codegen -connect jdbc:mysql://hostname:3306/sqoop -username test -password test -table broker
```


#### 2.2、create-hive-table
生成与关系数据库表的表结构对应的HIVE表

具体导入到哪里看 hive-site.xml 配置文件

```
1) 把线上的 mysql sqoop 数据库中 broker 表的的结构导入到 hive 中
sqoop create-hive-table -connect jdbc:mysql://hostname:3306/sqoop -username test -password test -table broker -hive-table h_broker

```

#### 2.3、eval
可以快速地使用SQL语句对关系数据库进行操作，这可以使得在使用import这种工具进行数据导入的时候，可以预先了解相关的SQL语句是否正确，并能将结果显示在控制台。

```
1) 直接执行 sql (*时,字段太多会无法显示,可以获取少量字段)
sqoop eval --connect jdbc:mysql://hostname:3306/sqoop -username test -password test --query "select * from broker limit 1";

```

#### 2.4、export 导出到 关系数据库

从hdfs中导数据到关系数据库中

```
sqoop export --connect jdbc:mysql://hostname:3306/sqoop -username test -password test -table m_broker -export-dir /user/hive/warehouse/jason_test.db/h_broker_2 -input-fields-terminated-by '\001' -input-lines-terminated-by '\n' -input-null-string '\\N' -input-null-non-string '\\N' -outdir /tmp;


***带分隔符***
-fields-terminated-by 字段'\001'号分割
-lines-terminated-by '\n' 换行分割

sqoop export --connect jdbc:mysql://hostname:3306/sqoop -username test -password test -table m_broker -export-dir /user/hive/warehouse/jason_test.db/h_broker_2 -input-fields-terminated-by '\001' -input-lines-terminated-by '\n' -input-null-string '\\N' -input-null-non-string '\\N' -outdir /tmp;

```


#### 2.5、import 导入到 hive
将关系数据库的数据导入到 hive 中，如果在 hive 中没有对应的表，则自动生成与关系数据库表名相同的表。


```
参数
–query 支持从关系型数据库的结果集导出数据到 mysql
-hive-overwrite 覆盖插入
-hive-table 指定存放地点[数据库.表名 | 表名]
  如 jason_test.h_broker

1) 把关系数据库 sqoop broker 表 导入到 hive h_broker 表中
sqoop import -connect jdbc:mysql://hostname:3306/sqoop -username test -password test -table broker -hive-table h_broker -hive-import -split-by user_id -input-null-string '\\N' -input-null-non-string '\\N' -outdir /tmp;

***带分隔符***
-fields-terminated-by 字段'\001'号分割
-lines-terminated-by '\n' 换行分割
sqoop import -connect jdbc:mysql://hostname:3306/sqoop -username test -password test -table broker -hive-table jason_test.h_broker_2 -hive-import -fields-terminated-by '\001' -lines-terminated-by '\n' -input-null-string '\\N' -input-null-non-string '\\N' -outdir /tmp;



2) 增量导入
***增量导入参数***
-incremental [append]
-check-column user_id  用来作为判断的列名，如 user_id
-last-value 指定自从上次导入后列的最大值（大于该指定的值），如上次导入的最大 ID

sqoop import -connect jdbc:mysql://hostname:3306/sqoop -username test -password test -table broker -hive-table h_broker -hive-import -split-by user_id -incremental append -check-column user_id -last-value 2000001738 -input-null-string '\\N' -input-null-non-string '\\N'

3) 直接累加导入
sqoop import -connect "jdbc:mysql://hostname:3306/dw_db?useUnicode=true&characterEncoding=utf-8" -username test -password test -table dw_basis_dimension_filter_ip -hive-table jason_test.dw_basis_dimension_filter_ip -hive-import -fields-terminated-by '\001' -lines-terminated-by '\n' -input-null-string '\\N' -input-null-non-string '\\N' -split-by id -append -input-null-string '\\N' -input-null-non-string '\\N';


```


#### 2.6 import-all-tables 导入所有数据 hfds 中

将数据库里的所有表导入到HDFS中，每个表在hdfs中都对应一个独立的目录

```
1) 导入关系数据库 sqoop 所有到 hive
sqoop import-all-tables --connect jdbc:mysql://hostname:3306/sqoop -username test -password test  -hive-import -input-null-string '\\N' -input-null-non-string '\\N'

***这个参数会出问题，导入数据只显示 hfds 中但不在hive表中***
-warehouse-dir 与–target-dir不能同时使用，指定数据导入的存放目录，适用于hdfs导入，不适合导入hive目录
```


#### 2.7 显示关系数据库信息
```
1) 显示所有数据库
sqoop list-databases -connect jdbc:mysql://hostname:3306/ -username test -password test


2) 显示 sqoop 数据库中所有的表
sqoop list-tables -connect jdbc:mysql://hostname:3306/ -username test -password test

```

### 参数
```
--map-column-hive field_name=STRING 把字段替换成指定的类型
--split-by fiele_id MySQL 主键
-m 2 设置 map reduce 数量，不要大于集群数
```
