# 代理 server 配置

- conf/server.yaml 配置文件
- Sharding-Proxy 使用 conf/server.yaml 配置注册中心、认证信息以及公用属性

## 一. 配置模板

``` yaml
# 数据治理
orchestration:
  # 数据治理实例名称
  name: orchestration_ds
  # 本地配置是否覆盖注册中心配置。如果可覆盖，每次启动都以本地配置为准
  overwrite: true
  # 注册中心配置
  registry:
    # 连接注册中心服务器的列表。包括IP地址和端口号。多个地址用逗号分隔。如: host1:2181,host2:2181
    serverLists: localhost:2181
    # 注册中心的命名空间
    namespace: orchestration
    # 其他属性
    digest: #连接注册中心的权限令牌。缺省为不需要权限验证
    operationTimeoutMilliseconds: #操作超时的毫秒数，默认500毫秒
    maxRetries: #连接失败后的最大重试次数，默认3次
    retryIntervalMilliseconds: #重试间隔毫秒数，默认500毫秒
    timeToLiveSeconds: #临时节点存活秒数，默认60秒

# 权限验证
authentication:
  username: root
  password: root

# 公用属性
props:
  # 每个查询的最大连接大小
  max.connections.size.per.query: 1
  # 用于设置接收客户端请求的工作线程个数，默认为CPU核数*2
  acceptor.size: 16
  # 工作线程数量，默认值: CPU核数
  executor.size: 16
  # 是否开启事务, 目前仅支持XA事务，默认为不开启
  proxy.transaction.enabled: false
  # 是否开启链路追踪功能，默认为不开启。详情请参见[链路追踪]
  proxy.opentracing.enabled: false
  # 打印 sql
  sql.show: true
```

## 二. 配置案例

``` yaml
authentication:
  username: root
  password:

props:
  max.connections.size.per.query: 1
  acceptor.size: 16  # The default value is available processors count * 2.
  executor.size: 16  # Infinite by default.
  proxy.transaction.enabled: true
  proxy.opentracing.enabled: false
  sql.show: true
```
