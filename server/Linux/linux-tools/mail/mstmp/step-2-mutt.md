# mutt 通过 mstmp 客户通发送邮件

## 说明
Mutt 是一个 Unix 的邮件程序，Mutt 管理的是 email

优秀文章
http://blog.163.com/a12333a_li/blog/static/87594285201212042332551/

## 一、版本
所有版本地址：
http://sourceforge.net/projects/mutt/files/mutt/

mutt-1.5.23 版本：
http://sourceforge.net/projects/mutt/files/mutt/mutt-1.5.23.tar.gz/download

## 二、安装

### 1、下载

```
1) 下载编译
wget  http://sourceforge.net/projects/mutt/files/mutt/mutt-1.5.23.tar.gz/download

2) 解压
tar -zxvf mutt-1.5.23.tar.gz

3) 编译
./configure prefix=/usr/local/mutt-1.5.23
make
make install

```

#### 1.1 *** 错误处理 ***
```
1) no curses library found
  RedHat:
    yum list|grep ncurses
    yum -y install ncurses-devel
    yum install ncurses-devel
    apt-cache search ncurses

  Ubuntu或Debian
    apt-cache search ncurses
    apt-get install libncurses5-dev

```

## 二、配置 .muttrc 文件

vim ~/.muttrc 写入如下内容(在执行的账号上加)
```
#发送服务器
set sendmail="/usr/local/msmtp-1.6.1/bin/msmtp"
set use_from=yes
#发送的邮箱账号，必须跟 msmtp 配置的账号一样
set from=jason@angejia.com
set envelope_from=yes

```


## 三、运行
文章
http://blog.163.com/a12333a_li/blog/static/87594285201212042332551/
```
1) 软链
ln -s /usr/local/mutt-1.5.23/bin/mutt /usr/bin/

2) shell 命令发送邮件
  a) 普通发送
  echo "内容内容内容" | /usr/local/mutt-1.5.23/bin/mutt -s '标题' jason@angejia.com

  b) 带抄送的
  echo "内容内容内容" | mutt -s '标题' jason@angejia.com -c ray@angejia.com,lvht@angejia.com;

  c) 抄送加附件
  echo "内容内容内容" | mutt -s '标题'  jason@angejia.com -c ray@angejia.com,lvht@angejia.com -a /etc/hosts

  d) HTML 类型
  echo "内容内容内容" | mutt -s '标题' -e 'set content_type="text/html"' jason@angejia.com -c ray@angejia.com,lvht@angejia.com

3) 其他发送
/usr/local/mutt-1.5.23/bin/mutt -s "测试" jason@angejia.com
```
