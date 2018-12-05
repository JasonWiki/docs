# Spark ThriftServer Sql 服务配置

## 一. 配置调度器

### 1. 配置调度器
- Spark 默认使用FIFO（First In First Out）的调度策略，但对于多并发的场景，使用 FIFO 策略容易导致短任务执行失败。因此在多并发的场景下，需要使用 FAIR 公平调度策略，防止任务执行失败。
- http://spark.apache.org/docs/latest/job-scheduling.html#scheduling-within-an-application

``` xml
$SPARK_HOME/conf/fairscheduler.xml

<allocations>
  <!-- 线程池 名称 -->
  <pool name="default">
    <!-- 调度模式 FIFO 和 FAIR -->
    <schedulingMode>FIFO</schedulingMode>

    <!-- 权重: 控制线程池 比 其他线程池的优先级
    默认情况下，池的权重均为 1.
    例如，如果为特定池提供权重 2，则其资源将比其他活动池多2倍。设置高权重（例如1000）也可以在池之间实现 优先级 - 实质上，只要有活动作业，weight-1000池就会始终首先启动任务
    -->
    <weight>1</weight>

    <!-- 最小保留核数资源
    除了总体权重之外，每个池都可以获得管理员希望拥有的最小份额（作为许多CPU核心）。公平调度程序始终尝试满足所有活动池的最小份额，然后根据权重重新分配额外资源。minShare因此，该属性可以是另一种确保池可以始终快速达到一定数量的资源（例如10个核心）而不会为群集的其余部分赋予高优先级的方法。默认情况下，每个池 minShare 为0
    -->
    <minShare>2</minShare>
  </pool>

  <!-- 自定义 -->
  <pool name="real_time">
    <schedulingMode>FAIR</schedulingMode>
    <weight>2</weight>
    <minShare>4</minShare>
  </pool>
</allocations>
```


### 2. JDBCServer 并发场景

- [并发场景下处理](http://support.huawei.com/hedex/pages/EDOC1000175670YZG1108L/06/EDOC1000175670YZG1108L/06/resources/admin_guide/hd_admin/zh-cn_topic_0048174054.html)
- [sql性能调优](http://spark.apache.org/docs/latest/sql-performance-tuning.html)

``` sql
1. spark-defaults.conf 配置
  # 设置调度模式  
  spark.scheduler.mode  =   FAIR

  # 设置使用的线程池
  spark.scheduler.pool  =   real_time


2. 代码中配置(如果要在代码中配置线程池)
  new SparkConf().set("spark.scheduler.mode", "FAIR")
  new SparkContext(conf).setLocalProperty("spark.scheduler.pool", "real_time")


3. spark beeline 客户端

  -- Spark Sql 指定线程池跑, 在 beeline 启动后手工设置, 若不设置则提交到默认线程池 default
  SET spark.sql.thriftserver.scheduler.pool=real_time;

  # BroadcastHashJoin 的最大的线程池个数，同一时间被广播的表的个数应该小于该参数值
  # BroadCastHashJoin 使用多线程方式广播表，在多并发场景下，会有多个表同时在多线程中，一旦广播表的个数大于线程池个数，任务会出错，
  # 因此需要在JDBCServer的 spark-defaults.conf 配置文件中或在命令行中执行 set spark.sql.broadcastHashJoin.maxThreadNum=value，调整线程池个数。
  SET spark.sql.broadcastHashJoin.maxThreadNum    = 128 (默认)

  # BroadcastHashJoin 中广播表的超时时间，当任务并发数较高的时候，可以调高该参数值，或者直接配置为负数，负数为无穷大的超时时间。
  # BroadCastHashJoin 有超时参数，一旦超过预设的时间，该查询任务直接失败，在多并发场景下，由于计算任务抢占资源，可能会导致 BroadCastHashJoin的Spark 任务无法执行，导致超时出现
  # 因此需要在 JDBCServer 的 spark-defaults.conf 配置文件中调整超时时间
  SET spark.sql.broadcastTimeout   =    300（数值类型，实际为五分钟）

  # 是否使用串行方式执行 BroadcastHashJoin。串行化 BroadcastHashJoin 会降低集群资源使用率，但对于高并发的重任务，可以解决超时的困扰
  # 当并发任务非常重（例如全部完成时间超过2个小时），需要将 BroadcastHashJoin 设置为串行化，这样就能去除超时时间对并发任务的影响。
  # 但是串行化相对于并行化，会降低集群资源的使用率，因此在轻量级任务并发时，不要开启该配置项
  SET spark.sql.bigdata.useSerialBroadcastHashJoin      =     false


  以上配置若添加添加到默认选项则放到  --conf 中 (待测试)
  $SPARK_HOME/sbin/start-thriftserver.sh \
  --master yarn \
  --deploy-mode client \
  --queue root.default \
  --name test \
  --driver-cores 4 \
  --driver-memory 8192M \
  --executor-cores 6 \
  --executor-memory 12288M \
  --conf spark.dynamicAllocation.enabled=true \
```
