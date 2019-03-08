# Nginx 配置

## 一. 基础配置

### 1. nginx.conf

- ngx_core_module 核心

``` sh
# 配置用户和用户组
user nginx nginx;

# 工作进程数，建议设置为CPU的总核数
worker_processes  2;

# 全局错误日志定义类型，日志等级从低到高依次为： debug | info | notice | warn | error | crit
error_log  logs/error.log  info;

# 记录主进程ID的文件(不要动)
#pid        nginx.pid;

# 一个进程能打开的文件描述符最大值，理论上该值因该是最多能打开的文件数除以进程数. 但是由于nginx负载并不是完全均衡的，
# 所以这个值最好等于最多能打开的文件数. 执行 sysctl -a | grep fs.file 可以看到linux文件描述符.
worker_rlimit_nofile 65535;

# 工作模式与连接数上限
events {
    # uname -a 工作模式，linux2.6版本以上用 epoll
    use epoll;
    # 单个进程允许的最大连接数
    worker_connections  65535;
}

```

### 2. 变量表

``` sh
$args	|	请求中的参数;
$binary_remote_addr	|	远程地址的二进制表示
$body_bytes_sent	|	已发送的消息体字节数
$content_length	|	HTTP请求信息里的"Content-Length";
$content_type	|	请求信息里的"Content-Type";
$document_root	|	针对当前请求的根路径设置值;
$document_uri	|	与$uri相同;
$host	|	请求信息中的"Host"，如果请求中没有Host行，则等于设置的服务器名;
$hostname	|
$http_cookie	|	cookie 信息
$http_post	|
$http_referer	|	引用地址
$http_user_agent	|	客户端代理信息
$http_via	|	最后一个访问服务器的Ip地址.
$http_x_forwarded_for	|	相当于网络访问路径.
$is_args	|
$limit_rate	|	对连接速率的限制;
$nginx_version	|
$pid	|
$query_string	|	与$args相同;
$realpath_root	|
$remote_addr	|	客户端地址;
$remote_port	|	客户端端口号;
$remote_user	|	客户端用户名，认证用;
$request	|	用户请求
$request_body	|
$request_body_file	|	发往后端的本地文件名称
$request_completion	|
$request_filename	|	当前请求的文件路径名
$request_method	|	请求的方法，比如"GET"、"POST"等;
$request_uri	|	请求的URI，带参数;
$scheme	|	所用的协议，比如http或者是https，比如rewrite^(.+)$$scheme://example.com$1redirect;
$sent_http_cache_control	|
$sent_http_connection	|
$sent_http_content_length	|
$sent_http_content_type	|
$sent_http_keep_alive	|
$sent_http_last_modified	|
$sent_http_location	|
$sent_http_transfer_encoding	|
$server_addr	|	服务器地址，如果没有用listen指明服务器地址，使用这个变量将发起一次系统调用以取得地址(造成资源浪费);
$server_name	|	请求到达的服务器名;
$server_port	|	请求到达的服务器端口号;
$server_protocol	|	请求的协议版本，"HTTP/1.0"或"HTTP/1.1";
$uri	|	请求的URI，可能和最初的值有不同，比如经过重定向之类的.
```

## 二. 基本的 HTTP 服务器功能

### * http 核心

- ngx_http_core_module 核心模块

