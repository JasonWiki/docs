# cassandra

- [下载 cassandra](hteacher_sortp://archive.apache.org/dist/cassandra)
- [文档](hteacher_sortp://cassandra.apache.org/doc/latest/geteacher_sorting_started/installing.html)


## 安装

``` sh
# cassandra cluster
xxx.xxx.xxx.xx1    cassandra-node1
xxx.xxx.xxx.xx2    cassandra-node2
xxx.xxx.xxx.xx3    cassandra-node3
```


## 配置

- conf/cassandra.yaml

``` sh
# ----------------- 主运行时属性 -----------------
# 集群名称
cluster_name:    cassandra-cluster

# 集群 ip
seeds:           cassandra-node1,cassandra-node2,cassandra-node3

# TCP端口，用于命令和数据
storage_port:   7000

# 用来连接其他Cassandra节点的IP地址或者主机名称
  # 一般设置为空。如果节点是正确设置的(主机名称，名称解析等)，Cassandra通过InetAddress.getLocalHost()可以从系统获取本地地址
  # 如果是单节点集群，你可以使用默认配置(localhost)
  # 永远不要指定 0.0.0.0，总是错的。
  # 全部注释自动获取
# listen_address: localhost

# 客户端连接的侦听地址（Thrift RPC服务和本机传输）
  # 全部注释自动获取
# rpc_address:     localhost   

# 客户端连接的侦听地址 端口
native_transport_port:  9042



# ----------------- 更改目录的位置 -----------------
# 数据文件所在的一个或多个目录
data_file_directories:
  - /opt/data/cassandra/data

# commitlog文件所在的目录, 出于性能原因，如果您有多个磁盘，请考虑将 commitlog 和数据文件放在不同的磁盘上。
commitlog_directory:    /opt/data/cassandra/commitlog

# 保存的缓存所在的目录
saved_caches_directory: /opt/data/cassandra/saved_caches

# 提示所在的目录
hints_directory:        /opt/data/cassandra/hints


# 在超过此值的任何多分区批处理大小上记录WARN。 默认情况下为每批5kb。 应该注意增加此阈值的大小，因为它可能导致节点不稳定。
batch_size_warn_threshold_in_kb: 50

batch_size_fail_threshold_in_kb:
```


- cassandra-env.sh

``` sh
## 环境变量
# 可以设置JVM级别的设置，例如堆大小 cassandra-env.sh。您可以向JVM_OPTS环境变量添加任何其他JVM命令行参数; 当Cassandra启动时，这些参数将被传递给JVM。
```


## 启动

``` sh
# 前台启动
./bin/cassandra -f

# 后台启动
./bin/cassandra

# 查看运行状态
./bin/nodetool status

# 停止Cassandra服务
./bin/nodetool stopdaemon

# 杀死进程
pkill -f CassandraDaemon



# ---------- Cassandra inter-node ports ----------
7000	Cassandra inter-node cluster communication.
7001	Cassandra SSL inter-node cluster communication.
7199	Cassandra JMX monitoring port.


# ---------- Cassandra client ports ----------
9042	Cassandra client port.
9160	Cassandra client port (Thrift).
9142	Default for native_transport_port_ssl, useful when both encrypted and unencrypted connections are required
```
