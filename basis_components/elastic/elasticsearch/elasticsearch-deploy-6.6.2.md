# elasticsearch 部署手册

- Java1.8.0_131 或更高版本, 仅支持Oracle的Java和OpenJDK

``` sh
wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-6.6.2.tar.gz

tar -zxvf elasticsearch-6.6.2.tar.gz

Elasticsearch有三个配置文件：

```

## 配置

### ES 重要配置

- conf/elasticsearch.yml ES 集群配置

``` sh
# ---------------------------------- Cluster -----------------------------------
# 集群名称
cluster.name: data-center


# ------------------------------------ Node ------------------------------------
# 节点名称
## 默认情况下，Elasticsearch 将使用随机生成的 UUID 的前七个字符作为节点id。请注意，节点 id 是持久化的，在节点重启时不会更改，因此默认的节点名称也不会更改。
# node.name: node-1
# 使用主机名
node.name: ${HOSTNAME}


# ----------------------------------- Paths ------------------------------------
# 路径配置
## 指定路径
path:
  logs: /opt/logs/elasticsearch
  data: /opt/data/elasticsearch
## 多路径
path:
  data:
    - /mnt/elasticsearch_1
    - /mnt/elasticsearch_2
    - /mnt/elasticsearch_3


# ----------------------------------- Memory -----------------------------------
# 锁定内存, 避免交换到 swap 中
bootstrap.memory_lock: true


# ---------------------------------- Network -----------------------------------
# 网络设置
## 默认情况下，Elasticsearch仅绑定到环回地址 - 例如127.0.0.1 和[::1]。这是在服务器上运行单个开发节点。
network.host
## 将绑定地址设置为特定的IP (IPv4或IPv6)， 如果自定义设置了 network.host 的 Ip, Elasticsearch 就会假定从开发模式转移到生产模式
network.host  192.168.1.10

# 为 HTTP 设置自定义端口
http.port: 9200


# --------------------------------- Discovery ----------------------------------
# 发现设置, Elasticsearch使用名为“Zen Discovery”的自定义发现实现进行节点到节点的群集和主选举。在投入生产之前，应该配置两个重要的发现设置。
## 第一种配置格式
discovery.zen.ping.unicast.hosts:
   - 192.168.1.10:9300
   - 192.168.1.11
   - seeds.mydomain.com
## 第二种配置格式
discovery.zen.ping.unicast.hosts: ["host1", "host2"]
```


- conf/jvm.options 配置 Elasticsearch JVM 设置

``` sh

# JVM 内存设置, Xmx 为不超过物理 RAM 的 50％, 以确保有足够的物理RAM用于内核文件系统缓存。
## 表示总堆空间的初始大小
-Xms8g
## 表示总堆空间的最大大小
-Xmx8g


# JVM 堆转储路径
## 默认情况下，Elasticsearch 将 JVM 配置为将内存异常转储到默认数据目录.(RPM 和 Debian 软件包发行版，/var/lib/elasticsearch, tar 和 zip 安装, 放在安装目录的 data 文件夹下)
## mkdir -p /opt/logs/elasticsearch/heap_dump
## mkdir -p /opt/logs/elasticsearch/gc
-XX:HeapDumpPath=/opt/logs/elasticsearch/heap_dump


# specify an alternative path for JVM fatal error logs
-XX:ErrorFile=/opt/logs/elasticsearch/gc/hs_err_pid%p.log


# JDK 8 GC logging
8:-Xloggc:/opt/logs/elasticsearch/gc/gc.log


# JDK 9+ GC logging
9-:-Xlog:gc*,gc+age=trace,safepoint:file=/opt/logs/elasticsearch/gc/gc.log:utctime,pid,tags:filecount=32,filesize=64m
```


- conf/log4j2.properties 用于配置 Elasticsearch 日志记录

``` sh
待定
```


### 系统重要配置

- Disable swapping 禁止使用交换区(https://www.elastic.co/guide/en/elasticsearch/reference/current/setup-configuration-memory.html)


``` sh
大多数操作系统尝试使用尽可能多的内存来存储文件系统缓存，并急切地交换掉未使用的应用程序内存。这可能导致部分JVM堆甚至其可执行页面被换出到磁盘。

交换对性能，节点稳定性非常不利，应该不惜一切代价避免。它可能导致垃圾收集持续数分钟而不是毫秒，并且可能导致节点响应缓慢甚至断开与群集的连接。在弹性分布式系统中，让操作系统终止节点更有效。

有三种禁用交换的方法。首选选项是完全禁用交换。如果这不是一个选项，是否更喜欢最小化swappiness与内存锁定取决于您的环境。

1. Disable all swap files
sudo swapoff -a

2. 永久禁用
需要编辑 /etc/fstab 文件并注释掉包含该单词的任何行 swap

3. 启用 bootstrap.memory_lock
另一种选择是在Linux / Unix系统上使用mlockall，或 在Windows 上 使用 VirtualLock，以尝试将进程地址空间锁定到RAM中，从而防止任何Elasticsearch内存被换出。这可以通过将此行添加到config/elasticsearch.yml文件来完成：

```


- [File Descriptors 文件描述符](https://www.elastic.co/guide/en/elasticsearch/reference/current/file-descriptors.html)

``` sh
1. ulimit 方式
sudo su root
ulimit -n 65536
su elasticsearch

2. /etc/security/limits.conf 永久生效
elasticsearch  -  nofile  65536
```


- [Virtual memory 确保足够的虚拟内存](https://www.elastic.co/guide/en/elasticsearch/reference/current/vm-max-map-count.html)

``` sh
Elasticsearch mmapfs默认使用目录来存储其索引。mmap计数的默认操作系统限制可能太低，这可能导致内存不足异常。

1. 在 Linux 上，可以通过运行以下命令来增加限制 root：
sysctl -w vm.max_map_count=262144

2. /etc/sysctl.conf 永久生效
vm.max_map_count = 262144
```


- [Number of threads 确保足够的线程](https://www.elastic.co/guide/en/elasticsearch/reference/current/max-number-of-threads.html)

``` sh
Elasticsearch 为不同类型的操作使用许多线程池。重要的是它能够在需要时创建新线程。确保 Elasticsearch 用户可以创建的线程数至少为 4096。

1. ulimit
ulimit -u 65536

2. /etc/security/limits.conf 生效
elasticsearch  -  nofile  65536
```


- [DNS cache settings JVM DNS 缓存设置](https://www.elastic.co/guide/en/elasticsearch/reference/current/networkaddress-cache-ttl.html)

``` sh
# 缓存ttl(以秒为单位)，以便进行积极的DNS查找, JDK安全属性networkaddress.cache.ttl;设置为-1以永久缓存
-Des.networkaddress.cache.ttl=60

# 为负DNS查找缓存ttl(以秒为单位)，注意这将覆盖JDK安全属性networkaddress.cache。- ttl;设置为-1以缓存永远
-Des.networkaddress.cache.negative.ttl=10
```


## 集群控制



``` sh
# --------------------------------- 启动 ----------------------------------

1. 启动
./bin/elasticsearch


2. 守护进程
./bin/elasticsearch -d -p pid


pkill -F pid


sudo journalctl --unit elasticsearch --since  "2016-10-30 18:17:16"


# --------------------------------- 停止 ----------------------------------
jps | grep Elasticsearch
14542 Elasticsearch


./bin/elasticsearch -p /tmp/elasticsearch-pid -d
cat /tmp/elasticsearch-pid && echo
kill -SIGTERM PID
```
