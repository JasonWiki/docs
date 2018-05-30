# Hbase 操作

## 一、基本操作

### 1. `基本操作`

``` sh
hbase shell
  进入系统

help
  显示帮助信息

status
  查看服务器状态

version
  查看 hbase 版本
```

### 1. `DDL 操作`

``` sh
* 创建表 :
  create '表名','列族 1','列族 2','列族 N'
  create 'member','member_id','address','info'
  create 'member',{NAME=>'member_id'},{NAME=>'address'},{NAME=>'info'}

* 修改表名
  disable 'member'
  snapshot 'member', 'member_Snapshot'  // 快照一份
  clone_snapshot 'member_Snapshot', 'member_20160518' // 克隆到目标表
  delete_snapshot 'member_Snapshot' // 删除快照表

* 查看所有表 :
  list

* 查看表结构 :
  describe '表名'
  describe 'member'

* 删除、修改列族 :
  disable 'member' // 禁用
  alter 'member',{'delete' => 'member_id'}
  enable 'member'  // 开启

* 增加列族
  disable 'member'
  alter 'member',{NAME => 'recommend', VERSIONS => 3}  表示存储 3 个版本数据
  enable 'member'

* 删除表 :
  disable 'member' // 禁用
  drop 'member'

* 删除列
  delete 'member','1','列族:列'

* 检测一个表是否存在 :
  exists 'member'

* 判断表是否 enable(启用) :
  is_enabled 'member'

* 判断表示会否 disable(禁用) :
  is_disabled 'member'

```

### 2. `DML 操作`

### 2.1 普通操作

``` sh
* 插入记录 :
  put '表名','行名','列族:','值'
  // 向 member 表, (row-key), (列族:列) ,写入数据
  put 'member','jason','info:age','1'
  put 'member','jason','info:birthday','2016-01-01'
  put 'member','jason','info:company','angejia'
  // 向 member 表, (row-key), (列族:列) ,写入数据
  put 'member','jason','address:contry','china'
  put 'member','jason','address:province','shanghai'
  put 'member','jason','address:city','shanghai'

* 查看记录 :
  get '表名','行名','列族:列名'
  1) 获取一个 row-key 所有列族数据
    get 'member','jason'

  2) 获取一个 row-key , 列族数据
    get 'member','jason','info'

  3) 获取一个 row-key , 列族:列 的数据
    get 'member','jason','info:age'

* 更新记录(就是重新写一条) :
  put 'member','jason','info:age','24'

* 根据获取 2 个版本数据 :
  1) 修改前时间戳数据
    get 'member','jason',{COLUMN=>'info:age',TIMESTAMP=>1456068948812}

  2) 修改后时间戳数据
    get 'member','jason',{COLUMN=>'info:age',TIMESTAMP=>1456069279146}

  3) 获取三个版本数据
    get 'member','jason',{COLUMN=>'info:age',VERSIONS=>3}

* 全表扫描(显示所有 row-key,列族) :
  scan 'member'
  1) 显示 member 所有列族，但只取一行
    scan 'member', {COLUMNS => ['info'], LIMIT => 1}

  1) 显示 member 表,列族是 info 的所有数据
    scan 'member', {COLUMNS => ['info'], LIMIT => 1}

  2) 显示 member 表,列族 info , birthday 列的 数据
    scan 'member', {COLUMNS => ['info:birthday'], LIMIT => 1}

* 删除 row-key 下的 列族:列 的字段 :
  delete 'member','jason','info:age'

* 删除 row-key
  deleteall 'member','10'

* 查询表行数 :
  count 'member'

* 递增
  incr 'member','jason','info:age'

* 清空表
  truncate 'member'

```

### 2. filter 过滤器

- [过滤器合集](http://blog.csdn.net/liuxiaochen123/article/details/7737718)
- [过滤器操作](http://www.cnblogs.com/luogankun/p/3939712.html)

``` sh
* ValueFilter() 针对值查找过滤
  1) 列族:所有列,值 = (angejia)
    scan 'member', {FILTER=>"ValueFilter(=,'binary:angejia')"}

  2) 列族:所有列,值 包含 (ge)
    scan 'member', {FILTER=>"ValueFilter(=,'substring:ge')"}

* ColumnPrefixFilter() 针对列查找过滤
  1) 列族:company 列,值 包含 (ge)
    scan 'member', {FILTER=>"ColumnPrefixFilter('company') AND ValueFilter(=,'substring:ge')"}
  2) 值 = (angejia)
    scan 'member', {FILTER=>"ColumnPrefixFilter('company') AND (ValueFilter(=,'binary:angejia') )"}

* PrefixFilter() row-key 开头查找过滤
  1) row-key 为 jason 开头的，所有列族和列 的数据
    scan 'member', {FILTER => "PrefixFilter ('jason')"}

* FirstKeyOnlyFilter() 多个 version 的第一个
  KeyOnlyFilter() 只要,key 不要值
  1) 查找 多个版本中第一个版本的数据
  scan 'member', {FILTER=>"FirstKeyOnlyFilter() AND KeyOnlyFilter()"}

* STARTROW  范围查找开始
  STOPROW   范围查找结束
  1) 从 row-key 的 n 个位置开始查找(star_row)
    scan 'member', {STARTROW => 'jason'}

  2) 从 row-key 的 n 个位置开始 到 从 row-key 的 n 个位置 结束的数据
    scan 'member', {STARTROW=>'jason', STOPROW=>'jason'}

* RowFilter() row-key 包含查找过滤
  import org.apache.hadoop.hbase.filter.CompareFilter
  import org.apache.hadoop.hbase.filter.SubstringComparator
  import org.apache.hadoop.hbase.filter.RowFilter
  import org.apache.hadoop.hbase.filter.RegexStringComparator

  1) row-key 里面包含 jas 的数据
    scan 'member', {
      FILTER => RowFilter.new(
        CompareFilter::CompareOp.valueOf('EQUAL'),
        SubstringComparator.new('jas')
      )
    }

  2) row-key 正则匹配查询
    scan 'member', {
      FILTER => RowFilter.new(
        CompareFilter::CompareOp.valueOf('EQUAL'),
        RegexStringComparator.new('^jas[a-z]+$')
      )
    }

* SingleColumnValueFilter() 列值过滤器
  import org.apache.hadoop.hbase.filter.CompareFilter
  import org.apache.hadoop.hbase.filter.SingleColumnValueFilter
  import org.apache.hadoop.hbase.filter.SubstringComparator

  1) info:company 列,值 = angejia 的数据
    scan 'member', {
      # 显示指定列数据,如果不写,则返回所有匹配出的数据
      COLUMNS => 'info:company',
      FILTER => SingleColumnValueFilter.new(
        Bytes.toBytes('info'),
        Bytes.toBytes('company'),
        CompareFilter::CompareOp.valueOf('EQUAL'),
        Bytes.toBytes('angejia')
      )
    }
```
