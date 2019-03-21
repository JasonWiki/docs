# sysctl 系统资源配置

-  /etc/sysctl.conf

``` sh
sysctl -a 查看所有变量

sysctl -w vm.max_map_count=262144 临时生效
```

``` sh
net.ipv4.ip_forward = 0
net.ipv4.tcp_tw_reuse = 1
net.ipv4.tcp_tw_recycle = 0
net.ipv4.tcp_fin_timeout = 30
net.ipv4.tcp_keepalive_time = 1200
net.ipv4.tcp_syncookies = 1
net.ipv4.tcp_synack_retries = 3
net.ipv4.tcp_syn_retries = 3
net.ipv4.tcp_retrans_collapse = 0
net.ipv4.ip_local_port_range = 4000    65000
net.ipv4.tcp_max_syn_backlog = 8192
net.ipv4.tcp_max_tw_buckets = 50000
net.ipv4.tcp_timestamps = 0

# 定义了每个端口最大的监听队列的长度
net.core.somaxconn = 65535

# ES 推荐将此参数设置为 1，大幅降低 swap 分区的大小，强制最大程度的使用内存，注意，这里不要设置为 0, 这会很可能会造成 OOM
vm.swappiness = 1

# 限制一个进程可以拥有的 VMA(虚拟内存区域)的数量。虚拟内存区域是一个连续的虚拟地址空间区域。当VMA 的数量超过这个值，OOM
# Elasticsearch mmapfs 默认使用目录来存储其索引。map 计数的默认操作系统限制可能太低，这可能导致内存不足异常。
vm.max_map_count= 262144   

# 设置 Linux 内核分配的文件句柄的最大数量
fs.file-max = 518144                   
```
