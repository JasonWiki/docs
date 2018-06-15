# Maven - Java 项目管理工具

- POM (Project Object Model)
  - 定义项目的类型、名字，管理依赖关系，定制插件的行为等等

- Maven 插件
  - mvn archetype:generate (archetype:generate)就是其中一个插件

- Maven 生命周期、阶段
  - mvn 生命周期

    ```
    default   生命期关注的是项目的编译和打包

    clean     生命期关注的是从输出目录中删掉临时文件，包括自动生成的源文件、编译后的类文件，之前版本的jar文件等

    site      生命期关注的是为项目生成文档。实际上，site 可以使用文档为项目生成一个完整的网站
    ```

  - mvn 阶段

    ```
    1. validate           验证项目的正确性，以及所有必需的信息都是否都存在。同时也会确认项目的依赖是否都下载完毕。
    2. compile            编译项目的源代码
    3. test               选择合适的单元测试框架，对编译后的源码执行测试；这些测试不需要代码被打包或者部署。
    4. package            将编译后的代码以可分配的形式打包，如Jar包。
    5. install            将项目打包后安装到本地仓库，可以作为其它项目的本地依赖。
    6. deploy             将最终的包复制到远程仓库，与其它开发者和项目共享。
    ```
- Maven 依赖管理

- Maven 库

- Maven 工程类型
 - war 网站工程
 - jar 工程
 - pom 聚合工程
  - 父工程使用 pom 工程
  - 定义依赖的 jar 版本
  - maven 插件
  - maven 仓库源

## 一、安装与配置

### 1. 安装

