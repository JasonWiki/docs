# MySql 主从复制数据负载均衡技术

## todolist

- 1、开启慢查询日志
- 2、开启innodb引擎的分区存储
- 3、开启bin-log 二进制日志，用于数据恢复

## 一、主从复制，Mysql负载均衡

### 2、主从服务器配置

``` sh

			#主从服务器配置，主服务器用于写数据，从服务器用于读数据。
				数据实时更新要求高：	依旧从主服务器读取数据
				数据实时更新要求不高： 用从服务器读取数据，

		 	#配置流程
			1) 在主服务中，配置
				vi /etc/my.cnf
				log-bin=mysql-bin					//写在[mysqld] 下
				server-id=1							//设置主服务器ID，千万不要写错了

				mysql>grant all on *.* to user1@192.168.10.2 identified by "456"		//授权一个用户，给从服务器登陆使用
				mysqldump -uroot -p123 test -l -F -> ./test.sql			//主服务器上备份数据
				mysql -uroot -p123 test -v -f < ./test.sql					//从服务器上恢复数据

			2)	从服务器
				vi /etc/my.cnf
				log-bin=mysql-bin						//写在[mysqld] 下
				server-id=2									//设置从服务器ID
				master-host=192.168.10.1		//需要同步的主服务器Ip
				master-host=user1						//授权账号
				master-host=123						//授权密码
				master-port=3306						//数据库端口

				service mysqld restart				//重新启动服务器
				mysql>show slave status\G		//查看与主服务器是否同步
					#状态
					Slave_IO _Running:YES		//表示已与主服务器同步
					Slave_SQL_Running:YES	//表示与主服务器同步的SQL语句可以执行成功

```

## 二、恢复、备份

``` sql
	1、主服务器授权一个远程登陆用户
		1) mysql>grant all PRIVILEGES on *.* to user1@% identified by "123456"	//授权所有权限(all)，所有表和库(*.*)，给user1用户登陆,user1登陆的ip为任意，登陆密码为123456

		2) 远程登陆测试
			mysql -uuser1 -p456 -h192.168.10.1
			mysql>select database();			//产看当前所在数据库
			mysql>show tables;					//查看当前数据库的所有表，无 表为Empty Set
			mysql>create table t1(id int)		//创建测试表

		3) Mysql bin-log 二进制日志（用于数据恢复）
			a) 开启日志
				vi /etc/my.cnf
				log-bin=mysql-bin					//写在[mysqld] 下
				service mysqld restart 			//重启Mysql
				#进入mysql 后  输入：
				mysql>show variables like "%bin%";			//查看log_bin 是否为ON，为ON则开启了
			b) 命令
				mysql>flush logs;							//重新生成一个新的日志
				mysql>show master status;			//查看最后一个bin日志
				mysql>reset master;						//清空所有的bin-log日志
				//查看mysql-bin-log 二进制日志文件的其中一个
				mysqlbinlog --no-defaults /var/lib/mysql/mysql-bin.000001			//查看二进制文件日志，一些增删改

			c) 数据备份与恢复
				mysqldump -uroot -p123 test -l -F -> ./test.sql				//备份test数据库,并且更新bin-log日志
				mysql -uroot -p123 test -v -f < ./test.sql							//把数据恢复到test数据库

				//通过查看日志文件来选择恢复点
				mysqlbinlog --no-defaults /var/lib/mysql/mysql-bin.000002 --start-position="418" --stop-position="723"

				//恢复一个时间段的数据到test数据库
				mysqlbinlog --no-defaults /var/lib/mysql/mysql-bin.000002 --start-position="418" --stop-position="723" |
				mysql -uroot -p123 test

			d) 当表被删除、表内数据丢失时，用备份过得数据、和bin-log日志来恢复
				#数据恢复前，先用mysql>flush logs;重新生成一个日志
				i) mysql -uroot -p123 test -v -f < ./test.sql							//把数据恢复到test数据库
				ii) mysqlbinlog --no-defaults /var/lib/mysql/mysql-bin.000002 --start-position="418" --stop-position="723" |
					mysql -uroot -p123 test


```

## 三、mysql数据库优化

