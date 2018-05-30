# expect

## 简介

官方地址：http://expect.sourceforge.net/

代码托管：http://sourceforge.net/projects/expect/?source=navbar

expect 的功能是很强大的，实现了无须人与系统交互的功能，
比如 ssh 需要输入密码，使用此工具则可以在代码中配置


## 安装

* 需要先安装 Tcl,详细见 安装文档

``` sh
tar -zxvf expect5.45.tar

cd expect5.45

./configure --prefix=/usr/local/expect5.45 --with-tcl=/usr/local/tcl8.6.4/lib --with-tclinclude=/usr/local/tcl8.6.4/include

如：其他目录
./configure --prefix=/home/dwadmin/usr/expect5.45 --with-tcl=/home/dwadmin/usr/tcl8.6.4/lib --with-tclinclude=/home/dwadmin/usr/tcl8.6.4/include

make
make install

会在 tcl 的目录生成 expect
cd /usr/local/tcl8.6.4/bin/

sudo ln -s /usr/local/tcl8.6.4/bin/expect /usr/local/bin/expect
sudo ln -s /usr/local/tcl8.6.4/bin/tclsh8.6 /usr/local/bin/tclsh8.6
```


## 使用

- 文章 http://segmentfault.com/a/1190000002564816

### 1、ssh 登录案例

``` sh
1. 代码块
expect << EOF

#set timeout 5

spawn ssh hadoop@192.168.160.44 -p 22

expect "password" {send "angejia888\n"}

#expect  "Last login" {send " ifconfig |grep eth0 -A3\n"}

expect eof

exit

EOF


2. -c 带参数

/usr/bin/expect  -c '
  #set timeout 5

  spawn ssh hadoop@192.168.160.44 -p 22

  expect "password" {send "angejia888\n"}

  #expect  "Last login" {send " ifconfig |grep eth0 -A3\n"}

  interact

  expect eof
';



```

### 2、高级处理

#### 2.1、普通登录执行命令

``` sh
#!/bin/bash
auto_login_ssh_cmd () {
    ./expect  -c "
        set timeout -1;
        spawn -noecho ssh -o StrictHostKeyChecking=no $1 $3
        expect {
            *password:* {
                send -- $2\r;
            }

        }
        interact
    ";
}
auto_login_ssh_cmd hadoop@192.168.160.44 angejia888 "bash -i ls /tmp";
```

#### 2.2、传送文件

``` sh
#!/bin/bash
auto_login_scp_data () {

    fn_alsd_local_url=$1;
    fn_alsd_tager_rul=$2;
    fn_alsd_account=$3;
    fn_alsd_passwd=$4;

    expect  -c "
        set timeout -1;
        spawn -noecho scp -o StrictHostKeyChecking=no $fn_alsd_local_url $fn_alsd_account:$fn_alsd_tager_rul
        expect {
           *password:* {
               send -- $fn_alsd_passwd\r;
            }
         }
         interact
    ";
}
auto_login_scp_data  /tmp/aaaaa.txt  /tmp/bbb.txt hadoop@192.168.160.44 angejia888 ;
```
