# Cloudera yum 方式安装

- 安装流程

- 1 首先配置系统环境

- 2 安装 `Cloudera Manager Server`
  - 2.1 配置 yum 源, 执行安装流程
  - 2.2 配置 SCM 数据库
  - 2.3 启动 Cloudera Manager Server 服务(监控报错日志)
  - 2.4 打开 http://hostname:7180 端口, 跳过所有安装步骤, 直接添加 Cloudera Management Service 服务

- 2 Cloudera Manager Agent -> `Cloudera Manager Server 注册节点`
  - 4.1 配置 yum 源, 执行安装流程
  - 4.2 修改配置 Cloudera Manager Agent 服务指向 Cloudera Manager Server 服务所在的 host, 配置文件 /etc/cloudera-scm-agent/config.ini 的 server_host -> Cloudera Manager Server
  - 4.3 手动启动 Cloudera Manager Agent 服务(监控报错日志)
  - 4.4 注意第一次 Agent 注册到 Server 节点是没有 CDH 版本的, 需要在 Server 管理界面添加已经注册的主机(分发 CDH 版本的)
  - 4.5 Cloudera Manager Server 7180 -> 主机管理界面, 点击添加主机到集群中(选中已注册的节点), 这个时候才会有 CDH 版本信息

- 4 CDH 组件 -> Cloudera Manager Server 和 Cloudera Manager Agent 安装完成后再配置 Hadoop 组件

## * 系统环境配置

### 1. 环境属性

``` sh
1. 创建用户 cloudera-scm, 给与免密码 sudo 权限

  vim /etc/sudoers
  # 以下注释取消
  %wheel  ALL=(ALL)       NOPASSWD: ALL

  1) 方法 1
    userdel cloudera-scm
    # 创建cm部署用户
    groupadd -r cloudera-scm
    #  分配到 组
    useradd -m -s /bin/bash -g  cloudera-scm cloudera-scm
    # 追加用户到 sudo 组
    usermod -G wheel cloudera-scm


    # 创建hadoop用户
    userdel hadoop
    groupadd -r hadoop
    useradd -m -s /bin/bash -g  hadoop hadoop
    # 追加用户到 sudo 组
    usermod -G 	wheel hadoop

  2) 方法 2
    %cloudera-scm ALL=(ALL) NOPASSWD: ALL  账号其他配置

  *) sudo 权限附属配置
    vim /etc/sudoers
    # 确认包含如下
    Defaults secure_path = /sbin:/bin:/usr/sbin:/usr/bin

    vim /etc/pam.d/su
    # 确保包含如下行
    session         required        pam_limits.so


2. 同步系统时区(每台服务器的时区必须一样)
  yum install ntp

  ntpdate -d time.nist.gov 或者 ntpdate -d pool.ntp.org

  ntpdate -d node-host

  crontab -e , 添加如下命令
    */5 * * * * /usr/sbin/ntpdate monitor.50bang.org && /sbin/hwclock -w


3. 关闭防火墙
  // Centos 6
  service iptables stop
  chkconfig iptables off

  service ip6tables stop
  chkconfig ip6tables off

  // Centos 7
  systemctl stop firewalld
  systemctl disable firewalld


3.1 设置 HOSTNAME 与 域名统一
  vim /etc/sysconfig/network
  HOSTNAME=dw[N]  根据实际情况填写


4. 关闭 selinux
  # 临时生效
  setenforce 0
  # 永久失效
  vi /etc/selinux/config
  SELINUX=disabled


5. 安装 scp
  yum -y install openssh-clients


6. ssh key  
  # 生成 key
  ssh-keygen

  # 分配
  ssh-copy-id -i ~/.ssh/id_rsa.pub username@hostname

```


### 2. 系统属性

