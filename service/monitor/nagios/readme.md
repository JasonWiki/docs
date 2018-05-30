# 通用说明

## 注意场景

当 nagios 主服务部署服务器要监控本身服务, 在配置 host 的时候需要填写本地 ip 地址 127.0.0.1, 例如下面

``` sh
# 注意, 如果本机地址和 nagios 服务在一台服务器上, 需要些 ip 地址 127.0.0.1, 否则会导致某些插件不可用
define host{
        use                     host_base_template

        host_name               AppServer1
        alias                   AppServer1
        address                 127.0.0.1
}

```


## 宏命令

- Nagios 事先定义了许多宏，它们的值通常依赖于其上下文。

``` sh
1. 场景
  HOSTNAME: 用于引用host_name指定所定义的主机的主机名；每个主机的主机名都是唯一的；

  HOSTADDRESS: 用于引用host对象中的address指令的值，它通常可以为IP地址或主机名；

  HOSTDISPLAYNAME: 用于引用host对象中alias指令的值，用以描述当前主机，即主机的显示名称；

  HOSTSTATE：某主机的当前状态，为UP,DOWN,UNREACHABLE三者之一；

  HOSTGROUPNAMES: 用于引用某主机所属的所有主机组的简名，主机组名称之间以逗号分隔；

  LASTHOSTCHECK：用于引用某主机上次检测的时间和日期，Unix时间戳格式；

  LISTHOSTSTATE：用于引用某主机前一次检测时的状态，为UP,DOWN或UNREACHABLE三者之一；

  SERVICEDESC: 用于引用对应service对象中的desccription指令的值；

  SERVICESTATE: 用于引用某服务的当前状态，为OK,WARNING,UNKOWN或CRITICAL四者之一；

  SERVICEGROUPNAMES: 用于引用某服务所属的所有服务组的简名，服务组名称之间以逗号分隔；

  CONTACTNAME: 用于引用某contact对象中contact_name指令的值；

  CONTACTALIAS: 用于引用某contact对象中alias指令的值；

  CONTACTEMAIL: 用于引用某contact对象中email指令的值；

  CONTACTGROUPNAMES: 用于引用某contact所属的所有contact组的简名，contact组名称之间以逗号分隔；


2. 如下使用案例:
  联系人邮件: $CONTACTEMAIL$

  通知类型: $NOTIFICATIONTYPE$

  Host 别名: $HOSTALIAS$

  Host 地址: $HOSTADDRESS$

  Service 描述: $SERVICEDESC$

  Service 状态: $SERVICESTATE$

  Service 服务状态类型: $SERVICESTATETYPE$

  Service 服务重试次数: $SERVICEATTEMPT$

  Time: $LONGDATETIME$

  服务警报: $HOSTALIAS$ / $SERVICEDESC$ is $SERVICESTATE$

  额外的信息: $SERVICEOUTPUT$

```


## 返回状态

- 0 (OK) 表示状态正常/绿色
- 1 (WARNING) 表示出现警告/黄色
- 2 (CRITICAL) 表示出现非常严重的错误/红色
- 3 (UNKNOWN) 表示未知错误/深黄色。


## 事件处理器 event handler

``` sh
nagios.cfg 打开全局事件处理
  enable_event_handlers=1

hosts.cfg、services.cfg 事件处理器
  event_handler_enabled=1


时间处理器的处理逻辑, 脚本(故障/恢复)状态才会触发 event handler, 具体流程如下
1) 正常检查间隔(check_interval), 发现脚本状态 <> 0, 触发 event_handler 定义的脚本, 不发送通知
2) 转到 故障检查间隔(retry_interval), 检查次数达到了(max_check_attempts), 触发 event_handler 定义的脚本, 并且发送通知
3) 达到 max_check_attempts 次数后, 转到 正常检查间隔(check_interval), 如果错误会继续发送通知邮件, 但是不再触发 event_handler 定义的脚本


# 案例模板
define service{
        # 检查间隔
        check_interval          1
        # 重试间隔
        #retry_interval          2
        # 重试次数
        max_check_attempts      2

        # 监控时间范围
        check_period            24x7

        # 通知配置
        notification_interval   60
        notification_period     24x7
        notification_options    w,u,c,r

        host_name               AppServer1
        service_description     Test
        check_command           check_tcp!8000

        # 打开 service 的事件处理器
        event_handler_enabled   1
        # 配置处理命令
        event_handler           check_nrpe!spark_thrift_service

        contacts                jason
        #retain_status_information   1
}

```


## 延迟报警 flapping state

``` sh
Nagios 会对频繁变动的监控服务延迟通知, 为了能正常收到监控的信息, 需要把 nagios.cfg 配置中的关闭参数 enable_flap_detection

enable_flap_detection=0
```
