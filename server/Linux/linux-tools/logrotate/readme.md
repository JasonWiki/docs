# Logrotate Linux 下的日志切分工具

## 一、介绍

- Logrotate 可以对日志按照大小、日期进行归档切分

- Logrotate 是基于 CRON 来运行的
  - 可通过 crontab 配置文件控制运行 /etc/crontab
  - 具体原理是 crontab 调度服务根据 /etc/crontab 中的配置文件 -> 运行 /etc/cron.daily、/etc/cron.weekly、/etc/cron.monthly 目录里的所有脚本
  - logrotate 脚本就在 /etc/cron.daily 目录中, logrotate 又会调度  /etc/logrotate.d/ 中的配置文件, 来对日志文件进行归档、切分等


## 二、安装

``` sh

Debian 或 Ubuntu 上
  apt-get install logrotate cron


Fedora，CentOS 或 RHEL 上
   yum install logrotate crontabs

```


## 三、配置运行


### 1. 配置说明

- /etc/logrotate.conf  主配置文件

- /etc/logrotate.d/    用户自定义配置文件, 一般我们把自己的配置放到这个目录下即可

``` sh

/var/log/log-file {

  monthly: 日志文件将按月轮循。其它可用值为‘daily’，‘weekly’或者‘yearly’。

  rotate 5: 一次将存储5个归档日志。对于第六个归档，时间最久的归档将被删除。

  size 1024M:  当日志文件到达指定的大小时才转储，Size 可以指定 bytes (缺省)以及KB (sizek)或者MB (sizem).

  compress: 在轮循任务完成后，已轮循的归档将使用gzip进行压缩。

  nocompress: 不需要压缩时，用这个参数

  delaycompress: 总是与compress选项一起用，delaycompress选项指示logrotate不要将最近的归档压缩，压缩将在下一次轮循周期进行。这在你或任何软件仍然需要读取最新归档时很有用。

  nodelaycompress 覆盖 delaycompress 选项，转储同时压缩。

  missingok: 在日志轮循期间，任何错误将被忽略，例如“文件无法找到”之类的错误。

  ifempty 即使是空文件也转储，这个是 logrotate 的缺省选项。

  notifempty: 如果日志文件为空，轮循不会进行，不转储。

  create 644 dwadmin dwadmin: 以指定的权限创建全新的日志文件，同时logrotate也会重命名原始日志文件。

  copytruncate 用于还在打开中的日志文件，把当前日志备份并截断

  nocopytruncate 备份日志文件但是不截断

  create  创建新的文件.因为日志被改名,因此要创建一个新的来继续存储之前的日志

  nocreate 不建立新的日志文件

  dateext  文件后缀是日期格式,也就是切割后文件是:xxx.log-20131216.gz 这样,如果注释掉,切割出来是按数字递增,即前面说的 xxx.log-1 这种格式

  errors address 专储时的错误信息发送到指定的Email 地址

  mail address 把转储的日志文件发送到指定的E-mail 地址

  nomail 转储时不发送日志文件

  olddir directory 转储后的日志文件放入指定的目录，必须和当前日志文件在同一个文件系统

  noolddir 转储后的日志文件和当前日志文件放在同一个目录下

  tabootext [+] list 让logrotate 不转储指定扩展名的文件，缺省的扩展名是：.rpm-orig, .rpmsave, v, 和 ~

  sharedscripts 表示 postrotate 脚本在压缩了日志之后只执行一次

  prerotate/endscript 在转储以前需要执行的命令可以放入这个对，这两个关键字必须单独成行

  postrotate/endscript 在转储以后需要执行的命令可以放入这个对，这两个关键字必须单独成行

  postrotate/endscript: 在所有其它指令完成后，postrotate和endscript里面指定的命令将被执行。在这种情况下，rsyslogd 进程将立即再次读取其配置并继续运行。

}
```


### 2. 案例

- logrotate -d -f /etc/logrotate.d/hive-server  调试模式手动执行

- logrotate -f /etc/logrotate.d/hive-server     手动执行

``` sh

# 配置文件存放路径
sudo vim /etc/logrotate.d/hive-server   

# 配置文件内容

/data/log/dwlogs/jetty_log/hive-server.out {

  # 按照日轮询
  daily

  # 保留 30 天
  rotate 30

  # 不压缩
  nocompress

  # 在日志轮循期间，任何错误将被忽略，例如“文件无法找到”之类的错误。
  missingok

  # 即使是空文件也转储
  ifempty

  # 转储后的日志文件和当前日志文件放在同一个目录下
  noolddir

  # 用于还在打开中的日志文件，把当前日志备份并截断
  copytruncate

  create 644 dwadmin dwadmin

  # 创建新的文件.因为日志被改名,因此要创建一个新的来继续存储之前的日志
  create

  # 按照日期切分
  dateext

  endscript

}

```


### 3. 系统 syslog 案例

``` sh
/var/log/cron
/var/log/maillog
/var/log/messages
/var/log/secure
/var/log/spooler
{
    # 表示 postrotate 脚本在压缩了日志之后只执行一次
    sharedscripts
    postrotate
        /bin/kill -HUP `cat /var/run/syslogd.pid 2> /dev/null` 2> /dev/null || true
    endscript
}
```
