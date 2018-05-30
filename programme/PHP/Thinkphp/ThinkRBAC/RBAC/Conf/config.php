<?php
if (!defined('THINK_PATH'))exit();
//Home分组配置
//数据库
$setdb = array (
	//数据库连接信息
	'DB_TYPE'               => 'mysql',     		// 数据库类型
    'DB_HOST'               => '127.0.0.1', 		// 服务器地址
    'DB_NAME'               => 'rbac',         	 // 数据库名
    'DB_USER'               => 'root',      			// 数据库用户名
    'DB_PWD'                => '',          			// 数据库密码
    'DB_PORT'               => '3306',    		   	 // 端口
    'DB_PREFIX'             => 'rbac_',   		 // 数据库表前缀
    'DB_FIELDTYPE_CHECK'    => false,     // 是否进行字段类型检查
    'DB_FIELDS_CACHE'       => true,        // 启用字段缓存
    'DB_CHARSET'            => 'utf8',    		// 数据库编码默认采用utf8	
    
	'SESSION_AUTO_START' => true,		//开启SESSION
	'URL_MODEL'             => 3,							//URL重写，兼容模式  如：home.php?s=/User/user   或者  home.php/User/user

	'DEFAULT_TIMEZONE'=>'Asia/Shanghai', 	// 设置默认时区
	'VAR_PAGE'=>'page',										//分页类URL标示
	'DB_FIELDS_CACHE'=>false,						//关闭字段缓存
);

//角色权限控制RBAC
$RBAC = array (		
		
		//RBAC设置关系表
		'USER_AUTH_MODEL'           =>  'User',						// 默认查找的用户表
		'RBAC_ACCESS_TABLE'      =>  'rbac_access',			//给组授权，让组有访问某个节点的权限
		'RBAC_NODE_TABLE'           =>  'rbac_node',				//节点表(1项目、2模块、3方法)的关系
		'RBAC_ROLE_TABLE'           =>  'rbac_role',					//组	
		'RBAC_USER_TABLE'           =>  'rbac_role_user',		//用户隶属某个组
				
		'SHOW_PAGE_TRACE'           =>  1,//显示调试信息
		'USER_AUTH_ON'              =>  true,					//开启用户认证
		'USER_AUTH_TYPE'			=>  2,						// 默认认证类型 1 登录认证 2 实时认证
		'USER_AUTH_KEY'             =>  'authId',				// 用户认证SESSION标记（保存用户的id）
		'ADMIN_AUTH_KEY'			=>  'administrator',		//管理员标示,有所有访问权限
		/*
		 *  if($authInfo['account']=='admin') {		//通过这里设置管理员模块
            	$_SESSION['administrator']		=	true;
            }
		 */
		
		'AUTH_PWD_ENCODER'          =>  'md5',	// 用户认证密码加密方式
		'USER_AUTH_GATEWAY'         =>  '/Public/login',	// 默认认证网关,用户无法登陆或验证失败去寻找的方法
		'NOT_AUTH_MODULE'           =>  'Public',				// 默认无需认证模块
		'NOT_AUTH_ACTION'           =>  'image',				// 默认无需认证操作
		'REQUIRE_AUTH_MODULE'       =>  '',			// 默认必须认证方法
		'REQUIRE_AUTH_ACTION'       =>  '',			// 默认必须认证操作		
		'GUEST_AUTH_ON'             =>  false,    			// 是否开启游客授权访问(没有登陆的游客也可以进行访问)
		'GUEST_AUTH_ID'             =>  0,        			// 游客的用户ID
		'DB_LIKE_FIELDS'            =>  'title|remark',	//数据库Like匹配字段
);

//合并数组，返回出去
return array_merge($setdb, $RBAC);
?>