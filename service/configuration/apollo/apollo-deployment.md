# apollo 分布式配置中心部署手册

## 一. 服务组成

apollo 主要由以下几个角色组成

- [架构图](http://note.youdao.com/noteshare?id=32f049b3e5a66cbb39384fc418c69223)

- Config Service
  - 提供配置的读取、推送等功能，服务对象是 Apollo 客户端
- Meta Server
  - Eureka 之上我们架了一层 Meta Server 用于封装 Eureka 的服务发现接口(这是为了支持 JAVA 意外的客户端, 支持服务发现)
  - 为了简化部署，我们实际上会把 Config Service、Eureka 和 Meta Server 三个逻辑角色部署在同一个JVM进程中
- Admin Service
  - 提供配置的修改、发布等功能
  - Portal 通过域名访问 Meta Server 获取 Admin Service 服务列表（IP+Port），而后直接通过 IP+Port 访问服务，同时在 Portal 侧会做 load balance、错误重试
- Portal
  - 配置管理、发布修改、组织架构权限等操作


## 二. 部署手册

### 1. 准备环境

- MySQL 5.6 +
  - 参数: 不区分大小写 lower_case_table_names = 1

- JDK 1.8


### 2. 编译版本和修改配置

- [基于 1.2.0 版本编译](https://gitee.com/java-project/apollo/tree/feature-v1.2.0/)


#### 2.1 下载脚本修改配置

``` sh
# 下载脚本
git clone git@gitee.com:java-project/apollo.git
git fetch origin feature-v1.2.0:feature-v1.2.0
git checkout feature-v1.2.0
```


#### 2.2 打开 ide 导入项目, 修改相关配置(使用了 feature-v1.2.0 这个版本则不需要修改)

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
  apollo/apollo-configservice/target/apollo-configservice-1.2.0-SNAPSHOT-github.zip
  apollo/apollo-adminservice/target/apollo-adminservice-1.2.0-SNAPSHOT-github.zip
  apollo/apollo-portal/target/apollo-portal-1.2.0-SNAPSHOT-github.zip  
```


## 二. 部署流程

- 部署的顺序为 configservice > adminservice > portal

- 需要注意的是
  - configservice 和 adminservice 需要组合部署的
  - 例如在 DEV 环境要部署一套, PRO 环境要部署一套
  - 环境有 (DEV/FAT/UAT/PRO)

### 1. 部署 Config Service 和 Meta Server

- Config Service 中包含了(Config Service、Meta Server、Eureka) 所以启动了 Config Service, 这 3 个服务全部启动

- 导入 Config Service 表到数据库中

``` sh
1. 创建 apollo_config_db 数据库
  # 创建数据库
  CREATE DATABASE IF NOT EXISTS apollo_config_db DEFAULT CHARACTER SET = utf8mb4;
  # 授权用户
  GRANT ALL PRIVILEGES ON  apollo_config_db.* TO  'apollo'@'%' IDENTIFIED BY 'your_password' WITH GRANT OPTION ;

2. 导入数据文件 apollo/scripts/sql/apolloconfigdb.sql 到 apollo_config_db 中
```

- 启动脚本

``` sh
# 解压
unzip -o apollo/apollo-configservice/target/apollo-configservice-1.2.0-SNAPSHOT-github.zip

1. 修改 application-github.properties 配置
  # 修改数据库配置
  vim config/application-github.properties

  # DataSource
  spring.datasource.url = jdbc:mysql://{your_db_host}:3306/apollo_config_db?characterEncoding=utf8
  spring.datasource.username = apollo
  spring.datasource.password = {your_password}

2. 修改系统表 apollo_config_db.serverconfig 参数
  # 设置 apollo-configservice 部署的 ip 和 port
  eureka.service.url http://hostname-1:20101/eureka/,http://hostname-2:20101/eureka/,http://hostname-3:20101/eureka/

3. 启动和关闭
  # 创建日志目录
  mkdir -p /opt/logs/service/apollo/configservice

  # 关闭
  scripts/startup.sh

  # 启动
  scripts/startup.sh

4. 启动成功
  netstat -tunlp | grep 20101  有数据表示启动成功
```


### 2. 部署 Admin Service

- 使用以上 Config Service 的数据库 apollo_config_db

- 启动脚本

``` sh
# 解压
unzip -o apollo/apollo-adminservice/target/apollo-adminservice-1.2.0-SNAPSHOT-github.zip

1. 修改 application-github.properties 配置

  # 修改数据库配置
  vim config/application-github.properties

  # DataSource (使用 Config Service 中 apollo_config_db 同样的数据库配置)
  spring.datasource.url = jdbc:mysql://{your_db_host}:3306/apollo_config_db?characterEncoding=utf8
  spring.datasource.username = apollo
  spring.datasource.password = {your_password}

2. 启动和关闭
  # 创建日志目录
  mkdir -p /opt/logs/service/apollo/adminservice

  # 关闭
  scripts/startup.sh

  # 启动
  scripts/startup.sh

3. 启动成功
  netstat -tunlp | grep 20102  有数据表示启动成功
```


### 3. 部署 Portal

- Portal 界面需要连接不同环境的 Meta Server(跟 Config Service 部署在一起的角色)

- 导入 Portal 表到数据库中

``` sh
1. 创建 apollo_portal_db 数据库
  # 创建数据库
  CREATE DATABASE IF NOT EXISTS apollo_portal_db DEFAULT CHARACTER SET = utf8mb4;
  # 授权用户
  GRANT ALL PRIVILEGES ON  apollo_portal_db.* TO  'apollo'@'%' IDENTIFIED BY 'your_password' WITH GRANT OPTION ;

2. 导入数据文件 apollo/scripts/sql/apolloportaldb.sql 到 apollo_portal_db 中
```

- 启动脚本

``` sh
# 解压
unzip -o apollo/apollo-portal/target/apollo-portal-1.2.0-SNAPSHOT-github.zip  

1. 设置 Portal 支持环境属性 (DEV/FAT/UAT/PRO)

  a) 修改 config/apollo-env.properties 设置已经有的环境(必须基于已经配置好的 Config Service 和 Admin Service 的环境)
    #local.meta=http://localhost:20101
    #dev.meta=http://apollo-dev-meta-server:20102
    #fat.meta=http://apollo-fat-meta-server:20101
    #uat.meta=http://apollo-uat-meta-server:20101
    #lpt.meta=${lpt_meta}
    # 为已有的环境配置 Meta Server 地址
    # 多个使用逗号分隔, 但是建议使用 nginx slb 软负载, 只填写一个 host 地址
    pro.meta=http://log1:20101,http://log2:20101,http://log3:20101

  b) 修改系统表 apollo_config_db.serverconfig 参数
    # 设置目前支持的环境, 没有的环境注释掉不要填写, 不然启动会报错
    apollo.portal.envs   PRO

