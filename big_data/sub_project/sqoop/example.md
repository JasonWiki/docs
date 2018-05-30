# example 案例


## import


###  常用

``` sh
sqoop import

--connect "jdbc:mysql://${mysql_host}:3306/${mysql_database}?useUnicode=true&tinyInt1isBit=false&characterEncoding=utf-8" \
--username '${mysql_user}' \
--password '${mysql_password}' \
--table '${mysql_table}' \
--hive-table '${hive_database}.${hive_table}' \
--hive-import \

--hive-delims-replacement '%n&' \
--fields-terminated-by '\001' \
--lines-terminated-by '\n' \

--input-null-string '\\N' \
--input-null-non-string '\\N' \
--null-string '\\N' \
--null-non-string '\\N' \

--outdir /home/dwadmin/test/jason/uba/scripts/shell/tool/../.tmp \
--target-dir /umr-jdlg4d/temp/sqoop-target/agent_team \
--delete-target-dir \

--num-mappers 1;

```

### 1、按照最大 id 之后的数据抽取

``` sh
sqoop import \
--connect "jdbc:mysql://192.168.160.54:3306/angejia?useUnicode=true&tinyInt1isBit=false&characterEncoding=utf-8" \
--username angejia \
--password angejia123 \
\
#导入执行参数
--target-dir /user/temp/sqoop-target/user_msg \
--num-mappers 4 \
--table user_msg \
--hive-table jason_test.user_msg \
--hive-import \
--hive-delims-replacement '%n&' \
\
#列行分隔符
--fields-terminated-by '\t' \
--lines-terminated-by '\n' \
\
--input-null-string '\\N' \
--input-null-non-string '\\N' \
--null-string '\\N'
--null-non-string '\\N'
--outdir /home/hadoop/test/jason/uba/scripts/shell/tool/../.tmp \
\
#抽取主键
--check-column 'msg_id' \
--incremental 'append' \
--last-value '10' \
\
#分区字段
--hive-partition-key 'p_dt' \
--hive-partition-value '2015-07-16'

```


### 2、按照 id 范围抽取

``` sh

把查询结果写入到 mysql 中，格式如下
table_name | primary_id | min_num | max_num | p_dt

每次导入前，查询上一天的 max_num 数量,作为当天 min_num 的值,查询当天 max_num 写入到数据库
当天 拼接 min_num 和 max_num 作为查询条件,去 mysql 查询这个边界的数据，写入到 hive 中

sqoop import \
--connect "jdbc:mysql://192.168.160.54:3306/angejia?useUnicode=true&tinyInt1isBit=false&characterEncoding=utf-8" \
--username angejia \
--password angejia123 \
\
--target-dir /user/temp/sqoop-target/user_msg \
--delete-target-dir \
--num-mappers 1 \
--table user_msg \
--hive-table jason_test.user_msg \
--hive-import \
--hive-delims-replacement '%n&' \
--split-by 'msg_id' \
#获取最小最大 ID
--boundary-query "select 11, 30" \
\
--fields-terminated-by '\001' \
--lines-terminated-by '\n' \
\
--input-null-string '\\N' \
--input-null-non-string '\\N' \
--null-string '\\N'
--null-non-string '\\N'
--outdir /home/hadoop/test/jason/uba/scripts/shell/tool/../.tmp \
\
--hive-partition-key 'p_dt' \
--hive-partition-value '2015-07-17'

```
