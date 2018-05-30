# Standalone 独立模式

- [集群模式概述](http://spark.apache.org/docs/latest/cluster-overview.html)
  - [Standalone](http://spark.apache.org/docs/latest/spark-standalone.html)


## 一、详细部署

```
1. 安装好 Hadoop ,配置好环境变量
```


## 二、连接操作

``` sh
1. 连接
  pyspark --master spark://uhadoop-ociicy-task3:7077 --deploy-mode client
  spark-shell --master spark://uhadoop-ociicy-task3:7077 --deploy-mode client

```
