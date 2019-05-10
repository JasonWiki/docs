# 压力测试工具 wrk

- [wrk](https://github.com/wg/wrk)

``` sh
# wrk
-c, --connections: 总的http并发数

-d, --duration:    持续压测时间, 比如: 2s, 2m, 2h

-t, --threads:     总线程数

-s, --script:      luajit脚本,使用方法往下看

-H, --header:      添加http header, 比如. "User-Agent: wrk"

    --latency:     在控制台打印出延迟统计情况

    --timeout:     http超时时间


# 结果说明
Latency：响应时间
Req/Sec：每个线程每秒钟的完成的请求数

Avg：平均
Max：最大
Stdev：标准差
+/- Stdev： 正负一个标准差占比


# 使用 12 个线程, 1000 个 http 并发, 运行 30 秒
wrk -t12 -c1000 -d30s --latency http://xxx.com
```
