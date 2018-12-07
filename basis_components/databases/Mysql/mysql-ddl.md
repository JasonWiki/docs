# Mysql dml 数据定义语言

## 表结构操作

``` sql

* 创建数据库
  CREATE DATABASE `test_demo` /*!40100 DEFAULT CHARACTER SET utf8 */
  CREATE DATABASE IF NOT EXISTS test_demo DEFAULT CHARACTER SET = utf8mb4;

* 创建表
  CREATE TABLE `demo_test` (
    `id` int(10) unsigned NOT NULL  AUTO_INCREMENT COMMENT '主键',
    `db_name` char(20) NOT NULL DEFAULT '' COMMENT '数据库名',
    `tb_name` char(20) NOT NULL DEFAULT '' COMMENT '数据表名',
    `im_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '导入方式',
    `is_snapshot` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否快照',
    `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
    `CREATE_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建日期',
    PRIMARY KEY (`id`),
    KEY `idx_qy` (`db_name`,`tb_name`,`is_delete`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8


* 复制表
	CREATE TABLE t2 like t1;							//创建表复制t1数据结构
	insert into t2 select * from t1;				//把t1表数据放入t2表中


* 字段操作
	表开头添加自增主键
		ALTER TABLE `test`
		ADD `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST
	表结尾添加字段
		ALTER TABLE `test` ADD `t` VARCHAR( 255 ) NOT NULL DEFAULT ''
	某个位置添加字段
		ALTER TABLE
			`test`
		ADD t_1 varchar(255) NOT NULL DEFAULT '' AFTER a,
		ADD t_2 varchar(255) NOT NULL DEFAULT '' AFTER t_1;
	删除字段
		ALTER TABLE `user_movement_log`
			DROP column `Gatewayid`,
			DROP column `Gatewayi2`;



```


## * 索引

- 3个索引中只能用一种索引

```sql
	- 主键联合索引，不允许重复
	PRIMARY KEY (`date_INDEX`,`class_id`,`city_id`,`item_id`),

	- 索引
	KEY `idx_item_id` (`item_id`),

	- 联合索引
	KEY `idx_class_item` (`class_id`,`item_id`)


  //创建表时的索引
  	CREATE TABLE `cms_topic_tags` (

  	  `id` int(10) NOT NULL AUTO_INCREMENT,

  	  `city_id` int(10) NOT NULL DEFAULT '0' COMMENT '城市',

       -- 主键索引
       PRIMARY KEY (`id`),

       -- //联合索引
  	   KEY `city_id` (`city_id`,`identifier`(25))

       -- 单个字段的索引建议 用跟字段名相同的名字 ,25表示索引的长度。字符串
  	   KEY `id` (`id`(25))		
  	) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8

  //创建表时, 添加索引

  	CREATE INDEX 索引名 ON 表名（字段名1，字段名2）

  	CREATE INDEX idx_ac_st ON app_users(account(11),is_del)

  //修改表时, 增加索引
    -- 普通和联合索引
  	ALTER TABLE 表名 add INDEX 索引名 (字段1,字段2) ;

    -- 唯一索引
  	ALTER TABLE 表名 add UNIQUE (字段) ;		

    -- 主键索引        
  	ALTER TABLE 表名 add PRIMARY KEY (字段) ;	     

    -- 案例
  	ALTER TABLE  `app_user_advertisement` ADD INDEX idx_aaa(  `users_id` ) 添加索引
  	ALTER TABLE  `app_users` ADD UNIQUE (`account`)

  //删除索引
  	ALTER TABLE 表名 DROP INDEX 索引名;
  	DROP INDEX 索引名 ON 表名 ;
```


## * 存储过程

- 预先定义好SQL语句，在使用是调用存储过程
- 调用存储过程
	- EXECUTE 存储过程名


## * 触发器（DML和DDL触发器）

- 当表中出现增、删、改时，执行的SQL语句


## * 游标
- 对查找出的结果集进行逐行处理
