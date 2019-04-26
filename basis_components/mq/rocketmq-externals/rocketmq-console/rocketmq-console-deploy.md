# rocketmq-console

## 简介

rocketmq-console 是 rocketmq 的界面管理工具

## 下载

- [rocketmq-console](https://github.com/apache/rocketmq-externals/tree/master/rocketmq-console)

``` sh

```

## 安装

```sh
git clone -b release-rocketmq-console-1.0.0 https://github.com/apache/rocketmq-externals.git
```

## 配置

``` sh
cd rocketmq-externals/rocketmq-console/

# 修改配置文件
vim src/main/resources/application.properties
  # uri 路径
  server.contextPath=/rocketmq

  # 访问端口
  server.port=12580

  # Name Server 地址
  rocketmq.config.namesrvAddr=rocketmq-nameserver1:9876;rocketmq-nameserver2:9876;rocketmq-nameserver3:9876

  # 日志目录
  rocketmq.config.dataPath=/opt/logs/rocketmq-console/data


# 修改 logback
vim src/main/resources/logback.xml

  修改 ${user.home} -> /opt
```

## 部署

### 1. 准备工作

``` sh
sudo mkdir -p /opt/logs/rocketmq-console
sudo chown $USER:$USER /opt/logs/rocketmq-console
```

### 2. 安装部署

``` sh
# 打包编译
mvn clean package -Dmaven.test.skip=true

# 启动
java -jar ./rocketmq-console-ng-1.0.0.jar \
--server.contextPath=/rocketmq \
--server.port=12580 \
--rocketmq.config.dataPath=/opt/logs/rocketmq-console/data \
--rocketmq.config.namesrvAddr='rocketmq-nameserver1:9876;rocketmq-nameserver2:9876;rocketmq-nameserver3:9876'   

http://hostname:12580/rocketmq
```
