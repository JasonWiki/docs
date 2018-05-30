
- server 表
-- clusters.CLUSTER_ID (需要修改)
INSERT INTO scm.services (`SERVICE_ID`, `OPTIMISTIC_LOCK_VERSION`, `NAME`, `SERVICE_TYPE`, `CLUSTER_ID`, `MAINTENANCE_COUNT`, `DISPLAY_NAME`, `GENERATION`)
VALUES
	(19, 273, 'hive', 'HIVE', 3, 0, 'Hive', 1),
	(20, 40, 'zookeeper', 'ZOOKEEPER', 3, 0, 'ZooKeeper', 1),
	(21, 110, 'hue', 'HUE', 3, 0, 'Hue', 1),
	(22, 115, 'oozie', 'OOZIE', 3, 0, 'Oozie', 1),
	(23, 508, 'yarn', 'YARN', 3, 0, 'YARN (MR2 Included)', 1),
	(24, 296, 'hdfs', 'HDFS', 3, 0, 'HDFS', 1),
	(26, 113, 'flume', 'FLUME', 3, 0, 'Flume', 1),
	(32, 109, 'hbase', 'HBASE', 3, 0, 'HBase', 1);


- role_config_groups 表
-- services.SERVICE_ID(无需修改)
INSERT INTO scm.role_config_groups (`ROLE_CONFIG_GROUP_ID`, `ROLE_TYPE`, `NAME`, `OPTIMISTIC_LOCK_VERSION`, `BASE`, `DISPLAY_NAME`, `SERVICE_ID`)
VALUES
	(144, 'AGENT', 'flume-AGENT-BASE', 2, 00000000, 'Agent Default Group', 26),
	(137, 'BALANCER', 'hdfs-BALANCER-BASE', 7, 10000000, 'Balancer Default Group', 24),
	(133, 'DATANODE', 'hdfs-DATANODE-BASE', 6, 00000000, 'DataNode Default Group', 24),
	(134, 'FAILOVERCONTROLLER', 'hdfs-FAILOVERCONTROLLER-BASE', 1, 10000000, 'Failover Controller Default Group', 24),
	(123, 'GATEWAY', 'hive-GATEWAY-BASE', 0, 00000000, 'Gateway Default Group', 19),
	(131, 'GATEWAY', 'yarn-GATEWAY-BASE', 0, 00000000, 'Gateway Default Group', 23),
	(136, 'GATEWAY', 'hdfs-GATEWAY-BASE', 0, 00000000, 'Gateway Default Group', 24),
	(166, 'GATEWAY', 'hbase-GATEWAY-BASE', 0, 00000000, 'Gateway Default Group', 32),
	(167, 'HBASERESTSERVER', 'hbase-HBASERESTSERVER-BASE', 2, 00000000, 'HBase REST Server Default Group', 32),
	(169, 'HBASETHRIFTSERVER', 'hbase-HBASETHRIFTSERVER-BASE', 2, 00000000, 'HBase Thrift Server Default Group', 32),
	(121, 'HIVEMETASTORE', 'hive-HIVEMETASTORE-BASE', 1, 10000000, 'Hive Metastore Server Default Group', 19),
	(142, 'HIVEMETASTORE', 'hive-HIVEMETASTORE-1', 0, 00000000, 'Hive Metastore Server Group 1', 19),
	(120, 'HIVESERVER2', 'hive-HIVESERVER2-BASE', 1, 10000000, 'HiveServer2 Default Group', 19),
	(140, 'HTTPFS', 'hdfs-HTTPFS-BASE', 1, 10000000, 'HttpFS Default Group', 24),
	(126, 'HUE_LOAD_BALANCER', 'hue-HUE_LOAD_BALANCER-BASE', 0, 00000000, 'Load Balancer Default Group', 21),
	(125, 'HUE_SERVER', 'hue-HUE_SERVER-BASE', 1, 10000000, 'Hue Server Default Group', 21),
	(130, 'JOBHISTORY', 'yarn-JOBHISTORY-BASE', 1, 10000000, 'JobHistory Server Default Group', 23),
	(139, 'JOURNALNODE', 'hdfs-JOURNALNODE-BASE', 6, 00000000, 'JournalNode Default Group', 24),
	(127, 'KT_RENEWER', 'hue-KT_RENEWER-BASE', 0, 00000000, 'Kerberos Ticket Renewer Default Group', 21),
	(170, 'MASTER', 'hbase-MASTER-BASE', 1, 10000000, 'Master Default Group', 32),
	(135, 'NAMENODE', 'hdfs-NAMENODE-BASE', 2, 00000000, 'NameNode Default Group', 24),
	(141, 'NFSGATEWAY', 'hdfs-NFSGATEWAY-BASE', 0, 00000000, 'NFS Gateway Default Group', 24),
	(132, 'NODEMANAGER', 'yarn-NODEMANAGER-BASE', 3, 10000000, 'NodeManager Default Group', 23),
	(128, 'OOZIE_SERVER', 'oozie-OOZIE_SERVER-BASE', 5, 10000000, 'Oozie Server Default Group', 22),
	(168, 'REGIONSERVER', 'hbase-REGIONSERVER-BASE', 2, 00000000, 'RegionServer Default Group', 32),
	(129, 'RESOURCEMANAGER', 'yarn-RESOURCEMANAGER-BASE', 2, 00000000, 'ResourceManager Default Group', 23),
	(138, 'SECONDARYNAMENODE', 'hdfs-SECONDARYNAMENODE-BASE', 2, 00000000, 'SecondaryNameNode Default Group', 24),
	(124, 'SERVER', 'zookeeper-SERVER-BASE', 15, 10000000, 'Server Default Group', 20),
	(122, 'WEBHCAT', 'hive-WEBHCAT-BASE', 2, 00000000, 'WebHCat Server Default Group', 19);


