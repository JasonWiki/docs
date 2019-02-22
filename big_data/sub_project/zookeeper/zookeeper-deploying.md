 # Zookeeper 安装

## * 构架

- [Zookeeper 首页](http://zookeeper.apache.org/)
- [Zookeeper 文档](http://zookeeper.apache.org/doc/)

## 一、下载

- [Zookeeper 下载](http://www.apache.org/dyn/closer.cgi/zookeeper/)
- [最新稳定版本 stable](http://apache.fayea.com/zookeeper/stable/)
- [下载稳定版本 stable](http://mirrors.cnnic.cn/apache/zookeeper/zookeeper-3.4.6/zookeeper-3.4.6.tar.gz)


## 二、环境

### 1. 配置

- [Zookeeper 配置文档](http://zookeeper.apache.org/doc/r3.4.6/zookeeperAdmin.html#sc_configuration)

``` sh

1) 环境变量
vim ~/.bashrc
# ZOOKEEPER
export ZOOKEEPER_HOME=/usr/local/zookeeper
export ZOOKEEPER_CONF_DIR=$ZOOKEEPER_HOME/conf
export PATH=$ZOOKEEPER_HOME/bin:$PATH

source ~/.bashrc

2) 修改配置

  cp $ZOOKEEPER_CONF_DIR/zoo_sample.cfg $ZOOKEEPER_CONF_DIR/zoo.cfg
  vim $ZOOKEEPER_CONF_DIR/zoo.cfg

  # 监听的客户端连接端口
  clientPort=2181

  # 存储内存中数据快照的位置
  dataDir=/data/zookeeper

  # 基本事件单元,以毫秒为单位,控制心跳、超时,默认情况下最小的会话超时时间为 2 倍的 tickTime
  tickTime=2000


  # 把事务日志写入到指定的目录中
  dataLogDir=/data/log/zookeeper

  # 限制连接到客户端的数量,以 IP 区分不同客户端,设置为 0 表示不限制
  maxClientCnxns=0

  # 最小会话超时时间和最大会话超时实践，默认 -1 ,最小会话超时时间为 2 倍 tickTime 时间,最大会话超时时间为 20 倍 tickTime 时间
  #minSessionTimeout=-1
  #maxSessionTimeout=-1

  # 允许 follower(针对 leader 角色而言的客户端)  连接并同步到 leaer 的初始化连接时间,以 tickTime 的倍数来表示的
  initLimit=10

  # leader 与 follower 之间发送消息时请求和应答的时间长度，如果 follower 在设置的时间内不能与 leader 通信,那么此 follower 会被丢弃
  syncLimit=5


  # zookeeper 节点服务器需要配置的id 和端口
  # 格式: server.[server_id]=[hostname]:[port_1]:[port_2]
  server.1=uhadoop-ociicy-master1:2888:3888
  server.2=uhadoop-ociicy-master2:2888:3888
  server.3=uhadoop-ociicy-core1:2888:3888


修改完成 配置后
  配置： /data/zookeeper/myid  文件的 id 为 1 - n
  例如: echo 1 > /data/zookeeper/myid 

PS:
  端口 2181 由 ZooKeeper 客户端使用，用于连接到 ZooKeeper 服务器
  端口 2888 由对等 ZooKeeper 服务器使用，用于互相通信
  端口 3888 用于领导者选举。

```


### 2. 启动命令

``` sh

1. 启动服务
  1) 启动
    $ZOOKEEPER_HOME/bin/zkServer.sh start
  2) 指定某个配置文件
    $ZOOKEEPER_HOME/bin/zkServer.sh start $ZOOKEEPER_CONF_DIR/zoo-xxx.xx.xxx.cfg

2. 连接指定 zookeeper 客户端
  $ZOOKEEPER_HOME/bin/zkCli.sh -server [hostname]:2181

3. 连接指定 zookeeper 客户端,执行命令
  $ZOOKEEPER_HOME/bin/zkCli.sh -server [hostname]:2181 ls /

4. 四子命令
  1) echo stat |nc 127.0.0.1 2181 来查看哪个节点被选择作为follower或者leader
  2) echo ruok | nc 127.0.0.1 2181 测试是否启动了该Server，若回复imok表示已经启动。
  3) echo dump | nc 127.0.0.1 2181 ,列出未经处理的会话和临时节点。
  4) echo kill | nc 127.0.0.1 2181 ,关掉server
  5) echo conf | nc 127.0.0.1 2181 ,输出相关服务配置的详细信息。
  6) echo cons | nc 127.0.0.1 2181 ,列出所有连接到服务器的客户端的完全的连接 / 会话的详细信息。
  7) echo envi | nc 127.0.0.1 2181 ,输出关于服务环境的详细信息（区别于 conf 命令）。
  8) echo reqs | nc 127.0.0.1 2181 ,列出未经处理的请求。
  9) echo wchs | nc 127.0.0.1 2181 ,列出服务器 watch 的详细信息。
  10) echo wchc | nc 127.0.0.1 2181 ,通过 session 列出服务器 watch 的详细信息，它的输出是一个与 watch 相关的会话的列表。
  11) echo wchp | nc 127.0.0.1 2181 ,通过路径列出服务器 watch 的详细信息。它输出一个与 session 相关的路径。

5. 查看 zookeeper 占用资源情况
```


### 3. 清理日志

``` sh
1. 清理日志, 保留 3 天的历史
  $ZOOKEEPER_HOME/bin/zkCleanup.sh /data/zookeeper -n 3

2. 配置文件清理  
  vim $ZOOKEEPER_HOME/conf/zoo.cfg  编辑配置文件

  # 指定清理频率，单位是小时，默认是0，表示不开启自己清理功能。
  autopurge.purgeInterval=24

  # 和上面的参数搭配使用，指定需要保留的文件数目，默认是保留3个。
  autopurge.snapRetainCount=5


  $ZOOKEEPER_HOME/bin/zkServer.sh restart  重启
```
