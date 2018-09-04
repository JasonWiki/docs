<?php 
/**
 * AJAX跨域时，代理处理，必须支持CURL扩展，统一请求为POST
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);	//屏蔽一些错误
/**
 * CURD发送类
 * @param Data $content
 * @param String $_URL
 */
function Wade_send_gcm_notify($content,$_URL) {

	$headers = array(
			//	'Authorization: key=' . $_GOOGLE_API_KEY,
			"Content-type: application/x-www-form-urlencoded" //application/json
			//	'Content-Type: application/x-www-form-urlencoded'		//application/x-www-form-urlencoded(表单)  multipart/form-data(原始数据)
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $_URL);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	//头文件信息
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);	//请求信息

	$result = curl_exec($ch);
	if ($result === false) {
		exit('underdog effect: ' . curl_error($ch));
	}

	curl_close($ch);

	return $result;
}


/* 以下是对跨域的处理 */

$post_data = $_POST;		//接收请求的数据
$post_data_format = '';		//格式化数据后，请求给实际服务器。

foreach ($post_data as $k=>$v)
{
	if ($k == 'server_url') continue;				//把URL地址从请求中去除
	$post_data_format.= "$k=".$v."&";			//组合成表单格式的字符串
}


/* 执行请求实际服务器 */
$result = Wade_send_gcm_notify($post_data_format,$post_data['server_url']);

echo $result;



?>