``` sh
##### HTTP 代理配置 Start #####

http {
    # 文件扩展名与文件类型映射表
    include       /etc/nginx/mime.types;

    # 最大上传
    client_max_body_size 20m;

    # 默认文件类型
    default_type  application/octet-stream;

    # 日志格式
    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                                   '$status $body_bytes_sent "$http_referer" '
                                   '"$http_user_agent" "$http_x_forwarded_for"';

    # access log 记录了哪些用户，哪些页面以及用户浏览器、ip和其他的访问信息
    access_log  logs/access.log  main;

    # 服务器名字的hash表大小
    server_names_hash_bucket_size 128;

    # 客户端请求头缓冲大小. nginx默认会用client_header_buffer_size 这个 buffer 来读取 header 值
    # 如果header过大，它会使用large_client_header_buffers来读取.
    # 如果设置过小HTTP头/Cookie过大 会报400 错误 nginx 400 bad request
    # 如果超过buffer，就会报HTTP 414错误(URI Too Long)
    # nginx接受最长的HTTP头部大小必须比其中一个buffer大，否则就会报400的HTTP错误(Bad Request).
    client_header_buffer_size 32k;
    large_client_header_buffers 4 32k;

    # 客户端请求体的大小
    client_body_buffer_size    8m;

    # 隐藏 ngnix 版本号
    server_tokens off;

    # 忽略不合法的请求头
    ignore_invalid_headers   on;

    # 指定启用除第一条error_page指令以外其他的error_page.
    recursive_error_pages    on;

    # 让 nginx 在处理自己内部重定向时不默认使用  server_name 设置中的第一个域名
    server_name_in_redirect off;

    # 开启文件传输，一般应用都应设置为on；若是有下载的应用，则可以设置成off来平衡网络I/O和磁盘的I/O来降低系统负载
    sendfile                 on;

    # 告诉nginx在一个数据包里发送所有头文件，而不一个接一个的发送.
    tcp_nopush     on;

    # 告诉nginx不要缓存数据，而是一段一段的发送--当需要及时发送数据时，就应该给应用设置这个属性，
    # 这样发送一小块数据信息时就不能立即得到返回值.
    tcp_nodelay    on;

    # 长连接超时时间，单位是秒
    keepalive_timeout  30;

    # gzip模块设置，使用 gzip 压缩可以降低网站带宽消耗，同时提升访问速度.
    gzip  on;                      # 开启gzip
    gzip_min_length  1k;           # 最小压缩大小
    gzip_buffers     4 16k;        # 压缩缓冲区
    gzip_http_version 1.0;         # 压缩版本
    gzip_comp_level 2;             # 压缩等级
    gzip_types       text/plain application/x-javascript text/css application/xml;           #压缩类型

	  # 导入配置
	  include /etc/nginx/conf.d/http-*.conf;
}

##### HTTP 代理配置 End #####
```

### 1. http 反向代理

- ngx_http_proxy_module 模块

``` sh
location / {
    proxy_pass       http://localhost:8000;
    proxy_set_header Host      $host;
    proxy_set_header X-Real-IP $remote_addr;
}
```

### 2. http 负载均衡

- ngx_http_upstream_module 模块

``` sh
http {
  # 负载均衡配置
  ## upstream: 作负载均衡，在此配置需要轮询的服务器地址和端口号
  ## load_balance_name: 负载均衡内部名称
  ## server: 实际地址
  ## max_fails: 为允许请求失败的次数，默认为1.
  ## weight: 为轮询权重，根据不同的权重分配可以用来平衡服务器的访问率.
  upstream load_balance_name {
      server 192.168.2.149:8080 max_fails=0 weight=1;
      server 192.168.1.9:8080 max_fails=0 weight=1;
  }
}

# conf.d/http-*.conf 指定文件配置
server {
    location / {
        # 此处配置的域名必须与 load_balance_name 的域名一致，才能转发.
        proxy_pass     http://load_balance_name;
        proxy_set_header   X-Real-IP $remote_addr;
    }
}
```

## 三. 其他 HTTP 服务器功能

### 1. server 虚拟机服务器 - 模板

- ngx_http_core_module 模块

