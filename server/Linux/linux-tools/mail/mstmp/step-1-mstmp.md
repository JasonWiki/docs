# mstmp 邮件客户端

- 可以通过第三方的代理邮箱配置后，发送邮件的客户端

## 一、版本

最新版本
http://sourceforge.net/projects/msmtp/files/


其他所有版本:
http://sourceforge.net/p/msmtp/code/ci/master/tree/
http://sourceforge.net/projects/msmtp/files/msmtp/


## 二、安装

```
1) 下载编译
wget  http://sourceforge.net/projects/msmtp/files/latest/download?source=files

2) 解压
tar -xvf[-jxvf] msmtp-1.6.1.tar.xz

3) 编译
sudo apt-get install pkgconf
sudo apt-get install pkg-config


./configure prefix=/usr/local/msmtp-1.6.1
make
make install

```

## 三、配置

### 1、日志目录
```
1) 建立日志文件目录，以及日志文件
mkdir /var/log/msmtp
touch /var/log/msmtp/msmtp.log
chmod 777 /var/log/msmtp/msmtp.log

*** 注意要与配置文件 msmtprc 中的 logfile 参数相同 ***
```

### 2、配置文件 msmtprc
```
1) 建立配置文件
mkdir  /usr/local/msmtp-1.6.1/etc

#配置环境的账号
touch /usr/local/msmtp-1.6.1/etc/msmtprc

2) 配置本地的账号
touch ~/.msmtprc
chmod 600 ~/.msmtprc

```

#### 2.1、vim ~/.msmtprc 配置文件内容
(同样适用于环境账号)
```
defaults
logfile /var/log/msmtp/msmtp.log
syslog on

#[163]
account 163
host smtp.163.com
port 25
protocol smtp
auth login
tls off
#tls_nocertcheck
from angejia_monitoring@163.com
user angejia_monitoring@163.com
password angejia888

#set a default account 设置默认账号
account default : 163
```

### 3、运行
```
软链一份
ln -s /usr/local/msmtp-1.6.1/bin/msmtp /usr/local/bin/

*** 有些第三方的 stmp 服务会屏蔽邮箱 ***

/usr/local/msmtp-1.6.1/bin/msmtp jason@angejia.com -vvv

输入内容后，按control + d
```
