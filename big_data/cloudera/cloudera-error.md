# CDH 常见错误

## Service Monitor 和 Activity Monitor 无法启动错误

- 现象: 无法启动
- 原因: 追查日志
  ``` sh
    报错 C  [libzip.so+0x5ac0]  readCEN+0x7b0 , 这里是报错原因
    Failed to write core dump. Core dumps have been disabled. To enable core dumping, try "ulimit -c unlimited" before starting Java again
  ```
- 解决
  ``` sh
    Activity Monitor 的 Java 配置选项, 加下面这句到 JVM 参数配置中, 最后重启服务即可

    禁用内存映射
    -Dsun.zip.disableMemoryMapping=true
  ```
