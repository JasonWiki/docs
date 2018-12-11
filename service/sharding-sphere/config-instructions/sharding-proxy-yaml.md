# sharding-proxy-yaml 配置项说明

- [配置项说明](http://shardingsphere.io/document/current/cn/manual/sharding-proxy/configuration/)

## 一. 数据源与分片配置项说明

### 1. 数据分片

``` xml
schemaName: #逻辑数据源名称

dataSources: #数据源配置，可配置多个data_source_name
  <data_source_name>: #与Sharding-JDBC配置不同，无需配置数据库连接池
    url: #数据库url连接
    username: #数据库用户名
    password: #数据库密码
    autoCommit: true #hikari连接池默认配置
    connectionTimeout: 30000 #hikari连接池默认配置
    idleTimeout: 60000 #hikari连接池默认配置
    maxLifetime: 1800000 #hikari连接池默认配置
    maximumPoolSize: 65 #hikari连接池默认配置

shardingRule: #省略数据分片配置，与Sharding-JDBC配置一致
```

### 2. 读写分离

``` xml
schemaName: #逻辑数据源名称

dataSources: #省略数据源配置，与数据分片一致

masterSlaveRule: #省略读写分离配置，与Sharding-JDBC配置一致
```


## 二. 全局配置项说明

### 1. 数据治理

- 与Sharding-JDBC配置一致

### 1. Proxy属性

``` xml
#省略与Sharding-JDBC一致的配置属性
props:
  acceptor.size: #用于设置接收客户端请求的工作线程个数，默认为CPU核数*2
  proxy.transaction.enabled: #是否开启事务, 目前仅支持XA事务，默认为不开启
  proxy.opentracing.enabled: #是否开启链路追踪功能，默认为不开启。详情请参见[链路追踪](/cn/features/orchestration/apm/)
```

### 2. 权限验证

- 用于执行登录Sharding Proxy的权限验证。配置用户名、密码后，必须使用正确的用户名、密码才可登录Proxy。

``` xml
authentication:
   username: root
   password:
```
