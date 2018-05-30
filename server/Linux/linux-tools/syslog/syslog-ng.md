# Syslog-Ng

## 一、介绍

- Syslog-Ng 是 Syslog 的升级版本, 支持自定义数据源, 过滤, 灵活配置发送源, 和日志链的能力

## 二、安装

``` sh
安装
 sudo apt_get install syslog_ng_core

重启
 sudo /etc/init.d/syslog_ng restart

配置文件
  /etc/syslog-ng/syslog-ng.conf 系统配置

  /etc/syslog-ng/conf.d/myconf.conf   自定义配置目录

```


## 三、配置

- source(日志源)->filter(过滤)->destination(目标)->log(日志链)

### * 核心 syslog-ng.conf

``` sh

# 全局配置
options {
    chain_hostnames(off);
    keep_hostname(yes);
    flush_lines(0);
    use_dns(no);
    use_fqdn(no);
    owner("root");
    group("adm");
    perm(0640);
    stats_freq(0);
    bad_hostname("^gconfd$");
    # 目前实测下来只能是这么大
    log_msg_size(65535);
};

# 数据源
source s_src {
    system();
    internal();
};

# 表示 syslog-ng 服务开启的端口
source s_net { udp(ip(0.0.0.0) port(1000)); };

```

### 1. options 全局选项

