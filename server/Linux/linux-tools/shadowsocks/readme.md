# shadowsocks


## 一、安装

``` sh
Centos 7:

sudo yum -y install epel-release
sudo yum install python-pip
pip install --upgrade pip
pip install shadowsocks


Ubuntu:

apt-get install python-pip
pip install shadowsocks

```


## 二、使用

配置

``` json
单用户配置
{
    "server":"0.0.0.0",
    "server_port":10245,
    "local_address": "127.0.0.1",
    "local_port":1080,
    "password":"DWteam@2345.com",
    "timeout":30000,
    "method":"aes-256-cfb",
    "fast_open": false
}

多用户配置, 一个端口一个密码
{
    "server": "0.0.0.0",
    "port_password": {
        "8381": "foobar1",
        "8382": "foobar2",
        "8383": "foobar3",
        "8384": "foobar4"
    },
    "timeout": 300,
    "method": "aes-256-cfb"
}
```


服务

``` sh
# 前台服务启动
ssserver -c /etc/shadowsocks/shadowsocks.json

# 后台服务启动
sudo ssserver -c /etc/shadowsocks/shadowsocks.json -d start
sudo ssserver -c /etc/shadowsocks/shadowsocks.json -d stop

# 安装m2crypto可以加快一点加密速度
apt-get install python-m2crypto

# 查看日志
tail -f /var/log/shadowsocks.log
```
