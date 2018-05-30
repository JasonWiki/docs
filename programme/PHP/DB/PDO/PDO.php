<?php 
header('Content-Type:text/html;charset=utf-8');
/*	PDO 
 * 1.底层操作所有数据库的类
 * 2.需要在php配置文件中开启pdo相关数据库的功能，必须PHP 5.1以上版本
 * 3.PDO给开发者提供了三组类：PDO、PDOStatement、PDOException ；
 * 	    分别是：数据库使用、预处理、异常等操作
 * 4. PDOException  捕获PDO类的异常错误（包括SQL的语法错误）
 */

//连接数据库
try {
	$PDO = new PDO('mysql:host=127.0.01;dbname=thinkphp;charset=UTF8', 'root','514591');
	//报错模式
	//PDO::ERRMODE_EXCEPTION   //异常模式	（可以用错事务处理）
	//PDO::ERRMODE_WARNING  	 //错误模式
	$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$PDO->query('SET NAMES UTF8');	//设置字符集编码
	//获取系统相关属性
	//$PDO->getAttribute(PDO::ATTR_AUTOCOMMIT).'<br />';	//是否自动提交
	//$PDO->getAttribute(PDO::ATTR_SERVER_VERSION).'<br />';	//数据库版本
	//设置系统相关属性
	//$PDO->setAttribute(PDO::ATTR_AUTOCOMMIT, false);	//参数，@属性名，属性值
} catch (PDOException $e) {	//PDOException 出现错误时，抛出的异常
	exit('数据库连接错误：'.$e->getMessage());
}
//一、普通方法处理处理SQL
/* 1.exec($sql) 执行SQL查看影响的行数
$_sql = "INSERT INTO demo (name,pass,time) VALUES ('Wade','12345678','xxx')";
$state = $PDO->exec($_sql);	//影响的行数
if ($state) {
	echo 'OK';
} else {
	echo '错误代码：'.$PDO->errorCode();
	echo '错误信息：'.$PDO->errorInfo();
}
 */

/* 2.query($sql,返回数组类型)	//执行一句SQL，返回资源句柄
 * @参数: 
 *		1)PDO::FETCH_NUM  数字数组
 *		2)PDO::FETCH_ASSOC  关联数组
 * 		3)PDO::FETCH_OBJ  	对象关联数组

$_sql = "select * from demo";
$_stmt = $PDO->query($_sql,PDO::FETCH_ASSOC);	//资源句柄
foreach ($_stmt as $_row) {
	print_r($_row);
}					
 */

/* 3.	fetch() 解析资源句柄成一个数组
 	1) setFetchMode()	设置统一的结果集的数组类型
 *		
$_sql = "select * from demo";
$_stmt = $PDO->query($_sql);	//取得资源句柄
$_stmt->setFetchMode(PDO::FETCH_OBJ);	//设置统一的结果集类型

$array = array();
while ($_stmt->fetch()) {
	$array [] = $_stmt->fetch();
}
print_r($array);
 */


//二、使用准备语句，提高效率，避免过度的开销
/* 
1). prepare($_sql) 与 execute()进行增、删、改
$_sql = "INSERT INTO demo (name,pass,time) VALUES ('UCD','123','456')";
$_stmt = $PDO->prepare($_sql);		//准备语句SQL
$_stmt->execute();							//执行SQL

echo $_stmt->rowCount();				//影响行数
echo $PDO->lastInsertId();				//返回新增记录的id *

2).读取数据  fetchAll()
$_sql = "SELECT * FROM demo";
$_stmt = $PDO->prepare($_sql);		//准备语句
$_stmt->execute();							//执行SQL
$list = $_stmt->fetchAll(PDO::FETCH_ASSOC);		//解析结果集
var_dump($list);

//3). 插入多条数据
//索引数组模式
$_sql = "INSERT INTO demo (name,pass,time) VALUES (?,?,?)";
$_stmt = $PDO->prepare($_sql);		//准备语句SQL
$_stmt->execute(array('name1','pass1','time1'));	
$_stmt->execute(array('name2','pass2','time3'));		
$_stmt->execute(array('name3','pass3','time3'));		

//4). 关联数组模式
$_sql = "INSERT INTO demo (name,pass,time) VALUES (:name,:pass,:time)";
$_stmt = $PDO->prepare($_sql);		//准备语句SQL
$_stmt->execute(array(':name'=>'name1',':pass'=>'pass1',':time'=>'time1'));
$_stmt->execute(array(':name'=>'name2',':pass'=>'pass2',':time'=>'time2'));
$_stmt->execute(array(':name'=>'name3',':pass'=>'pass3',':time'=>'time3'));

 */


//5) .事务处理(MySql数据库类型为InnoDB才能用事务处理)
try {
	$PDO->beginTransaction();	//开启事务处理
	//sql1
	$_sql = "UPDATE demo SET time=time+50 WHERE id=10";
	$PDO->prepare($_sql)->execute();
	//sql2
	$_sql = "UPDATE demo SET time=time+50 WHERE id=11";
	$PDO->prepare($_sql)->execute();
	
	$PDO->commit();				//提交	
} catch (PDOException $e) {
	echo $e->getMessage();
	$PDO->rollBack();			//回滚
}


?>