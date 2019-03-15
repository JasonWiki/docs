# nginx 安装

## 一、准备工作

### 1.关闭防火墙

``` sh
service iptables stop (关闭防火墙)（关闭selinux）
```

### 2.安装一些包

``` sh

yum -y install zlib-devel pcre-devel openssl-devel openssl

```

### 3.创建nginx执行的用户(evans),如果存在忽略

```
groupadd -r evans
useradd -g  evans evans
```


## 二、开始安装

### 1.移动nginx源码包到linux指定的用户编译源码目录

``` sh
mv nginx-1.6.0.tar.gz /usr/src/
cd /usr/src/
tar -zxvf nginx-1.6.0.tar.gz
cd nginx-1.6.0
```

### 2.编译

``` sh
./configure --prefix=/usr/local/nginx-1.6.0 --with-openssl=/usr/include/openssl --with-pcre --with-http_stub_status_module

make  
make install
```

#### 3.安装目录、已经启动项

``` sh
(安装成功在/usr/local/nginx-1.6.0 目录中)

#配置文件目录
/usr/local/nginx-1.6.0/conf/nginx.conf

#命令
/usr/local/nginx-1.6.0/sbin/nginx   启动

/usr/local/nginx-1.6.0/sbin/nginx -s stop  快速关闭

/usr/local/nginx-1.6.0/sbin/nginx -s quit  退出（关闭），这个会等待Nginx处理完当前请求

/usr/local/nginx-1.6.0/sbin/nginx -s reload  重新加载配置文件

/usr/local/nginx-1.6.0/sbin/nginx -s reopen 	重新打开日志文件。

#添加软链
ln -s /usr/local/nginx-1.6.0/sbin/nginx /usr/local/bin/

#配置文件目录软链
ln -s /usr/local/nginx-1.6.0/conf/ /etc/nginx
```

## 四、配置文件详解

