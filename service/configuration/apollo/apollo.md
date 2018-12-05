# apollo 分布式部署配置中心


## 模块修改

/opt/logs/service/apollo/

### apollo-portal
  conf/
    apollo-portal.conf
      LOG_FOLDER=/opt/logs/service/apollo/portal/

  resources/
    application.yml
      logging:
        file: /opt/logs/service/apollo/portal/apollo-portal.log
      server:
        port: 20100 (old: 8070)

  scripts/
    startup.sh
      LOG_DIR= /opt/logs/service/apollo/portal
      SERVER_PORT=20100


### apollo-configservice
  conf/
    apollo-configservice.conf
      LOG_FOLDER=/opt/logs/service/apollo/configservice/

  resources/
    application.yml
      logging:
        file: /opt/logs/service/apollo/configservice/apollo-configservice.log
      server:
        port: 20101 (old: 8080)

    configservice.properties
      logging.file= /opt/logs/service/apollo/configservice/apollo-configservice.log
      server.port= 20101

    scripts/
      startup.sh
        LOG_DIR= /opt/logs/service/apollo/configservice
        SERVER_PORT=20101


### apollo-adminservice
  conf/
    apollo-adminservice.conf
      LOG_FOLDER=/opt/logs/service/apollo/adminservice/

  resources/
    application.yml
      logging:
        file: /opt/logs/service/apollo/adminservice/apollo-adminservice.log
      server:
        port: 20102

    configservice.properties
      logging.file= /opt/logs/service/apollo/adminservice/apollo-adminservice.log
      server.port= 20102 (old: 8090)

  scripts/
    startup.sh
      LOG_DIR= /opt/logs/service/apollo/adminservice
      SERVER_PORT=20102


## 数据库配置

### 创建数据库和用户
不区分大小写 lower_case_table_names                 = 1


创建 apollo_config_db 数据库
CREATE DATABASE IF NOT EXISTS apollo_config_db DEFAULT CHARACTER SET = utf8mb4;
GRANT ALL PRIVILEGES ON  apollo_config_db.* TO  'apollo'@'172.16.24.140' IDENTIFIED BY 'apollo_818' WITH GRANT OPTION ;
GRANT ALL PRIVILEGES ON  apollo_config_db.* TO  'apollo'@'172.16.24.151' IDENTIFIED BY 'apollo_818' WITH GRANT OPTION ;
GRANT ALL PRIVILEGES ON  apollo_config_db.* TO  'apollo'@'172.16.24.156' IDENTIFIED BY 'apollo_818' WITH GRANT OPTION ;


创建 apollo_portal_db 数据库
CREATE DATABASE IF NOT EXISTS apollo_portal_db DEFAULT CHARACTER SET = utf8mb4;
GRANT ALL PRIVILEGES ON  apollo_portal_db.* TO  'apollo'@'172.16.24.140' IDENTIFIED BY 'apollo_818' WITH GRANT OPTION ;
GRANT ALL PRIVILEGES ON  apollo_portal_db.* TO  'apollo'@'172.16.24.151' IDENTIFIED BY 'apollo_818' WITH GRANT OPTION ;
GRANT ALL PRIVILEGES ON  apollo_portal_db.* TO  'apollo'@'172.16.24.156' IDENTIFIED BY 'apollo_818' WITH GRANT OPTION ;

### 数据库配置

需要注意的是每个环境只填入自己环境的eureka服务地址，

 按照目前的实现，apollo-configservice 本身就是一个 eureka 服务

serverconfig
  | key | Cluster | Value | Comment |
  eureka.service.url	default	http://1.1.1.1:8080/eureka/	默认的Eureka服务Url


vim config/application-github.properties
# DataSource
spring.datasource.url = jdbc:mysql://app1:3306/apollo_config_db?characterEncoding=utf8
spring.datasource.username = apollo
spring.datasource.password = apollo_818
