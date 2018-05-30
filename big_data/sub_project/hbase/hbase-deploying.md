# Hbase 部署



## 配置

```
HBase Thrift 服务器端口
 hbase.regionserver.thrift.port=9090

HBase Thrift 服务器绑定地址
  hbase.regionserver.thrift.ipaddress=0.0.0.0

HBase Thrift 服务器 Web UI 端口
  hbase.thrift.info.port=9095

RegionServer 中启动的 RPC 服务器实例数量。
  hbase.regionserver.handler.count=30

处理 RegionServer 中的优先级请求的处理程序的数量
  hbase.regionserver.metahandler.count=10

HLog 条目的同步间隔
  hbase.regionserver.optionallogflushinterval=1

RegionServer IPC Read 线程池大小
  hbase.ipc.server.read.threadpool.size=10


```
