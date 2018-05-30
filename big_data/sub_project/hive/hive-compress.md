# hive 开启压缩

## 一、性能测试

- hive-1.1.0-cdh5.4.4
- hadoop-2.6.0-cdh5.4.4
- 文件 1200 M

| 压缩类型 | 压缩后的数据 | 压缩写入时间 | 查询解压时间 |
| --- | --- | --- | --- | --- | --- |
| 原始文件 | 1200 M (100%)  |  100s  |  32 s |
| BZip2 | 132 M (11%)  |  190 s  |  40 s |
| Snappy | 428 M (36%)  |  54 s  |  20 s |
| Hive ORC 文件格式 | 50 M (4%)  |  90 s  |  20 s |


## 二、开启压缩

- MapReduce 压缩选择
  - [Snappy](http://www.cloudera.com/content/www/zh-CN/documentation/enterprise/5-3-x/topics/cdh_ig_snappy_mapreduce.html)
  - BZip2
- Hive 压缩选择
  - [ORC Apache 文档](https://cwiki.apache.org/confluence/display/Hive/LanguageManual+ORC)
  - [ORC 使用指南](http://lxw1234.com/archives/2016/04/630.htm)

- 优化的 [Hive Sql](technology/hadoop-docs/sub-project/hive/conf/optimize/hive_sql_optimize.sql)

## 三、压缩后的效果

| 压缩库 | 压缩前数据 | 压缩后数据 |
| --- | --- | --- |
| access_log | 294.7 G 884.0 G  | 121.6 G  364.9 G  |
| db_gather | 369.1 G  1.1 T  | 66.1 G  198.2 G |
