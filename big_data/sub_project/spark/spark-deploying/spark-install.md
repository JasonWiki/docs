# Spark 安装


## 一、下载

### 1.下载地址

-[downloads](http://spark.apache.org/downloads.html)

## 二、环境配置

### 1. 安装

``` sh
1. 解压
  tar -zxvf /your-path/xx.xx.xx.tar
  sudo ln -s /your-path/spark.xx.xx /usr/local/spark

2. 环境变量
  vim ~/.bashrc
  # SPARK
  export SPARK_HOME=/usr/local/spark
  export SPARK_CONF_DIR=$SPARK_HOME/conf
  export PATH=$SPARK_HOME/bin:$PATH

  source ~/.bashrc
```

### 2. 配置变量

``` sh

1. spark-env.sh

  # Hdoop 环境变量
  export HADOOP_HOME=$HADOOP_HOME
  export HADOOP_CONF_DIR=$HADOOP_HOME/conf

  # Spark 类配置
  SPARK_LIBRARY_PATH=$SPARK_LIBRARY_PATH:$HADOOP_HOME/lib
  SPARK_CLASSPATH=$SPARK_CLASSPATH:$HADOOP_HOME/lib/hadoop-lzo.jar

```
