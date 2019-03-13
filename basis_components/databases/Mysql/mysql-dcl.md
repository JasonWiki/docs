# mysql DCL 授权语句

## 一、创建用户

``` sh
语法：{username}用户名 @ {host}可访问地址 {password}密码
CREATE USER 'username'@'host' IDENTIFIED BY 'password';

1) 创建任意远程连接账号
  CREATE USER 'user1'@'%' IDENTIFIED BY  '123456'

2) 创建本地连接账号
  CREATE USER 'user1'@'localhost' IDENTIFIED BY '123456';

3) 创建指定host远程连接账号, 如果用于代理, 则只需要授权代理服务器即可. 代理服务能访问 mysql 服务器
  CREATE USER 'user1'@'180.166.126.94' IDENTIFIED BY '123456';

4) 删除用户
  drop user 'user1'@'180.166.126.94';

```

# 二、授权用户

- 授权本身，如果账号不存在也会创建账号，所以用授权来创建账号也不错
- [授权文章](http://blog.csdn.net/andy_yf/article/details/7487519)

``` sql
语法：{*.*} 数据库.表
  GRANT ALL PRIVILEGES ON *.* TO '用户名'@'%' IDENTIFIED BY '登录密码' WITH GRANT OPTION;

1) 创建 test3 账号，授予任意host登录权限，并且授予所有数据库访问权限

  GRANT ALL PRIVILEGES ON *.* TO 'user1'@'%' IDENTIFIED BY '123456' WITH GRANT OPTION;


2) 授权 test1 账号 test 数据库权限

  GRANT ALL PRIVILEGES ON  test.* TO  'user1'@'localhost' WITH GRANT OPTION ;


3) 授权指定权限 (SELECT,INSERT,UPDATE,DELETE,CREATE,DROP)

  GRANT SELECT ON *.* to 'readonly'@'%' WITH GRANT OPTION;

  GRANT ALL PRIVILEGES ON pm.* TO 'hadoop'@'%' WITH GRANT OPTION;

  GRANT SELECT ON pm.* TO 'dev'@'%' WITH GRANT OPTION


4) 授权指定权限给函数权限
  GRANT execute ON database.* to readonly@'%';


5) 权限
  INSERT,DELETE,UPDATE,SELECT,EXECUTE,CREATE,ALTER,DROP,INDEX

  EVENT,CREATE VIEW,FILE,LOCK TABLES,PROCESS,REFERENCES,REPLICATION CLIENT

  更多详见: https://dev.mysql.com/doc/refman/8.0/en/privileges-provided.html


6) mysql 8.0
  # 创建用户
  create user 'user1'@'xxx.com' IDENTIFIED BY 'xxx.passwd.com' ;

  # 修改加密方式， 加密方式有 caching_sha2_password, mysql_native_password
  ALTER USER 'user1'@'xxx.com' IDENTIFIED WITH mysql_native_password BY 'xxx.passwd.com';

  # 授权账号, 创建和授权要分开(命令行登录授权)
  GRANT INSERT,DELETE,UPDATE,SELECT,CREATE,ALTER,DROP ON `test.*` TO 'user1'@'xxx.com';
```

# 三、用户操作

``` sql
--
SELECT DISTINCT CONCAT('User: ''',user,'''@''',host,''';') AS query FROM mysql.user;

-- 修改用户密码
UPDATE mysql.user SET password=password("2345.com") WHERE user="root";

-- 删除用户
DELETE FROM mysql.user WHERE user='';

-- 修改内容刷新
flush privileges;

```
