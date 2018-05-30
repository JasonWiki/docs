# Mesos

## 一、Mesos 介绍

- Mesos 的 master/slave 架构设计
  - master: 是一个全局资源调度器, 很轻所以只保存了 framework(计算框架) 和 slave 的状态, 这些 slave 和 framework 可以通过注册生成到 master 中, 所以可以使用 zookeeper 解决 Mesos master 的单点故障
  - slave: master 使用策略把 slave 的空闲资源分配给 framework, slave 主要功能是汇报任务的状态和启动各个 framework 的 executor
  - framework: framework 通过自己向 Mesos master 中注册，接入到 Mesos 中

- Mesos 的双层调度框架
  - 第一层，由mesos将资源分配给框架；
  - 第二层，框架自己的调度器将资源分配给自己内部的任务。在Mesos中，各种计算框架是完全融入Mesos中的，

- Mesos 优点和缺点
  - 优点：可以同时支持短类型任务以及长类型服务，比如 webservice 以及 SQL service。 资源分配粒度粗，比较适合我们产品多种计算框架并存的现状
  - 缺点：Mesos中的DRF调度算法过分的追求公平，没有考虑到实际的应用需求。在实际生产线上，往往需要类似于Hadoop中Capacity Scheduler的调度机制，将所有资源分成若干个queue，每个queue分配一定量的资源，每个user有一定的资源使用上限；更使用的调度策略是应该支持每个queue可单独定制自己的调度器策略，如：FIFO，Priority等

- 与 Yarn 的区别
  - 全局的 ResourceManager(RM)/NodeManager(NM)/ApplicationMaster(AM), 跟 Mesos 的 master/slave/framework 不同
  - Mesos 使用 Linux container 隔离资源, Yarn 使用 NodeManager container 隔离资源
  - Yarn 中 ResourceManager 是资源的分配的决策者(根据 ApplicationMaster 申请的资源判断)。 Mesos 中 framework 是资源的角色着
  - Yarn 自带了多个资源调度器，如 Capacity Scheduler和Fair Scheduler, 而 Mesos 要在 framework 中自己实现
  - ResourceManager 负责所有应用的任务调度, 而 Mesos 要在 framework 中实现作业的调度
