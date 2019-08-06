# tidb 安装文档

## 一、TiDB-Ansible 部署方案

- centos7.3 以上
- 中控机是用来控制整个 tidb 集群的


### 1. 中控机 - 安装系统依赖包

``` sh
# 在中控机上安装系统依赖包
sudo yum -y install epel-release git curl sshpass
sudo yum -y install python-pip
```


### 2. 中控机 - 创建 tidb 用户，并生成 ssh key

``` sh
# 配置免密, 所有主机
sudo useradd -m -d /home/tidb tidb
sudo usermod -a -G  wheel tidb

# 修改密码
sudo passwd tidb

# 登录 tidb 账号
sudo su tidb

# 创建 tidb 用户 ssh key， 提示 Enter passphrase 时直接回车即可。执行成功后，ssh 私钥文件为
ssh-keygen -t rsa
```


### 3. 中控机 - 下载 TiDB-Ansible 和安装依赖

``` sh
# 登录 tidb 用户
sudo su tidb

# 下载 2.1 版本
git clone -b tag-v3.0.1-v1 https://gitee.com/techonline/tidb-ansible.git

# 在中控机器上安装 Ansible 及其依赖
cd ~/app/tidb-ansible
sudo pip install -r ./requirements.txt

# 验证
ansible --version
    ansible 2.7.11
```


### 4. 中控机 - 配置部署机器 ssh 互信及 sudo 规则

``` sh
# 在中控机上配置部署机器, 写入需要部署的 主机
vi hosts.ini
[servers]
tidb-node1
tidb-node2
tidb-node3
tidb-node4
tidb-node5
tidb-node6

[all:vars]
username = tidb
ntp_server = pool.ntp.org


# ssh 免密配置

## 方法 1. 手动方式, 见系统环境
ssh-copy-id -i ~/.ssh/id_rsa.pub tidb-node1
ssh-copy-id -i ~/.ssh/id_rsa.pub tidb-node2
ssh-copy-id -i ~/.ssh/id_rsa.pub tidb-node3
......
ssh-copy-id -i ~/.ssh/id_rsa.pub tidb-nodeN

## 方法 2. 登录 tidb 服务器手动创建
vim ~/.ssh/authorized_keys
# 写入
~/.ssh/id_rsa.pub
# 修改权限
chmod 600 ~/.ssh/authorized_keys

## 验证是否免密
ssh tidb@[tidb-node1]
```


### 5. 中控机 - 在部署目标机安装 NTP 服务

``` sh
# 在中控机使用脚本在 目标机器上安装 NTP 服务
cd ~/tidb-ansible
ansible-playbook -i hosts.ini deploy_ntp.yml -u tidb -b

# 检测 ntp 是否开启
sudo systemctl status ntpd.service

# 开机启动
sudo systemctl enable ntpd.service

# 服务器重启
sudo systemctl restart ntpd.service


sudo systemctl stop ntpd.service
sudo ntpdate pool.ntp.org
sudo systemctl start ntpd.service

# 执行 ntpstat 命令，输出 synchronised to NTP server(正在与 NTP server 同步)表示在正常同步：
ntpstat
synchronised to NTP server (85.199.214.101) at stratum 2
   time correct to within 91 ms
   polling server every 1024 s

# 如需要修改 ntp 配置
/etc/ntp.conf
```


### 6. 中控机 - 在部署目标机配置 CPUfreq 调节器模式

- 为了让 CPU 发挥最大性能，请将 CPUfreq 调节器模式设置为 performance 模式
- 如果系统不支持则跳过此步骤

``` sh
# 1. 查看当前系统的 CPUfreq 调节器模式
cpupower frequency-info --policy

## 返回结果： 表示未设置
analyzing CPU 0:
  Unable to determine current polic


# 2. cpupower 命令查看系统支持的调节器模式
cpupower frequency-info --governors

## 返回结果如： Not Available 表示当前系统不支持配置 CPUfreq，跳过此步骤
analyzing CPU 0:
  available cpufreq governors: Not Available

## 返回结果如： performance powersave，表示支持这两种模式
analyzing CPU 0:
  available cpufreq governors: performance powersave


# 3. 设置 CPUfreq 调节器模式为 performance
cpupower frequency-set --governor performance


# 4. 若系统支持 performance 模式，则在中控机做批量设置
ansible -i hosts.ini all -m shell -a "cpupower frequency-set --governor performance" -u tidb -b
```


