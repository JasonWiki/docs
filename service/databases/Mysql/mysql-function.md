# Mysql 函数

## 一、String

- [优秀文章](http://justdo2008.iteye.com/blog/1141609)

``` sql

1. substring_index(str,delim,count) 字符串截取
  截取第二个 '.' 之前的所有字符。
  SELECT substring_index('www.sqlstudy.com.cn', '.', 2);

  截取第二个 '.' （倒数）之后的所有字符。
  SELECT substring_index('www.sqlstudy.com.cn', '.', -2);

2. substring(str, pos) 字符串截取
  substring(str, pos, len)
  SELECT substring('sqlstudy.com', 4);

3. CONCAT('a','b','c')
  组合字符串
```


## 二、时间函数

- [优秀文章](http://blog.csdn.net/lwjnumber/article/details/7023566)

``` sql

1. UNIX_TIMESTAMP() 获取当前时间戳

  UNIX_TIMESTAMP("2015-06-01 12:12:00")  转换为时间戳


2. FROM_UNIXTIME() 格式化时间戳成需要的时间

  FROM_UNIXTIME(1218290027,'%Y-%m-%d %h:%i:%s');


3. str_to_date(str, format)  字符串转换为正常日期

  PS: 使用前需要查看 sql_mode 的参数
    show variables like '%sql_mode%';
    set sql_mode=NO_ENGINE_SUBSTITUTION;

  案例 1:
    set time_zone='+00:00';
    str_to_date('30 9','%i %h');  09:30:00
    '%s %i %H'

  案例 2: 把调度日期转换正常时间
    str_to_date(substring_index('30 9 * * *',' ',2),'%i %h') as schedule_seconds


4. 时间转换
  -- 时间 -> 秒
  SELECT time_to_sec('01:00:05'); -- 3605

  -- 秒 -> 时间
  SELECT sec_to_time(3605); -- '01:00:05'

  -- 获取当前日期
  SELECT NOW();

  -- 当天日期格式化
  DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s')

  -- 日期增减, 获取指定日期 14 天前的日期
  date_sub('2017-02-16', interval 14 day)


5. 日期应用
  一天前日期
  WHERE p_dt = date_sub(DATE_FORMAT(NOW(), '%Y-%m-%d'), interval 1 day)
  WHERE date(p_dt) = date_sub( DATE_FORMAT( NOW(), '%Y-%m-%d'), interval 1 day)

  -- 当前日期 14 天前和 当天范围
  WHERE p_dt BETWEEN date_sub( DATE_FORMAT( NOW(), '%Y-%m-%d'), interval 14 day) AND DATE_FORMAT( NOW() ,'%Y-%m-%d')

  -- 获取当前时间, 10 分钟前日期
  SELECT DATE_SUB(NOW(),INTERVAL 10 MINUTE);

  -- 获取当前时间, 3 小时钟前日期
  SELECT DATE_SUB(NOW(),INTERVAL 3 HOUR);


6. 表分区格式: p_dt = '2017-02-18', p_hours = '11', 对分区进行时间范围查询时
-- 组合分区成为一个日期格式
WHERE concat(p_dt, ' ', p_hours, ':00', ':00')
  -- 定位 1 个小时前日期格式
  BETWEEN from_unixtime(unix_timestamp("2017-02-17 11:00:00")-3600,'%Y-%m-%d %H:%i:%s')
  -- 定位当前小时日期格式
  AND '2017-02-17 11:00:00'




```


## 三、数据类型转换

``` sql
CAST(xxx AS 类型) , CONVERT(xxx,类型)，类型必须用下列的类型：

可用的类型：　   
  二进制,同带binary前缀的效果 : BINARY    
  字符型,可带参数 : CHAR()     
  日期 : DATE     
  时间: TIME     
  日期时间型 : DATETIME     
  浮点数 : DECIMAL      
  整数 : SIGNED     
  无符号整数 : UNSIGNED
```


## 四、聚合函数

``` sql

-- GROUP_CONCAT 方法, 聚合行成列
SELECT
  class_name,
  -- 组合字段
  GROUP_CONCAT(class_val separator ',') AS part_names,
FROM TBALE_NAME
GROUP BY
  class_name
;

```
