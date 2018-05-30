# Yarn 命令 和 资源配置

## 一、 用户命令

``` json
1) application 管理

  (1) 查看作业列表
    yarn application -list  

  (2) 删除应用
    yarn application -kill <application_id> 删除应用


2) application logs

  yarn logs -applicationId xxxxxxx : 根据 applicationId id 查询运行完成的应用日志

  $HADOOP_HOME/conf/yarn-default.xml

  (1) yarn.log-aggregation-enable=true : 是否开启日志聚合

    默认 : false
      a) yarn.nodemanager.log.retain-seconds=86400 : (秒数,保留 NodeManager 本地的日志的时间)
      b) yarn.nodemanager.log-dirs=file:///data/yarn/logs : 日志本地目录
        默认 : ${yarn.log.dir}/userlogs

    当为 true 时:
      a) yarn.nodemanager.remote-app-log-dir=/tmp/logs : 日志聚合保存在 HDFS 中
      b) yarn.nodemanager.remote-app-log-dir-suffix=logs : 日志前缀
      c) yarn.log-aggregation.retain-seconds=86400 : (秒数,表示多少秒删除一次日志,86400 表示一天删除一次,-1 表示不删除)
      d) yarn.log-aggregation.retain-check-interval-seconds=86400 : (秒数,表示多久检查一次是否保留汇聚的日志,86400 表示一天检查一次，-1 表示不检查)

  (2) application 任务完成后的归档日志
    hdfs dfs -ls  /var/log/hadoop-yarn/apps  

  (3) application 正在运行的日志
    NodeManager 的 /data/yarn/logs 中

  (4) yarn 本地运行日志
    cd /var/log/hadoop-yarn/
    tail -f yarn-hadoop-resourcemanager-uhadoop-ociicy-master1.log


3) node 输出节点报告

  (1) 列出所有正在运行的节点
    yarn node -list

  (2) 输出节点的状态报告
    yarn node -status uhadoop-ociicy-task3:33616


4) jar 运行一个 jar 文件。
  yarn jar <jar> [mainClass] args


5) yarn version
  输出 Yarn 版本


6) yarn classpath
  打印所需的类路径获取Hadoop jar和所需的库
```

MapReduce 命令:
``` sh

1) 查看 job 任务
  mapred job -list

2) 将正在运行的 hadoop 作业kill掉
  mapred job –kill [job-id]

3) 查看 job 状态
  mapred job -status [job-id]

4) 内存
  $HADOOP_HOME/conf/mapred-site.xml

5) 重启 historyserver
  service hadoop-mapreduce-historyserver start

```


## 二、 管理命令

- yarn rmadmin -help