``` sh

options {
  option1(params);
  option2(params);
};  


全局参数说明:
   bad_hostname() [default no]
   //值：正则表达式
   //可通过正规表达式指定某主机的信息不被接受

   chain_hostnames() [default no]
   //值：yes | no
   //是否打开主机名链功能，打开后可在多网络段转发日志时有效

   check_hostname() [default no]
   //值：yes | no
   //启用或禁止检查主机名包含有效的字符串

   create_dirs() [default no]
   //值: yes | no
   //当指定的目标目录不存在时，是否创建该目录

   custom_domain() default[empty string]
   //值：string
   //使用这个选项指定一个自定义的域名后附加短主机名接收FQDN。这个选项会影响每一个输出消息:事件日志消息,文件来源,MARK的消息和syslog_ng OSE的内部消息。

   dir_group() default[root]
   //值：groupid
   //指定新创建目录的默认组

   dir_owner() default[root]
   //值：userid
   //指定新创建目录的默认用户

   dir_perm() default[0700]
   //值：permission value
   //指定目录的权限，使用此方式标注，例如0700

   dns_cache() default[yes]
   //值：yes | no
   //启用或禁止DNS缓存

   dns_cache_expire() default[3600]
   //值：number
   //指定DNS查询缓存过期时间

   dns_cache_expire_failed() default[60]
   //值：number
   //指定失败的DNS缓存过期时间

   dns_cache_hosts() default[unset]
   //值：filename
   //指定文件在/etc/hosts，包含静态的IP_>HOSTNAME的映射关系，使用此选项解析主机名而非DNS。

   dns_cache_size() default[1007]
   //值：number
   //指定DNS缓存主机名的数量

   file_template() defalut[local timezone]
   //值：时间偏移量 (如: +03:00)
   //指定一个默认模板,类似文件的目的地使用。例如:
   template t_isostamp { template("$ISODATE $HOST $MSGHDR$MSG\n"); };
   options { file_template(t_isostamp); };

   flush_lines() default[100]
   //值：number //指定有多少行文件一次刷新到目的地，syslog_ng应用程序等待这行数积累并发送它们在单个批处理。增加这个数量增加吞吐量随着越来越多的消息被发送在单个批处理,但是也增加了信息延迟。限制延迟调整flush_timeout()选项。默认情况下,syslog_ng所等待100行。syslog_ng不会将消息发送到目的地,直到flush_timeout()时间的流逝(默认10秒)。如果你停止或重新加载syslog_ng OSE的网络资源连接

   flush_timeout() default[10000]
   //值：时间以毫秒为单位
   //指定时间syslog_ng等待行积累的输出缓冲区。有关更多信息,查看flush_lines()选项。

   frac_digits() default[10000]
   //值：number  //syslog_ng应用程序可以存储的第二个分数显示时间戳ISO8601格式。frac_digits()参数指定数字存储的数量。数字存储分数由零填充如果原始消息的时间戳指定几秒钟。分数可以总是被存储接收到的消息的时间。注意,syslog_ng可以添加non_ISO8601的分数时间戳。

   group() default[root]
   //值：groupid
   //输出文件的默认组。默认情况下,syslog_ng改变访问文件的权限(例如/dev/null)。0600根。禁用修改权限,使用这个选项_1

   keep_hostname() default[no]
   //值：yes or no
   //启用或禁止主机名重写

   keep_timestamp() default[yes]
   //值：yes | no
   //指定syslog_ng是否应该接受发送应用程序收到的时间戳或客户端。如果禁用,接收的时候使用，这个选项可以指定全局每个源。源的地方设置将覆盖全局选项如果可用。

   log_fifo_size() default[10000]
   //值：number
   //指定输出消息队列的值

   log_msg_size() default[8192]
   //值：number
   //消息的最大长度字节。这个长度包括整个消息(数据结构和单个字段)。可以设置的最大价值是268435456字节(256 mb)。信息使用IETF_syslog消息格式(RFC5424),一个SDATA字段的值的最大大小为64 kb。

   mark() default[1200]
   //值：number
   //mark_freq()方法是一个别名弃用mark()选项。这是保留兼容1.6.x syslog_ng版本。

   mark_freq() default[1200]
   //值：number[秒]
   //

   mark_mode()
   //值：file(), pipe(), unix_stream(), unix_dgram(), program()
   //mark_mode()选项可以设置以下目的驱动:file(), program(),unix_dgram(), unix_stream(), udp(), udp6(), tcp(), tcp6(), pipe(), syslog()在全局选项

   normalize_hostnames() default[no]
   //值：yes | no
   //启用此选项，syslog_ng 转换主机名为小写

   on_error() default[drop_message]
   //值：drop_message|drop_property|fallback_to_string|silently_drop_message|silently_drop_property|silently_fallback_to_string
   //控制类型转换失败时将会发生什么,syslog_ng大阪证交所不能将一些数据转换成指定的类型。
   默认情况下,syslog_ng丢弃整个消息和打印错误日志。目前,value_pairs()方法使用的设置中的on_error()

   owner() default[root]
   //值：userid
   //默认输出文件的所有者。默认情况下,syslog_ng改变访问文件的权限(例如/ dev/null)。0600根。禁用修改权限,使用这个选项_1

   perm() default[0600]
   //值：permission value
   //默认输出文件的权限。默认情况下,syslog_ng改变访问文件的权限(例如/ dev/null)。0600根。禁用修改权限,使用这个选项_1

   proto_template() default[默认使用协议的消息格式]
   //值：模板名
   //指定一个模板,协议(比如目的地(例如,network()和syslog())例如:
   recv_time_zone()
   template t_isostamp { template("$ISODATE $HOST $MSGHDR$MSG\n"); };
   options { proto_template(t_isostamp); };

   recv_time_zone() default[local timezone]
   //值：时区名称,或时区偏移
   //指定接收消息的时区

   send_time_zone() default[local timezone]
   //值：时区名称,或时区偏移
   //指定发送消息的时区

   stats_freq() default[600]
   //值：number
   //指定两个数据之间的时间信息在几秒钟内。统计数据由syslog_ng发送日志消息,包含统计数据日志消息。设置为0禁用统计信息。

   stats_level() default[0]
   //值：0123
   //指定数据的细节syslog_ng收集处理信息。
     0级只收集统计信息的来源和目的地
     1级包含不同的连接和日志文件的详细信息,但有一个轻微的内存开销
     2级包含基于主机名的详细统计数据。
     3级包含详细的统计基础设施等各种信息参数,严重程度,或标记。

   stats_lifetime() default[10]
   //值：number (minutes)
   //控制频率动态计数器过期了。计时器是不准确的,一些计时器可能住有点超过指定的时间。
   动态计数器正在清理在指定时间间隔反复stats_lifetime(),而不是只在重新加载。这将减少使用的内存动态计数器。

   sync() or sync_freq() default[0]
   //值：number
   //flush_lines()的别名

   threaded() default[yes]
   //值：yes|no
   //使syslog_ng在多线程运行模式时使用多个cpu。

   time_reap() deault[60]
   //值：number
   //在没有消息前，到达多少秒，即关闭该文件的连接

   time_reopen() default[60]
   //值：number
   //对于死连接，到达多少秒，会重新连接

   time_sleep() default[0]
   //值：number
   //每次调用之间的等待时间以毫秒为单位的poll()迭代。

   time_zone() default[unspecified]
   //值：时区或时区偏移
   //转换时间戳(以及所有日期相关的宏的时间戳)时区，指定此选项。如果不设置这个选项,那么使用原来的时区信息的消息。

   use_dns() default[yes]
   //值：yes, no, persist_only
   //启用或禁用DNS的使用。persist_only选项尝试在本地解析主机名从文件(例如/etc/hosts)。

   use_fqdn() default[no]
   //值：yes or no
   //添加完全限定域名而不是短主机名。这个选项可以指定，在全局范围内,以及每个源。源的地方设置将覆盖全局选项如果可用。

   use_rcptid() default[no]
   //值：yes | no
   //当全局use_rcptid选项设置为yes,syslog_ng自动分配一个独一无二的接待每一个收到的消息ID。你可以访问这个ID和在模板中使用它通过${RCPTID}
   接收ID是一个单调增加48比特位整数,不能是零(如果1)计数器溢出,它重新启动。

```


