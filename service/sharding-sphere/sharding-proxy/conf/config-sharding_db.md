# 数据分库分表配置案例

## 一. 配置模板

### 1. 配置分库分表模板

``` yaml
# 配置对外数据库名
schemaName: big_db

# 配置数据源
dataSources:
  # ds0 源
  ds0:
    url: jdbc:mysql://localhost:3315/ds0
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65

  # ds1 源
  ds1:
    url: jdbc:mysql://localhost:3306/ds1
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 65

# 配置分表规则
shardingRule:

  # 配置数据表
  tables:
    # 配置 t_order 表
    t_order:
      # 配置数据源规则
      actualDataNodes: ds${0..1}.t_order${0..1}
      # 配置表分表规则
      tableStrategy:
        # 分表算法
        inline:
          # 分表字段
          shardingColumn: order_id
          # 分表定位到 mysql 实体表的路由规则
          algorithmExpression: t_order${order_id % 2}
      # 分布式 id 生成字段
      keyGeneratorColumnName: order_id

    # 配置 t_order_item 表
    t_order_item:
      actualDataNodes: ds${0..1}.t_order_item${0..1}
      tableStrategy:
        inline:
          shardingColumn: order_id
          algorithmExpression: t_order_item${order_id % 2}

  # 注册到代理的数据表, 多个逗号分隔
  bindingTables:
    - t_order,t_order_item

  # 默认分库策略
  defaultDatabaseStrategy:
    # 分库算法
    inline:
      # 分库字段
      shardingColumn: user_id
      # 分库规则
      algorithmExpression: ds${user_id % 2}

  # 默认分表策略
  defaultTableStrategy:
    none:

  # 分布式 id 生成算法
  defaultKeyGeneratorClassName: io.shardingsphere.core.keygen.DefaultKeyGenerator
```

### 2. 配套 SQL

``` sql
use big_db;

show tables;

DROP TABLE big_db.t_order;

CREATE TABLE IF NOT EXISTS big_db.t_order (
  id BIGINT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  order_id INT NOT NULL,
  info VARCHAR(50),
  PRIMARY KEY (id))
;

INSERT INTO `t_order` (`user_id`,`order_id`, `info`) VALUES (2, 3, 'info');

SELECT COUNT(*) FROM t_order;
```


## 二. 配置案例

### 1. 配置分库分表

- 配置 conf/config-sharding_db.yaml 文件

``` yaml
schemaName: big_db

dataSources:
  ds_app1:
    url: jdbc:mysql://app1:3306/temp_db?useSSL=false&useUnicode=true&characterEncoding=utf8&serverTimezone=UTC
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 50

  ds_app2:
    url: jdbc:mysql://app2:3315/temp_db?useSSL=false&useUnicode=true&characterEncoding=utf8&serverTimezone=UTC
    username: root
    password:
    autoCommit: true
    connectionTimeout: 30000
    idleTimeout: 60000
    maxLifetime: 1800000
    maximumPoolSize: 50

shardingRule:
  tables:

    # 配置表 t_big_table
    t_big_table:
      actualDataNodes: ds_${['app2', 'app1']}.t_big_table_${['default']}
      # 分表规则
      tableStrategy:
        # 分表算法
        inline:
          # 分表字段
          shardingColumn: tb_partition_key
          # 分表
          algorithmExpression: t_big_table_${tb_partition_key}
      keyGeneratorColumnName: id

  # 注册表
  bindingTables:
    - t_big_table

  # 默认连接数据库
  defaultDataSourceName: ds_app2

  # 默认分库策略
  defaultDatabaseStrategy:
    # 分库算法
    inline:
      # 分库字段
      shardingColumn: db_partition_key
      # 分库路由规则
      algorithmExpression: ds_${db_partition_key}

  # 默认分表策略
  defaultTableStrategy:
    none:

  defaultKeyGeneratorClassName: io.shardingsphere.core.keygen.DefaultKeyGenerator
```

### 2. 配套 SQL

``` sql
use big_db;

show tables;

-- 删除 SQL
DROP TABLE big_db.t_big_table;

-- 创建数据表
CREATE TABLE IF NOT EXISTS big_db.t_big_table (
  id BIGINT NOT NULL AUTO_INCREMENT,
  info varchar(100) NOT NULL DEFAULT 'info' COMMENT 'info',
  db_partition_key varchar(100) NOT NULL DEFAULT 'app2' COMMENT 'database 分区',
  tb_partition_key varchar(100) NOT NULL DEFAULT 'default' COMMENT 'table 分区',
  PRIMARY KEY (id))
;

-- 插入数据, 会写入到 数据库(app1) 表 t_big_table_(default) 中
INSERT INTO `t_big_table` (`info`, `db_partition_key`, `tb_partition_key`) VALUES ('info', 'app1', 'default');

-- 插入数据, 会写入到 数据库(app2) 表 t_big_table_(default) 中
INSERT INTO `t_big_table` (`info`, `db_partition_key`, `tb_partition_key`) VALUES ('info', 'app2', 'default');

-- 插入数据, 会写入到 数据库(app2) 表 t_big_table_(20181211) 中, 如果表不存在需要创建
INSERT INTO `t_big_table` (`info`, `db_partition_key`, `tb_partition_key`) VALUES ('info', 'app2', '20181211');

-- 查询总数据, 会有个 BUG, 需要提前把所有规则填写在 actualDataNodes 配置中, 才能读到数据
SELECT COUNT(*) FROM big_db.t_big_table;

-- 查询指定数据库, 指定数据表数据
SELECT COUNT(*) FROM big_db.t_big_table WHERE db_partition_key='app2' AND tb_partition_key = '20181211';
```
