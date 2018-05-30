# sbt Scala 的包管理工具

- Simple Build Tool 简单的构建工具


## 一、安装与配置

- [sbt 官方文档](http://www.scala-sbt.org/0.13/docs/zh-cn/Getting-Started.html/)

### 1. 下载

- [JAVA 环境](../Java/java-jdk.MD)
- [SCALA 环境](scala-deploying.md)

``` sh
1. 使用手动方式安装
   (http://www.scala-sbt.org/0.13/docs/zh-cn/Manual-Installation.html)
   下载 sbt-launch.jar

2. 安装变量
  export SBT_HOME=/data/usr/sbt

  1) 存放目录
     mv sbt-launch.jar  $SBT_HOME
     sudo ln -s $SBT_HOME /usr/local/sbt

  2) 编写 stb 执行文件
    vim $SBT_HOME/sbt
      #!/bin/bash
      # 这里是你的 JAVA_HOME 安装目录
      # JAVA_HOME="/Library/Java/JavaVirtualMachines/jdk1.7.0_75.jdk/Contents/Home"
      SBT_OPTS="-Xms512M -Xmx1536M -Xss1M -XX:+CMSClassUnloadingEnabled -XX:MaxPermSize=256M -Dsbt.ivy.home=${SBT_HOME}/ivy-repository -Dsbt.boot.directory=${SBT_HOME}/boot"
      $JAVA_HOME/bin/java $SBT_OPTS -jar `dirname $0`/sbt-launch.jar "$@"

    #增加执行权限
    chmod u+x $SBT_HOME/sbt

  3) sbt 写入环境变量
    vim ~/.bashrc
    # SBT
    export PATH=$SBT_HOME:$PATH

    source ~/.bashrc

  4) 创建仓库目录
    mkdir -p ${SBT_HOME}/ivy-repository
    mkdir -p ${SBT_HOME}/boot

2. 安装后查看版本
   sbt sbt-version  : 第一次运行初始化会很久

```


## 二、构架与使用

### 1. 配置文件、运行模式介绍

``` sbt
1. 依赖管理
  1) unmanaged dependencies  非托管的依赖性
    a) jar 包放到 lib 目录下即可
      // 非托管依赖
      unmanagedBase := baseDirectory.value / "lib"

    b) 更改存放路径
      unmanagedBase <<= baseDirectory { base => base / "3rdlibs" }

  2) managed dependencies 依赖关系管理 (一般用这种方式)
    采用 Apache Ivy 的依赖管理方式， 可以支持从 Maven 或者 Ivy 的 Repository 中自动下载相应的依赖

    a) 格式
      libraryDependencies += groupID % artifactID % revision
      如 : libraryDependencies += "org.apache.derby" % "derby" % "10.4.1.3"

    b) 实际案例
      libraryDependencies += "org.apache.derby" % "derby" % "10.4.1.3" % "test" 允许我们限定依赖的范围只限于测试期间

      libraryDependencies += "org.apache.derby" % "derby" % "10.4.1.3" exclude("org", "artifact") 允许我们排除递归依赖中某些我们需要排除的依赖

      libraryDependencies += "org.apache.derby" %% "derby" % "10.4.1.3"  
      等同于"org.apache.derby" %% "derby_2.9.2" % "10.4.1.3"，这种方式更多是为了简化同一依赖类库存在有多个 Scala 版本对应的发布的情况

      libraryDependencies ++= Seq("org.apache.derby" %% "derby" % "10.4.1.3",
                            "org.scala-tools" %% "scala-stm" % "0.3",
                            ...)
      一次添加多个依赖


2. Resovers 多资源管理
  1) 增加远程资源 (可以增加 git 依赖)
    resolvers += "Sonatype OSS Snapshots" at "https://oss.sonatype.org/content/repositories/snapshots"

  2) 增加本地源
    resolvers += "Local Maven Repository" at "file:///usr/local/maven/repository"
    resolvers += "Local Maven Repository" at "file://"+Path.userHome.absolutePath+"/.m2/repository"


3. sbt 项目的 build 文件类型

  1) 对于一个SBT项目来说，SBT在构建的时候，只关心两点
    a) build 文件的类型（是 *.sbt OR *.scala）
    b) build 文件的存放位置

    .sbt 和 .scala 二者之间的 settings(设置) 是可互相访问的
    .scala 中的内容会被 import 到 .sbt
    .sbt 中 的 settings 也会被添加到 .scala 的 settings 当中
    默认情况下 .sbt 中的 settings 会被纳入 Project 级别的 Scope 中，除非明确指定哪些Settings定义的Scope； .scala中则可以将settings纳入Build级别的Scope，也可以纳入Project级别的Scope。

  2) *.sbt
    a) *.sbt 项目根目录下
    b) .sbt 中定义常用的 settings

  3) *.scala
    a) *.scala 项目根目录下的 project 目录下
    b) 在多模块项目构建中，为了避免多个 .sbt 的存在引入过多的繁琐，才会只用.scala形式的build定义。

  4) sbt 目录结构 (可以一层嵌套一层)
    从第一层的项目根目录开始， 其下project/目录内部再嵌套project/目录，可以无限递归
    hello/
      *.scala
      build.sbt
      project/
        build.properties  : 声明使用的要使用哪个版本的SBT来编译当前项目
        plugins.sbt       : 声明当前项目希望使用哪些插件来增强当前项目使用的sbt的功能
        *.scala
        build.sbt
        /project
          *.scala
      src/main/resources  : 配置文件资料目录
      src/main/java       : java 代码
      src/main/scala      : scala 代码目录
      lib_managed         : 工程所依赖的 jar 文件。会在sbt更新的时候添加到该目录
```

### 2.1 build.sbt 实际案例配置

``` sbt
import AssemblyKeys._

// 打开 assembly 插件功能
assemblySettings

// 配置 assembly 插件所有使用的 JAR
jarName in assembly := "recommend-2.0.jar"

// 项目名称
name := "recommend-2.0"

// 组织名称
organization := "com.angejia.dw.recommend"

// 项目版本号
version := "2.0"

// scala 版本
scalaVersion := "2.10.6"

// Eclipse 支持
EclipseKeys.createSrc := EclipseCreateSrc.Default + EclipseCreateSrc.Resource

// 非托管资源目录
unmanagedResourceDirectories in Compile += { baseDirectory.value / "src/main/resources" }
// 非托管依赖(项目根目录 lib)
unmanagedBase := baseDirectory.value / "lib"

// 相关依赖
// provided 关键字, 不会把 provided 关键字的依赖，打入到 jar 中.
libraryDependencies ++= Seq(
    // scala-library
    "org.scala-lang" % "scala-library" % "2.10.6",

    // hadoop 依赖
    "org.apache.hadoop" % "hadoop-common" % "2.6.0",
    "org.apache.hadoop" % "hadoop-hdfs" % "2.6.0",
    "org.apache.hadoop" % "hadoop-client" % "2.6.0",

    // Spark 依赖 : spark-core_2.10(spark 所属 scala 版本号) 1.5.2(spark 版本号)
    "org.apache.spark" % "spark-core_2.10" % "1.5.2",
    "org.apache.spark" % "spark-streaming_2.10" % "1.5.2",
    "org.apache.spark" % "spark-streaming-kafka_2.10" % "1.5.2"
        exclude("org.apache.avro","*")
        exclude("org.slf4j","*"),
    "org.apache.spark" % "spark-mllib_2.10" % "1.5.2",
    //"org.apache.avro"  % "avro" % "1.7.4",
    //"org.apache.avro"  % "avro-ipc" % "1.7.4" excludeAll(excludeNetty),

    // jblas 线性代数库,求向量点积
    "org.jblas" % "jblas" % "1.2.4",

    // Kafka 依赖
    "org.apache.kafka" % "kafka_2.10" % "0.9.0.0" ,
    "org.apache.kafka" % "kafka-log4j-appender" % "0.9.0.0" % "provided" ,
    "org.apache.kafka" % "kafka_2.10" % "0.9.0.0"
        exclude("javax.jms", "jms")
        exclude("com.sun.jdmk", "jmxtools")
        exclude("com.sun.jmx", "jmxri"),

    // Hbase 依赖
    //"org.apache.hbase" % "hbase" % "1.0.0",
    "org.apache.hbase" % "hbase-common" % "1.0.0",
    "org.apache.hbase" % "hbase-client" % "1.0.0",
    "org.apache.hbase" % "hbase-server" % "1.0.0",

    // Mysql 依赖
    "mysql" % "mysql-connector-java" % "5.1.38",

    // play Json 包, 版本太高会冲突
    "com.typesafe.play" % "play-json_2.10" % "2.3.9",
    // spray Json 包
    "io.spray" % "spray-json_2.10" % "1.3.2",
    // smart Json 包
    "net.minidev" % "json-smart" % "2.2.1",

    // java Json 包
     "com.googlecode.json-simple" % "json-simple" % "1.1.1",


    "net.sf.jopt-simple" % "jopt-simple" % "4.9" % "provided",
    "joda-time" % "joda-time" % "2.9.2" % "provided",
    "log4j" % "log4j" % "1.2.9"

)


// 强制默认合并
mergeStrategy in assembly <<= (mergeStrategy in assembly) { mergeStrategy => {
 case entry => {
   val strategy = mergeStrategy(entry)
   if (strategy == MergeStrategy.deduplicate) MergeStrategy.first
   else strategy
 }
}}


// 配置远程资源
resolvers ++= Seq(
      // HTTPS is unavailable for Maven Central
      "Maven Repository"     at "http://repo.maven.apache.org/maven2",
      "Apache Repository"    at "https://repository.apache.org/content/repositories/releases",
      "JBoss Repository"     at "https://repository.jboss.org/nexus/content/repositories/releases/",
      "MQTT Repository"      at "https://repo.eclipse.org/content/repositories/paho-releases/",
      "Cloudera Repository"  at "https://repository.cloudera.com/artifactory/cloudera-repos/",
      "Elaticsearch Repository" at "https://mvnrepository.com/artifact/org.elasticsearch/elasticsearch",
      // For Sonatype publishing
      // "sonatype-snapshots"   at "https://oss.sonatype.org/content/repositories/snapshots",
      // "sonatype-staging"     at "https://oss.sonatype.org/service/local/staging/deploy/maven2/",
      // also check the local Maven repository ~/.m2

      //本地 mavan 仓库地址,详细目录写自己的
      "Local Maven Repository" at "file:///usr/local/maven/repository",
      Resolver.mavenLocal
)


// 配置本地资源
// resolvers += "Local Maven Repository" at "file:///usr/local/maven/repository"

// 指定 JDK 版本
javaHome := Some(file("/opt/jdk/jdk1.7.0"))

```

### 2.2 assembly 打包 jar 冲突解决方案

对于 jar 冲突具体有几个解决方案

- 排除冲突的 package
- 合并冲突的 class
- 排除冲突的 jars

``` sql
1. provided 关键字, 不把这个依赖包打入 jar 中
  例 1: 不加入打包法
  "org.apache.kafka" % "kafka-log4j-appender" % "0.9.0.0" % "provided"

2. excludeAll 排除冲突
  例 1: 排除组织和包名法
  "org.apache.hadoop" % "hadoop-client" % "2.6.0" excludeAll(
    // 排除名为 hive-metastore 的包
    ExclusionRule(name = "hive-metastore"),
    // 排除组织名为  com.sun.jdmk 的包
    ExclusionRule(organization = "com.sun.jdmk")
  )

3. mergeStrategy 合并策略, 对 class、文件做合并策略
  例 1: 对每个文件都有合并策略
  mergeStrategy in assembly <<= (mergeStrategy in assembly) { (old) => {
      case PathList("org", "slf4j", xs@_*) => MergeStrategy.last
      // 排除 指定 class
      case PathList(ps @ _*) if ps.last endsWith "ILoggerFactory.class"     => MergeStrategy.first
      case PathList(ps@_*) if ps.last endsWith "pom.properties"             => MergeStrategy.last
      case PathList(ps@_*) if ps.last endsWith ".class"                     => MergeStrategy.last
      case PathList(ps@_*) if ps.last endsWith ".thrift"                    => MergeStrategy.last
      case PathList(ps@_*) if ps.last endsWith ".xml"                       => MergeStrategy.last
      case PathList(ps@_*) if ps.last endsWith ".css"                       => MergeStrategy.last
      case PathList(ps@_*) if ps.last endsWith ".properties"                => MergeStrategy.last
      case PathList("javax", "servlet", xs @ _*)                            => MergeStrategy.last
      case x => old(x)
    }
  }

  例 2: 强制合并策略, 对所有冲突使用一个合并策略
  mergeStrategy in assembly <<= (mergeStrategy in assembly) { mergeStrategy => {
      case entry => {
       val strategy = mergeStrategy(entry)
       if (strategy == MergeStrategy.deduplicate) MergeStrategy.first
       else strategy
      }
    }
  }

4. 强制排除 jars 不打入依赖包
  例 1: 排除指定 jar, excludedJars(sbt 0.13 或者之前版本)
  excludedJars in assembly <<= (fullClasspath in assembly) map { cp =>
    cp filter {_.data.getName == "hive-metastore-1.1.0.jar"}
  }

  例 2: 排除指定 jar, assemblyExcludedJars(sbt 0.13 之后版本, 详细见 github 文档):
  // 官方写法
  assemblyExcludedJars in assembly := {
    val cp = (fullClasspath in assembly).value
    cp filter {_.data.getName == "compile-0.1.0.jar"}
  }

  // 自定义写法
  assemblyExcludedJars in assembly := {
    val cp = (fullClasspath in assembly).value
    cp filter { f =>
      f.data.getName.contains("spark-core") ||
      f.data.getName == "spark-core_2.11-2.0.1.jar"
    }
  }
```


### 3. project/plugins.sbt 插件配置文件

``` sbt
// 配置远程资源
resolvers += Resolver.url("artifactory", url("http://scalasbt.artifactoryonline.com/scalasbt/sbt-plugin-releases"))(Resolver.ivyStylePatterns)

resolvers += "Typesafe Repository" at "http://repo.typesafe.com/typesafe/releases/"

// eclipse 插件
addSbtPlugin("com.typesafe.sbteclipse" % "sbteclipse-plugin" % "2.5.0")

// 打包所有依赖的插件
addSbtPlugin("com.eed3si9n" % "sbt-assembly" % "0.11.2")

// 依赖树 插件 dependencyTree, dependencyBrowseGraph
addSbtPlugin("net.virtual-void" % "sbt-dependency-graph" % "0.8.2")
```


### 4. 运行项目

- [sbt 命令](http://www.scala-sbt.org/0.13/docs/zh-cn/Running.html)

#### 4.1 运行模式和组件

``` sh
1. SBT 支持两种使用方式：
  1) 批处理模式(batch mode)
    sbt compile test package 批处理模式 (命令前后有依赖关系)

  2) 可交互模式(interactive mode)
    sbt
    > compile
    > test
    > package


2. SBT 阶段
  1) 阶段
    compile         编译
    test-compile    测试编译
    test            测试用例
    run             运行
    package         打包


3. 触发器 ~
  sbt ~compile  当源码变动时，自动编译
  sbt ~run      变动时，运行

```

#### 4.2 实际案例

- [运行命令集合](http://www.scala-sbt.org/1.0/docs/zh-cn/Running.html)

``` sh

1. 创建项目结构
  mkdir sbt-app
  cd sbt-app
  mkdir project

2. *. sbt 文件配置
  # 项目说明
  vim build.sbt
    写 build.sbt

  # 添加插件
  vim project/plugins.sbt
    写 project/plugins.sbt

4. 运行
  sbt clean compile       // 先清除target目录下的所有的文件，再编译
  sbt eclipse             // 将当前的sbt项目转为eclipse项目
  sbt update              // 更新项目依赖
  sbt run                 // 运行当前项目（需要设置：mainClass）
  sbt test                // 运行测试用例
  sbt clean compile  package    // 组合命令
  sbt reload eclipse     // 更新项目依赖, 如果在 eclipse 要切换到 Scala 环境中, 重新 clean 项目
  sbt update eclipse

  # 测试程序
  echo 'object HelloWorld { def main(args: Array[String]) = println("HelloWorld") }' >> src/main/scala/HelloWorld.scala

  # sbt 打包 jar
  sbt clean assembly    打包所有依赖 jar
    java -cp target/scala-2.10/xx.xxx.xx-SNAPSHOT.jar HelloWorld

  sbt 命令合集
    console - 启用 Scala 解释器
    actions – 显示对当前工程可用的命令
    update – 下载依赖
    compile – 编译代码
    test – 运行测试代码
    run - 运行代码
    package – 创建一个可发布的jar包
    publish-local – 把构建出来的jar包安装到本地的ivy缓存
    publish – 把jar包发布到远程仓库（如果配置了的话)

    test-failed – 运行失败的spec
    test-quick – 运行所有失败的以及/或者是由依赖更新的spec

    sbt clean compile "testOnly TestA TestB"  : testOnly 有两个参数 TestA 和 TestB。这个命令会按顺序执行（clean， compile， 然后 testOnly）

5. 查看依赖树
  sbt dependency-graph
```
