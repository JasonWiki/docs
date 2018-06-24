# 日志

``` java

1. 加载配置文件路径
  默认 src 根目录下
    加载顺序
    log4j.xml
    log4j.properties

  指定路径
    String log4jConfPath = "path/src/resources/";

    log4j.properties 文件使用
      PropertyConfigurator.configure(log4jConfPath + "log4j.properties");

    log4j.xml  
      DOMConfigurator.configureAndWatch(log4jConfPath + "log4j.xml",10);



1. 初始化
   private static final Logger LOG=LoggerFactory.getLogger(Classname.class);


2. 记录日志
  LOG.error("curatorOperato");
  LOG.info("调度成功启动......");


```
