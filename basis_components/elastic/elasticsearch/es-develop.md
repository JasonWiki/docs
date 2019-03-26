# 分布式搜索引擎

- 基于 Lucene 的搜索服务器。它提供了一个分布式多用户能力的全文搜索引擎

## api 使用

- REST 访问模式是很普遍在所有的API命令
  - curl -X<REST Verb> <Node>:<Port>/<Index>/<Type>/<ID>
  - 例如:   curl -XPUT 'dwtest:9200/customer/external/1?pretty'

### 1. Document APIs

- 索引->类型->文档

``` sh

* 集群健康
  curl 'dwtest:9200/_cat/health?v'

* 节点健康
  curl 'dwtest:9200/_cat/nodes?v'

* 列出所有索引
  curl 'dwtest:9200/_cat/indices?v'



* 创建一个名为“customer”使用PUT动词的索引。 我们只是添加pretty的通话结束，告诉它漂亮打印
  curl -XPUT 'dwtest:9200/customer?pretty'

  response:
  {
    "acknowledged" : true
  }


* 查看所有 indices 指标
  curl 'dwtest:9200/_cat/indices?v'

  PS : Elasticsearch 默认创建一个副本此索引。 因为我们只有一个节点的时间，此刻正在运行，这一个副本还不能分配（高可用性），直到稍后当其他节点加入集群。 一旦该副本被分配到第二个节点，这个索引的健康状况会变成绿色。

  response:
  health  index     pri(5个主要碎片) rep(一个副本,默认) docs.count docs.deleted store.size pri.store.size
  yellow  customer    5             1                0            0            495b           495b


* 创建一个文档 customer(索引)->external(类型)->1(文档)
  curl -XPUT 'dwtest:9200/customer/external/1?pretty' -d '
  {
    "name": "John Doe"
  }'

  response:
  {
    "_index" : "customer",
    "_type" : "external",
    "_id" : "1",
    "_version" : 1,
    "_shards" : {
      "total" : 2,
      "successful" : 1,
      "failed" : 0
    },
    "created" : true
  }


* 查询文档
  curl -XGET 'dwtest:9200/customer/external/1?pretty'

  response:
  {
    "_index" : "customer",
    "_type" : "external",
    "_id" : "1",
    "_version" : 1,
    "found" : true,
    "_source" : {
      "name" : "John Doe"
    }
  }


* 删除索引
  curl -XDELETE 'dwtest:9200/customer?pretty'

  response:
  {
    "acknowledged" : true
  }

  查看所有 indices
  curl 'dwtest:9200/_cat/indices?v'

  response:
  空


* 自动生成文档 id
  curl -XPOST 'dwtest:9200/customer/external?pretty' -d '
  {
  "name": "Jane Doe"
  }'


* 更新文档 (增加 age 字段)
  curl -XPOST 'dwtest:9200/customer/external/1/_update?pretty' -d '
  {
    "doc": { "name": "Jane Doe", "age": 20 }
  }'


* 更新文档 (累加字段数据)
  curl -XPOST 'dwtest:9200/customer/external/1/_update?pretty' -d '
  {
  "script" : "ctx._source.age += 5"
  }'


* 删除文档
  curl -XDELETE 'dwtest:9200/customer/external/1?pretty'


* 加载文件数据到 bank->account 中
  curl -XPOST 'dwtest:9200/bank/account/_bulk?pretty' --data-binary "@accounts.json"
  curl 'dwtest:9200/_cat/indices?v'


* 查看 bank 索引结构
  curl -XGET 'http://dwtest:9200/bank/_mapping'

```

### 2. Search APIs

- Search APIS 的 Request Body 参数语法依赖于 Query DSL
- URL 搜索: [GET 方式](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-uri-request.html)
- DSL 语句: 基于 JSON 定义查询 [DSL 语法查询方式的 Request Body 请求语法和参数详解法](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-body.html)
  - [Full text queries 全文查询, 用于搜索文本和邮件内容](https://www.elastic.co/guide/en/elasticsearch/reference/current/full-text-queries.html)
  - [Term level queries 用于结构化数据查询](https://www.elastic.co/guide/en/elasticsearch/reference/current/term-level-queries.html)
  - [Compound queries 复合查询](https://www.elastic.co/guide/en/elasticsearch/reference/current/compound-queries.html)
  - [Joining queries 联结查询, Json 模仿 SQL 风格](https://www.elastic.co/guide/en/elasticsearch/reference/current/joining-queries.html)
  - [Geo queries 地理位置查询](https://www.elastic.co/guide/en/elasticsearch/reference/current/geo-queries.html)
- [Elasticsearch 中的 DSL 主要由两部分组成](http://www.cnblogs.com/xing901022/p/4975931.html)
  - Leaf query Cluase 叶查询子句: 这种查询可以单独使用，针对某一特定的字段查询特定的值，比如match、term、range等
  - Compound query Cluase 复合查询子句: 这种查询配合其他的叶查询或者复合查询，用于在逻辑上，组成更为复杂的查询，比如 bool

``` sh
* 查询 bank 索引下所有数据
  curl 'dwtest:9200/bank/_search?q=*&pretty'

  response:
    took - 时间以毫秒为单位 Elasticsearch 来执行搜索
    timed_out - 搜索结果是否超时
    _shards - 告诉我们许多碎片是如何搜索，以及成功的计数/失败碎片搜索
    hits - 搜索结果
      hits.total - 匹配搜索的文档总数
      hits.hits - 搜索结果实际的数组 (默认为前10个文件)


* 匹配所有, 获取 1 条记录
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
  {
    "query": { "match_all": {} },
    "size": 1
  }'


* 匹配所有, 从 10 条开始, 取 10 条 记录
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
  {
  "query": { "match_all": {} },
  "from": 10,
  "size": 10
  }'


* 匹配所有, 排序 balance 字段
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
  {
  "query": { "match_all": {} },
  "sort": { "balance": { "order": "desc" } }
  }'


* 匹配所有, 限制返回字段
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
  {
    "query": { "match_all": {} },
    "_source": ["account_number", "balance"]
  }'


* Macth 匹配查询

  数字匹配
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
  {
  "query": { "match": { "account_number": 20 } }
  }'

  包含匹配 (mill)
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
  {
    "query": { "match": { "address": "mill" } }
  }'

  包含匹配 (mill 或 lane)
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
  {
    "query": { "match": { "address": "mill lane" } }
  }'


* bool(ean) query 必须满足条件查询
  必须满足 mill 和 lane
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
  {
    "query": {
      "bool": {
        "must": [
          { "match": { "address": "mill" } },
          { "match": { "address": "lane" } }
        ]
      }
    }
  }'

  必须满足 40 and 不满足 ID
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
  {
    "query": {
      "bool": {
        "must": [
          { "match": { "age": "40" } }
        ],
        "must_not": [
          { "match": { "state": "ID" } }
        ]
      }
    }
  }'


* Filters 过滤器
  范围查询
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
    {
     "query": {
       "bool": {
         "must": { "match_all": {} },
         "filter": {
           "range": {
             "balance": {
               "gte": 20000,
               "lte": 30000
             }
           }
         }
       }
     }
  }'


* aggs 聚合操作

  等同于: SELECT state, COUNT(*) FROM bank GROUP BY state ORDER BY COUNT(*) DESC  
  curl -XPOST 'dwtest:9200/bank/_search?pretty' -d '
  {
    "size": 0,
    "aggs": {
      "group_by_state": {
        "terms": {
          "field": "state"
        }
      }
    }
  }'

```
