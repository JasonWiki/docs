# Hive metastore 结构

``` sql

-- Hive 文件存储信息相关的元数据表 (SDS、SD_PARAMS、SERDES、SERDE_PARAMS)
-- Hive表分区相关的元数据表 (PARTITIONS、PARTITION_KEYS、PARTITION_KEY_VALS、PARTITION_PARAMS)


-- 显示所有 hive 中所有的表
SELECT
  db_tb.DB_ID,
  db_tb.NAME,
  db_tb.DB_LOCATION_URI,
  db_tb.OWNER_NAME,
  db_tb.DESC,
  tb_tb.TBL_ID,
  tb_tb.SD_ID,
  tb_tb.TBL_NAME,
  tb_tb.TBL_TYPE,
  tb_tb.CREATE_TIME,
  tb_tb.OWNER AS tb_OWNER,
  tb_sds.LOCATION,
  tb_params.lastDdlTime,
  FROM_UNIXTIME(tb_params.lastDdlTime,'%Y-%m-%d %h:%i:%s') AS lastTime
FROM
  DBS AS db_tb

LEFT JOIN
  TBLS AS tb_tb
ON
  db_tb.DB_ID = tb_tb.DB_ID

LEFT JOIN
  SDS AS tb_sds
ON
  tb_sds.SD_ID = tb_tb.SD_ID

-- 表部分属性信息
LEFT JOIN (
  SELECT
    TBL_ID,
    PARAM_VALUE AS lastDdlTime
  FROM
    TABLE_PARAMS
  WHERE
    PARAM_KEY = 'transient_lastDdlTime'
) AS tb_params
ON
  tb_params.TBL_ID = tb_tb.TBL_ID


--WHERE tb_tb.TBL_NAME = 'jason_test_member'
;



--- 显示数据表的字段信息 START ---
SELECT
  tb_tb.TBL_ID,
  tb_tb.SD_ID,
  tb_tb.TBL_NAME,
  tb_sds.LOCATION,
  tb_columns.COLUMN_NAME,
  tb_columns.TYPE_NAME,
  tb_columns.COMMENT,
  tb_partition.PKEY_NAME,
  tb_partition.PKEY_TYPE,
  tb_partition.PKEY_COMMENT
FROM
  TBLS AS tb_tb

LEFT JOIN
  SDS AS tb_sds
ON
  tb_sds.SD_ID = tb_tb.SD_ID

LEFT JOIN
  COLUMNS_V2 AS tb_columns
ON
  tb_columns.CD_ID = tb_sds.CD_ID

LEFT JOIN
  PARTITION_KEYS AS tb_partition
ON
  tb_partition.TBL_ID = tb_tb.TBL_ID

WHERE
  tb_tb.TBL_NAME = 'dw_app_access_log'
ORDER BY
  tb_columns.INTEGER_IDX ASC
;


  -- 处理思路流程
  SELECT TBL_ID,SD_ID,TBL_NAME FROM TBLS WHERE TBL_NAME = 'dw_app_access_log';

  -- 显示存储格式以及地址信息 (通过 SD_ID = SD_ID)
  SELECT SD_ID,CD_ID,LOCATION FROM SDS WHERE SD_ID = 19054

  -- 通过 CD_ID 找出字段信息
  SELECT * FROM COLUMNS_V2 WHERE CD_ID = 17723

--- 显示数据表的字段信息 END ---



-- 显示表所有分区信息
SELECT
  tb_tb.TBL_ID,
  tb_tb.SD_ID,
  tb_tb.TBL_NAME,
  tb_par.PART_ID,
  tb_par.CREATE_TIME,
  tb_par.PART_NAME
FROM
  TBLS AS tb_tb
LEFT JOIN
  PARTITIONS AS tb_par
ON
  tb_par.TBL_ID = tb_tb.TBL_ID
WHERE
  tb_tb.TBL_NAME = 'dw_app_access_log'
;






-- 该表存储表/视图的属性信息。
SELECT * FROM TABLE_PARAMS WHERE TBL_ID = 66
-- 该表存储表/视图的授权信息
SELECT * FROM TBL_PRIVS WHERE TBL_ID = 66




```
