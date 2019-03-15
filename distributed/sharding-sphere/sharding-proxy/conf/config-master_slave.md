# 数据主从分离

## 一. 配置模板

### 1. 配置模板

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

### 2. 配套 SQL

``` sql

```
