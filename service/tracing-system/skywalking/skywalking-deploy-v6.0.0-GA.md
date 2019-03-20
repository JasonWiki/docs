# skywalking 部署

## 下载

- [下载地址](http://skywalking.apache.org/downloads/)

``` sh
1. 下载
wget http://www.apache.org/dyn/closer.cgi/incubator/skywalking/6.0.0-GA/apache-skywalking-apm-incubating-6.0.0-GA.tar.gz

2. 解压
tar -zxvf apache-skywalking-apm-incubating-6.0.0-GA.tar.gz

3. 放到指定目录
mkdir -p ~/app/skywalking
mv apache-skywalking-apm-incubating-6.0.0-GA/*

PS. 或直接下载
  git clone -b feature-6.0.0-GA git@gitee.com:JasonWiki/skywalking.git
```


## 准备工作

- 安装 jdk1.8
- 配置 host

``` sh
sudo vim /etc/hosts

##### skywalking Start #####
192.168.1.10    skywalking-db

192.168.1.10    skywalking-server1
192.168.1.11    skywalking-server2
192.168.1.12    skywalking-server3
##### skywalking End #####
```


## 部署后端 oapService 服务

- oapService 用于接收 restful 和 rpc 发来的日志

### 配置 TiDB 存储(MySQL 同理)

``` sh
cd ~/app/skywalking

修改配置文件 config/application.yml

1. 配置集群模式, 采用 zookeeper 案例如下
cluster:
  # standalone:
  # Please check your ZooKeeper is 3.5+, However, it is also compatible with ZooKeeper 3.4.x. Replace the ZooKeeper 3.5+
  # library the oap-libs folder with your ZooKeeper 3.4.x library.
  zookeeper:
    nameSpace: ${SW_NAMESPACE:"skywalking"}
    hostPort: zookeeper-server1:2181,zookeeper-server2:2181,zookeeper-server3:2181

2. 配置 storage 存储, 其他全部注释, 打开 mysql
storage:
  mysql:


3. 配置路径跟踪缓冲区文件
# 创建日志文件
sudo mkdir -p /opt/logs/skywalking
sudo chomd 777 /opt/logs/skywalking

# 配置目录
receiver-trace:
  default:
    bufferPath: ${SW_RECEIVER_BUFFER_PATH:/opt/logs/skywalking/trace-buffer/}


4. 配置 store, skywalking 数据库(MySQL 开账号)
# 下载 mysql 驱动
wget http://central.maven.org/maven2/mysql/mysql-connector-java/8.0.15/mysql-connector-java-8.0.15.jar  --directory-prefix=oap-libs/

# 配置数据库信息
vim config/datasource-settings.properties

# 注意数据库连接地址, 端口, 登录信息需要符合要求
jdbcUrl=jdbc:mysql://skywalking-db:4000/skywalking
dataSource.user=skywalking
dataSource.password=yourpass
dataSource.cachePrepStmts=true
dataSource.prepStmtCacheSize=250
dataSource.prepStmtCacheSqlLimit=2048
dataSource.useServerPrepStmts=true
dataSource.useLocalSessionState=true
dataSource.rewriteBatchedStatements=true
dataSource.cacheResultSetMetadata=true
dataSource.cacheServerConfiguration=true
dataSource.elideSetAutoCommits=true
dataSource.maintainTimeStats=false


5. 在 MySQL 创建账号
CREATE DATABASE IF NOT EXISTS skywalking DEFAULT CHARACTER SET = utf8mb4  collate utf8mb4_general_ci;
create user 'skywalking'@'yourip' IDENTIFIED BY 'yourpass' ;
GRANT ALL PRIVILEGES on skywalking.* to 'skywalking'@'yourip';


6. 初始化(在此模式下，oap 服务器启动以执行初始化工作，然后退出。使用此模式初始化存储，例如 ElasticSearch 索引，MySQL 和 TiDB 表以及 init 数据。)
# 初始化存储等信息
./bin/oapServiceInit.sh

# 监控日志
tail -f logs/skywalking-oap-server.log


7. 启动 oapService 服务
# 启动
./bin/oapService.sh

# 监控进程
jps | grep OAPServerStartUp
netstat -tunlp | grep 11800
netstat -tunlp | grep 12800

restfulPort 接收器端口
  restPort：$ {SW_SHARING_SERVER_REST_PORT：12800}
gRPCPort    接收器端口
  gRPCPort：$ {SW_SHARING_SERVER_GRPC_PORT：11800}
```



## 部署 UI

### 官方默认 UI

``` sh
1. 修改配置文件
vim webapp/webapp.yml

server:
  port: 20140

collector:
  path: /graphql
  ribbon:
    ReadTimeout: 10000
    # Point to all backend's restHost:restPort, split by ,
    listOfServers: skywalking-server1:12800,skywalking-server2:12800,skywalking-server3:12800


2. 启动服务
./bin/webappService.sh

# 查看服务启动情况
jps | grep skywalking-webapp
netstat -tunlp | grep 20140
```