### 2. source 定义日志源

``` sh
source s_internal {
  internal();
};               

source 有几个数据源:
   system();
   unix_dgram("/dev/log");
   # 内核的数据
   internal();
   # udp 收到的数据
   udp(ip("0.0.0.0") port(514));


file() 指定文件读取消息
    source s_file { file("/var/log/messages"); };

    file()选项说明：
        default_facility() //默认设备
        #类型：设备字符串
        #默认：kernel

        default_priority() //默认优先级
        #类型：优先级字符串
        #默认：空

        file() //读取消息文件路径
        #类型：文件名和路径
        #默认：空

        encoding() //字符编码
        #类型：字符
        #默认：空

        flags() //指定源的日志解析选项
        #类型：assume_utf8, empty_lines, expect_hostname, kernel,no_multi_line, no_parse, store_legacy_msghdr,syslog_protocol, validate_utf8
        #默认：空

        follow_freq() //定期检查源的可读性
        #类型：数字
        #默认：1

        keep_timestamp() //指定syslog_ng是否应该接受发送应用程序收到的时间戳或客户端时间戳。
        #类型：yes | no
        #默认：yes

        log_fetch_limit() //信息获取的最大数量从源的循环队列中。
        #类型：数字
        #默认：10

        log_iw_size() //初始窗口的大小,这个值是在流控制时使用,此值必须大于log_fetch_limit
        #类型：数字
        #默认：100

        log_msg_size() //指定接收日志消息的最大大小，如果全局未指定，使用次选项
        #类型：数字
        #默认：1000

        multi_line_garbage() //处理多行消息，匹配或不匹配是否需要的消息
        #类型：正则表达式
        #默认：空字串

        multi_line_mode() //多行消息模式
        #类型：indented|regexp
        #默认：空字串

network() 指定TCP或UDP方式接收消息
    network()参数说明：
        encoding() //字符编码
        #类型：字符
        #默认：空

        flags() //指定源的日志解析选项
        #类型：assume_utf8, empty_lines, expect_hostname, kernel,no_multi_line, no_parse, store_legacy_msghdr,syslog_protocol, validate_utf8
        #默认：空

        host_override() //取代${HOST}消息的参数字符串的一部分。
        #类型：字串
        #默认：空

        ip() or localip() //指定ip地址
        #类型：字串
        #默认：0.0.0.00

        ip_protocol() //使用ipv4或ipv6
        #类型：数字
        #默认：4

        ip_tos() //指定出站包的Type_of_Service
        #类型：数字
        #默认：0

        ip_ttl() //指定出站包的Type_of_Service
        #类型：数字
        #默认：0

        keep_hostname() //启用或禁止主机名重写
        #类型：yes | no
        #默认：no

        keep_alive() //使用启用keepalive
        #类型：yes | no
        #默认：yes


        keep_timestamp() //指定syslog_ng是否应该接受发送应用程序收到的时间戳或客户端时间戳。
        #类型：yes | no
        #默认：yes

        log_fetch_limit() //信息获取的最大数量从源的循环队列中。
        #类型：数字
        #默认：10

        log_iw_size() //初始窗口的大小,这个值是在流控制时使用,此值必须大于log_fetch_limit
        #类型：数字
        #默认：1000

        log_msg_size() //指定接收日志消息的最大大小，如果全局未指定，使用次选项
        #类型：数字
        #默认：8192

        max_connections() //指定最大并发连接数
        #类型：数字
        #默认：10

        pad_size() //指定块大小
        #类型：数字
        #默认：0

        port() or localport() //指定绑定端口tcp默认514，udp默认601
        #类型：数字
        #默认：TCP : 601
               UDP : 514

        program_override() //取代${PROGRAM}消息的参数字符串的一部分。
        #类型：字串
        #默认：空

        so_broadcast() //是否启用消息广播
        #类型：yes or no
        #默认：no

        so_keepalive() //保持消息，保持打开套接字，只对TCP and UNIX_stream 有效
        #类型：yes or no
        #默认：no

        so_rcvbuf() //指定套接字接收缓冲区的大小的字节
        #类型：数字
        #默认：0

        so_sndbuf() //指定套接字发送缓冲区的大小的字节
        #类型：数字
        #默认：0

        transport()
        #类型：assume_utf8
        #默认：TCP

        tls()
        #类型：tls选项
        #默认：n/a

        use_dns()
        #类型：yes, no, persist_only
        #默认：yes

        use_fqdn()
        #类型：yes or no
        #默认：no

nodejs() 接收json消息从nodejs


internal() syslog_ng 收集内部产生的消息。

    source s_local { internal(); };

pacct() 读取进程统计消息


pipe() 管道读取消


program() 打开指定程序读取消息


syslog() 使用标准的syslog协议，监听读入的消息


tcp ()  指定的TCP端口接收日志消息


udp ()  指定的UDP端口接收日志消息        


unix_dgram() 从指定的uninx套接字SOCK_DGRAM接收消息


unix_stream() 从指定的uninx套接字SOCK_STREAM接收消息

```


