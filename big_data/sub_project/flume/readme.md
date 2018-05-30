# Flume Develop

- Flume-OG 老版本
- Flume-NG 重构后的新版本
- 本文档以 Flume-NG 说明

## 一、什么是 Flume

- 收集日志发送到 HDFS,Kafka,本地文件


## 二、部署 Flume

``` sh
1. 下载 flume
  mkdir -r /data/usr/src
  mkdir -r /data/usr
  cd /data/usr/src
  wget http://archive.cloudera.com/cdh5/cdh/5/flume-ng-1.5.0-cdh5.4.4.tar.gz

2. 安装
  cd /data/usr/src
  tar -zxvf flume-ng-1.5.0-cdh5.4.4.tar.gz
  mv apache-flume-1.5.0-cdh5.4.4-bin /data/usr
  sudo ln -s /data/usr/apache-flume-1.5.0-cdh5.4.4-bin /usr/local/flume

3. 环境配置
  vim ~/.bashrc

  # Flume
  export FLUME_HOME=/usr/local/flume

  export PATH=$FLUME_HOME/bin:$PATH


4. 生效
  source ~/.bashrc

5. 启动脚本

  # 增大内存
  mv $FLUME_HOME/conf/flume-env.sh.template $FLUME_HOME/conf/flume-env.sh
  export JAVA_OPTS="-Xms100m -Xmx2000m -Dcom.sun.management.jmxremote"

  # 启动
  ${FLUME_HOME}/bin/flume-ng agent --conf ${FLUME_HOME}/conf/ -f ${FLUME_HOME}/conf/flume.conf -n agentDw -Dflume.root.logger=DEBUG,console


6. 工具
  echo -n "Hello World" | nc -4u -w1 [ip] [端口号]			// 发送 UDP 数据到端口中, 可用于测试 flume syslogudp 端口
```


## 三、Flume 结构

- Event：一个数据单元，带有一个可选的消息头
- Flow：Event从源点到达目的点的迁移的抽象
- Client：操作位于源点处的Event，将其发送到Flume Agent
- Agent：一个独立的Flume进程，包含组件Source、Channel、Sink
- Source：用来消费传递到该组件的Event
- Channel：中转Event的一个临时存储，保存有Source组件传递过来的Event
- Sink：从 Channel 中读取并移除 Event，将 Event 传递到 Flow Pipeline 中的下一个 Agent（如果有的话）

- [Flume Source](http://flume.apache.org/FlumeUserGuide.html#flume-sources) 收集各种数据源
  - avro、exec、netcat、spooling-directory、syslog 等
  - [Flume Source Interceptors](http://flume.apache.org/FlumeUserGuide.html#flume-interceptors)  将 source event(每行/条) 数据提取出来, 加入到 header 中
    - Timestamp Interceptor 将当前时间戳（毫秒）加入到 events header 中，key 名字为：timestamp，value 值 : 为当前时间戳
    - Host Interceptor  主机名拦截器。将运行Flume agent的主机名或者IP地址加入到events header中，key名字为：host（也可自定义）
    - Static Interceptor  静态拦截器，用于在events header中加入一组静态的key和value。
    - UUID Interceptor  在每个events header中生成一个UUID字符串，例如：b5755073-77a9-43c1-8fad-b7a586fc1b97。生成的UUID可以在sink中读取并使用
    - Morphline Interceptor  使用 Morphline 对每个 events 数据做相应的转换
    - Search and Replace Interceptor  将 events 中的正则匹配到的内容做相应的替换
    - Regex Filtering Interceptor  使用正则表达式过滤原始 events 中的内容
    - Regex Extractor Interceptor  使用正则表达式抽取原始 events 中的内容，并将该内容加入 events header 中

- [Flume Channel](http://flume.apache.org/FlumeUserGuide.html#flume-channels) 负责传输和暂时储存
  - JDBC、file-channel、custom-channel 等
  - [Channel Selectors 通道选择器](http://flume.apache.org/FlumeUserGuide.html#flume-channel-selectors) 复制和多路传输
   - Replicating Channel Selector (default): 复制就是不对日志进行分组，而是将所有日志都传输到每个通道中，对所有通道不做区别对待
   - Multiplexing Channel Selector: 多路传输就是根据指定的header将日志进行分类，根据分类规则将不同的日志投入到不同的channel中，从而将日志进行人为的初步分类

- [Flume Sink](http://flume.apache.org/FlumeUserGuide.html#flume-sinks) sink为目的地，将采集到的日志保存到目的地
  - HDFS, File, Kafka 等
  - [Flume Sink Processors](http://flume.apache.org/FlumeUserGuide.html#flume-sink-processors) 在 slink 对日志处理
    - default 默认规则
    - Failover Sink Processor 故障转移处理器
    - Load balancing Sink Processor 负载平衡处理器
    - Custom Sink Processor 定义接收处理器, 暂时不支持