``` json
1) 增加或关闭 Yarn 节点
  yarn rmadmin -refreshNodes  刷新节点

  (1) yarn.resourcemanager.nodes.include-path :  一个路径文件 , 指定 ResourceManager 接受的 Node 列表 ,默认空
    example:
      10.10.10.11
      10.10.10.10

  (2) yarn.resourcemanager.nodes.exclude-path :  一个路径文件 , 指定 ResourceManager 不会接受使用的 Node 列表 ,默认空
    example:
      10.10.10.12
      10.10.10.13


2) Capacity 调度器配置
  yarn rmadmin -refreshQueues  刷新调度器配置

  $HADOOP_HOME/conf/capacity-scheduler.xml  : 配置文件


3) JobHistoryServer  Yarn 上所有 MapReduce 作业历史服务器
  $HADOOP_HOME/conf/mapred-default.xml

  (1) mapreduce.jobhistory.address=xxx.xxx.xxx.xxx:10020  
    日志服务器的 ip 和 port

  (2) mapreduce.jobhistory.cleaner.enable=true
    清理作业历史

  (3) mapreduce.jobhistory.done-dir=${yarn.app.mapreduce.am.staging-dir}/history/done
    完成后的日志目录



5) 更新用户到用户组的映射
    yarn rmadmin -refreshUserToGroupsMappings

    $HADOOP_HOME/conf/core-default.xml

    (1) hadoop.security.group.mapping


6) 刷新超级用户代理组映射
    yarn rmadmin -refreshSuperUserGroupsConfiguration

    $HADOOP_HOME/conf/core-default.xml


7) 更新 ResourceManager 管理的 ACL (Access Control List 访问控制列表)
    yarn rmadmin -refreshAdminAcls

    $HADOOP_HOME/conf/yarn-default.xml

    (1) yarn.acl.enable=true
      启用 ACL

    (2) yarn.admin.acl
      指定某个账号可以成为集群的管理者
      example:
        yarn.admin.acl=user1,user2 group1,group2
        用户用 ',' 号分割
        用户组用 ',' 号分割
        用户和用户组用 ' ' 空格分开,空格左边是用户，空格右边是用户组


8) 重新加载服务级授权策略文件
    yarn rmadmin -refreshServiceAcl

9) 获取节点状态
    yarn rmadmin -getServiceState rm1

10) 切换活动节点
    yarn rmadmin -transitionToActive rm1


* 其他管理命令

1) 启动 ResourceManager (ResourceManager 节点启动)
  yarn resourcemanager

  $HADOOP_HOME/conf/yarn-default.xml

  (1) ApplicationMaster -> ResourceManager 申请 最小/最大 内存数 (一个容器), 可为容器请求的最大物理内存数量
    yarn.scheduler.minimum-allocation-mb=1024  :  最小可申请内存量，默认是1024
    yarn.scheduler.maximum-allocation-mb=25600  :  最大可申请内存量，默认是 8096


  (2) ApplicationMaster -> ResourceManager 申请 最小/最大 CPU 核数 (一个容器), 容器可以请求的虚拟 CPU 内核的最大数量
    yarn.scheduler.minimum-allocation-vcores=1   : 最小可申请CPU数，默认是1
    yarn.scheduler.maximum-allocation-vcores=8   : 最大可申请CPU数，默认是 4


2) 启动 NodeManager (NodeManager 节点启动)
   yarn nodemanager

   $HADOOP_HOME/conf/yarn-default.xml

   (1) NodeManager -> ResourceManager 注册节点时到集群的最大 内存, 只需给系统保留 4G 的容量给系统
      yarn.nodemanager.resource.memory-mb=25600 : 节点最大可用内存, 25 G

   (2) NodeManager -> ResourceManager 注册节点时到集群的最大 CPU
      yarn.nodemanager.resource.cpu-vcores=8  :  节点最最大可用 CPU 数


3) 启动 proxyserver  web 代理服务器
  yarn proxyserver


4) 获取/设置每个守护进程的日志级别
  (1) yarn daemonlog -getlevel <host:port> <name>

  (2) yarn daemonlog -setlevel <host:port> <name> <level>

```


## 三、Balancer 平衡数据

- 增加节点后的平和数据

``` sh
hdfs getconf -confKey dfs.balance.bandwidthPerSec

查看存储节点情况
  hdfs dfsadmin -report

设置负载的带宽
  hdfs dfsadmin -setBalancerBandwidth 524288000

开始均衡 threshold 一般10， 即各个节点与集群总的存储使用率相差不超过10%，我们可将其设置为5%
  $HADOOP_HOME/sbin/start-balancer.sh -threshold 5
```


## 四、平滑下节点

- 下掉指定节点

### 1. 设置需要下的节点

- 配置需要下的 data node 节点

```xml
  <property>
    <name>dfs.namenode.replication.work.multiplier.per.iteration</name>
    <value>10</value>
  </property>

  <property>
    <name>dfs.namenode.replication.max-streams</name>
    <value>50</value>
  </property>

  <property>
    <name>dfs.namenode.replication.max-streams-hard-limit</name>
    <value>100</value>
  </property>

  <!-- 设置需要下的节点, 一行一行写节点 -->
  <property>
    <name>dfs.hosts.exclude</name>
    <value>/home/hadoop/conf/excludes</value>
  </property>

yarn rmadmin -refreshNodes
```


## * ucloud 重启节点 管理

### 1. 重启 resourcemanager 步骤

``` sh

1. 查看节点状态
  yarn rmadmin -getServiceState rm1
  yarn rmadmin -getServiceState rm2

  在状态是 standby 的节点修改配置 运行重启命令

2. 重启 resourcemanager 命令
  sudo service hadoop-yarn-resourcemanager restart
```

### 2. 重启 nodemanager 步骤

``` sh
service hadoop-yarn-nodemanager restart
```
