# mysql to hdfs 性能测试方案

## test 数据表

``` sql

test.performance_mb
test.performance_gb

CREATE TABLE `performance_model`(
  `id` bigint,
  `s1` int,
  `s2` string,
  `s3` string,
  `s4` string)
  ROW FORMAT DELIMITED
  FIELDS TERMINATED BY '\t'
  COLLECTION ITEMS TERMINATED BY '\n'
;

```

## mysql 与 sqoop 时间对比


| type | table | rows | size | mr_num | args | start_time | end_time | seconds |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| sqoop |  performance_kb | (10,000) | 369 KB | 1 | null | 15:13:57 | 15:14:34 | 37
| sqoop |  performance_kb | (10,000) | 369 KB | 1 | --direct | 15:30:18 | 15:30:49 | 31
| sqoop |  performance_kb | (10,000) | 369 KB | 4 | null | 15:16:54 | 15:17:39 | 45 |
| sqoop |  performance_kb | (10,000) | 369 KB | 4 | --direct | 15:31:05 | 15:31:43 | 38 |
| sqoop |  performance_kb | (10,000) | 369 KB | 10 | null | 15:18:01 | 15:18:51 | 50 |
| sqoop |  performance_kb | (10,000) | 369 KB | 10 | --direct | 15:32:11 | 15:32:44 | 33 |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| sqoop |  performance_mb_5 | (158,600) | 5.5 MB  | 1 | null | 15:19:37 | 15:20:18 | 41 |
| sqoop |  performance_mb_5 | (158,600) | 5.5 MB  | 1 | --direct | 15:33:30 | 15:33:57 | 27 |
| sqoop |  performance_mb_5 | (158,600) | 5.5 MB  | 4 | null | 15:21:19 | 15:21:53 | 34 |
| sqoop |  performance_mb_5 | (158,600) | 5.5 MB  | 4 | --direct | 15:34:10 | 15:34:46 | 36 |
| sqoop |  performance_mb_5 | (158,600) | 5.5 MB  | 10 | null | 15:22:04 | 15:22:34 | 30 |
| sqoop |  performance_mb_5 | (158,600) | 5.5 MB  | 10 | --direct | 15:35:04 | 15:35:36 | 32 |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| sqoop |  performance_mb_50 | (2,000,000) | 67.5 MB  | 1 | null | 15:23:11 | 15:23:54 | 43 |
| sqoop |  performance_mb_50 | (2,000,000) | 67.5 MB  | 1 | --direct | 15:36:53 | 15:37:25 | 32 |
| sqoop |  performance_mb_50 | (2,000,000) | 67.5 MB  | 4 | null | 15:24:10 | 15:25:01 | 51 |
| sqoop |  performance_mb_50 | (2,000,000) | 67.5 MB  | 4 | --direct | 15:37:41 | 15:38:07 | 26 |
| sqoop |  performance_mb_50 | (2,000,000) | 67.5 MB  | 10 | null | 15:25:23 |  15:26:10 | 47 |
| sqoop |  performance_mb_50 | (2,000,000) | 67.5 MB  | 10 | --direct | 15:38:29 | 15:38:58 | 29 |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| sqoop |  performance_mb | (16,777,216) | 561 MB | 1 | null | 15:27:36 | 15:29:04 | 88 |
| sqoop |  performance_mb | (16,777,216) | 561 MB | 1 | --direct | 15:47:15 | 15:48:18 | 63 |
| sqoop |  performance_mb | (16,777,216) | 561 MB | 4 | null | 15:33:44 | 15:34:47 | 63 |
| sqoop |  performance_mb | (16,777,216) | 561 MB | 4 | --direct | 15:49:09 | 15:49:48 | 39 |
| sqoop |  performance_mb | (16,777,216) | 561 MB | 10 | null | 15:36:41 | 15:37:30 | 49 |
| sqoop |  performance_mb | (16,777,216) | 561 MB | 10 | --direct | 15:50:16 | 15:50:50 | 34 |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| sqoop |  performance_gb | (33,554,432) | 1.1 GB | 1 | null | 15:39:10 | 15:40:49 | 99 |
| sqoop |  performance_gb | (33,554,432) | 1.1 GB | 1 | --direct | 15:53:25 | 15:54:38 | 73 |
| sqoop |  performance_gb | (33,554,432) | 1.1 GB | 4 | null | 15:41:24 | 15:42:33 | 69 |
| sqoop | performance_gb | (33,554,432) | 1.1 GB | 4 | --direct | 15:55:16 | 15:56:09 | 53 |
| sqoop |  performance_gb | (33,554,432) | 1.1 GB | 10 | null | 15:43:00 | 15:43:58 | 58 |
| sqoop |  performance_gb | (33,554,432) | 1.1 GB | 10 | --direct | 15:56:27 | 15:57:08 | 40 |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| mysql |  performance_kb | (10,000) | 369 KB  | mysql -s -e | null | 15:05:00 | 15:05:01 | mysql(1) +  hdfs put(2) + create hive table(1) = 4 |
| mysql |  performance_mb_5 | (158,600) | 5.5 MB  | mysql -s -e | null | 15:07:00 | 15:07:01 | mysql(1) +  hdfs put(3) + create hive table(1) = 5 |
| mysql |  performance_mb_50 | (2,000,000) | 67.5 MB  | mysql -s -e | null | 13:49:10 | 13:49:58 | mysql(4) +  hdfs put(4) + create hive table(1) = 8 |
| mysql |  performance_mb | (16,777,216) | 561 MB | mysql -s -e | null | 13:35:00 | 13:35:24  | mysql(24) + hdfs put(12) + create hive table(1) = 37 |
| mysql |  performance_gb | (33,554,432) | 1.1 GB  | mysql -s -e | null | 13:49:10 | 13:49:58 | mysql(48) +  hdfs put(17) + create hive table(1) = 66 |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| mysql |  performance_mb | (16,777,216) | 561 MB | mysqldump | null | null | null | mysql(13.48) + hdfs put(12) + create hive table(1) = 26.48 |
| mysql |  performance_gb | (33,554,432) | 1.1 GB  | mysqldump | null | null | null | mysql(28.84) +  hdfs put(20) + create hive table(1) = 49.84 |


```
总结下来:
注意：使用 --direct 会有中文乱码问题

1、当 mysql 的数据量在 100 MB 以下，使用 mysql -s -e 写到 hdfs 最快

2、当 mysql 的数据量在 大于 500 MB 以后，使用 sqoop, 开 10 个 mp, 使用 --direct 最快

3、当 mysql 的数据量在 大于 1 GB 以后，使用 sqoop, 开 10 个 mp, 使用 --direct 最快



```


## 增量数据对比
```

-- 44.713 + 20~
SELECT
  *
FROM
  -- 10,000
  performance_kb AS t_1
LEFT JOIN
  -- 158,600
  performance_mb_5 AS t_2
ON
  t_1.id = t_2.id

LIMIT 100
;


-- 47.448 + 30~
SELECT
  *
FROM
  -- 158,600
  performance_mb_5 AS t_1
LEFT JOIN
  -- 2,000,000
  performance_mb_50 AS t_2
ON
  t_1.id = t_2.id

LIMIT 100
;



-- 197.095 + 40~
SELECT
  *
FROM
  -- 16,777,216
  performance_mb  AS t_1
LEFT JOIN
  -- 33,554,432
  performance_gb AS t_2
ON
  t_1.id = t_2.id

LIMIT 100
;


总结
光计算
KB 级别的累加大概要 60S 左右
MB 级别的累加大概要 80S 左右
GB 级别的累加大概要 197S 左右


```
