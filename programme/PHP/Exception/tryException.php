<?php
header('Content-Type:text/html;charset=utf-8');
/*异常：
 * 程序运行过程中发生了意外而产生的错误。如：服务器断电，电脑断网等，导致程序无法运行。这种情况称之为异常，而不是错误。
 * Exception 类，捕获异常
 * 
 * PS:大部分异常要自己抛出
 */

$a = 5;
$b = 0;

try {	//尝试运行的语句，可能出现异常的语句
	if ($b == 0) throw new Exception('被除数不得为0',9);
	echo '123';
} catch (Exception $e) {	//出现异常执行的语句
	echo $e->getMessage();	//错误消息
	echo "<br />";
	echo $e->getCode();		//错误代码
}



?>