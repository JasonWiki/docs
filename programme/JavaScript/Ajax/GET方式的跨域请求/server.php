<?php

//$name = "我的名字叫：".$_REQUEST['name']; 
//echo $_REQUEST["callbackparam"]."(".json_encode($name).")"; 

function callback($status, $msg = 'Yes!',$data = array()) {
		$return = array(
				'status' => $status,
				'msg' => $msg,
				'data' => $data,
				'num' => count($data),
		);	
		header('charset=utf-8');	
		//die(json_encode($return));
		exit(JSON($return));
	}

//callback(1,'aaaa');


$client_fn = $_GET['callback'];		//客户端请求的函数名

$data = array($client_fn,2,'你好');	//回馈数据

$return = array(
	'status' => 0,
	'msg' => '成功！',
	'data' => $data,
	'num' => count($data),
);	

echo $client_fn.'('.json_encode($return).')';

exit;



?>