- roles 表
-- services.SERVICE_ID
-- role_config_groups.ROLE_CONFIG_GROUP_ID
-- hosts.HOST_ID (需要修改)

-- 获取 ROLE_NAME 与 HOST_NAME 映射关系
DROP TABLE IF EXISTS temp_db.restore___role_host_map;
CREATE TABLE temp_db.restore___role_host_map AS
SELECT
  r.NAME AS ROLE_NAME,
  r.HOST_ID AS HOST_ID,
  h.NAME as HOST_NAME,
  new_hosts.HOST_ID AS NEW_HOST_ID
FROM temp_db.roles AS r
LEFT JOIN temp_db.hosts AS h
	ON r.HOST_ID = h.HOST_ID
LEFT JOIN scm.hosts AS new_hosts
  ON h.NAME = new_hosts.NAME
ORDER BY r.NAME ASC
;


-- 定位需要恢复的 role
DROP TABLE IF EXISTS temp_db.restore___role;
CREATE TABLE temp_db.restore___role AS
SELECT
  r.ROLE_ID, r.NAME,
  h.HOST_ID,
  r.ROLE_TYPE, r.CONFIGURED_STATUS, r.SERVICE_ID, r.MERGED_KEYTAB, r.MAINTENANCE_COUNT, r.DECOMMISSION_COUNT, r.OPTIMISTIC_LOCK_VERSION, r.ROLE_CONFIG_GROUP_ID, r.HAS_EVER_STARTED
  ,h.NAME AS HOST_NAME
-- roles 基础数据表
FROM temp_db.roles AS r
-- 通过 ROLE_NAME 找 HOST_NAME
LEFT JOIN temp_db.restore___role_host_map AS rh
  ON r.NAME = rh.ROLE_NAME
-- HOST_NAME 找 HOST_ID
LEFT JOIN scm.hosts AS h
  ON rh.HOST_NAME = h.NAME
WHERE r.NAME NOT LIKE 'mgmt%'
ORDER BY r.NAME
;