``` sh
1. 禁用大透明页

  1.1 Centos 6:
    cat /sys/kernel/mm/redhat_transparent_hugepage/defrag   
      [always] never 表示已启用透明大页面压缩。
      always [never] 表示已禁用透明大页面压缩。

    如果启用, 请关闭
    echo 'never' > /sys/kernel/mm/redhat_transparent_hugepage/defrag
    echo 'never' > /sys/kernel/mm/transparent_hugepage/enabled

    加入开机启动中
    vim /etc/rc.local (给执行权限 chmod +x /etc/rc.d/rc.local )
    # 禁用大透明页
    echo 'never' > /sys/kernel/mm/redhat_transparent_hugepage/defrag
    echo 'never' > /sys/kernel/mm/transparent_hugepage/enabled

  1.2 Centos 7:
    cat /sys/kernel/mm/transparent_hugepage/defrag    

    如果启用, 请关闭
    echo 'never' >  /sys/kernel/mm/transparent_hugepage/defrag    

    加入开机启动中
    vim /etc/rc.local (给执行权限 chmod +x /etc/rc.d/rc.local )
    # 禁用大透明页
    echo 'never' >  /sys/kernel/mm/transparent_hugepage/defrag   


2. vm.swappiness Linux 内核参数
  # 默认为 60 , 用于控制将内存页交换到磁盘的幅度, 介于 0-100 之间的值；值越高，内核寻找不活动的内存页并将其交换到磁盘的幅度就越大。
  cat /proc/sys/vm/swappiness

  # 设置为 0
  sysctl -w vm.swappiness=0

3. 集群挂载的文件系统,不使用 RAID 和 LVM 文件系统

4. 最大打开的文件数
  sysctl -a | grep fs.file

  如果比 65535 小, 则设置如下参数
  sudo vim /etc/security/limits.conf
  *    hard    nofile          102400
  *    soft    nofile          102400
```


## 一、安装 Cloudera Manager Server

- Cloudera Management Service 可作为一组角色实施各种管理功能
- Activity Monitor
  - 收集有关 MapReduce 服务运行的活动的信息。默认情况下未添加此角色。
- Host Monitor
  - 收集有关主机的运行状况和指标信息
- Service Monitor
  - 收集有关服务的运行状况和指标信息以及 YARN 和 Impala 服务中的活动信息
- Event Server
  - 聚合 relevant Hadoop 事件并将其用于警报和搜索
- Alert Publisher
  - 为特定类型的事件生成和提供警报
- Reports Manager
  - 生成报告，它提供用户、用户组和目录的磁盘使用率的历史视图，用户和 YARN 池的处理活动，以及 HBase 表和命名空间。此角色未在 Cloudera Express 中添加。
- Cloudera Manager 将单独管理每个角色，而不是作为 Cloudera Manager Server 的一部分进行管理，可实现可扩展性（例如，在大型部署中，它可用于将监控器角色置于自身的主机上）和隔离。


