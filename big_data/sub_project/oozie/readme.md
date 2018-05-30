# Oozie 用于 Hadoop 平台的一种工作流调度引擎

## 一、介绍

在 Oozie 中，工作流是一个由动作(action)节点和多个控制流程节点组成的 DAG(有向无环图)

当工作流结束时，Oozie 通过发送一个 HTTP 的回调向客户端通知工作流的状态。
还可以在每次进入工作流或退出一个动作节点时接收到回调

## 二、定义工作流

### 1. 参考资料
- [流程图](https://www.processon.com/view/link/5663fccce4b01db999f74793)
- [使用案例](http://www.ibm.com/developerworks/cn/data/library/bd-hadoopoozie/index.html)


### 2. 注意事项

```
1) 每个工作流都必须有一个 start 节点和一个 end 节点
```
