# Cloudera Manage 硬件配置方案

## 说明

如果集群有大量小文件，则要求NN内存至少要16GB，DN内存至少4GB。

几个组件中NN对内存最为敏感，它有单点问题，直接影响到集群的可用性；JT同样是单点，如果JT内存溢出则所有MapReduce Job都无法正常执行。

## 一、线下环境
| 节点 | 角色 | 配置 |
| ------ | ------------ | -----|
| CDH-Manager | CM-Server、Mysql(CM元数据存储)、CM-agent | 8GB内存，1xT硬盘 |
| NameNode | NameNode Jobtracker、Hive-Metastore、ResourManager、 CM-agent | 16GB内存，1xT硬盘 |
| SecondaryNameNode | SecondaryNameNode、CM-agent | 4~8GB内存，1xT硬盘 |
| DataNode01 | DataNode TaskTracker Zookeeper CM-agent | 4~8G内存，1xT硬盘 |
| DataNode02 | DataNode TaskTracker Zookeeper CM-agent | 4~8G内存，1x1T硬盘 |
| DataNode03 | DataNode TaskTracker Zookeeper CM-agent | 4~8G内存，1x1T硬盘 |


## 二、生产环境
| 节点 | 角色 | 配置 |
| ------ | ------------ | -----|
| CDH-Manager | CM-Server Mysql(CM元数据存储) | 1U,双路4核CPU,16GB内存，1xTB硬盘 |
| NameNode | NameNode Jobtracker Hive-Metastore ResourManager CM-agent | 1U,双路4核CPU,32GB内存，4x1T(2T) |
| SecondaryNameNode | SecondaryNameNode CM-agent | 1U,双路4核CPU,16GB内存，4x1T(2T) |
| DataNode01 | DataNode TaskTracker Zookeeper CM-agent | 1U,双路4核CPU,16GB内存，4x1T(2T) |
| DataNode02 | DataNode TaskTracker Zookeeper CM-agent | 1U,双路4核CPU,16GB内存，4x1T(2T) |
| DataNode03 | DataNode TaskTracker Zookeeper CM-agent | 1U,双路4核CPU,16GB内存，4x1T(2T) |
