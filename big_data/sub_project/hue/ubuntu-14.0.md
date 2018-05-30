# hue ubuntu 14.0 安装

- [hue ubuntu 14.0](http://gethue.com/how-to-build-hue-on-ubuntu-14-04-trusty/)

## 安装流程

``` sh

1. 安装依赖包
  apt-get install python2.7-dev \
  make \
  libkrb5-dev \
  libxml2-dev \
  libxslt-dev \
  libsqlite3-dev \
  libssl-dev \
  libldap2-dev \
  python-pip \
  libgmp3-dev

2. 根目录下编译
  cd hue
  make apps
  如果有错误参考 hue ubuntu 14.0 文章

3.修改配置文件(修改前先备份)

  vim desktop/conf/hue.ini
  http://ju.outofmemory.cn/entry/105162

4.启动
  cd hue
  $HUE_HOME/build/env/bin/hue runserver 0.0.0.0:8888

5.如果其他账号使用，需要在 hdfs 创建对应账号的用户目录
  hadoop dfs -mkdir /user/jason
  hadoop dfs -chown jason:jason /user/jason
```


## 配置成 MYSQL

``` sql

准备工作：
  1. mysql 中创建数据库 hue
  2. mysql 分配远程登录账号，可以直接访问 hue 数据

cd $HUE_HOME

1. 修改配置文件配置文件 ./desktop/conf/hue.ini
[[database]]
    # Database engine is typically one of:
    # postgresql_psycopg2, mysql, sqlite3 or oracle.
    #
    # Note that for sqlite3, 'name', below is a path to the filename. For other backends, it is the database name.
    # Note for Oracle, options={'threaded':true} must be set in order to avoid crashes.
    # Note for Oracle, you can use the Oracle Service Name by setting "port=0" and then "name=<host>:<port>/<service_name>".
    engine=mysql
    host=
    port=3306
    user=
    password=
    name=hue
    options={}

2. 创建表以及表结构
  ./build/env/bin/hue syncdb --noinput
  ./build/env/bin/hue migrate

3. 删除外键 （仅限 InnoDB）
  SHOW CREATE TABLE auth_permission;
  ALTER TABLE auth_permission DROP FOREIGN KEY content_type_id_refs_id_XXXXXX;

4. 删除 django_content_type 表中的行。
  DELETE FROM django_content_type;

5. 添加外键 (仅限 InnoDB）。
  ALTER TABLE auth_permission ADD FOREIGN KEY (`content_type_id`) REFERENCES `django_content_type` (`id`)

6. 启动服务
  $HUE_HOME/build/env/bin/hue runserver 0.0.0.0:8888
```
