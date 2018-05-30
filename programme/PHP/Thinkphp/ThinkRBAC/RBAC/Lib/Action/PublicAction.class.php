<?php
//公共模块
class PublicAction extends Action {
	
	// 登录检测
	public function checkLogin() {
		if(empty($_POST['username'])) {
			$this->error('帐号错误！');
		}elseif (empty($_POST['password'])){
			$this->error('密码必须！');
		}
		
		//生成认证条件
		$map  =   array();
		$map['username']	= $_POST['username'];	//用户账号
		$map["status"]=	array('gt',0);					//大于0
		import ( 'ORG.Util.RBAC' );
		$authInfo = RBAC::authenticate($map);		//按照条件查找所有用户信息

		//使用用户名、密码和状态的方式进行认证
		if($authInfo === false) {		
			$this->error('帐号不存在或已禁用！');
		}else {
			if($authInfo['password'] != md5($_POST['password'])) {	//Md5验证密码
				$this->error('密码错误！');
			}

			$_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];		//生成用户标识id SESSION
			if($authInfo['username']=='admin') {		//如果是管理员用户
				$_SESSION['administrator']		=	true;		//开启管理员标识，拥有所有访问权限
			}
			
			//更新登录信息
			$User	=	M('User');			//用户表
			$data = array();
			$data['id']	=	$authInfo['id'];
			$data['last_login_time']	=	time();		
			$data['login_count']	=	array('exp','login_count+1');
			$data['last_login_ip']	=	get_client_ip();	
			$User->save($data);
	
			// 缓存访问权限
			RBAC::saveAccessList();
			$this->success('登录成功！',__APP__.'/Index/index');
		}
	}
	
	
	// 用户退出
	public function logout() {
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION[C('USER_AUTH_KEY')]);
			unset($_SESSION);
			session_destroy();
			$this->success('登出成功！',__URL__.'/login/');
		}else {
			$this->error('已经登出！');
		}
	}
	/* (non-PHPdoc)
	 * @see Action::index()
	 */public function index() {
		// TODO Auto-generated method stub
		}

	
}
?>