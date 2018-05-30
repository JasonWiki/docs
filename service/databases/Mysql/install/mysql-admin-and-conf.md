# mysql 管理和配置

## 一、管理命令

``` sh

-- 查看 Mysql 进程列表
  SHOW PROCESSLIST;

-- 杀死指定进程
  kill [id]

-- 查看变量配置
  show variables like "%lower_case_table_names%";

-- 查看 Mysql 最大连接数
  show variables like '%max_connections%';

  # 永久生效 my.cnf
  max_connections=300

  # 当前进程生效
  set global max_connections=300

-- 服务相应用户最大连接数
  show global status like 'Max_used_connections';

  #  max_max_connections 合理设置范围(Max_used_connections 连接比例值要占 max_connections 10% 以上, 如果没有则表示 max_connections 设置过高)
  Max_used_connections / max_connections * 100% , 结果要在 10% 以上


-- 重新加载
   service mysqld reload

```

## 二、MySQL 配置

### 1. 配置模板

- 5.7

``` sh
## MySQL 5.7 Configuration File

[mysqld]

## General
user                                   = mysql
bind_address                           = 172.16.24.146
port                                   = 3306
basedir                                = /opt/app/mysql57/
datadir                                = /opt/app/mysql57/data/data
tmpdir                                 = /opt/app/mysql57/data/tmp
socket                                 = /opt/app/mysql57/data/logs/mysql.sock
pid-file                               = /opt/app/mysql57/data/logs/mysqld.pid

character_set_server                   = utf8
# SQL 支持模式
sql_mode                               = NO_ENGINE_SUBSTITUTION  # default "STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION
transaction_isolation                  = READ-COMMITTED    # default REPEATABLE-READ
explicit_defaults_for_timestamp        = ON     # default OFF
secure_file_priv                       =

skip-symbolic-links
skip_name_resolve                      = ON
skip_external_locking                  = ON

performance_schema                     = ON      # default ON
autocommit                             = ON      # default ON
# event_scheduler                        = ON     # default OFF
## 不区分大小写
lower_case_table_names                 = 1      # default 0
show_compatibility_56                  = ON      # >= 5.7.8 default OFF

## ssl
#ssl_ca                                 = /opt/app/mysql57/data/ssl/ca.pem
#ssl_cert                               = /opt/app/mysql57/data/ssl/server-cert.pem
#ssl_key                                = /opt/app/mysql57/data/ssl/server-key.pem

## Cache
thread_cache_size                      = 192     # since 5.6.8 default -1, autosized ( max_connections / 100 ) + 8
table_open_cache                       = 4096
table_definition_cache                 = 4096
table_open_cache_instances             = 8
query_cache_type                       = 0
query_cache_size                       = 0
#query_cache_size                       = 32M
#query_cache_limit                      = 1M
#query_cache_min_res_unit               = 2K

## Per_thread Buffers
sort_buffer_size                       = 32M     # default 256K
read_buffer_size                       = 16M     # default 256K
read_rnd_buffer_size                   = 32M     # default 256K
join_buffer_size                       = 128M    # default 256K
bulk_insert_buffer_size                = 64M
thread_stack                           = 256K    # default 192K

## Temp Tables
tmp_table_size                         = 512M
max_heap_table_size                    = 512M

## Networking
back_log                               = 1000
max_connections                        = 300
max_connect_errors                     = 1000000
interactive_timeout                    = 300
wait_timeout                           = 300
connect_timeout                        = 10
net_buffer_length                      = 1M
max_allowed_packet                     = 32M

## Sort
max_length_for_sort_data               = 2048    # default 1024
eq_range_index_dive_limit              = 200     # default 10

### Storage Engines
default_storage_engine                 = InnoDB

## MyISAM
key_buffer_size                        = 512M
myisam_sort_buffer_size                = 256M    # default 8M, for change index
myisam_max_sort_file_size              = 10G
myisam_repair_threads                  = 1
myisam_recover_options                 = default

## InnoDB
innodb_file_per_table                  = ON
innodb_file_format_check               = ON
# innodb缓冲池大小
innodb_buffer_pool_size                = 4G
innodb_buffer_pool_instances           = 8       # since 5.6.6 if innodb_buffer_pool_size < 1G default 1 else 8
innodb_data_file_path                  = ibdata1:100M:autoextend
innodb_log_group_home_dir              = /opt/app/mysql57/data/redolog/
innodb_log_file_size                   = 1G
innodb_log_files_in_group              = 2
innodb_log_buffer_size                 = 16M
innodb_undo_directory                  = /opt/app/mysql57/data/undolog/
innodb_undo_logs                       = 128     # defautl 128
innodb_undo_tablespaces                = 4       # default 3
innodb_open_files                      = 4000
innodb_thread_concurrency              = 32
innodb_flush_log_at_trx_commit         = 1
innodb_flush_log_at_timeout            = 1       # defautl 1, when innodb_flush_log_at_trx_commit = 0 or 2
innodb_purge_threads                   = 4
innodb_print_all_deadlocks             = ON
innodb_max_dirty_pages_pct             = 70
innodb_lock_wait_timeout               = 50
innodb_flush_method                    = O_DIRECT
innodb_old_blocks_time                 = 1000    # since 5.6.6 default 1000
innodb_io_capacity                     = 600     # default 200
innodb_io_capacity_max                 = 2000    # default 2000
innodb_lru_scan_depth                  = 1024    # default 1024
innodb_read_io_threads                 = 8
innodb_write_io_threads                = 8
innodb_buffer_pool_load_at_startup     = ON
innodb_buffer_pool_dump_at_shutdown    = ON
innodb_buffer_pool_filename            = ib_buffer_pool  # default ib_buffer_pool
innodb_sort_buffer_size                = 64M     # default 1M , 64K - 64M , for change index

innodb_buffer_pool_dump_pct            = 40
innodb_page_cleaners                   = 16
innodb_undo_log_truncate               = ON
innodb_max_undo_log_size               = 2G
innodb_purge_rseg_truncate_frequency   = 128

## Replication
server_id                              = 146
log_bin                                = /opt/app/mysql57/data/binlog/mysql_bin
expire_logs_days                       = 3
binlog_format                          = ROW
binlog_row_image                       = noblob  # default full
#innodb_autoinc_lock_mode               = 2      # default 1
binlog_rows_query_log_events           = 1
max_binlog_size                        = 500M
binlog_cache_size                      = 1M
sync_binlog                            = 1
#gtid_mode                              = ON
#enforce_gtid_consistency               = ON
#binlog_gtid_simple_recovery            = 1

master_info_repository                 = TABLE
relay_log_info_repository              = TABLE

#skip-slave-start                       = 1
#relay_log                              = /opt/app/mysql57/data/relaylog/relay_log
#max_relay_log_size                     = 500M   # default 0, use max_binlog_size
#read_only                              = ON
#log_slave_updates                      = ON
#relay_log_purge                        = 1
#relay_log_recovery                     = 1
#slave_net_timeout                      = 60
#replicate_wild_do_table                = mysql.%
#replicate_wild_ignore_table            = test.%
#auto_increment_offset                  = 1
#auto_increment_increment               = 2
#plugin_dir                             = /opt/app/mysql57/lib/plugin
#plugin_load                            = "rpl_semi_sync_master=semisync_master.so;rpl_semi_sync_slave=semisync_slave.so"
#rpl_semi_sync_master_enabled           = ON
#rpl_semi_sync_slave_enabled            = ON
#rpl_semi_sync_master_timeout           = 1000
#slave_parallel_type                    = LOGICAL_CLOCK
#slave_parallel_workers                 = 16
#slave_preserve_commit_order            = 1
#slave_transaction_retries              = 128

## Logging
log_output                             = FILE
# 慢查询
slow_query_log                         = ON
# 慢查询日志
slow_query_log_file                    = /opt/app/mysql57/data/logs/slow_mysqld.log
log_queries_not_using_indexes          = OFF     # default OFF
log_throttle_queries_not_using_indexes = 10      # default 0
min_examined_row_limit                 = 100     # default 0
log_slow_admin_statements              = ON
log_slow_slave_statements              = ON
long_query_time                        = 1
#log-short-format                       = 0
log_error                              = /opt/app/mysql57/data/logs/error_mysqld.log
#general_log                            = ON
#general_log_file                       = /opt/app/mysql57/data/logs/general_mysqld.log
log_timestamps                         = system
log-queries-not-using-indexes
log-slow-admin-statements

## Index
ft_min_word_len                        = 4

[mysqld_safe]
open_files_limit                       = 65535

[mysql]
no_auto_rehash
prompt                                 = "MySQL [\\d] > "

[mysqldump]
quick
max_allowed_packet                     = 32M

[myisamchk]
key_buffer_size                        = 64M
sort_buffer_size                       = 32M
read_buffer                            = 8M
write_buffer                           = 8M

[mysqlhotcopy]
interactive_timeout

[client]
socket                                 = /opt/app/mysql57/data/logs/mysql.sock

```