```sh
server {
    # 监听端口
    listen       80;
    # 域名
    server_name  hostname1 hostname2;
    #字符集
    charset utf-8;
    # 单独的 access_log 文件
    access_log  logs/192.168.2.149.access.log  main;

    # 反向代理配置，将所有请求为 http://hostname 的请求全部转发到 upstream 中定义的目标服务器中.
    location / {
        # 此处配置的域名必须与 upstream 的域名一致，才能转发.
        proxy_pass     http://hostname;
        proxy_set_header   X-Real-IP $remote_addr;
    }

    #启用nginx status 监听页面
    location /nginxstatus {
        stub_status on;
        access_log on;
    }

    #错误页面
    error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   html;
    }
}
```

#### 1.1 server 虚拟机服务器 - 代理

``` sh

# conf.d/http-*.conf
server {
  listen 80;
  server_name dw.corp.angejia.com;

  location /monitor {
       proxy_pass http://bi1:9080;
       proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
  }

  #node js socket.io
  location /socket.io {
       proxy_pass http://bi3:8000;
       proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
       proxy_http_version 1.1;
       proxy_set_header Upgrade $http_upgrade;
       proxy_set_header Connection "Upgrade";
   }
}

```


#### 1.2 server 虚拟机服务器 - PHP 集成

``` sh
# php 可以直接用的demo
server {
  listen 80;
  # 网站别名
  server_name demo.box.cn;
  #access_log /data/logs/nginx/test.ttlsa.com.access.log main;

  #默认文件
  index index.php index.html;

  #网站跟目录
  root /web/www/demo;

  location / {
    try_files $uri $uri/ /index.php?$args;
  }

  location ~ .*\.(php)?$ {
    expires -1s;
    try_files $uri =404;
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    include fastcgi_params;
    fastcgi_param PATH_INFO $fastcgi_path_info;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

    # 设置 php-fpm 的端口地址
    fastcgi_pass 127.0.0.1:9000;
    #fastcgi_pass unix:/usr/local/php5.5/var/run/php-fpm.pid;

  }
}


# php 直接使用的动态主机
server {
    listen 80;
    #access_log /data/logs/nginx/test.ttlsa.com.access.log main;

    #默认文件
    index index.php index.html index.html
    #网站跟目录
    server_name ~^(?<app>.+)\.hadoop\.box\.com;
    root /web/www/$app;

    location / {
            try_files $uri $uri/ /index.php?$args;
            #try_files $uri $uri/ /public/index.php?$query_string;
            #try_files $uri $uri/ /index.php?s=$request_uri;
    }

    location ~ .*\.(php)?$ {
                  expires -1s;
            try_files $uri =404;
                  fastcgi_split_path_info ^(.+\.php)(/.+)$;
            include fastcgi_params;
                  fastcgi_param PATH_INFO $fastcgi_path_info;
            fastcgi_index index.php;
                  fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            #fastcgi_param SCRIPT_FILENAME /home/hadoop/app/uba/scripts/service/uba.ph
            fastcgi_pass 127.0.0.1:9000;
            #fastcgi_pass unix:/usr/local/php5.5/var/run/php-fpm.pid;
            #fastcgi_pass unix:/var/run/php5-fpm.sock
    }
}
```


## 三. 邮件代理功能

### * 核心模块

- ngx_mail_core_module 模块

``` sh

##### Mail 代理配置 Start #####

mail {
  include /etc/nginx/conf.d/mail-*.conf;
}

##### Mail 代理配置 End #####

```


## 四. TCP/UDP 代理服务器功能

### * 核心

- ngx_stream_core_module stream 核心模块

``` sh
##### Stream 代理配置 Start #####

stream {

  # 指定预读缓冲区大小
  ## preread_buffer_size                        
  preread_buffer_size                 16k;

  # 指定预读阶段时间
  preread_timeout                     30s;

  # 读取 PROXY 协议头来完成. 如果在这段时间内没有发送完整的报头，则连接关闭
  proxy_protocol_timeout              30s;

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


  include /etc/nginx/conf.d/stream-*.conf;
}

##### Stream 代理配置 End #####
```

### 1. TCP/UDP 代理

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

### 2. TCP/UDP 负载均衡

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

### 3. 心跳检测

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
