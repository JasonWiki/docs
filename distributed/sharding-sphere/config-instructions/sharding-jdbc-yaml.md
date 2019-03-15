# sharding-jdbc-yaml 配置项说明

- [配置项说明](http://shardingsphere.io/document/current/cn/manual/sharding-jdbc/configuration/config-yaml/)

``` sh
Yaml语法说明
!! 表示实例化该类

- 表示可以包含一个或多个

[] 表示数组，可以与减号相互替换使用
```

## 一. 配置项说明

### 1. 数据分片

``` xml
dataSources: #数据源配置，可配置多个data_source_name
  <data_source_name>: #<!!数据库连接池实现类> `!!`表示实例化该类
    driverClassName: #数据库驱动类名
    url: #数据库url连接
    username: #数据库用户名
    password: #数据库密码
    # ... 数据库连接池的其它属性

shardingRule:
  tables: #数据分片规则配置，可配置多个logic_table_name
    <logic_table_name>: #逻辑表名称
       :
      # 由数据源名 + 表名组成，以小数点分隔。
      # 多个表以逗号分隔，支持inline表达式。缺省表示使用已知数据源与逻辑表名称生成数据节点。用于广播表（即每个库中都需要一个同样的表用于关联查询，多为字典表）或只分库不分表且所有库的表结构完全一致的情况

      databaseStrategy: #分库策略，缺省表示使用默认分库策略，以下的分片策略只能选其一，Sharding提供了以下4种算法接口：

        # PreciseShardingAlgorithm    精确分片算法
        ## 对应 PreciseShardingAlgorithm，用于处理使用单一键作为分片键的=与IN进行分片的场景。需要配合 StandardShardingStrategy 使用
        ## PreciseShardingAlgorithm 标准分片策略。提供对 SQL 语句中的 =, IN和BETWEEN AND 的分片操作支持。
        ## StandardShardingStrategy 只支持单分片键，提供PreciseShardingAlgorithm 和 RangeShardingAlgorithm 两个分片算法。
        ### PreciseShardingAlgorithm 是必选的，用于处理 = 和 IN 的分片
        ### RangeShardingAlgorithm 是可选的，用于处理 BETWEEN AND 分片，如果不配置 RangeShardingAlgorithm，SQL 中的 BETWEEN AND 将按照全库路由处理。

        # ComplexShardingStrategy 复合分片策略
        ## 复合分片策略。提供对SQL语句中的=, IN和BETWEEN AND的分片操作支持。
        ## ComplexShardingStrategy 支持多分片键，由于多分片键之间的关系复杂，因此 Sharding-JDBC 并未做过多的封装，而是直接将分片键值组合以及分片操作符交于算法接口，完全由应用开发者实现，提供最大的灵活度

        # InlineShardingStrategy Inline 表达式分片策略
        ## Inline 表达式分片策略。使用 Groovy 的 Inline 表达式，提供对 SQL 语句中的 = 和 IN 的分片操作支持
        ## InlineShardingStrategy 只支持单分片键，对于简单的分片算法，可以通过简单的配置使用，从而避免繁琐的 Java 代码开发，如: tuser${user_id % 8} 表示 t_user 表按照 user_id 按 8 取模分成 8 个表，表名称为 t_user_0 到 t_user_7。

        # HintShardingStrategy
        ## 通过 Hint 而非 SQL 解析的方式分片的策略。

        # NoneShardingStrategy
        ## 不分片的策略


        standard: #用于单分片键的标准分片场景
          shardingColumn: #分片列名称
          preciseAlgorithmClassName: #精确分片算法类名称，用于=和IN。。该类需实现PreciseShardingAlgorithm接口并提供无参数的构造器
          rangeAlgorithmClassName: #范围分片算法类名称，用于BETWEEN，可选。。该类需实现RangeShardingAlgorithm接口并提供无参数的构造器

        complex: #用于多分片键的复合分片场景
          shardingColumns: #分片列名称，多个列以逗号分隔
          algorithmClassName: #复合分片算法类名称。该类需实现ComplexKeysShardingAlgorithm接口并提供无参数的构造器

        inline: #行表达式分片策略
          shardingColumn: #分片列名称
          algorithmInlineExpression: #分片算法行表达式，需符合groovy语法

        hint: #Hint分片策略
          algorithmClassName: #Hint分片算法类名称。该类需实现HintShardingAlgorithm接口并提供无参数的构造器

        none: #不分片
      tableStrategy: #分表策略，同分库策略

      keyGeneratorColumnName: #自增列名称，缺省表示不使用自增主键生成器
      keyGeneratorClassName: #自增列值生成器类名称。该类需实现KeyGenerator接口并提供无参数的构造器

      logicIndex: #逻辑索引名称，对于分表的Oracle/PostgreSQL数据库中DROP INDEX XXX语句，需要通过配置逻辑索引名称定位所执行SQL的真实分表
  bindingTables: #绑定表规则列表
  - <logic_table_name1, logic_table_name2, ...>
  - <logic_table_name3, logic_table_name4, ...>
  - <logic_table_name_x, logic_table_name_y, ...>
  bindingTables: #广播表规则列表
  - table_name1
  - table_name2
  - table_name_x

  defaultDataSourceName: #未配置分片规则的表将通过默认数据源定位  
  defaultDatabaseStrategy: #默认数据库分片策略，同分库策略(databaseStrategy)
  defaultTableStrategy: #默认表分片策略，同分库策略(databaseStrategy)
  defaultKeyGeneratorClassName: #默认自增列值生成器类名称，缺省使用io.shardingsphere.core.keygen.DefaultKeyGenerator。该类需实现KeyGenerator接口并提供无参数的构造器

  masterSlaveRules: #读写分离规则，详见读写分离部分
    <data_source_name>: #数据源名称，需要与真实数据源匹配，可配置多个data_source_name
      masterDataSourceName: #详见读写分离部分
      slaveDataSourceNames: #详见读写分离部分
      loadBalanceAlgorithmClassName: #详见读写分离部分
      loadBalanceAlgorithmType: #详见读写分离部分
      configMap: #用户自定义配置
          key1: value1
          key2: value2
          keyx: valuex

  props: #属性配置
    sql.show: #是否开启SQL显示，默认值: false
    executor.size: #工作线程数量，默认值: CPU核数

  configMap: #用户自定义配置
    key1: value1
    key2: value2
    keyx: valuex
```


### 2. 读写分离

``` xml
dataSources: #省略数据源配置，与数据分片一致

masterSlaveRule:
  name: #读写分离数据源名称
  masterDataSourceName: #主库数据源名称
  slaveDataSourceNames: #从库数据源名称列表
    - <data_source_name1>
    - <data_source_name2>
    - <data_source_name_x>
  loadBalanceAlgorithmClassName: #从库负载均衡算法类名称。该类需实现MasterSlaveLoadBalanceAlgorithm接口且提供无参数构造器
  loadBalanceAlgorithmType: #从库负载均衡算法类型，可选值：ROUND_ROBIN，RANDOM。若`loadBalanceAlgorithmClassName`存在则忽略该配置

  configMap: #用户自定义配置
    key1: value1
    key2: value2
    keyx: valuex

  props: #属性配置
    sql.show: #是否开启SQL显示，默认值: false
    executor.size: #工作线程数量，默认值: CPU核数
```


### 3. 数据治理

``` xml
dataSources: #省略数据源配置
shardingRule: #省略分片规则配置
masterSlaveRule: #省略读写分离规则配置

orchestration:
  name: #数据治理实例名称
  overwrite: #本地配置是否覆盖注册中心配置。如果可覆盖，每次启动都以本地配置为准
  registry: #注册中心配置
    serverLists: #连接注册中心服务器的列表。包括IP地址和端口号。多个地址用逗号分隔。如: host1:2181,host2:2181
    namespace: #注册中心的命名空间
    digest: #连接注册中心的权限令牌。缺省为不需要权限验证
    operationTimeoutMilliseconds: #操作超时的毫秒数，默认500毫秒
    maxRetries: #连接失败后的最大重试次数，默认3次
    retryIntervalMilliseconds: #重试间隔毫秒数，默认500毫秒
    timeToLiveSeconds: #临时节点存活秒数，默认60秒
```