``` sh
* 登录 cloudera-scm 账号

1. 添加 cloudera-manager.repo 源(所有节点)
  Centos 6: sudo wget http://archive.cloudera.com/cm5/redhat/6/x86_64/cm/cloudera-manager.repo --directory-prefi=/etc/yum.repos.d

  Centos 7: sudo wget http://archive.cloudera.com/cm5/redhat/7/x86_64/cm/cloudera-manager.repo --directory-prefi=/etc/yum.repos.d

  PS: 若要安装指定的版本, 修改如下参数
    sudo vim /etc/yum.repos.d/cloudera-manager.repo

    # Centos 6: 修改 baseurl 属性中的 url (url 可以指定版本)
    baseurl=http://archive.cloudera.com/cm5/redhat/6/x86_64/cm/5.9.0/

    # Centos 7: 修改 baseurl 属性中的 url (url 可以指定版本)
    baseurl=http://archive.cloudera.com/cm5/redhat/7/x86_64/cm/5.9.0/

  更新 yum:
    yum update

2. 下载安装组件
  a) 安装 JAVA
    # PS 这个包在 CM 的源中
    sudo yum install oracle-j2sdk1.7

    # 加入环境变量
    vim ~/.bashrc

    # CDH JAVA HOME
    export JAVA_HOME=/usr/java/jdk1.7.0_67-cloudera
    export JRE_HOME=${JAVA_HOME}/jre
    export CLASSPATH=.:${JAVA_HOME}/lib:${JRE_HOME}/lib
    export PATH=${JAVA_HOME}/bin:$PATH

    source ~/.bashrc


  PS) 这里可以配置 JAVA 1.8 的版本, 安装好 CM 后, 启动 Web /cmf/hardware/hosts/config, 其中可以配置 JAVA Home 的目录
    # 下载、安装、权限
    下载软件 jdk-8u131-linux-x64.tar.gz (oracle 版本)
    安装目录 /opt/app/jdk-1.8.0_131
    给与 /opt/app/jdk-1.8.0_131 目录 hadoop 权限(774 -R)

    # 配置 Cloudera Java 软链
    sudo mkdir -p /usr/java/
    sudo ln -s /opt/app/jdk-1.8.0_131 /usr/java/jdk1.8

    # 配置环境变量
    vim ~/.bashrc

    # CDH JAVA HOME
    export JAVA_HOME=/opt/app/jdk-1.8.0_131
    export JRE_HOME=${JAVA_HOME}/jre
    export CLASSPATH=.:${JAVA_HOME}/lib:${JRE_HOME}/lib
    export PATH=${JAVA_HOME}/bin:$PATH

    source ~/.bashrc


  b) 安装服务 Cloudera 组件
    sudo yum install cloudera-manager-daemons
    sudo yum install cloudera-manager-server


  d) 下载 mysql-connector-java-5.1.40.jar 依赖包
    # 下载 MySQL 驱动
    sudo wget http://central.maven.org/maven2/mysql/mysql-connector-java/5.1.40/mysql-connector-java-5.1.40.jar --directory-prefix=/usr/share/java/

    # 放到 cmf 公用库中
    sudo cp /usr/share/java/mysql-connector-java-5.1.40.jar  /usr/share/cmf/lib/mysql-connector-java-5.1.40.jar


3. 安装 scm 数据库
  1)方法 1 使用脚本配置
    /usr/share/cmf/schema/scm_prepare_database.sh mysql -hmysql-host -P3306 -uroot -pxxx.com scm hadoop xxx.com

    具体参数看配置 : /usr/share/cmf/schema/scm_prepare_database.sh --help

  2)方法 2 编写配置文件 /etc/cloudera-scm-server/db.properties
    com.cloudera.cmf.db.type=mysql
    com.cloudera.cmf.db.host=manager1:3306
    com.cloudera.cmf.db.name=scm
    com.cloudera.cmf.db.user=hadoop
    com.cloudera.cmf.db.password=xxx.com
    com.cloudera.cmf.db.setupType=EXTERNAL


4. root 权限登录 mysql 查看账户是否生效
  SELECT DISTINCT CONCAT('User: ''',user,'''@''',host,''';') AS query FROM mysql.user;


5. 监控启动服务
  tail -f /var/log/cloudera-scm-server/cloudera-scm-server.log

  # 给与目录权限
  sudo chmod 775 -R /opt/cloudera/parcel-repo/
  sudo chmod 775 -R /var/lib/cloudera-scm-server

  # 启动 cm 服务
  sudo service cloudera-scm-server restart

  # 查看 服务状态
  service cloudera-scm-server status

  # 监控端口
  netstat -tunlp

  7180 : Http Web 服务

  7182 : 监控通讯服务


6. 启动完成后(最后步骤)

  # 创建 /opt/cloudera/parcels 目录
  sudo mkdir -p /opt/cloudera
  sudo mkdir -p /opt/cloudera/parcel-repo/
  sudo chown cloudera-scm:cloudera-scm -R /opt/cloudera
  sudo chown cloudera-scm:cloudera-scm -R /opt/cloudera

  # 下载 3 个文件
  wget http://archive.cloudera.com/cdh5/parcels/5.13.1/CDH-5.13.1-1.cdh5.13.1.p0.2-el7.parcel --directory-prefix=/opt/cloudera/parcel-repo/
  wget http://archive.cloudera.com/cdh5/parcels/5.13.1/CDH-5.13.1-1.cdh5.13.1.p0.2-el7.parcel.sha1 --directory-prefix=/opt/cloudera/parcel-repo/
  wget http://archive.cloudera.com/cdh5/parcels/5.13.1/manifest.json --directory-prefix=/opt/cloudera/parcel-repo/
  # 修改数据 *.sha1 mv *.sha, 不然会出现如下错误 (对于此 Cloudera Manager 版本 (version) 太新的 CDH 版本不会显示)
  mv /opt/cloudera/parcel-repo/CDH-5.13.1-1.cdh5.13.1.p0.2-el7.parcel.sha1 /opt/cloudera/parcel-repo/CDH-5.13.1-1.cdh5.13.1.p0.2-el7.parcel.sha


* 启动遇到的问题

对于此 Cloudera Manager 版本 (5.4.7) 太新的 CDH 版本不会显示
  问题描述：Versions of CDH that are too new for this version of Cloudera Manager (5.4.7) will not be shown.
  问题定位：PARCELS 表 fileName=CDH-5.4.7-1.cdh5.4.7.p0.3-el6.parcel 的 hash 值为 null，判断为parcel文件问题或scm数据库生成干扰问题
  解决思路：判断为/ opt/cloudera/parcel-repo/ 目录内的本地parcel应该在scm数据库初始化结束后再放入
  问题解决：停止server > 重置scm数据库 > 启动server > 复制 parcel 到 /opt/cloudera/parcel-repo/ 目录

```


