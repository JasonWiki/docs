# 路径相关

``` java
File.separator 路径分隔符

1.项目路径
  获取项目绝对路径, 从类路径寻找
  StartScheduler.class.getClass().getResource("/").getFile().toString()

  获取项目相对路径(所以 打jar 包后无效), 从 classpath 的目录开始寻找
  StartScheduler.class.getClassLoader().getResource("").getPath();

  /**
  * 第一种：获取根路径
           * bin : /Users/jack/app/tools/dw_scheduler_agent/bin
           * jar :
           */
          File f = new File(this.getClass().getResource("/").getPath());
          System.out.println(f);

          /**
           * 第二种：获取类所在路径
           * bin : /Users/jack/app/tools/dw_scheduler_agent/bin/com/ajk/dw/scheduler
           * jar : com/ajk/dw/scheduler
           */
          File f2 = new File(this.getClass().getResource("").getPath());
          System.out.println(f2);


2.动态加载当前类所在项目根路径文件流
  InputStream file = SchedulerConfigFactory.class.getResourceAsStream("/resources/schedul.default.yaml");





#动态传参 -D 设置系统参数
java -jar -Dkey.name=123
System.setProperty("log4j.configuration",x);
System.getProperty(key.name, ".");默认是 .




MANIFEST.MF
Manifest-Version: 1.0
Rsrc-Class-Path: ./ commons-beanutils.jar commons-collections-3.1.jar
 commons-dbcp-1.4.jar commons-dbutils-1.5.jar commons-io-2.0.1.jar com
 mons-lang-2.5.jar commons-logging-1.1.1.jar commons-pool-1.5.4.jar cu
 rator-client-2.5.0.jar curator-framework-2.5.0.jar curator-recipes-2.
 5.0.jar guava-16.0.1.jar hibernate3.jar httpclient-4.0.1.jar httpcore
 -4.0.1.jar jta-1.1.jar libthrift-0.9.1.jar log4j-1.2.15.jar mysql-con
 nector-java-5.1.20.jar quartz-all-1.6.0.jar slf4j-api-1.5.6.jar slf4j
 -log4j12-1.5.6.jar snakeyaml-1.11.jar zookeeper-3.5.0-alpha.jar
Class-Path: .
Rsrc-Main-Class: com.ajk.dw.scheduler.StartScheduler
Main-Class: org.eclipse.jdt.internal.jarinjarloader.JarRsrcLoader

```
