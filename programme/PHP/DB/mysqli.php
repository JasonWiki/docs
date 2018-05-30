<?php 

	//过程连接数据库

$_mysqli = new mysqli('数据库地址','数据库用户名','数据库密码','选择一款连接的数据库',3306);
if (mysqli_connect_errno()) {//容错处理
	echo '数据库连接错误!错误代码'.mysqli_connect_errno();
	exit();
} 
$_mysqli->set_charset('utf8');	//设置字符集

$_sql = '这里是sql语句';
$_result = $_mysqli->query($_sql);//资源句柄

$_html = array();
while (!!$_objects = $_result->fetch_object()) {	//返回结果集

	$_html[] = $_objects;//保存在数组中
}


$_result->free();	//销毁资源句柄
$_result = null;	//销毁对象
$_mysqli->close();//关闭数据库
$_mysqli = null;	//销毁对象



print_r($_html);

?>