2. 修改 application-github.properties 配置

  # 修改数据库配置
  vim config/application-github.properties

  # DataSource
  spring.datasource.url = jdbc:mysql://app1:3306/apollo_portal_db?characterEncoding=utf8
  spring.datasource.username = apollo
  spring.datasource.password = apollo_818

3. 启动和关闭
  # 创建日志目录
  mkdir -p /opt/logs/service/apollo/portal

  # 关闭
  scripts/startup.sh

  # 启动
  scripts/startup.sh

4. 启动成功
  netstat -tunlp | grep 20100  有数据表示启动成功

5. 问题
  a) 启动的时候如果报错
     [Apollo-ServiceLocator-1] c.c.f.a.p.c.AdminServiceAddressLocator : Get admin server address from meta server failed. env: DEV, meta server address:http://apollo.meta
  详见: https://github.com/ctripcorp/apollo/issues/1743

  b) 如果出现网络超时, 设置
    修改 scripts/startup.sh 脚本, 添加 eureka 指定注册地址
      -Deureka.instance.ip-address=172.16.24.140
    例如:
      export JAVA_OPTS="$JAVA_OPTS -Deureka.instance.ip-address=172.16.24.140 -Dserver.port=$SERVER_PORT -Dlogging.file=$LOG_DIR/$SERVICE_NAME.log -Xloggc:$LOG_DIR/gc.log -XX:NumberOfGCLogFiles=5 -XX:GCLogFileSize=5M -XX:HeapDumpPath=$LOG_DIR/HeapDumpOnOutOfMemoryError/"
```
