# 开发环境配置

## 思路

- 下载软件包
- 配置插件
- 添加 Server

## 下载软件包

- [Eclipse IDE for Java EE Developers](http://www.eclipse.org/downloads/packages/eclipse-ide-java-ee-developers/marsr) Eclipse J2ee IDE
- [Tomcat 7.0.62 Released](http://tomcat.apache.org/download-70.cgi) Tomcat Web 服务器
  ``` java
  注意下载二进制包 :
  Binary Distributions
    - Core
    - tar.gz(http://mirrors.hust.edu.cn/apache/tomcat/tomcat-7/v7.0.73/bin/apache-tomcat-7.0.73.tar.gz)
  ```

## 配置 Eclipse

- [tomcatPlugin](http://www.eclipsetotale.com/tomcatPlugin.html) Eclipse 的 tomcat 插件
```
1.下载完成解压获得 jar 包,放到 Eclipse "安装包的" plugins 目录中
2.重启 Eclipse
3.然后打开 Eclipse 的首选项，找到
  Tomcat
    Tomcat Home : 这里选择下载的 Tomcat 二进制文件目录
```


## Server

```
File
  Other
    Server
      Server
        配置下载的 Tomcat 地址即可

```