### 2. 实例配置

``` sh
[mysqld]
user                                   = mysql
bind_address                           = 0.0.0.0

# 大小写忽略
lower_case_table_names                 = 1

# 最大连接数
max_connections                        = 300

sql_mode                               = NO_ENGINE_SUBSTITUTION

basedir                                = /opt/app/mysql57/
datadir                                = /opt/app/mysql57/data/data
tmpdir                                 = /opt/app/mysql57/data/tmp
socket                                 = /opt/app/mysql57/data/logs/mysql.sock
pid-file                               = /opt/app/mysql57/data/logs/mysqld.pid


# 慢查询
slow_query_log                         = ON
# 慢查询日志
slow_query_log_file                    = /opt/app/mysql57/data/logs/slow_mysqld.log
log_queries_not_using_indexes          = OFF     # default OFF
log_throttle_queries_not_using_indexes = 10      # default 0
min_examined_row_limit                 = 100     # default 0
log_slow_admin_statements              = ON
log_slow_slave_statements              = ON
long_query_time                        = 1
#log-short-format                       = 0
log_error                              = /opt/app/mysql57/data/logs/error_mysqld.log
#general_log                            = ON
#general_log_file                       = /opt/app/mysql57/data/logs/general_mysqld.log
log_timestamps                         = system
log-queries-not-using-indexes
log-slow-admin-statements
```
