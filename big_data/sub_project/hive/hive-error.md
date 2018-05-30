# Hive 错误处理

## Hive Runtime Error while processing row 错误

``` sql
-- 开关矢量化试试
-- 控制是否启用查询执行的向量模式
SET hive.vectorized.execution.enabled=false;
-- ?
SET hive.vectorized.execution.reduce.enabled=false;
```
