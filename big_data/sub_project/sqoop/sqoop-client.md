# sqoop 客户端配置


## 一、配置

``` sh
1. 安装 sqoop
  sudo apt-get install sqoop

2. Sqoop 环境

  vim ~/.bashrc
  # SQOOP
  export SQOOP_HOME=/usr/lib/sqoop
  export SQOOP_CONF_DIR=$SQOOP_HOME/conf

3. Sqoop 配置

  sudo cp $SQOOP_HOME/conf/sqoop-env-template.sh $SQOOP_HOME/conf/sqoop-env.sh

  # 配置 Hive 环境
  sudo vim $SQOOP_HOME/conf/sqoop-env.sh

  export HIVE_CONF_DIR=$HIVE_HOME/conf
  export HADOOP_CLASSPATH=$HADOOP_CLASSPATH:$HIVE_HOME/lib/*

  # 软链 hive 配置文件
  sudo ln -s $HIVE_HOME/conf/hive-site.xml $SQOOP_HOME/conf/

```

## 二、常见问题

``` sh
1.出现 org.apache.sqoop.Sqoop 找不到主类

  解决 : 把 sqoop 目录下的 sqoop-1.4.4.jar 拷贝到 hadoop 的 lib 目录下
  cd /opt/cloudera/parcels/CDH/lib/hadoop
  sudo ln -s ../../jars/sqoop-1.4.5-cdh5.3.3.jar ./

2.mysql 类加载不到

  解决 : 下载 mysql JDBC 放到 hadoop 目录下即可
  cd /opt/cloudera/parcels/CDH/lib/hadoop
  sudo ln -s ../../jars/mysql-connector-java-5.1.31.jar ./


3. HADOOP_MAPRED_HOME is /usr/lib/hadoop-mapreduce 找不到
  ERROR tool.ImportTool: Imported Failed: Parameter 'directory' is not a directory

  解决 : sudo ln -s /opt/cloudera/parcels/CDH/lib/hadoop-mapreduce /usr/lib/hadoop-mapreduce


4. Could not load org.apache.hadoop.hive.conf.HiveConf. Make sure HIVE_CONF_DIR is set correctly

  # 配置 Hive 环境
  sudo vim $SQOOP_HOME/conf/sqoop-env.sh

  export HIVE_CONF_DIR=$HIVE_HOME/conf
  export HADOOP_CLASSPATH=$HADOOP_CLASSPATH:$HIVE_HOME/lib/*

  # 软链 hive 配置文件
  sudo ln -s $HIVE_HOME/conf/hive-site.xml $SQOOP_HOME/conf/
```