## 二、安装 Cloudera Agent

- 请参照 安装流程 4

``` sh
1. 登录 cloudera-scm 账号

  # 配置源 Cloudera yum 源


2. 下载安装组件
  a) 安装 JAVA

    # 同如上步骤

  b) 安装服务
    sudo yum install cloudera-manager-daemons
    sudo yum install cloudera-manager-agent


2. 修改配置文件
  sudo vim /etc/cloudera-scm-agent/config.ini

  # 改成这里 server 的主机地址
  server_host=manager1


3. 监控启动服务

  # 监控日志
  tail -f /var/log/cloudera-scm-agent/cloudera-scm-agent.log

  # 给与目录权限
  sudo chown cloudera-scm:cloudera-scm -R /var/lib/cloudera-scm-agent
  sudo chown cloudera-scm:cloudera-scm -R /var/run/cloudera-scm-agent

  # 创建 /opt/cloudera/parcels 目录
  mkdir -p /opt/cloudera
  sudo chown cloudera-scm:cloudera-scm -R /opt/cloudera

  # 启动 agent 服务, 开启端口
  sudo service cloudera-scm-agent restart

  # 查看运行状态
  service cloudera-scm-agent status

  # 查看监控端口
  netstat -tunlp

  9000 : HTTP （调试）端口
  19001 : supervisord 状态和控制端口；用于 Agent 和 supervisord 之间的通信；仅内部打开（本地主机上）


*. 注册可能遇到的问题

  1. 时间与 cm 相差太多导致无法 agent 无法向 cm 注册

  2. host 与 ip 设置错误
    查看主机与 host 与 ip
    python -c 'import socket; print socket.getfqdn(), socket.gethostbyname(socket.getfqdn())'

    hostname 环境变量主机名不同(会导致 agent 注册到 manager, hostname 不对)

  3. Failed to connect to previous supervisor.
    一般这种情况不用处理, 只要能 agent 能注册到 server, 并且 agent 在 server 是绿颜色的心态即可
    参照安装步骤 4

  4. cdh No extant cgroups 错误
    /var/run/cloudera-scm-agent 和  /var/lib/cloudera-scm-agent 目录权限

    sudo chown cloudera-scm:cloudera-scm -R /var/lib/cloudera-scm-agent
    sudo chown cloudera-scm:cloudera-scm -R /var/run/cloudera-scm-agent

  5. Error, CM server guid updated, expected  错误描述, CM 重新安装后 ID 错误
    rm /var/lib/cloudera-scm-agent/cm_guid  , 主要原因是因为 .parcel.sha1 没有重名为 .parcel.sha

  6. ERROR Failed to connect to newly launched supervisor. Agent will exit
    pgrep -f supervisord , 然后 Kill 掉 , 主要是更换版本的时候有服务没有杀干净

  *. 排查思路
    1) 检查 hostname、/etc/hosts、 主机 IP 是否正确

    2) 是否关闭防火墙和 Selinux, 当前服务器和目标服务器

    3) Agent 本身配置问题
      /etc/cloudera-scm-agent/config.ini

    4) 检查各个节点的系统日期
```


