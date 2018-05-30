# Scala 安装

## 一、安装 Java

- [Java 安装指南](../Java/java-jdk.MD)
- xx.xx.xxx 表示版本号

``` sh
1) 配置 JAVA_HOME 环境变量
  vim ~/.bashrc
  export JAVA_HOME=/your_path/java
  export PATH=$JAVA_HOME/bin:$PATH

2) 验证版本
  java -version
  javac -version

  出现如下表示安装成功
    java version "xx.xx.xxx"
    Java(TM) SE Runtime Environment (build 1.8.0_31-b13)
    Java HotSpot(TM) 64-Bit Server VM (build 25.31-b07, mixed mode)
```

## 二、安装 Scala

### 1.下载 Scala

- 下载指定版本的二进制包
  - [Scala .xx.x binaries](http://www.scala-lang.org/download/)
  - [所有版本 建议下载 2.10.6](http://www.scala-lang.org/download/all.html)

- x.xx.x 表示版本号

``` sh
1) 解压包
  tar -zxvf /your_path/scala-x.xx.x.tgz

2) 配置环境变量
  # SCALA
  export SCALA_HOME=/your_path/scala-x.xx.x
  export PATH=$SCALA_HOME/bin:$PATH

3) 验证版本
  scala -version
  scalac -version

  出现如下表示安装成功
    Scala code runner version x.xx.x -- Copyright 2002-2013, LAMP/EPFL
```
