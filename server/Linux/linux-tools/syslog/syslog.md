# Syslog

## 一、介绍

### 1. syslog 组成

- 是系统的日志总管, 由两个进程组成

``` sh
klogd: 进程是记录系统运行的过程中内核生成的日志,而在系统启动的过程中内核初始化过程中 生成的信息记录到控制台(/dev/console）当系统启动完成之后会把此信息存放到/var/log/dmesg文件中,我可以通过cat /var/log/dmesg查看这个文件,也可以通过dmesg命令来查看

syslogd(ubuntu 是 rsyslogd): 进程是记录非内核以外的信息, 写入方式如下

  （1）Unix域套接字 /dev/log
  （2）UDP端口514
  （3）特殊的设备 /dev/klog（读取内核发出的消息）

```
