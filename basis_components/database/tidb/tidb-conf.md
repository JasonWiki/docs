# tidb 集群配置

## TiDB 配置

- [TiDB conf/tidb.yml](https://pingcap.com/docs-cn/v3.0/reference/configuration/tidb-server/configuration-file/)

``` sh
# 性能相关配置
performance:

  # TiDB 的 CPU 使用数量, 最大可用 cpu 个数。 默认值为 0 表示使用机器上所有的 CPU，也可以设置成 n，那么 TiDB 会使用 n 个 CPU 数量。
  max-procs: 16

  # TiDB 一个事务允许的最大语句条数限制
  stmt-count-limit: 5000

tikv-client:

  # 跟每个 TiKV 之间建立的最大连接数。
  grpc-connection-count: 16

  # 执行事务提交时，最大的超时时间。
  commit-timeout: 41s
```

## PD 配置

- [PD conf/pd.yml](https://pingcap.com/docs-cn/v3.0/reference/configuration/pd-server/configuration-file/)

``` sh

# 元信息数据库存储空间的大小，默认 2GB。
quota-backend-bytes: 2147483648

# etcd leader 选举的超时时间。
election-interval: 3s


# metric 监控相关的配置项
metric:
  # 向 promethus 推送监控指标数据的间隔时间
  interval: 15s


# 调度相关的配置项
schedule:
  # 控制 Region Merge 的 size 上限，当 Region Size 大于指定值时 PD 不会将其与相邻的 Region 合并
  max-merge-region-size: 20

  # 控制 Region Merge 的 key 上限，当 Region key 大于指定值时 PD 不会将其与相邻的 Region 合并
  max-merge-region-keys: 200000

  # 控制对同一个 Region 做 split 和 merge 操作的间隔，即对于新 split 的 Region 一段时间内不会被 merge。
  split-merge-interval: 1h

  # 同时进行 leader 调度的任务个数。
  leader-schedule-limit: 4

  # 同时进行 Region 调度的任务个数
  region-schedule-limit: 4

  # 同时进行 replica 调度的任务个数
  replica-schedule-limit: 8

  # 同时进行的 Region Merge 调度的任务，设置为 0 则关闭 Region Merge
  merge-schedule-limit: 8


# 副本相关的配置项
replication:
  # 副本数量
  max-replicas: 3

  # 标签相关的配置项
  label-property

```


## TiKV 配置

- [TiKV conf/tikv.yml](https://pingcap.com/docs-cn/v3.0/reference/configuration/tikv-server/configuration-file/)
- [TiVK 调优](https://pingcap.com/docs-cn/v3.0/reference/performance/tune-tikv/)

``` sh
# Http API 服务的工作线程数量。
status-thread-pool-size: 1

# gRPC 消息的压缩算法，取值：none， deflate， gzip
grpc-compression-type: none

# gRPC 工作线程的数量
grpc-concurrency: 4

# 一个 gRPC 链接中最多允许的并发请求数量
grpc-concurrent-stream: 1024

# tikv 节点之间用于 raft 通讯的链接最大数量
server.grpc-raft-conn-num: 10

# gRPC stream 的 window 大小 (KB|MB|GB)
server.grpc-stream-initial-window-size: 2MB


# 存储线程池相关的配置项
readpool.storage:
  # 处理高优先级读请求的线程池线程数量
  high-concurrency: 4

  # 处理普通优先级读请求的线程池线程数量
  normal-concurrency: 4

  # 处理低优先级读请求的线程池线程数量
  low-concurrency: 4

  # 高优先级线程池中单个线程允许积压的最大任务数量，超出后会返回 Server Is Busy
  max-tasks-per-worker-high: 2000

  # 普通优先级线程池中单个线程允许积压的最大任务数量，超出后会返回 Server Is Busy
  max-tasks-per-worker-normal: 2000

  # 低优先级线程池中单个线程允许积压的最大任务数量，超出后会返回 Server Is Busy
  max-tasks-per-worker-low: 2000

  # Storage 读线程池中线程的栈大小 （单位：KB|MB|GB）
  stack-size: 10MB


# 协处理器线程池相关的配置项
readpool.coprocessor:
  # 处理高优先级 Coprocessor 请求（如点查）的线程池线程数量。默认值：CPU * 0.8
  high-concurrency

  # 处理普通优先级 Coprocessor 请求的线程池线程数量。默认值：CPU * 0.8
  normal-concurrency

  # 处理低优先级 Coprocessor 请求（如扫表）的线程池线程数量。默认值：CPU * 0.8
  low-concurrency

  # 高优先级线程池中单个线程允许积压的任务数量，超出后会返回 Server Is Busy。
  max-tasks-per-worker-high: 2000

  # 普通优先级线程池中单个线程允许积压的任务数量，超出后会返回 Server Is Busy。
  max-tasks-per-worker-normal: 2000

  # 低优先级线程池中单个线程允许积压的任务数量，超出后会返回 Server Is Busy。
  max-tasks-per-worker-low: 2000


# 存储相关的配置项。

storage:

  # 是否为 RocksDB 的所有 CF 都创建一个 `shared block cache`。
  # 推荐设置：capacity = MEM_TOTAL * 0.5 / TiKV 实例数量
  block-cache:
    capacity: "1GB"

  # scheduler 一次获取最大消息个数
  scheduler-notify-capacity: 10240

  # scheduler 内置一个内存锁机制，防止同时对一个 key 进行操作。每个 key hash 到不同的槽。
  scheduler-concurrency: 2048000

  # scheduler 线程个数，主要负责写入之前的事务一致性检查工作
  scheduler-worker-pool-size: 4

  # 写入数据队列的最大值，超过该值之后对于新的写入 TiKV 会返回 Server Is Busy 错误。
  scheduler-pending-write-threshold: 100MB


# raftstore 相关的配置项
raftstore:

  # 数据、log 落盘是否 sync，注意：设置成 false 可能会丢数据。
  sync-log: true

  # 开启 Prevote 的开关，开启有助于减少隔离恢复后对系统造成的抖动。
  prevote: true

  # raft 库的路径，默认存储在 storage.data-dir/raft 下
  raftdb-path:

  # 状态机 tick 一次的间隔时间
  raft-base-tick-interval: 1s

  # 发送心跳时经过的 tick 个数，即每隔 raft-base-tick-interval * raft-heartbeat-ticks 时间发送一次心跳。
  raft-heartbeat-ticks: 2

  # 发起选举时经过的 tick 个数，即如果处于无主状态，大约经过 raft-base-tick-interval * raft-election-timeout-ticks 时间以后发起选举
  # 最小值：raft-heartbeat-ticks
  raft-election-timeout-ticks: 10



# rocksdb 相关的配置项。
rocksdb:
  # RocksDB 后台线程个数。
  max-background-jobs: 8

  # RocksDB 进行 subcompaction 的并发个数。
  max-sub-compactions: 1

  # RocksDB 可以打开的文件总数。
  max-open-files: 40960

  # RocksDB Manifest 文件最大大小。
  max-manifest-file-size: 128MB

  # 自动创建 DB 开关。
  create-if-missing: true


# rocksdb defaultcf 相关的配置项。
rocksdb.defaultcf
  # 设置 rocksdb block 大小
  block-size: 64KB

  # 设置 rocksdb block 缓存大小。默认值：机器总内存 / 4
  block-cache-size

  # 开启或关闭 block cache。
  disable-block-cache: false

  # 开启或关闭缓存 index 和 filter。
  cache-index-and-filter-blocks: true


# Titan 相关的配置项。
rocksdb.titan:

  # 开启或关闭 Titan。默认值：false
  enabled: true

  # Titan Blob 文件存储目录。默认值：titandb
  dirname:

  # 控制是否关闭 Titan 对 Blob 文件的 GC。
  disable-gc: false

  # Titan 后台 GC 的线程个数。
  max-background-gc: 1


# rocksdb defaultcf titan 相关的配置项。
rocksdb.defaultcf.titan:

  # 最小存储在 Blob 文件中 value 大小，低于该值的 value 还是存在 LSM-Tree 中。
  min-blob-size: 1KB

  # Blob 文件所使用的压缩算法，可选值：no, snappy, zlib, bzip2, lz4, lz4hc, zstd。
  blob-file-compression: lz4

  # Blob 文件的 cache 大小，默认：0GB
  blob-cache-size: 0GB

  # 做一次 GC 所要求的最低 Blob 文件大小总和
  min-gc-batch-size: 16MB


# rocksdb writecf 相关的配置项。
rocksdb.writecf:
  # block cache size。默认值：机器总内存 * 15%.单位：MB|GB
  block-cache-size

  # 开启优化 filter 的命中率的开关。
  optimize-filters-for-hits: false

  # 开启将整个 key 放到 bloom filter 中的开关。
  whole-key-filtering: false


#  ocksdb lockcf 相关配置项。
rocksdb.lockcf:

  # block cache size。默认值：机器总内存 * 2% 。 单位：MB|GB
  block-cache-size:

  # 开启优化 filter 的命中率的开关。
  optimize-filters-for-hits: false


# raftdb 相关配置项。
raftdb:
  # RocksDB 后台线程个数。
  max-background-jobs: 2

  # RocksDB 进行 subcompaction 的并发数。
  max-sub-compactions: 1

  # WAL 存储目录。默认值：/tmp/tikv/store
  wal-dir:


# import 导入相关的配置项。
import:
  # 处理 RPC 请求线程数。
  num-threads: 8

  # 并发导入工作任务数。
  num-import-jobs: 8
```