### 7. 部署目标机 - 添加数据盘 ext4 文件系统挂载参数

- [添加数据盘 ext4 文件系统挂载参数](https://www.pingcap.com/docs-cn/op-guide/ansible-deployment/)

``` sh

```


### 8. 中控机 - 分配机器资源，编辑 inventory.ini 文件

``` sh
cd ~/app/tidb-ansible
vim inventory.ini

## TiDB Cluster Part （这里必须写 ip 地址）
# tidb_servers
[tidb_servers]
tidb-node1 ansible_host=172.16.20.81
tidb-node2 ansible_host=172.16.20.82
tidb-node3 ansible_host=172.16.20.83
tidb-node4 ansible_host=172.16.20.97
tidb-node5 ansible_host=172.16.20.98
tidb-node6 ansible_host=172.16.20.99



# tikv_servers
[tikv_servers]
## 配置方法一： 单机单 TiKV 实例集群拓扑, 配置方法
tidb-node1 ansible_host=172.16.20.81
tidb-node2 ansible_host=172.16.20.82
tidb-node3 ansible_host=172.16.20.83

### 配置方法二: 单机多 TiKV 实例集群拓扑
# 详见(https://pingcap.com/docs-cn/v3.0/how-to/deploy/orchestrated/ansible/#%E5%8D%95%E6%9C%BA%E5%A4%9A-tikv-%E5%AE%9E%E4%BE%8B%E9%9B%86%E7%BE%A4%E6%8B%93%E6%89%91)
# 调优(https://pingcap.com/docs-cn/v3.0/reference/performance/tune-tikv/)

## 第一步: 单节点 tikv 进程数量
# 详见: raftstore 参数, 根据磁盘容量计算出, 一共需要多少个 tikv 数量
# 根据配置, 定义单台服务器, tikv 的数量为 5 个.
tidb-node1-1 ansible_host=172.16.20.81 deploy_dir=/opt/app/tidb/deploy1 tikv_port=20171 tikv_status_port=20181 labels="host=tikv1"
tidb-node1-2 ansible_host=172.16.20.81 deploy_dir=/opt/app/tidb/deploy2 tikv_port=20172 tikv_status_port=20182 labels="host=tikv1"
tidb-node1-3 ansible_host=172.16.20.81 deploy_dir=/opt/app/tidb/deploy3 tikv_port=20173 tikv_status_port=20183 labels="host=tikv1"

tidb-node2-1 ansible_host=172.16.20.82 deploy_dir=/opt/app/tidb/deploy1 tikv_port=20171 tikv_status_port=20181 labels="host=tikv2"
tidb-node2-2 ansible_host=172.16.20.82 deploy_dir=/opt/app/tidb/deploy2 tikv_port=20172 tikv_status_port=20182 labels="host=tikv2"
tidb-node2-3 ansible_host=172.16.20.82 deploy_dir=/opt/app/tidb/deploy3 tikv_port=20173 tikv_status_port=20183 labels="host=tikv2"

tidb-node3-1 ansible_host=172.16.20.83 deploy_dir=/opt/app/tidb/deploy1 tikv_port=20171 tikv_status_port=20181 labels="host=tikv3"
tidb-node3-2 ansible_host=172.16.20.83 deploy_dir=/opt/app/tidb/deploy2 tikv_port=20172 tikv_status_port=20182 labels="host=tikv3"
tidb-node3-3 ansible_host=172.16.20.83 deploy_dir=/opt/app/tidb/deploy3 tikv_port=20173 tikv_status_port=20183 labels="host=tikv3"


## 第二步: 配置内存和磁盘(修改 conf/tikv.yml 参数)
# titan 存储引擎
titan:
  enabled: true
  max-background-gc: 4

storage:
  ## RocksDB 使用 block cache 来缓存未压缩的数据块。较大的 block cache 可以加快读取速度。
  # 推荐开启 `shared block cache` 参数。这样只需要设置全部缓存大小，使配置过程更加方便。
  # 要在单个物理机上部署多个 TiKV 节点，需要显式配置该参数。否则，TiKV 中可能会出现 OOM 错误。
  # 推荐设置：常情况下应设置为系统全部内存的 30%-50%。
  block-cache:
    shared: true
    capacity: "50GB"

readpool:
  # 多实例情况下，需要修改 tidb-ansible/conf/tikv.yml 中 high-concurrency、normal-concurrency 和 low-concurrency 三个参数：
  # 推荐设置：TiKV 实例数量 * 参数值 = CPU 核心数量 * 0.8
  coprocessor:
    # Notice: if CPU_NUM > 8, default thread pool size for coprocessors
    # will be set to CPU_NUM * 0.8.
    # high-concurrency: 8
    # normal-concurrency: 8
    # low-concurrency: 8

raftstore:
  # 每个 TiKV 实例, 占用磁盘大小。 当如果多个 TiKV 实例部署在同一块物理磁盘上，需要修改 capacity 参数
  # 推荐配置：capacity = 磁盘总容量 / TiKV 实例数量, 如果没有设置，则使用磁盘容量。
  # capacity: "2048GB"


# pd_servers
[pd_servers]
tidb-node4 ansible_host=172.16.20.97
tidb-node5 ansible_host=172.16.20.98
tidb-node6 ansible_host=172.16.20.99

## Monitoring Part (prometheus)
# node_exporter and blackbox_exporter servers( 抽取日志服务器 )
[monitoring_servers]
tidb-node1 ansible_host=172.16.20.81
tidb-node2 ansible_host=172.16.20.82
tidb-node3 ansible_host=172.16.20.83
tidb-node4 ansible_host=172.16.20.97
tidb-node5 ansible_host=172.16.20.98
tidb-node6 ansible_host=172.16.20.99

[grafana_servers]
tidb-node5 ansible_host=172.16.20.98

# prometheus and pushgateway servers
[monitoring_servers]
tidb-node5 ansible_host=172.16.20.98

[alertmanager_servers]
tidb-node5 ansible_host=172.16.20.98
# 如果已经了则使用 alertmanager_host:alertmanager_port

## Group variables
[pd_servers:vars]
# location_labels = ["zone","rack","host"]
location_labels = ["host"]


# 1. 编辑部署配置文件
vim inventory.ini

  ## Global variables, 部署数据目录
  [all:vars]
  deploy_dir = /opt/app/tidb/deploy

  ## ssh via normal user
  ansible_user = tidb

  ## 集群名称
  cluster_name = tidb-cluster-1

  ## 集群版本
  tidb_version = v3.0.1


# 2. 检测
## 检测 tidb 用户 ssh 互信配置是否成功
ansible -i inventory.ini all -m shell -a 'whoami'

## 检测 tidb 用户 sudo 免密码配置成功
ansible -i inventory.ini all -m shell -a 'whoami' -b


# 3. 执行 local_prepare.yml playbook，联网下载 TiDB binary 到中控机
ansible-playbook local_prepare.yml -f 10


# 4. 初始化系统环境，修改内核参数
ansible-playbook bootstrap.yml


# 5. 部署 TiDB 集群软件
ansible-playbook deploy.yml


# 6. 启动 TiDB 集群
ansible-playbook start.yml
```


## tidb 错误处理

``` doc

1. NTP 检测关闭
错误内容
  TASK [check_system_dynamic : Preflight check - NTP service]

编辑配置
  vim roles/check_system_dynamic/tasks/main.yml

如下注释
#- name: Preflight check - Get NTP service status
#  shell: ntpstat | grep -w synchronised | wc -l
#  register: ntp_st
#  changed_when: false

#- name: Preflight check - NTP service
#  fail:
#    msg: "Make sure NTP service is running and ntpstat is synchronised to NTP server. See https://github.com/pingcap/docs/blob/master/op-guide/ansible-deployment.md#how-to-check-whether-the-ntp-service-is-normal ."
#  when:
#    - enable_ntpd
#    - ntp_st.stdout|int != 1


2. swap 内存检测关闭
编辑配置
  vim roles/check_system_dynamic/tasks/main.yml

#- name: Preflight check - Check swap
#  fail:
#    msg: "Swap is on, for best performance, turn swap off"
#  when: ansible_swaptotal_mb != 0


3. 如果是非 SSD 测试的话 ，最好将如下的内容注释掉 
编辑配置
  vim bootstrap.yml

如下注释
- name: tikv_servers machine benchmark
  hosts: tikv_servers
  gather_facts: false
  roles:
#    - { role: machine_benchmark, when: not dev_mode }
```
