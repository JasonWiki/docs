# MapReduce1

## 一、介绍

- 分布式计算框架

## 二、组成构架

- [官方文档](http://hadoop.apache.org/docs/r1.0.4/cn/mapred_tutorial.html)

### 1. MapReduce 的一些实体

- [MapReduce1 架构原理图](https://www.processon.com/view/link/5664347fe4b026a7ca2a71a6)
- [MapReduce1 工作原理](http://sishuok.com/forum/blogPost/list/5965.html)

```
1. 客户端: 提交 MapReduce 作业

2. jobtracker: 协调作业,创建任务列表

3. tasktracker: 运行作业划分后的任务

4. 分布式文件系统 HDFS
```

### 2. JobTracker 和 TaskTracker

- JobTracker

  ```
  Map-reduce 框架的中心,他需要与集群中的机器定时通信 (heartbeat),
  需要管理哪些程序应该跑在哪些机器上，需要管理所有 job 失败、重启等操作
  ```
- TaskTracker

  ```
  是 Map-reduce 集群中每台机器都有的一个部分，他做的事情主要是监视自己所在机器的资源情况
  TaskTracker 同时监视当前机器的 tasks 运行状况。
  TaskTracker 需要把这些信息通过 heartbeat 发送给 JobTracker，
  JobTracker 会搜集这些信息以给新提交的 job 分配运行在哪些机器上。
  ```
