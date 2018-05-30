# HDFS 操作命令

## 一. 用户命令

- hdfs://cluster/
- hdfs://hostname:8020/

``` json
1) 列出文件列表
  hdfs dfs –ls [文件目录]
  hdfs dfs -ls /
  hdfs dfs -ls hdfs://ip:8020/user/

2) 打开某个文件
  hdfs dfs –cat [file_path]
  hdfs dfs -cat /user/hive/warehouse/jason_test.db/student/student.txt
  hdfs dfs -text adult/adult.txt.snappy | head  读取压缩文件

3) 将本地文件存储至 hdfs
  hdfs dfs –put [本地地址] [hdfs目录]
  hdfs dfs -put ./example.log /user/test/a.log

4) 将本地文件夹存储至 hdfs
  hdfs dfs –put [本地目录] [hdfs目录]
  hdfs dfs –put /home/t/dir_name /user/test/
  hdfs dfs -put /data/soj_log/aaa.log hdfs://ip:8020/user/test/

5) 下载 hdfs 文件到我本地
  hdfs dfs -get [文件目录] [本地目录]
  hdfs dfs –get /user/test/a.log ./

6) 删除 hdfs 上指定文件
  hdfs dfs –rm [文件地址]
  hdfs dfs –rm /user/test/a.log

7) 删除 hdfs 上指定文件夹（包含子目录等）
  hdfs dfs –rmr [目录地址]
  hdfs dfs –rmr /user/test

8) 在 hdfs 指定目录内创建新目录
  hdfs dfs –mkdir /user/test/aaa

9) 在 hdfs 指定目录下新建一个空文件
  hdfs dfs -touchz  /user/test/new.txt

10) 将 hdfs 上某个文件重命名
  hdfs dfs –mv [原目录地址] [新目录地址]
  hdfs dfs –mv /user/test/new.txt  /user/test/new1.txt

11) 将hdfs指定目录下所有内容保存为一个文件，同时下载至本地
  hdfs dfs –getmerge [原目录地址] [本地目录地址]
  hdfs dfs –getmerge /user/test/ ./

12) hdfs dfs -count -q /  

  hdfs dfs -count -q -h -v /

  QUOTA（命名空间的限制文件数）: 8.0 E
  REMAINING_QUATA(剩余的命名空间): 8.0 E
  SPACE_QUOTA(限制空间占用大小): none
  REMAINING_SPACE_QUOTA(剩余的物理空间): inf
  DIR_COUNT(目录数统计): 81.4 K
  FILE_COUNT(文件数统计): 272.6 K
  CONTENT_SIZE: 258.6 G
  FILE_NAME :  /

13) 查看目录使用情况
  hdfs dfs -du -s -h /

14) 获取配置
  hdfs getconf -confKey [key]  配置参数

15) 查看各个 DataNode 存储情况
  hdfs dfsadmin -report

16) balancer 存储平衡器
  设置负载的带宽 (字节), 500 MB
  hdfs dfsadmin -setBalancerBandwidth 524288000

  开始均衡 threshold 一般5， 即各个节点与集群总的存储使用率相差不超过10%，我们可将其设置为5%
  $HADOOP_HOME/sbin/start-balancer.sh -threshold 5

  cloudera 上运行 balancer:
    sudo -u hdfs hdfs balancer -threshold 5

17) 块和复本
  hdfs 块:
    hdfs fsck <目录>  <参数>

    hdfs fsck /  -files -blocks  查看文件系统中的文件由哪些块组成

    hdfs fsck /  检查块丢失情况

    hdfs fsck <目录>  -delete  对失败的块做删错操作，慎用

    hdfs fsck -list-corruptfileblocks   查看失败块信息
    hdfs fsck / | egrep -v '^\.+$' | grep -v eplica    查看失败块信息

  hdfs 副本:
    hdfs dfs -setrep -w 3 文件地址        对文件重新生成副本

    hdfs dfs -setrep -w 3 -R 目录地址     对目录重新生成副本

18) 归档 (减少小文件, 不能解决压缩格式问题)
  hadoop archive -archiveName [归档名.har] -p [需要归档的父目录] [归档的文件] [归档存放目录]

  归档文件: hadoop archive -archiveName archive.har -p /tmp/test 000000_* /tmp/test
  归档读取: hdfs dfs -ls har:/tmp/test/archive.har

19) hadoop checknative -a
  查看 native, 指的压缩算法

20) hadoop ha 选举时都是备用模式时使用
  hdfs zkfc -formatZK

21) 跨集群复制

  hdfs distcp hdfs://nn1:8020/foo/bar hdfs://nn2:8020/bar/foo

```


## 二. 挂载 hdfs 目录到本地，实现云存储到 hadoop hdfs

- [安装 JDK 包，下载地址](http://www.oracle.com/technetwork/java/javase/downloads/jdk7-downloads-1880260.html)

``` json
$(lsb_release -cs)  linux内核版本

1) 找到源
  (1) Ubuntu
  wget  http://archive.cloudera.com/cdh5/one-click-install/$(lsb_release -cs)/amd64/cdh5-repository_1.0_all.deb

  dpkg -i cdh5-repository_1.0_all.deb

  sudo apt-get update

  apt-get install hadoop-hdfs-fuse


  (2) Centos6
  yum 包源
  wget http://archive.cloudera.com/cdh5/redhat/6/x86_64/cdh/cloudera-cdh5.repo

  cp ./cloudera-cdh5.repo /etc/yum.repos.d/

  导入key
  rpm --import http://archive.cloudera.com/cdh5/redhat/6/x86_64/cdh/RPM-GPG-KEY-cloudera

  yum install hadoop-hdfs-fuse


2) 挂载
  hadoop-fuse-dfs dfs://<name_node_hostname>:<namenode_port> <mount_point>

  mkdir -p /data/hdfs/
  chown -R hdfs:hdfs /data/hdfs/

  hadoop-fuse-dfs dfs://192.168.160.45:8020/ /data/hdfs/

  umount /data/hdfs/ 卸载分区

```
