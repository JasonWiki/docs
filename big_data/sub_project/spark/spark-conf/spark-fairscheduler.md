# Spark ThriftServer Sql 调度


## 一、配置线程池

``` xml
<allocations>
  <!-- default 线程池 -->
  <pool name="default">
    <!-- 调度模式 FIFO 和 FAIR -->
    <schedulingMode>FAIR</schedulingMode>
    <!-- 权重 -->
    <weight>1</weight>
    <!-- 最小保留核数 -->
    <minShare>2</minShare>
  </pool>

  <pool name="real_time">
    <schedulingMode>FAIR</schedulingMode>
    <weight>2</weight>
    <minShare>4</minShare>
  </pool>
</allocations>

```


## 二、应用

``` sql

--- 调度器优化 Start ---

-- FAIR 公平调度器, FIFO 先进先出调度器, 在 spark-defaults.conf 中配置
SET spark.scheduler.mode=FAIR;

-- 任务推测
SET spark.speculation=true;

-- 每个任务分配的 CPU 核数
SET spark.task.cpus=1;

--- 调度器优化 End ---


-- Spark Sql 指定线程池跑, 在 beeline 启动后手工设置, 若不设置则提交到默认线程池 default
SET spark.sql.thriftserver.scheduler.pool=real_time

```
