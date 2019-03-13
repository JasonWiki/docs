# Nginx 配置

- nginx.conf

## 基础配置

- ngx_core_module 核心

``` sh
# 配置用户和用户组
user nginx nginx;

# 工作进程数，建议设置为CPU的总核数
worker_processes  4;

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



## 变量表

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
