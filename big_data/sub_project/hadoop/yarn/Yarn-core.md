#　Yarn 构架

## 一、Yarn

### 1、Yarn 构架组成

- [Yanr 构架图](https://www.processon.com/view/link/56643e61e4b026a7ca2ac271)

```
1) 组成节点
  (1) ResourceManager : 统一资源管理器节点
  (2) NodeManager : 节点管理器节点(多个)

2) 组成角色
  (1) ResourceManager : 统一资源管理器

  (2) NodeManager : 节点管理器

  (3) ApplicationMaster : 应用 master,管理整一个应用生命周期

  (4) Container :　容器，运行任务环境
```


## 二、Yarn 作业的调度策略

### 1. FIFO 先进先出，队列调度器

```
1) 默认的调度器,基于队列的 FIFO 调度器

2) 优先级
  VERY_HIHG,HIGH,NORMAL,LOW,VERY_LOW 任意一个值作为优先级

3) 默认值调度属性 FIFO
  mapreduce.jobtracker.taskscheduler(mapred.jobtracker.taskScheduler) = org.apache.hadoop.mapred.JobQueueTaskScheduler
```

### 2. 公平调度器 (Fair Scheduler)

- [Fair Scheduler 参数汇总](http://dongxicheng.org/mapreduce-nextgen/hadoop-yarn-configurations-capacity-scheduler/)

```
1) 让每个用户公平共享集群能力

2) 如果只有一个作业运行，就会得到集群所有资源，随着提交的作业越来越多，闲置的任务槽会以"让每个用户公平共享集群"，这种方式进行分配

3) 公平调度器把作业都放在作业池中 :
  (1) 默认每个用户都有自己的作业池
  (2) 提交作业数较多的用户，不会获得更多的资源。
  (3) 可以用 map 和 reduce 的任务槽数来定制作业池的最小容量，可以设置每个池的权重。

4) 公平调度器支持抢占机制:
  (1)如果一个作业池在特定的一段时间内未能公平共享资源，就会中止运行池得到过的资源的任务
  把空出来的任务槽让给运行资源不足的作业池

5) 使用公平调度器
  $HADOOP_HOME/contrib/fairscheduler 复制到 $HADOOP_HOME/lib 目录中
  设置 mapreduce.jobtracker.taskscheduler(mapred.jobtracker.taskScheduler) = org.apache.hadoop.mapred.FairScheduler
  详见 255 page 或者文档
```


### 3. 容量调度器 (Capacity Scheduler)

- [Capacity Scheduler 参数汇总](http://dongxicheng.org/mapreduce-nextgen/hadoop-yarn-configurations-capacity-scheduler/)

```
1) 针对多用户调度

2) 由很多队列组成，这些队列可能是层次结构的(一个队列可能是另外一个队列的子队列)

3) 每个队列被分配有一定的容量
  (1) 每个队列内部根据 FIFO 方式(考虑优先级) 进行调度

4) 容量调度器允许用户或者组织(使用队列进行定义)为每个用户或者组织模拟出一个使用 FIFO 调度策略的独立 MapReduce 集群

5) 相比之下，公平调度器强制每个池内公平共享，试运行作业共享池的资源

```
