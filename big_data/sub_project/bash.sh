# HADOOP_HOME
# JAVA_HOME
export JAVA_HOME=/opt/app/jdk1.8.0_131
export JRE_HOME=${JAVA_HOME}/jre
export CLASSPATH=.:${JAVA_HOME}/lib:${JRE_HOME}/lib
export PATH=${JAVA_HOME}/bin:$PATH


# Hadoop
export HADOOP_HOME_WARN_SUPPRESS=true
export HADOOP_HOME=/usr/lib/hadoop
export HADOOP_CONF_DIR=$HADOOP_HOME/etc/hadoop
export YARN_CONF_DIR=$HADOOP_HOME/etc/hadoop

# LIBRARY
export JAVA_LIBRARY_PATH=$JAVA_LIBRARY_PATH:$HADOOP_HOME/lib/native
export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:$HADOOP_HOME/lib/native:/usr/lib64:/usr/local/cuda/lib64:/usr/local/cuda/lib


# HIVE_HOME
export HIVE_HOME=/usr/lib/hive
export HIVE_CONF_DIR=${HIVE_HOME}/conf
export HIVE_SKIP_SPARK_ASSEMBLY=true


# HBase
export HBASE_HOME=/usr/lib/hbase
export HBASE_CONF_DIR=$HBASE_HOME/conf


# Spark
export SPARK_HOME=/usr/local/spark
export SPARK_CONF_DIR=$SPARK_HOME/conf
#export SPARK_LIBRARY_PATH=$SPARK_LIBRARY_PATH:$HADOOP_HOME/lib/native
#export SPARK_CLASSPATH=$SPARK_CLASSPATH
export PATH=$SPARK_HOME/bin:$PATH


# MYSQL_HOME
export MYSQL_HOME=/opt/app/mysql57
export PATH=$MYSQL_HOME/bin:$PATH


# SBT
export SBT_HOME=/usr/local/sbt
export PATH=$SBT_HOME:$PATH

# MAVEN
export MAVEN_HOME=/usr/local/maven
export M2_HOME=$MAVEN_HOME
export M2_REPO=$MAVEN_HOME/repository
export PATH=$MAVEN_HOME/bin:$PATH


# TOMCAT_HOME
export TOMCAT_HOME=/usr/local/tomcat-8


# DW_SCHEDULER_HOME
export DW_SCHEDULER_HOME=~/app/dw_scheduler
# DW_SCHEDULER_AGENT_HOME
export DW_SCHEDULER_AGENT_HOME=${DW_SCHEDULER_HOME}/dw_scheduler_agent
# DW_SCHEDULER_WEB_HOME
export DW_SCHEDULER_WEB_HOME=${DW_SCHEDULER_HOME}/dw_scheduler_web
# DW_SCHEDULER_SOCKET_HOME
export DW_SCHEDULER_SOCKET_HOME=${DW_SCHEDULER_HOME}/dw_scheduler_socket


# DW_MONITOR_HOME
export DW_MONITOR_HOME=~/app/monitor


#DATAX_HOME
export DATAX_HOME=/home/hadoop/app/tools/datax
export PATH=${DATAX_HOME}/bin:$PATH


# DI_HOME
export KETTLE_HOME=/home/hadoop/app/tools/data-integration
export PATH=$KETTLE_HOME:$PATH


# NAGIOS_HOME
export NAGIOS_HOME=/usr/local/nagios


# NODEJS_HOME
export NODEJS_HOME=/usr/local/nodejs
# 配置环境变量
export PATH=${NODEJS_HOME}/bin:$PATH


# DW_HIVE_SERVER_HOME
export DW_HIVE_SERVER_HOME=~/app/dw_hive_server


# DW_GENERAL_LOADER_HOME
export DW_GENERAL_LOADER_HOME=~/app/dw_general_loader
