#Redis 配置

##一、Redis（分布式数据库） 默认端口6379

###结构说明
1、数据库->表(无表)->字段(无字段)

2、对字段没有要求，字段的扩张很灵活

3、存储格式为BSON ( 一直二进制的json )



##二、使用环境
1、适用环境
```
1) 缓存
2) 高效的实施性
3) 用于对象及JSON数据的存储
4) 订阅系统
```

2、不适用环境
```
1) 高度事务性的系统
2) 传统的商业智能应用
3) 复杂多表查询
```


##三、安装使用
1、下载安装
```
下载地址（选择稳定版本Stable）
http://redis.io/download
```


2、安装编译 (这里只需要make就好，到时src下的执行文件到)
```
tar -zxvf redis-2.8.19.tar.gz
cd redis-2.8.19
make

```


3、配置目录
```
1) 创建执行文件目录，存放编译后的文件
mkdir -p /usr/local/redis-2.8.19/bin
cp -airp /usr/src/redis-2.8.19/src/* /usr/local/redis-2.8.19/bin/

2) 创建配置文件目录，存放配置配置文件
mkdir -p /usr/local/redis-2.8.19/etc
cp -airp /usr/src/redis-2.8.19/redis.conf /usr/local/redis-2.8.19/etc/

3) 创建执行目录，存放redis执行时用到的文件
mkdir -p /usr/local/redis-2.8.19/run

4) 创建日志目录，留作以后备用
mkdir -p /usr/local/redis-2.8.19/logs

5) 创建redis存储目录
mkdir -p /data/redis

```


4、修改配置文件
```
vim /usr/local/redis-2.8.19/etc/redis.conf

1) 设置后台启动
daemonize yes

2) 端口
port 6379

3) 存储文件目录
dir /data/redis

4) pid文件路径
pidfile /usr/local/redis-2.8.19/run/redis-1.pid


参考资料
http://www.cnblogs.com/cxd4321/archive/2012/12/14/2817669.html
```

4、启动与关闭
```
1) 启动redis
/usr/local/redis-2.8.19/bin/redis-server /usr/local/redis-2.8.19/etc/redi

netstat -tunlp	| grep 6379 	查看，看到6379表示启动成功

2) 关闭进程
pkill redis-server

3) 进入客户端
/usr/local/redis-2.8.19/redis-cli


设置软链
ln -s /usr/local/redis-2.8.19/bin/* /usr/bin/

设置开机启动
vi /etc/rc.d/rc.local
#启动redis
/usr/local/redis-2.8.19/bin/redis-server /usr/local/redis-2.8.19/etc/redis.conf

```

##四、数据库使用

###1、String

应用场景：
String是最常用的一种数据类型，普通的key/value存储都可以归为此类，可以存储二进制文件，可以存储任何类型。

```
常用命令：
set,get,decr,incr,mget 等。

127.0.0.1:6379> set a 1			//设置值
127.0.0.1:6379> get a				//获取值

127.0.0.1:6379> setex c 10 red		//设置:c ,时效为:10秒, 值为red

127.0.0.1:6379> setnx b 2		//设置值(值存在不设置，不存在则设置)

127.0.0.1:6379> msetnx key1 a key2 b key3 c	//与setnx属性类似，但是此方法表示一次性设置多个值。如果有重复则全部失效，貌似无用

127.0.0.1:6379> setrange email 8 139.com		//从key为email中，替换第6位开始的字符串(只会替换与替换内容相同长度的内容)

127.0.0.1:6379> getrange email 0 5					//从key为email中,0到4的字符

127.0.0.1:6379> mset key1 a key2 b key3 c		//批量设置，成功返回OK，失败返回0表示没有任何值被设置

127.0.0.1:6379> mget key1 key2 key3				//一次设置多个值

127.0.0.1:6379> getset key1 aaa		//获取旧值，并设置新值

127.0.0.1:6379> incr a						//自增一个key值，返回新的值
127.0.0.1:6379> incrby a 5				//自增key的值为5，如key不存在默认设置为0，并自增5
//也可以自减-5

127.0.0.1:6379> decr a						//自减一个key值，返回新的值
127.0.0.1:6379> decrby a	 3				//自减一个key值，返回新的值

127.0.0.1:6379> append name .net 	//为一个key追加字符，如key不存在则创建

127.0.0.1:6379>strlen a		//返回Key长度

127.0.0.1:6379>exists	a		//检测Key是否存在
```


###2、Hash表  {user:001:{name:1,age:2}} 的结构
应用场景：
用来存储对象

