# NSCA 被动模式插件

- 版本 2.9.x

- 调用流程
  - 远程主机 send_nsca 插件 -> nagios 主机上的 NSCA daemon 服务

- 组成结构
  - send_nsca : 客户端插件, 安装在远程主机上, 用法发送指定格式的数据到 NSCA daemon 服务上
  - NSCA daemon : 运行在 Nagios 主机上的守护服务

- 需要安装 nagios


## 一、部署 NSCA daemon 服务

``` sh
1. 创建账号
# 创建 nagios 账号, 并且分配 nagios 到 nagios 组中
useradd nagios
usermod -a -G nagios nagios


2. 下载 NSCA(Nagios 主机)
# 下载
cd /usr/local/src
sudo wget https://nchc.dl.sourceforge.net/project/nagios/nsca-2.x/nsca-2.9.2/nsca-2.9.2.tar.gz
sudo tar -zxvf nsca-2.9.2.tar.gz

# 编译
cd nsca-2.9.2
./configure
make all

# 出现如下信息
NSCA port:  5667
NSCA user:  nagios
NSCA group: nagios

# 编译检查正确执行以后, 会出现如下信息
src/ 目录下生成两个程序 nsca(服务进程)  send_nsca(客户端)
sample-config/ 中会有 nsca.cfg 和 send_nsca.cfg 的配置文件


3. 部署 NSCA 和配置文件
# 部署 NSCA (send_nsca 和 nsca)
cp src/send_nsca src/nsca /usr/local/nagios/bin/
chown nagios:nagios /usr/local/nagios/bin/nsca /usr/local/nagios/bin/send_nsca

# 部署配置文件
mkdir -p /usr/local/nagios/etc/nsca
cp sample-config/* /usr/local/nagios/etc/nsca/
chown nagios:nagios /usr/local/nagios/etc/nsca/*


4. NSCA daemon 配置
# 修改配置文件: etc/nsca/nsca.cfg
vim etc/nsca/nsca.cfg

# 端口
server_port=5667

# 监听地址
server_address=0.0.0.0

# 开启 debug 模式, 用于调试。部署的时候调整到 debug=0
debug=1

# 命令文件, 这是守护进程的 Nagios 命令文件的位置
# 注意文件路径
command_file=/usr/local/nagios/var/rw/nagios.cmd

# 备用转储文件, 这是用来指定守护进程应该的另一个文件
# 注意文件路径
alternate_dump_file=/usr/local/nagios/var/rw/nsca.dump

# 聚合写选项
aggregate_writes=1

# password: 通讯密码, 与 send_nsca 客户端定义相同的密码, send_nsca 的配置文件在 etc/nsca/send_nsca.cfg
password=2345.com


5. NSCA daemon 启动服务
# 语法 bin/nsca --help
Usage: bin/nsca -c <config_file> [mode]
Options:
 <config_file> = Name of config file to use
 [mode]        = Determines how NSCA should run. Valid modes:
   --inetd     = Run as a service under inetd or xinetd
   --daemon    = Run as a standalone multi-process daemon
   --single    = Run as a standalone single-process daemon (default)

# 启动服务
/usr/local/nagios/bin/nsca -c /usr/local/nagios/etc/nsca/nsca.cfg --single

# 监控日志
tail -f /var/log/message

# 查看进程是否存在
ps -axu | grep nsca

# 查看端口是否存在
netstat -tunlp | grep 5667

```


## 二、 Nagios 与 NSCA daemon 服务打通

``` sh
1. Nagios 在 services 配置 nsca 脚本
# 修改配置文件
vim etc/objects/services.cfg

# 定义 service, 使用 check_dummy 插件, 接收 send_nsca 客户端发送过来的数据
define service{
        # 使用的模板
        use                     service_nsca_template

        # 定义用于接收 send_nsca 信息的主机名
        host_name               serverTest
        # 定义用于接收 send_nsca 信息的主机描述
        service_description     nscaTest
        # 定义 <脚本名称> check_dummy, 用于处理 send_nsca 发送来的数据
        # check_dummy: 这个脚本, 就是 commands.cfg 配置中定义的<脚本名称>
        check_command           check_dummy!0
        # 是否发送通知
        notifications_enabled   1

        contact_groups          MonitorGroup
}


2. Nagios 在 commands 配置 nsca <脚本名称>
# 修改配置文件
vim etc/objects/commands.cfg

# 定义 commands, 定义 <脚本名称> check_dummy, 处理 send_nsca 客户端发送过来的数据
# check_dummy: 插件是一个简单的翻译插件, 只能处理 4 个参数状态分别是 0,1,3,663, 对应了 (OK、WARNING、CRITICAL、UNKNOWN), 例如: check_dummy 0 对应就是 OK
define command{
        command_name    check_dummy
        command_line    $USER1$/check_dummy $ARG1$
}


3. 重启 nagios 服务
service nagios restart

```


## 二、send_nsca 远程主机部署

``` sh
# 语法 bin/send_nsca --help
Usage: bin/send_nsca -H <host_address> [-p port] [-to to_sec] [-d delim] [-c config_file]

Options:
 <host_address> = The IP address of the host running the NSCA daemon
 [port]         = The port on which the daemon is running - default is 5667
 [to_sec]       = Number of seconds before connection attempt times out.
                  (default timeout is 10 seconds)
 [delim]        = Delimiter to use when parsing input (defaults to a tab)
 [config_file]  = Name of config file to use

Service Checks:
 <host_name>[tab]<svc_description>[tab]<return_code>[tab]<plugin_output>[newline]

Host Checks:
 <host_name>[tab]<return_code>[tab]<plugin_output>[newline]


# 案例: 发送数据到 NSCA daemon 中, 注意其中的 serverTest,nscaTest 必须与 services.cfg 中定义的 service 相同, 才可接受到数据
echo -e "serverTest,nscaTest,0,test" | bin/send_nsca -H dw0 -p 5667 -c /usr/local/nagios/etc/nsca/send_nsca.cfg -d ","
```
