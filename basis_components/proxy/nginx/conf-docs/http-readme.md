# 基本的 HTTP 服务器功能

## http 核心

- ngx_http_core_module 核心模块

``` sh
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

```


### http 反向代理

- ngx_http_proxy_module 模块

``` sh
location / {
    proxy_pass       http://localhost:8000;
    proxy_set_header Host      $host;
    proxy_set_header X-Real-IP $remote_addr;
}
```


### http 负载均衡

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


## 其他 HTTP 服务器功能

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
