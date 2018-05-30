# Yarn 配置

- [官方文档](http://hadoop.apache.org/docs/stable2/hadoop-yarn/hadoop-yarn-common/yarn-default.xml)

## Yarn 参数配置

``` sh

##### Yarn MapReduce 限制参数 Start #####

# 作业提交到的队列, 默认 default
mapreduce.job.queuename=rooo.default

# 作业优先级
mapreduce.job.priority=NORMAL

# MR ApplicationMaster 最大失败尝试次数
mapreduce.am.max-attempts=2
# MR ApplicationMaster 占用的内存量
yarn.app.mapreduce.am.resource.mb=1536
# MR ApplicationMaster 占用的虚拟CPU个数
yarn.app.mapreduce.am.resource.cpu-vcores=2


# 每个 Map/Reduce Task 向 ResourceManager 申请的内存
mapreduce.map.memory.mb=6144
mapreduce.reduce.memory.mb=8192

# 每个 Map/Task Task 向 ResourceManager 申请的虚拟 CPU
mapreduce.map.cpu.vcores=1
mapreduce.reduce.cpu.vcores=1

# 每个 Map/Reduce 启动的 JVM 堆内存
mapreduce.map.java.opts=-Xmx6144M
mapreduce.reduce.java.opts=-Xmx8192M

# Map/Reduce Task 最大失败尝试次数
mapreduce.map.maxattempts=4;
mapreduce.reduce.maxattempts=4


# Map 阶段溢写文件的阈值（排序缓冲区大小的百分比)
mapreduce.map.sort.spill.percent=0.8

# Reduce Task 启动的并发拷贝数据的线程数目
mapreduce.reduce.shuffle.parallelcopies=5

##### Yarn MapReduce 限制参数 End #####



##### Yarn Scheduler 参数 Start #####

# ApplicationMaster -> ResourceManager 申请 最小/最大 内存数 (一个容器), 根据集群任务的实际情况配置
yarn.scheduler.minimum-allocation-mb=1024;
yarn.scheduler.maximum-allocation-mb=8192;

# ApplicationMaster -> ResourceManager 申请 最小/最大 CPU 核数 (一个容器), 根据集群任务的实际情况配置
yarn.scheduler.minimum-allocation-vcores=1;
yarn.scheduler.maximum-allocation-vcores=16;

# 内存规整化, 单位 MB
yarn.scheduler.increment-allocation-mb=512

# CPU 规整化
yarn.scheduler.increment-allocation-vcores=1

##### Yarn Scheduler 参数 End #####



##### Yarn Nodemanager 参数 Start #####

# NodeManager -> ResourceManager 注册节点时到集群的最大 内存, 只需给系统保留 4G 的容量给系统, 根据实际情况配置
yarn.nodemanager.resource.memory-mb=25600;
#  NodeManager -> ResourceManager 注册节点时到集群的最大 CPU
yarn.nodemanager.resource.cpu-vcores=8;


# NodeManager 上运行的附属服务，用于提升 Shuffle 计算性能。
yarn.nodemanager.aux-services=mapreduce_shuffle,spark_shuffle;

# NodeManager 中辅助服务对应的类。
yarn.nodemanager.aux-services.spark_shuffle.class=org.apache.spark.network.yarn.YarnShuffleService;

# Shuffle 服务监听数据获取请求的端口。可选配置，默认值为“7337”。
spark.shuffle.service.port=7337;

# 添加依赖的jar包
$SPARK_HOME/yarn/spark-2.0.2-yarn-shuffle.jar 到  $HADOOP_HOME/lib/ 目录下。

##### Yarn Nodemanager 参数 End #####

```



## Fair Scheduler 公平调度器

``` sh

##### yarn-site.xml 配置 Start #####

# 选择调度器种类
yarn.resourcemanager.scheduler.class=org.apache.hadoop.yarn.server.resourcemanager.scheduler.fair.FairScheduler;

# 使用默认队列时的 Fair Scheduler 用户。 当设置为 true 时，如果未指定池名称，Fair Scheduler 将会使用用户名作为默认的池名称。当设置为 false 时，所有应用程序都在一个名为 default 的共享池中运行。
yarn.scheduler.fair.user-as-default-queue=true;

# Fair Scheduler 优先权。是否抢占资源
yarn.scheduler.fair.preemption=false;

# Fair Scheduler 优先权利用率阈值。抢占之前的利用率阈值。利用率计算为所有资源中使用量与容量之间的最大比例。默认为0.8, 到 80% 开始抢占资源
yarn.scheduler.fair.preemption.cluster-utilization-threshold=0.8;

# 在一个队列内部分配资源时
# false 采用公平轮询的方法将资源分配各各个应用程序，默认
# true 按照应用程序资源需求数目分配资源，即需求资源数量越多，分配的资源越多。
yarn.scheduler.fair.sizebasedweight

# 是否启动批量分配容器分配，当一个节点出现大量资源时，可以一次分配完成，也可以多次分配完成, 默认 false
# false 不启用批量分配
# true 启用批量分配
yarn.scheduler.fair.assignmultiple=false

# cloudera 的 hadoop 参数: 是否动态在一个心跳中分配的资源量
# yarn.scheduler.fair.assignmultiple 为 true 时
# false 不开启
# true 节点上大约一半的未分配资源将分配给单个心跳中的容器, 默认
yarn.scheduler.fair.dynamic.max.assign=true

# 一次心跳最多分配的 container 数量
# yarn.scheduler.fair.assignmultiple 为 true，yarn.scheduler.fair.dynamic.max.assign 为 false 时
# -1 默认, 表示不限制
yarn.scheduler.fair.max.assign=-1

# 需要启用 Fair Scheduler 持续调度。如果禁用此调度，应改为使用 yarn.scheduler.fair.locality.threshold.node
# 对于在特定节点上请求容器的应用程序，接受在另一节点上的位置前，Fair Scheduler 等待的最短时间（以毫秒为单位）
yarn.scheduler.fair.locality-delay-node-ms=2

# 需要启用 Fair Scheduler 持续调度。如果禁用持续调度，应改为使用 yarn.scheduler.fair.locality.threshold.rack
# 对于在特定机架上请求容器的应用程序，接受另一机架上的位置前，Fair Scheduler 等待的最短时间（以毫秒为单位）
yarn.scheduler.fair.locality-delay-rack-ms=4

# 需要禁用 Fair Scheduler 持续调度。如果启用此调度，应改为使用 yarn.scheduler.fair.locality-delay-node-ms
# 一次心跳代表一次调度机会，而该参数则表示跳过调度机会占节点总数的比例，表示为 0 和 1 之间的一个浮点数， 默认情况下，该值为 -1.0，表示不跳过任何调度机会
yarn.scheduler.fair.locality.threshold.node=0.1

# 需要禁用 Fair Scheduler 持续调度。如果启用持续调度，应改为使用 yarn.scheduler.fair.locality-delay-rack-ms
# 对于请求在特定机架的容器的 apps，自从最后一次容器分配等待接受配置到其他机架的调度机会数量。表达式为 0 ~ 1 之间的浮点数，作为集群大小的因子，是错过的调度机会。默认值为 -1.0 表示不错过任何调度机会
yarn.scheduler.fair.locality.threshold.rack

# true application 提交时可以创建新的队列，要么是因为 application 指定了队列，或者是按照 user-as-default-queue 放置到相应队列，默认
# false 任何时间一个 app 要放置到一个未在分配文件中指定的队列，都将被放置到 “default” 队列
yarn.scheduler.fair.allow-undeclared-pools=false

# 锁定调度程序并重新计算公平份额的时间间隔，默认值 500ms，
yarn.scheduler.fair.update-interval-ms=500ms

##### yarn-site.xml 配置 End #####


##### 自定义参数, fair-scheduler.xml



##### queue 元素 Start #####

# 最少资源保证量，设置格式为“X mb, Y vcores”，当一个队列的最少资源保证量未满足时，它将优先于其他同级队列获得资源
minResources

# 最多可以使用的资源量，fair scheduler 会保证每个队列使用的资源量不会超过该队列的最多可使用资源量
maxResources

# 限制应用程序: 最多同时运行的应用程序数目
maxRunningApps

# 限制应用程序: 限制队列用于运行 Application Master 的资源比例。这个属性只能用于叶子队列。0.1 ~ 1.0 范围, 默认 0.5
# 1.0 这个队列的 AMs 可以占用 100% 的内存和CPU的公平共享
# 0.5 这个队列的 AMs 可以占用 50% 的内存和CPU的公平共享
maxAMShare

# weight 主要用在资源共享之时，weight 越大，拿到的资源越多, 默认为 1
# 权重是 2 的队列将会收到接近默认权重 2 倍的资源
weight  

# 设置队列的调度策略, 允许的值包括 fifo, fair, drf
schedulingPolicy

# 可以提交 apps 到队列的用户或者组的列表, 例如: *,  hadoop,jason
aclSubmitApps

# 可以管理队列的用户和/或组的列表, 例如: *,  hadoop,jason
aclAdministerApps

# 最小共享量抢占时间。如果一个资源池在该时间内使用的资源量一直低于最小资源量，则开始抢占资源
minSharePreemptionTimeout=10

# 公平共享量抢占时间。如果一个资源池在该时间内使用资源量一直低于公平共享量的一半，则开始抢占资源
fairSharePreemptionTimeout

# 队列的公平共享抢占阈值,如果队列等待 fairSharePreemptionTimeout 之后没有接收到 fairSharePreemptionThreshold * fairShare 的资源,它被允许从其他队列抢占资源。如果不设置，队列将会总其父队列继承这个值, 默认 0.5
fairSharePreemptionThreshold

##### queue 元素 End #####



##### 其他全局元素 Start #####

# 设置任意用户（没有特定限制的用户）运行 app 的默认最大数量限制
userMaxAppsDefault

# 设置 root 队列的公平共享抢占的默认超时时间；可以被 root 队列下的 fairSharePreemptionTimeout 设置覆盖
defaultFairSharePreemptionTimeout

# 设置 root 队列的默认最小共享抢占超时时间；可以被 root 队列下 minSharePreemptionTimeout 覆盖
defaultMinSharePreemptionTimeout

# 设置 root 队列的公平共享抢占的默认阈值；可以被 root 队列下的 fairSharePreemptionThreshold 覆盖
defaultFairSharePreemptionThreshold

# 设置队列的默认运行 app 数量限制；可以被任一队列的 maxRunningApps 元素覆盖
queueMaxAppsDefault

# cloudera 参数: 设置队列的默认最大资源限制; 在每个队列中被 maxResources 元素覆盖
queueMaxResourcesDefault=40000 mb，0vcores

# 设置队列的默认 AM 资源限制; 在每个队列中由 maxAMShare 元素覆盖
queueMaxAMShareDefault=0.5

# 队列设置默认调度策略; 如果指定，则由每个队列中的 schedulePolicy 元素覆盖。默认为“公平”
defaultQueueSchedulingPolicy

# 其他全局元素 End #####


##### queuePlacementPolicy 规则元素 Start #####

queuePlacementPolicy
# 包含一个 Rule 元素列表用于告诉调度器如何放置app到队列
# Rule 生效顺序与列表中的顺序一致。Rule可以含有参数。所有 Rule 接受"create"参数，用于标明该规则是否能够创建新队列
# "Create" 默认值为 true，如果设置为 false 并且 Rule 要放置 app 到一个 allocations file 没有配置的队列，那么继续应用下一个Rule

# app 放置到它请求的队列。如果没有请求队列，例如它指定"default",执行continue。如果app请求队列以英文句点开头或者结尾，例如 “.q1” 或者 “q1.” 将会被拒绝
specified

# app 按照提交用户名放置到同名的队列。用户名中的英文句点将会被“_dot_”替换，如对于用户"first.last"的队列名是"first_dot_last"
user

# app 放置到与提交用户 primary group 同名的队列。用户名中的英文句点将会被“_dot_”替换，如对于组"one.two"的队列名是"one_dot_two"
primaryGroup

# app 放置到与提交用户所属的 secondary group 名称相匹配的队列。
# 第一个与配置相匹配的 secondary group 将会被选中。组名中的英文句点会被替换成“_dot_”,例如用户使用“one.two”作为他的secondary groups将会放置到“one_dot_two”队列，如果这个队列存在的话
secondaryGroupExistingQueue

# app 放置到根据队列中嵌套规则建议的用户名同名的队列中。这有些类似于 UserRule，在‘nestedUserQueue’规则中不同的是用户队列可以创建在任意父队列下，而'user'规则只能在root队列下创建用户队列。有一点需要注意，nestedUserQueue 规则只有在嵌入规则返回一个父队列时才会生效。用户可以通过设置 队列的‘type’属性为 ‘parent’ 来配置父队列，或者在队列下至少配置一个叶子
nestedUserQueue

# app 放置到 default 规则中指定的 ‘queue’属性对应的队列。如果 ‘queue’属性没有指定，app放置到 ‘root.default’ 队列
default

# 拒绝   app
reject

##### queuePlacementPolicy 规则元素 End #####

```


## 配置案例

- 分为两个队列
  - default 默认队列, 所有任务默认提交到此队列
  - real_time 实时队列，在线实时业务
- real_time 队列可以抢占 default 队列的资源
-

``` xml
<?xml version="1.0"?>
<allocations>

  <!# 设置队列的默认 AM 资源限制; 在每个队列中由 maxAMShare 元素覆盖 #>
  <queueMaxAMShareDefault>0.5</queueMaxAMShareDefault>
  <!# cloudera 参数: 设置队列的默认最大资源限制; 在每个队列中被 maxResources 元素覆盖 #>
  <queueMaxResourcesDefault>40000 mb,0vcores</queueMaxResourcesDefault>
  <!# 设置任意用户（没有特定限制的用户）运行 app 的默认最大数量限制 #>
  <userMaxAppsDefault>5</userMaxAppsDefault>
  <!# 用户规则 #>
  <user name="sample_user">
    <!# 限制用户最大运行 apps 数量 #>
    <maxRunningApps>30</maxRunningApps>
  </user>

  <!-- 实时队列 -->
  <queue name="real_time">
    <minResources>70000 mb,20vcores</minResources>
    <maxResources>100000 mb,30vcores</maxResources>
    <maxRunningApps>50</maxRunningApps>
    <maxAMShare>0.5</maxAMShare>
    <weight>1.0</weight>
    <aclSubmitApps>hadoop</aclSubmitApps>
    <aclAdministerApps>hadoop</aclAdministerApps>
    <schedulingPolicy>drf</schedulingPolicy>
  </queue>

  <!-- 默认队列 -->
  <queue name="default">
    <minResources>50000 mb,40vcores</minResources>
    <maxResources>170000 mb,84vcores</maxResources>
    <maxRunningApps>150</maxRunningApps>
    <maxAMShare>0.5</maxAMShare>
    <weight>2.0</weight>
    <aclSubmitApps>hadoop</aclSubmitApps>
    <aclAdministerApps>hadoop</aclAdministerApps>
    <schedulingPolicy>drf</schedulingPolicy>
  </queue>

  <!-- 配置规则 -->
  <queuePlacementPolicy>
    <rule name="specified" />
    <rule name="primaryGroup" create="false" />
    <rule name="nestedUserQueue">
        <rule name="secondaryGroupExistingQueue" create="false" />
    </rule>
    <rule name="default" queue="sample_queue"/>
  </queuePlacementPolicy>
</allocations>



Allow Preemption From: 是否允许抢占
勾选 true :  本资源池资源可以被抢占
反选 false : 本资源池资源不可被抢占

```
