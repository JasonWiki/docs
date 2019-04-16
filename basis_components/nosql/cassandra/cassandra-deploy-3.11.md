# cassandra

- [下载 cassandra](http://archive.apache.org/dist/cassandra)
- [文档](http://cassandra.apache.org/doc/latest/getting_started/installing.html)


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
## 一般设置为空。如果节点是正确设置的(主机名称，名称解析等)，Cassandra通过InetAddress.getLocalHost()可以从系统获取本地地址
## 如果是单节点集群，你可以使用默认配置(localhost)
## 永远不要指定 0.0.0.0，总是错的。
# listen_address: localhost

# 确保此端口未被防火墙阻止，因为客户端将在此端口上与Cassandra通信
native_transport_port:  9042



# ----------------- 更改目录的位置 -----------------
# 数据文件所在的一个或多个目录
data_file_directories:  /opt/data/cassandra/data

# commitlog文件所在的目录, 出于性能原因，如果您有多个磁盘，请考虑将 commitlog 和数据文件放在不同的磁盘上。
commitlog_directory:    /opt/data/cassandra/commitlog

# 保存的缓存所在的目录
saved_caches_directory: /opt/data/cassandra/saved_caches

# 提示所在的目录
hints_directory:        /opt/data/cassandra/hints
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

# 杀死进程
pkill -f CassandraDaemon


# 查看启动端口
netstat -tunlp | grep 7000
netstat -tunlp | grep 9042
```


## 工具

- cqlsh shell 工具

``` sh
cqlsh>

# ------------------------------ Cassandra 系统命令 ------------------------------
# 此命令显示当前的一致性级别，或设置新的一致性级别。
Consistency

# 此命令将数据从 Cassandra 复制到文件并从中复制。下面给出一个将名为emp的表复制到文件myfile的示例。
Copy


# ------------------------------ Cassandra 创建键空间 ------------------------------
# 创建 Keyspace 空间
CREATE KEYSPACE test
WITH replication = {'class':'SimpleStrategy', 'replication_factor' : 3};

  SimpleStrategy: 只用于单数据中心和单机架。SimpleStrategy 把第一份备份放在由分区器决定的节点上。余下的备份被放在环的顺时针方向的下面的节点上，而不考虑拓扑结构(机架或数据中心的位置)。
  NetworkTopologyStrategy: 当你已经(或者计划)将你的集群部署成多数据中心的时候，使用 NetworkTopologyStrategy 策略。这个策略需要指定在每个数据中心有多少个副本数量。


# 修改 Keyspace 空间
ALTER KEYSPACE test
WITH REPLICATION = {'class' : 'NetworkTopologyStrategy', 'replication_factor' : 3}

# 删除 Keyspace 空间
DROP KEYSPACE test;

# 查看所有 Keyspace 空间
DESCRIBE keyspaces;

# 查看指定 test Keyspace
DESCRIBE test;

# 使用 test Keyspace
USE test;


# ------------------------------ Cassandra 创建表 ------------------------------
# 语法.
CREATE (TABLE | COLUMNFAMILY) <tablename>
('<column-definition>' , '<column-definition>')
(WITH <option> AND <option>)

# 使用表空间
use test;

# 创建表
CREATE TABLE emp(
   emp_id int PRIMARY KEY,
   emp_name text,
   emp_city text,
   emp_sal varint,
   emp_phone varint
   );

# 查看已有表
DESC tables;

# 查看表详情
DESCRIBE  emp;


# ------------------------------ Cassandra 修改表 ------------------------------
# 语法.
ALTER (TABLE | COLUMNFAMILY) <tablename> <instruction>

# 增加列
ALTER TABLE emp ADD emp_email text;

# 删除列
ALTER TABLE emp DROP emp_email;


# ------------------------------ Cassandra 删除表 ------------------------------
# 删除指定表
DROP TABLE emp;

# 验证表是否已删除, 由于 emp 表已删除，您不会在列族列表中找到它。
ESCRIBE COLUMNFAMILIES;


# ------------------------------ Cassandra 截断表 ------------------------------
# 语法. 表的所有行都将永久删除
TRUNCATE <tablename>

# 清理 emp 表
TRUNCATE emp;


# ------------------------------ Cassandra 索引 ------------------------------
# 创建索引语法.
# identifier 命令规范为: <键空间_表名_字段名>
CREATE INDEX <identifier> ON <tablename>

# 创建索引 idx_emp_name 放到 emp 表的 emp_name 字段中
CREATE INDEX test_emp_name ON emp (emp_name);


# 删除索引语法.
DROP INDEX <identifier>

# 删除表中列的索引的示例。这里我们删除表emp中的列名的索引
DROP INDEX idx_emp_name;


# ------------------------------ Cassandra 创建数据 ------------------------------
# 语法.
INSERT INTO <tablename>
(<column1 name>, <column2 name>....)
VALUES (<value1>, <value2>....)
USING <option>

INSERT INTO emp (emp_id, emp_name, emp_city, emp_phone, emp_sal) VALUES(1,'ram', 'Hyderabad', 9848022338, 50000);

INSERT INTO emp (emp_id, emp_name, emp_city, emp_phone, emp_sal) VALUES(2,'robin', 'Hyderabad', 9848022339, 40000);

INSERT INTO emp (emp_id, emp_name, emp_city, emp_phone, emp_sal) VALUES(3,'rahman', 'Chennai', 9848022330, 45000);


# ------------------------------ Cassandra 更新数据 ------------------------------
# 语法.
UPDATE <tablename>
SET <column name> = <new value>
<column name> = <value>....
WHERE <condition>

# 更新数据
UPDATE emp SET emp_city='Delhi',emp_sal=50000 WHERE emp_id=2;


# ------------------------------ Cassandra 读数据 ------------------------------
# 语法.
SELECT FROM <tablename>

# 查询所有数据
SELECT * FROM test.emp;

# 查询指定列数据
SELECT emp_name, emp_sal FROM test.emp;

# 查询数据指定条件数据
# WHERE 的子查询必须有索引才可以查询
CREATE INDEX test_emp_name ON emp (emp_name);
SELECT * FROM emp WHERE emp_name='ram';


# ------------------------------ Cassandra 删除数据 ------------------------------
# 语法.
DELETE FROM <identifier> WHERE <condition>;

# 删除整个列数据, 删除指定列 emp_sal 的数据
DELETE emp_sal FROM emp WHERE emp_id=3;

# 删除整行
DELETE FROM emp WHERE emp_id=3;
```


- nodetool 工具

``` sh
# 用于显示Cassandra当前版本信息
nodetool version

# 用于显示当前机器节点信息，数据中心机架信息
nodetool status

# 停止Cassandra服务
nodetool stopdaemon

# 用于创建Cassandra keyspace或table的快照信息，即数据备份
nodetool snapshot

# 用于删除所有快照信息，所以为了避免误删，要先将快照移到其他位置，运行这个命令的唯一目的就是节省磁盘空间
nodetool clearsnapshot

# 关闭当前节点，并把数据复制到环中紧邻的下一节点中
nodetool decommision

# 用于描述集群信息
nodetool describecluster

# 后面跟keyspace名字，显示圆环的节点信息
nodetool describering  

# 把 Memtable 中的数据刷新到 sstable，并且终止当前节点与其他节点之间的联系
# 执行完这条命令要重启这个节点，一般Cassandra升级时才会使用这个命令，如果只是单纯的想把Memtable中的数据刷新到sstable中，请使用nodetool flush命令
nodetool drain

# 把 Memtable 中的文件刷新到 sstable 中，不需要重启节点
nodetool flush  

# 查看key分布在哪台机器上，需要三个参数: keyspace table key
nodetool getendpoints

# 查看某个key落在那个sstable中 需要参数为keyspace table key
nodetool getsstables

# 获取节点的网络连接信息 可以指定-h参数查看具体节点信息
nodetool netstats

# 新的数据中心加入集群是，运行这个命令复制数据到新的数据中心
nodetool rebuild  

# 在删除数据时，Cassandra并非真正的删除了数据，而是重新插入一条数据，记录删除记录的信息和时间，叫做tombstone，使用nodetool repair可以删除墓碑数据，频繁修改的数据节点可以使用这个命令节省空间，提高读速度。
nodetool repair

# 列出Cassandra维护的线程池的信息，你可以直接看到每个阶段有多少操作，以及他们的状态是活动中，等待还是完成。
nodetool tpstats

# 查看表的一些信息，包括读的次数，写的次数，sstable数量，Memtable信息，压缩信息，bloomfilter信息。使用-H 则文件的信息会以可读的方式
nodetool cfstats

# 清理不需要的keyspace，当新增数据节点或减少数据节点的时候，数据会在节点中重新分发，可以运行这个命令，清除不分布在这个节点的keyspace，唯一的目的就是节省磁盘空间。后面不带参数会清理所有不需要的keyspace，加keyspace会清理对应的keyspace中冗余的数据
nodetool cleanup

# 用于合并sstable文件，省略表的名字会压缩指定keyspace的所有表的sstable文件，如果不加keyspace会压缩所有的keyspace下的sstable文件
nodetool compact  

# 显示当前压缩信息
nodetool compactionstats
```


- cassandra-stress 压力测试

``` sh
# 用于压力测试 模拟写入和读取   
cassandra-stress

# 插入一万条数据
./tool/bin/cassandra-stress write n=10000  

# 读取一万条数据
./tool/bin/cassandra-stress read n=10000   

# 持续三分钟一直读取
./tool/bin/cassandra-stress read duration=3m

```


- sstable 工具集合

``` sh
# 用于载入大量外部数据至一集群 或者将已经存在的sstable载入到另外一个节点数不同或者复制策略不同的集群或者从快照中恢复数据
sstable-loader  

# 清洗指定的表的sstable，试图删除损坏的部分，保留完好的部分。因为是在节点关闭的状况下可以运行，所以他可以修复nodetool scrub不能修复的问题 一般出现问题的时候先使用 nodetool scrub修复 如果没有解决，那么使用sstablescrub修复
sstablescrub  

# 切割大的sstable为小文件  运行前必须关闭Cassandra服务，执行sstablesplit -s 40 sstable所在的路径  把大的文件分割成小的40m的文件
sstablesplit  

# 以json方式显示sstable文件的内容
sstable2json  
```
