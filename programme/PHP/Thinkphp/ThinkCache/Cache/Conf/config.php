<?php
//文件缓存配置

return array(
		
	'HTML_CACHE_ON'=>true, // 开启静态缓存
	'HTML_FILE_SUFFIX'  =>  '.shtml', // 设置静态缓存后缀为.shtml
	
	//缓存规则
	'HTML_CACHE_RULES'=> array(
			//定义模块下的所有方法都缓存
			'Index:'            => array('{:module}/{$_SERVER.REQUEST_URI|md5}',5),
			
			//定义模块下某个方法缓存
			'Public:login'            => array('{:module}/{$_SERVER.REQUEST_URI|md5}', 2),
		)
);
?>