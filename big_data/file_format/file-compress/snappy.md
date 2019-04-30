## 一、Snappy

- [Google 开源的压缩算法库](http://google.github.io/snappy/)

### 1. snzip 基于 Snappy 的压缩/解压工具

- 使用的版本 1.0.4
- 支持框架的格式化
  - framing-format
  - old framing-format
  - hadoop-snappy format (Hadoop Snappy 文件格式的压缩)
  - raw format
  - snappy-java
  - snappy-in-java
- snzip 项目地址
  - [snzip github](https://github.com/kubo/snzip)
  - [snzip 下载地址](https://bintray.com/kubo/generic/snzip)


> 下载、安装使用方法, 具体见 github 文档

``` sh

1. 安装
  tar xvfz snzip-1.0.4.tar.gz
  cd snzip-1.0.4
  ./configure --prefix=/usr/local/snappy
  make
  make install


2. 加载到系统环境
  vim ~/.bashrc
  # snzip
  export SNZIP_HOME=/usr/local/snappy
  export PATH=${SNZIP_HOME}/bin:$PATH

  source ~/.bashrc


3. snzip -help

  general options:
   -c       输出到标准输出,保持原始文件不变
   -d       解压缩
   -k       不删除原文件
   -t name  压缩框架文件格式
   -h       give this help

  raw_format option:
   -s size  size of input data when compressing.
            The default value is the file size i f available

  tuning options(调优参数):
   -b num   internal block size in bytes
   -B num   internal block size. \'num\'-th power of two.
   -R num   size of read buffer in bytes
   -W num   size of write buffer in bytes
   -T       trace for debug

  supported formats(压缩框架格式选择):
    NAME            SUFFIX  URL
    ----            ------  ---
    framing2        sz      https://github.com/google/snappy/blob/master/framing_format.txt
    hadoop-snappy   snappy  https://code.google.com/p/hadoop-snappy/
    iwa             iwa     https://github.com/obriensp/iWorkFileFormat/blob/master/Docs/index.md#snappy-compression
    framing         sz      https://github.com/google/snappy/blob/0755c815197dacc77d8971ae917c86d7aa96bf8e/framing_format.txt
    snzip           snz     https://github.com/kubo/snzip
    snappy-java     snappy  https://github.com/xerial/snappy-java
    snappy-in-java  snappy  https://github.com/dain/snappy
    comment-43      snappy  http://code.google.com/p/snappy/issues/detail?id=34#c43


4. 压缩 hadoop 框架支持的格式化

  snzip -t -k hadoop-snappy -k file_name  压缩

  snzip -d compressed_file.snappy 解压

```



### 2. python 压缩/解压接口(不兼容 HDFS 原生的 Snappy)

- [python-snappy github](https://github.com/andrix/python-snappy)

``` sh

1. 安装

依赖包
  ubuntu:
    sudo apt-get install libsnappy-dev

  Centos:
    sudo yum install libsnappy-devel

  Brew:
    brew install snappy

安装
  pip install python-snappy

  python -m snappy --help

2. 压缩/解压文件
  python -m snappy -c uncompressed_file compressed_file.snappy
  python -m snappy -d compressed_file.snappy uncompressed_file


3. 压缩/解压 Stream
  cat uncompressed_data | python -m snappy -c > compressed_data.snappy
  cat compressed_data.snappy | python -m snappy -d > uncompressed_data
```


### 3. java 压缩/解压接口

- [snappy-java github](https://github.com/xerial/snappy-java/tree/release/1.0.5)
- [snappy-java mvnrepository](http://mvnrepository.com/artifact/org.xerial.snappy/snappy-java)

``` sh
1. 注意事项, 如果在 Mac 环境中使用请把 jar 包解压

  复制 libsnappyjava.jnilib -> libsnappyjava.dylib
  cp org/xerial/snappy/native/Mac/x86_64/libsnappyjava.jnilib org/xerial/snappy/native/Mac/x86_64/libsnappyjava.dylib

  重启打包
  jar cf snappy-java-1.0.4.1.jar org


2. pom.xml 配置加载本地包
<dependency>
      <groupId>org.xerial.snappy</groupId>
      <artifactId>snappy-java</artifactId>
      <version>1.0.4.1</version>
      <scope>system</scope>
      <systemPath>${basedir}/lib/snappy-java-1.0.4.1.jar</systemPath>
  </dependency>
</dependencies>
```


## 二、SPARK 配置

``` sh
# Spark 配置 Snappy
export JAVA_LIBRARY_PATH=$JAVA_LIBRARY_PATH:$HADOOP_HOME/lib/native
export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:$HADOOP_HOME/lib/native
export SPARK_LIBRARY_PATH=$SPARK_LIBRARY_PATH:$HADOOP_HOME/lib/native

export SPARK_CLASSPATH=$SPARK_CLASSPATH:$HADOOP_HOME/lib/snappy-java-1.0.4.1.jar

spark-sql --jars file:///etc/hive/auxlib/json-serde-1.3.7-jar-with-dependencies.jar,file:///usr/lib/hadoop/lib/snappy-java-1.0.4.1.jar
```
