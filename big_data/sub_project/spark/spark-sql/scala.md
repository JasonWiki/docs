# Spark Sql

- [spark sql](http://www.infoq.com/cn/articles/apache-spark-sql)

## 一、Spark SQL

### 1. sqlContext

- SparkContext 创建上下文
- 基于 SparkContext 创建 sqlContext 上下文
- sqlContext 操作源数据

#### 1.1 测试数据源

``` txt
vim /data/log/customers.txt

100, John Smith, Austin, TX, 78727
200, Joe Johnson, Dallas, TX, 75201
300, Bob Jones, Houston, TX, 77028
400, Andy Davis, San Antonio, TX, 78227
500, James Williams, Austin, TX, 78727
```

### 1.2 sqlContext 编程 - 指定模式 操作 sql

- [sqlContext 编程 - 指定模式 官方文档](http://spark.apache.org/docs/1.5.2/sql-programming-guide.html#programmatically-specifying-the-schema)
- [sqlContext 编程 - 指定模式 指导文档](http://www.infoq.com/cn/articles/apache-spark-sql)

``` java
import org.apache.spark.{SparkConf, SparkContext};

val sparkConf = new SparkConf()
    sparkConf.setAppName("SparkSQLHiveOnYarn")
    sparkConf.setMaster("local[2]")
val sc = new SparkContext(sparkConf)

val sqlContext = new org.apache.spark.sql.SQLContext(sc)

// 创建RDD对象
val rddCustomers = sc.textFile("/data/log/customers.txt")

// 导入 Spark SQL Row
import org.apache.spark.sql.Row;

// 导入 Spark SQL 数据类型
import org.apache.spark.sql.types.{StructType, StructField, StringType};

 // 用字符串编码模式
val schemaString = "customer_id name city state zip_code"
// 用模式字符串生成模式对象
val schema = StructType(schemaString.split(" ").map(fieldName => StructField(fieldName, StringType, true)))

// 将RDD（rddCustomers）记录转化成Row。
val rowRDD = rddCustomers.map(_.split(",")).map(p => Row(p(0).trim,p(1),p(2),p(3),p(4)))

// 将模式应用于RDD对象。
val dfCustomers = sqlContext.createDataFrame(rowRDD, schema)

// 将DataFrame注册为表
dfCustomers.registerTempTable("customers")

// 用sqlContext对象提供的sql方法执行SQL语句。
val custNames = sqlContext.sql("SELECT name FROM customers")

// SQL查询的返回结果为DataFrame对象，支持所有通用的RDD操作。
// 可以按照顺序访问结果行的各个列。
custNames.map(t => "Name: " + t(0)).collect().foreach(println)

// 用sqlContext对象提供的sql方法执行SQL语句。
val customersByCity = sqlContext.sql("SELECT name,zip_code FROM customers ORDER BY zip_code")

// SQL查询的返回结果为DataFrame对象，支持所有通用的RDD操作。
// 可以按照顺序访问结果行的各个列。
customersByCity.map(t => t(0) + "," + t(1)).collect().foreach(println)

```


### 2. SparkContext -> HiveContext 操作 hive table

- SparkContext 创建上下文
- 基于 SparkContext 创建 HiveContext 上下文
- 使用 HiveContext 去操作 hive table
- [HiveContext 操作指导文章](http://lxw1234.com/archives/2015/08/466.htm)

``` java

import org.apache.spark.{SparkConf, SparkContext};
import org.apache.spark.sql._;
import org.apache.spark.sql.hive.HiveContext;

// spark 设置配置与上线文
val sparkConf = new SparkConf()
sparkConf.setAppName("SparkSQLHiveOnYarn")
sparkConf.setMaster("local[2]")
val sc = new SparkContext(sparkConf)

// 基于 SparkContext 上下文配置 HiveContext
val hiveContext = new HiveContext(sc)
// 配置 hive 元数据服务
hiveContext.setConf("hive.metastore.uris", "thrift://NameNode:9083")

// 通过 hiveContext 操作 hive table 数据, 写法一
    hiveContext.sql("SHOW DATABASES").collect().foreach { x => println(x) }
    hiveContext.sql("SHOW TABLES").collect().foreach { x => println(x) }

    hiveContext.sql("CREATE TABLE IF NOT EXISTS src (key INT, value STRING)")
    hiveContext.sql("LOAD DATA LOCAL INPATH 'examples/src/main/resources/kv1.txt' INTO TABLE src")
    // Queries are expressed in HiveQL
    hiveContext.sql("FROM src SELECT key, value").collect().foreach(println)

// 通过 hiveContext 操作 hive 的数据, 写法二
    // 导入语句，可以隐式地将 RDD 转化成 DataFrame
    import hiveContext.implicits._  ;
    import hiveContext.sql;
    sql("SHOW DATABASES").collect().foreach { x => println(x) }
    sql("SHOW TABLES").collect().foreach { x => println(x) }

```


### 3. 通过 Thriftserver 连接 Hive

- 启动 spark 的 Thriftserver
- 通过 hive 客户端连接 Thriftserver 服务器
- 操作 hive 数据

``` java

import java.util.Map;
import java.util.HashMap;
import java.util.List;
import java.util.ArrayList;
import java.util.Arrays;

import java.sql.SQLException;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.Statement;
import java.sql.DriverManager;
import org.apache.hive.jdbc.HiveDriver;

public class HiveClient {

    private static String driverName = "org.apache.hive.jdbc.HiveDriver";

    /**
     * 获取连接
     */
    private Connection connection;
    public Connection getConnection() {
        return connection;
    }
    public void setConnection(Connection conn) {
        this.connection = conn;
    }


    /**
     * 获取连接句柄
     */
    private Statement stmt;
    public Statement getStmt() {
        return stmt;
    }
    public void setStmt(Statement stmt) {
        this.stmt = stmt;
    }


    public HiveClient(String url, String user, String password) throws SQLException {
        // 导入类
        try {
            Class.forName(driverName);
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        }

        // 创建连接
        Connection con = DriverManager.getConnection(url, user, password);
        this.setConnection(con);

        // 创建连接句柄语句
        Statement stmt = con.createStatement();
        this.setStmt(stmt);
    }


    /**
     * 执行指定 Sql
     * @param sql
     * @return 布尔值
     */
    public Boolean execute (String sql) {
        Boolean rs = false;
        try {
            rs = this.getStmt().execute(sql);
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return rs;
    }


    /**
     * 查询数据
     * @param sql
     * @param fields 字段
     * @return List<Map<String, String>>
     * @throws SQLException
     */
    public List<Map<String, String>> select(String sql, String fields) throws SQLException {
        // 保存结果数据
        List<Map<String, String>> listResult = new ArrayList<Map<String, String>>();

        ResultSet res = null;
        try {
            res = this.getStmt().executeQuery(sql);
        } catch (SQLException e) {
            e.printStackTrace();
        }

        // 转换为数组
        String[] arrFields = fields.split(",");

        // 遍历每一行
        while (res.next()) {
            // 保存一行数据
            Map<String, String> mapRowData = new HashMap<String, String>();

            // 拼接字段值
            for (String field : arrFields) {
                mapRowData.put(field, res.getString("broker_id"));
            }
            // 追加到 list 中
            listResult.add(mapRowData);
        }

        /**
        for (Map<String, String> rs : listResult) {
            System.out.println(rs.get("broker_id"));
        }
        */

        return listResult;
    }


    /**
     * 关闭连接
     */
    public void closeConnection() {
        try {
            this.getConnection().close();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

}

```
