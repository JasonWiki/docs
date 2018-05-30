# MySql 分区详解

### 一、注意事项


#### 1、是否支持分区技术：5.1以后的版本

```sql
1) mysql>show plugins;																//查看支持的插件
		partition | ACTIVE																	//表示支持

2) mysql> SHOW VARIABLES LIKE '%partition%';				//查看mysql是否支持分区
		have_partition_engine | YES													//结果为yes表示支持
```


#### 2、分区字段准一事项

```
1) 做分区时，要么不定义主键，要么把分区字段加入到主键中。

2) 分区用的字段不能为NULL，要不然怎么确定分区范围呢，所以尽量NOT NULL
```


#### 3、Innodb 表结构设置分区

- Innodb 默认为独立表空间，要设置成共享表空间，才可以适用分区技术


``` sh

1) vi /etc/my.cnf
	#在[mysqld]下设置
	innodb_file_per_table=1
  #以下可选
	#innodb_data_home_dir = E:\wamp\bin\mysql\mysql5.5.24\data\						//数据库存放文件目录
	#innodb_data_file_path = ibdata1:10M:autoextend
	#innodb_log_group_home_dir = E:\wamp\bin\mysql\mysql5.5.24\data\				//存放日志文件目录

2) 查看是否开启
	mysql> show variables like '%per_table%';
	innodb_file_per_table | ON 			//表示开启

  *最后测试，添加一个表，出现.frm  和 .ibd文件表示 成功
```


###	二、分区的类型选择


#### 1、RANGE 分区：（给予一个字段（如1991、1992、1993）为基点的分区）

- 基于属于一个给定连续区间的列值，把多行分配给分区。
- 使用环境：如按照店铺类型、用户类型、酒店类型等，其中一个字段进行分区，固定的有限的分区。


```sql
range分区
mysql>create table t_range(
	id int(11),
	money int(11) unsigned not null,
	date datetime
) partition BY RANGE (date) (
	partition p2007 values less than (2008),
	partition p2008 values less than (2009),
	partition p2009 values less than (2010)
	partition p2010 values less than maxvalue
);

```

#### 2、LIST 分区：（给予列里面的值进行分区，固定值，如：男女等枚举）

- 类似于按RANGE分区，区别在于LIST分区是基于列值匹配一个离散值集合中的某个值来进行选择。
 - 使用环境：一张表中有多个类型的数据，比如：一张表里有东部区域、西部区域、南部区域等数据的时候，以多个字段进行分区存储。
- 注意：
  - 对于innodb和myisam引擎，一条语句插入多条记录的时候，如果中间有值不能插入，innodb会全部回滚，myisam在错误值之前的数据可以插入到表中。

```sql
list分区
mysql>create table t_list(
	a int(11),
	b int(11)
) partition by list (b)
partition p0 values in (1,3,5,7,9),
partition p1 values in (2,4,6,8,0)
);
```


#### 3、HASH分区：（每次插入一列数据，平局分配到多个分区中）

- 基于用户定义的表达式的返回值来进行选择的分区，该表达式使用将要插入到表中的这些行的列值进行计算。这个函数可以包>含MySQL中有效的、产生非负整数值的任何表达式。
 - 使用环境：当列某个类型的数据，是变化的，不可确定的时候，进行HASH随机平局分配。比如：可以随机添加大类型的数据
- 注意：
 - hash的分区函数页需要返回一个<<<整数值>>>。partitions子句中的值是一个非负整数，不加的partitions子句的话，默认为分区数为1。


```sql
hash分区
mysql>create table t_hash(
　a int(11),
　b datetime
) partition by hash (YEAR(b) )
partitions 4;		//表示分配到4张表存储

```

#### 4、KEY分区：类似于按HASH分区，区别在于KEY分区只支持计算一列或多列，且MySQL服务器提供其自身的哈希函数。必须有一列或多列包含>整数值。



### 三、测试环境

```sql
mysql> -- 查看表创建的过程
show create table table_name;

mysql> -- 插入一行数据
INSERT INTO t2 (
  `cid`,
  `tid`
) VALUES (
  '1',
  '2'
);

mysql> -- 复制现有所有行数据，插入t2表中
insert into t2 select * from t2;

```
