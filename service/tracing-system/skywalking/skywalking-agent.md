# 监控接入

- Agent 收集 Trace 数据
- Agent 发送 Trace 数据给 Collector
- Collector 接收 Trace 数据
- Collector 存储 Trace 数据到存储器，例如，数据库


## 一. Java Agent

- [配置 Java Agent 指南](https://github.com/apache/incubator-skywalking/tree/master/docs/en/setup/service-agent/java-agent)

### * Java Agent 配置

- [文件覆盖的系统属性](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/Setting-override.md)
- [通过系统属性](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/Specified-agent-config.md)
- 更多配置见 -> 配置 Java Agent

``` sh
# 当前的应用编码，最终会显示在webui上。
# 建议一个应用的多个实例，使用有相同的application_code。请使用英文
agent.application_code=Your_ApplicationName

# 每三秒采样的Trace数量
# 默认为负数，代表在保证不超过内存Buffer区的前提下，采集所有的Trace
# agent.sample_n_per_3_secs=-1

# 设置需要忽略的请求地址
# 默认配置如下
# agent.ignore_suffix=.jpg,.jpeg,.js,.css,.png,.bmp,.gif,.ico,.mp3,.mp4,.html,.svg

# 探针调试开关，如果设置为true，探针会将所有操作字节码的类输出到/debugging目录下
# skywalking团队可能在调试，需要此文件
# agent.is_open_debugging_class = true

# 对应 Collector的config/application.yml 配置文件中 agent_server/jetty/port 配置内容
# 例如：
# 单节点配置：SERVERS="127.0.0.1:8080"
# 集群配置：SERVERS="10.2.45.126:8080,10.2.45.127:7600"
collector.servers=127.0.0.1:10800

# 日志文件名称前缀
logging.file_name=skywalking-agent.log

# 日志文件最大大小
# 如果超过此大小，则会生成新文件。
# 默认为300M
logging.max_file_size=314572800

# 日志级别，默认为DEBUG。
logging.level=DEBUG
```

- Java Agent 启动方式

``` sh
# JAR File 或 Spring Boot, 添加 -javaagent 参数
java -javaagent:/path/to/skywalking-agent/skywalking-agent.jar -jar /path/to/xxx.jar

# Linux Tomcat 7，Tomcat 8, tomcat/bin/catalina.sh
CATALINA_OPTS="$CATALINA_OPTS -javaagent:/path/to/skywalking-agent/skywalking-agent.jar"; export CATALINA_OPTS

# Jetty, 在 {JETTY_HOME}/start.ini 配置文件中添加以下内容
--exec    # 去掉前面的井号取消注释
-javaagent:<skywalking-agent-path>
```


### 1. JAVA Agent 自动代理插件列表

- [支持的中间件/框架/库的列表](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/Supported-list.md)
- [各版本插件兼容测试](https://github.com/SkyAPMTest/agent-integration-test-report)
- [插件开发指南](https://github.com/apache/incubator-skywalking/blob/master/docs/en/guides/Java-Plugin-Development-Guide.md)

#### 1.1 关闭插件

``` sh
删除对应的 plugins 列表即可

+-- skywalking-agent
	+-- activations
		 apm-toolkit-log4j-1.x-activation.jar
		 apm-toolkit-log4j-2.x-activation.jar
		 apm-toolkit-logback-1.x-activation.jar
		 ...
	+-- config
		 agent.config  
	+-- plugins
		 apm-dubbo-plugin.jar
		 apm-feign-default-http-9.x.jar
		 apm-httpClient-4.x-plugin.jar
		 .....
	skywalking-agent.jar
```

#### 1.2 可选插件

- [Spring bean 插件](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/agent-optional-plugins/Spring-annotation-plugin.md)

``` sh
允许在Spring上下文中追踪带有@Bean、 @Service、@Component和@Repository注解的bean的所有方法

为什么这个插件是可选的？ 在Spring上下文中追踪所有方法会创建很多的span，也会消耗更多的CPU，内存和网络。 当然你希望包含尽可能多的span，但请确保你的系统有效负载能够支持这些。
```

- [Oracle and Resin 插件](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/agent-optional-plugins/Oracle-Resin-plugins.md)

``` sh
由于Oracle和Resin的License，这些插件无法在Apache发行版中提供。

我们应该如何在本地构建这些可选插件？
Resin 3: 下载Resin 3.0.9 并且把jar放在/ci-dependencies/resin-3.0.9.jar。
Resin 4: 下载Resin 4.0.41 并且把jar放在/ci-dependencies/resin-4.0.41.jar。
Oracle: 下载Oracle OJDBC-14 Driver 10.2.0.4.0 并且把jar放在/ci-dependencies/ojdbc14-10.2.0.4.0.jar。
```

- [自定义跟踪忽略](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/agent-optional-plugins/trace-ignore-plugin.md)

``` sh
此插件的目的是过滤预期被跟踪系统忽略的端点。
您可以设置多个URL路径模式，不会跟踪匹配这些模式的端点。
当前的匹配规则遵循Ant Path的比赛风格，像/path/*，/path/**，/path/?。
复制apm-trace-ignore-plugin-x.jar到agent/plugins，重新启动agent可以影响插件。
```

- [支持自定义增强](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/Customize-enhance-trace.md)


### 2. Application Toolkit

- Application Toolkit 是由 Skywalking APM 提供的库的集合
- 应用程序和远程 APM 代理之间建立桥梁

#### 2.1 OpenTracing Java API

- [Opentracing](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/Opentracing.md)
- [Opentracing IO](http://opentracing.io)

``` java
<dependency>
   <groupId>org.apache.skywalking</groupId>
   <artifactId>apm-toolkit-opentracing</artifactId>
   <version>{project.release.version}</version>
</dependency>


# 使用我们的 OpenTracing tracer 实现
Tracer tracer = new SkywalkingTracer();
Tracer.SpanBuilder spanBuilder = tracer.buildSpan("/yourApplication/yourService");
```


#### 2.2 SkyWalking 本机 Java API

- [SkyWalking 手动 API](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/Application-toolkit-trace.md)

``` java
<dependency>
	<groupId>org.apache.skywalking</groupId>
	<artifactId>apm-toolkit-trace</artifactId>
	<version>${skywalking.version}</version>
</dependency>

import TraceContext;
modelAndView.addObject("traceId", TraceContext.traceId());

ActiveSpan.tag("my_tag", "my_value");
```


#### 2.3 日志中打印跟踪上下文（例如traceId）

- [log4j](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/Application-toolkit-log4j-1.x.md)
- [log4j2](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/Application-toolkit-log4j-2.x.md)
- [logback](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/Application-toolkit-logback-1.x.md)

``` java
log4j-2 案例如下

Dependency the toolkit, such as using maven or gradle
<dependency>
  <groupId>org.apache.skywalking</groupId>
  <artifactId>apm-toolkit-log4j-2.x</artifactId>
  <version>{project.release.version}</version>
</dependency>


Config the [%traceId] pattern in your log4j2.xml
<Appenders>
  <Console name="Console" target="SYSTEM_OUT">
      <PatternLayout pattern="%d [%traceId] %-5p %c{1}:%L - %m%n"/>
  </Console>
</Appenders>

当使用-javaagent激活空中行走跟踪器时，如果存在traceId, log4j2将输出traceId。如果跟踪器处于非活动状态，则输出TID: N/A。
```


#### 2.4 手动跨线程解决方案 API

- [跨线程解决方案API](https://github.com/apache/incubator-skywalking/blob/master/docs/en/setup/service-agent/java-agent/Application-toolkit-trace-cross-thread.md)



## 二. 接入第三方调用链监控

### 1. zipkin

``` sh
修改配置文件 config/application.yml 打开如下注释, 重启服务

receiver_zipkin:
  default:
    host: ${SW_RECEIVER_ZIPKIN_HOST:0.0.0.0}
    port: ${SW_RECEIVER_ZIPKIN_PORT:9411}
    contextPath: ${SW_RECEIVER_ZIPKIN_CONTEXT_PATH:/}
```
