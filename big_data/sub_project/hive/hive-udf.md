# hive-udf

## 参考资料

- 官方文档 ：https://cwiki.apache.org/confluence/display/Hive/LanguageManual+UDF
- 其他文档 ：http://lxw1234.com/archives/2015/06/251.htm

## 一、集合

### 1、collect_set

- 返回一组对象,消除重复的元素。

``` sql

collect_set(app_name)[0]

案例
SELECT
  user_id,
  -- 返回一组对象,消除重复的元素。
  collect_set(app_name)[0],
  -- size 函数获取集合的长度
  size(collect_set(app_name)) AS app_name_cn
FROM
  dw_db.dw_app_access_log
WHERE
    user_id <> ''
  AND
    app_name IN ('a-broker','i-broker')
  AND
    p_dt = ${dealDate}
GROUP BY
  user_id
;

```


### 2、ROW_NUMBER

- 返回一行的顺序号的结果集的一个分区中，从1开始的每个分区中的第一行。
 - 人话[按照指定规则(如 OVER)，返回行出现的次数]
- [案例](http://blog.csdn.net/yangjun2/article/details/9339641)

``` sql

-- 语法
ROW_NUMBER() OVER (
    -- 分区规则
    PARTITION BY
      [filed]
    ORDER BY
      [ts] DESC
  ) AS [recent_click]


-- 使用场景，排重重复数据
SELECT
  user_id,
  app_name
FROM (
  -- 拿出排序好的结果集
  SELECT
    s_1.user_id,
    s_1.app_name,

    ROW_NUMBER ()
      -- OVER 表示规则
      OVER (
        -- 先对 user_id 进行类似 GROUP BY 操作
        PARTITION BY
          s_1.user_id
        -- 再按照时间排序  
        ORDER BY
          s_1.date_time
        DESC
      ) AS row_num
  FROM
    demo_table_1 AS s_1
) AS t_1

-- 再把排名好的结果集，第一名的拿出来
WHERE
  t_1.row_num = 1
;


-- 或者使用 distribute by
SELECT
  t_1.user_id,
  t_1.app_name
FROM (
  SELECT
    s_1.user_id,
    s_1.app_name,
    ROW_NUMBER ()
      OVER (
        DISTRIBUTE BY
          s_1.user_id
        SORT BY
          s_1.time
        DESC
      ) AS row_num
    FROM
      demo_table_1 AS s_1
) AS t_1

WHERE
  t_1.row_num = 1
;
```


## 二、字符串类

### 1、regexp_extract

- 正则匹配截取

``` sql

SELECT
  current_page,
  -- 把括号内匹配到的内容截取出来
  regexp_extract(current_page,'^/broker/sh_([1-9]+)/$',1) AS broker_id
FROM
  dw_db.dw_web_visit_traffic_log
WHERE
    p_dt = '2015-06-11'
  AND
    current_page RLIKE '^/broker/sh_[1-9]+/$';
```


### 2、CONCAT_WS

- 数组拼接成字符串

``` sql

-- 案例 1, 直接使用
SELECT
  CONCAT_WS(',',device_id) AS device_ids
FROM
  dw_db.dw_app_access_log
WHERE
    p_dt='2015-06-18'
  AND
    user_id <> ''
GROUP BY
  user_id,
  device_id
;

-- 案例 2,用 collect_set(会去除重复的组) 把统计分组后的行，合并到列中
SELECT
  CONCAT_WS(',',COLLECT_SET(device_id)) AS device_ids
FROM
  dw_db.dw_app_access_log
WHERE
    p_dt='2015-06-18'
  AND
    user_id <> ''
GROUP BY
  user_id
;

```

### 3、COALESCE

- Returns the first v that is not NULL, or NULL if all v's are NULL.

``` sql
SELECT
  -- 当 a 为 null,使用 b 的值。或者当 b 的值为 null 使用 c 的值，以此类推
  COALESCE(t_1.a,t_1.b,t_1.c) AS n,
FROM
  table_1 AS t_1

```

### 4、split

- 把字符串，分割成数组

``` sql
SELECT
  split(request_uri,'\\?')[0] AS n
FROM
  table_1 AS t_1

```

### 5、concat

- 组合字符串

``` sql

SELECT
  concat (t_1.a,',',t_2.b) AS n
FROM
  table_1 AS t_1


```

### 6、parse_url

- 截取URL

``` sql
URL:
http://m.angejia.com/sale/sh_minhang_meilong/?pi=uc-cpc-esfwap-sh-jingpin&utm_term=爱屋吉屋二手房&city_id=1&defaultLimit=10&limit=10&page=1


案例 1,截取 utm_term 的值
parse_url(current_full_url,'QUERY','utm_term')

案例 2,截取 值 并且转换为中文字符
java_method("java.net.URLDecoder", "decode",parse_url(current_full_url,'QUERY','utm_term'),'utf-8') AS china


案例 3
parse_url(url, partToExtract[, key]) - extracts a part from a URL
解析URL字符串，partToExtract的选项包含[HOST,PATH,QUERY,REF,PROTOCOL,FILE,AUTHORITY,USERINFO]。
举例：
* parse_url('http://facebook.com/path/p1.php?query=1', 'HOST')返回'facebook.com'
* parse_url('http://facebook.com/path/p1.php?query=1', 'PATH')返回'/path/p1.php'
* parse_url('http://facebook.com/path/p1.php?query=1', 'QUERY')返回'query=1'，
可以指定key来返回特定参数，例如
* parse_url('http://facebook.com/path/p1.php?query=1', 'QUERY','query')返回'1'，

* parse_url('http://facebook.com/path/p1.php?query=1#Ref', 'REF')返回'Ref'
* parse_url('http://facebook.com/path/p1.php?query=1#Ref', 'PROTOCOL')返回'http'

```

### 7、regexp_replace

``` sql
-- 替换
  regexp_replace('2016-01-22','-','');
```

### 8、字符串转义

``` sql
1) 分号 ; 号用 \073 转义  

```

### 9、substr 和 length 截取字符串

``` sql

SELECT
  row_key,
  -- 从第 0 个字符到最后一个字符 -1 的字符串
  substr(field,0,length(field)-1)
FROM
  table
LIMIT 1;
```



## 三、 Date 函数

### 1、unix_timestamp

``` sql
1.unix_timestamp() 获取当前时间戳

2.unix_timestamp(string date) 获取这种日期格式的时间戳
  案例
  unix_timestamp('2009-03-20 11:30:01')

3.unix_timestamp(string date, string pattern) 获取指定格式的时间戳
  案例
  unix_timestamp('2009-03-20', 'yyyy-MM-dd') = 1237532400

4. date_sub(current_date(),1); 昨天日期
```

### 2、from_unixtime

``` sql

时间戳转换日期

1.格式
  yyyy-MM-dd HH:mm:ss

  from_unixtime(1440409548,'yyyy-MM-dd HH:mm:ss')

```

### 3. 时间技巧

``` sql

1. 对分区进行时间范围查询时

 表分区格式: p_dt = '2017-02-18', p_hours = '11',

 -- 组合分区成为一个日期格式
 WHERE concat(p_dt, ' ', p_hours, ':00', ':00')
  -- 定位 1 个小时前日期格式
  BETWEEN from_unixtime(unix_timestamp("2017-02-17 11:00:00")-3600,'yyyy-MM-dd HH:mm:ss')
  -- 定位当前小时日期格式
  AND concat('2017-02-17', ' ', '11', ':00', ':00')



2. date_format 自定义的日期 UDF

 表分区格式: p_dt = '2017-02-18', p_hours = '11',

 WHERE concat(p_dt, ' ', p_hours) BETWEEN date_format('2017-02-18 11',"yyyy-MM-dd HH","yyyy-MM-dd HH", '-', 3600) AND '2017-02-18 11'

```


## 四、Obj 函数

### 1、get_json_object

- 解析字段的字符串的 json 对象

``` sql
1) 解析 json
  SELECT
    get_json_object(desc,'$.hash') AS hash
  FROM
    table
  LIMIT
    10;


```


## 五、 复合类型

- map
- struct
- array

### 1、array

``` sql

-- 把行的数据，塞到一列中
CREATE TABLE array_test_1 AS  
SELECT
  ARRAY(  
  id,
  name ) AS all_col_data
FROM
  source_table
LIMIT 2;

-- 查询所有
SELECT * FROM array_test_1;
-- 访问某个值
SELECT all_col_data[0] FROM array_test_1 LIMIT 10;

-- 把列中数据，都按照行输出
SELECT
  explode(all_col_data) AS all_1
from
  source_table;


-- posexplode 对一列的数据，按照数据打印，每次从 1 开始
SELECT
  posexplode(all_col_data) AS  (pos, col_data)
from
  source_table;


-- 格式化输出,列转行 (Column列, Row 行)
  SELECT
    'aaa' AS a,
    -- lateral view 处理好 展示的列
    row_list
  FROM
    array_test_1 AS t_1
  -- 列转行成为行显示, OUT 表示如果列为 Null, 也展示
  lateral view [OUT]
    -- 分割成数组, 会把数组映射到每一行中
    -- 如果是字符串可以用: split 分割字符为数组(如果是 ; 分号会报错,需要转义 \073, 或者 \;)
    explode (数组列) now_row_list AS row_list;


```


### 2、map

``` sql

-- 把行的数据，塞到一列中
CREATE TABLE map_test_1 AS  
SELECT
  MAP(
  'id',id,
  'name',name
   ) AS all_col_data
FROM
  source_table
LIMIT 2;

-- 查询所有
SELECT * FROM map_test_1;
-- 查询某个键下的数据
SELECT all_col_data['id'] FROM map_test_1 LIMIT 10;

-- 把列中数据，都按照行输出
SELECT
  -- key_name , value_name 表示输出到列的字段名
  explode(all_col_data) AS (key_name , value_name)
FROM
  map_test;

-- 或者不写也可以  
SELECT
  explode(all_col_data)
FROM
  map_test;

```


### 3. ARRAY 复合类型应用技巧

``` sql

1. 同级的两个列数组, 列转行

  两个字段都是数组或者符号分割的字符比如

  district_id 字段是: 13;13;13;13;13;13;13
  block_id 字段是:    126;130;183;125;127;184;2252
  这两个字段是一一对应的, 现在把 2 个字段分别对应到 a 列 , b 列,  一行行排开, 要的数据格式是

  user_id |  a   |  b
      1   |  13  |  126
      1   |  13  |  130
      1   |  13  |  183
      1   |  13  |  125
      1   |  13  |  127

  -- 实际案例
  -- 第一列
  DROP TABLE IF EXISTS dw_db_temp.jason_test_1;
  CREATE TABLE dw_db_temp.jason_test_1 AS
  SELECT
    user_id,
    city_id,
    district_list,
    -- ROW_NUMBER, 为每一行打上定位 index
    ROW_NUMBER ()
      OVER (
        PARTITION BY
        user_id,
        city_id
     ) AS pos_index
  FROM db_sync.angejia__member_demand
  -- 字符串分割成数组,一行行排开
  lateral view explode(split(district_id,'\;')) now_district_list AS district_list
  ;

  -- 第二列
  DROP TABLE IF EXISTS dw_db_temp.jason_test_2;
  CREATE TABLE dw_db_temp.jason_test_2 AS
  SELECT
    user_id,
    city_id,
    block_list,
    -- ROW_NUMBER, 为每一行打上定位 index
    ROW_NUMBER ()
      OVER (
        PARTITION BY
        user_id,
        city_id
     ) AS pos_index
  FROM db_sync.angejia__member_demand
  -- 字符串分割成数组,一行行排开
  lateral view explode(split(block_id,'\;')) now_block_list AS block_list
  ;

  -- 组合数据
  DROP TABLE IF EXISTS dw_db_temp.jason_test_3;
  CREATE TABLE dw_db_temp.jason_test_3 AS
  SELECT
    a.user_id,
    a.city_id,
    a.district_list,
    b.block_list
  FROM dw_db_temp.jason_test_1 AS a
  LEFT JOIN dw_db_temp.jason_test_2 AS b
    ON a.user_id = b.user_id
    AND a.city_id = b.city_id
    AND a.pos_index = b.pos_index
  ;

```

### 4. 字符串 array 转换为 array 格式

``` sql

SELECT
  '["2345Explorer.exe","2345RTProtect.exe"]' AS string_data,

  regexp_replace('["2345Explorer.exe","2345RTProtect.exe"' , '[\\[|\\]|\"]+' , '') AS string_array,

  split(
    regexp_replace('["2345Explorer.exe","2345RTProtect.exe"' , '[\\[|\\]|\"]+' , ''), ','
  ) AS array


```


## 六、自定义 jar 和 function

### 1. 临时函数

``` sql

-- 显示所有函数
show functions;

-- 创建临时函数
CREATE  TEMPORARY  FUNCTION  function_name AS class_name;
-- 删除临时函数
DROP  TEMPORARY  FUNCTION  IF  EXISTS function_name;


```

### 2. 永久函数

- 永久函数保存在 hive 的元数据库的 FUNCS 数据表中
- 当命令行无法删除永久函数时, 清理永久函数流程
 - 清除 FUNCS 这张表需要删除的函数
 - 重新启动服务

``` sql

-- 创建永久函数
CREATE  FUNCTION function_name AS class_name;
-- 删除临时函数
DROP FUNCTION  IF  EXISTS function_name;


-- 这种方式不要用, 因为会注册到 元数据库的 FUNCS 和 FUNC_RU 表中
CREATE  FUNCTION parse_user_agent AS 'com.angejia.dw.hive.udf.useragent.ParseUserAgent' USING JAR 'hdfs://Ucluster/user/jars/dw_hive_udf-1.0-SNAPSHOT-spark.jar';
CREATE  FUNCTION get_page_info AS 'com.angejia.dw.hive.udf.pageinfo.CalculatePageInfo' USING JAR 'hdfs://Ucluster/user/jars/dw_hive_udf-1.0-SNAPSHOT-spark.jar';

```


## 七、变量和命令行

``` sql

1. 普通变量 --define OR --hivevar

  --define OR --hivevar

  hive --hivevar a=123 --hivevar b=456 -f ./test.sql

  test.sql 写入数据:
  SELECT '${hivevar:a}';
  SELECT '${b}';


2. hiveconf 变量

  hiveconf 的命名空间指的是 hive-site.xml 下面的配置变量值


  SET –v; 查看所有的 hiveconf

  SET name=lucy;
  SET name;   // name=lucy

  ${hiveconf:name}

  SELECT '${hiveconf:name}';


3. env, export 的变量

  指环境变量，包括Shell环境下的变量信息，如 HADOOP_HOME 之类的

  ${env:varname}

  SELECT '${env:FINEBI_HOME}';


4. system 的命名空间是系统的变量，包括JVM的运行环境


5. source FILE <filepath> 在交互Shell中执行一个脚本


6. dfs <dfs command> 在交互 Shell 中执行 hadoop fs 命令
  dfs -du -s /tmp/;


6. ! <command> 在交互Shell中执行Linux操作系统命令并打印出结果

```