-- 写入数据
INSERT INTO scm.roles (`ROLE_ID`, `NAME`, `HOST_ID`, `ROLE_TYPE`, `CONFIGURED_STATUS`, `SERVICE_ID`, `MERGED_KEYTAB`, `MAINTENANCE_COUNT`, `DECOMMISSION_COUNT`, `OPTIMISTIC_LOCK_VERSION`, `ROLE_CONFIG_GROUP_ID`, `HAS_EVER_STARTED`)
SELECT r.ROLE_ID, r.NAME, r.HOST_ID,r.ROLE_TYPE, r.CONFIGURED_STATUS, r.SERVICE_ID, r.MERGED_KEYTAB, r.MAINTENANCE_COUNT, r.DECOMMISSION_COUNT, r.OPTIMISTIC_LOCK_VERSION, r.ROLE_CONFIG_GROUP_ID, r.HAS_EVER_STARTED
FROM temp_db.restore___role AS r;



- configs 表
-- roles.ROLE_ID
-- services.SERVICE_ID
-- hosts.HOST_ID
-- config_container.CONFIG_CONTAINER_ID
-- role_config_groups.ROLE_CONFIG_GROUP_ID
-- external_accounts.EXTERNAL_ACCOUNT_ID

DROP TABLE IF EXISTS temp_db.restore___configs;
CREATE TABLE temp_db.restore___configs AS
SELECT
  `CONFIG_ID`, `ROLE_ID`, `ATTR`, `VALUE`, `SERVICE_ID`, cf.HOST_ID, rh.NEW_HOST_ID, `CONFIG_CONTAINER_ID`, `OPTIMISTIC_LOCK_VERSION`, `ROLE_CONFIG_GROUP_ID`, `CONTEXT`, `EXTERNAL_ACCOUNT_ID`
FROM temp_db.configs AS cf
LEFT JOIN (
  SELECT HOST_ID,NEW_HOST_ID
  FROM temp_db.restore___role_host_map
  GROUP BY HOST_ID
) AS rh
  ON cf.HOST_ID = rh.HOST_ID
-- 排除系统 config
WHERE cf.SERVICE_ID IS NOT NULL
;




DROP TABLE IF EXISTS temp_db.restore___configs;
CREATE TABLE temp_db.restore___configs AS
SELECT
  `CONFIG_ID`, `ROLE_ID`, `ATTR`, `VALUE`, cf.SERVICE_ID, `HOST_ID`, `CONFIG_CONTAINER_ID`, cf.OPTIMISTIC_LOCK_VERSION, `ROLE_CONFIG_GROUP_ID`, `CONTEXT`, `EXTERNAL_ACCOUNT_ID`
FROM temp_db.configs AS cf
LEFT JOIN temp_db.services AS s
  ON cf.SERVICE_ID = s.SERVICE_ID
WHERE cf.SERVICE_ID IS NOT NULL
  AND s.NAME <> 'mgmt'
;


SELECT * FROM temp_db.restore___configs;

-- 写入数据
INSERT INTO scm.configs (`CONFIG_ID`, `ROLE_ID`, `ATTR`, `VALUE`, `SERVICE_ID`, `HOST_ID`, `CONFIG_CONTAINER_ID`, `OPTIMISTIC_LOCK_VERSION`, `ROLE_CONFIG_GROUP_ID`, `CONTEXT`, `EXTERNAL_ACCOUNT_ID`)
SELECT `CONFIG_ID`, `ROLE_ID`, `ATTR`, `VALUE`, `SERVICE_ID`, `HOST_ID`, `CONFIG_CONTAINER_ID`, `OPTIMISTIC_LOCK_VERSION`, `ROLE_CONFIG_GROUP_ID`, `CONTEXT`, `EXTERNAL_ACCOUNT_ID`
FROM temp_db.restore___configs
ORDER BY SERVICE_ID ASC

;
