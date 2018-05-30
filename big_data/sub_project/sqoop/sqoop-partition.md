# sqoop 从 mysql 向 hive 分区表导入数据

## 一、解决方案
- sqoop 关键字 ： --hive-partition-key KEY  --hive-partition-value VALUE
 - 这种方式只适合只有一个分区字段的表
- 中转方式：
 - 先把 mysql 通过 sqoop 导入到 hive_tmp_table 一张临时表中
 - 然后从 hive_tmp_table 中把数据导入到指定的 hive 分区表中


## 二、sqoop 关键字

sqoop import -connect jdbc:mysql://CDH-Manager:3306/sqoop -username test -password test -table broker -hive-table jason_test.h_broker_2 -hive-import -fields-terminated-by '\001' -lines-terminated-by '\n' -input-null-string '\\N' -input-null-non-string '\\N' -append --hive-partition-key KEY --hive-partition-value  VALUE;

## 三、中转方式

- 按照 sqoop.md 文档把 mysql 数据库的数据导入到 hive_tmp_table 中
- 再按照 hive-partition.md 文档把 hive_tmp_table 导入到目标分区表中
