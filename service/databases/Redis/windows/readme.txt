//端口6379

redis-server.exe：服务程序
redis-check-dump.exe：本地数据库检查
redis-check-aof.exe：更新日志检查
redis-benchmark.exe：性能测试，用以模拟同时由N个客户端发送M个 SETs/GETs 查询 (类似于 Apache 的ab 工具).

启动redis：
输入命令：redis-server.exe redis.conf

启动cmd窗口要一直开着，关闭后则Redis服务关闭。 


这时服务开启着，另外开一个窗口进行，设置客户端： 
输入命令：redis-cli.exe -h 127.0.0.1 -p 6379 


