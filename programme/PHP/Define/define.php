<?php
header('Content-Type:text/html;charset=utf-8');
define('ROOT_PATH',dirname(__FILE__));//运行文件当前路径
date_default_timezone_set('Asia/Shanghai');	//设置亚洲时间

define('GPC',get_magic_quotes_gpc());		//是否开启自动转义，php.ini中为：magic_quotes_gpc
stripslashes();	//被转义的字符


define('PREV_URL',$_SERVER["HTTP_REFERER"]);	//页面跳转时，保存上一页地址

//自定义网站地址
define('WEB_PATH',dirname($_SERVER['SCRIPT_NAME']).'/'.APP_DIR.'/'.WEBROOT_DIR.'/');
//POST提交控制器变量
define('POST_PATH',$_SERVER['SCRIPT_NAME'].'/');

$_SERVER['QUERY_STRING'];

//print_r(get_included_files());//返回被引用的文件

//php错误日志
ini_get('display_errors');			//获取系统配置信息
ini_set('display_errors', 'off');	//屏蔽页面错误显示
ini_set('log_errors','on');			//开启错误日志
error_reporting(E_ALL);			//输出所有错误日志
error_reporting(E_ERROR | E_WARNING | E_PARSE);	//屏蔽一些错误


ini_set('error_log', 'Project');	//错误日志存放位置

session.gc_maxlifetime	//session过期时间，秒为单位1440秒=24分钟
session.save_path		//SESSION保存路径

//修改执行时间、与最大内存
//memory_limit = 20M
// max_execution_time = 300	//最大运行时间。300秒
//upload_max_filesize = 30M	//
//post_max_size = 30M


//调试bug扩展
zend_extension = "e:/wamp/bin/php/php5.3.13/zend_ext/php_xdebug-2.2.0-5.3-vc9.dll"

//<?php   <?		//支持多种解析
short_open_tag = On


file_get_contents("php://input");		//获取请求的所有数据
$GLOBALS['HTTP_RAW_POST_DATA']   //获取所有请求的数据

?>