## 三、安装 CDH 组件

- 配置源 CDH 源

``` sh
*. 登录 hadoop 用户

  sudo wget http://archive.cloudera.com/cdh5/redhat/6/x86_64/cdh/cloudera-cdh5.repo --directory-prefi=/etc/yum.repos.d

  PS: 若要安装指定的版本, 修改如下参数
  sudo vim /etc/yum.repos.d/cloudera-cdh5.repo
  # 修改 baseurl 属性中的 url (url 可以指定版本)
  baseurl=https://archive.cloudera.com/cdh5/redhat/6/x86_64/cdh/5.9.0/

yum update

```

### 1. 安装 Flume

- 具体说明转 flume 文档

``` sh
sudo yum install flume-ng-agent
```




## 四、授权数据库服务

``` sql
-- hive
CREATE DATABASE `hive` /*!40100 DEFAULT CHARACTER SET utf8 */;
GRANT ALL PRIVILEGES ON hive.* TO 'hadoop'@'%' WITH GRANT OPTION;
flush privileges;

-- Hive 的元数据库是
# 找到对应版本的, 复制 Hive 版本的 Sql 文件, 放在 MySQL 客户端执行即可, 出现问题解决问题, 直到全部创建完成即可
$HIVE_HOME/scripts/metastore/upgrade/mysql/hive-schema-1.1.0.mysql.sql (与 Hive 版本相同)
$HIVE_HOME/scripts/metastore/upgrade/mysql/upgrade-1.1.0-to-1.1.0-cdh5.12.0.mysql.sql (与 Hive 版本相同)

# CDH
datanucleus.autoCreateSchema=false
datanucleus.metadata.validate=false
hive.metastore.schema.verification=false
# 启用直接 SQL
hive.metastore.try.direct.sql=true



-- hue
CREATE DATABASE `hue` /*!40100 DEFAULT CHARACTER SET utf8 */;
GRANT ALL PRIVILEGES ON hue.* TO 'hadoop'@'%' WITH GRANT OPTION;
flush privileges;


-- oozie
CREATE DATABASE `oozie` /*!40100 DEFAULT CHARACTER SET utf8 */;
GRANT ALL PRIVILEGES ON oozie.* TO 'hadoop'@'%' WITH GRANT OPTION;
flush privileges;

```


## 五、重新安装,删除所有组件

``` sh
# 删除组件
sudo yum remove cloudera-manager-daemons
sudo yum remove cloudera-manager-server
sudo yum remove cloudera-manager-server-db-2
sudo yum remove cloudera-manager-agent


# server
sudo service cloudera-scm-server stop
sudo rm -rf /var/lib/cloudera-manager-server
sudo rm -rf /var/run/cloudera-manager-server


# agent
sudo service cloudera-scm-agent stop
sudo rm -rf /var/lib/cloudera-scm-agent
sudo rm -rf /var/run/cloudera-scm-agent
sudo rm -rf /run/cloudera-scm-agent

```

## * 常见问题

### 1. oozie 找不到 mysql-connector.jar

``` sh
把 mysql-connector.jar 复制到 /var/lib/oozie 中.
cp /path/mysql-connector-java-5.1.40.jar /var/lib/oozie  中


# 添加 Jar 到 hive
sudo cp /usr/share/java/mysql-connector-java-5.1.40.jar /opt/cloudera/parcels/CDH/lib/hive/lib/mysql-connector-java-5.1.40.jar

# 添加 Jar 到 Oozie
sudo cp /usr/share/java/mysql-connector-java-5.1.40.jar /opt/cloudera/parcels/CDH/lib/oozie/lib/mysql-connector-java-5.1.40.jar
sudo cp /usr/share/java/mysql-connector-java-5.1.40.jar /var/lib/oozie/
```
