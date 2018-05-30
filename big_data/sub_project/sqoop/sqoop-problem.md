# Sqoop 问题总结

## 注意事项

- sqoop 很强大，但是我们在向数据库导入数据的时候，为了避免很多问题，我们有几个特别注意的地方和原则
	- 操作要导入导出的表之前需要用 show create table 查看表结构.
	看清楚字段和行的分隔符后，指定给 sqoop ,可以避免很多解析错误
	- 对要导出的 mysql table 适用 varchar(255) 或者更宽松的字符串，并且允许 null ，也可以避免
	导出时 null 值出现 \N 以及导入不进去 mysql 的情况

## 一、关于分隔符

### 1、默认分隔符

#### 问题
	sqooq使用','分隔符会和数据中的冲突
#### 解决
	使用\001
	import时用:--fields-terminated-by '\001'
	export时用:--input-fields-terminated-by '\001'


## 二、关于字符编码

### 1、字符编码 utf-8
#### 问题
	sqoop支持的编码参数  --default-character-set=utf-8
	测试下来不起作用
#### 解决
	使用jdbc的编码导入导出数据
	sqoop import --connect "jdbc:mysql://10.10.2.91/hadoop_test?useUnicode=true&characterEncoding=utf-8"
	sqoop export --connect "jdbc:mysql://10.10.2.91/hadoop_test?useUnicode=true&characterEncoding=utf-8"

### 2、出现 \N 的情况
#### 问题
	sqoop 向 Mysql 导入数据的时候会把空的值转换为 \N

#### 解决
##### 2.1、mysql 的字段必须是允许为null的，如：
```
CREATE TABLE `dw_touch_traffic_20150401` (
  `user_id` varchar(255) DEFAULT '',
  `ccid` varchar(255) DEFAULT '',
  `referer_page` varchar(255) DEFAULT '',
  `current_page` varchar(255) DEFAULT '',
  `query_page` varchar(255) DEFAULT '',
  `user_visit_hour` varchar(255) DEFAULT '',
  `user_visit_minute` varchar(255) DEFAULT '',
  `user_visit_second` varchar(255) DEFAULT '',
  `guid` varchar(255) DEFAULT '',
  `page_content` varchar(255) DEFAULT '',
  `client_content` varchar(255) DEFAULT '',
  `client_ip` varchar(255) DEFAULT '',
  `os_type` varchar(255) DEFAULT '',
  `os_version` varchar(255) DEFAULT '',
  `brower_type` varchar(255) DEFAULT '',
  `brower_version` varchar(255) DEFAULT '',
  `phone_type` varchar(255) DEFAULT '',
  `cal_dt` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

#####	2.2 sqloop 导入数据的时候要跟参数
-input-null-string '\\N' -input-null-non-string '\\N';

```
sqoop export -connect "jdbc:mysql://${mysql_host}/${mysql_database}?useUnicode=true&characterEncoding=utf-8" -username ${mysql_user} -password ${mysql_password} -table ${uba_web_visit_table} -export-dir ${hadoop_uba_web_visit_log_dir} -fields-terminated-by '\001' -input-null-string '\\N' -input-null-non-string '\\N';
```



## 二、关于数据类型

### 1、tinyint

#### 问题
	mysql导入hive时
	tinyint(1) 导入会有问题，0->false 1->true. 但是tinyint(2) 就不会有问题。

#### 解决
	方法1、sqoop import --connect "jdbc:mysql://10.10.2.91/hadoop_test?tinyInt1isBit=false"
	方法2、--map-column-java fieldName=Integer
				--map-column-hive RAW_TYPE_ID=STRING

### 2、mysql date 数据类型
#### 问题
	mysql 中 date 类型，且数据值为'0000-00-00' -> hdfs 为 NULL.

#### 解决
	sqoop import --connect "jdbc:mysql://10.10.2.91/hadoop_test?zeroDateTimeBehavior=round"
	则：convertToNull -> round , 0000-00-00 -> 0001-01-01

### 3、UNSIGNED columns

	sqoop不支持unsigned，所以导入hive后数据范围发生变化

### 4、BLOB and CLOB columns
	sqoop暂时不支持blob类型

### 5、hive和sql的数据类型匹配
	DATE,TIME,TIMESTAMP 会被当做是字符串处置， NUMERIC和DECIMAL会被认为是double


## 三、关于一些方法参数

### 1、库和表
```
1) import-all-tables 到指定库

2) --hive-database 参数指定hive的库
```


## 三、系统配置问题

### 1、无法导入报错问题 (hadoop -> core-site.xml )
```
1) 可能是压缩算法问题

<!--
property>
  	<name>io.compression.codecs</name>
    	<value>org.apache.hadoop.io.compress.DefaultCodec,com.hadoop.compression.lzo.LzoCodec,com.hadoop.compression.lzo.LzopCodec,org.apache.hadoop.io.compress.GzipCodec,org.apache.hadoop.io.compress.BZip2Codec</value>
</property
-->
```

### 2、sqoop 连接的 hive 客户端需要配置 Zookeeper  (hive -> hive-site.xml)
```
<property>
  <name>hive.zookeeper.quorum</name>
    <description>Zookeeper quorum used by Hive's Table Lock Manager</description>
      <value>10.10.69.129</value>
</property>
```
