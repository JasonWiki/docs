# Hive Json Serde

- SERDE 是序列化/反序列化
- [教程](https://www.jianshu.com/p/043223b0024d)

## 一、org.apache.hive.hcatalog.data.JsonSerDe

- hive 表结构, 必须定好结构, 不可以动态支持字段
- [hive](http://mvnrepository.com/artifact/org.apache.hive.hcatalog/hive-hcatalog-core)
- [spark](http://mvnrepository.com/artifact/org.spark-project.hive.hcatalog/hive-hcatalog-core)

测试数据:

``` json
{"common":{
  "c1":"a",
  "c2":"b",
  "c3":"xxxx",
  "c4":[
    "a","b","c"
    ]
  },
  "ip":"192.168.1.1"
}

```

案例:

``` sql
ADD JAR /path/hive-hcatalog-core-1.1.0-cdh5.9.0.jar;

-- 创建解析表
CREATE EXTERNAL TABLE source_json_table (
  `common` struct<
    c1:string,
    c2:string,
    c3:string,
    c4:array<string>
  >,
  `ip` string
) PARTITIONED BY (p_dt String)
ROW FORMAT SERDE 'org.apache.hive.hcatalog.data.JsonSerDe'
WITH SERDEPROPERTIES (
  -- 当前行 json 解析失败跳过
  "ignore.malformed.json"="true"
)
STORED AS TEXTFILE
;

-- 增加数据
ALTER TABLE source_json_table ADD IF NOT EXISTS PARTITION  (p_dt = '2017-01-17') LOCATION '/path/20170117';

-- 查询数据
SELECT common.c1 FROM source_json_table LIMIT 10;
```



## 二、org.openx.data.jsonserde.JsonSerDe (推荐)

### 1. 处理 json 格式

- hive 表结构可根据 json 格式任意调节
- [文档](http://www.lamborryan.com/hive-json/)
- [github 项目和文档](https://github.com/rcongiu/Hive-JSON-Serde)
  - [二进制 jar 下载](http://www.congiu.net/hive-json-serde)

测试数据:

``` json
{ "common":{
    "c1":"a",
    "c2":"b",
    "c3":"xxxx",
  },
  "country": "Switzerland",
  "languages":["German","French", "Italian"],
  "religions":{
    "catholic":[10,20],
    "protestant":[40,50]
  },
  "count" : {
    "a": 4,
    "b": 1,
    "c":[
      "2345MiniPage.exe",
      "2345PinyinCloud.exe",
      "2345PinyinUpdate.exe"
    ]
  }
}
```

案例一, 自动匹配映射 Start:

``` sql
ADD JAR /path/json-serde-1.3.7-jar-with-dependencies.jar;

-- 创建解析表
CREATE EXTERNAL TABLE source_json_table  (
    -- 模式匹配映射
    `common` map<string,string>,
    -- 映射 字符创
    `country` string,
    -- 映射数组
    `languages` array<string>,
    -- 映射嵌套
    `religions` map<string,array<int>>,
    -- 自定义结构映射
    `count` struct<
      a:string,
      b:string,
      c:array<string>
    >
) ROW FORMAT SERDE 'org.openx.data.jsonserde.JsonSerDe'
WITH SERDEPROPERTIES (
  -- 当前行 json 解析失败跳过
  "ignore.malformed.json"="true"
) STORED AS TEXTFILE
LOCATION '/path/20170117';


-- 查询数据(注意不同 map 的访问格式)
SELECT common['c1'],count.a FROM ods.ods_pinyin_click LIMIT 1
```


### 2. 处理 json arr 格式

测试数据

``` json
["00:00:35","27.188.84.94","SERVER_RES",1,"F852FB306F94EDCFA59C806F8BDFCD2F","",""]
```

映射表

``` sql
CREATE TABLE IF NOT EXISTS source_json_arr_table (
  `time` string,
  `ip` string,
  `server` string,
  `num` string,
  `mac` string,
  `other1` string,
  `other2` string
) ROW FORMAT SERDE 'org.openx.data.jsonserde.JsonSerDe'
WITH SERDEPROPERTIES (
 -- 当前行 json 解析失败跳过
 "ignore.malformed.json"="true"
)
STORED AS TEXTFILE
LOCATION '/path/20170117';
;

SELECT * FROM ods.ods_pinyin_click LIMIT 1
```


## 三、org.apache.hadoop.hive.contrib.serde2.JsonSerde 解析

- HIVE 1.1 后, CDH 5.9 后支持不好, 所以不建议使用了

``` sql
ADD JAR /path/hive-json-serde-0.2.jar;

-- 创建映射表
CREATE EXTERNAL TABLE IF NOT EXISTS source_json_table (
  `uid` string COMMENT 'from deserializer',
  `ccid` string COMMENT 'from deserializer'
)
ROW FORMAT SERDE 'org.apache.hadoop.hive.contrib.serde2.JsonSerde'
STORED AS TEXTFILE
LOCATION '/path/20170117'
;
```



## 四、永久生效

``` sh
方法 1: hive-env.sh
export HIVE_AUX_JARS_PATH=/etc/hive/auxlib/*.jar


方法 2. hive-site.xml
<property>
  <name>hive.aux.jars.path</name>
  <value>file:///etc/hive/auxlib/hive-json-serde-0.2.jar</value>
</property>
```
