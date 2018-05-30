<?php //Mysql事物，就将2个SQL包含起来。2个SQL都成功，则处理。
			//如果其中一个失败，或2个都失败，则回滚。		
//MySql数据库类型为InnoDB			
$_mysqli = new mysqli();

$_mysqli->connect('127.0.0.1','root','514591','textguest');

$_mysqli->set_charset('utf8');

if (mysqli_connect_errno()) {
	echo "数据库连接出错，错误代码".mysqli_connect_error();
	exit();
}

//关闭自动提交(手工提交)
$_mysqli->autocommit(false);

$_sql = "UPDATE tg_flower SET tg_flower=tg_flower-50 WHERE tg_id=3;";
$_sql .= "UPDATE tg_flower SET tg_flower=tg_flower+50 WHERE tg_id=5";

//执行多条数据时，当都修改成功，就手工提交给数据库。错误，就回滚，撤销之前的操作
if ($_mysqli->multi_query($_sql)) {//如果第一条SQL执行成功
	//通过影响行数，判断是否成功执行了SQL
	$_success1 = $_mysqli->affected_rows == 1 ? true : false;
		
	$_mysqli->next_result();//执行下语句SQL
	$_success2 = $_mysqli->affected_rows == 1 ? true : false;
	
	if (($_success1 && $_success2) == true) {
		$_mysqli->commit();	//执行sql语句，进行手工提交
		echo '提交成功';
	} else {
		$_mysqli->rollback();//回滚机制，撤销之前的所有的操作
		echo '提交失败，操作取消';	
	}
	
} else {
	echo '第一条ql出错';
}

//再次开启自动提交
$_mysqli->autocommit(true);


?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	