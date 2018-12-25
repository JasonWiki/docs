# 分布式锁

## 创建分布式锁表

``` sql
-- 创建分布式锁表
CREATE TABLE `distributed_lock` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `application_id` varchar(64) NOT NULL DEFAULT '' COMMENT '应用 id',
  `resource_id` varchar(64) NOT NULL DEFAULT '' COMMENT '资源 id',
  `node_id` varchar(64) NOT NULL DEFAULT '' COMMENT '节点 id',
  `lock_count` int(11) NOT NULL DEFAULT '1' COMMENT '锁的次数, 统计可冲入锁',
  `version` int(11) NOT NULL DEFAULT '1' COMMENT '版本',
  `desc` varchar(1024) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '保存数据时间，自动生成',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新数据时间，自动生成',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_application_resource` (`application_id`,`resource_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分布式锁';

-- 通过 for update 操作，数据库在查询的时候就会给这条记录加上排它锁。
-- 在 InnoDB 中只有字段加了索引的，才会是行级锁，否者是表级锁，所以要给 WHERE 条件中的字段加上索引
SELECT * FROM distributed_lock WHERE application_id = 'XXX' AND resource_id = 'YYY' FOR UPDATE;
```

## 悲观锁(排它锁)

``` sql
-- 加锁
def lock:
  1. 尝试加锁
  exec sql:
    INSERT INTO distributed_lock (`application_id`, `resource_id`, `node_id`, `version`, `lock_count`) VALUES ('exclusive-lock', 'money-service', 'node-1', 1, 1);
  if (result == true) {
    return true;

  2. 锁存在
  } else {
    -- 开启事务
    exec sql:
      SELECT `node_id`, `lock_count` FROM distributed_lock WHERE application_id = 'exclusive-lock' AND resource_id = 'money-service' FOR UPDATE;

    -- 如果加锁的节点和当前节点相同, 标识是同一个线程加锁的(在其中一个节点尝试加锁, 注意 `node_id` 可以使机器信息, 线程信息, 用来标识这个线程的唯一性)
    if (node_id == current_node_id) {
      -- 记录当前节点, 锁重入次数
      UPDATE distributed_lock SET lock_count = lock_count + 1 WHERE application_id = 'exclusive-lock' AND resource_id = 'money-service';
      return true;
    } else {
      return false;
    }
  }

-- 释放锁
def unlock:
  DELETE FROM distributed_lock WHERE application_id = 'exclusive-lock' AND resource_id = 'money-service';


def lock(timeout):
  while(true) {
    -- 不断重试, 直到获取锁.
    lock
  }
```


## 乐观锁

- 简单基于 CAS(Compare And Swap) 思想实现
- 每个涉及到的数据表都要加上 version 字段

``` sql
1. 首先获取数据, 拿到 version 字段
  SELECT version FROM distributed_lock WHERE application_id = 'optimistic-lock' AND resource_id = 'order-service' FOR UPDATE;

2. 更新数据, 带上 version 字段, 如果 version 与查询的不一致, 则更新失败. 因为这条记录被其他线程更改了。
  UPDATE distributed_lock SET `version`= version + 1 WHERE application_id = 'optimistic-lock' AND resource_id = 'order-service' AND version = '1';
```
