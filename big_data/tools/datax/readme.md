# DataX

- 数据传输工具

``` sh
生成规则
python bin/datax.py -r hdfsreader -w mysqlwriter

运行
python {DATAX_HOME}/bin/datax.py {JSON_FILE_NAME}.json


python bin/datax.py conf/hdfsreader_mysqlwriter.json
```


``` json


{
    "job": {
        "content": [
            {
                "reader": {
                    "name": "hdfsreader",
                    "parameter": {
                        "defaultFS": "hdfs://nameservice1",
                        "path": "/user/hive/warehouse/temp_db.db/dm_safe_day_active_loss_trace/*",
                        "encoding": "UTF-8",
                        "fileType": "orc",
                        "column": ["*"],
                        "fieldDelimiter": ",",
                        "hadoopConfig":{
                             "dfs.nameservices": "nameservice1",
                             "dfs.ha.namenodes.nameservice1": "namenode103,namenode95",
                             "dfs.namenode.rpc-address.nameservice1.namenode103": "dw1:8020",
                             "dfs.namenode.rpc-address.nameservice1.namenode95": "dw2:8020",
                             "dfs.client.failover.proxy.provider.nameservice1": "org.apache.hadoop.hdfs.server.namenode.ha.ConfiguredFailoverProxyProvider"
                       }
                    }
                },


                "writer": {
                    "name": "mysqlwriter",
                    "parameter": {
                        "column": [""],
                        "connection": [
                            {
                                "jdbcUrl": "jdbc:mysql://dw0:3306/test?useUnicode=true&tinyInt1isBit=false&characterEncoding=utf-8",
                                "table": ["dm_safe_day_active_loss_trace"]
                            }
                        ],
                        "username": "dw_service",
                        "password": "dw_service_818",
                        "preSql": [],
                        "session": [],
                        "writeMode": "insert",
                        "batchSize": "1024"
                    }
                },


                "writer": {
                    "name": "streamwriter",
                    "parameter": {
                        "print": true
                    }
                }
            }
        ],
        "setting": {
            "speed": {
                "channel": "1"
            }
        }
    }
}


```


``` sql

CREATE TABLE temp_db.dm_safe_day_active_loss_trace AS SELECT * FROM dm_db.dm_safe_day_active_loss_trace;


~/develop/jason/datax$ python bin/datax.py conf/hdfsreader_mysqlwriter.json -p "-DdbName=temp_db -DtbName=dm_safe_day_active_loss_trace -Dwhere='DELETE FROM temp_db.dm_safe_day_active_loss_trace WHERE monitor_days = 30' -Dcol='{\"index\": 0,\"type\": \"string\"}'"

```
