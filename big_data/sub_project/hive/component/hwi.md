# HWI hive 简单客户端配置

- 文章 ： http://www.cognoschina.net/home/space.php?uid=58961&do=blog&id=47433

## 一、准备工作

- 安装 java 7 jdk，配置好 JAVA_HOME
- 保证 hive 可以正常访问


## 二、配置

### 1、由于 cdh 管理的版本没有 whi 包，所有从 hadoop hive 中下载

```
hive 下载页面
http://www.apache.org/dyn/closer.cgi/hive/

wget http://mirrors.hust.edu.cn/apache/hive/hive-0.13.1/apache-hive-0.13.1-src.tar.gz

tar -zxvf apache-hive-0.13.1-src.tar.gz

mv apache-hive-0.13.1-src /usr/local/

cd apache-hive-0.13.1-src/hwi/web

这里注意，你的 cdh 版本号. 打包成 war 文件
jar cvf  hive-hwi-0.13.1-cdh5.3.2.war -C ./

```

### 3、安装 java ant
```
下载地址
http://mirrors.hust.edu.cn/apache/ant/source/

1) 下载解压
wget http://mirrors.hust.edu.cn/apache/ant/source/apache-ant-1.9.4-src.tar.gz
tar -zxvf apache-ant-1.9.4-src.tar.gz
mv apache-ant-1.9.4 /usr/local/

2) 给 hwi.sh 配置 ant

查询 hwi.sh 目录位置
cdh 在这个目录下
/opt/cloudera/parcels/CDH/lib/hive/bin/ext/hwi.sh

vim hwi.sh 查看 ANT_LIB=/opt/ant/lib ，发现在这个目录下，做一份软链
sudo ln -s /usr/local/apache-ant-1.9.4/lib/* /opt/ant/lib/


```


### 4、把 java 的 tools.jar 放到 hive lib 中

- 具体看你当前 cdh 中 hive 的目录

```
ln -s /usr/local/jdk1.7.0_75/lib/tools.jar /opt/cloudera/parcels/CDH/lib/hive/lib/
```

### 5、启动
- 注意查看报错信息，如果找不到类，把 hive-hwi-0.13.1-cdh5.3.2.war 移动到报错的目录下

```
/opt/cloudera/parcels/CDH/bin/hive --service hwi

后台运行
nohup /opt/cloudera/parcels/CDH/bin/hive --service hwi
```

### 6、访问 http://xxx.xxx.xxx:9999/hwi/
