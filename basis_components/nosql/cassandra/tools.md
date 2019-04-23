# 管理工具

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
cassandra-stress command [options]

# 插入十万条数据
./tools/bin/cassandra-stress write n=100000 -rate threads=5 -node node4,node5,node6

# 读取十万条数据
./tools/bin/cassandra-stress read n=100000 -rate threads=5 -node node4,node5,node6

# 持续三分钟一直读取
./tools/bin/cassandra-stress read duration=3m -node node4,node5,node6
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
