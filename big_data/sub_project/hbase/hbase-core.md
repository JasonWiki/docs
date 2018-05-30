# Hbase 核心

## 一、概括

- 面向列的分布式数据库
- 实时随机访问超大规模数据集


## 二、架构

- [架构图](https://www.processon.com/view/link/577b873ce4b082b555038133)

```
* HMaster
  1. 管理用户对 Table 的增删改查
  2. 管理 HRegionServer 的负载, 调整 Region 分布
  3. HRegion 中 HStore 里的 StoreFile 超过阀值, 引发 Split 拆分 HRegion 时, 负责重新分配 HRegion
  4. HRegionServer 停机后, 负责失效的 HRegionServer 上的 HRegion 迁移

* HRegionServer
  负责响应用户 I/O 请求, 向 HDFS 文件系统写入数据

  - HRegionServer
    - HRegion (对应 Table 中的一个 Region)
      - HStore (对应一个 Column Family)
        - MemStore (先写内存, 写满阀值, 会 flush 到 StoreFile 文件中)
        - StoreFile (当 StoreFile 文件数量到一定阀值时, 会进行一次 Compact 合并操作, 把多个 StoreFile 合并成一个大的 StoreFile 文件)
        - StoreFile (当 StoreFile 大小超过阀值时, 会触发 split 操作, 把当前的 HRegion 分割成 2 个子 HRegion, 让父的 HRegion 下线, HMaster 会分配新的 HRegion 到 HRegionServer 中, 原来的用户流量, 就会分流到 2 个 HRegion 中)
        - StoreFile (存储格式)
          - HFile (是 Hadoop 的二进制文件格式)
          - HLog File (是 Hadoop 的 Sequence File)
      - HStore
    - HRegion
      - HStore
      - HStore
    - HRegion
      - HStore
      - HStore
      - HStore

  1. HRegion
    一个 HRegionServer 管理一系列的 HRegion 对象
    一个 HRegion 对应 Table 中的一个 Region
    HRegion 由 2 个部分组成 MemStore 和 StoreFile

  2. HStore
    对应一个 Column Famil 用来存储合、合并、拆分数据

* HLog
  HRegionServer -> HLog 中的对象
  1. 每次用户写 MemStore 时, 也会写一份数据到 HLog 文件中, HLog 会自动删除旧的文件
  2. 当 HRegionServer 节点故障后, HMaster 会通过 ZooKeeper 感知到
    a. HMaster 将 HRegionServer 中的 HLog 进行处理, 将其中不同的 HRegion 的 Log 进行数据拆分, 分别放到 HRegion 对应的目录下, 然后再将失效的 HRegion 重新分配到 HRegionServer 中
    b. HRegion 被分配到新的 HRegionServer 中后, 在 Load Region 的过程中, 发现有 HLog 要处理, 会重新加载 HLog 中的数据到 Region-> MemStore 中, 然后保存到 StoreFile 中, 数据恢复完成

```


## 三、详细命令

``` sh

hbase xxx

Some commands take arguments. Pass no args or -h for usage.
  shell           Run the HBase shell
  hbck            Run the hbase 'fsck' tool, hbase hbck 查看故障
  snapshot        Create a new snapshot of a table
  wal             Write-ahead-log analyzer
  hfile           Store file analyzer
  zkcli           Run the ZooKeeper shell
  upgrade         Upgrade hbase
  master          Run an HBase HMaster node
  regionserver    Run an HBase HRegionServer node
  zookeeper       Run a Zookeeper server
  rest            Run an HBase REST server
  thrift          Run the HBase Thrift server
  thrift2         Run the HBase Thrift2 server
  clean           Run the HBase clean up script
  classpath       Dump hbase CLASSPATH
  mapredcp        Dump CLASSPATH entries required by mapreduce
  pe              Run PerformanceEvaluation
  ltt             Run LoadTestTool
  version         Print the version
  CLASSNAME       Run the class named CLASSNAME

```