- [Maven Download](http://maven.apache.org/download.cgi)

``` sh

1. 下载二进制的包
  Binary tar.gz archive
  tar -zxvf /your-path/apache-maven-x.x.x-bin.tar.gz

2. 连接
  sudo ln -s /your-path/apache-maven-x.x.x /usr/local/maven

3. 环境变量
  vim ~/.bashrc
# Maven
# 注意配置 Java Home ,如果 Java Home 不存在的话
# export JAVA_HOME=/java_path
export MAVEN_HOME=/usr/local/maven
export M2_HOME=$MAVEN_HOME
export M2_REPO=$MAVEN_HOME/repository
export PATH=$MAVEN_HOME/bin:$PATH

source ~/.bashrc

4. 验证
  mvn -v

5. 添加 alibab 源
  vim $MAVEN_HOME/conf/settings.xml

  <!-- 阿里源 -->
  <mirror>
     <id>alimaven</id>
     <name>aliyun maven</name>
     <mirrorOf>central</mirrorOf>
     <url>http://maven.aliyun.com/nexus/content/groups/public/</url>
   </mirror>

```

### 2. 配置 settings.xml

- mvn 环境配置文件

``` sh
1. 本地仓库路径修改

  mkdir -p $MAVEN_HOME/repository
  vim $MAVEN_HOME/conf/settings.xml

  # 本地仓库路径
  <localRepository>/usr/local/maven/repository</localRepository>

```

### 3. Maven 集成到 eclipse

```
偏好设置 -> Mavan -> Installations -> add  : 添加 Maven 安装目录

                 -> User Settings -> User Settings -> Browse : 选择 Maven 配置文件

                                  -> Local Repository : 配置仓库目录,与环境变量 $M2_REPO 一致

```


## 三、使用案例

### 1. 运行

``` sh

* maven 阶段

1. 创建 Maven 项目
  1) mvn -B archetype:generate \
  -DarchetypeGroupId=com.angejia.dw \
  -DgroupId=com.angejia.dw.hive.udf \
  -DartifactId=my-maven-app

  2) 基于原型(模板工具) 创建项目
  官方文档: http://maven.apache.org/guides/introduction/introduction-to-archetypes.html
  # maven-archetype-quickstart (Java Project)
  # maven-archetype-webapp (Java Web Project)
  mvn archetype:generate \
  -DgroupId=com.yiibai.core \
  -DartifactId=ProjectName \
  -DarchetypeArtifactId=maven-archetype-quickstart \
  -DinteractiveMode=false

2. compile 编译
  mvn compile

3. test 测试单元
  mvn test 编译测试资源,并运行运行所有单元测试
  mvn test-compile 编译测试资源(但不执行测试)
  mvn -Dtest=AppTest test 运行单个类的测试单元

4. package 打包项目,更新代码重新运行
  mvn clean package 先清理后打包
  mvn package

  # 运行
  java -cp target/dw-hive-udf-1.0-SNAPSHOT.jar com.angejia.dw.hive.udf.App

5. install 将项目打包后安装到本地仓库，可以作为其它项目的本地依赖。
  mvn clean install 先清理后安装
  mvn install

6. clean 清理项目
  mvn clean


*. eclipse IDE 集成
  mvn eclipse:eclipse  第一次运行、更新 pox.xml 文件需要重新运行

*. 把 jar 包放到本地 repository 仓库中管理
  mvn install:install-file \
  -Dfile=/your-path/kaptcha-{version}.jar \
  -DgroupId=com.google.code \
  -DartifactId=kaptcha \
  -Dversion={version} \
  -Dpackaging=jar

  # pox.xml 使用
  <dependency>
      <groupId>com.google.code</groupId>
      <artifactId>kaptcha</artifactId>
      <version>2.3</version>
  </dependency>

*。 mvn 依赖管理

  1) 查看当前项目的依赖树
  mvn dependency:tree

  2) 查看当前项目的已解析的依赖
  mvn dependency:list

  3) 分析当前项目的依赖
  mvn dependency:analyze

  4) 强制更新 pox.xml
  mvn clean install -U
  mvn eclipse:eclipse  // 如果是 eclipse  项目则需要更新，执行此命令


*. 打印所有java系统属性和环境变量
  mvn help:system

```

### 2. pom.xml 文件详解

``` xml
<!--pom 顶级元素 -->
<project xmlns="http://maven.apache.org/POM/4.0.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://maven.apache.org/POM/4.0.0 http://maven.apache.org/maven-v4_0_0.xsd">
  <!-- POM 的对象模型是使用哪个版本 -->
  <modelVersion>4.0.0</modelVersion>
  <!-- 组织或集团的唯一标识符,通常是基于组织的完全限定域名。例如 org. .maven -->
  <groupId>com.mycompany.app</groupId>
  <!-- 组织下项目 ID, 例如 myapp-1.0.jar-->
  <artifactId>my-maven-app</artifactId>
  <!-- 表示要使用的包类型的工件(例如 JAR, WAR, EAR) -->
  <packaging>jar</packaging>
  <!-- 生成项目的版本
  1.0.2 (发布版本)
    1 大版本, 0 小版本, 2 Bug 和修订版本
  SNAPSHOT (项目阶段)
    SNAPSHOT < M1 < M2 < RC < GA < RELEASE
     SNAPSHOT 开发版本
     M[N] 里程碑版本(即将发布版本)
     RC 发布候选版本
     GA 基本可以用版本
     RELEASE 正式版本
 -->
  <version>1.0.1-SNAPSHOT</version>
  <!-- 用于项目的显示名称。这是常用于Maven生成文档 -->
  <name>my-maven-app</name>
  <!-- 表明项目的网站可以找到。这是常用于Maven生成文档 -->
  <url>http://maven.apache.org</url>
  <!-- 描述基本的项目 -->
  <dependencies>
    <!-- <dependency> 元素包含信息项目的依赖 -->
    <dependency>
      <!-- 依赖组织 -->
      <groupId>junit</groupId>
      <!-- 组织下的项目 id -->
      <artifactId>junit</artifactId>
      <!-- 版本 -->
      <version>4.11</version>
      <!-- 依赖的范围 :
        compile 编译和打包都需要(默认),
        provided 编译需要，打包不需要
        runtime 编译和打包都不需要, 在运行时需要,
        test  测试在单元测试中需要,
        -->
      <scope>compile</scope>
    </dependency>


    <!-- 定义一个外部依赖(不在 maven 仓库中) -->
    <dependency>
      <groupId>mydependency</groupId>
      <artifactId>mydependency</artifactId>
      <scope>scope</scope>
      <version>1.0</version>
      <systemPath>${basedir}\war\WEB-INF\lib\mydependency.jar</systemPath>
    </dependency>

  </dependencies>


  <build>
    <plugins>

      <!-- 指定 JDK 版本 -->
      <plugin>
          <groupId>org.apache.maven.plugins</groupId>
          <artifactId>maven-compiler-plugin</artifactId>
          <version>2.3.2</version>
          <configuration>
              <source>1.8</source>
              <target>1.8</target>
          </configuration>
      </plugin>

    </plugins>
  </build>



</project>

```



## 四、打包所有依赖 assembly

- [maven-assembly-plugin](http://maven.apache.org/plugins/maven-assembly-plugin/usage.html)
- [Maven 打包 resources 资源](http://bglmmz.iteye.com/blog/2063856)

### 1. assembly 命令

``` sh

mvn clean compile package  打包命令
mvn assembly:assembly
mvn eclipse:eclipse

打包后的 jar
  target/xxxx-jar-with-dependencies.jar  会以 jar-with-dependencies 结尾

```

### 2. 配置 maven-assembly-plugin 插件到 pox.xml

``` xml
<!--pom 顶级元素 -->
<project xmlns="http://maven.apache.org/POM/4.0.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://maven.apache.org/POM/4.0.0 http://maven.apache.org/maven-v4_0_0.xsd">
  <!-- POM 的对象模型是使用哪个版本 -->
  <modelVersion>4.0.0</modelVersion>
  <!-- 组织或集团的唯一标识符,通常是基于组织的完全限定域名。例如 org. .maven -->
  <groupId>com.mycompany.app</groupId>
  <!-- 组织下项目 ID, 例如 myapp-1.0.jar-->
  <artifactId>my-maven-app</artifactId>
  <!-- 表示要使用的包类型的工件(例如 JAR, WAR, EAR) -->
  <packaging>jar</packaging>
  <!-- 生成项目的版本 -->
  <version>1.0-SNAPSHOT</version>
  <!-- 用于项目的显示名称。这是常用于Maven生成文档 -->
  <name>my-maven-app</name>
  <!-- 表明项目的网站可以找到。这是常用于Maven生成文档 -->
  <url>http://maven.apache.org</url>


  <!-- 增加额外的源 -->
  <repositories>

    <repository>
        <id>Maven Repository</id>
        <url>http://repo.maven.apache.org/maven2</url>
    </repository>

    <repository>
        <id>Apache Repository</id>
        <url>https://repository.apache.org/content/repositories/releases</url>
    </repository>

    <repository>
        <id>JBoss Repository</id>
        <url>https://repository.jboss.org/nexus/content/repositories/releases/</url>
    </repository>

    <repository>
        <id>Cloudera Repository</id>
        <url>https://repository.cloudera.com/artifactory/cloudera-repos/</url>
    </repository>

  </repositories>



  <!-- 依赖管理 -->
  <dependencies>
    <!-- <dependency> 元素包含信息项目的依赖 -->
    <dependency>
      <!-- 依赖组织 -->
      <groupId>junit</groupId>
      <!-- 组织下的项目 id -->
      <artifactId>junit</artifactId>
      <!-- 版本 -->
      <version>4.11</version>
      <scope>scope</scope>
    </dependency>
  </dependencies>



  <build>

    <!-- 插件 -->
    <plugins>

      <!-- 打包插件 -->
      <plugin>
          <groupId>org.apache.maven.plugins</groupId>
          <artifactId>maven-compiler-plugin</artifactId>
          <version>2.3.2</version>
          <configuration>
              <source>1.8</source>
              <target>1.8</target>
          </configuration>
      </plugin>

      <!-- assembly 打包插件 -->
      <plugin>
        <artifactId>maven-assembly-plugin</artifactId>
        <version>2.6</version>
        <configuration>

          <!-- 打包依赖后的 jar 后缀名 -->
          <descriptorRefs>

            <descriptorRef>jar-with-dependencies</descriptorRef>
          </descriptorRefs>

          <!--描述文件路径
          <descriptors>
            <descriptor>src/assembly/assembly.xml</descriptor>
          </descriptors>
          -->

        </configuration>
        <executions>
          <execution>
            <id>make-assembly</id> <!-- this is used for inheritance merges -->
            <phase>package</phase> <!-- bind to the packaging phase -->
            <goals>
              <goal>single</goal>
            </goals>
          </execution>
        </executions>
      </plugin>

    </plugins>

  </build>

</project>

```
