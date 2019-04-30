# 压缩种类

## Compress 压缩选择

| 压缩类型 | 压缩处理类 | 是否分割 | 说明 | 优先级 |
| --- | --- | --- | --- | --- |
| Snappy | org.apache.hadoop.io.compress.SnappyCodec | Yes | 压缩、解压均衡 | 1 |
| LZO | com.hadoop.compression.lzo.LzopCodec | Yes | 压缩、解压均衡 | 2 |
| gzip | org.apache.hadoop.io.compress.GzipCodec | No | 压缩效果好, 压缩、解压速度快 | 3 |
| BZip2 | org.apache.hadoop.io.compress.BZip2Codec | Yes | 压缩效果最好, 压缩、解压速度慢 | 4 |
| Deflate | org.apache.hadoop.io.compress.DeflateCodec | Yes | 默认 | 5 |  
| Hive ORC | org.apache.hadoop.hive.ql.io.orc.OrcSerde | Yes | Hive 本身提供的压缩格式, 压缩、解压均衡 | 1 |


## 一、Parquet-and-ORC

- http://dongxicheng.org/mapreduce-nextgen/columnar-storage-parquet-and-orc/

``` sh
Parquet ：
     不支持修改，
     Java 编写，
     主导公司 Twitter/Cloudera
     支持的查询引擎 Apache Drill/impala
     支持索引 : block/group/chunk

ORC :
     支持修改,可与 Hive 结合
     Java 编写，
     主导公司 Hortonworks
     支持的查询引擎 Apache Hive
     支持索引 : file/Stripe/row

```
