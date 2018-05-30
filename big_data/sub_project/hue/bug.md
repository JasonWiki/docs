# 出现以下 BUG

## 1. HUE - Couldn't find log associated with operation handle

- [解决地址](https://community.cloudera.com/t5/Web-UI-Hue-Beeswax/Hue-3-8-1-throws-an-error-when-running-SQL-against-Howrtonworks/td-p/33101)

```
错误:
  Couldn't find log associated with operation handle: OperationHandle [opType=EXECUTE_STATEMENT, getHandleIdentifier()=40fad20f-8413-4bd1-b6c8-9f0e858e3262]

解决 1:
  hue.ini
  use_get_log_api=true

解决 2:
  这可能是 hiveserver2 错误引起的, 重启 hiveserver2

```
