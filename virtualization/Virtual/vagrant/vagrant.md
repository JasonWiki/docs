# vagrant 管理虚拟机

### 文档地址
http://blog.smdcn.net/article/1308.html

### 坑
有些会虚拟成一个虚拟光驱，这是需要开启的时候按f2，来设置成Hard
硬盘启动，才可以读到

intel CPU 虚拟化在boot开启，否则各种坑

只能非 root 的用户普通权限才可以启动

### 命令行

vagrant up （启动虚拟机）

vagrant halt （关闭虚拟机——对应就是关机）

vagrant reload (重启)

vagrant suspend （暂停虚拟机——只是暂停，虚拟机内存等信息将以状态文件的方式保存在本地，可以执行恢复操作后继续使用）

vagrant resume （恢复虚拟机 —— 与前面的暂停相对应）

vagrant destroy （删除虚拟机，删除后在当前虚拟机所做进行的除开Vagrantfile中的配置都不会保留）
