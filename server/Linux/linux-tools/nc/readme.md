# NC 命令

## 一、安装


``` sh
1. 下载地址
  http://sourceforge.net/projects/netcat/files/netcat/
  下载 netcat-x.x.x.tar.gz 包

2. 安装
  ./configure -help --prefix=/path/netcat
  make
  make install

3. 测试
  /path/netcat/bin/nc -help

4.  yum install nc
```

## 二、使用

- [NC 文档](http://www.tuicool.com/articles/m67Z3m)
- [NC 文档](http://www.cnblogs.com/sunddenly/p/4031322.html?utm_source=tuicool&utm_medium=referral)

``` sh

nc -l [ip|host] [port]  		 // 测试一个端口连接

nc -l 192.168.160.49 10001   // Tcp 检测发送到这个端口的数据, 注意系统端口、注册端口、私有动态端口

nc -lk 192.168.160.49 10001   // 开启一个监听端口

echo -n "Hello World" | nc -4u -w1 127.0.0.1 10012			// 发送 UDP 数据到端口中



```
