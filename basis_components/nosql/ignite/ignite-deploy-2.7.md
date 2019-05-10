# ignite 部署文档

## 简介

- 说明

``` doc
内存为中心的数据库，缓存和处理平台

用于事务，分析和流式工作负载的以内存为中心的分布式数据库，缓存和处理平台，以PB级的速度提供内存速度
```


## 下载

- [下载地址](http://www.google.com)

``` sh

```

## 安装

```sh

bin/ignite.sh examples/config/example-ignite.xml

```

## 配置

``` xml


<beans xmlns="http://www.springframework.org/schema/beans"
       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xsi:schemaLocation="
       http://www.springframework.org/schema/beans
       http://www.springframework.org/schema/beans/spring-beans.xsd">
    <!--
        Alter configuration below as needed.
    -->
    <bean id="grid.cfg" class="org.apache.ignite.configuration.IgniteConfiguration"/>
</beans>


<bean class="org.apache.ignite.configuration.IgniteConfiguration">

  <!-- 集群部署配置 -->
  <property name="discoverySpi">

    <!-- zookeeper 探测器 -->
    <bean class="org.apache.ignite.spi.discovery.zk.ZookeeperDiscoverySpi">
      <property name="zkConnectionString" value="127.0.0.1:34076,127.0.0.1:43310,127.0.0.1:36745"/>
      <property name="sessionTimeout" value="30000"/>
      <property name="zkRootPath" value="/apacheIgnite"/>
      <property name="joinTimeout" value="10000"/>
    </bean>

    <!-- 组播和静态IP探测器
    <bean class="org.apache.ignite.spi.discovery.tcp.TcpDiscoverySpi">
      <property name="ipFinder">
        <bean class="org.apache.ignite.spi.discovery.tcp.ipfinder.multicast.TcpDiscoveryMulticastIpFinder">
          <property name="multicastGroup" value="228.10.10.157"/>

          <property name="addresses">
            <list>
              <value>1.2.3.4</value>
              <value>1.2.3.5:47500</value>
            </list>
          </property>

        </bean>
      </property>
    </bean>
    -->

    <!-- JDBC探测器
    <bean class="org.apache.ignite.spi.discovery.tcp.TcpDiscoverySpi">
      <property name="ipFinder">
        <bean class="org.apache.ignite.spi.discovery.tcp.ipfinder.jdbc.TcpDiscoveryJdbcIpFinder">
          <property name="dataSource" ref="ds"/>
        </bean>
      </property>
    </bean>
    -->

    <!-- 基于共享文件系统探测器
    <bean class="org.apache.ignite.spi.discovery.tcp.TcpDiscoverySpi">
     <property name="ipFinder">
       <bean class="org.apache.ignite.spi.discovery.tcp.ipfinder.sharedfs.TcpDiscoverySharedFsIpFinder">
         <property name="path" value="/var/ignite/addresses"/>
       </bean>
     </property>
   </bean>
   -->

  </property>

  <property name="userAttributes">
    <map>
      <entry key="ROLE" value="worker"/>
    </map>
  </property>


  <property name="cacheConfiguration">
    <bean class="org.apache.ignite.configuration.CacheConfiguration">
      <!-- Set a cache name. -->
      <property name="name" value="cacheName"/>
      <!-- Set cache mode. -->
      <property name="cacheMode" value="PARTITIONED"/>
    </bean>
    
  </property>


</bean>
```

## 部署

### 1. 准备工作

``` sh

zookeeper-s1


```

### 2. 安装部署

``` sh

```
