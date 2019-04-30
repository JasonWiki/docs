# Mysql dml 数据库操作语言

## * 创建操作


```sql

1、登录 mysql
	mysql -uroot -p514591
	show databases;


3、获取表中所有字段名
		SELECT COLUMN_NAME from information_schema.COLUMNS
		WHERE table_name = 'your_table_name'
		AND table_schema = 'your_db_name';

4、查看表结构
		SHOW FULL FIELDS FROM oa_case_pics

5、字段子查询 (只能查询一条数据)
	 (SELECT SUM(count) FROM cms_vote  WHERE vid=t1.id) AS participants
	如：
	 	SELECT
						t1.id,
						t1.title,
						t1.state,
						(SELECT SUM(count) FROM cms_vote  WHERE vid=t1.id LIMIT 1) AS participants
			FROM
						cms_vote AS t1
		WHERE
						t1.vid=0
	ORDER BY
						t1.date DESC

6、条件子查询
		WHERE vid IN (SELECT id FROM cms_vote WHERE state=1);
		WHERE ct.nav in (SELECT
													id
										FROM
													cms_nav AS c
									WHERE
													c.pid='$this->nav')

7、筛选子查询
		ORDER BY
							(SELECT
									    	COUNT(*)
								FROM
									    	cms_comment AS c
							WHERE
									    	ct.id=c.cid) DESC


8、表连接
	SELECT n.name,n.title FROM app_group_user AS gu LEFT JOIN app_group_node AS gn ON gu.group_id = gn.group_id LEFT JOIN app_node AS n ON gn.node_id = n.id WHERE ( gu.user_id = 2 )


9、GROUP BY 语句
		GROUP BY 语句用于结合合计函数，根据一个或多个列对结果集进行分组。

		SELECT Customer,SUM(OrderPrice) FROM Orders
		GROUP BY Customer


10、HAVING 子句(对GROUP BY分组后的结果集进行帅选查询)
		在 SQL 中增加 HAVING 子句原因是，WHERE 关键字无法与合计函数一起使用。

		SELECT Customer,SUM(OrderPrice) FROM Orders
			GROUP BY Customer
			HAVING SUM(OrderPrice)<2000

		SELECT Customer,SUM(OrderPrice) FROM Orders
			WHERE Customer='Bush' OR Customer='Adams'
			GROUP BY Customer
			HAVING SUM(OrderPrice)>1500

	distinct 对 user_id 的记录只取一条
	SELECT distinct(user_id),app_name from dw_stage.dw_app_access_log;


11、获取字段详细信息
	SELECT
		 column_name AS `列名`,
		 data_type   AS `数据类型`,
		 character_maximum_length  AS `字符长度`,
		 numeric_precision AS `数字长度`,
		 numeric_scale AS `小数位数`,
		 is_nullable AS `是否允许非空`,
		 CASE WHEN extra = 'auto_increment'
		   THEN 1 ELSE 0 END AS `是否自增`,
		 column_default  AS  `默认值`,
		 column_comment  AS  `备注`
	FROM
	 Information_schema.columns
	WHERE
	  table_Name='test_table';


12. 插入复制数据

	-- 新增当月的数据
	INSERT INTO table_name1
	SELECT
		*
	-- 小区经纬度
	FROM table_name2 a
	;

	*  插入数据
		INSERT INTO table(account,password) values('evans','e10adc3949ba59abbe56e057f20f883e')

	* 修改数据
	  UPDATE table SET price=1 WHERE xx=xx
	  UPDATE table SET addr=REPLACE (addr,'成都','天府') WHERE time<'2013-11--5'


13. GROUP BY 内联排序
	-- 5.7 前
	SELECT id, name
	FROM (SELECT * FROM table ORDER BY id DESC) as tb
	GROUP BY name
	ORDER BY id DESC

	-- 5.7 中
	SELECT id, name
	FROM (SELECT * FROM table GROUP BY id ORDER BY id DESC) as tb
	GROUP BY name
	ORDER BY id DESC
```
