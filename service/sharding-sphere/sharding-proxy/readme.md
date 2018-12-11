# sharding-proxy 数据库代理中间件

## 部署文档

- [官方文档](http://shardingsphere.io/document/current/cn/manual/sharding-proxy/)

``` sh
# 下载解压
wget https://github.com/sharding-sphere/sharding-sphere-doc/raw/master/dist/sharding-proxy-3.0.0.tar.gz
tar -zvx -f sharding-proxy-3.0.0.tar.gz -C  ./

# 系统配置
conf/server.yaml

# 分片配置等
conf/config-sharding_db.yaml

# 日志级别配置
conf/logback.xml

# 关闭代理
bin/stop.sh

# 启动代理
bin/start.sh 21030

# 查看日志
logs/stdout.log

# 其他说明
Sharding-Proxy  默认使用 3307端口，可以通过启动脚本追加参数作为启动端口号。如: bin/start.sh 3308
Sharding-Proxy  使用 conf/server.yaml配置注册中心、认证信息以及公用属性。
Sharding-Proxy  支持多逻辑数据源，每个以 config- 前缀命名的 yaml 配置文件，即为一个逻辑数据源。
```

## 配置案例

- Sharding-Proxy 支持多逻辑数据源，每个以 config-前缀 命名的 yaml 配置文件
- 即为一个逻辑数据源。以下是 config-xxx.yaml 的配置配置示例

### 1. 数据分片

- 适用于数据量特别大的表

- [数据分片案例](conf/config-sharding_db.md)


### 2. 读写分离

- 适用于访问量特别大

``` xml
schemaName: master_slave_db

dataSources:
  ds_master:
    url: jdbc:mysql://localhost:3306/ds_master
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65
  ds_slave0:
    url: jdbc:mysql://localhost:3306/ds_slave0
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65
  ds_slave1:
    url: jdbc:mysql://localhost:3306/ds_slave1
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65

masterSlaveRule:
  name: ds_ms
  masterDataSourceName: ds_master
  slaveDataSourceNames:
    - ds_slave0
    - ds_slave1
```


### 3. 数据分片 + 读写分离

- 使用与数据量和读写都非常大

``` xml
schemaName: sharding_master_slave_db

dataSources:
  ds0:
    url: jdbc:mysql://localhost:3306/ds0
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65
  ds0_slave0:
    url: jdbc:mysql://localhost:3306/ds0_slave0
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65
  ds0_slave1:
    url: jdbc:mysql://localhost:3306/ds0_slave1
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65

  ds1:
    url: jdbc:mysql://localhost:3306/ds1
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65
  ds1_slave0:
    url: jdbc:mysql://localhost:3306/ds1_slave0
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65
  ds1_slave1:
    url: jdbc:mysql://localhost:3306/ds1_slave1
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65

shardingRule:  
  tables:
    t_order:
      actualDataNodes: ms_ds${0..1}.t_order${0..1}
      tableStrategy:
        inline:
          shardingColumn: order_id
          algorithmExpression: t_order${order_id % 2}
      keyGeneratorColumnName: order_id
    t_order_item:
      actualDataNodes: ms_ds${0..1}.t_order_item${0..1}
      tableStrategy:
        inline:
          shardingColumn: order_id
          algorithmExpression: t_order_item${order_id % 2}  
  bindingTables:
    - t_order,t_order_item
  broadcastTables:
    - t_config

  defaultDataSourceName: ds0
  defaultDatabaseStrategy:
    inline:
      shardingColumn: user_id
      algorithmExpression: ms_ds${user_id % 2}
  defaultTableStrategy:
    none:
  defaultKeyGeneratorClassName: io.shardingsphere.core.keygen.DefaultKeyGenerator

  masterSlaveRules:
      ms_ds0:
        masterDataSourceName: ds0
        slaveDataSourceNames:
          - ds0_slave0
          - ds0_slave1
        loadBalanceAlgorithmType: ROUND_ROBIN
        configMap:
          master-slave-key0: master-slave-value0
      ms_ds1:
        masterDataSourceName: ds1
        slaveDataSourceNames:
          - ds1_slave0
          - ds1_slave1
        loadBalanceAlgorithmType: ROUND_ROBIN
        configMap:
          master-slave-key1: master-slave-value1
```