### 3. filter 过滤

``` sh

设置过滤器,选项(例如TLS加密)和其他高级功能。
filter <identifier> {
  <filter_type>("<filter_expression>");
 };

fileter 使用的计算操作符：
   数字    字符串操作    含义
    ==         eq          等于
    !=         ne          不等于
    >       gt          大于
    <       lt             小于
    >=      ge             大于等于
    =<      le             小于等于

filter 方法说明：

facility()
    //基于过滤消息发送功能。
    数字   设备名     含义
    0      kern      内核消息
    1      user     用户相关消息
    2      mail     邮件相关
    3      daemon    系统相关
    4      auth      认证相关
    5      syslog    syslog消息
    6      lpr       打印机相关
    7      news      网络新闻相关
    8      uucp      UUCP相关
    9      cron      计划任务相关
    10         authpriv  权限,授权相关的
    11         ftp      ftp相关
    12         ntp       NTP相关
    13         security  安全相关的,与auth 类似
    14         console   日志警告
    15         solaris_cron clock daemon
    16_23  local0..local7 在本地使用的设备(local0_local7)
    例如：
    facility(user)
    facility(1)
    facility(local0..local5)


filter()  调用另一个filter方法


host() 基于过滤消息发送主机。


inlist() 基于黑白名单过滤
    in_list("</path/to/file.list>", value("<field_to_filter>"));
    例如：
    /etc/syslog_ng/programlist.list
    kernel
    sshd
    sudo
    filter f_whitelist { in_list("/etc/syslog_ng/programlist.list", value("PROGRAM")); };


level() or priority() 基于等级或优先级过滤
    例如：
    level(warning)
    level(err..emerg)

    emerg, alert, crit, err, warning, notice, info, debug

match() 使用正则表达式根据指定的标题或内容过滤消息字段。


message() 使用一个正则表达式基于内容过滤消息。


netmask() 基于过滤消息发送主机的IP地址。


program() 根据发送应用程序过滤消息。


source() 选择指定syslog_ng OSE的消息源语句。


tags() 选择消息指定的标签


```


