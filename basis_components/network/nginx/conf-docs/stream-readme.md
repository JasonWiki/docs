# TCP/UDP 代理服务器功能

## * 核心配置

- ngx_stream_core_module stream 核心模块

``` sh
stream {
  # 指定预读缓冲区大小                   
  preread_buffer_size                 16k;

  # 指定预读阶段时间
  preread_timeout                     30s;

  # 读取 PROXY 协议头来完成. 如果在这段时间内没有发送完整的报头，则连接关闭
  proxy_protocol_timeout              30s;
  # 定义与代理服务器建立连接的超时时间
  #proxy_connect_timeout               15;
  #proxy_send_timeout                  20;
  #proxy_read_timeout                  20;
  #proxy_buffer_size                   256k;
  #proxy_buffers                       4 256k;
  #proxy_busy_buffers_size             512k;
  #proxy_temp_file_write_size          512k;

  # 将用于解析上游服务器名称的名称服务器配置为地址，例如
  # resolver                            resolver 127.0.0.1 [::1]:5353 valid=30s;
  # 设置名称解析超时时间
  # resolver_timeout                    5s;

  # 设置服务器的配置
  server {
    # 监听端口 或 UNIX sockets
    ## address:port [ssl] [udp] [proxy_protocol]
    ## unix:/var/run/nginx.sock
    listen address:port;
  }

  # 通过 引入配置加载 server 模块
  include /etc/nginx/conf.d/stream-*.conf;
}
```


## TCP/UDP 代理

- ngx_stream_proxy_module 模块

``` sh
# nginx.config 配置
stream {
  server {
      listen 127.0.0.1:12345;
      proxy_pass 127.0.0.1:8080;
  }

  server {
      listen 12345;
      # 定义与代理服务器建立连接的超时时间
      proxy_connect_timeout 1s;
      # 在客户端或代理服务器连接上,设置两次连续的读取或写入操作. 如果在这段时间内没有数据传输，则连接关闭.
      proxy_timeout 10m;
      proxy_pass example.com:12345;
  }

  server {
      listen 53 udp;
      # 如果使用UDP协议，设置期望从被代理服务器响应给客户端请求的数据报数量. 默认数据报数量是不限制的：响应数据报将一直发送直到proxy_timeout值过期
      proxy_responses 1;
      proxy_timeout 600s;
      proxy_pass dns.example.com:53;
  }

  server {
      listen [::1]:12345;
      proxy_pass unix:/tmp/stream.socket;
  }
}
```


## TCP/UDP 负载均衡

- ngx_stream_upstream_module 模块

