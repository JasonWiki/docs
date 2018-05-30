# Hadoop HDFS 迁移

## 一、块和节点

### 1. 同一个节点，不同磁盘迁移块

``` sh
方法 一: 复制
  1. 下线该 datanode 节点, 对节点做 Decommissioned 退役操作 (一个节点一个节点操作)
  2. 复制磁盘数据 -> 到新磁盘中
  3. 修改 hdfs-site.xml 配置
    dfs.data.dir, dfs.datanode.data.dir  修改成新的磁盘
  4. 上线该 datanode 节点, 重启集群


方法 二: 利用 HDFS 集群机制
  1. 下线该 datanode 节点, 对节点做 Decommissioned 退役操作 (一个节点一个节点操作)
  2. 停止 hbase, hbase 依赖 hdfs
  3. 修改 hdfs-site.xml 配置
    dfs.data.dir, dfs.datanode.data.dir  修改成新的磁盘
  4. 上线该 datanode 节点, 重启集群
  5. 检查块信息
    hdfs fsck / | egrep -v '^\.+$' | grep -v eplica
  6. 重新生成副本
    hdfs dfs -setrep -w 3 文件地址
    hdfs dfs -setrep -w 3 -R 目录地址
  7. balancer 重新均衡数据
    hdfs dfsadmin -setBalancerBandwidth 524288000
    sudo -u hdfs hdfs balancer -threshold 5
```
