# apollo 分布式多机房部署手册

## 一. 服务组成

apollo 主要由以下几个角色组成

- [架构图](http://note.youdao.com/noteshare?id=32f049b3e5a66cbb39384fc418c69223)
- [分布式部署手册](https://github.com/ctripcorp/apollo/wiki/%E5%88%86%E5%B8%83%E5%BC%8F%E9%83%A8%E7%BD%B2%E6%8C%87%E5%8D%97)

- Config Service 职责
  - 提供配置的读取、推送等功能，服务对象是 Apollo 客户端
- Meta Server 职责
  - Eureka 之上架了一层 Meta Server 用于封装 Eureka 的服务发现接口(这是为了支持 JAVA 意外的客户端, 支持服务发现)
  - 为了简化部署，实际上会把 Config Service、Eureka 和 Meta Server 三个逻辑角色部署在同一个 JVM 进程中
- Admin Service 职责
  - 提供配置的修改、发布等功能
- Portal 职责
  - 配置管理、发布修改、组织架构权限等操作
  - Portal 通过域名访问 Meta Server 获取 Admin Service 服务列表（IP+Port）
  - Portal 拿到 Admin Service 地址后, 而后直接通过 IP+Port 访问 Admin Service 服务，同时在 Portal 会做 load balance、错误重试
- ENV 环境概念
  - DEV Development environment   开发环境
  - FAT Feature Acceptance Test environment   特性验收测试环境
  - UAT User Acceptance Test environment      用户验收测试环境
  - PRO Production environment   产品环境生产环境
- 三大核心概念
  - 应用 应用唯一标识
  - 环境 软件各类环境
  - 集群 不同机房
  - 命名空间 配置分层


## 二. 准备工作

### 1. 环境配置要求

- MySQL 5.6 +
  - 参数: 不区分大小写 lower_case_table_names = 1
- JDK 1.8


### 2. 编译版本和修改配置

- [基于 1.4.0 版本编译](https://gitee.com/java-project/apollo/tree/feature-v1.4.0/)
- [官方文档](https://github.com/ctripcorp/apollo/wiki/分布式部署指南)  


#### 2.1 下载脚本修改配置

``` sh
# 下载脚本
git clone git@gitee.com:java-project/apollo.git
git fetch origin feature-v1.4.0:feature-v1.4.0
git checkout feature-v1.4.0
```

#### 2.2 打开 ide 导入项目, 修改相关配置

``` conf
1. apollo-configservice 项目修改日志地址、端口、脚本
  conf/
    apollo-configservice.conf
        LOG_FOLDER=/opt/logs/service/apollo/configservice/

  resources/
    application.yml
      logging:
        file: /opt/logs/service/apollo/configservice/apollo-configservice.log
      server:
        port: 20101 (old: 8080)

    configservice.properties
      logging.file= /opt/logs/service/apollo/configservice/apollo-configservice.log
      server.port= 20101

    scripts/
      startup.sh
        LOG_DIR=/opt/logs/service/apollo/configservice
        SERVER_PORT=20101


2. apollo-adminservice 项目修改日志地址、端口、脚本
  conf/
    apollo-adminservice.conf
      LOG_FOLDER=/opt/logs/service/apollo/adminservice/

  resources/
    application.yml
      logging:
        file: /opt/logs/service/apollo/adminservice/apollo-adminservice.log
      server:
        port: 20102 (old: 8090)

    configservice.properties
      logging.file= /opt/logs/service/apollo/adminservice/apollo-adminservice.log
      server.port= 20102

  scripts/
    startup.sh
      LOG_DIR=/opt/logs/service/apollo/adminservice
      SERVER_PORT=20102


3. apollo-portal 项目修改日志地址、端口、脚本
  conf/
    apollo-portal.conf
      LOG_FOLDER=/opt/logs/service/apollo/portal/

  resources/
    application.yml
      logging:
        file: /opt/logs/service/apollo/portal/apollo-portal.log
      server:
        port: 20100 (old: 8070)

  scripts/
    startup.sh
      LOG_DIR= /opt/logs/service/apollo/portal
      SERVER_PORT=20100
```


#### 2.3 编译脚本

``` sh
# 编译脚本
apollo/scripts/build.sh

编译完成后会有 3 个包 zip 压缩包, apollo-portal、apollo-adminservice、apollo-portal, 目录如下
  apollo/apollo-configservice/target/apollo-configservice-1.4.0-SNAPSHOT-github.zip
  apollo/apollo-adminservice/target/apollo-adminservice-1.4.0-SNAPSHOT-github.zip
  apollo/apollo-portal/target/apollo-portal-1.4.0-SNAPSHOT-github.zip  
```


## 三. 分布式部署

- [分布式部署图纸](https://raw.githubusercontent.com/ctripcorp/apollo/master/doc/images/apollo-deployment.png)
- 支持环境有 (DEV/FAT/UAT/PRO), 支持多机房
- 部署的顺序为 configservice > adminservice > portal

### 1. 部署 Config Service / Admin Service

- 本案例使用 PRO 环境为例, 其他环境模式相同, 直接 copy 即可.

- PRO 环境(多机房部署)
  - 无锡机房, 同一台物理机器同时部署 Configservice 和 Adminservice
  - 宁波机房, 同一台物理机器同时部署 Configservice 和 Adminservice
- DEV 环境(单机房部署)

#### 1.1 配置环境独立 ApolloConfigDB(apollo_config_db) 数据库

- 相同环境, 共用一个数据库服务器, 多个机房也共用一个数据库服务器
- 不同环境, 各自使用独立的数据库服务器
- 例如
  - PRO 环境, 部署在一个或者两个机房, 只需要一个数据库服务器
  - DEV 环境 / PRO 环境, 属于两个环境, 要分开, 各自使用不同的数据库服务器

``` sh
1. 创建 apollo_config_db 数据库
  # 创建数据库
  CREATE DATABASE IF NOT EXISTS apollo_config_db DEFAULT CHARACTER SET = utf8mb4;
  # 授权用户
  GRANT ALL PRIVILEGES ON  apollo_config_db.* TO  'apollo'@'%' IDENTIFIED BY 'your_password' WITH GRANT OPTION ;

2. 导入数据文件 scripts/db/migration/configdb/V1.0.0__initialization.sql 到 apollo_config_db 中

3. 修改 ApolloConfigDB.serverconfig 的 Eureka 参数

  # 在多机房部署时 Config Service / Admin Service 希望只向同机房的 eureka 注册
  a) 利用 Cluster 字段, 添加 Cluster 为 wuxi 机房的配置, 配置如下(如果不指定 Cluster, 则全部使用 default 的配置)
    Key:        eureka.service.url
    Cluster:    wuxi
    Value:      http://hotname1:20101/eureka/,http://hotname2:20101/eureka/,http://hotname3:20101/eureka/
    Comment:    wuxi 机房的 Eureka 服务 Url

  b) 创建配置文件 /opt/settings/server.properties, 设置 idc 属性为无锡机房, 此时 Config Service / Admin Service 启动时会向指定机房注册
    idc=wuxi

4. 其他优化配置
  a) 开启缓存
    config-service.cache.enabled  true
```

#### 1.2 部署 Config Service 服务

- Config Service 本身就是一个 Eureka 服务, 其中包含了(Config Service、Meta Server、Eureka) 所以启动了 Config Service, 这 3 个服务全部启动
- Config Service / Admin Service 都需要向 Eureka 服务注册

``` sh
1. 部署脚本
# 解压
  unzip -o apollo/apollo-configservice/target/apollo-configservice-1.4.0-SNAPSHOT-github.zip

# 修改 application-github.properties 数据库配置
  vim config/application-github.properties

  # DataSource
  spring.datasource.url = jdbc:mysql://{your_db_host}:3306/apollo_config_db?characterEncoding=utf8
  spring.datasource.username = apollo
  spring.datasource.password = {your_password}


2. 启动和关闭
  # 创建日志目录
  mkdir -p /opt/logs/service/apollo/configservice

  # 关闭
  scripts/shutdown.sh

  # 启动
  scripts/startup.sh


3. 启动成功
  netstat -tunlp | grep 20101  有数据表示启动成功
```


#### 1.3 部署 Admin Service 服务

- Admin Service 与 Config Service 部署在同一台物理机上
- Admin Service 与 Config Service 使用同一个 apollo_config_db

``` sh
1. 部署脚本
 # 解压
 unzip -o apollo/apollo-adminservice/target/apollo-adminservice-1.4.0-SNAPSHOT-github.zip

2. 修改 application-github.properties 配置

  # 修改数据库配置
  vim config/application-github.properties

  # DataSource (使用 Config Service 中 apollo_config_db 同样的数据库配置)
  spring.datasource.url = jdbc:mysql://{your_db_host}:3306/apollo_config_db?characterEncoding=utf8
  spring.datasource.username = apollo
  spring.datasource.password = {your_password}

3. 启动和关闭
  # 创建日志目录
  mkdir -p /opt/logs/service/apollo/adminservice

  # 关闭
  scripts/shutdown.sh

  # 启动
  scripts/startup.sh

4. 启动成功
  netstat -tunlp | grep 20102  有数据表示启动成功
```


### 2. 配置 Portal 服务

- Portal 是控制管理界面, 管理所有环境(DEV/FAT/UAT/PRO), 所以 Portal 要能够与所有环境通讯
- Portal 会连接不同环境的 Meta Server 拿到 Admin Service 地址与之通讯

#### 2.1 创建 ApolloPortalDB(apollo_portal_db) 数据库

- ApolloPortalDB 只需要单独部署一个即可, 不需要分环境

``` sh
1. 创建 apollo_portal_db 数据库
  # 创建数据库
  CREATE DATABASE IF NOT EXISTS apollo_portal_db DEFAULT CHARACTER SET = utf8mb4;
  # 授权用户
  GRANT ALL PRIVILEGES ON  apollo_portal_db.* TO  'apollo'@'%' IDENTIFIED BY 'your_password' WITH GRANT OPTION ;

2. 导入数据文件 scripts/db/migration/portaldb/V1.0.0__initialization.sql 到 apollo_portal_db 中

3. 修改系统表 ApolloPortalDB.serverconfig 参数

  a) 设置目前支持的环境(DEV,FAT,UAT,PRO)多个用逗号分隔, 没有的环境注释掉不要填写, 不然启动会报错
    apollo.portal.envs   PRO
```

#### 2.2 配置 Portal 脚本

- Portal 必须基于已有环境(DEV/FAT/UAT/PRO) 的 Config Service 和 Admin Service

``` sh
1. 部署脚本
  # 解压
  unzip -o apollo/apollo-portal/target/apollo-portal-1.4.0-SNAPSHOT-github.zip  

2. 设置 Portal 支持环境属性 (DEV/FAT/UAT/PRO)

  a) 修改 config/apollo-env.properties 设置已经有的环境, 建议使用 nginx slb 软负载, 只填写一个 host 地址
    #local.meta=http://localhost:20101
    # dev 测试环境所在的  Config Service 地址
    dev.meta=http://apollo-dev-meta-server:20101
    #
    fat.meta=http://apollo-fat-meta-server:20101
    uat.meta=http://apollo-uat-meta-server:20101
    lpt.meta=${lpt_meta}

    # PRO 生产环境所在的 Config Service 地址
    pro.meta=http://apollo-pro-meta-server1:20101,http://apollo-pro-meta-server2:20101,http://apollo-pro-meta-server3:20101

3. 修改 application-github.properties 配置
  # 修改数据库配置
  vim config/application-github.properties

  # DataSource
  spring.datasource.url = jdbc:mysql://app1:3306/apollo_portal_db?characterEncoding=utf8
  spring.datasource.username = apollo
  spring.datasource.password = apollo_818

4. 启动和关闭
  # 创建日志目录
  mkdir -p /opt/logs/service/apollo/portal

  # 关闭
  scripts/shutdown.sh

  # 启动
  scripts/startup.sh

5. 启动成功
  netstat -tunlp | grep 20100  有数据表示启动成功

6. 问题
  a) 启动的时候如果报错
     [Apollo-ServiceLocator-1] c.c.f.a.p.c.AdminServiceAddressLocator : Get admin server address from meta server failed. env: DEV, meta server address:http://apollo.meta
  详见: https://github.com/ctripcorp/apollo/issues/1743

  b) 如果出现网络超时, 设置
    修改 scripts/startup.sh 脚本, 添加 eureka 指定注册地址
      -Deureka.instance.ip-address=xxx.xxx.com
    例如:
      export JAVA_OPTS="$JAVA_OPTS -Deureka.instance.ip-address=xxx.xxx.com -Dserver.port=$SERVER_PORT -Dlogging.file=$LOG_DIR/$SERVICE_NAME.log -Xloggc:$LOG_DIR/gc.log -XX:NumberOfGCLogFiles=5 -XX:GCLogFileSize=5M -XX:HeapDumpPath=$LOG_DIR/HeapDumpOnOutOfMemoryError/"
```
