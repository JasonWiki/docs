# 

- https://projects.spring.io/spring-cloud/
- https://github.com/spring-cloud/spring-cloud-release/releases

The Finchley builds and works with Spring Boot 2.0.x, and is not expected to work with Spring Boot 1.5.x.-- Finchley构建并使用Spring Boot 2.0.x，并且不期望与Spring Boot 1.5.x一起使用。

The Dalston and Edgware release trains build on Spring Boot 1.5.x, and are not expected to work with Spring Boot 2.0.x. -- Dalston和Edgware建立在Spring Boot 1.5.x上，并不期望与Spring Boot 2.0.x一起使用。

The Camden release train builds on Spring Boot 1.4.x, but is also tested with 1.5.x. -- Camden搭载Spring Boot 1.4.x，但也经过1.5.x测试。

The Brixton release train builds on Spring Boot 1.3.x, but is also tested with 1.4.x. -- Brixton搭载Spring Boot 1.3.x，但也经过1.4.x测试。

The Angel release train builds on Spring Boot 1.2.x, and is incompatible in some areas with Spring Boot 1.3.x. Brixton builds on Spring Boot 1.3.x and is similarly incompatible with 1.2.x. Some libraries and most apps built on Angel will run fine on Brixton, but changes will be required anywhere that the OAuth2 features from spring-cloud-security 1.0.x are used (they were mostly moved to Spring Boot in 1.3.0).
 



Spring Cloud Config(Spring)

    配置管理工具包，让你可以把配置放到远程服务器，集中化管理集群配置，目前支持本地存储、Git 以及 Subversion。


Spring Cloud Netflix 核心组件
	
	Eureka(Netflix) [,jʊ(ə)'riːkə] 

	    云端服务发现，一个基于 REST 的服务，用于定位服务，以实现云端中间层服务发现和故障转移。

	Ribbon(Netflix) ['rɪbən]

		提供云端负载均衡，有多种负载均衡策略可供选择，可配合服务发现和断路器使用。

		Spring cloud 有两种服务调用方式，一种是 ribbon + restTemplate, 另一种是 feign

	Feign(Netflix)
	
		Feign 是一种声明式、模板化的HTTP客户端。 Feign 默认集成了 Ribbon，并和 Eureka 结合，默认实现了负载均衡的效果。

		Feign 采用的是基于接口的注解

		Feign 整合了ribbon
 
	Hystrix(Netflix)

    	熔断器，容错管理工具，旨在通过熔断机制控制服务和第三方库的节点,从而对延迟和故障提供更强大的容错能力。

	Zuul(Netflix)

		Zuul 是在云平台上提供动态路由,监控,弹性,安全等边缘服务的框架。Zuul 相当于是设备和 Netflix 流应用的 Web 网站后端所有请求的前门。

	Archaius(Netflix) [a:kei s]

		配置管理 API，包含一系列配置管理API，提供动态类型化属性、线程安全配置操作、轮询框架、回调机制等功能。


Spring Cloud Bus(Spring)

    事件、消息总线，用于在集群（例如，配置变化事件）中传播状态变化，可与 Spring Cloud Config 联合实现热部署。


Spring Cloud Cluster

	提供 Leadership 选举，如：Zookeeper, Redis, Hazelcast, Consul 等常见状态模式的抽象和实现。


Spring Cloud for Cloud Foundry(Pivotal)

	通过 Oauth2 协议绑定服务到 CloudFoundry，CloudFoundry 是 VMware 推出的开源 PaaS 云平台。

Spring Cloud Consul(HashiCorp)

	封装了 Consul 操作，Consul 是一个服务发现与配置工具，与 Docker 容器可以无缝集成。


Spring Cloud Sleuth(Spring)

	日志收集工具包，封装了 Dapper 和 log-based 追踪以及 Zipkin 和 HTrace 操作，为 SpringCloud 应用实现了一种分布式追踪解决方案。


Spring Cloud Data Flow(Pivotal)

	大数据操作工具，作为 Spring XD 的替代产品，它是一个混合计算模型，结合了流数据与批量数据的处理方式。


Spring Cloud Security(Spring)

	基于 spring security 的安全工具包，为你的应用程序添加安全控制。


Spring Cloud Zookeeper

	操作 Zookeeper 的工具包，用于使用 zookeeper 方式的服务发现和配置管理。


Spring Cloud Stream

	数据流操作开发包，封装了与 Redis, Rabbit、Kafka 等发送接收消息。


Spring Cloud CLI

	基于 Spring Boot CLI，可以让你以命令行方式快速建立云组件。


Spring Cloud Task

	提供云端计划任务管理、任务调度。


Spring Cloud Connectors

	便于云端应用程序在各种PaaS平台连接到后端，如：数据库和消息代理服务。


Spring Cloud Starters

	Spring Boot 式的启动项目，为 Spring Cloud 提供开箱即用的依赖管理。


Turbine(Netflix) ['tɜːbaɪn; -ɪn]

	Turbine 是聚合服务器发送事件流数据的一个工具，用来监控集群下 hystrix 的 metrics 情况。