### 4. destination 目标

``` sh
destination d_network {
  network("10.1.2.3" transport("udp");
};

destination 说明：
    amqp()
    //发布消息使用AMQP

    file()
    //写日志到指定文件
    file()参数说明：

    graphite()
    //发送度量值到Graphite存储time_series数据.

    mongodb()
    //发送消息到mongodb数据库

    network()
    //发送消息到远程主机，支持TCP、UDP

    pipe()
    //写消息到管道

    program()
    //发消息到指定程序

    redis()
    //发送消息使用键值对存储到redis

    smtp()
    //发送email消息到指定的接收者

    syslog()
    //发送消息到指定的远程主机，使用syslog协议

    tcp()
    //发送消息到远程主机，通过指定的TCP端口

    UDP()
    //发送消息到远程主机，通过指定的UDP端口

    unix_dgram()
    //发送消息到指定的unix套接字文件SOCK_DGRAM

    unix_stream()
    //发送消息到指定的unix套接字文件SOCK_STREAM

    usertty()
    //发送消息到指定的终端用户，如果用户在登录状态

```


### 5. 处理流程

``` sh
log { source(s_internal); destination(d_file); }; //创建一个日志语句连接源和当地的目的地。

```


## 三、案例

### 1. php 写 syslog_ng 配置模板


``` sh

# uba_app_action 主题 Log

# uba_app_action 日志模板
template t_uba_app_action {
  template("${MSG}\n"); template_escape(no);
};


# 日志过滤规则,处理 info 级别的日志
filter f_uba_app_action_info {
  level("info");
  # 表示从指定的日志标识获取日志
  program("uba_app_action");
};

# 日志过滤规则,处理 error 级别的日志
filter f_uba_app_action_error {
  level("error");
  # 表示从指定的日志标识获取日志
  program("uba_app_action");
};

# 日志去处, 争取日志去处
destination d_uba_app_action_info {
  # 本地文件记录一份
  file("/var/log/uba/uba_app_action/uba_app_action_${S_YEAR}${S_MONTH}${S_DAY}.log" template(t_uba_app_action) perm(0644) );

  # 写 TPC 或者 UDP 注意一个坑, 就是目标的 tcp 或者 udf 端口要提前建立, 不然 syslog-ng 就不能建立连接, 导致数据不能发过去
  # nc -l ip_address 10001, 提前开一个端口, 用来测试数据
  tcp( "ip_address" port(10001) template(t_uba_app_action) );
};

# 错误日志去处
destination d_uba_app_action_error {
  # 本地文件记录一份
  file("/var/log/uba/uba_app_action/uba_app_action_invalid_${S_YEAR}${S_MONTH}${S_DAY}.log" template(t_uba_app_action) perm(0644) );
};


# info 日志链, 处理日志流向规则
log {
  source(s_uba_app_action);
  filter(f_uba_app_action_info);
  destination(d_uba_app_action_info);
};


# error 日志链, 处理日志流向规则
log {
  # 除非特殊原因不然, 不建议替换成系统自身的 s_src 源
  source(s_src);
  filter(f_uba_app_action_error);
  destination(d_uba_app_action_error);
};

```
