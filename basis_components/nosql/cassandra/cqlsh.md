# cqlsh 语句

- cqlsh shell 工具

``` sql
# ------------------------------ cqlsh 配置环境 ------------------------------
# 配置环境变量
vim ~/.bashrc
# 配置 Cassandra client host
export CQLSH_HOST=xxx.xxx.xxx.xxx
# 配置 Cassandra client port
export CQLSH_PORT=9042

# 启动
./bin/cqlsh


# ------------------------------ Cassandra 系统命令 ------------------------------
# 此命令显示当前的一致性级别，或设置新的一致性级别。
Consistency

# 此命令将数据从 Cassandra 复制到文件并从中复制。下面给出一个将名为emp的表复制到文件myfile的示例。
Copy


# ------------------------------ Cassandra 创建键空间 ------------------------------
# 创建 Keyspace 空间
CREATE KEYSPACE test
WITH replication = {'class':'NetworkTopologyStrategy', 'replication_factor' : 3};

  SimpleStrategy: 只用于单数据中心和单机架。SimpleStrategy 把第一份备份放在由分区器决定的节点上。余下的备份被放在环的顺时针方向的下面的节点上，而不考虑拓扑结构(机架或数据中心的位置)。
  NetworkTopologyStrategy: 当你已经(或者计划)将你的集群部署成多数据中心的时候，使用 NetworkTopologyStrategy 策略。这个策略需要指定在每个数据中心有多少个副本数量。


# 修改 Keyspace 空间
ALTER KEYSPACE test
WITH REPLICATION = {'class' : 'NetworkTopologyStrategy', 'replication_factor' : 3}

# 删除 Keyspace 空间
DROP KEYSPACE test;

# 查看所有 Keyspace 空间
DESCRIBE keyspaces;

# 查看指定 test Keyspace
DESCRIBE test;

# 使用 test Keyspace
USE test;


# ------------------------------ Cassandra 创建表 ------------------------------
# 语法.
CREATE (TABLE | COLUMNFAMILY) <tablename>
('<column-definition>' , '<column-definition>')
(WITH <option> AND <option>)

# 使用表空间
use test;

# 创建表
CREATE TABLE emp(
   emp_id int PRIMARY KEY,
   emp_name text,
   emp_city text,
   emp_sal varint,
   emp_phone varint
   );

# 查看已有表
DESC tables;

# 查看表详情
DESCRIBE  emp;


# ------------------------------ Cassandra 修改表 ------------------------------
# 语法.
ALTER (TABLE | COLUMNFAMILY) <tablename> <instruction>

# 增加列
ALTER TABLE emp ADD emp_email text;

# 删除列
ALTER TABLE emp DROP emp_email;


# ------------------------------ Cassandra 删除表 ------------------------------
# 删除指定表
DROP TABLE emp;

# 验证表是否已删除, 由于 emp 表已删除，您不会在列族列表中找到它。
DESCRIBE COLUMNFAMILIES;


# ------------------------------ Cassandra 截断表 ------------------------------
# 语法. 表的所有行都将永久删除
TRUNCATE <tablename>

# 清理 emp 表
TRUNCATE emp;


# ------------------------------ Cassandra 索引 ------------------------------
# 创建索引语法.
# identifier 命令规范为: idx_<键空间_表名_字段名>
CREATE INDEX <identifier> ON <tablename>

# 创建索引 idx_emp_name 放到 emp 表的 emp_name 字段中
CREATE INDEX idx_test_emp_name ON emp (emp_name);

# 删除索引语法.
DROP INDEX <identifier>

# 删除表中列的索引的示例。这里我们删除表emp中的列名的索引
DROP INDEX idx_test_emp_name;


# ------------------------------ Cassandra 创建数据 ------------------------------
# 语法.
INSERT INTO <tablename>
(<column1 name>, <column2 name>....)
VALUES (<value1>, <value2>....)
USING <option>

INSERT INTO emp (emp_id, emp_name, emp_city, emp_phone, emp_sal) VALUES(1,'ram', 'Hyderabad', 9848022338, 50000);

INSERT INTO emp (emp_id, emp_name, emp_city, emp_phone, emp_sal) VALUES(2,'robin', 'Hyderabad', 9848022339, 40000);

INSERT INTO emp (emp_id, emp_name, emp_city, emp_phone, emp_sal) VALUES(3,'rahman', 'Chennai', 9848022330, 45000);


# ------------------------------ Cassandra 更新数据 ------------------------------
# 语法.
UPDATE <tablename>
SET <column name> = <new value>
<column name> = <value>....
WHERE <condition>

# 更新数据
UPDATE emp SET emp_city='Delhi',emp_sal=50000 WHERE emp_id=2;


# ------------------------------ Cassandra 读数据 ------------------------------
# 语法.
SELECT * | select_expression | DISTINCT partition
FROM [keyspace_name.] table_name
[WHERE partition_value
   [AND clustering_filters
   [AND static_filters]]]
[ORDER BY PK_column_name ASC|DESC]
[LIMIT N]
[ALLOW FILTERING]

# 查询所有数据
SELECT * FROM test.emp;

# 查询指定列数据
SELECT emp_name, emp_sal FROM test.emp;

# 查询数据指定条件数据
# WHERE 的子查询必须有索引才可以查询
CREATE INDEX idx_test_emp_name ON emp (emp_name);
SELECT * FROM emp WHERE emp_name='ram';


# ------------------------------ Cassandra 删除数据 ------------------------------
# 语法.
DELETE FROM <identifier> WHERE <condition>;

# 删除整个列数据, 删除指定列 emp_sal 的数据
DELETE emp_sal FROM emp WHERE emp_id=3;

# 删除整行
DELETE FROM emp WHERE emp_id=3;


# ------------------------------ Cassandra 索引查询 ------------------------------
CREATE table teacher(
    id int,
    address text,
    name text,
    age int,
    height int,
    # 一级索引
    primary key(id, height)
);

INSERT INTO teacher(id,address,name,age,height) VALUES(1,'guangdong','lixiao',32,172);
INSERT INTO teacher(id,address,name,age,height) VALUES(1,'guangxi','linzexu',68,178);
INSERT INTO teacher(id,address,name,age,height) VALUES(1,'guangxi','lihao',25,178);
INSERT INTO teacher(id,address,name,age,height) VALUES(2,'guangxi','lixiaolong',32,172);
INSERT INTO teacher(id,address,name,age,height) VALUES(2,'guangdong','lixiao',32,172);
INSERT INTO teacher(id,address,name,age,height) VALUES(2,'guangxi','linzexu',68,178);
INSERT INTO teacher(id,address,name,age,height) VALUES(2,'guangxi','lihao',25,178);
INSERT INTO teacher(id,address,name,age,height) VALUES(2,'guangxi','nnd',32,172);

# 第一主键 只能用 = 号查询
SELECT * FROM teacher WHERE id = 1;

# 第二主键 支持 = ( >、 < 、 >= 、 <= ), 必须带主键
SELECT * FROM teacher WHERE id = 1 AND height>10 AND age > 10;

# 创建 二级索引列
CREATE INDEX idx_teacher_age on teacher(age);

# 二级索引列只可以用 = 号查询
SELECT * FROM teacher WHERE age=32;

# 如果查询条件里，有一列是 age 二级索引，那其它非索引非主键字段，可以通过加一个 ALLOW FILTERING 来过滤实现
# 原理是：先根据 age=32 过滤出结果集，然后再对结果集进行 height>30 过滤
SELECT * FROM teacher WHERE age=32 AND height>30 ALLOW FILTERING;


# ------------------------------ Cassandra 排序 ------------------------------
# Cassandra 支持排序, 但也是限制重重
  # 1. 必须有第一主键的 = 号查询。 cassandra 的第一主键是决定记录分布在哪台机器上，也就是说 cassandra 只支持单台机器上的记录排序。
  # 2. 只能根据第二、三、四…主键进行有序的，相同的排序
    # 有序：order by 后面只能是先二、再三、再四…这样的顺序，有四，前面必须有三；有三，前面必须有二，以此类推。
    # 相同的顺序：参与排序的主键要么与建表时指定的顺序一致，要么全部相反，具体会体现在下面的示例中
  # 3. 不能有索引查询

# 创建排序表
CREATE TABLE teacher_sort(
    id int,
    address text,
    name text,
    age int,
    height int,
    primary key(id, address, name)
)
# 可选
WITH CLUSTERING ORDER BY(address DESC, name ASC);

# 插入数据
INSERT INTO teacher_sort(id,address,name,age,height) VALUES(1,'guangdong','lixiao',32,172);
INSERT INTO teacher_sort(id,address,name,age,height) VALUES(1,'guangxi','linzexu',68,173);
INSERT INTO teacher_sort(id,address,name,age,height) VALUES(1,'guangxi','lihao',25,178);
INSERT INTO teacher_sort(id,address,name,age,height) VALUES(2,'guangxi','lixiaolong',32,172);
INSERT INTO teacher_sort(id,address,name,age,height) VALUES(2,'guangdong','lixiao',32,172);
INSERT INTO teacher_sort(id,address,name,age,height) VALUES(2,'guangxi','linzexu',68,174);
INSERT INTO teacher_sort(id,address,name,age,height) VALUES(2,'guangxi','lihao',25,170);
INSERT INTO teacher_sort(id,address,name,age,height) VALUES(2,'guangxi','nnd',32,172);

# 查询数据
SELECT * FROM teacher_sort WHERE id=1 ORDER BY address ASC;

# 按照索引顺序排序
SELECT * FROM teacher_sort
  WHERE id=1
  ORDER BY address ASC, name DESC;

# 查询
SELECT * FROM teacher_sort
  WHERE id=1 AND address='guangxi'
  ORDER BY address ASC;

# 查询
SELECT * FROM teacher_sort
  WHERE id=1 AND address='guangxi'
  ORDER BY address ASC, name DESC;

# 排序总结
  # Cassandra 的任何查询, 最后的结果都是有序的，默认与建表时指定的排序规则一致.
  # 建表时 teacher(address ASC, name ASC) 或者 teacher_sort(address DESC, name ASC) 是有序的.
  # 查询时 teacher 表使用 (address DESC, name ASC), 或者(address ASC, name DESC).
  # 查询时 teacher_sort 表使用 (address DESC, name DESC) 排序, 或者 (address ASC, name ASC) 排序.
  # 所以 (ASC, ASC) 或者 (DESC, DESC) 对 Cassandra 是没有意义的.

  PS: 因为 Cassandra 的存储, key 本质就是有序的.
```