``` sh
vim /usr/local/nginx-1.6.0/conf/nginx.conf

1、修改配置文件

#配置用户和用户组
user evans evans;

#工作进程数，建议设置为CPU的总核数
worker_processes  2;

#全局错误日志定义类型，日志等级从低到高依次为： debug | info | notice | warn | error | crit
error_log  logs/error.log  info;

#记录主进程ID的文件(不要动)
#pid        /usr/local/nginx-1.6.0/nginx.pid;


#一个进程能打开的文件描述符最大值，理论上该值因该是最多能打开的文件数除以进程数。但是由于nginx负载并不是完全均衡的，
#所以这个值最好等于最多能打开的文件数。执行 sysctl -a | grep fs.file 可以看到linux文件描述符。
worker_rlimit_nofile 65535;


#工作模式与连接数上限
events {
    #uname -a 工作模式，linux2.6版本以上用epoll
    use epoll;
    #单个进程允许的最大连接数
    worker_connections  65535;
}


2、负载均衡和反向代理设置

#设定http服务器，利用它的反向代理功能提供负载均衡支持
http {
    #文件扩展名与文件类型映射表
    include       mime.types;

    #最大上传
    client_max_body_size 20m;

    #默认文件类型
    default_type  application/octet-stream;

    #日志格式
    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                                   '$status $body_bytes_sent "$http_referer" '
                                   '"$http_user_agent" "$http_x_forwarded_for"';

    #access log 记录了哪些用户，哪些页面以及用户浏览器、ip和其他的访问信息
    access_log  logs/access.log  main;

    #服务器名字的hash表大小
    server_names_hash_bucket_size 128;

    #客户端请求头缓冲大小。nginx默认会用client_header_buffer_size这个buffer来读取header值，
    #如果header过大，它会使用large_client_header_buffers来读取。
    #如果设置过小HTTP头/Cookie过大 会报400 错误 nginx 400 bad request
    #如果超过buffer，就会报HTTP 414错误(URI Too Long)
    #nginx接受最长的HTTP头部大小必须比其中一个buffer大，否则就会报400的HTTP错误(Bad Request)。
    client_header_buffer_size 32k;
    large_client_header_buffers 4 32k;

    #客户端请求体的大小
    client_body_buffer_size    8m;

    #隐藏ngnix版本号
    server_tokens off;

    #忽略不合法的请求头
    ignore_invalid_headers   on;

    #指定启用除第一条error_page指令以外其他的error_page。
    recursive_error_pages    on;

    #让 nginx 在处理自己内部重定向时不默认使用  server_name 设置中的第一个域名
    server_name_in_redirect off;

    #开启文件传输，一般应用都应设置为on；若是有下载的应用，则可以设置成off来平衡网络I/O和磁盘的I/O来降低系统负载
    sendfile                 on;

    #告诉nginx在一个数据包里发送所有头文件，而不一个接一个的发送。
    tcp_nopush     on;

    #告诉nginx不要缓存数据，而是一段一段的发送--当需要及时发送数据时，就应该给应用设置这个属性，
    #这样发送一小块数据信息时就不能立即得到返回值。
    tcp_nodelay    on;

    #长连接超时时间，单位是秒
    keepalive_timeout  65;

    #gzip模块设置，使用 gzip 压缩可以降低网站带宽消耗，同时提升访问速度。
    gzip  on;             #开启gzip
    gzip_min_length  1k;          #最小压缩大小
    gzip_buffers     4 16k;        #压缩缓冲区
    gzip_http_version 1.0;       #压缩版本
    gzip_comp_level 2;            #压缩等级
    gzip_types       text/plain application/x-javascript text/css application/xml;           #压缩类型

    #upstream作负载均衡，在此配置需要轮询的服务器地址和端口号，max_fails为允许请求失败的次数，默认为1.
    #weight为轮询权重，根据不同的权重分配可以用来平衡服务器的访问率。
    upstream hostname {
        server 192.168.2.149:8080 max_fails=0 weight=1;
        server 192.168.1.9:8080 max_fails=0 weight=1;
	}

	#如果有拓展文件则添加 mkdir -p    /usr/local/nginx-1.6.0/conf/conf.d
	include conf.d/*.conf;	 (放在跟nginx 的conf目录中)



	#三、主机配置模板
    server {
        #监听端口
        listen       80;

        #域名
        server_name  hostname;

        #字符集
        charset utf-8;

        #单独的access_log文件
        access_log  logs/192.168.2.149.access.log  main;

        #反向代理配置，将所有请求为http://hostname的请求全部转发到upstream中定义的目标服务器中。
        location / {
            #此处配置的域名必须与upstream的域名一致，才能转发。
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



	#php 可以直接用的demo
	server {
		listen 80;
		#网站别名
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

			fastcgi_pass 127.0.0.1:9000;
			#fastcgi_pass unix:/usr/local/php5.5/var/run/php-fpm.pid;

		}
	}


  #php 直接使用的动态主机
        server {
                listen 80;
                #网站别名
                #access_log /data/logs/nginx/test.ttlsa.com.access.log main;

                #默认文件
                index index.php index.html index.html;

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
                        #fastcgi_param SCRIPT_FILENAME /home/hadoop/app/uba/scripts/service/uba.php;


                        fastcgi_pass 127.0.0.1:9000;
                        #fastcgi_pass unix:/usr/local/php5.5/var/run/php-fpm.pid;
                        #fastcgi_pass unix:/var/run/php5-fpm.sock;

                }
        }



    # nginx 转发到独立的 php 服务器 (待测试)
    upstream php-backend {
        server app0:9000;
        server app1:9000;
        server app2:9000;

    }
    location @php {

        include        fastcgi_params;
        # 重写不会改变$document_uri，
        # 我们需要重写REQUEST_URI变量，否则laravel无法路由请求
        fastcgi_param REQUEST_URI     $document_uri?$query_string;
        # 此处可以读取之前设置的$app_root变量，却无法动态设置root
        fastcgi_param SCRIPT_FILENAME $app_root/index.php;

        fastcgi_pass php-backend;
    }




}




#详细说明
query_string = s=/Admin/User/personal.html
request_uri = /Admin/User/personal.html


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
$http_via	|	最后一个访问服务器的Ip地址。
$http_x_forwarded_for	|	相当于网络访问路径。
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
$uri	|	请求的URI，可能和最初的值有不同，比如经过重定向之类的。
```