常用命令：
hget,hset,hgetall 等。
说明：
hash是一个string类型的field和value的映射表。添加和操作都是0(1)平均操作。
```
127.0.0.1:6379> hset user:001 name jsonlin		//设置user:001哈希表中的name 为jsonlin

127.0.0.1:6379> hget user:001 name					//读取user:001表中的name

127.0.0.1:6379> hsetnx user:002 name jsonlin123	//设置值(值存在不设置，不存在则设置)

127.0.0.1:6379> hmset user:001 name '1' age '2'		//user:001哈希表中批量设置(不存在设置，存在则替换)

127.0.0.1:6379> hmget user:001 name age				//批量返回user:001哈希表中的字段

127.0.0.1:6379> hincrby user:001 age 5						//哈希表字段累加

127.0.0.1:6379> hexists user:001 age							//检测哈希表中的字段是否存在（1和0）

127.0.0.1:6379> hlen user:001									//查看user:001哈希表中所有字段的长度

127.0.0.1:6379> hdel user:001 age								//删除user:001哈希表中age字段（1和0）

127.0.0.1:6379> hkeys user:001									//返回哈希表中所有的字段

127.0.0.1:6379> hvals user:001									//返回哈希表中所有的value值

127.0.0.1:6379> hgetall user:001								//返回哈希表中所有字段和值

```

###3、List array(0=>a,1=>b) 的结构
应用场景：
Redis list的应用场景非常多，也是Redis最重要的数据结构之一，比如twitter的关注列表，粉丝列表等都可以用Redis的list结构来实现，比较好理解，这里不再重复。

常用命令：
lpush,rpush,lpop,rpop,lrange等。
实现方式：
即可当栈，也可以作为队列
Redis list的实现为一个双向链表，即可以支持反向查找和遍历，更方便操作，不过带来了部分额外的内存开销，Redis内部的很多实现，包括发送缓冲队列等也都是用的这个数据结构。

```
0 ： 表示key为0的值，一般表示数组第一个值
-1： 表示	key 为最后一个的值 ，表示数组的最后一个值

1) lpush 从list (头部)压入元素	(堆栈)
127.0.0.1:6379>lpush list1 "hellow"
127.0.0.1:6379>lpush list1 "world"
127.0.0.1:6379>lrange list1 0 -1

2) rpush 从list (尾部)添加元素 	(队列)
127.0.0.1:6379>rpush list2 "hellow"
127.0.0.1:6379>rpush list2 "world"
127.0.0.1:6379>lrange list2 0 -1

3) linsert 在队列中的任意的中间插入元素
127.0.0.1:6379>LINSERT list3 BEFORE "World" "There"		//在World后插入一个There
127.0.0.1:6379>lrange list3 0 -1

4) lset 给某个元素赋值
127.0.0.1:6379>lset list4 0 "World"		//修改列表中，第0个元素为World

5) lrem 删除值
127.0.0.1:6379>lrem list5 3 "World"		//列表中，删除3个值为World的元素

6) ltrim 保留范围，其他全部删除
127.0.0.1:6379>ltrim list6  1 2			//保留范围是1 到 2的数据，其他全部删除

7) lpop 从头部弹出一个元素
127.0.0.1:6379>lpop

8) rpoplpush 从尾部弹出一个元素，插入到另外一个元素的头部
127.0.0.1:6379>rpoplpush list7 list8
```


###4、sets
应用场景：
Redis set对外提供的功能与list类似是一个列表的功能，特殊之处在于set是可以自动排重的，
当你需要存储一个列表数据，又不希望出现重复数据时，
set是一个很好的选择，并且set提供了判断某个成员是否在一个set集合内的重要接口，这个也是list所不能提供的。

常用命令：
sadd,spop,smembers,sunion 等。
实现方式：
set 的内部实现是一个 value永远为null的HashMap，实际就是通过计算hash的方式来快速排重的，这也是set能提供判断一个成员是否在集合内的原因。
```

1)sadd 添加集合
127.0.0.1:6379>sadd myset1 "one"

2)smembers 查看集合
127.0.0.1:6379>smembers myset1

3)srem 删除集合
127.0.0.1:6379>srem myset1 "one"

4)sdiff 取差集
127.0.0.1:6379>sdiff myset2 myset3

5)sdiffstore 组合差集放入到一个新的集合中myset4
127.0.0.1:6379>sdiffstore myset4 myset2 myset3

6)sinter 取交集
127.0.0.1:6379>sinter myset2 myset3

7)sinterstore 	组合交集放入到myset6中
127.0.0.1:6379>sinterstore myset6 myset2 myset3

8)sunion 并集(取不重复的值)
127.0.0.1:6379>sunion myset2 myset3

9)sunionstore 组合并集放入到myset7中
127.0.0.1:6379>sunionstore myset7 myset2 myset3

10)smove  移动一个myset1集合中的two值,到myset8集合中
127.0.0.1:6379>smove myset1 myset8 two

11)sismember 查看集合的元素个数
127.0.0.1:6379>sismember myset2 one

12)zadd 增加至，并且设置排序
127.0.0.1:6379>zadd myzset2 1 "one"

13)zincrby	对已有元素集合 的 顺序增加与减少
127.0.0.1:6379>zincrby sset1 2 "one"		//增加
127.0.0.1:6379>zincrby sset1 -2  "one"		//减少

14)
```
