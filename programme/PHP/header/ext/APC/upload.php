<?php 
header('Content-Type:text/html;charset=utf-8');
/**
 * 1.判断是否为post提交
 * @$value  post提交的值
 * return 布尔值
 */
function isPost($value) {
	//是post提交 ，并且post值存在，或者post值不为空
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($value) && !empty($value)) {
		return true;
	} else {
		return false;
	}
}

function dump($var, $echo=true, $label=null, $strict=true) {
	$label = ($label === null) ? '' : rtrim($label) . ' ';
	if (!$strict) {
		if (ini_get('html_errors')) {
			$output = print_r($var, true);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		} else {
			$output = $label . print_r($var, true);
		}
	} else {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		if (!extension_loaded('xdebug')) {
			$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		}
	}
	if ($echo) {
		echo($output);
		return null;
	}else
		return $output;
}
/**
 *1. 根据字节大小，计算相应的大小
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 * @return string
 */
function byte_format($size, $dec=2) {
	$a = array("B", "KB", "MB", "GB", "TB", "PB");
	$pos = 0;
	while ($size >= 1024) {
		$size /= 1024;
		$pos++;
	}
	return round($size,$dec)." ".$a[$pos];
}


/**
 * 通过APC插件实现上传文件进度
一、配置
phpini中APC配置
extension=php_apc.dll			//导入插件库ext目录中
apc.rfc1867 = on
apc.max_file_size = 100M
//上传文件大小配置
upload_max_filesize = 100M
post_max_size = 100M
//把所有值都设置为一样即可
apc.max_file_size,  设置apc所支持上传文件的大小，要求apc.max_file_size <=upload_max_filesize  并且
apc.max_file_size <=post_max_size.

二、代码实现思路
1、上传表达中添加，如下代码,其中APC_UPLOAD_PROGRESS不可变。value是对应一个上传表单中的文件。
<input type="text" name="APC_UPLOAD_PROGRESS" value="demo1" />

2、执行上传文件后，通过$_GET['progress_key']==demo1 去获取指定表单中的上传缓存文件。

3.返回的是上传文件在服务器的缓存信息，通过这个信息，外部程序调用取得上传进度。
$cacheInfo = apc_fetch($_GET['progress_key']);	
$cacheInfo  = (
    [total] => 29102			//总文件大小
    [current] => 29102		//当前进度文件大小
    [rate] => 232816			//速度(多少字节)
    [filename] => 20100504_570112.jpg		//文件名
    [name] => file				//表单字段名
    [temp_filename] => C:\WINDOWS\TEMP\phpE2.tmp		//零时路径
    [cancel_upload] => 0
    [done] => 1					//是否上传完成
)

4.比如计算百分比
current / total * 100	//就是百分比

 */




//取得缓存中上传进度
function getprogress () {
	$cacheName = 'upload_'.$_GET['progress_key'];//缓存名
	$cacheInfo = apc_fetch($cacheName);  					//获取当前上传文件信息
	
	//计算上传进度
	if ($cacheInfo) $nowprogress  =  ($cacheInfo['current']/$cacheInfo['total'])*100;
	
	//上传完毕清除缓存
	if ($cacheInfo['done'] ==1 &&  $nowprogress ==100) {
		if ($cacheInfo) apc_delete($cacheName);	//清除缓存
	}
	
	return $cacheInfo;
}


//上传请求
if (isPost($_FILES)) { 
		
	foreach ($_FILES as $key=>$val) {
		//组合文件名后缀
		$filename = explode('.',$val['name']);//把字符串，分割成数组
		$fileHz = $filename[count($filename) - 1];//获取数组后最后一位的下标(获取文件名的后缀)

		//当零时文件存在
		if (is_uploaded_file($val['tmp_name'])) { 
			//move_uploaded_file(临时文件，移动到规定的目录)
			if (!move_uploaded_file($val['tmp_name'],'up/123.'.$fileHz)) {	//移动临时文件到新的目录中
				echo '{"info":"上传失败！","status":"n"}';
			} else {
				echo '{"info":"上传成功！","status":"y"}';
			}
		} else {
			echo '{"info":"文件不存在！","status":"n"}';
		}
	}

	//读取缓存上传文件的信息
} elseif (isset($_GET['progress_key'])) {   
	
	$nowInfo	=  getprogress();
	//dump($nowInfo);
	if ($nowInfo) {
		$jd = $Info['current'] / $nowInfo['total'] * 100;
		if ($nowInfo['done'] == true) {
			$st = 'y';
		} else {
			$st = 'n';
		}
		echo "{'info':$jd,'status':$st}";
		//dump($cacheInfo);	
	}
	exit;
	   
}





?>
