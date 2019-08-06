# tidb 运维


## 集群运维

- [集群扩容](https://pingcap.com/docs-cn/v3.0/how-to/scale/with-ansible/)

``` sh
# 初始化系统环境，修改内核参数
ansible-playbook bootstrap.yml

# 部署集群
ansible-playbook deploy.yml

# 启动集群
ansible-playbook start.yml

# 关闭集群
ansible-playbook stop.yml

# 滚动升级激情(用于更新配置)
ansible-playbook rolling_update.yml

# 清除集群数据
ansible-playbook unsafe_cleanup_data.yml

# 销毁集群
ansible-playbook unsafe_cleanup.yml
```

## 集群监控

- [Overview 面板重要监控指标详解](https://pingcap.com/docs-cn/v3.0/reference/key-monitoring-metrics/overview-dashboard/)


## 性能优化

- [TiKV 性能参数调优](https://pingcap.com/docs-cn/v3.0/reference/performance/tune-tikv/)


## 集群扩容

### 1. 扩容 TiDB/TiKV 节点

``` sh
# 初始化新增节点
ansible-playbook bootstrap.yml -l 172.16.20.97,172.16.20.98,172.16.20.99

# 部署新增节点
ansible-playbook deploy.yml -l 172.16.20.97,172.16.20.98,172.16.20.99

# 启动新节点服务
ansible-playbook start.yml -l 172.16.20.97,172.16.20.98,172.16.20.99

# 更新 Prometheus 配置并重启
ansible-playbook rolling_update_monitor.yml --tags=prometheus

# 打开浏览器访问监控平台：http://172.16.10.3:3000，监控整个集群和新增节点的状态。
```