``` sql
	1、代码
		mysql>desc select * from t1\G;						//查看sql语句影响的行数，用于性能测试

	2、索引
		mysql>show index from t1;			//查看表索引
		//创建
			mysql>ALTER TABLE  t1 ADD INDEX (  id )	;						//创建索引
			mysql>ALTER TABLE  t1 ADD UNIQUE  (  id )	;					//创建唯一索引
			mysql>ALTER TABLE  t1 ADD PRIMARY KEY (  `id` );			//创建主键索引
		 //删除
			mysql>alter table aaa drop index id;
			mysql>alter table aaa drop index PRIMARY KEY

		//创建表时的索引
			CREATE TABLE `cms_topic_tags` (
			  `id` int(10) NOT NULL AUTO_INCREMENT,
			  `city_id` int(10) NOT NULL DEFAULT '0' COMMENT '城市',
			   PRIMARY KEY (`id`),
			   KEY `city_id` (`city_id`,`identifier`(25))	//联合索引
			   KEY `identifier` (`identifier`(25))		//单个字段的索引建议 用跟字段名相同的名字 ,25表示索引的长度。字符串
			) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8

			//添加索引
			创建表时
			create index 索引名 on 表名（字段名1，字段名2）
			create index idx_ac_st on app_users(account(11),is_del)
			已经有表的
			alter table 表名 add index 索引名 (字段1,字段2) ;//普通和联合索引
			alter table 表名 add unique (字段) ;		//唯一索引
			alter table 表名 add primary key (字段) ;	//主键索引
			如：
			alter table  `app_user_advertisement` ADD INDEX idx_aaa(  `users_id` ) 添加索引
			alter table  `app_users` ADD UNIQUE (`account`)


			//删除索引
			alter table 表名 drop index 索引名;
			或者
			drop index 索引名 on 表名 ;



	3、注意事项
		1) 索引生效
			a) 当sql中有比较符号(and or)时，where 后的所有字段都要加索引。
			b) like 字符匹配时， like 'aaa%';		//才会有效
			c) 查询带索引的地段时，查询的数据类型，要跟字段数据类型相吻合。

	4、开启慢查询日志
		1) 配置
			mysql>show variables like "%slow%";		//log-slow-queries 为 ON 表示开启
			vi /etc/my.cnf		//添加如下到[mysqld]
				[mysqld]下
				log-slow-queries=E:/wamp/bin/mysql/mysql5.5.24/data/slow.log					//日志保存位置
				long_query_time = 1																						//超出秒数后记录
				log-queries-not-using-indexes (log下来没有使用索引的query,可以根据情况决定是否开启)
				log-long-format 所有没有使用索引的查询也将被记录

		2) 分析日志
			 常用命令
			-s ORDER what to sort by (t, at, l, al, r, ar etc), 'at’ is default
			-t NUM just show the top n queries
			-g PATTERN grep: only consider stmts that include this string

			eg:
			s，是order的顺序，说明写的不够详细，俺用下来，包括看了代码，主要有 c,t,l,r和ac,at,al,ar，分别是按照query次数，时间，lock的时间和返回的记录数来排序，前面加了a的时倒序 -t，是top n的意思，即为返回前面多少条的数据 -g，后边可以写一个正则匹配模式，大小写不敏感的

			*) mysqldumpslow -s c -t 20 slow.log	//访问次数最多的20个sql语句

			*) mysqldumpslow -s r -t 20 slow.log	//返回记录集最多的20个sql。

			mysqldumpslow -t 10 -s t -g “left join” slow.log
			这个是按照时间返回前10条里面含有左连接的sql语句。


		3) 查看
			Query_time: 372(用了372秒) Lock_time: 136(锁了136秒) Rows_sent: 152（返回152行） Rows_examined: 263630（查了263630行）
			select id, name from manager where id in (66,10135);

	5、监控
		mysql>show status like 'Handler_read%';				//其中Handler_read_rnd_next的值越高，数据库又很慢的话，通过查看慢查询日志来分析数据，加索引。

		mysql>desc select * from t1\G;			//通过SQL分析，开查看SQL语句的优化性能，具体数值对照图标


	6、mysql -h192.168.1.103 -ucaixh -pcaixh123		远程登录
```