``` sh
# 结构
stream {

  # 监控服务器域名对应的IP地址变化，自动修改上游配置，无需重新启动 nginx.
  # resolver  

  # 定义一组服务器. 服务器可以侦听不同的端口. 另外，侦听TCP和UNIX域套接字的服务器可以混合使用
  upstream server1_load_balance{

      # 组的配置和运行时被工作者进程之间共享状态中的共享存储器区. 语法: zone name [size];
        ## name  共享存储名
        ## [size] 共享粗存大小
      zone upstream_server1_load_balance 64k;


      # 服务器映射基于哈希key值的服务器组的负载平衡方法. 该key可以包含文本，变量，以及它们的组合（1.11.2）, 语法: hash key [consistent]
        ## 默认: 基于哈希 key 值的服务器组的负载平衡方法,
        ## consistent: 使用 ketama 一致哈希方法. 该方法可确保在向组中添加或删除服务器时，只有少数密钥将被重新映射到不同的服务器. 这有助于为高速缓存服务器实现更高的高速缓存命中率.
      hash $remote_addr consistent;


      # 配置服务主机列表, 语法: server address [parameters]
      ## address:
        ### unix:/tmp/backend3    套字节
        ### host:12345            ip 端口
      ## [parameters]:
        ### weight=1              服务器权重, weight 越大，负载的权重就越大
        ### max_conns=0           同时连接到代理服务器的最大数量（1.11.5）. 默认值是零，这意味着没有限制.
        ### max_fails=1           允许请求失败的次数(建立与服务器的连接时的错误或超时)，默认情不成功尝试的次数设置为 1. 零值将禁用尝试的计费
        ### fail_timeout=30s      max_fails 次失败后, 服务器不可用的时间段
        ### backup                将服务器标记为备份服务器. 主服务器不可用时，将连接到备份服务器
        ### down                  将服务器标记为永久不可用
        ### resolve               (需要上文 stream 中配置 resolver 才可使用). 监控服务器域名对应的IP地址变化，自动修改上游配置，无需重新启动 nginx. 服务器组必须驻留在共享内存中
        ### service=自定义服务名    解析DNS SRV 记录并设置服务name（1.9.13）. 为了使这个参数起作用，有必要指定服务器的解析参数，并指定一个没有端口号的主机名
        ### slow_start=0s         设置服务器权重从0恢复到标准值的时间
      server  host1:10000   weight=1 max_fails=2 fail_timeout=30s;
      server  host2:12345   resolve;
      server  host3         service=http;

      ## 备份服务器, 主服务器不可用时，将连接到备份服务器
      server  host4:12345   backup;
      server  host5:12345   backup;
  }
}


# 案例
stream {
    # 配置负载服务器
    upstream spark_thrift_server {
        hash $remote_addr consistent;

        # 配置负载的服务器
        server host1:10000 weight=1 max_fails=3 fail_timeout=30s;
        server host2:10000 weight=1 max_fails=3 fail_timeout=30s;
    }

    # 开启负载端口
    server {
        listen 10000;
        # 定义与代理服务器建立连接的超时时间
        proxy_connect_timeout 5s;
        # 在客户端或代理服务器连接上,设置两次连续的读取或写入操作. 如果在这段时间内没有数据传输，则连接关闭.
        proxy_timeout 10m;
        proxy_pass spark_thrift_server;
    }
}
```

## 心跳检测

- ngx_stream_upstream_hc_module (这个模块要收费)

``` sh
# 结构
stream {
    # 覆盖 运行 状况检查的 proxy_timeout 值.
    # health_check_timeout  5s;

    # 定义一组服务器. 服务器可以侦听不同的端口. 另外，侦听TCP和UNIX域套接字的服务器可以混合使用
    upstream service1 {
        zone upstream_tcp 64k;

        server backend1.example.com:12345 weight=5;
        server backend2.example.com:12345 fail_timeout=5s slow_start=30s;
        server 192.0.2.1:12345            max_fails=3;

        server backup1.example.com:12345  backup;
        server backup2.example.com:12345  backup;
    }

    # TCP
    server {
        listen     12346;
        proxy_pass service1;

        # 启用组中服务器的定期运行状况检查, 语法 health_check [parameters]
        ## [parameters]
          ### interval=5s     设置两次连续健康检查之间的时间间隔，默认为 5 秒
          ### fails=1         设置特定服务器连续失败的健康检查次数，在此之后，此服务器将被视为不健康，默认情况下为 1
          ### passes=1        设置特定服务器连续传递的健康状况检查的次数，在此之后服务器将被视为健康，默认情况下为 1
          ### udp|tcp         指定UDP协议应该用于运行状况检查而不是默认TCP协议
        health_check  interval=2s;
    }

    # UDP
    server {
        listen       53 udp;
        proxy_pass   service1;
        health_check udp;
    }

}


## 案例
stream {

  # 定义一组服务器
  upstream spark_thrift_server {
      zone upstream_spark_thrift_server 64k;

      # 负载均衡服务配置
      server master1:10000  weight=10  max_fails=1  fail_timeout=30s;
      server master2:10000  weight=1   max_fails=1  fail_timeout=30s;

      # 备份服务器
      server master2:10000  backup;
  }


  # 开启负载端口
  server {
      listen 10000;

      # 连接超时
      proxy_connect_timeout 5s;

      # 会话时间
      proxy_timeout 60m;

      # 负载代理
      proxy_pass spark_thrift_server;

      # 心跳检测
      health_check  tcp  interval=2s  fails=1  passes=1;
  }
}
```
